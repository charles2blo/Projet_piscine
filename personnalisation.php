<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

include 'db_connect.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $theme = $_POST['theme'];

    // Vérifiez si l'utilisateur a déjà un thème défini
    $stmt = $pdo->prepare("SELECT * FROM themes WHERE utilisateur_id = ?");
    $stmt->execute([$user_id]);
    $existingTheme = $stmt->fetch();

    if ($existingTheme) {
        // Mettre à jour le thème existant
        $stmt = $pdo->prepare("UPDATE themes SET theme = ? WHERE utilisateur_id = ?");
        $stmt->execute([$theme, $user_id]);
    } else {
        // Insérer un nouveau thème
        $stmt = $pdo->prepare("INSERT INTO themes (utilisateur_id, theme) VALUES (?, ?)");
        $stmt->execute([$user_id, $theme]);
    }

    echo "Thème mis à jour avec succès !";
}

// Récupérer le thème actuel de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM themes WHERE utilisateur_id = ?");
$stmt->execute([$user_id]);
$currentTheme = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Personnalisation du Thème</title>
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
                <a href="profile.php">Mon Profil</a>
                <a href="logout.php">Se Déconnecter</a>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Personnalisation du Thème</h2>
        <form method="post">
            <label for="theme">Choisissez un thème:</label>
            <select name="theme" id="theme">
                <option value="clair" <?php echo ($currentTheme && $currentTheme['theme'] == 'clair') ? 'selected' : ''; ?>>Clair</option>
                <option value="sombre" <?php echo ($currentTheme && $currentTheme['theme'] == 'sombre') ? 'selected' : ''; ?>>Sombre</option>
            </select>
            <button type="submit">Enregistrer</button>
        </form>
    </div>
</div>
</body>
</html>
