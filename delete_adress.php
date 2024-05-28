<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo"blabla";
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['address_id'])) {
        $address_id = $_POST['address_id'];
        $user_id = $_SESSION['user_id'];

        // Vérifier que l'utilisateur est bien le propriétaire de l'adresse
        $stmt = $pdo->prepare("SELECT * FROM adresses WHERE id = ? AND utilisateur_id = ?");
        $stmt->execute([$address_id, $user_id]);
        $address = $stmt->fetch();

        if ($address) {
            $stmt = $pdo->prepare("DELETE FROM adresses WHERE id = ? AND utilisateur_id = ?");
            $stmt->execute([$address_id, $user_id]);
            echo "Adresse supprimée avec succès.";
        } else {
            echo "Erreur : Adresse non trouvée ou vous n'avez pas la permission de la supprimer.";
        }
    } else {
        echo "Aucune adresse spécifiée.";
    }

    header('Location: profile.php');
    exit;
} else {
    echo "Méthode de requête invalide.";
}
?>
