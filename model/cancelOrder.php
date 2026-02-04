<?php
include 'connexion.php';

if (!empty($_GET['idCommande']) && !empty($_GET['idArticle']) && !empty($_GET['quantite'])) {
    // Mettre à jour l'état de la commande
    $sql = "UPDATE commande SET etat='0' WHERE id=?";
    $req = $connexion->prepare($sql);
    $req->execute(array($_GET['idCommande']));

    if ($req->rowCount() != 0) {
        // Mettre à jour la quantité de l'article
        $sql = "UPDATE article SET quantite=quantite+? WHERE id=?";
        $req = $connexion->prepare($sql);
        $req->execute(array($_GET['quantite'], $_GET['idArticle']));

        // Définir un message de succès
        $_SESSION['message'] = array(
            'type' => 'success',
            'text' => 'La commande a été annulée avec succès.'
        );
    } else {
        // Définir un message d'erreur si la mise à jour a échoué
        $_SESSION['message'] = array(
            'type' => 'error',
            'text' => 'Erreur lors de l\'annulation de la commande.'
        );
    }
} else {
    // Définir un message d'erreur si les paramètres sont manquants
    $_SESSION['message'] = array(
        'type' => 'error',
        'text' => 'Paramètres manquants pour l\'annulation de la commande.'
    );
}

header('Location: ../vue/order.php');