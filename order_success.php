<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

$user_id = $_SESSION['user_id'];
$commande_id = $_GET['id'];

try {
    // Récupérer les détails de la commande
    $stmt = $pdo->prepare("SELECT * FROM commandes WHERE id = ? AND acheteur_id = ?");
    $stmt->execute([$commande_id, $user_id]);
    $commande = $stmt->fetch();

    // Récupérer les articles de la commande
    $stmt = $pdo->prepare("SELECT a.nom, a.description, a.photo, a.prix, ca.quantite FROM commandes_articles ca JOIN articles a ON ca.article_id = a.id WHERE ca.commande_id = ?");
    $stmt->execute([$commande_id]);
    $articles = $stmt->fetchAll();

    // Récupérer les informations de livraison
    $stmt = $pdo->prepare("SELECT adresse_livraison FROM commandes WHERE id = ?");
    $stmt->execute([$commande_id]);
    $livraison = $stmt->fetchColumn();

    // Récupérer les informations de paiement
    $stmt = $pdo->prepare("SELECT c.type_carte, c.numero_carte FROM cartes c JOIN commandes cmd ON c.utilisateur_id = cmd.acheteur_id WHERE cmd.id = ?");
    $stmt->execute([$commande_id]);
    $carte = $stmt->fetch();
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commande Réussie</title>
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

    <h2>Commande Réussie</h2>
    <p>Merci pour votre commande, <?php echo htmlspecialchars($user_id); ?> !</p>
    <h3>Détails de la Commande #<?php echo htmlspecialchars($commande_id); ?></h3>
    <p>Date: <?php echo htmlspecialchars($commande['date_commande']); ?></p>
    <p>Prix Total: <?php echo htmlspecialchars($commande['prix_total']); ?> €</p>

    <h3>Adresse de livraison:</h3>
    <p><?php echo htmlspecialchars($livraison); ?></p>

    <h3>Articles:</h3>
    <?php foreach ($articles as $article): ?>
        <div class="article">
            <h4><?php echo htmlspecialchars($article['nom']); ?></h4>
            <p><?php echo htmlspecialchars($article['description']); ?></p>
            <p>Prix: <?php echo htmlspecialchars($article['prix']); ?> €</p>
            <p>Quantité: <?php echo htmlspecialchars($article['quantite']); ?></p>
            <?php if ($article['photo']): ?>
                <img src="<?php echo htmlspecialchars($article['photo']); ?>" alt="Photo de l'article">
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <h3>Moyen de paiement:</h3>
    <p><?php echo htmlspecialchars($carte['type_carte']); ?>: **** **** **** <?php echo htmlspecialchars(substr($carte['numero_carte'], -4)); ?></p>
</div>
</body>
</html>
