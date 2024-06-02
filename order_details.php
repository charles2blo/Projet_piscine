<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

include 'db_connect.php';

if (!isset($_GET['commande_id'])) {
    echo "Commande non spécifiée.";
    exit;
}

$commande_id = $_GET['commande_id'];
$user_id = $_SESSION['user_id'];

// Récupérer les détails de la commande
$stmt = $pdo->prepare("SELECT * FROM commandes WHERE id = ? AND acheteur_id = ?");
$stmt->execute([$commande_id, $user_id]);
$commande = $stmt->fetch();

if (!$commande) {
    echo "Commande non trouvée.";
    exit;
}

// Récupérer les articles associés à la commande
$stmt = $pdo->prepare("SELECT ca.quantite AS quantite_commandee, a.nom, a.description, a.photo, a.prix, a.etat 
                        FROM commandes_articles ca 
                        JOIN articles a ON ca.article_id = a.id 
                        WHERE ca.commande_id = ?");
$stmt->execute([$commande_id]);
$articles_commandes = $stmt->fetchAll();

// Récupérer l'adresse de livraison
$adresse_livraison_json = $commande['adresse_livraison'];
$adresse_livraison = json_decode($adresse_livraison_json, true);

// Récupérer les détails de paiement
$stmt = $pdo->prepare("SELECT * FROM cartes WHERE utilisateur_id = ?");
$stmt->execute([$user_id]);
$cartes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de la Commande #<?php echo htmlspecialchars($commande['id'] ?? ''); ?></title>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .btn-retour {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
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
    <br>
    <a href="mes_commandes.php" class="btn-retour">Retour</a>
    <h1>Détails de la Commande #<?php echo htmlspecialchars($commande['id'] ?? ''); ?></h1>
    <div class="commande-details">
        <p>Date: <?php echo htmlspecialchars($commande['date_commande'] ?? ''); ?></p>
        <p>Prix Total: <?php echo htmlspecialchars($commande['prix_total'] ?? ''); ?> €</p>
        <h3>Articles</h3>
        <?php if ($articles_commandes): ?>
            <?php foreach ($articles_commandes as $article): ?>
                <div class="article-container">
                    <?php if (!empty($article['photo'])): ?>
                        <img src="<?php echo htmlspecialchars($article['photo']); ?>" alt="Photo de l'article" class="article-photo">
                    <?php endif; ?>
                    <div class="article-details">
                        <h4><?php echo htmlspecialchars($article['nom'] ?? ''); ?></h4>
                        <p>Description: <?php echo htmlspecialchars($article['description'] ?? ''); ?></p>
                        <p>Prix: <?php echo htmlspecialchars($article['prix'] ?? ''); ?> €</p>
                        <p>Quantité: <?php echo htmlspecialchars($article['quantite_commandee'] ?? ''); ?></p>
                        <p>État: <?php echo htmlspecialchars($article['etat'] ?? ''); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun article associé à cette commande.</p>
        <?php endif; ?>
        <h3>Moyens de paiement</h3>
        <?php foreach ($cartes as $carte): ?>
            <p><?php echo htmlspecialchars($carte['type_carte'] ?? ''); ?>: **** **** **** <?php echo htmlspecialchars(substr($carte['numero_carte'], -4) ?? ''); ?></p>
        <?php endforeach; ?>
        <h3>Adresse de livraison</h3>
        <?php if ($adresse_livraison): ?>
            <p><?php echo htmlspecialchars($adresse_livraison['nom'] ?? ''); ?> <?php echo htmlspecialchars($adresse_livraison['prenom'] ?? ''); ?></p>
            <p><?php echo htmlspecialchars($adresse_livraison['adresse_ligne1']); ?></p>
            <p><?php echo htmlspecialchars($adresse_livraison['adresse_ligne2'] ?? ''); ?></p>
            <p><?php echo htmlspecialchars($adresse_livraison['ville']); ?>, <?php echo htmlspecialchars($adresse_livraison['code_postal']); ?></p>
            <p><?php echo htmlspecialchars($adresse_livraison['pays']); ?></p>
            <p><?php echo htmlspecialchars($adresse_livraison['numero_telephone']); ?></p>
        <?php else: ?>
            <p>Adresse de livraison non spécifiée.</p>
        <?php endif; ?>
    </div>
</body>
</html>
