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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#toggle-addresses").click(function(){
                $("#address-container").toggle();
            });
            $("#toggle-payments").click(function(){
                $("#payment-container").toggle();
            });
        });
    </script>
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
        <a href="upload_photo.php">Changer de photo</a><br>
        <strong>Nom :</strong> <?php echo $user['prenom'] . ' ' . $user['nom']; ?><br>
        <strong>Email :</strong> <?php echo $user['email']; ?><br>

        <button id="toggle-addresses">Carnet d'adresses</button>

        <div id="address-container" style="display: none;">
            <h3>Carnet d'adresses</h3>
            <?php foreach ($addresses as $address): ?>
                <p>
                    <strong>Nom :</strong> <?php echo $address['nom']; ?><br>
                    <strong>Prénom :</strong> <?php echo $address['prenom']; ?><br>
                    <strong>Adresse :</strong> <?php echo $address['adresse_ligne1']; ?><br>
                    <strong>Adresse Ligne 2 :</strong> <?php echo $address['adresse_ligne2']; ?><br>
                    <strong>Ville :</strong> <?php echo $address['ville']; ?><br>
                    <strong>Code Postal :</strong> <?php echo $address['code_postal']; ?><br>
                    <strong>Pays :</strong> <?php echo $address['pays']; ?><br>
                    <strong>Numéro de téléphone :</strong> <?php echo $address['numero_telephone']; ?><br>
                    <a href="edit_address.php?id=<?php echo $address['id']; ?>">Modifier</a>
                    <a href="delete_address.php?id=<?php echo $address['id']; ?>">Supprimer</a>
                </p>
            <?php endforeach; ?>
            <a href="add_address.php">Ajouter une adresse</a>
        </div>

        <button id="toggle-payments">Moyens de paiement</button>

        <div id="payment-container" style="display: none;">
            <h3>Moyens de paiement</h3>
            <?php foreach ($cards as $card): ?>
                <p>
                    <?php echo $card['type_carte']; ?>: **** **** **** <?php echo substr($card['numero_carte'], -4); ?><br>
                    <a href="delete_card.php?id=<?php echo $card['id']; ?>">Supprimer</a>
                </p>
            <?php endforeach; ?>
            <a href="add_card.php">Ajouter un moyen de paiement</a>
        </div>

        <h3>Mes Commandes</h3>
        <!-- Code pour afficher les commandes de l'utilisateur -->

        <h3>Ma Wishlist</h3>
        <!-- Code pour afficher les articles likés -->

        <h3>Personnalisation</h3>
        <!-- Code pour personnaliser le thème -->
    </div>
    <footer class="footer">
        <p>Contactez-nous : <a href="mailto:contact@agorafrancia.fr">contact@agorafrancia.fr</a> | Téléphone : <a href="tel:+33123456789">01 23 45 67 89</a></p>
    </footer>
</div>
</body>
</html>
