<?php
include 'navbar.php';
date_default_timezone_set('Europe/Paris');

if (!empty($_GET['id'])) {
    $article = getCommande($_GET['id']);
}

?>


<div class="home-content">
    <div class="overview-boxes">
        <div class="box">
            <form action="<?= !empty($_GET['id']) ? "../model/editOrder.php" : "../model/addOrder.php" ?>" method="post">
                <input value="<?= !empty($_GET['id']) ? $article['id'] : "" ?>" type="hidden" name="id" id="id">

                <label for="id_article">Article</label>
                <select onchange="setPrix()" name="id_article" id="id_article">
                    <?php
                    $articles = getArticle();
                    if (!empty($articles) && is_array($articles)) {
                        foreach ($articles as $key => $value) {
                    ?>
                            <option data-prix="<?= $value['prix_unitaire'] ?>" value="<?= $value['id'] ?>"><?= $value['nom_article'] . " - " . $value['quantite'] . " disponible" ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>

                <label for="id_fournisseur">Fournisseur</label>
                <select name="id_fournisseur" id="id_fournisseur">
                    <?php
                    $clients = getFournisseur();
                    if (!empty($clients) && is_array($clients)) {
                        foreach ($clients as $key => $value) {
                    ?>
                            <option value="<?= $value['id'] ?>"><?= $value['nom'] . " " . $value['prenom'] ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>

                <label for="quantite">Quantité</label>
                <input onkeyup="setPrix()" value="<?= !empty($_GET['id']) ? $article['quantite'] : "" ?>" name="quantite" id="quantite" placeholder="Veuillez saisir la quantité">

                <label for="prix">Prix (€)</label>
                <input value="<?= !empty($_GET['id']) ? $article['prix'] : "" ?>" name="prix" id="prix" placeholder="Veuillez saisir le prix">

                <button type="submit">Valider</button>

                <?php
                if (!empty($_SESSION['message']['text'])) {
                ?>
                    <div class="alert <?= $_SESSION['message']['type'] ?>">
                        <?= $_SESSION['message']['text'] ?>
                    </div>
                <?php
                }
                ?>
            </form>
        </div>

        <div class="box">
   
        <table class="mtable">
            <thead><tr>
                    <th>Article</th>
                    <th>Fournisseur</th>
                    <th>Quantité</th>
                    <th>Prix (€)</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
                <tbody>
                <?php
                $vente = getCommande();
                if (!empty($vente) && is_array($vente)) {
                    foreach ($vente as $key => $value) {
                ?>
                        <tr>
                            <td data-label='Article'><?= $value['nom_article'] ?></td>
                            <td data-label='Fournisseur'><?= $value['nom'] . " " . $value['prenom'] ?></td>
                            <td data-label='Quantité'><?= $value['quantite'] ?></td>
                            <td data-label='Prix (€)'><?= $value['prix'] ?></td>
                            <td data-label='Date'><?= date('d/m/Y H:i:s', strtotime($value['date_commande'])) ?></td>
                            <td data-label='Action'>
                                <a href="orderReceipt.php?id=<?= $value['id'] ?>"><i class='bx bx-receipt'></i></a>
                                <a onclick="cancelOrder(<?= $value['id'] ?>, <?= $value['idArticle'] ?>, <?= $value['quantite'] ?>)" style="color: red;"><i class='bx bx-stop-circle'></i></a>
                            </td>
                        </tr>
                <?php
                    }
                }
                ?></tbody>
            </table>
        </div>
    

<?php
include 'footer.php';
?>

<script>
function cancelOrder(idCommande, idArticle, quantite) {
    if (confirm("Voulez-vous vraiment annuler cette commande ?")) {
        window.location.href = "../model/cancelOrder.php?idCommande=" + idCommande + "&idArticle=" + idArticle + "&quantite=" + quantite;
    }
}

function setPrix() {
    var article = document.querySelector('#id_article');
    var quantite = document.querySelector('#quantite');
    var prix = document.querySelector('#prix');

    var prixUnitaire = article.options[article.selectedIndex].getAttribute('data-prix');

    prix.value = Number(quantite.value) * Number(prixUnitaire);
}

</script>
