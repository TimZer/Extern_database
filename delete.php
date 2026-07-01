<?php

// Laadt de Database-klasse
require_once 'includes/Database.php';

// Laadt de Game-klasse
require_once 'includes/Game.php';

// Laadt de Team-klasse
require_once 'includes/Team.php';

// Maakt een nieuw Database-object aan
$db = new Database();

// Maakt verbinding met de database
$db->connect('nba');

// Maakt een nieuw Game-object aan
$game = new Game($db);

// Maakt een nieuw Team-object aan
$team = new Team($db);

// Controleert of er een id is meegegeven
if (isset($_GET['id'])) {

    // Slaat het id op in een variabele
    $id = $_GET['id'];

    // Verwijdert eerst de teamgegevens
    $team->delete($id);

    // Verwijdert daarna de wedstrijd
    $game->delete($id);
}

// Stuurt de gebruiker terug naar de homepage
header("Location: index.php");

// Stopt het script
exit;