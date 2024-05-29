<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

include 'db_connect.php';

$validation_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $type_carte = $_POST['type_carte'];
    $numero_carte = $_POST['numero_carte'];
    $nom_carte = $_POST['nom_carte'];
    $expiration = $_POST['expiration'] . '-01';  // Ajouter '-01' pour avoir un format valide 'YYYY-MM-DD'
    $code_securite = $_POST['code_securite'];

    // Validation côté serveur
    if (!preg_match('/^\d{16}$/', $numero_carte)) {
        $validation_error = "Le numéro de carte doit contenir exactement 16 chiffres.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO cartes (utilisateur_id, type_carte, numero_carte, nom_carte, expiration, code_securite) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $type_carte, $numero_carte, $nom_carte, $expiration, $code_securite]);
            header('Location: profile.php');
            exit;
        } catch (PDOException $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Moyen de Paiement - Agora Francia</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <script>
        function validateCardNumber() {
            var cardNumber = document.getElementById("numero_carte").value;
            if (cardNumber.length !== 16 || !/^\d{16}$/.test(cardNumber)) {
                alert("Le numéro de carte doit contenir exactement 16 chiffres.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
<div class="wrapper">
    <div class="section">
        <h2>Ajouter un Moyen de Paiement</h2>
        <?php if ($validation_error): ?>
            <p style="color: red;"><?php echo $validation_error; ?></p>
        <?php endif; ?>
        <form action="add_card.php" method="post" onsubmit="return validateCardNumber()">
            <label for="type_carte">Type de Carte:</label><br>
            <select id="type_carte" name="type_carte" required>
                <option value="Visa">Visa</option>
                <option value="MasterCard">MasterCard</option>
                <option value="AmericanExpress">American Express</option>
                <option value="PayPal">PayPal</option>
            </select><br>
            <label for="numero_carte">Numéro de Carte:</label><br>
            <input type="text" id="numero_carte" name="numero_carte" required maxlength="16"><br>
            <label for="nom_carte">Nom sur la Carte:</label><br>
            <input type="text" id="nom_carte" name="nom_carte" required maxlength="100"><br>
            <label for="expiration">Date d'Expiration:</label><br>
            <input type="month" id="expiration" name="expiration" required><br>
            <label for="code_securite">Code de Sécurité:</label><br>
            <input type="text" id="code_securite" name="code_securite" required maxlength="4"><br>
            <input type="submit" value="Ajouter">
        </form>
    </div>
</div>
</body>
</html>