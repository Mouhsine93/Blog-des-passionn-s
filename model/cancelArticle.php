<?php
include 'connexion.php';

if (!empty($_GET['idArticle'])) {
    // Mettre à jour l'état de l'article
    $sql = "UPDATE article SET etat='0' WHERE id=?";
    $req = $connexion->prepare($sql);
    $req->execute(array($_GET['idArticle']));

    if ($req->rowCount() != 0) {
        // Définir un message de succès
        $_SESSION['message'] = array(
            'type' => 'success',
            'text' => 'L\'article a été désactivé avec succès.'
        );
    } else {
        // Définir un message d'erreur si la mise à jour a échoué
        $_SESSION['message'] = array(
            'type' => 'error',
            'text' => 'Erreur lors de la désactivation de l\'article.'
        );
    }
} else {
    // Définir un message d'erreur si les paramètres sont manquants
    $_SESSION['message'] = array(
        'type' => 'error',
        'text' => 'Paramètres manquants pour la désactivation de l\'article.'
    );
}

header('Location: ../vue/article.php');
