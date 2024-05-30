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
    header('Location: chat.php');
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

    // Récupérer les détails de la discussion
    $stmt = $pdo->prepare("
        SELECT 
            m.*, 
            a.nom AS article_nom, 
            u.nom AS vendeur_nom, 
            u.prenom AS vendeur_prenom 
        FROM messagerie m
        JOIN articles a ON m.article_id = a.id
        JOIN utilisateurs u ON m.vendeur_id = u.id
        WHERE m.article_id = ? AND (m.vendeur_id = ? OR m.user_id = ?)
        ORDER BY m.timestamp ASC
    ");
    $stmt->execute([$article_id, $vendeur_id, $user_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
    die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'];
    $action = isset($_POST['action']) ? $_POST['action'] : null;

    if ($action) {
        if ($action == 'accept') {
            $message = "Votre offre a été acceptée. Vous pouvez maintenant ajouter l'article à votre panier.";
        } elseif ($action == 'reject') {
            $message = "Votre offre a été refusée.";
        } elseif ($action == 'counter') {
            $message = "Contre-offre : " . $message;
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO messagerie (user_id, vendeur_id, article_id, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $vendeur_id, $article_id, $message]);
        header("Location: discussion.php?article_id=$article_id&vendeur_id=$vendeur_id");
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
    <title>Discussion - Agora Francia</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
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
        <h2>Discussion sur l'article : <?php echo htmlspecialchars($messages[0]['article_nom']); ?></h2>
        <div class="messages">
            <?php if (count($messages) > 0): ?>
                <?php foreach ($messages as $message): ?>
                    <div class="message">
                        <p><strong><?php echo ($message['user_id'] == $user_id) ? "Vous" : htmlspecialchars($message['vendeur_prenom'] . ' ' . $message['vendeur_nom']); ?> :</strong> <?php echo htmlspecialchars($message['message']); ?></p>
                        <p><small><?php echo htmlspecialchars($message['timestamp']); ?></small></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun message dans cette discussion.</p>
            <?php endif; ?>
        </div>

        <?php if (isset($messages) && end($messages)['message'] == "Votre offre a été acceptée. Vous pouvez maintenant ajouter l'article à votre panier.") : ?>
            <a href="add_to_cart.php?article_id=<?php echo $article_id; ?>" class="btn btn-primary">Ajouter au Panier</a>
        <?php endif; ?>

        <form method="post" action="discussion.php?article_id=<?php echo $article_id; ?>&vendeur_id=<?php echo $vendeur_id; ?>">
            <label for="message">Message :</label>
            <textarea id="message" name="message" required></textarea>
            <input type="submit" value="Envoyer">
            <?php if ($user_id == $vendeur_id): ?>
                <button type="submit" name="action" value="accept">Accepter</button>
                <button type="submit" name="action" value="reject">Refuser</button>
                <button type="submit" name="action" value="counter">Faire une contre-offre</button>
            <?php endif; ?>
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
