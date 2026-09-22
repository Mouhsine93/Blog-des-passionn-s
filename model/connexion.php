<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nom_serveur = getenv("DB_HOST") ?: "localhost";
$nom_base_de_donnees = getenv("DB_NAME") ?: "gestion_de_stock";
$utilisateur = getenv("DB_USER") ?: "root";
$mot_de_passe = getenv("DB_PASSWORD") ?: "root";

try {
    $connexion = new PDO("mysql:host=$nom_serveur;dbname=$nom_base_de_donnees", $utilisateur, $mot_de_passe);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $connexion;
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
