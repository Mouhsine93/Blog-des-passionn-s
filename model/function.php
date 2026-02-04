<?php
include 'connexion.php';


function getArticle($id = null, $searchDATA = array())
{
    if (!empty($id)) {
        $sql = "SELECT a.id AS id, id_categorie, nom_article, libelle_categorie, quantite, prix_unitaire, date_fabrication, 
        date_expiration, images
        FROM article AS a, categorie_article AS c 
        WHERE a.id=? AND c.id=a.id_categorie AND a.etat='1'";

        $req = $GLOBALS['connexion']->prepare($sql);

        $req->execute(array($id));

        return $req->fetch();
    } elseif (!empty($searchDATA)) {
        $search = "";
        extract($searchDATA);
        if (!empty($nom_article)) $search .= " AND a.nom_article LIKE '%$nom_article%' ";
        if (!empty($id_categorie)) $search .= " AND a.id_categorie = $id_categorie ";
        if (!empty($quantite)) $search .= " AND a.quantite = $quantite ";
        if (!empty($prix_unitaire)) $search .= " AND a.prix_unitaire = $prix_unitaire ";
        if (!empty($date_fabrication)) $search .= " AND DATE(a.date_fabrication) = '$date_fabrication' ";
        if (!empty($date_expiration)) $search .= " AND DATE(a.date_expiration) = '$date_expiration' ";

        $sql = "SELECT a.id AS id, id_categorie, nom_article, libelle_categorie, quantite, prix_unitaire, date_fabrication, 
        date_expiration, images
        FROM article AS a, categorie_article AS c 
        WHERE c.id=a.id_categorie AND a.etat='1' $search";

        $req = $GLOBALS['connexion']->prepare($sql);

        $req->execute();

        return $req->fetchAll();
    } else {
        $sql = "SELECT a.id AS id, id_categorie, nom_article, libelle_categorie, quantite, prix_unitaire, date_fabrication, 
        date_expiration, images
        FROM article AS a, categorie_article AS c 
        WHERE c.id=a.id_categorie AND a.etat='1'";

        $req = $GLOBALS['connexion']->prepare($sql);

        $req->execute();
        return $req->fetchAll();
    }
}



function getClient($id = null)
{
    if (!empty($id)) {
        $sql = "SELECT * FROM client WHERE id=? AND etat='1'";

        $req = $GLOBALS['connexion']->prepare($sql);
        $req->execute(array($id));

        return $req->fetch();
    } else {
        $sql = "SELECT * FROM client WHERE etat='1'";

        $req = $GLOBALS['connexion']->prepare($sql);
        $req->execute();

        return $req->fetchAll();
    }
}



function getVente($id = null)
{
    if (!empty($id)) {
        $sql = "SELECT v.id, v.id_article, v.id_client, v.quantite, v.prix, v.date_vente, 
                       a.nom_article, a.prix_unitaire, 
                       c.nom, c.prenom, c.telephone, c.adresse
                FROM vente v
                JOIN article a ON v.id_article = a.id
                JOIN client c ON v.id_client = c.id
                WHERE v.id = ? AND v.etat = '1'";

        $req = $GLOBALS['connexion']->prepare($sql);
        $req->execute(array($id));

        return $req->fetch();
    } else {
        $sql = "SELECT nom_article, nom, prenom, v.quantite, prix, date_vente, v.id, a.id AS idArticle
                FROM client AS c, vente AS v, article AS a 
                WHERE v.id_article=a.id AND v.id_client=c.id AND v.etat='1'";

        $req = $GLOBALS['connexion']->prepare($sql);
        $req->execute();

        return $req->fetchAll();
    }
}


function getCommande($id = null)
{
    if (!empty($id)) {
        $sql = "SELECT co.id, co.id_article AS idArticle, co.id_fournisseur, co.quantite, co.prix, co.date_commande, 
                       a.nom_article, a.prix_unitaire, 
                       f.nom, f.prenom, f.telephone, f.adresse
                FROM commande co
                JOIN article a ON co.id_article = a.id
                JOIN fournisseur f ON co.id_fournisseur = f.id
                WHERE co.id = ? AND co.etat = '1'";

        $req = $GLOBALS['connexion']->prepare($sql);
        $req->execute(array($id));

        return $req->fetch();
    } else {
        $sql = "SELECT co.id, co.id_article AS idArticle, co.id_fournisseur, co.quantite, co.prix, co.date_commande, 
                       a.nom_article, a.prix_unitaire, 
                       f.nom, f.prenom
                FROM commande co
                JOIN article a ON co.id_article = a.id
                JOIN fournisseur f ON co.id_fournisseur = f.id
                WHERE co.etat = '1'";

        $req = $GLOBALS['connexion']->prepare($sql);
        $req->execute();

        return $req->fetchAll();
    }
}





function getFournisseur($id = null)
{
    if (!empty($id)) {
        $sql = "SELECT * FROM fournisseur WHERE id=? AND etat='1'";

        $req = $GLOBALS['connexion']->prepare($sql);

        $req->execute(array($id));

        return $req->fetch();
    } else {
        $sql = "SELECT * FROM fournisseur WHERE etat='1'";

        $req = $GLOBALS['connexion']->prepare($sql);

        $req->execute();
        return $req->fetchAll();
    }
}


function getAllCommande()
{
    $sql = "SELECT COUNT(*) AS nbre FROM commande WHERE etat='1'";

    $req = $GLOBALS['connexion']->prepare($sql);

    $req->execute();

    return $req->fetch();
}

function getAllVente()
{
    $sql = "SELECT COUNT(*) AS nbre FROM vente WHERE etat=?";

    $req = $GLOBALS['connexion']->prepare($sql);

    $req->execute(array(1));

    return $req->fetch();
}

function getAllArticle()
{
    $sql = "SELECT COUNT(*) AS nbre FROM article";

    $req = $GLOBALS['connexion']->prepare($sql);

    $req->execute();

    return $req->fetch();
}

function getCA()
{
    $sql = "SELECT SUM(prix) AS prix FROM vente WHERE etat='1'";

    $req = $GLOBALS['connexion']->prepare($sql);

    $req->execute();

    return $req->fetch();
}

function getLastVente()
{
    $sql = "SELECT nom_article, nom, prenom, v.quantite, v.prix, v.date_vente, v.id, a.id AS idArticle
        FROM client AS c, vente AS v, article AS a 
        WHERE v.id_article=a.id AND v.id_client=c.id AND v.etat=? 
        ORDER BY v.date_vente DESC LIMIT 10";

    $req = $GLOBALS['connexion']->prepare($sql);

    $req->execute(array(1));

    return $req->fetchAll();
}

function getMostVente()
{
    $sql = "SELECT nom_article, SUM(v.prix) AS prix
        FROM client AS c, vente AS v, article AS a 
        WHERE v.id_article=a.id AND v.id_client=c.id AND v.etat=? 
        GROUP BY a.id
        ORDER BY SUM(v.prix) DESC LIMIT 10";

    $req = $GLOBALS['connexion']->prepare($sql);

    $req->execute(array(1));

    return $req->fetchAll();
}



function getCategorie($id = null)
{
    if (!empty($id)) {
        $sql = "SELECT * FROM categorie_article WHERE id=? AND etat='1'";

        $req = $GLOBALS['connexion']->prepare($sql);

        $req->execute(array($id));

        return $req->fetch();
    } else {
        $sql = "SELECT * FROM categorie_article WHERE etat='1'";

        $req = $GLOBALS['connexion']->prepare($sql);

        $req->execute();

        return $req->fetchAll();
    }
}


class DataFacade
{
    public function getArticle($id = null, $searchDATA = array())
    {
        return getArticle($id, $searchDATA);
    }

    public function getClient($id = null)
    {
        return getClient($id);
    }

    public function getVente($id = null)
    {
        return getVente($id);
    }

    public function getCommande($id = null)
    {
        return getCommande($id);
    }

    public function getFournisseur($id = null)
    {
        return getFournisseur($id);
    }

    public function getAllCommande()
    {
        return getAllCommande();
    }

    public function getAllVente()
    {
        return getAllVente();
    }

    public function getAllArticle()
    {
        return getAllArticle();
    }

    public function getCA()
    {
        return getCA();
    }

    public function getLastVente()
    {
        return getLastVente();
    }

    public function getMostVente()
    {
        return getMostVente();
    }

    public function getCategorie($id = null)
    {
        return getCategorie($id);
    }
}

