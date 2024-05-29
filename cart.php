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
</head>
<body>
<h1>Votre Panier</h1>
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
                <form action="remove_from_cart.php" method="post">
                    <input type="hidden" name="panier_id" value="<?php echo $article['id']; ?>">
                    <input type="submit" value="Retirer du Panier">
                </form>
            </div>
        <?php endforeach; ?>
        <a href="checkout.php">Procéder au paiement</a>
    <?php else: ?>
        <p>Votre panier est vide.</p>
    <?php endif; ?>
</div>
</body>
</html>
