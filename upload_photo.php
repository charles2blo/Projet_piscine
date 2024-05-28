<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['photo'])) {
    $user_id = $_SESSION['user_id'];
    $photo = $_FILES['photo'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($photo["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Vérifier si le fichier est une image réelle
    $check = getimagesize($photo["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "Le fichier n'est pas une image.";
        $uploadOk = 0;
    }

    // Vérifier la taille du fichier
    if ($photo["size"] > 500000) {
        echo "Désolé, votre fichier est trop volumineux.";
        $uploadOk = 0;
    }

    // Autoriser certains formats de fichiers
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
        $uploadOk = 0;
    }

    // Vérifier si $uploadOk est à 0 à cause d'une erreur
    if ($uploadOk == 0) {
        echo "Désolé, votre fichier n'a pas été téléchargé.";
    } else {
        // Assurez-vous que le dossier existe
        if (!is_dir($target_dir)) {
            if (!mkdir($target_dir, 0777, true)) {
                echo "Désolé, une erreur est survenue lors de la création du dossier.";
                exit;
            }
        }

        if (move_uploaded_file($photo["tmp_name"], $target_file)) {
            $stmt = $pdo->prepare("UPDATE utilisateurs SET photo = ? WHERE id = ?");
            $stmt->execute([$target_file, $user_id]);
            header('Location: profile.php');
            exit;
        } else {
            echo "Désolé, une erreur est survenue lors du téléchargement de votre fichier.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Télécharger une Photo - Agora Francia</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="wrapper">
    <div class="section">
        <h2>Télécharger une Photo</h2>
        <form action="upload_photo.php" method="post" enctype="multipart/form-data">
            <label for="photo">Choisir une photo:</label><br>
            <input type="file" id="photo" name="photo" required><br>
            <input type="submit" value="Télécharger">
        </form>
    </div>
</div>
</body>
</html>
