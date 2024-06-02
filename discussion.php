<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$vendeur_id = isset($_GET['vendeur_id']) ? $_GET['vendeur_id'] : null;
$article_id = isset($_GET['article_id']) ? $_GET['article_id'] : null;

if (!$vendeur_id || !$article_id) {
    header('Location: chat.php');
    exit();
}

try {
    // Vérifier si l'article et les utilisateurs existent
    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE id = ?");
    $stmt->execute([$vendeur_id]);
    if ($stmt->rowCount() == 0) {
        throw new Exception("Vendeur non trouvé");
    }

    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE id = ?");
    $stmt->execute([$user_id]);
    if ($stmt->rowCount() == 0) {
        throw new Exception("Utilisateur non trouvé");
    }

    $stmt = $pdo->prepare("SELECT id FROM articles WHERE id = ?");
    $stmt->execute([$article_id]);
    if ($stmt->rowCount() == 0) {
        throw new Exception("Article non trouvé");
    }

    // Récupérer les détails de la discussion
    $stmt = $pdo->prepare("
        SELECT 
            m.*, 
            a.nom AS article_nom, 
            u.nom AS vendeur_nom, 
            u.prenom AS vendeur_prenom 
        FROM messagerie m
        JOIN articles a ON m.article_id = a.id
        JOIN utilisateurs u ON m.vendeur_id = u.id
        WHERE m.article_id = ? AND (m.vendeur_id = ? OR m.user_id = ?)
        ORDER BY m.timestamp ASC
    ");
    $stmt->execute([$article_id, $vendeur_id, $user_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer l'adresse de l'acheteur
    $stmt = $pdo->prepare("SELECT adresse_ligne1, adresse_ligne2, ville, code_postal, pays, numero_telephone FROM adresses WHERE utilisateur_id = ?");
    $stmt->execute([$user_id]);
    $adresse_acheteur = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$adresse_acheteur) {
        throw new Exception("Adresse de l'acheteur non trouvée");
    }
    $adresse_complet = $adresse_acheteur['adresse_ligne1'] . ' ' . $adresse_acheteur['adresse_ligne2'] . ', ' . $adresse_acheteur['ville'] . ', ' . $adresse_acheteur['code_postal'] . ', ' . $adresse_acheteur['pays'] . ', ' . $adresse_acheteur['numero_telephone'];

} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
    die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'];
    $action = isset($_POST['action']) ? $_POST['action'] : null;
    $montant_offre = isset($_POST['montant_offre']) ? $_POST['montant_offre'] : null;

    if ($action) {
        if ($action == 'accept') {
            // Vérifier si le montant de l'offre est défini
            $montant_offre = isset($_POST['hidden_montant_offre']) ? $_POST['hidden_montant_offre'] : null;
            if ($montant_offre === null) {
                throw new Exception("Montant de l'offre non défini");
            }

            try {
                // Créer la commande
                $stmt = $pdo->prepare("INSERT INTO commandes (acheteur_id, article_id, quantite, prix_total, date_commande, status, adresse_livraison) VALUES (?, ?, 1, ?, NOW(), 'en attente', ?)");
                $stmt->execute([$user_id, $article_id, $montant_offre, $adresse_complet]);
                $commande_id = $pdo->lastInsertId();

                // Ajouter l'article à la commande
                $stmt = $pdo->prepare("INSERT INTO commandes_articles (commande_id, article_id, quantite) VALUES (?, ?, 1)");
                $stmt->execute([$commande_id, $article_id]);

                // Mettre à jour la quantité de l'article
                $stmt = $pdo->prepare("UPDATE articles SET quantite = quantite - 1 WHERE id = ?");
                $stmt->execute([$article_id]);

                // Envoyer un message de confirmation avec lien vers la commande
                $confirmation_message = "Votre offre a été acceptée. Votre commande a été créée avec succès.";
                $stmt = $pdo->prepare("INSERT INTO messagerie (user_id, vendeur_id, article_id, message) VALUES (?, ?, ?, ?)");
                $stmt->execute([$user_id, $vendeur_id, $article_id, $confirmation_message]);

                header("Location: discussion.php?article_id=$article_id&vendeur_id=$vendeur_id");
                exit;
            } catch (PDOException $e) {
                echo "Erreur: " . $e->getMessage();
                die();
            }
        } elseif ($action == 'reject') {
            $message = "Votre offre a été refusée.";
        } elseif ($action == 'offer') {
            $_SESSION['montant_offre'] = $montant_offre;
            $message = "Proposition de prix : " . $montant_offre;
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO messagerie (user_id, vendeur_id, article_id, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $vendeur_id, $article_id, $message]);
        header("Location: discussion.php?article_id=$article_id&vendeur_id=$vendeur_id");
        exit();
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
        die();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Discussion - Agora Francia</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .section {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 20px auto;
            width: 95%;
        }
        .messages {
            max-height: 400px;
            overflow-y: auto;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
        }
        .message {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 10px;
        }
        .message p {
            margin: 0;
        }
        .message small {
            display: block;
            margin-top: 5px;
            color: #888;
        }
        .message:nth-child(odd) {
            background-color: #e6f7ff;
        }
        .message:nth-child(even) {
            background-color: #d6ecff;
        }
        form {
            display: flex;
            align-items: center;
        }
        form textarea {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            margin-right: 10px;
        }
        form button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007BFF;
            color: white;
            cursor: pointer;
        }
        form button:hover {
            background-color: #0056b3;
        }
        .btn-primary {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 10px;
            border-radius: 5px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
        }
        .btn-primary:hover {
            background-color: #45a049;
        }
        .btn-retour {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
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

    <h2>Discussion sur l'article : <?php echo htmlspecialchars($messages[0]['article_nom']); ?></h2>
    <a href="chat.php" class="btn-retour">Retour</a>
    <div class="messages">
        <?php if (count($messages) > 0): ?>
            <?php foreach ($messages as $message): ?>
                <div class="message">
                    <p><strong><?php echo ($message['user_id'] == $user_id) ? "Vous" : htmlspecialchars($message['vendeur_prenom'] . ' ' . $message['vendeur_nom']); ?> :</strong> <?php echo htmlspecialchars($message['message']); ?></p>
                    <p><small><?php echo htmlspecialchars($message['timestamp']); ?></small></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun message dans cette discussion.</p>
        <?php endif; ?>
    </div>

    <form method="post" action="discussion.php?article_id=<?php echo $article_id; ?>&vendeur_id=<?php echo $vendeur_id; ?>">
        <textarea id="message" name="message" required></textarea>
        <?php if ($user_id == $vendeur_id): ?>
            <input type="hidden" name="hidden_montant_offre" value="<?php echo isset($_SESSION['montant_offre']) ? $_SESSION['montant_offre'] : ''; ?>">
            <button type="submit" name="action" value="accept">Accepter</button>
            <button type="submit" name="action" value="reject">Refuser</button>
        <?php else: ?>
            <input type="number" name="montant_offre" placeholder="Montant de l'offre" required>
            <button type="submit" name="action" value="offer">Proposer</button>
        <?php endif; ?>
    </form>
</div>

<footer class="footer">
    <p>
        Contactez-nous : <a href="mailto:contact@agorafrancia.fr">contact@agorafrancia.fr</a> | Téléphone : <a href="tel:+33123456789">01 23 45 67 89</a> | Bureau : <a href="https://www.google.fr/maps/place/37+Quai+de+Grenelle,+75015+Paris/@48.8515004,2.2846575,17z/data=!3m1!4b1!4m6!3m5!1s0x47e6700497ee3ec5:0xdd60f514adcdb346!8m2!3d48.8515004!4d2.2872324?entry=ttu" target="_blank"><i class="fas fa-map-marker-alt"></i> Localisation</a>
    </p>
</footer>
</body>
</html>
