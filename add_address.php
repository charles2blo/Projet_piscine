<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $address = $_POST['address'];
    $stmt = $pdo->prepare("INSERT INTO adresses (utilisateur_id, adresse) VALUES (?, ?)");
    $stmt->execute([$user_id, $address]);
    header('Location: profile.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Adresse - Agora Francia</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="wrapper">
    <div class="section">
        <h2>Ajouter une Adresse</h2>
        <form action="add_address.php" method="post">
            <label for="address">Adresse:</label><br>
            <textarea id="address" name="address" required></textarea><br>
            <input type="submit" value="Ajouter">
        </form>
    </div>
</div>
</body>
</html>
