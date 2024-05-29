<?php
session_start();
include 'db_connect.php';

if (!isset($_GET['id'])) {
    header('Location: browse.php');
    exit;
}

$article_id = $_GET['id'];

try {
    // Récupérer les détails de l'article
    $stmt = $pdo->prepare("SELECT a.*, u.nom AS vendeur_nom, u.prenom AS vendeur_prenom, u.photo AS vendeur_photo FROM articles a JOIN utilisateurs u ON a.vendeur_id = u.id WHERE a.id = ?");
    $stmt->execute([$article_id]);
    $article = $stmt->fetch();

    if (!$article) {
        throw new Exception("Article non trouvé");
    }
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de l'Article</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <script src="script.js"></script>
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

    <h2>Détails de l'Article</h2>
    <div class="article-container">
        <a href="browse.php" class="return-button">Retour</a>
        <?php if ($article['photo']): ?>
            <img src="<?php echo htmlspecialchars($article['photo']); ?>" alt="Photo de l'article">
        <?php endif; ?>
        <div class="article-details">
            <h3><?php echo htmlspecialchars($article['nom']); ?></h3>
            <div class="article-info">
                <div><span>Catégorie:</span> <?php echo htmlspecialchars($article['categorie']); ?></div>
                <div><span>Prix:</span> <?php echo htmlspecialchars($article['prix']); ?> €</div>
                <div><span>Quantité:</span> <?php echo htmlspecialchars($article['quantite']); ?></div>
                <div><span>Type de vente:</span> <?php echo htmlspecialchars($article['type_vente']); ?></div>
                <div><span>État:</span> <?php echo htmlspecialchars($article['etat']); ?></div>
            </div>
            <div class="vendeur-info">
                <h4>Vendeur</h4>
                <p><?php echo htmlspecialchars($article['vendeur_prenom'] . ' ' . $article['vendeur_nom']); ?></p>
                <?php if ($article['vendeur_photo']): ?>
                    <img src="<?php echo htmlspecialchars($article['vendeur_photo']); ?>" alt="Photo de profil" class="profile-pic">
                <?php endif; ?>
            </div>
            <form action="add_to_cart.php" method="post">
                <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                <label for="quantity">Quantité:</label>
                <select name="quantity" id="quantity">
                    <?php for ($i = 1; $i <= $article['quantite']; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
                <input type="submit" value="Ajouter au Panier">
            </form>
        </div>
    </div>
</div>
</body>
</html>
