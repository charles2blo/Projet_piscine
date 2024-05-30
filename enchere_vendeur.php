<?php
session_start();
include 'db_connect.php';

if (!isset($_GET['id'])) {
    header('Location: browse.php');
    exit;
}

$article_id = $_GET['id'];

try {
    // Récupérer les détails de l'article et la dernière enchère
    $stmt = $pdo->prepare("SELECT a.*, MAX(e.montant_offre) AS derniere_enchere FROM articles a LEFT JOIN encheres e ON a.id = e.article_id WHERE a.id = ?");
    $stmt->execute([$article_id]);
    $article = $stmt->fetch();

    if (!$article) {
        throw new Exception("Article non trouvé");
    }

    if ($article['vendeur_id'] != $_SESSION['user_id']) {
        throw new Exception("Vous n'êtes pas autorisé à voir cette page.");
    }
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
    die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['stop_enchere'])) {
    $stmt = $pdo->prepare("UPDATE articles SET type_vente = 'indisponible' WHERE id = ?");
    $stmt->execute([$article_id]);
    header("Location: mes-encheres.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer Enchère - <?php echo htmlspecialchars($article['nom']); ?></title>
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

    <h2>Gérer Enchère pour <?php echo htmlspecialchars($article['nom']); ?></h2>
    <div class="article-container">
        <div class="article-details">
            <div class="article-info">
                <div><span>Catégorie:</span> <?php echo htmlspecialchars($article['categorie']); ?></div>
                <div><span>Dernière enchère:</span> <?php echo htmlspecialchars($article['derniere_enchere']); ?> €</div>
            </div>
            <form method="post">
                <input type="hidden" name="stop_enchere" value="1">
                <input type="submit" value="Stopper l'enchère">
            </form>
        </div>
    </div>
</div>
</body>
</html>

