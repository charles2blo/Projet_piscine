<?php
session_start();
include 'db_connect.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les articles de l'utilisateur connecté
try {
    $stmt = $pdo->prepare("SELECT id, nom, description, photo, prix FROM articles WHERE vendeur_id = ?");
    $stmt->execute([$user_id]);
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Annonces - Agora Francia</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
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
        <a href="notifications.html"><i class="fas fa-bell"></i> Notifications</a>
        <a href="cart.php"><i class="fas fa-shopping-cart"></i> Panier</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="publish_article.php">Publier un article</a>
            <a href="mes_annonces.php">Mes annonces</a>
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
        <h2>Mes Annonces</h2>
        <div class="catalogue">
            <?php if (count($articles) > 0): ?>
                <?php foreach ($articles as $article): ?>
                    <div class="article">
                        <img src="<?php echo htmlspecialchars($article['photo']); ?>" alt="<?php echo htmlspecialchars($article['nom']); ?>">
                        <h3><?php echo htmlspecialchars($article['nom']); ?></h3>
                        <p><?php echo htmlspecialchars($article['description']); ?></p>
                        <p><?php echo htmlspecialchars($article['prix']); ?> €</p>
                        <form action="delete_article.php" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">
                            <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                            <button type="submit">Supprimer</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Vous n'avez pas encore d'annonces.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.getElementById('login-btn').onclick = function() {
        document.getElementById('auth-forms').style.display = 'block';
        document.getElementById('login-form').style.display = 'block';
        document.getElementById('signup-form').style.display = 'none';
    };

    document.getElementById('signup-btn').onclick = function() {
        document.getElementById('auth-forms').style.display = 'block';
        document.getElementById('login-form').style.display = 'none';
        document.getElementById('signup-form').style.display = 'block';
    };

    document.querySelector('.close').onclick = function() {
        document.getElementById('auth-forms').style.display = 'none';
    };
</script>
</body>
</html>
