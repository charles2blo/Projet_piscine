<?php
session_start();
include 'db_connect.php';

$filters = [];
$sql = "SELECT * FROM articles WHERE quantite > 0";

// Recherche par nom
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $filters[] = "nom LIKE :search";
}

// Filtrage par catégorie
if (isset($_GET['categorie']) && !empty($_GET['categorie'])) {
    $filters[] = "categorie = :categorie";
}

// Filtrage par état
if (isset($_GET['etat']) && !empty($_GET['etat'])) {
    $filters[] = "etat = :etat";
}

// Filtrage par prix minimum
if (isset($_GET['prix_min']) && is_numeric($_GET['prix_min'])) {
    $filters[] = "prix >= :prix_min";
}

// Filtrage par prix maximum
if (isset($_GET['prix_max']) && is_numeric($_GET['prix_max'])) {
    $filters[] = "prix <= :prix_max";
}

// Filtrage par type de vente
if (isset($_GET['type_vente']) && !empty($_GET['type_vente'])) {
    $filters[] = "type_vente = :type_vente";
}

// Ajouter les filtres à la requête
if ($filters) {
    $sql .= " AND " . implode(" AND ", $filters);
}

// Ajouter le tri par prix si spécifié
if (isset($_GET['sort_order']) && in_array($_GET['sort_order'], ['asc', 'desc'])) {
    $sql .= " ORDER BY prix " . $_GET['sort_order'];
}

try {
    $stmt = $pdo->prepare($sql);

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $stmt->bindValue(':search', '%' . $_GET['search'] . '%', PDO::PARAM_STR);
    }

    if (isset($_GET['categorie']) && !empty($_GET['categorie'])) {
        $stmt->bindParam(':categorie', $_GET['categorie']);
    }

    if (isset($_GET['etat']) && !empty($_GET['etat'])) {
        $stmt->bindParam(':etat', $_GET['etat']);
    }

    if (isset($_GET['prix_min']) && is_numeric($_GET['prix_min'])) {
        $stmt->bindParam(':prix_min', $_GET['prix_min']);
    }

    if (isset($_GET['prix_max']) && is_numeric($_GET['prix_max'])) {
        $stmt->bindParam(':prix_max', $_GET['prix_max']);
    }

    if (isset($_GET['type_vente']) && !empty($_GET['type_vente'])) {
        $stmt->bindParam(':type_vente', $_GET['type_vente']);
    }

    $stmt->execute();
    $articles = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tout Parcourir</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <script src="script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .filter-form { display: none; }
        .filter-form.active { display: block; }
        .filter-toggle { cursor: pointer; background-color: #007BFF; color: white; padding: 10px 20px; border-radius: 5px; margin-bottom: 10px; display: inline-block; }
    </style>
    <script>
        $(document).ready(function(){
            $(".filter-toggle").click(function(){
                $(".filter-form").toggleClass("active");
                if ($(".filter-form").hasClass("active")) {
                    $(".filter-form").slideDown();
                } else {
                    $(".filter-form").slideUp();
                }
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

    <h1>Articles Disponibles</h1>

    <!-- Bouton pour afficher/cacher le formulaire de filtre -->
    <div class="filter-toggle">Filtre</div>

    <!-- Formulaire de filtre -->
    <form method="get" action="browse.php" class="filter-form">
        <label for="search">Recherche par nom:</label>
        <input type="text" name="search" id="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">

        <label for="categorie">Catégorie:</label>
        <select name="categorie" id="categorie">
            <option value="">Toutes</option>
            <option value="meubles" <?php if(isset($_GET['categorie']) && $_GET['categorie'] == 'meubles') echo 'selected'; ?>>Meubles</option>
            <option value="objets_art" <?php if(isset($_GET['categorie']) && $_GET['categorie'] == 'objets_art') echo 'selected'; ?>>Objets d'art</option>
            <option value="accessoire_vip" <?php if(isset($_GET['categorie']) && $_GET['categorie'] == 'accessoire_vip') echo 'selected'; ?>>Accessoire VIP</option>
            <option value="materiels_scolaires" <?php if(isset($_GET['categorie']) && $_GET['categorie'] == 'materiels_scolaires') echo 'selected'; ?>>Matériels scolaires</option>
        </select>

        <label for="type_vente">Type de vente:</label>
        <select name="type_vente" id="type_vente">
            <option value="">Tous</option>
            <option value="achat immédiat" <?php if(isset($_GET['type_vente']) && $_GET['type_vente'] == 'achat immédiat') echo 'selected'; ?>>Achat Immédiat</option>
            <option value="negociation" <?php if(isset($_GET['type_vente']) && $_GET['type_vente'] == 'negociation') echo 'selected'; ?>>Négociation</option>
            <option value="enchère" <?php if(isset($_GET['type_vente']) && $_GET['type_vente'] == 'enchère') echo 'selected'; ?>>Enchère</option>
        </select>

        <label for="etat">État:</label>
        <select name="etat" id="etat">
            <option value="">Tous</option>
            <option value="neuf avec etiquette" <?php if(isset($_GET['etat']) && $_GET['etat'] == 'neuf avec etiquette') echo 'selected'; ?>>Neuf avec étiquette</option>
            <option value="neuf sans etiquette" <?php if(isset($_GET['etat']) && $_GET['etat'] == 'neuf sans etiquette') echo 'selected'; ?>>Neuf sans étiquette</option>
            <option value="tres bon etat" <?php if(isset($_GET['etat']) && $_GET['etat'] == 'tres bon etat') echo 'selected'; ?>>Très bon état</option>
            <option value="bon etat" <?php if(isset($_GET['etat']) && $_GET['etat'] == 'bon etat') echo 'selected'; ?>>Bon état</option>
            <option value="satisfaisant" <?php if(isset($_GET['etat']) && $_GET['etat'] == 'satisfaisant') echo 'selected'; ?>>Satisfaisant</option>
        </select>

        <label for="prix_min">Prix minimum:</label>
        <input type="number" name="prix_min" id="prix_min" value="<?php echo isset($_GET['prix_min']) ? htmlspecialchars($_GET['prix_min']) : ''; ?>">

        <label for="prix_max">Prix maximum:</label>
        <input type="number" name="prix_max" id="prix_max" value="<?php echo isset($_GET['prix_max']) ? htmlspecialchars($_GET['prix_max']) : ''; ?>">

        <!-- Option de tri -->
        <label for="sort_order">Classer par:</label>
        <select name="sort_order" id="sort_order">
            <option value="">Sélectionner</option>
            <option value="asc" <?php if(isset($_GET['sort_order']) && $_GET['sort_order'] == 'asc') echo 'selected'; ?>>Prix croissant</option>
            <option value="desc" <?php if(isset($_GET['sort_order']) && $_GET['sort_order'] == 'desc') echo 'selected'; ?>>Prix décroissant</option>
        </select>

        <input type="submit" value="Filtrer">
    </form>

    <div class="articles-grid">
        <?php foreach ($articles as $article): ?>
            <div class="article">
                <h3><?php echo htmlspecialchars($article['nom']); ?></h3>
                <p>Prix: <?php echo htmlspecialchars($article['prix']); ?> €</p>
                <p>Type de vente: <?php echo htmlspecialchars($article['type_vente']); ?></p>
                <?php if ($article['photo']): ?>
                    <a href="article_details.php?id=<?php echo $article['id']; ?>"><img src="<?php echo htmlspecialchars($article['photo']); ?>" alt="Photo de l'article"></a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <footer class="footer">
        <p>
            Contactez-nous : <a href="mailto:contact@agorafrancia.fr">contact@agorafrancia.fr</a> | Téléphone : <a href="tel:+33123456789">01 23 45 67 89</a> | Bureau : <a href="https://www.google.fr/maps/place/37+Quai+de+Grenelle,+75015+Paris/@48.8515004,2.2846575,17z/data=!3m1!4b1!4m6!3m5!1s0x47e6700497ee3ec5:0xdd60f514adcdb346!8m2!3d48.8515004!4d2.2872324?entry=ttu" target="_blank"><i class="fas fa-map-marker-alt"></i> Localisation</a>
        </p>
    </footer>
</div>
</body>
</html>
