<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $address_id = $_POST['address_id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adresse_ligne1 = $_POST['adresse_ligne1'];
    $adresse_ligne2 = $_POST['adresse_ligne2'];
    $ville = $_POST['ville'];
    $code_postal = $_POST['code_postal'];
    $pays = $_POST['pays'];
    $numero_telephone = $_POST['numero_telephone'];
    $stmt = $pdo->prepare("UPDATE adresses SET nom = ?, prenom = ?, adresse_ligne1 = ?, adresse_ligne2 = ?, ville = ?, code_postal = ?, pays = ?, numero_telephone = ? WHERE id = ?");
    $stmt->execute([$nom, $prenom, $adresse_ligne1, $adresse_ligne2, $ville, $code_postal, $pays, $numero_telephone, $address_id]);
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
            <label for="nom">Nom:</label><br>
            <input type="text" id="nom" name="nom" value="<?php echo $address['nom']; ?>" required><br>
            <label for="prenom">Prénom:</label><br>
            <input type="text" id="prenom" name="prenom" value="<?php echo $address['prenom']; ?>" required><br>
            <label for="adresse_ligne1">Adresse Ligne 1:</label><br>
            <input type="text" id="adresse_ligne1" name="adresse_ligne1" value="<?php echo $address['adresse_ligne1']; ?>" required><br>
            <label for="adresse_ligne2">Adresse Ligne 2:</label><br>
            <input type="text" id="adresse_ligne2" name="adresse_ligne2" value="<?php echo $address['adresse_ligne2']; ?>"><br>
            <label for="ville">Ville:</label><br>
            <input type="text" id="ville" name="ville" value="<?php echo $address['ville']; ?>" required><br>
            <label for="code_postal">Code Postal:</label><br>
            <input type="text" id="code_postal" name="code_postal" value="<?php echo $address['code_postal']; ?>" required><br>
            <label for="pays">Pays:</label><br>
            <input type="text" id="pays" name="pays" value="<?php echo $address['pays']; ?>" required><br>
            <label for="numero_telephone">Numéro de téléphone:</label><br>
            <input type="text" id="numero_telephone" name="numero_telephone" value="<?php echo $address['numero_telephone']; ?>" required><br>
            <input type="submit" value="Modifier">
        </form>
    </div>
</div>
</body>
</html>
