<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $article_id = $_POST['article_id'];
    $quantite = $_POST['quantite'];
    $acheteur_id = $_SESSION['user_id'];

    // Vérifiez que l'article existe et que la quantité demandée est disponible
    $stmt = $pdo->prepare("SELECT quantite FROM articles WHERE id = ?");
    $stmt->execute([$article_id]);
    $article = $stmt->fetch();

    if ($article && $article['quantite'] >= $quantite) {
        // Ajouter l'article au panier
        $stmt = $pdo->prepare("INSERT INTO panier (acheteur_id, article_id, quantite) VALUES (?, ?, ?)
                               ON DUPLICATE KEY UPDATE quantite = quantite + VALUES(quantite)");
        $stmt->execute([$acheteur_id, $article_id, $quantite]);
        header('Location: cart.php');
        exit;
    } else {
        echo "Quantité demandée non disponible.";
    }
}
?>
