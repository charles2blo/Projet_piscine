<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

$panier_id = isset($_POST['panier_id']) ? (int)$_POST['panier_id'] : 0;
$new_quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

try {
    // Vérifiez la quantité disponible de l'article
    $stmt = $pdo->prepare("SELECT a.quantite FROM panier p JOIN articles a ON p.article_id = a.id WHERE p.id = ?");
    $stmt->execute([$panier_id]);
    $article = $stmt->fetch();

    if ($article && $new_quantity <= $article['quantite']) {
        // Mettre à jour la quantité de l'article dans le panier
        $stmt = $pdo->prepare("UPDATE panier SET quantite = ? WHERE id = ?");
        $stmt->execute([$new_quantity, $panier_id]);
        header('Location: cart.php');
    } else {
        echo "Erreur: La quantité demandée dépasse la quantité disponible.";
    }
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
    die();
}
?>
