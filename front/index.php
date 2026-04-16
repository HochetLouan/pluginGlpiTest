<?php

include('../../../inc/includes.php');

// Vérifie que l'utilisateur est connecté
Session::checkLoginUser();

// Affiche l'entête GLPI
Html::header('Mon Plugin', $_SERVER['PHP_SELF'], 'plugins', 'monplugin');

// Contenu de la page
echo "<div class='center'>";
echo "<h1>Bienvenue dans mon plugin 🚀</h1>";
echo "<p>Ceci est une page simple.</p>";
echo "</div>";

// Pied de page GLPI
Html::footer();