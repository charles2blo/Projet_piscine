<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['new-email'];
    $password = password_hash($_POST['new-password'], PASSWORD_BCRYPT);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['signup_error'] = "Cet Email est déjà utilisé";
        header('Location: index.html');
        exit();
    }

    // Insérer le nouvel utilisateur
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (email, mot_de_passe, prenom, nom) VALUES (?, ?, ?, ?)");
    $stmt->execute([$email, $password, $first_name, $last_name]);

    $_SESSION['user_id'] = $pdo->lastInsertId();
    header('Location: profile.php');
    exit();
}
?>
