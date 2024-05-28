<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}
include 'db_connect.php';

// Récupérer les informations de l'utilisateur
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Récupérer les adresses de livraison
$stmt = $pdo->prepare("SELECT * FROM adresses WHERE utilisateur_id = ?");
$stmt->execute([$user_id]);
$addresses = $stmt->fetchAll();

// Récupérer les moyens de paiement
$stmt = $pdo->prepare("SELECT * FROM cartes WHERE utilisateur_id = ?");
$stmt->execute([$user_id]);
$cards = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Agora Francia</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>Agora Francia</h1>
        <img src="logo.png" width="100" height="100" alt="logoAgora">
    </div>
    <div class="navigation">
        <a href="index.html">Accueil</a>
        <a href="browse.html">Tout Parcourir</a>
        <a href="notifications.html">Notifications</a>
        <a href="cart.html">Panier</a>
        <a href="profile.php">Votre Compte</a>
        <a href="logout.php">Se Déconnecter</a>
    </div>
    <div class="section">
        <h2>Mon Profil</h2>
        <img src="<?php echo $user['photo']; ?>" alt="Photo de Profil" width="150" height="150"><br>
        <strong>Nom :</strong> <?php echo $user['prenom'] . ' ' . $user['nom']; ?><br>
        <strong>Email :</strong> <?php echo $user['email']; ?><br>

        <h3>Carnet d'adresse</h3>
        <?php foreach ($addresses as $address): ?>
            <p>
                <?php echo $address['adresse']; ?><br>
                <a href="edit_address.php?id=<?php echo $address['id']; ?>">Modifier</a>
                <a href="delete_address.php?id=<?php echo $address['id']; ?>">Supprimer</a>
            </p>
        <?php endforeach; ?>
        <a href="add_address.php">Ajouter une adresse</a>

        <h3>Mes Commandes</h3>
        <!-- Code pour afficher les commandes de l'utilisateur -->

        <h3>Ma Wishlist</h3>
        <!-- Code pour afficher les articles likés -->

        <h3>Personnalisation</h3>
        <!-- Code pour personnaliser le thème -->

        <h3>Moyen de paiement</h3>
        <?php foreach ($cards as $card): ?>
            <p>
                <?php echo $card['type_carte']; ?>: **** **** **** <?php echo substr($card['numero_carte'], -4); ?><br>
                <a href="delete_card.php?id=<?php echo $card['id']; ?>">Supprimer</a>
            </p>
        <?php endforeach; ?>
        <a href="add_card.php">Ajouter un moyen de paiement</a>
    </div>
    <footer class="footer">
        <p>Contactez-nous : contact@agorafrancia.fr | Téléphone : 01 23 45 67 89</p>
    </footer>
</div>
</body>
</html>
