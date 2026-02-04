<?php
include 'connexion.php';

if (!empty($_GET['idFournisseur'])) {
    // Mettre à jour l'état du fournisseur
    $sql = "UPDATE fournisseur SET etat='0' WHERE id=?";
    $req = $connexion->prepare($sql);
    $req->execute(array($_GET['idFournisseur']));

    if ($req->rowCount() != 0) {
        // Définir un message de succès
        $_SESSION['message'] = array(
            'type' => 'success',
            'text' => 'Le fournisseur a été désactivé avec succès.'
        );
    } else {
        // Définir un message d'erreur si la mise à jour a échoué
        $_SESSION['message'] = array(
            'type' => 'error',
            'text' => 'Erreur lors de la désactivation du fournisseur.'
        );
    }
} else {
    // Définir un message d'erreur si les paramètres sont manquants
    $_SESSION['message'] = array(
        'type' => 'error',
        'text' => 'Paramètres manquants pour la désactivation du fournisseur.'
    );
}

header('Location: ../vue/supplier.php');
