<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

include 'db_connect.php';

$address_id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM adresses WHERE id = ?");
$stmt->execute([$address_id]);
header('Location: profile.php');
exit;
?>
