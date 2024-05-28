<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $address_id = $_POST['address_id'];
    $address = $_POST['address'];
    $stmt = $pdo->prepare("UPDATE adresses SET adresse = ? WHERE id = ?");
    $stmt->execute([$address, $address_id]);
    header('Location: profile.php');
    exit;
} else {
    $address_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM adresses WHERE id = ?");
    $stmt->execute([$address_id]);
    $address = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Adresse - Agora Francia</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="wrapper">
    <div class="section">
        <h2>Modifier une Adresse</h2>
        <form action="edit_address.php" method="post">
            <input type="hidden" name="address_id" value="<?php echo $address['id']; ?>">
            <label for="address">Adresse:</label><br>
            <textarea id="address" name="address" required><?php echo $address['adresse']; ?></textarea><br>
            <input type="submit" value="Modifier">
        </form>
    </div>
</div>
</body>
</html>
