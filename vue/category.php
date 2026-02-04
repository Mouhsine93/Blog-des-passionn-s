<?php
include 'navbar.php';
date_default_timezone_set('Europe/Paris');


if (!empty($_GET['id'])) {
    $categorie = getCategorie($_GET['id']);
}
?>
<div class="home-content">
    <div class="overview-boxes">
        <div class="box">
            <form action="<?= !empty($_GET['id']) ? "../model/editCategory.php" : "../model/addCategory.php" ?>" method="post">
                <label for="libelle_categorie">Libelle</label>
                <input value="<?= !empty($_GET['id']) ? $categorie['libelle_categorie'] : "" ?>" type="text" name="libelle_categorie" id="libelle_categorie" placeholder="Veuillez saisir le libéllé">
                <input value="<?= !empty($_GET['id']) ? $categorie['id'] : "" ?>" type="hidden" name="id" id="id">

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
                <tr>
                    <th>Libelle categorie</th>
                    <th>Action</th>
                </tr>
                <?php
                $categories = getCategorie();

                if (!empty($categories) && is_array($categories)) {
                    foreach ($categories as $key => $value) {
                ?>
                        <tr>
                            <td data-label='Libelle catégorie'><?= $value['libelle_categorie'] ?></td>
                            <td data-label='Action'>
                                <a href="?id=<?= $value['id'] ?>"><i class='bx bx-edit-alt'></i></a>
                                <a onclick="cancelCategory(<?= $value['id'] ?>)" style="color: red; cursor: pointer;"><i class='bx bx-stop-circle'></i></a>
                            </td>
                        </tr>
                <?php
                    }
                }
                ?>
            </table>
        </div>
    </div>
</div>
</section>
<?php
include 'footer.php';
?>

<script>
function cancelCategory(idCategory) {
    if (confirm("Voulez-vous vraiment désactiver cette catégorie ?")) {
        window.location.href = "../model/cancelCategory.php?idCategory=" + idCategory;
    }
}
</script>
