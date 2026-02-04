<?php

include 'navbar.php';

$searchCriteria = [];
if (isset($_GET['query'])) {
    $searchCriteria['nom_article'] = $_GET['query'];
}
if (isset($_GET['categorie'])) {
    $searchCriteria['id_categorie'] = $_GET['categorie'];
}
// Ajoutez d'autres critères de recherche selon vos besoins

$articles = getArticle(null, $searchCriteria);

// Afficher un message avec le nombre de résultats trouvés
$nombreResultats = count($articles);
if ($nombreResultats > 0) {
    echo "<p>Nous avons trouvé $nombreResultats résultat(s) pour votre recherche.</p>";
} else {
    echo "<p>Aucun résultat trouvé pour votre recherche.</p>";
}

// Afficher les résultats de la recherche
foreach ($articles as $article) {
    echo "<p><a href='article.php?id=" . $article['id'] . "'>" . htmlspecialchars($article['nom_article']) . "</a></p>";
}
