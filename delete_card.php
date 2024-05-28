<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

include 'db_connect.php';

$card_id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM cartes WHERE id = ?");
$stmt->execute([$card_id]);
header('Location: profile.php');
exit;
?>
