<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les discussions de l'utilisateur
try {
    $stmt = $pdo->prepare("
        SELECT 
            m.article_id, 
            a.nom AS article_nom, 
            u.id AS autre_user_id,
            u.nom AS autre_user_nom, 
            u.prenom AS autre_user_prenom,
            CASE 
                WHEN m.user_id = ? THEN (SELECT nom FROM utilisateurs WHERE id = m.vendeur_id)
                ELSE (SELECT nom FROM utilisateurs WHERE id = m.user_id)
            END AS autre_user_nom
        FROM messagerie m
        JOIN articles a ON m.article_id = a.id
        JOIN utilisateurs u ON (m.user_id = u.id OR m.vendeur_id = u.id)
        WHERE m.user_id = ? OR m.vendeur_id = ?
        GROUP BY m.article_id, autre_user_nom
    ");
    $stmt->execute([$user_id, $user_id, $user_id]);
    $discussions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Chat - Agora Francia</title>
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
        <h2>Mes Discussions</h2>
        <div class="messages">
            <?php if (count($discussions) > 0): ?>
                <?php foreach ($discussions as $discussion): ?>
                    <div class="discussion">
                        <p><strong>Article :</strong> <?php echo htmlspecialchars($discussion['article_nom']); ?></p>
                        <p><strong><?php echo ($user_id == $discussion['autre_user_id']) ? "Vendeur" : "Acheteur"; ?> :</strong> 
                        <a href="discussion.php?article_id=<?php echo $discussion['article_id']; ?>&vendeur_id=<?php echo $discussion['autre_user_id']; ?>">
                        <?php echo htmlspecialchars($discussion['autre_user_prenom'] . ' ' . $discussion['autre_user_nom']); ?></a></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Vous n'avez pas encore de discussions.</p>
            <?php endif; ?>
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
