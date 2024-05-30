<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

include 'db_connect.php';

// Get user information
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($user['type_utilisateur'] != 'admin') {
    echo "Vous n'avez pas la permission d'accéder à cette page.";
    exit;
}

// Process approval or rejection of requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['approve'])) {
        $request_user_id = $_POST['user_id'];
        $stmt = $pdo->prepare("UPDATE utilisateurs SET type_utilisateur = 'vendeur' WHERE id = ?");
        $stmt->execute([$request_user_id]);
        echo "Demande approuvée.";
    } elseif (isset($_POST['reject'])) {
        $request_user_id = $_POST['user_id'];
        $notification_message = "Votre demande de devenir vendeur a été refusée.";
        $stmt = $pdo->prepare("INSERT INTO notifications (utilisateur_id, message) VALUES (?, ?)");
        $stmt->execute([$request_user_id, $notification_message]);
        echo "Demande refusée.";
    }
}

// Get notifications
$stmt = $pdo->prepare("SELECT n.id, n.utilisateur_id, n.message, n.date_notification, u.prenom, u.nom 
                       FROM notifications n
                       JOIN utilisateurs u ON n.utilisateur_id = u.id
                       WHERE n.lu = 0");
$stmt->execute();
$notifications = $stmt->fetchAll();

// Mark notifications as read
$stmt = $pdo->prepare("UPDATE notifications SET lu = 1 WHERE lu = 0");
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Agora Francia</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        <a href="notifications.php"><i class="fas fa-bell"></i> Notifications</a>
        <a href="cart.php"><i class="fas fa-shopping-cart"></i> Panier</a>
        <div class="dropdown">
            <a href="#votrecompte" class="dropbtn"><i class="fas fa-user"></i> Votre Compte</a>
            <div class="dropdown-content">
                <a href="profile.php">Mon Profil</a>
                <a href="logout.php">Se Déconnecter</a>
            </div>
        </div>
    </div>
    <div class="section">
        <h2>Notifications</h2>
        <?php if ($notifications): ?>
            <ul>
                <?php foreach ($notifications as $notification): ?>
                    <li>
                        <?php echo htmlspecialchars($notification['message']); ?> (<?php echo htmlspecialchars($notification['date_notification']); ?>)<br>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($notification['utilisateur_id']); ?>">
                            <button type="submit" name="approve" class="btn btn-success">Accepter sa demande</button>
                            <button type="submit" name="reject" class="btn btn-danger">Refuser sa demande</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucune nouvelle notification.</p>
        <?php endif; ?>
    </div>
    <footer class="footer">
        <p>Contactez-nous : <a href="mailto:contact@agorafrancia.fr">contact@agorafrancia.fr</a> | Téléphone : <a href="tel:+33123456789">01 23 45 67 89</a> | <a href="https://www.google.fr/maps/place/37+Quai+de+Grenelle,+75015+Paris/@48.8515004,2.2846575,17z/data=!3m1!4b1!4m6!3m5!1s0x47e6700497ee3ec5:0xdd60f514adcdb346!8m2!3d48.8515004!4d2.2872324!16s%2Fg%2F11bw3y1mf8?entry=ttu" target="_blank"><i class="fas fa-map-marker-alt"></i> bureaux</a></p>
    </footer>
</div>
</body>
</html>
