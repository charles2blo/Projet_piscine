<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $article_id = $_POST['article_id'];
    $acheteur_id = $_SESSION['user_id'];
    $quantite = 1; // Vous pouvez modifier cette valeur pour permettre à l'utilisateur de choisir la quantité

    try {
        // Vérifiez si l'article est déjà dans le panier
        $stmt = $pdo->prepare("SELECT * FROM panier WHERE article_id = ? AND acheteur_id = ?");
        $stmt->execute([$article_id, $acheteur_id]);
        $article = $stmt->fetch();

        if ($article) {
            // Si l'article est déjà dans le panier, augmentez la quantité
            $stmt = $pdo->prepare("UPDATE panier SET quantite = quantite + ? WHERE article_id = ? AND acheteur_id = ?");
            $stmt->execute([$quantite, $article_id, $acheteur_id]);
        } else {
            // Sinon, ajoutez un nouvel article au panier
            $stmt = $pdo->prepare("INSERT INTO panier (article_id, acheteur_id, quantite) VALUES (?, ?, ?)");
            $stmt->execute([$article_id, $acheteur_id, $quantite]);
        }

        header('Location: cart.php');
        exit;
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }
}
?>
