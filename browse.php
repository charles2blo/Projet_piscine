<?php
session_start();
include 'db_connect.php';

$filters = [];
$sql = "SELECT * FROM articles WHERE quantite > 0";

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

// Filtrage par nom (recherche par mot-clé)
if (isset($_GET['nom']) && !empty($_GET['nom'])) {
    $filters[] = "nom LIKE :nom";
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

    if (isset($_GET['nom']) && !empty($_GET['nom'])) {
        $nom = '%' . $_GET['nom'] . '%';
        $stmt->bindParam(':nom', $nom);
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
</head>
<body>
<div class="wrapper">
    <header class="header">
        <h1 class="header-title">Agora Francia</h1>
        <img src="logo.png" width="100" height="100" alt="Logo Agora" class="logo">
    </header>
    <nav class="navigation">
        <a href="index.html" class="nav-link"><i class="fas fa-home"></i> Accueil</a>
        <a href="browse.php" class="nav-link"><i class="fas fa-th-list"></i> Tout Parcourir</a>
        <a href="notifications.html" class="nav-link"><i class="fas fa-bell"></i> Notifications</a>
        <a href="cart.php" class="nav-link"><i class="fas fa-shopping-cart"></i> Panier</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="publish_article.php" class="nav-link">Publier un article</a>
        <?php endif; ?>
        <div class="dropdown">
            <a href="#votrecompte" class="dropbtn nav-link"><i class="fas fa-user"></i> Votre Compte</a>
            <div class="dropdown-content">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="profile.php" class="dropdown-item">Mon Profil</a>
                    <a href="logout.php" class="dropdown-item">Se Déconnecter</a>
                <?php else: ?>
                    <a href="#" id="login-btn" class="dropdown-item">Se connecter</a>
                    <a href="#" id="signup-btn" class="dropdown-item">S'inscrire</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="section">
        <h1>Articles Disponibles</h1>

        <!-- Formulaire de filtre -->
        <form method="get" action="browse.php" class="filter-form">
            <label for="categorie">Catégorie:</label>
            <select name="categorie" id="categorie">
                <option value="">Toutes</option>
                <option value="meubles" <?php if(isset($_GET['categorie']) && $_GET['categorie'] == 'meubles') echo 'selected'; ?>>Meubles</option>
                <option value="objets_art" <?php if(isset($_GET['categorie']) && $_GET['categorie'] == 'objets_art') echo 'selected'; ?>>Objets d'art</option>
                <option value="accessoire_vip" <?php if(isset($_GET['categorie']) && $_GET['categorie'] == 'accessoire_vip') echo 'selected'; ?>>Accessoire VIP</option>
                <option value="materiels_scolaires" <?php if(isset($_GET['categorie']) && $_GET['categorie'] == 'materiels_scolaires') echo 'selected'; ?>>Matériels scolaires</option>
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

            <label for="nom">Recherche:</label>
            <input type="text" name="nom" id="nom" value="<?php echo isset($_GET['nom']) ? htmlspecialchars($_GET['nom']) : ''; ?>">

            <label for="type_vente">Type de vente:</label>
            <select name="type_vente" id="type_vente">
                <option value="">Tous</option>
                <option value="enchere" <?php if(isset($_GET['type_vente']) && $_GET['type_vente'] == 'enchere') echo 'selected'; ?>>Enchère</option>
                <option value="immédiat" <?php if(isset($_GET['type_vente']) && $_GET['type_vente'] == 'immédiat') echo 'selected'; ?>>Achat immédiat</option>
                <option value="négociation" <?php if(isset($_GET['type_vente']) && $_GET['type_vente'] == 'négociation') echo 'selected'; ?>>Négociation</option>
            </select>

            <!-- Option de tri -->
            <label for="sort_order">Trier par prix:</label>
            <select name="sort_order" id="sort_order">
                <option value="">Aucun</option>
                <option value="asc" <?php if(isset($_GET['sort_order']) && $_GET['sort_order'] == 'asc') echo 'selected'; ?>>Prix croissant</option>
                <option value="desc" <?php if(isset($_GET['sort_order']) && $_GET['sort_order'] == 'desc') echo 'selected'; ?>>Prix décroissant</option>
            </select>

            <input type="submit" value="Filtrer">
        </form>

        <div class="articles-grid">
            <?php foreach ($articles as $article): ?>
                <div class="article">
                    <img src="images/<?php echo htmlspecialchars($article['image']); ?>" alt="<?php echo htmlspecialchars($article['nom']); ?>">
                    <h2><?php echo htmlspecialchars($article['nom']); ?></h2>
                    <p>Catégorie: <?php echo htmlspecialchars($article['categorie']); ?></p>
                    <p>État: <?php echo htmlspecialchars($article['etat']); ?></p>
                    <p>Prix: €<?php echo htmlspecialchars($article['prix']); ?></p>
                    <a href="article.php?id=<?php echo $article['id']; ?>" class="btn">Voir plus</a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <footer class="footer">
        <p>&copy; 2023 Agora Francia. Tous droits réservés.</p>
    </footer>
</div>
</body>
</html>
