<?php

// Laad de Database-klasse in
require_once 'includes/Database.php';

// Laad de Game-klasse in
require_once 'includes/Game.php';

// Maak een nieuw Database-object aan
$db = new Database();

// Maak verbinding met de database 'nba'
$db->connect('nba');

// Maak een nieuw Game-object aan
$gameObj = new Game($db);

// Controleer of er een id is meegegeven via de URL
if (isset($_GET['id'])) {

    // Verwijder de game met het opgegeven id
    $gameObj->deleteGame($_GET['id']);
}

// Stuur de gebruiker terug naar de homepage
header("Location: index.php");

// Stop het script na de redirect
exit;