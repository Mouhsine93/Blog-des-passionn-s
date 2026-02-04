<?php
include 'connexion.php';

if (!empty($_POST['libelle_categorie'])) {
    $sql = "INSERT INTO categorie_article(libelle_categorie) VALUES(?)";
    $req = $connexion->prepare($sql);
    
    $req->execute(array(
        $_POST['libelle_categorie']
    ));
    
    if ($req->rowCount() != 0) {
        $_SESSION['message'] = array(
            'text' => "Catégorie ajoutée avec succès.",
            'type' => "success"
        );
    } else {
        $_SESSION['message'] = array(
            'text' => "Une erreur s'est produite lors de l'ajout de la catégorie.",
            'type' => "danger"
        );
    }
} else {
    $_SESSION['message'] = array(
        'text' => "Une information obligatoire non renseignée.",
        'type' => "danger"
    );
}

header('Location: ../vue/category.php');
exit();
?>
