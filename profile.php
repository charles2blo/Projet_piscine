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
    <script src="script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        <div class="logo-notification">
            <a href="notifications.html" class="notification-icon"><i class="fas fa-bell"></i></a>
            <img src="logo.png" width="100" height="100" alt="logoAgora">
        </div>
    </div>
    <div class="navigation">
        <a href="index.html"><i class="fas fa-home"></i> Accueil</a>
        <a href="browse.php"><i class="fas fa-th-list"></i> Tout Parcourir</a>
        <a href="chat.php"><i class="fas fa-comments"></i> Chat</a>
        <a href="cart.php"><i class="fas fa-shopping-cart"></i> Panier</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="publish_article.php">Publier un article</a>
        <?php endif; ?>
        <div class="dropdown">
            <a href="#votrecompte" class="dropbtn"><i class="fas fa-user"></i> Votre Compte</a>
            <div class="dropdown-content">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="profile.php">Mon Profil</a>
                    <a href="logout.php">Se Déconnecter</a>
                <?php else: ?>
                    <a href="#" id="login-btn">Se connecter</a>
                    <a href="#" id="signup-btn">S'inscrire</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="section">
        <h2>Mon Profil</h2>
        <img src="<?php echo $user['photo']; ?>" alt="Photo de Profil" width="150" height="150"><br>
        <a href="upload_photo.php">Changer de photo</a><br>
        <strong>Nom :</strong> <?php echo $user['prenom'] . ' ' . $user['nom']; ?><br>
        <strong>Email :</strong> <?php echo $user['email']; ?><br>

        <button id="toggle-addresses" class="btn">Carnet d'adresses</button>

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
                <form action="delete_adress.php" method="post" style="display:inline;">
                    <input type="hidden" name="address_id" value="<?php echo $address['id']; ?>">
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
                </p>
            <?php endforeach; ?>
            <a href="add_address.php" class="btn btn-success">Ajouter une adresse</a>
        </div>

        <button id="toggle-payments" class="btn">Moyens de paiement</button>

        <div id="payment-container" style="display: none;">
            <h3>Moyens de paiement</h3>
            <?php foreach ($cards as $card): ?>
                <p>
                    <?php echo $card['type_carte']; ?>: **** **** **** <?php echo substr($card['numero_carte'], -4); ?><br>
                    <a href="delete_card.php?id=<?php echo $card['id']; ?>" class="btn btn-danger">Supprimer</a>
                </p>
            <?php endforeach; ?>
            <a href="add_card.php" class="btn btn-success">Ajouter un moyen de paiement</a>
        </div>

        <h3><a href="mes_commandes.php">Mes Commandes</a></h3>

        <h3><a href="mes-annonces.php">Mes annonces</a></h3>

        <h3>Ma Wishlist</h3>
        <!-- Code pour afficher les articles likés -->
        <h3>Personnalisation</h3>
        <!-- Code pour personnaliser le thème -->
        <h3><a href="logout.php">Se Déconnecter</a></h3>
    </div>
    <footer class="footer">
        <p>Contactez-nous : <a href="mailto:contact@agorafrancia.fr">contact@agorafrancia.fr</a> | Téléphone : <a href="tel:+33123456789">01 23 45 67 89</a> | <a href="https://www.google.fr/maps/place/37+Quai+de+Grenelle,+75015+Paris/@48.8515004,2.2846575,17z/data=!3m1!4b1!4m6!3m5!1s0x47e6700497ee3ec5:0xdd60f514adcdb346!8m2!3d48.8515004!4d2.2872324!16s%2Fg%2F11bw3y1mf8?entry=ttu" target="_blank"><i class="fas fa-map-marker-alt"></i> bureaux</a></p>
    </footer>
</div>
</body>
</html>