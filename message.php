<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$vendeur_id = isset($_GET['vendeur_id']) ? $_GET['vendeur_id'] : null;
$article_id = isset($_GET['article_id']) ? $_GET['article_id'] : null;

if (!$vendeur_id || !$article_id) {
    header('Location: browse.php');
    exit();
}

try {
    // Vérifier si l'article et les utilisateurs existent
    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE id = ?");
    $stmt->execute([$vendeur_id]);
    if ($stmt->rowCount() == 0) {
        throw new Exception("Vendeur non trouvé");
    }

    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE id = ?");
    $stmt->execute([$user_id]);
    if ($stmt->rowCount() == 0) {
        throw new Exception("Utilisateur non trouvé");
    }

    $stmt = $pdo->prepare("SELECT id FROM articles WHERE id = ?");
    $stmt->execute([$article_id]);
    if ($stmt->rowCount() == 0) {
        throw new Exception("Article non trouvé");
    }

    // Récupérer les détails de l'article et du vendeur
    $stmt = $pdo->prepare("SELECT a.nom AS article_nom, a.prix AS article_prix, u.nom AS vendeur_nom, u.prenom AS vendeur_prenom FROM articles a JOIN utilisateurs u ON a.vendeur_id = u.id WHERE a.id = ?");
    $stmt->execute([$article_id]);
    $article = $stmt->fetch();

    if (!$article) {
        throw new Exception("Article ou vendeur non trouvé");
    }
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
    die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $proposed_price = $_POST['proposed_price'];
    $message = $_POST['message'];
    try {
        $stmt = $pdo->prepare("INSERT INTO messagerie (user_id, vendeur_id, article_id, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $vendeur_id, $article_id, "Prix proposé: " . $proposed_price . " € - Message: " . $message]);
        header('Location: chat.php');
        exit();
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
        die();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Envoyer une offre</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
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
        <h2>Faire une offre</h2>
        <div class="article-info">
            <p><strong>Nom de l'article :</strong> <?php echo htmlspecialchars($article['article_nom']); ?></p>
            <p><strong>Prix de l'article :</strong> <?php echo htmlspecialchars($article['article_prix']); ?> €</p>
            <p><strong>Vendeur :</strong> <?php echo htmlspecialchars($article['vendeur_prenom'] . ' ' . $article['vendeur_nom']); ?></p>
        </div>
        <form method="post" action="message.php?vendeur_id=<?php echo $vendeur_id; ?>&article_id=<?php echo $article_id; ?>">
            <label for="proposed_price">Prix proposé (€) :</label>
            <input type="number" id="proposed_price" name="proposed_price" required>
            <label for="message">Message :</label>
            <textarea id="message" name="message" required></textarea>
            <input type="submit" value="Envoyer">
        </form>
    </div>

    <footer class="footer">
        <p>
            Contactez-nous : <a href="mailto:contact@agorafrancia.fr">contact@agorafrancia.fr</a> | Téléphone : <a href="tel:+33123456789">01 23 45 67 89</a> | Bureau : <a href="https://www.google.fr/maps/place/37+Quai+de+Grenelle,+75015+Paris/@48.8515004,2.2846575,17z/data=!3m1!4b1!4m6!3m5!1s0x47e6700497ee3ec5:0xdd60f514adcdb346!8m2!3d48.8515004!4d2.2872324!16s%2Fg%2F11bw3y1mf8?entry=ttu" target="_blank"><i class="fas fa-map-marker-alt"></i> Localisation</a>
        </p>
    </footer>
</div>
</body>
</html>

