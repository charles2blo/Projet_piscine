<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

$acheteur_id = $_SESSION['user_id'];

try {
    // Récupérez les articles dans le panier
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
    $addresses = $stmt->fetchAll();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Adresse de livraison
        $delivery_type = $_POST['delivery_type'];
        if ($delivery_type == 'existing') {
            $selected_address_id = $_POST['address_id'];
            $stmt = $pdo->prepare("SELECT * FROM adresses WHERE id = ? AND utilisateur_id = ?");
            $stmt->execute([$selected_address_id, $acheteur_id]);
            $address = $stmt->fetch();
        } else {
            $address = [
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'adresse_ligne1' => $_POST['adresse_ligne1'],
                'adresse_ligne2' => $_POST['adresse_ligne2'],
                'ville' => $_POST['ville'],
                'code_postal' => $_POST['code_postal'],
                'pays' => $_POST['pays'],
                'numero_telephone' => $_POST['numero_telephone']
            ];
        }

        // Insérer la commande
        $stmt = $pdo->prepare("INSERT INTO commandes (acheteur_id, prix_total, date_commande, status, adresse_livraison) VALUES (?, ?, NOW(), 'en attente', ?)");
        $adresse_livraison = $address['nom'] . ' ' . $address['prenom'] . ', ' . $address['adresse_ligne1'] . ', ' . $address['adresse_ligne2'] . ', ' . $address['ville'] . ', ' . $address['code_postal'] . ', ' . $address['pays'] . ', ' . $address['numero_telephone'];
        $stmt->execute([$acheteur_id, $total, $adresse_livraison]);
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
        <div class="logo-notification">
            <a href="notifications.php" class="notification-icon"><i class="fas fa-bell"></i></a>
            <img src="logo.png" width="100" height="100" alt="logoAgora">
        </div>
    </div>
    <div class="navigation">
        <a href="index.html"><i class="fas fa-home"></i> Accueil</a>
        <a href="browse.php"><i class="fas fa-th-list"></i> Tout Parcourir</a>
        <a href="chat.php"><i class="fas fa-comments"></i> Chat</a>
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
        <label>
            <input type="radio" name="delivery_type" value="existing" checked> Utiliser une adresse enregistrée
        </label>
        <select name="address_id">
            <?php foreach ($addresses as $address): ?>
                <option value="<?php echo $address['id']; ?>">
                    <?php echo htmlspecialchars($address['nom'] . ' ' . $address['prenom'] . ', ' . $address['adresse_ligne1'] . ', ' . $address['ville']); ?>
                </option>
            <?php endforeach; ?>
        </select>
<br>
        <label>
            <input type="radio" name="delivery_type" value="new"> Saisir une nouvelle adresse
        </label>
        <div id="new-address" style="display: none;">
            <input type="text" name="nom" placeholder="Nom"><br>
            <input type="text" name="prenom" placeholder="Prénom"><br>
            <input type="text" name="adresse_ligne1" placeholder="Adresse Ligne 1"><br>
            <input type="text" name="adresse_ligne2" placeholder="Adresse Ligne 2 (optionnel)"><br>
            <input type="text" name="ville" placeholder="Ville"><br>
            <input type="text" name="code_postal" placeholder="Code Postal"><br>
            <input type="text" name="pays" placeholder="Pays"><br>
            <input type="text" name="numero_telephone" placeholder="Numéro de téléphone"><br>
        </div>

        <p><input type="submit" value="Payer"></p>
    </form>
</div>
<script>
    document.querySelector('input[name="delivery_type"][value="existing"]').addEventListener('change', function() {
        document.getElementById('new-address').style.display = 'none';
    });
    document.querySelector('input[name="delivery_type"][value="new"]').addEventListener('change', function() {
        document.getElementById('new-address').style.display = 'block';
    });

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
</script>
</body>
</html>

