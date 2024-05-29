<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

$acheteur_id = $_SESSION['user_id'];

try {
    // Récupérez les articles dans le panier
    $stmt = $pdo->prepare("SELECT p.id, p.quantite, a.id as article_id, a.nom, a.prix FROM panier p JOIN articles a ON p.article_id = a.id WHERE p.acheteur_id = ?");
    $stmt->execute([$acheteur_id]);
    $panier = $stmt->fetchAll();

    // Vérifiez les articles récupérés
    if (!$panier) {
        throw new Exception("Panier vide ou articles invalides");
    }

    // Calculer le total
    $total = 0;
    foreach ($panier as $article) {
        $total += $article['prix'] * $article['quantite'];
    }

    // Insérer la commande
    $stmt = $pdo->prepare("INSERT INTO commandes (acheteur_id, prix_total, date_commande, status) VALUES (?, ?, NOW(), 'en attente')");
    $stmt->execute([$acheteur_id, $total]);
    $commande_id = $pdo->lastInsertId();

    // Insérer les articles de la commande
    foreach ($panier as $article) {
        $article_id = $article['article_id'];  // Assurez-vous d'utiliser le bon nom de colonne
        $quantite = $article['quantite'];

        // Vérifiez que l'article existe
        $stmt = $pdo->prepare("SELECT id FROM articles WHERE id = ?");
        $stmt->execute([$article_id]);
        $articleExists = $stmt->fetch();

        if ($articleExists) {
            $stmt = $pdo->prepare("INSERT INTO commandes_articles (commande_id, article_id, quantite) VALUES (?, ?, ?)");
            $stmt->execute([$commande_id, $article_id, $quantite]);
        } else {
            throw new Exception("L'article avec l'ID $article_id n'existe pas.");
        }
    }

    // Vider le panier
    $stmt = $pdo->prepare("DELETE FROM panier WHERE acheteur_id = ?");
    $stmt->execute([$acheteur_id]);

    echo "Paiement réussi. Votre commande est en attente de traitement.";
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
}
?>
