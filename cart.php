<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

$acheteur_id = $_SESSION['user_id'];

try {
    // Récupérez les articles dans le panier
    $stmt = $pdo->prepare("SELECT p.id, p.quantite, a.nom, a.prix, a.photo FROM panier p JOIN articles a ON p.article_id = a.id WHERE p.acheteur_id = ?");
    $stmt->execute([$acheteur_id]);
    $panier = $stmt->fetchAll();

    // Calculer le total
    $total = 0;
    foreach ($panier as $article) {
        $total += $article['prix'] * $article['quantite'];
    }
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panier</title>
    <style>
        .panier {
            margin: 20px;
        }
        .article {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px;
        }
        .article img {
            max-width: 100px;
        }
        .article h3 {
            margin: 0 0 10px;
        }
    </style>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="script.js"></script>
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
        <a href="notifications.html"><i class="fas fa-bell"></i> Notifications</a>
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
    <h2>Votre Panier</h2>
    <div class="panier">
        <?php if ($panier): ?>
            <?php foreach ($panier as $article): ?>
                <div class="article">
                    <h3><?php echo htmlspecialchars($article['nom']); ?></h3>
                    <p>Prix: <?php echo htmlspecialchars($article['prix']); ?> €</p>
                    <p>Quantité: <?php echo htmlspecialchars($article['quantite']); ?></p>
                    <?php if ($article['photo']): ?>
                        <img src="<?php echo htmlspecialchars($article['photo']); ?>" alt="Photo de l'article">
                    <?php endif; ?>
                    <form action="update_cart.php" method="post">
                        <input type="hidden" name="panier_id" value="<?php echo $article['id']; ?>">
                        <input type="number" name="quantite" value="<?php echo $article['quantite']; ?>" min="1">
                        <input type="submit" value="Modifier">
                    </form>
                    <form action="remove_from_cart.php" method="post">
                        <input type="hidden" name="panier_id" value="<?php echo $article['id']; ?>">
                        <input type="submit" value="Retirer du Panier">
                    </form>
                </div>
            <?php endforeach; ?>
            <h3>Total: <?php echo $total; ?> €</h3>
            <a href="checkout.php">Procéder au paiement</a>
        <?php else: ?>
            <p>Votre panier est vide.</p>
        <?php endif; ?>
    </div>
</body>
</html>
