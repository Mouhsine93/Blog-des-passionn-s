<?php
include 'navbar.php';
date_default_timezone_set('Europe/Paris');

if (!empty($_GET['id'])) {
    $article = getVente($_GET['id']);
}

$ventes = getVente(); // Récupération de toutes les ventes
?>

<div class="home-content">
    <div class="overview-boxes">
        <div class="box">
            <form action="<?= !empty($_GET['id']) ? "../model/editSale.php" : "../model/addSale.php" ?>" method="post">
                <input value="<?= !empty($_GET['id']) ? htmlspecialchars($article['id']) : "" ?>" type="hidden" name="id" id="id">

                <label for="id_article">Article</label>
                <select onchange="setPrix()" name="id_article" id="id_article">
                    <?php
                    $articles = getArticle();
                    if (!empty($articles) && is_array($articles)) {
                        foreach ($articles as $value) {
                            echo "<option data-prix=\"" . htmlspecialchars($value['prix_unitaire']) . "\" value=\"" . htmlspecialchars($value['id']) . "\">" . htmlspecialchars($value['nom_article'] . " - " . $value['quantite'] . " disponible") . "</option>";
                        }
                    }
                    ?>
                </select>

                <label for="id_client">Client</label>
                <select name="id_client" id="id_client">
                    <?php
                    $clients = getClient();
                    if (!empty($clients) && is_array($clients)) {
                        foreach ($clients as $value) {
                            echo "<option value=\"" . htmlspecialchars($value['id']) . "\">" . htmlspecialchars($value['nom'] . " " . $value['prenom']) . "</option>";
                        }
                    }
                    ?>
                </select>

                <label for="quantite">Quantité</label>
                <input onkeyup="setPrix()" value="<?= !empty($_GET['id']) ? htmlspecialchars($article['quantite']) : "" ?>" name="quantite" id="quantite" placeholder="Veuillez saisir la quantité">

                <label for="prix">Prix (€)</label>
                <input value="<?= !empty($_GET['id']) ? htmlspecialchars($article['prix']) : "" ?>" name="prix" id="prix" placeholder="Veuillez saisir le prix">

                <button type="submit">Valider</button>

                <?php
                if (!empty($_SESSION['message']['text'])) {
                    echo "<div class=\"alert " . htmlspecialchars($_SESSION['message']['type']) . "\">" . htmlspecialchars($_SESSION['message']['text']) . "</div>";
                }
                ?>
            </form>
        </div>

        <div class="box">
   
        <table class="mtable">
            <thead>
                <tr>
                    <th>Article</th>
                    <th>Client</th>
                    <th>Quantité</th>
                    <th>Prix (€)</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
               
                $vente = getVente();
                
                if (!empty($vente) && is_array($vente)) {
                    foreach ($vente as $key => $value) {
                ?>
                        <tr>
                            <td data-label='Article'><?= htmlspecialchars($value['nom_article']) ?></td>
                            <td data-label='Client'><?= htmlspecialchars($value['nom'] . " " . $value['prenom']) ?></td>
                            <td data-label='Quantité'><?= htmlspecialchars($value['quantite']) ?></td>
                            <td data-label='Prix (€)'><?= htmlspecialchars($value['prix']) ?></td>
                            <td data-label='Date'><?= date('d/m/Y H:i:s', strtotime($value['date_vente'])) ?></td>
                            <td data-label='Action'>
                                <a href="saleReceipt.php?id=<?= htmlspecialchars($value['id']) ?>"><i class='bx bx-receipt'></i></a>
                                <a onclick="cancelSale(<?= htmlspecialchars($value['id']) ?>, <?= htmlspecialchars($value['idArticle']) ?>, <?= htmlspecialchars($value['quantite']) ?>)" style="color: red;"><i class='bx bx-stop-circle'></i></a>
                            </td>
                        </tr>
                <?php
                    }
                }
                ?>
                
            </tbody>
        </table>
    </div>
</div>
</div>

<?php include 'footer.php'; ?>

<script>
function cancelSale(idVente, idArticle, quantite) {
    if (confirm("Voulez-vous vraiment annuler cette vente ?")) {
        window.location.href = "../model/cancelSale.php?idVente=" + idVente + "&idArticle=" + idArticle + "&quantite=" + quantite;
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


