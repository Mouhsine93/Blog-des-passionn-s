<?php


session_start();
if (!isset($_SESSION['nom_utilisateur']) || !isset($_SESSION['jeton'])) {
    header('Location: login.php');
    exit;
}

require_once('../model/connexion.php');

try {
    $connexion = require('../model/connexion.php');

    // Vérifier le jeton de session
    $stmt = $connexion->prepare("SELECT jeton FROM utilisateur WHERE nom_utilisateur = :nom_utilisateur");
    $stmt->bindParam(':nom_utilisateur', $_SESSION['nom_utilisateur']);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && $result['jeton'] === $_SESSION['jeton']) {
        // Continuer l'affichage de la page
    } else {
        header('Location: deconnexion.php');
        exit;
    }
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
include_once '../model/function.php';

$titres = [
    'dashboard' => 'Tableau de bord',
    'sale' => 'Vente',
    'customer' => 'Client',
    'article' => 'Article',
    'supplier' => 'Fournisseur',
    'order' => 'Commande',
    'category' => 'Catégorie',
    'deconnexion' => 'Déconnexion',
    'search' => 'Résultat',
    'orderReceipt' => 'Reçu de commande',
    'saleReceipt' => 'Reçu de vente'
];

// Récupérer le nom du fichier sans l'extension
$nomFichier = basename($_SERVER['PHP_SELF'], ".php");

// Définir le titre en fonction du nom du fichier
$titrePage = isset($titres[$nomFichier]) ? $titres[$nomFichier] : ucfirst($nomFichier);

?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <title><?php echo $titrePage; ?></title>
    <link rel="stylesheet" href="../public/css/style.css" />
    <!-- Boxicons CDN Link -->
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
  <body>
    
  <div class="sidebar hidden-print">
  <div class="logo-details">
    <img src="../public/images/icon/logo2.ico" alt="Logo" id="logo">
    <span class="logo_name logo-text">Gestion de Stock</span>
  </div>




      <ul class="nav-links">
      <li>
                <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF'])=="dashboard.php" ? "active" : "" ?> ">
                    <i class="bx bx-grid-alt"></i>
                    <span class="links_name">Tableau de bord</span>
                </a>
            </li>
        <li>
          <a href="sale.php" class="<?php echo basename($_SERVER['PHP_SELF'])=="sale.php" ? "active" : "" ?> ">
                    <i class='bx bx-shopping-bag'></i>
                    <span class="links_name">Vente</span>
                </a>
            </li>
        <li>
          <a href="customer.php"  class="<?php echo basename($_SERVER['PHP_SELF'])=="client.php" ? "active" : "" ?> ">
                    <i class="bx bx-user"></i>
                    <span class="links_name">Client</span>
                </a>
            </li>
        <li>
                <a href="article.php"  class="<?php echo basename($_SERVER['PHP_SELF'])=="article.php" ? "active" : "" ?> ">
                    <i class="bx bx-box"></i>
                    <span class="links_name">Article</span>
                </a>
            </li>
        <li>
          <a href="supplier.php" class="<?php echo basename($_SERVER['PHP_SELF'])=="supplier.php" ? "active" : "" ?> ">
                    <i class="bx bx-user"></i>
                    <span class="links_name">Fournisseur</span>
                </a>
            </li>
        <li>
          <a href="order.php" class="<?php echo basename($_SERVER['PHP_SELF'])=="order.php" ? "active" : "" ?> ">
                    <i class="bx bx-list-ul"></i>
                    <span class="links_name">Commande</span>
                </a>
            </li>
        
        <li>
          <a href="category.php" class="<?php echo basename($_SERVER['PHP_SELF'])=="category.php" ? "active" : "" ?> ">
                    <i class="bx bx-category"></i>
                    <span class="links_name">Catégorie</span>
                </a>
            </li>

        <li class="log_out">
          <a href="deconnexion.php" class="<?php echo basename($_SERVER['PHP_SELF'])=="deconnexion.php" ? "active" : "" ?> ">
            <i class="bx bx-log-out"></i>
            <span class="links_name">Déconnexion</span>
            
          </a>
        </li>
      </ul>
    </div>
    <section class="home-section">
    <nav class="hidden-print">
        <div class="sidebar-button">
            <i class="bx bx-menu sidebarBtn"></i>
            <img src="../public/images/icon/logo2.ico" alt="Logo" id="logo">
            <span class="dashboard">
            
    <?php
$pageTitle = ''; // Initialisation de la variable du titre de la page

// Détermination du titre de la page en fonction de $_SERVER['PHP_SELF']
switch (basename($_SERVER['PHP_SELF'])) {
    case 'dashboard.php':
        $pageTitle = 'Tableau de bord';
        break;
    case 'sale.php':
        $pageTitle = 'Vente';
        break;
    case 'customer.php':
        $pageTitle = 'Client';
        break;
    case 'article.php':
        $pageTitle = 'Article';
        break;
    case 'supplier.php':
        $pageTitle = 'Fournisseur';
        break;
    case 'order.php':
        $pageTitle = 'Commande';
        break;
    case 'category.php':
        $pageTitle = 'Catégorie';
        break;
    case 'deconnexion.php':
        $pageTitle = 'Déconnexion';
        break;
        case 'search.php':
            $pageTitle = 'Résultat';
            break;
            case 'orderReceipt.php':
                $pageTitle = 'Résultat';
                break;
                case 'saleReceipt.php':
                    $pageTitle = 'Résultat';
                    break;
    default:
        $pageTitle = '';
        break;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="icon" href="../public/images/icon/logo2.ico" type="image/x-icon">
    <!-- Autres balises meta, liens CSS, etc. -->
</head>
<body>
    <!-- Le reste de votre contenu HTML -->
    <span class="dashboard">
        <?php echo $pageTitle; // Affichage du titre de la page ?>
    </span>
    <!-- Suite du contenu -->
</body>
</html>

        </div>
        <div class="search-box">
        <form action="search.php" method="GET">
            <input type="text" name="query" placeholder="Recherche..." />
            <button type="submit">Rechercher</button>
        </form>
    </div>
</div>

    </nav>
    <script>
let sidebar = document.querySelector(".sidebar");
let sidebarBtn = document.querySelector(".sidebarBtn");
sidebarBtn.onclick = function() {
    sidebar.classList.toggle("active");
    if(sidebar.classList.contains("active")){
        sidebarBtn.classList.replace("bx-menu" ,"bx-menu-alt-right");
    } else
        sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
}
</script>

