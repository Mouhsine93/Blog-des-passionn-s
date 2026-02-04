<?php
require_once('../model/connexion.php');

try {
    // Connexion à la base de données
    $connexion = new PDO("mysql:host=$nom_serveur;dbname=$nom_base_de_donnees", $utilisateur, $mot_de_passe);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom_utilisateur = trim($_POST['nom_utilisateur']);
        $mot_de_passe = trim($_POST['mot_de_passe']);
        $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        $email = trim($_POST['email']); // Nouveau champ email

        // Vérifier si le nom d'utilisateur existe déjà
        $stmt = $connexion->prepare("SELECT id FROM utilisateur WHERE nom_utilisateur = :nom_utilisateur");
        $stmt->bindParam(':nom_utilisateur', $nom_utilisateur);
        $stmt->execute();
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            echo "Ce nom d'utilisateur est déjà pris. Veuillez en choisir un autre.";
        } else {
            // Vérifier si l'email est déjà utilisé
            $stmt = $connexion->prepare("SELECT id FROM utilisateur WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $existingEmail = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingEmail) {
                echo "Cet email est déjà associé à un compte. Veuillez utiliser un autre email.";
            } else {
                // Insérer l'utilisateur seulement si le nom d'utilisateur et l'email ne sont pas déjà pris
                $stmt = $connexion->prepare("INSERT INTO utilisateur (nom_utilisateur, mot_de_passe_hash, email) VALUES (:nom_utilisateur, :mot_de_passe_hash, :email)");
                $stmt->bindParam(':nom_utilisateur', $nom_utilisateur);
                $stmt->bindParam(':mot_de_passe_hash', $mot_de_passe_hash);
                $stmt->bindParam(':email', $email);

                if ($stmt->execute()) {
                    echo "Inscription réussie ! Vous pouvez maintenant vous <a href='login.php'>connecter</a>.";
                } else {
                    echo "Erreur lors de l'inscription.";
                }
            }
        }
    }
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Gestion de Stock</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            max-width: 90%;
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .login-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .login-container label {
            margin-bottom: 10px;
            color: #555;
        }
        .login-container input[type="text"],
        .login-container input[type="password"],
        .login-container input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        .login-container input[type="text"]:focus,
        .login-container input[type="password"]:focus
        .login-container input[type="email"]:focus {
            outline: none;
            border-color: rgb(6, 183, 233); /* Couleur accentuée */
        }
        .login-container button {
            padding: 10px 20px;
            background-color: rgb(6, 183, 233);
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .login-container button:hover {
            background-color: #293949; /* Couleur accentuée plus sombre au survol */
        }
        .login-container .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style></head>
<body>
<div class="login-container">
    <img src="../public/images/icon/logo2.png" alt="Logo" class="login-logo">
        <h2>Inscription <br>Gestion de Stock</h2>
   
    <form method="post" action="">
        <label for="nom_utilisateur">Nom d'utilisateur:</label>
        <input type="text" id="nom_utilisateur" name="nom_utilisateur" required><br><br>
        <label for="email">Adresse email:</label> <!-- Champ pour l'email -->
        <input type="email" id="email" name="email" required><br><br>
        <label for="mot_de_passe">Mot de passe:</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required><br><br>
        <button type="submit">Inscription</button>
    </form>
</body>
</html>

