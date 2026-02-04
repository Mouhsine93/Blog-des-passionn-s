<?php
include 'connexion.php';
include_once "function.php";

session_start();

if (!empty($_POST['id_article']) && !empty($_POST['id_client']) && !empty($_POST['quantite']) && !empty($_POST['prix'])) {
    try {
        $article = getArticle($_POST['id_article']);

        if (!empty($article) && is_array($article)) {
            if ($_POST['quantite'] > $article['quantite']) {
                $_SESSION['message']['text'] = "La quantité à vendre n'est pas disponible";
                $_SESSION['message']['type'] = "danger";
            } else {
                $sql = "INSERT INTO vente (id_article, id_client, quantite, prix) VALUES (?, ?, ?, ?)";
                $req = $connexion->prepare($sql);
                $req->execute([
                    $_POST['id_article'],
                    $_POST['id_client'],
                    $_POST['quantite'],
                    $_POST['prix']
                ]);

                if ($req->rowCount() != 0) {
                    $sql = "UPDATE article SET quantite = quantite - ? WHERE id = ?";
                    $req = $connexion->prepare($sql);
                    $req->execute([
                        $_POST['quantite'],
                        $_POST['id_article']
                    ]);

                    if ($req->rowCount() != 0) {
                        $_SESSION['message']['text'] = "Vente effectuée avec succès";
                        $_SESSION['message']['type'] = "success";
                    } else {
                        $_SESSION['message']['text'] = "Impossible de mettre à jour la quantité de l'article.";
                        $_SESSION['message']['type'] = "danger";
                    }
                } else {
                    $_SESSION['message']['text'] = "Une erreur s'est produite lors de la vente.";
                    $_SESSION['message']['type'] = "danger";
                }
            }
        } else {
            $_SESSION['message']['text'] = "Article introuvable.";
            $_SESSION['message']['type'] = "danger";
        }
    } catch (Exception $e) {
        $_SESSION['message']['text'] = "Erreur: " . $e->getMessage();
        $_SESSION['message']['type'] = "danger";
    }
} else {
    $_SESSION['message']['text'] = "Une information obligatoire non renseignée.";
    $_SESSION['message']['type'] = "danger";
}

header('Location: ../vue/sale.php');
