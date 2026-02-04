<?php
include 'connexion.php';
if (
    !empty($_POST['nom'])
    && !empty($_POST['prenom'])
    && !empty($_POST['telephone'])
    && !empty($_POST['adresse'])
    && !empty($_POST['id'])
) {

$sql = "UPDATE fournisseur SET nom=?, prenom=?, telephone=?, adresse=? WHERE id=?";
    $req = $connexion->prepare($sql);
    
    $req->execute(array(
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['telephone'],
        $_POST['adresse'],
        $_POST['id']
    ));
    
    if ( $req->rowCount()!=0) {
        $_SESSION['message']['text'] =  "Fournisseur modifié avec succès.";
        $_SESSION['message']['type'] = "success";
    }else {
        $_SESSION['message']['text'] = "Aucune modification n'a été effectuée.";
        $_SESSION['message']['type'] = "warning";
    }

} else {
    $_SESSION['message']['text'] ="Une information obligatoire non renseignée.";
    $_SESSION['message']['type'] = "danger";
}

header('Location: ../vue/supplier.php');