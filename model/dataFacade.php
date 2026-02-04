<?php
include 'function.php';


$dataFacade = new DataFacade();

$articles = $dataFacade->getArticle();
$client = $dataFacade->getClient(1);
$ventes = $dataFacade->getVente();
$commandes = $dataFacade->getCommande();
$fournisseurs = $dataFacade->getFournisseur();
$allCommandes = $dataFacade->getAllCommande();
$allVentes = $dataFacade->getAllVente();
$allArticles = $dataFacade->getAllArticle();
$ca = $dataFacade->getCA();
$lastVentes = $dataFacade->getLastVente();
$mostVentes = $dataFacade->getMostVente();
$categories = $dataFacade->getCategorie();

print_r($articles);
print_r($client);
print_r($ventes);
print_r($commandes);
print_r($fournisseurs);
print_r($allCommandes);
print_r($allVentes);
print_r($allArticles);
print_r($ca);
print_r($lastVentes);
print_r($mostVentes);
print_r($categories);
