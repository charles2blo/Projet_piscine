<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

include 'db_connect.php';

// Récupérer les commandes de l'utilisateur
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM commandes WHERE acheteur_id = ?");
$stmt->execute([$user_id]);
$commandes = $stmt->fetchAll();

// Récupérer les articles associés à chaque commande
$articles = [];
if ($commandes) {
    foreach ($commandes as $commande) {
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$commande['article_id']]);
        $article = $stmt->fetch();
        if ($article) {
            $articles[$commande['id']] = $article;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Mes Commandes</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="script.js"></script>
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
<div class="commandes">
    <a href="profile.php" class="btn-retour">Retour</a>
    <?php if ($commandes): ?>
        <?php foreach ($commandes as $commande): ?>
            <div class="commande">
                <h2>Commande #<?php echo htmlspecialchars($commande['id']); ?></h2>
                <p>Date: <?php echo htmlspecialchars($commande['date_commande'] ?? ''); ?></p>
                <p>Prix Total: <?php echo htmlspecialchars($commande['prix_total']); ?> €</p>
                <a href="order_details.php?commande_id=<?php echo $commande['id']; ?>">Voir les détails</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune commande en cours.</p>
    <?php endif; ?>
</div>
</div>
</body>
</html>