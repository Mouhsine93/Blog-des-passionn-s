<?php
include 'connexion.php';

if (!empty($_POST['libelle_categorie']) && !empty($_POST['id'])) {
    $sql = "UPDATE categorie_article SET libelle_categorie=? WHERE id=?";
    $req = $connexion->prepare($sql);
    
    $req->execute(array(
        $_POST['libelle_categorie'],
        $_POST['id']
    ));
    
    if ($req->rowCount() != 0) {
        $_SESSION['message'] = array(
            'text' => "Catégorie modifiée avec succès.",
            'type' => "success"
        );
    } else {
        $_SESSION['message'] = array(
            'text' => "Aucune modification n'a été effectuée.",
            'type' => "warning"
        );
    }
} else {
    $_SESSION['message'] = array(
        'text' => "Une information obligatoire non renseignée.",
        'type' => "danger"
    );
}

header('Location: ../vue/category.php');