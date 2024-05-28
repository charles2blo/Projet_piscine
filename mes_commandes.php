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

// Récupérer les commandes de l'utilisateur
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM commandes WHERE acheteur_id = ?");
$stmt->execute([$user_id]);
$commandes = $stmt->fetchAll();

// Récupérer les articles associés à chaque commande
$articles = [];
if ($commandes) {
    foreach ($commandes as $commande) {
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$commande['article_id']]);
        $article = $stmt->fetch();
        if ($article) {
            $articles[$commande['id']] = $article;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Commandes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .commandes {
            margin: 20px;
        }
        .commande {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <h1>Mes Commandes</h1>
    <div class="commandes">
        <?php if ($commandes): ?>
            <?php foreach ($commandes as $commande): ?>
                <div class="commande">
                    <h2>Commande #<?php echo htmlspecialchars($commande['id']); ?></h2>
                    <p>Date: <?php echo htmlspecialchars($commande['date']); ?></p>
                    <?php if (isset($articles[$commande['id']])): ?>
                        <h3>Article</h3>
                        <p>Nom: <?php echo htmlspecialchars($articles[$commande['id']]['nom']); ?></p>
                        <p>Description: <?php echo htmlspecialchars($articles[$commande['id']]['description']); ?></p>
                        <p>Prix: <?php echo htmlspecialchars($articles[$commande['id']]['prix']); ?> €</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune commande en cours.</p>
        <?php endif; ?>
    </div>
</body>
</html>
