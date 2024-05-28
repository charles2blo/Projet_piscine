<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $type_carte = $_POST['type_carte'];
    $numero_carte = $_POST['numero_carte'];
    $nom_carte = $_POST['nom_carte'];
    $expiration = $_POST['expiration'];
    $code_securite = $_POST['code_securite'];
    $stmt = $pdo->prepare("INSERT INTO cartes (utilisateur_id, type_carte, numero_carte, nom_carte, expiration, code_securite) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $type_carte, $numero_carte, $nom_carte, $expiration, $code_securite]);
    header('Location: profile.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Moyen de Paiement - Agora Francia</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="wrapper">
    <div class="section">
        <h2>Ajouter un Moyen de Paiement</h2>
        <form action="add_card.php" method="post">
            <label for="type_carte">Type de Carte:</label><br>
            <select id="type_carte" name="type_carte" required>
                <option value="Visa">Visa</option>
                <option value="MasterCard">MasterCard</option>
                <option value="AmericanExpress">American Express</option>
                <option value="PayPal">PayPal</option>
            </select><br>
            <label for="numero_carte">Numéro de Carte:</label><br>
            <input type="text" id="numero_carte" name="numero_carte" required><br>
            <label for="nom_carte">Nom sur la Carte:</label><br>
            <input type="text" id="nom_carte" name="nom_carte" required><br>
            <label for="expiration">Date d'Expiration:</label><br>
            <input type="month" id="expiration" name="expiration" required><br>
            <label for="code_securite">Code de Sécurité:</label><br>
            <input type="text" id="code_securite" name="code_securite" required><br>
            <input type="submit" value="Ajouter">
        </form>
    </div>
</div>
</body>
</html>
