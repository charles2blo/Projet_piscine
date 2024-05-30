<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}
include 'db_connect.php';

// Récupérer les informations de l'utilisateur
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($user['type_utilisateur'] !== 'admin') {
    header('Location: profile.php');
    exit;
}

// Récupérer les vendeurs
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE type_utilisateur = 'vendeur'");
$stmt->execute();
$vendeurs = $stmt->fetchAll();

// Gestion de la suppression du statut vendeur
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_seller'])) {
    $vendeur_id = $_POST['vendeur_id'];
    $stmt = $pdo->prepare("UPDATE utilisateurs SET type_utilisateur = 'acheteur' WHERE id = ?");
    $stmt->execute([$vendeur_id]);
    header('Location: manage_sellers.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Vendeurs - Agora Francia</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <script src="script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .seller-catalog {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .seller-item {
            flex: 1 1 calc(20% - 20px); /* Ajustement pour avoir 5 éléments par ligne avec un gap de 20px */
            box-sizing: border-box;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            background-color: #f9f9f9;
        }
        .seller-item img {
            max-width: 100px;
            height: auto;
            border-radius: 50%;
        }
        .btn-retour {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-retour:hover {
            background-color: #45a049;
        }
    </style>
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
        <a href="profile.php"><i class="fas fa-user"></i> Mon Profil</a>
    </div>
    <div class="section">
        <h2>Gestion des Vendeurs</h2>
        <a href="profile.php" class="btn-retour">Retour</a>
        <?php if ($vendeurs): ?>
            <div class="seller-catalog">
                <?php foreach ($vendeurs as $vendeur): ?>
                    <div class="seller-item">
                        <img src="<?php echo htmlspecialchars($vendeur['photo']); ?>" alt="Photo de profil">
                        <div class="seller-details">
                            <p><strong>Nom :</strong> <?php echo htmlspecialchars($vendeur['nom']); ?></p>
                            <p><strong>Prénom :</strong> <?php echo htmlspecialchars($vendeur['prenom']); ?></p>
                            <p><strong>Email :</strong> <?php echo htmlspecialchars($vendeur['email']); ?></p>
                            <p>Voulez-vous supprimer son statut "vendeur" ?</p>
                            <form method="post">
                                <input type="hidden" name="vendeur_id" value="<?php echo $vendeur['id']; ?>">
                                <button type="submit" name="remove_seller" value="oui" class="btn btn-danger">Oui</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucun vendeur trouvé.</p>
        <?php endif; ?>
    </div>
    <footer class="footer">
        <p>Contactez-nous : <a href="mailto:contact@agorafrancia.fr">contact@agorafrancia.fr</a> | Téléphone : <a href="tel:+33123456789">01 23 45 67 89</a> | Bureau : <a href="https://www.google.fr/maps/place/37+Quai+de+Grenelle,+75015+Paris/@48.8515004,2.2846575,17z/data=!3m1!4b1!4m6!3m5!1s0x47e6700497ee3ec5:0xdd60f514adcdb346!8m2!3d48.8515004!4d2.2872324!16s%2Fg%2F11bw3y1mf8?entry=ttu" target="_blank"><i class="fas fa-map-marker-alt"></i> Localisation</a></p>
    </footer>
</div>
</body>
</html>
