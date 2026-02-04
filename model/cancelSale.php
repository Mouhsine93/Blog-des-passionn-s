<?php
include 'connexion.php';

if (
    !empty($_GET['idVente']) &&
    !empty($_GET['idArticle']) &&
    !empty($_GET['quantite'])
) {
    try {
        // Commencer une transaction
        $connexion->beginTransaction();

        // Mettre à jour l'état de la vente
        $sql = "UPDATE vente SET etat=? WHERE id=?";
        $req = $connexion->prepare($sql);
        $req->execute(array(0, $_GET['idVente']));

        if ($req->rowCount() != 0) {
            // Mettre à jour la quantité de l'article
            $sql = "UPDATE article SET quantite=quantite+? WHERE id=?";
            $req = $connexion->prepare($sql);
            $req->execute(array($_GET['quantite'], $_GET['idArticle']));

            // Si tout s'est bien passé, valider la transaction
            $connexion->commit();

            // Définir un message de succès
            $_SESSION['message'] = array(
                'type' => 'success',
                'text' => 'La vente a été annulée avec succès.'
            );
        } else {
            // Si la vente n'a pas été trouvée ou déjà annulée
            throw new Exception("La vente n'a pas pu être annulée.");
        }
    } catch (Exception $e) {
        // En cas d'erreur, annuler la transaction
        $connexion->rollBack();

        // Définir un message d'erreur
        $_SESSION['message'] = array(
            'type' => 'error',
            'text' => 'Erreur lors de l\'annulation de la vente : ' . $e->getMessage()
        );
    }
} else {
    // Si des paramètres sont manquants
    $_SESSION['message'] = array(
        'type' => 'error',
        'text' => 'Paramètres manquants pour l\'annulation de la vente.'
    );
}

// Rediriger vers la page des ventes
header('Location: ../vue/sale.php');