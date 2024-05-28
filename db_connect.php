<?php
$host = 'localhost';
$db = 'agora_francia';
$user = 'root'; // Changez ceci en fonction de votre configuration
$pass = ''; // Changez ceci en fonction de votre configuration

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}
?>

