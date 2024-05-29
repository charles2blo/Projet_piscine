<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

$article_id = isset($_POST['article_id']) ? (int)$_POST['article_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
$acheteur_id = $_SESSION['user_id'];

try {
    // Vérifiez que l'article existe et que la quantité demandée est disponible
    $stmt = $pdo->prepare("SELECT quantite FROM articles WHERE id = ?");
    $stmt->execute([$article_id]);
    $article = $stmt->fetch();

    if ($article && $quantity <= $article['quantite']) {
        // Vérifiez si l'article est déjà dans le panier
        $stmt = $pdo->prepare("SELECT id, quantite FROM panier WHERE acheteur_id = ? AND article_id = ?");
        $stmt->execute([$acheteur_id, $article_id]);
        $existingItem = $stmt->fetch();

        if ($existingItem) {
            // Mettre à jour la quantité de l'article dans le panier
            $newQuantity = $existingItem['quantite'] + $quantity;
            $stmt = $pdo->prepare("UPDATE panier SET quantite = ? WHERE id = ?");
            $stmt->execute([$newQuantity, $existingItem['id']]);
        } else {
            // Ajouter un nouvel article au panier
            $stmt = $pdo->prepare("INSERT INTO panier (acheteur_id, article_id, quantite) VALUES (?, ?, ?)");
            $stmt->execute([$acheteur_id, $article_id, $quantity]);
        }

        header('Location: cart.php');
    } else {
        echo "Erreur: La quantité demandée dépasse la quantité disponible.";
    }
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
    die();
}
?>
