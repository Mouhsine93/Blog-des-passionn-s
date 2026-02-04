<?php
include 'navbar.php';
date_default_timezone_set('Europe/Paris');

if (!empty($_GET['id'])) {
    $commande = getCommande($_GET['id']);
}
?>

<div class="home-content">
    <button class="hidden-print" id="btnPrint" style="position: relative; left: 45%;"> <i class='bx bx-printer'></i> Imprimer </button>

    <div class="page">
        <div class="cote-a-cote">
            <h2>Gestion de Stock</h2>
            <div>
                <p>Reçu N° : <?= $commande['id'] ?></p>
                <p>Date : <?= date('d/m/Y H:i:s', strtotime($commande['date_commande'])) ?></p>
            </div>
        </div>

        <div class="cote-a-cote" style="width: 50%;">
            <p> Nom :</p>
            <p><?= $commande['nom'] . " " . $commande['prenom'] ?></p>
        </div>

        <div class="cote-a-cote" style="width: 50%;">
            <p> Tel :</p>
            <p><?= isset($commande['telephone']) ? $commande['telephone'] : 'Non renseigné' ?></p>
        </div>

        <div class="cote-a-cote" style="width: 50%;">
            <p> Adresse :</p>
            <p><?= isset($commande['adresse']) ? $commande['adresse'] : 'Non renseignée' ?></p>
        </div>

        <br>
        <table class="mtable">
            <tr>
                <th>Designation</th>
                <th>Quantité</th>
                <th>Prix unitaire (€)</th>
                <th>Prix total (€)</th>
            </tr>
            <tr>
                <td data-label='Designation'><?= $commande['nom_article'] ?></td>
                <td data-label='Quantité'><?= $commande['quantite'] ?></td>
                <td data-label='Prix unitaire (€)'><?= $commande['prix_unitaire'] ?></td>
                <td data-label='Prix total (€)'><?= $commande['prix'] ?></td>
            </tr>
        </table>
    </div>
</div>

<?php
include 'footer.php';
?>

<script>
var btnPrint = document.querySelector('#btnPrint');
btnPrint.addEventListener("click", () => {
    window.print();
});
</script>
