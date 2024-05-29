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
$stmt = $pdo->prepare("SELECT ca.quantite, a.* FROM commandes_articles ca JOIN articles a ON ca.article_id = a.id WHERE ca.commande_id = ?");
$stmt->execute([$commande_id]);
$articles_commandes = $stmt->fetchAll();

// Récupérer l'adresse de livraison
$adresse_livraison = $commande['adresse_livraison'];

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
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .commande-details {
            margin: 20px;
        }
        .article {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
        }
        .article img {
            max-width: 100px;
        }
    </style>
</head>
<body>
<h1>Détails de la Commande #<?php echo htmlspecialchars($commande['id'] ?? ''); ?></h1>
<div class="commande-details">
    <p>Date: <?php echo htmlspecialchars($commande['date_commande'] ?? ''); ?></p>
    <p>Prix Total: <?php echo htmlspecialchars($commande['prix_total'] ?? ''); ?> €</p>
    <p>Adresse de livraison: <?php echo htmlspecialchars($adresse_livraison ?? ''); ?></p>
    <h3>Articles</h3>
    <?php if ($articles_commandes): ?>
        <?php foreach ($articles_commandes as $article): ?>
            <div class="article">
                <h4><?php echo htmlspecialchars($article['nom'] ?? ''); ?></h4>
                <p>Description: <?php echo htmlspecialchars($article['description'] ?? ''); ?></p>
                <p>Prix: <?php echo htmlspecialchars($article['prix'] ?? ''); ?> €</p>
                <p>Quantité: <?php echo htmlspecialchars($article['quantite'] ?? ''); ?></p>
                <p>État: <?php echo htmlspecialchars($article['etat'] ?? ''); ?></p>
                <?php if (!empty($article['photo'])): ?>
                    <img src="<?php echo htmlspecialchars($article['photo']); ?>" alt="Photo de l'article">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun article associé à cette commande.</p>
    <?php endif; ?>
    <h3>Moyens de paiement</h3>
    <?php foreach ($cartes as $carte): ?>
        <p><?php echo htmlspecialchars($carte['type_carte'] ?? ''); ?>: **** **** **** <?php echo htmlspecialchars(substr($carte['numero_carte'], -4) ?? ''); ?></p>
    <?php endforeach; ?>
</div>
</body>
</html>
