<?php
session_start();
include 'db_connect.php';

$filters = [];
$sql = "SELECT * FROM articles WHERE quantite > 0";

// Filtrage par catégorie
if (isset($_GET['categorie']) && !empty($_GET['categorie'])) {
    $filters[] = "categorie = :categorie";
}

// Filtrage par état
if (isset($_GET['etat']) && !empty($_GET['etat'])) {
    $filters[] = "etat = :etat";
}

// Filtrage par prix minimum
if (isset($_GET['prix_min']) && is_numeric($_GET['prix_min'])) {
    $filters[] = "prix >= :prix_min";
}

// Filtrage par prix maximum
if (isset($_GET['prix_max']) && is_numeric($_GET['prix_max'])) {
    $filters[] = "prix <= :prix_max";
}

if ($filters) {
    $sql .= " AND " . implode(" AND ", $filters);
}

try {
    $stmt = $pdo->prepare($sql);

    if (isset($_GET['categorie']) && !empty($_GET['categorie'])) {
        $stmt->bindParam(':categorie', $_GET['categorie']);
    }

    if (isset($_GET['etat']) && !empty($_GET['etat'])) {
        $stmt->bindParam(':etat', $_GET['etat']);
    }

    if (isset($_GET['prix_min']) && is_numeric($_GET['prix_min'])) {
        $stmt->bindParam(':prix_min', $_GET['prix_min']);
    }

    if (isset($_GET['prix_max']) && is_numeric($_GET['prix_max'])) {
        $stmt->bindParam(':prix_max', $_GET['prix_max']);
    }

    $stmt->execute();
    $articles = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tout Parcourir</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <script src="script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>Agora Francia</h1>
        <div class="logo-notification">
            <a href="notifications.html" class="notification-icon"><i class="fas fa-bell"></i></a>
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

    <div class="section">
    <h1>Articles Disponibles</h1>

    <!-- Formulaire de filtre -->
    <form method="get" action="browse.php">
        <label for="categorie">Catégorie:</label>
        <select name="categorie" id="categorie">
            <option value="">Toutes</option>
            <option value="meubles" <?php if(isset($_GET['categorie']) && $_GET['categorie'] == 'meubles') echo 'selected'; ?>>Meubles</option>
            <option value="objets_art" <?php if(isset($_GET['categorie']) && $_GET['categorie'] == 'objets_art') echo 'selected'; ?>>Objets d'art</option>
            <option value="accessoire_vip" <?php if(isset($_GET['categorie']) && $_GET['categorie'] == 'accessoire_vip') echo 'selected'; ?>>Accessoire VIP</option>
            <option value="materiels_scolaires" <?php if(isset($_GET['categorie']) && $_GET['categorie'] == 'materiels_scolaires') echo 'selected'; ?>>Matériels scolaires</option>
        </select>

        <label for="etat">État:</label>
        <select name="etat" id="etat">
            <option value="">Tous</option>
            <option value="neuf avec etiquette" <?php if(isset($_GET['etat']) && $_GET['etat'] == 'neuf avec etiquette') echo 'selected'; ?>>Neuf avec étiquette</option>
            <option value="neuf sans etiquette" <?php if(isset($_GET['etat']) && $_GET['etat'] == 'neuf sans etiquette') echo 'selected'; ?>>Neuf sans étiquette</option>
            <option value="tres bon etat" <?php if(isset($_GET['etat']) && $_GET['etat'] == 'tres bon etat') echo 'selected'; ?>>Très bon état</option>
            <option value="bon etat" <?php if(isset($_GET['etat']) && $_GET['etat'] == 'bon etat') echo 'selected'; ?>>Bon état</option>
            <option value="satisfaisant" <?php if(isset($_GET['etat']) && $_GET['etat'] == 'satisfaisant') echo 'selected'; ?>>Satisfaisant</option>
        </select>

        <label for="prix_min">Prix minimum:</label>
        <input type="number" name="prix_min" id="prix_min" value="<?php echo isset($_GET['prix_min']) ? htmlspecialchars($_GET['prix_min']) : ''; ?>">

        <label for="prix_max">Prix maximum:</label>
        <input type="number" name="prix_max" id="prix_max" value="<?php echo isset($_GET['prix_max']) ? htmlspecialchars($_GET['prix_max']) : ''; ?>">

        <input type="submit" value="Filtrer">
    </form>

    <div class="articles-grid">
        <?php foreach ($articles as $article): ?>
            <div class="article">
                <h3><?php echo htmlspecialchars($article['nom']); ?></h3>
                <p>Prix: <?php echo htmlspecialchars($article['prix']); ?> €</p>
                <?php if ($article['photo']): ?>
                    <a href="article_details.php?id=<?php echo $article['id']; ?>"><img src="<?php echo htmlspecialchars($article['photo']); ?>" alt="Photo de l'article"></a>
                <?php endif; ?>

                <?php if ($article['type_vente'] == 'negociation'): ?>
                        <a href="message.php?vendeur_id=<?php echo $article['vendeur_id']; ?>&article_id=<?php echo $article['id']; ?>">Faire une offre</a>
                        <?php else: ?>
                        <form action="add_to_cart.php" method="post">
                        <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo htmlspecialchars($article['quantite']); ?>">
                        <input type="submit" value="Ajouter au Panier">
                        </form>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>
    </div>
    </div>
    <footer class="footer">
        <p>
            Contactez-nous : <a href="mailto:contact@agorafrancia.fr">contact@agorafrancia.fr</a> | Téléphone : <a href="tel:+33123456789">01 23 45 67 89</a> | Bureau : <a href="https://www.google.fr/maps/place/37+Quai+de+Grenelle,+75015+Paris/@48.8515004,2.2846575,17z/data=!3m1!4b1!4m6!3m5!1s0x47e6700497ee3ec5:0xdd60f514adcdb346!8m2!3d48.8515004!4d2.2872324!16s%2Fg%2F11bw3y1mf8?entry=ttu" target="_blank"><i class="fas fa-map-marker-alt"></i> Localisation</a>
        </p>
    </footer>
</div>
</body>
</html>

























