<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

$acheteur_id = $_SESSION['user_id'];

try {
    // Si l'appel provient de la discussion (négociation)
    if (isset($_GET['from_discussion']) && isset($_GET['article_id']) && isset($_GET['montant_offre'])) {
        $article_id = $_GET['article_id'];
        $montant_offre = $_GET['montant_offre'];
        $quantite = 1; // Par défaut, 1 pour la négociation

        // Insérer la commande
        $stmt = $pdo->prepare("INSERT INTO commandes (acheteur_id, article_id, quantite, prix_total, date_commande, status, type_transaction) VALUES (?, ?, ?, ?, NOW(), 'en attente', 'negociation')");
        $stmt->execute([$acheteur_id, $article_id, $quantite, $montant_offre]);
        $commande_id = $pdo->lastInsertId();

        // Mettre à jour la quantité de l'article
        $stmt = $pdo->prepare("UPDATE articles SET quantite = quantite - ? WHERE id = ?");
        $stmt->execute([$quantite, $article_id]);

        // Rediriger vers la page de confirmation de commande
        header("Location: order_success.php?id=$commande_id");
        exit;
    }

    // Récupérez les articles dans le panier (achat immédiat)
    $stmt = $pdo->prepare("SELECT p.id, p.quantite, a.id as article_id, a.nom, a.prix FROM panier p JOIN articles a ON p.article_id = a.id WHERE p.acheteur_id = ?");
    $stmt->execute([$acheteur_id]);
    $panier = $stmt->fetchAll();

    // Vérifiez les articles récupérés
    if (!$panier) {
        throw new Exception("Panier vide ou articles invalides");
    }

    // Calculer le total
    $total = 0;
    foreach ($panier as $article) {
        $total += $article['prix'] * $article['quantite'];
    }

    // Récupérer les cartes enregistrées
    $stmt = $pdo->prepare("SELECT * FROM cartes WHERE utilisateur_id = ?");
    $stmt->execute([$acheteur_id]);
    $cartes = $stmt->fetchAll();

    // Récupérer les adresses enregistrées
    $stmt = $pdo->prepare("SELECT * FROM adresses WHERE utilisateur_id = ?");
    $stmt->execute([$acheteur_id]);
    $adresses = $stmt->fetchAll();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $selected_card_id = $_POST['card_id'];
        $adresse_id = isset($_POST['adresse_id']) ? $_POST['adresse_id'] : null;

        if ($selected_card_id == "new_card") {
            $type_carte = $_POST['type_carte'];
            $numero_carte = $_POST['numero_carte'];
            $nom_carte = $_POST['nom_carte'];
            $expiration = $_POST['expiration'];
            $code_securite = $_POST['code_securite'];

            $stmt = $pdo->prepare("INSERT INTO cartes (utilisateur_id, type_carte, numero_carte, nom_carte, expiration, code_securite) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$acheteur_id, $type_carte, $numero_carte, $nom_carte, $expiration, $code_securite]);

            $selected_card_id = $pdo->lastInsertId();
        }

        // Gestion de l'adresse de livraison
        if ($adresse_id == "new_address") {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $adresse_ligne1 = $_POST['adresse_ligne1'];
            $adresse_ligne2 = $_POST['adresse_ligne2'];
            $ville = $_POST['ville'];
            $code_postal = $_POST['code_postal'];
            $pays = $_POST['pays'];
            $numero_telephone = $_POST['numero_telephone'];

            $stmt = $pdo->prepare("INSERT INTO adresses (utilisateur_id, nom, prenom, adresse_ligne1, adresse_ligne2, ville, code_postal, pays, numero_telephone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$acheteur_id, $nom, $prenom, $adresse_ligne1, $adresse_ligne2, $ville, $code_postal, $pays, $numero_telephone]);

            $adresse_id = $pdo->lastInsertId();
        } else {
            $stmt = $pdo->prepare("SELECT adresse_ligne1, adresse_ligne2, ville, code_postal, pays, numero_telephone FROM adresses WHERE id = ?");
            $stmt->execute([$adresse_id]);
            $adresse = $stmt->fetch(PDO::FETCH_ASSOC);

            $nom = $adresse['nom'];
            $prenom = $adresse['prenom'];
            $adresse_ligne1 = $adresse['adresse_ligne1'];
            $adresse_ligne2 = $adresse['adresse_ligne2'];
            $ville = $adresse['ville'];
            $code_postal = $adresse['code_postal'];
            $pays = $adresse['pays'];
            $numero_telephone = $adresse['numero_telephone'];
        }

        // Insérer la commande
        $stmt = $pdo->prepare("INSERT INTO commandes (acheteur_id, prix_total, date_commande, status, adresse_livraison) VALUES (?, ?, NOW(), 'en attente', ?)");
        $stmt->execute([$acheteur_id, $total, json_encode(compact('nom', 'prenom', 'adresse_ligne1', 'adresse_ligne2', 'ville', 'code_postal', 'pays', 'numero_telephone'))]);
        $commande_id = $pdo->lastInsertId();

        // Insérer les articles de la commande
        foreach ($panier as $article) {
            $article_id = $article['article_id'];
            $quantite = $article['quantite'];

            // Vérifiez que l'article existe
            $stmt = $pdo->prepare("SELECT id FROM articles WHERE id = ?");
            $stmt->execute([$article_id]);
            $articleExists = $stmt->fetch();

            if ($articleExists) {
                $stmt = $pdo->prepare("INSERT INTO commandes_articles (commande_id, article_id, quantite) VALUES (?, ?, ?)");
                $stmt->execute([$commande_id, $article_id, $quantite]);

                // Mettre à jour la quantité de l'article
                $stmt = $pdo->prepare("UPDATE articles SET quantite = quantite - ? WHERE id = ?");
                $stmt->execute([$quantite, $article_id]);
            } else {
                throw new Exception("L'article avec l'ID $article_id n'existe pas.");
            }
        }

        // Vider le panier
        $stmt = $pdo->prepare("DELETE FROM panier WHERE acheteur_id = ?");
        $stmt->execute([$acheteur_id]);

        // Rediriger vers la page des commandes avec le numéro de commande
        header("Location: order_success.php?id=" . $commande_id);
        exit;
    }
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Procéder au Paiement</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>Agora Francia</h1>
        <img src="logo.png" width="100" height="100" alt="logoAgora">
    </div>
    <div class="navigation">
        <a href="index.html"><i class="fas fa-home"></i> Accueil</a>
        <a href="browse.php"><i class="fas fa-th-list"></i> Tout Parcourir</a>
        <a href="notifications.html"><i class="fas fa-bell"></i> Notifications</a>
        <a href="cart.php"><i class="fas fa-shopping-cart"></i> Panier</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="publish_article.php">Publier un article</a>
        <?php endif; ?>
        <div class="dropdown">
            <a href="#votrecompte" class="dropbtn"><i class="fas fa-user"></i> Votre Compte</a>
            <div class="dropdown-content">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="profile.php">Mon Profil</a>
                    <a href="logout.php">Se Déconnecter</a>
                <?php else: ?>
                    <a href="#" id="login-btn">Se connecter</a>
                    <a href="#" id="signup-btn">S'inscrire</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <h2>Procéder au Paiement</h2>
    <form action="checkout.php" method="post">
        <h3>Sélectionnez un moyen de paiement</h3>
        <?php if (!empty($cartes)): ?>
            <?php foreach ($cartes as $carte): ?>
                <div>
                    <input type="radio" id="carte_<?php echo $carte['id']; ?>" name="card_id" value="<?php echo $carte['id']; ?>">
                    <label for="carte_<?php echo $carte['id']; ?>"><?php echo htmlspecialchars($carte['type_carte']); ?>: **** **** **** <?php echo htmlspecialchars(substr($carte['numero_carte'], -4)); ?></label>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune carte enregistrée. Veuillez ajouter une nouvelle carte.</p>
        <?php endif; ?>
        <div>
            <input type="radio" id="new_card" name="card_id" value="new_card">
            <label for="new_card">Ajouter une nouvelle carte</label>
        </div>
        <div id="new_card_details" style="display: none;">
            <label for="type_carte">Type de carte:</label>
            <select name="type_carte" id="type_carte">
                <option value="Visa">Visa</option>
                <option value="MasterCard">MasterCard</option>
                <option value="AmericanExpress">American Express</option>
                <option value="PayPal">PayPal</option>
            </select><br>
            <label for="numero_carte">Numéro de carte:</label>
            <input type="text" name="numero_carte" id="numero_carte"><br>
            <label for="nom_carte">Nom sur la carte:</label>
            <input type="text" name="nom_carte" id="nom_carte"><br>
            <label for="expiration">Date d'expiration:</label>
            <input type="text" name="expiration" id="expiration" placeholder="MM/YY"><br>
            <label for="code_securite">Code de sécurité:</label>
            <input type="text" name="code_securite" id="code_securite"><br>
        </div>
        <h3>Adresse de livraison</h3>
        <?php if (!empty($adresses)): ?>
            <?php foreach ($adresses as $adresse): ?>
                <div>
                    <input type="radio" id="adresse_<?php echo $adresse['id']; ?>" name="adresse_id" value="<?php echo $adresse['id']; ?>">
                    <label for="adresse_<?php echo $adresse['id']; ?>"><?php echo htmlspecialchars($adresse['adresse_ligne1'] . ', ' . $adresse['ville'] . ', ' . $adresse['code_postal'] . ', ' . $adresse['pays']); ?></label>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune adresse enregistrée. Veuillez ajouter une nouvelle adresse.</p>
        <?php endif; ?>
        <div>
            <input type="radio" id="new_address" name="adresse_id" value="new_address">
            <label for="new_address">Ajouter une nouvelle adresse</label>
        </div>
        <div id="new_address_details" style="display: none;">
            <label for="nom">Nom:</label>
            <input type="text" name="nom" id="nom" required><br>
            <label for="prenom">Prénom:</label>
            <input type="text" name="prenom" id="prenom" required><br>
            <label for="adresse_ligne1">Adresse Ligne 1:</label>
            <input type="text" name="adresse_ligne1" id="adresse_ligne1" required><br>
            <label for="adresse_ligne2">Adresse Ligne 2:</label>
            <input type="text" name="adresse_ligne2" id="adresse_ligne2"><br>
            <label for="ville">Ville:</label>
            <input type="text" name="ville" id="ville" required><br>
            <label for="code_postal">Code Postal:</label>
            <input type="text" name="code_postal" id="code_postal" required><br>
            <label for="pays">Pays:</label>
            <input type="text" name="pays" id="pays" required><br>
            <label for="numero_telephone">Numéro de téléphone:</label>
            <input type="text" name="numero_telephone" id="numero_telephone" required><br>
        </div>
        <input type="submit" value="Payer">
    </form>
</div>
<script>
    document.getElementById('new_card').addEventListener('change', function() {
        document.getElementById('new_card_details').style.display = 'block';
        document.getElementById('type_carte').required = true;
        document.getElementById('numero_carte').required = true;
        document.getElementById('nom_carte').required = true;
        document.getElementById('expiration').required = true;
        document.getElementById('code_securite').required = true;
    });

    var existingCards = document.querySelectorAll('input[name="card_id"]:not(#new_card)');
    existingCards.forEach(function(card) {
        card.addEventListener('change', function() {
            document.getElementById('new_card_details').style.display = 'none';
            document.getElementById('type_carte').required = false;
            document.getElementById('numero_carte').required = false;
            document.getElementById('nom_carte').required = false;
            document.getElementById('expiration').required = false;
            document.getElementById('code_securite').required = false;
        });
    });

    document.getElementById('new_address').addEventListener('change', function() {
        document.getElementById('new_address_details').style.display = 'block';
        document.getElementById('nom').required = true;
        document.getElementById('prenom').required = true;
        document.getElementById('adresse_ligne1').required = true;
        document.getElementById('ville').required = true;
        document.getElementById('code_postal').required = true;
        document.getElementById('pays').required = true;
        document.getElementById('numero_telephone').required = true;
    });

    var existingAddresses = document.querySelectorAll('input[name="adresse_id"]:not(#new_address)');
    existingAddresses.forEach(function(address) {
        address.addEventListener('change', function() {
            document.getElementById('new_address_details').style.display = 'none';
            document.getElementById('nom').required = false;
            document.getElementById('prenom').required = false;
            document.getElementById('adresse_ligne1').required = false;
            document.getElementById('ville').required = false;
            document.getElementById('code_postal').required = false;
            document.getElementById('pays').required = false;
            document.getElementById('numero_telephone').required = false;
        });
    });
</script>
</body>
</html>
