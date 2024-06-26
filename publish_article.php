<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

include 'db_connect.php';

// Récupérer les informations de l'utilisateur
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT type_utilisateur FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($user['type_utilisateur'] != 'vendeur' && $user['type_utilisateur'] != 'admin') {
    echo "<script>alert('Vous n avez pas la permission de publier une annonce.'); window.history.back();</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $categorie = $_POST['categorie'];
    $prix = $_POST['prix'];
    $quantite = $_POST['quantite'];
    $type_vente = $_POST['type_vente'];
    $etat = $_POST['etat'];
    $vendeur_id = $_SESSION['user_id'];
    $video = $_POST['video'];

    // Gestion de l'upload de la photo
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $photo = $target_file;
        } else {
            echo "Erreur lors du téléchargement de l'image.";
            exit;
        }
    } else {
        $photo = "";
    }

    try {
        // Définir la date de fin de l'enchère si le type de vente est 'enchere'
        if ($type_vente == 'enchere') {
            $now = new DateTime();
            $date_fin = $now->modify('+5 minutes')->format('Y-m-d H:i:s');
        } else {
            $date_fin = null;
        }

        $stmt = $pdo->prepare("INSERT INTO articles (nom, description, categorie, prix, quantite, type_vente, etat, vendeur_id, photo, video, date_fin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $description, $categorie, $prix, $quantite, $type_vente, $etat, $vendeur_id, $photo, $video, $date_fin]);

        header("Location: profile.php");
        exit();
    } catch (PDOException $e) {
        echo "Erreur lors de la publication de l'article: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publier un article - Agora Francia</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
    <div class="section">
        <h2>Publier un nouvel article</h2>
        <form action="publish_article.php" method="post" enctype="multipart/form-data">
            <label for="nom">Nom de l'article:</label><br>
            <input type="text" id="nom" name="nom" required><br>
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" required></textarea><br>
            <label for="categorie">Catégorie:</label><br>
            <select id="categorie" name="categorie" required>
                <option value="meubles">Meubles</option>
                <option value="objets_art">Objets d'art</option>
                <option value="accessoire_vip">Accessoire VIP</option>
                <option value="materiels_scolaires">Matériels scolaires</option>
            </select><br>
            <label for="prix">Prix:</label><br>
            <input type="number" id="prix" name="prix" step="0.01" required><br>
            <label for="quantite">Quantité:</label><br>
            <input type="number" id="quantite" name="quantite" required><br>
            <label for="type_vente">Type de vente:</label><br>
            <select id="type_vente" name="type_vente" required>
                <option value="immediate">Achat immédiat</option>
                <option value="negociation">Négociation</option>
                <option value="enchere">Enchère</option>
            </select><br>
            <label for="etat">État:</label><br>
            <select id="etat" name="etat" required>
                <option value="neuf avec etiquette">Neuf avec etiquette</option>
                <option value="neuf sans etiquette">Neuf sans etiquette</option>
                <option value="tres bon etat">Très bon état</option>
                <option value="bon etat">Bon état</option>
                <option value="satisfaisant">Satisfaisant</option>
            </select><br>
            <label for="photo">Photo:</label><br>
            <input type="file" id="photo" name="photo" required><br>
            <label for="video">Vidéo URL (optionnel):</label><br>
            <input type="text" id="video" name="video"><br>
            <input type="submit" value="Publier">
        </form>
    </div>
    <footer class="footer">
        <p>Contactez-nous : <a href="mailto:contact@agorafrancia.fr">contact@agorafrancia.fr</a> | Téléphone : <a href="tel:+33123456789">01 23 45 67 89</a> | <a href="https://www.google.fr/maps/place/37+Quai+de+Grenelle,+75015+Paris/@48.8515004,2.2846575,17z/data=!3m1!4b1!4m6!3m5!1s0x47e6700497ee3ec5:0xdd60f514adcdb346!8m2!3d48.8515004!4d2.2872324!16s%2Fg%2F11bw3y1mf8?entry=ttu" target="_blank"><i class="fas fa-map-marker-alt"></i> bureaux</a></p>
    </footer>
</div>
</body>
</html>
