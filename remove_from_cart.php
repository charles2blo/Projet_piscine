<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $panier_id = $_POST['panier_id'];
    $acheteur_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM panier WHERE id = ? AND acheteur_id = ?");
        $stmt->execute([$panier_id, $acheteur_id]);

        header('Location: cart.php');
        exit;
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }
}
?>
