<?php
session_start();
require '../model/connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Vérifier si l'email existe dans la base de données
    $stmt = $connexion->prepare("SELECT id FROM utilisateur WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Générer un token de réinitialisation sécurisé
        $token = bin2hex(random_bytes(32));
        $expire = date('Y-m-d H:i:s', strtotime('+1 hour')); // Exemple : lien expiré dans 1 heure
        
        // Stocker le token dans la base de données avec l'email et l'heure d'expiration
        $stmt = $connexion->prepare("UPDATE utilisateur SET reset_token = :token, reset_token_expire = :expire WHERE id = :id");
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expire', $expire);
        $stmt->bindParam(':id', $user['id']);
        $stmt->execute();

        // Envoyer un email avec le lien de réinitialisation
        $reset_link = "http://votresite.com/reset_password.php?token=" . $token;
        $to = $email;
        $subject = "Réinitialisation de mot de passe - Gestion de Stock";
        $message = "Bonjour,\n\nPour réinitialiser votre mot de passe, veuillez cliquer sur le lien suivant :\n\n$reset_link\n\nCe lien expire dans 1 heure.\n\nCordialement,\nVotre équipe Gestion de Stock";
        $headers = "From: votre_email@domaine.com\r\n";

        if (mail($to, $subject, $message, $headers)) {
            $success = "Un email de réinitialisation a été envoyé à votre adresse.";
        } else {
            $error = "Une erreur est survenue lors de l'envoi de l'email. Veuillez réessayer.";
        }
    } else {
        $error = "Cette adresse email n'est pas associée à un compte utilisateur.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - Gestion de Stock</title>
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
        .forgot-password-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            max-width: 90%;
            text-align: center;
        }
        .forgot-password-container h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .forgot-password-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .forgot-password-container label {
            margin-bottom: 10px;
            color: #555;
        }
        .forgot-password-container input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        .forgot-password-container input[type="email"]:focus {
            outline: none;
            border-color: rgb(6, 183, 233);
        }
        .forgot-password-container button {
            padding: 10px 20px;
            background-color: rgb(6, 183, 233);
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .forgot-password-container button:hover {
            background-color: #293949;
        }
        .forgot-password-container .error-message {
            color: red;
            margin-bottom: 10px;
        }
        .forgot-password-container .success-message {
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="forgot-password-container">
        <h2>Mot de passe oublié</h2>
        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php elseif (isset($success)): ?>
            <p class="success-message"><?php echo $success; ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label for="email">Adresse email :</label>
            <input type="email" id="email" name="email" required><br><br>
            <button type="submit">Envoyer le lien de réinitialisation</button>
        </form>
    </div>
</body>
</html>
