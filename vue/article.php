<?php
include 'navbar.php';
date_default_timezone_set('Europe/Paris');

// Initialiser une variable pour stocker le message d'erreur
$errorMessage = '';

if (isset($_GET['id'])) {
    $articleId = $_GET['id'];
    $article = getArticle($articleId);
    
    if (!$article) {
        $errorMessage = "Article non trouvé.";
    }
} else {
    $errorMessage = "Aucun ID d'article spécifié.";
}
?>


<div class="home-content">
    <div class="overview-boxes">
        <div class="box">
            <form action="<?= !empty($_GET['id']) ? "../model/editArticle.php" : "../model/addArticle.php" ?>" method="post" enctype="multipart/form-data">
                <label for="nom_article">Nom de l'article</label>
                <input value="<?= !empty($_GET['id']) ? $article['nom_article'] : "" ?>" type="text" name="nom_article" id="nom_article" placeholder="Veuillez saisir le nom">
                <input value="<?= !empty($_GET['id']) ? $article['id'] : "" ?>" type="hidden" name="id" id="id">
        
                <label for="id_categorie">Catégorie</label>
                <select name="id_categorie" id="id_categorie">
                    <option value="">--Choisir une catégorie--</option>
                    <?php
                    $categories = getCategorie();
                    if (is_array($categories) && !empty($categories)) {
                        foreach ($categories as $key => $value) {
                    ?>
                            <option <?= !empty($_GET['id']) && $article['id_categorie'] == $value['id'] ? "selected" : "" ?> value="<?= $value['id'] ?>"><?= $value['libelle_categorie'] ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
        
                <label for="quantite">Quantité</label>
                <input value="<?= !empty($_GET['id']) ? $article['quantite'] : "" ?>" type="number" name="quantite" id="quantite" placeholder="Veuillez saisir la quantité">

                <label for="prix_unitaire">Prix unitaire (€)</label>
                <input value="<?= !empty($_GET['id']) ? $article['prix_unitaire'] : "" ?>" type="number" name="prix_unitaire" id="prix_unitaire" placeholder="Veuillez saisir le prix">

                <label for="date_fabrication">Date de fabrication</label>
                <input value="<?= !empty($_GET['id']) ? $article['date_fabrication'] : "" ?>" type="datetime-local" name="date_fabrication" id="date_fabrication">

                <label for="date_expiration">Date d'expiration</label>
                <input value="<?= !empty($_GET['id']) ? $article['date_expiration'] : "" ?>" type="datetime-local" name="date_expiration" id="date_expiration">

                <label for="images">Images</label>
                <input value="<?= !empty($_GET['id']) ? $article['images'] : "" ?>" type="file" name="images" id="images">

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
        <div style="display: block;" class="box ">
            <form action="" method="get">
                <table class="mtable">
                    <tr>
                        <th>Nom article</th>
                        <th>Catégorie</th>
                        <th>Quantité</th>
                        <th>Prix unitaire (€)</th>
                        <th>Date fabrication</th>
                        <th>Date expiration</th>
                    </tr>
                    <tr>
                        <td data-label='Nom Article'>
                            <input type="text" name="nom_article" id="nom_article" placeholder="Veuillez saisir le nom">
                        </td>
                        <td data-label='Catégorie'>
                            <select name="id_categorie" id="id_categorie">
                                <option value="">--Choisir une catégorie--</option>
                                <?php
                                $categories = getCategorie();
                                if (is_array($categories) && !empty($categories)) {
                                    foreach ($categories as $key => $value) {
                                ?>
                                        <option <?= !empty($_GET['id']) && $article['id_categorie'] == $value['id'] ? "selected" : "" ?> value="<?= $value['id'] ?>"><?= $value['libelle_categorie'] ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </td>
                        <td data-label='Quantité'>
                            <input type="number" name="quantite" id="quantite" placeholder="Veuillez saisir la quantité">
                        </td>
                        <td data-label='Prix unitaire (€)'>
                            <input type="number" name="prix_unitaire" id="prix_unitaire" placeholder="Veuillez saisir le prix">
                        </td>
                        <td data-label='Date fabrication'>
                            <input type="date" name="date_fabrication" id="date_fabrication">
                        </td>
                        <td data-label='Date expiration'>
                            <input type="date" name="date_expiration" id="date_expiration">
                        </td>
                    </tr>
                </table>
                <br>
                <button type="submit">Valider</button>
            
            <br><br>
            <table class="mtable">
                <tr>
                    <th>Nom article</th>
                    <th>Catégorie</th>
                    <th>Quantité</th>
                    <th>Prix unitaire (€)</th>
                    <th>Date fabrication</th>
                    <th>Date expiration</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
                <?php
                if (!empty($_GET)) {
                    $articles = getArticle(null, $_GET);
                } else {
                    $articles = getArticle();
                }
                
                if (!empty($articles) && is_array($articles)) {
                    foreach ($articles as $key => $value) {
                ?>
                        <tr>
                            <td data-label='Nom article'><?= $value['nom_article'] ?></td>
                            <td data-label='Catégorie'><?= $value['libelle_categorie'] ?></td>
                            <td data-label='Quantité'><?= $value['quantite'] ?></td>
                            <td data-label='Prix unitaire (€)'><?= $value['prix_unitaire'] ?></td>
                            <td data-label='Date fabrication'><?= date('d/m/Y H:i:s', strtotime($value['date_fabrication'])) ?></td>
                            <td data-label='Date expiration'><?= date('d/m/Y H:i:s', strtotime($value['date_expiration'])) ?></td>
                            <td data-label='Image'><img width="100" height="100" src="<?= $value['images'] ?>" alt="<?= $value['nom_article'] ?>"></td>
                            <td data-label='Action'>
                                <a href="?id=<?= $value['id'] ?>"><i class='bx bx-edit-alt'></i></a>
                                <a onclick="cancelArticle(<?= $value['id'] ?>)" style="color: red; cursor: pointer;"><i class='bx bx-stop-circle'></i></a>
                            </td>
                        </tr>
                <?php
                    }
                }
                ?>
            </table>
        </div></form> </div>
    </div>
</div>
</section>

<?php
include 'footer.php';
?>

<script>
function cancelArticle(idArticle) {
    if (confirm("Voulez-vous vraiment désactiver cet article ?")) {
        window.location.href = "../model/cancelArticle.php?idArticle=" + idArticle;
    }
}
</script>
