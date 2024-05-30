<?php
session_start();
include 'db_connect.php';

if (!isset($_GET['id'])) {
    header('Location: browse.php');
    exit;
}

$article_id = $_GET['id'];

// Récupérer les détails de l'article et la dernière enchère
$stmt = $pdo->prepare("SELECT a.*, MAX(e.montant_offre) AS derniere_enchere, MAX(e.date_offre) AS derniere_date_offre, u.nom AS vendeur_nom, u.prenom AS vendeur_prenom, u.photo AS vendeur_photo FROM articles a LEFT JOIN encheres e ON a.id = e.article_id JOIN utilisateurs u ON a.vendeur_id = u.id WHERE a.id = ?");
$stmt->execute([$article_id]);
$article = $stmt->fetch();

if (!$article) {
    echo "Article non trouvé";
    exit;
}

// Calculer le temps restant de l'enchère
$now = new DateTime();
$end_time = new DateTime($article['date_fin']);
$time_left = $end_time->getTimestamp() - $now->getTimestamp();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['montant_offre'])) {
    $nouvelle_enchere = $_POST['montant_offre'];

    if ($nouvelle_enchere > ($article['derniere_enchere'] ?? $article['prix'])) {
        // Prolonger l'enchère de 30 secondes
        $end_time->modify('+30 seconds');
        $stmt = $pdo->prepare("UPDATE articles SET date_fin = ? WHERE id = ?");
        $stmt->execute([$end_time->format('Y-m-d H:i:s'), $article_id]);

        // Enregistrer la nouvelle enchère
        $stmt = $pdo->prepare("INSERT INTO encheres (article_id, acheteur_id, montant_offre) VALUES (?, ?, ?)");
        $stmt->execute([$article_id, $_SESSION['user_id'], $nouvelle_enchere]);

        // Rafraîchir la page pour afficher les mises à jour
        header("Location: enchere.php?id=$article_id");
        exit;
    } else {
        $message = "Enchère trop basse";
    }
}

// Vérifier si l'enchère est terminée
if ($time_left <= 0) {
    // Ajouter l'article au panier du dernier enchérisseur
    $stmt = $pdo->prepare("SELECT acheteur_id FROM encheres WHERE article_id = ? ORDER BY montant_offre DESC, date_offre DESC LIMIT 1");
    $stmt->execute([$article_id]);
    $dernier_encherisseur = $stmt->fetch();

    if ($dernier_encherisseur) {
        $stmt = $pdo->prepare("INSERT INTO panier (utilisateur_id, article_id) VALUES (?, ?)");
        $stmt->execute([$dernier_encherisseur['acheteur_id'], $article_id]);

        echo "L'enchère est terminée. L'article a été ajouté au panier du gagnant.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Enchères - <?php echo htmlspecialchars($article['nom']); ?></title>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <script>
        // Fonction pour afficher le temps restant
        function updateTimeLeft() {
            var timeLeft = <?php echo $time_left; ?>;
            var countdownElement = document.getElementById('countdown');

            function updateCountdown() {
                var minutes = Math.floor(timeLeft / 60);
                var seconds = timeLeft % 60;
                countdownElement.innerHTML = minutes + "m " + seconds + "s";
                timeLeft--;

                if (timeLeft < 0) {
                    clearInterval(countdownInterval);
                    countdownElement.innerHTML = "L'enchère est terminée.";
                    location.reload(); // Rafraîchir la page pour mettre à jour l'état de l'enchère
                }
            }

            updateCountdown();
            var countdownInterval = setInterval(updateCountdown, 1000);
        }

        window.onload = updateTimeLeft;
    </script>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>Agora Francia</h1>
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

    <h2>Enchères pour <?php echo htmlspecialchars($article['nom']); ?></h2>
    <div class="article-container">
        <div class="article-details">
            <?php if ($article['photo']): ?>
                <img src="<?php echo htmlspecialchars($article['photo']); ?>" alt="Photo de l'article">
            <?php endif; ?>
            <h3><?php echo htmlspecialchars($article['nom']); ?></h3>
            <div class="article-info">
                <div><span>Catégorie:</span> <?php echo htmlspecialchars($article['categorie']); ?></div>
                <div><span>Description:</span> <?php echo htmlspecialchars($article['description']); ?></div>
                <div><span>Prix de base:</span> <?php echo htmlspecialchars($article['prix']); ?> €</div>
                <div><span>Quantité:</span> <?php echo htmlspecialchars($article['quantite']); ?></div>
                <div><span>Type de vente:</span> <?php echo htmlspecialchars($article['type_vente']); ?></div>
                <div><span>État:</span> <?php echo htmlspecialchars($article['etat']); ?></div>
                <div><span>Vendeur:</span> <?php echo htmlspecialchars($article['vendeur_prenom'] . ' ' . $article['vendeur_nom']); ?></div>
                <?php if ($article['vendeur_photo']): ?>
                    <img src="<?php echo htmlspecialchars($article['vendeur_photo']); ?>" alt="Photo de profil" class="profile-pic">
                <?php endif; ?>
            </div>
        </div>
        <div class="enchere-details">
            <div class="article-info">
                <div><span>Dernière enchère:</span> <?php echo htmlspecialchars($article['derniere_enchere'] ?? $article['prix']); ?> €</div>
                <div><span>Temps restant:</span> <span id="countdown"></span></div>
            </div>
            <?php if (isset($message)): ?>
                <p style="color:red;"><?php echo $message; ?></p>
            <?php endif; ?>
            <form method="post">
                <label for="montant_offre">Votre enchère:</label>
                <input type="number" name="montant_offre" id="montant_offre" step="0.01" min="<?php echo ($article['derniere_enchere'] ?? $article['prix']) + 0.01; ?>" required>
                <input type="submit" value="Placer votre enchère">
            </form>
        </div>
    </div>
</div>
</body>
</html>
