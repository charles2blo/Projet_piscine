<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'db_connect.php';

$article_id = $_GET['id']; // Utilisation de GET pour la suppression par ID

// Préparation et exécution de la requête de suppression
$stmt = $pdo->prepare("DELETE FROM articles WHERE id = ? AND vendeur_id = ?");
$stmt->execute([$article_id, $_SESSION['user_id']]);

header('Location: mes-annonces.php');
exit;
?>

