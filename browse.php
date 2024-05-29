<?php
session_start();
include 'db_connect.php';

// Initialiser les valeurs de filtre par défaut
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : 10000;
$category = isset($_GET['category']) ? $_GET['category'] : '';
$etat = isset($_GET['etat']) ? $_GET['etat'] : '';

// Récupérer les catégories uniques depuis la base de données
try {
    $stmt = $pdo->query("SELECT DISTINCT categorie FROM articles");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
    die();
}

// Liste des états disponibles
$etats = [
    'neuf avec etiquette',
    'neuf sans etiquette',
    'tres bon etat',
    'bon etat',
    'satisfaisant'
];

// Construire la requête SQL avec les filtres
$query = "SELECT * FROM articles WHERE quantite > 0 AND prix BETWEEN ? AND ?";
$params = [$min_price, $max_price];

if ($category != '') {
    $query .= " AND categorie = ?";
    $params[] = $category;
}

if ($etat != '') {
    $query .= " AND etat = ?";
    $params[] = $etat;
}

// Récupérer les articles filtrés de la base de données
try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <h2>Articles Disponibles</h2>

        <!-- Formulaire de filtre -->
        <form method="GET" action="browse.php" class="filter-form">
            <label for="min_price">Prix minimum:</label>
            <input type="number" id="min_price" name="min_price" value="<?php echo htmlspecialchars($min_price); ?>">

            <label for="max_price">Prix maximum:</label>
            <input type="number" id="max_price" name="max_price" value="<?php echo htmlspecialchars($max_price); ?>">

            <label for="category">Catégorie:</label>
            <select id="category" name="category">
                <option value="">Toutes</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat['categorie']); ?>" <?php if ($category == $cat['categorie']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($cat['categorie']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="etat">État:</label>
            <select id="etat" name="etat">
                <option value="">Tous</option>
                <?php foreach ($etats as $et): ?>
                    <option value="<?php echo htmlspecialchars($et); ?>" <?php if ($etat == $et) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($et); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="submit" value="Filtrer">
        </form>

        <div class="articles-grid">
            <?php foreach ($articles as $article): ?>
                <div class="article">
                    <h3><?php echo htmlspecialchars($article['nom']); ?></h3>
                    <p><?php echo htmlspecialchars($article['description']); ?></p>
                    <p>Prix: <?php echo htmlspecialchars($article['prix']); ?> €</p>
                    <p>Quantité: <?php echo htmlspecialchars($article['quantite']); ?></p>
                    <p>Catégorie: <?php echo htmlspecialchars($article['categorie']); ?></p>
                    <p>État: <?php echo htmlspecialchars($article['etat']); ?></p>
                    <?php if ($article['photo']): ?>
                        <img src="<?php echo htmlspecialchars($article['photo']); ?>" alt="Photo de l'article">
                    <?php endif; ?>
                    <form action="add_to_cart.php" method="post">
                        <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo htmlspecialchars($article['quantite']); ?>">
                        <input type="submit" value="Ajouter au Panier">
                    </form>
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
