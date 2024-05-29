<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$article_id = $_POST['article_id'];
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

// Vérifiez si l'article appartient à l'utilisateur actuel
$stmt = $pdo->prepare("SELECT vendeur_id FROM articles WHERE id = ?");
$stmt->execute([$article_id]);
$article = $stmt->fetch();

if ($article['vendeur_id'] == $user_id) {
    echo "<script>alert('Vous ne pouvez pas ajouter au panier votre propre annonce.'); window.history.back();</script>";
    exit;
}

// Vérifiez si l'article est déjà dans le panier
$stmt = $pdo->prepare("SELECT quantite FROM panier WHERE acheteur_id = ? AND article_id = ?");
$stmt->execute([$user_id, $article_id]);
$existing_item = $stmt->fetch();

if ($existing_item) {
    // Mettre à jour la quantité si l'article est déjà dans le panier
    $new_quantity = $existing_item['quantite'] + $quantity;
    $stmt = $pdo->prepare("UPDATE panier SET quantite = ? WHERE acheteur_id = ? AND article_id = ?");
    $stmt->execute([$new_quantity, $user_id, $article_id]);
} else {
    // Ajouter l'article au panier
    $stmt = $pdo->prepare("INSERT INTO panier (acheteur_id, article_id, quantite) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $article_id, $quantity]);
}

header('Location: cart.php');
exit;
?>
