<?php
session_start();
require '../model/connexion.php';
date_default_timezone_set('Europe/Paris');


function logConnexion($nom_utilisateur_ou_email, $statut) {
    $log_message = date('Y-m-d H:i:s') . " - Tentative de connexion : " . 
                   "Utilisateur: " . $nom_utilisateur_ou_email . 
                   ", Statut: " . $statut;
    $log_message .= "\n";
    file_put_contents('../logs/connexion.log', $log_message, FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') { //manipulation formulaire + variable superglobale = $_SERVER, $_POST, $_SESSION fourni par php
    $nom_utilisateur_ou_email = trim($_POST['nom_utilisateur']);
    $mot_de_passe = trim($_POST['mot_de_passe']);

    $stmt = $connexion->prepare("SELECT id, nom_utilisateur, email, mot_de_passe_hash FROM utilisateur WHERE nom_utilisateur = :nom_utilisateur OR email = :email");
    $stmt->bindParam(':nom_utilisateur', $nom_utilisateur_ou_email);
    $stmt->bindParam(':email', $nom_utilisateur_ou_email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC); //stocker et manipuler les résultats d'une requête

    if (!$result) {
        $error = "Utilisateur inconnu. Veuillez vérifier votre nom d'utilisateur ou email.";
        logConnexion($nom_utilisateur_ou_email, "Échec - Utilisateur inconnu");
    } elseif ($result && password_verify($mot_de_passe, $result['mot_de_passe_hash'])) {
        

        $jeton = bin2hex(random_bytes(16));
        $_SESSION['jeton'] = $jeton;

        $stmt = $connexion->prepare("UPDATE utilisateur SET jeton = :jeton WHERE id = :id");
        $stmt->bindParam(':jeton', $jeton);
        $stmt->bindParam(':id', $result['id']);
        $stmt->execute();

        $_SESSION['email'] = $result['email'];
        $_SESSION['nom_utilisateur'] = $result['nom_utilisateur'];

        logConnexion($nom_utilisateur_ou_email, "Réussi");

        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Mot de passe incorrect.";
        logConnexion($nom_utilisateur_ou_email, "Échec - Mot de passe incorrect");
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion de Stock</title>
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
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        .login-container input[type="text"]:focus,
        .login-container input[type="password"]:focus {
            outline: none;
            border-color: rgb(6, 183, 233);
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
            background-color: #293949;
        }
        .login-container .error-message {
            color: red;
            margin-bottom: 10px;
        }
        .warning-message {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="../public/images/icon/logo2.png" alt="Logo" class="login-logo">
        <h2>Connexion <br>Gestion de Stock</h2>
        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label for="nom_utilisateur">Nom d'utilisateur ou Email :</label>
            <input type="text" id="nom_utilisateur" name="nom_utilisateur" required><br><br>
            <label for="mot_de_passe">Mot de passe :</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required><br><br>
            <button type="submit">Connexion</button>
        </form>
    </div>
</body>
</html>
