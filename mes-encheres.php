<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

include 'db_connect.php';

// Récupérer les annonces de type enchère du vendeur
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM articles WHERE vendeur_id = ? AND type_vente = 'enchere'");
$stmt->execute([$user_id]);
$articles = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Enchères - Agora Francia</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>Agora Francia</h1>
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

    <h2>Mes Enchères</h2>
    <div class="article-container">
        <?php if (count($articles) > 0): ?>
            <?php foreach ($articles as $article): ?>
                <div class="article-item">
                    <a href="enchere_vendeur.php?id=<?php echo $article['id']; ?>">
                        <?php if ($article['photo']): ?>
                            <img src="<?php echo htmlspecialchars($article['photo']); ?>" alt="Photo de l'article">
                        <?php endif; ?>
                        <div class="article-details">
                            <h3><?php echo htmlspecialchars($article['nom']); ?></h3>
                            <div><span>Prix de départ:</span> <?php echo htmlspecialchars($article['prix']); ?> €</div>
                            <div><span>État:</span> <?php echo htmlspecialchars($article['etat']); ?></div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune enchère trouvée.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
