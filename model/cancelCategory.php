<?php
include 'connexion.php';

if (!empty($_GET['idCategory'])) {
    // Mettre à jour l'état de la catégorie
    $sql = "UPDATE categorie_article SET etat='0' WHERE id=?";
    $req = $connexion->prepare($sql);
    $req->execute(array($_GET['idCategory']));

    if ($req->rowCount() != 0) {
        // Définir un message de succès
        $_SESSION['message'] = array(
            'type' => 'success',
            'text' => 'La catégorie a été désactivée avec succès.'
        );
    } else {
        // Définir un message d'erreur si la mise à jour a échoué
        $_SESSION['message'] = array(
            'type' => 'error',
            'text' => 'Erreur lors de la désactivation de la catégorie.'
        );
    }
} else {
    // Définir un message d'erreur si les paramètres sont manquants
    $_SESSION['message'] = array(
        'type' => 'error',
        'text' => 'Paramètres manquants pour la désactivation de la catégorie.'
    );
}

header('Location: ../vue/category.php');
