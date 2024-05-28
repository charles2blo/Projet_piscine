<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['new-email'];
    $password = password_hash($_POST['new-password'], PASSWORD_DEFAULT);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    // Vérifier si l'utilisateur existe déjà
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        echo "Cet email est déjà utilisé.";
    } else {
        // Insérer un nouvel utilisateur
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (email, mot_de_passe, type_utilisateur, nom, prenom) VALUES (?, ?, 'acheteur', ?, ?)");
        if ($stmt->execute([$email, $password, $first_name, $last_name])) {
            echo "Inscription réussie.";
        } else {
            echo "Erreur lors de l'inscription.";
        }
    }
}
?>
