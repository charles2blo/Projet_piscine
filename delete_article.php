<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "Erreur: vous devez être connecté pour supprimer vos annonces.";
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $article_id = $_POST['article_id'];
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM articles WHERE id = ? AND vendeur_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo "Erreur de préparation de la requête: " . $conn->error;
        exit();
    }

    $stmt->bind_param('ii', $article_id, $user_id);
    if ($stmt->execute()) {
        header('Location: mes_annonces.php');
        exit();
    } else {
        echo "Erreur lors de la suppression de l'annonce: " . $stmt->error;
    }
} else {
    echo "Requête invalide.";
}
?>
