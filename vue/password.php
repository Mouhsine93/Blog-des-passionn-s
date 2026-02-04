<?php

//Limitation des tentatives de connexion :

// Au début du fichier, après la connexion à la base de données
$max_tentatives = 3;
$temps_blocage = 15 * 60; // 15 minutes

// Avant la vérification du mot de passe
$stmt = $connexion->prepare("SELECT tentatives_connexion, derniere_tentative FROM utilisateur WHERE nom_utilisateur = :nom_utilisateur OR email = :email");
$stmt->execute([':nom_utilisateur' => $nom_utilisateur_ou_email, ':email' => $nom_utilisateur_ou_email]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user_data && $user_data['tentatives_connexion'] >= $max_tentatives && time() - $user_data['derniere_tentative'] < $temps_blocage) {
    $error = "Trop de tentatives. Veuillez réessayer dans 15 minutes.";
    logConnexion($nom_utilisateur_ou_email, "Échec - Compte bloqué");
} else {
    // Vérification du mot de passe et mise à jour des tentatives
    if (!$result || !password_verify($mot_de_passe, $result['mot_de_passe_hash'])) {
        $stmt = $connexion->prepare("UPDATE utilisateur SET tentatives_connexion = tentatives_connexion + 1, derniere_tentative = :time WHERE nom_utilisateur = :nom_utilisateur OR email = :email");
        $stmt->execute([':time' => time(), ':nom_utilisateur' => $nom_utilisateur_ou_email, ':email' => $nom_utilisateur_ou_email]);
        // ... (code d'erreur existant)
    } else {
        // Réinitialisation des tentatives en cas de succès
        $stmt = $connexion->prepare("UPDATE utilisateur SET tentatives_connexion = 0 WHERE id = :id");
        $stmt->execute([':id' => $result['id']]);
        // ... (code de connexion réussie existant)
    }
}


// Double authentification :

// Après la vérification du mot de passe réussie
if ($result['double_auth_active']) {
    $_SESSION['auth_temp'] = $result['id'];
    header('Location: double_auth.php');
    exit;
}
// Sinon, continuer avec la connexion normale


// Journalisation des activités de connexion(partie Réalisation) :

// Modifier la fonction logConnexion
function logConnexion($nom_utilisateur_ou_email, $statut, $ip_address) {
    $log_message = date('Y-m-d H:i:s') . " - Tentative de connexion : " . 
                   "Utilisateur: " . $nom_utilisateur_ou_email . 
                   ", Statut: " . $statut .
                   ", IP: " . $ip_address;
    $log_message .= "\n";
    file_put_contents('../logs/connexion.log', $log_message, FILE_APPEND);
}

// Utilisation
$ip_address = $_SERVER['REMOTE_ADDR'];
logConnexion($nom_utilisateur_ou_email, $statut, $ip_address);


// Renforcement de la politique de mots de passe :

// Fonction de vérification de la complexité du mot de passe
function verifierComplexiteMotDePasse($mot_de_passe) {
    // Au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $mot_de_passe);
}

// Lors de la création ou modification du mot de passe
if (!verifierComplexiteMotDePasse($mot_de_passe)) {
    $error = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
    // Gérer l'erreur...
}

