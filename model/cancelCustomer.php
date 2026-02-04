<?php
include 'connexion.php';

if (!empty($_GET['idCustomer'])) {
    // Mettre à jour l'état du client
    $sql = "UPDATE client SET etat='0' WHERE id=?";
    $req = $connexion->prepare($sql);
    $req->execute(array($_GET['idCustomer']));

    if ($req->rowCount() != 0) {
        // Définir un message de succès
        $_SESSION['message'] = array(
            'type' => 'success',
            'text' => 'Le client a été désactivé avec succès.'
        );
    } else {
        // Définir un message d'erreur si la mise à jour a échoué
        $_SESSION['message'] = array(
            'type' => 'error',
            'text' => 'Erreur lors de la désactivation du client.'
        );
    }
} else {
    // Définir un message d'erreur si les paramètres sont manquants
    $_SESSION['message'] = array(
        'type' => 'error',
        'text' => 'Paramètres manquants pour la désactivation du client.'
    );
}

header('Location: ../vue/customer.php');