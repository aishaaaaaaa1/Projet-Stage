<?php
// Fonction pour obtenir le chemin de base
function getBasePath() {
    // Si on est dans un sous-dossier adminView
    if (strpos($_SERVER['PHP_SELF'], '/adminView/') !== false) {
        return '../';
    }
    // Sinon on est à la racine
    return './';
}

// Fonction pour obtenir le chemin vers adminView
function getAdminViewPath() {
    return getBasePath() . 'adminView/';
}

// Fonction pour obtenir le chemin vers controller
function getControllerPath() {
    return getBasePath() . 'controller/';
}
?> 