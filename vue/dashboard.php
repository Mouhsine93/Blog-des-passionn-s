<?php
// Au début de chaque page PHP


// Réinitialisation des variables de session
if (!isset($_SESSION['initialized'])) {
    $_SESSION['initialized'] = true;

    // Réinitialisation des données spécifiques à l'utilisateur
    $_SESSION['commandes'] = 0;
    $_SESSION['ventes'] = 0;
    // ... autres données à réinitialiser ...
}

include 'navbar.php';
date_default_timezone_set('Europe/Paris');
?>
<div class="home-content">
    <div class="overview-boxes">
        <div class="box">
            <div class="right-side">
                <div class="box-topic">Commande(s)</div> 
                <div class="number"><?php echo getAllCommande()['nbre'] ?></div>
                <div class="indicator">
                    <i class="bx bx-up-arrow-alt"></i>
                    <span class="text">Depuis hier</span>
                </div>
            </div>
            <i class="bx bx-cart-alt cart"></i>
        </div>
        <div class="box">
            <div class="right-side">
                <div class="box-topic">Vente(s)</div>
                <div class="number"><?php echo getAllVente()['nbre'] ?></div>
                <div class="indicator">
                    <i class="bx bx-up-arrow-alt"></i>
                    <span class="text">Depuis hier</span>
                </div>
            </div>
            <i class="bx bxs-cart-add cart two"></i>
        </div>
        <div class="box">
            <div class="right-side">
                <div class="box-topic">Article(s)</div>
                <div class="number"><?php echo getAllArticle()['nbre'] ?></div>
                <div class="indicator">
                    <i class="bx bx-up-arrow-alt"></i>
                    <span class="text">Depuis hier</span>
                </div>
            </div>
            <i class="bx bx-cart cart three"></i>
        </div>
        <div class="box">
            <div class="right-side">
                <div class="box-topic">Chiffre d'Affaire  (€)</div>
                <div class="number"><?php echo number_format(getCA()['prix'], 0, ',', ' ') ?></div>
                <div class="indicator">
                    <i class="bx bx-down-arrow-alt down"></i>
                    <span class="text">Aujourd'hui</span>
                </div>
            </div>
            <i class="bx bxs-cart-download cart four"></i>
        </div>
    </div>

    <div class="sales-boxes">
    <div class="recent-sales box">
        <div class="title">Ventes récentes  (€)</div>
        <?php $ventes = getLastVente(); ?>
        <div class="sales-details">
            <table class="sales-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Article</th>
                        <th>Prix</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventes as $vente): ?>
                    <tr>
                        <td><?php echo date('d M Y', strtotime($vente['date_vente'])); ?></td>
                        <td><?php echo $vente['nom'] . " " . $vente['prenom']; ?></td>
                        <td><?php echo $vente['nom_article']; ?></td>
                        <td><?php echo number_format($vente['prix'], 0, ",", " ") ; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="top-sales box">
        <div class="title">Articles les plus vendus (€)</div>
        <ul class="top-sales-details">
            <?php $articles = getMostVente(); ?>
            <?php foreach ($articles as $article): ?>
                <li>
                    <span class="product"><?php echo $article['nom_article']; ?></span>
                    <span class="price"><?php echo number_format($article['prix'], 0, ",", " "); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>


<?php
include 'footer.php';
?>