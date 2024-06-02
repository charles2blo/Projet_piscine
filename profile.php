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

// Gestion de la demande de changement de statut
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['demande_statut'])) {
    // Envoi d'une notification aux administrateurs
    $notification_message = "L'utilisateur " . htmlspecialchars($user['prenom'] . ' ' . $user['nom']) . " a demandé à devenir vendeur.";
    $stmt = $pdo->prepare("INSERT INTO notifications (utilisateur_id, message) VALUES (?, ?)");
    $stmt->execute([$user_id, $notification_message]);
    echo "Votre demande a été envoyée aux administrateurs.";
}
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
            $("#learn-more-btn").click(function(){
                $("#seller-application").show();
                $('html, body').animate({
                    scrollTop: $("#seller-application").offset().top
                }, 1000);
            });
        });
    </script>
    <style>
        .btn-retour {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .btn-retour:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>Agora Francia</h1>
        <div class="logo-notification">
            <a href="notifications.php" class="notification-icon"><i class="fas fa-bell"></i></a>
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
                <a href="profile.php">Mon Profil</a>
                <a href="logout.php">Se Déconnecter</a>
            </div>
        </div>
    </div>
    <div class="section">
        <h2>Mon Profil</h2>
        <img src="<?php echo htmlspecialchars($user['photo']); ?>" alt="Photo de Profil" class="profile-pic" width="150" height="150"><br>
        <a href="upload_photo.php" class="btn-retour">Changer de photo</a><br>
        <strong>Nom :</strong> <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?><br>
        <strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?><br>
        <strong>Statut :</strong> <?php echo htmlspecialchars($user['type_utilisateur']); ?><br>


        <button id="toggle-addresses" class="btn">Carnet d'adresses</button>

        <div id="address-container" style="display: none;">
            <h3>Carnet d'adresses</h3>
            <?php foreach ($addresses as $address): ?>
                <p>
                    <strong>Nom :</strong> <?php echo htmlspecialchars($address['nom']); ?><br>
                    <strong>Prénom :</strong> <?php echo htmlspecialchars($address['prenom']); ?><br>
                    <strong>Adresse :</strong> <?php echo htmlspecialchars($address['adresse_ligne1']); ?><br>
                    <strong>Adresse Ligne 2 :</strong> <?php echo htmlspecialchars($address['adresse_ligne2']); ?><br>
                    <strong>Ville :</strong> <?php echo htmlspecialchars($address['ville']); ?><br>
                    <strong>Code Postal :</strong> <?php echo htmlspecialchars($address['code_postal']); ?><br>
                    <strong>Pays :</strong> <?php echo htmlspecialchars($address['pays']); ?><br>
                    <strong>Numéro de téléphone :</strong> <?php echo htmlspecialchars($address['numero_telephone']); ?><br>
                <form action="delete_adress.php" method="post" style="display:inline;">
                    <input type="hidden" name="address_id" value="<?php echo htmlspecialchars($address['id']); ?>">
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
                    <?php echo htmlspecialchars($card['type_carte']); ?>: **** **** **** <?php echo htmlspecialchars(substr($card['numero_carte'], -4)); ?><br>
                    <a href="delete_card.php?id=<?php echo htmlspecialchars($card['id']); ?>" class="btn btn-danger">Supprimer</a>
                </p>
            <?php endforeach; ?>
            <a href="add_card.php" class="btn btn-success">Ajouter un moyen de paiement</a>
        </div>

        <h3><a href="mes_commandes.php" class="btn-retour">Mes Commandes</a></h3>

        <h3><a href="mes-annonces.php" class="btn-retour">Mes annonces</a></h3>

        <h3><a href="personnalisation.php" class="btn-retour">Personnalisation</a></h3>

        <?php if ($user['type_utilisateur'] == 'admin'): ?>
            <h3><a href="manage_sellers.php" class="btn-retour">Gestion des vendeurs</a></h3>
        <?php endif; ?>

        <h3><a href="logout.php" class="btn-retour">Se Déconnecter</a></h3>

        <?php if ($user['type_utilisateur'] == 'acheteur'): ?>
            <h3>Voulez-vous publier des articles sur Agora Francia ?</h3>
            <button id="learn-more-btn" class="btn">En savoir plus</button>

            <div id="seller-application" style="display:none;">
                <h3>Politique de vente:</h3>
                <p>En tant que vendeur, vous devez fournir des informations précises et complètes sur vos articles.</p>
                <p>Vous devez garantir que les articles que vous vendez sont conformes à la description fournie.</p>
                <p>Vous devez expédier les articles dans les délais spécifiés.</p>
                <p>Vous acceptez que Agora Francia ne soit pas responsable des litiges entre vous et les acheteurs.</p>
                <p>Vous acceptez de respecter toutes les lois et réglementations applicables en matière de vente en ligne.</p>
                <p>Agora Francia se réserve le droit de suspendre ou de supprimer votre compte en cas de non-respect de ces conditions.</p>
                <form method="post">
                    <input type="checkbox" name="accepter_conditions" required> J'accepte les conditions générales de vente<br>
                    <input type="submit" name="demande_statut" value="Demander à devenir vendeur">
                </form>
            </div>
        <?php endif; ?>

    </div>
    <footer class="footer">
        <p>Contactez-nous : <a href="mailto:contact@agorafrancia.fr">contact@agorafrancia.fr</a> | Téléphone : <a href="tel:+33123456789">01 23 45 67 89</a> | <a href="https://www.google.fr/maps/place/37+Quai+de+Grenelle,+75015+Paris/@48.8515004,2.2846575,17z/data=!3m1!4b1!4m6!3m5!1s0x47e6700497ee3ec5:0xdd60f514adcdb346!8m2!3d48.8515004!4d2.2872324?entry=ttu" target="_blank"><i class="fas fa-map-marker-alt"></i> Localisation</a>
        </p>
    </footer>
</div>
</body>
</html>
