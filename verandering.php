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

// Controleer of het formulier is verzonden
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Werk de gegevens van de game bij
    $gameObj->updateGame(
            $_POST['id'],
            $_POST['arena'],
            $_POST['home_team'],
            $_POST['visitor_team']
    );

    // Stuur de gebruiker terug naar de homepage
    header("Location: index.php");
    exit;
}

// Controleer of er een game-id is meegegeven
if(isset($_GET['id'])) {

    // Sla het id op
    $id = $_GET['id'];

    // Haal de gegevens van de geselecteerde game op
    $game = $gameObj->getGameById($id);

} else {

    // Stop het script als er geen geldig id aanwezig is
    die("Geen geldige id.");
}
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Game Bewerken</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<form action="verandering.php?id=<?php echo $id;?>" method="POST">

    <!-- Verborgen veld met het game-id -->
    <input type="hidden" name="id" value="<?php echo ($game['id']); ?>">

    <!-- Invoerveld voor het thuisteam -->
    <label>Home Team</label><br>
    <input type="text" name="home_team" value="<?php echo $game['home_team']; ?>"><br>

    <!-- Invoerveld voor de punten van het thuisteam -->
    <label>Home Points</label><br>
    <input type="number" name="home_pts" value="<?php echo $game['home_pts']; ?>"><br>

    <!-- Invoerveld voor het uitteam -->
    <label>Visitor Team</label><br>
    <input type="text" name="visitor_team" value="<?php echo $game['visitor_team']; ?>"><br>

    <!-- Invoerveld voor de punten van het uitteam -->
    <label>Visitor Points</label><br>
    <input type="number" name="visitor_pts" value="<?php echo $game['visitor_pts']; ?>"><br>

    <!-- Invoerveld voor de wedstrijddatum -->
    <label>Game Date</label><br>
    <input type="date" name="game_date" value="<?php echo $game['game_date']; ?>"><br>

    <!-- Invoerveld voor de arena -->
    <label>Arena</label><br>
    <input type="text" name="arena" value="<?php echo $game['arena']; ?>"><br>

    <!-- Invoerveld voor de duur van de wedstrijd -->
    <label>Game Duration</label><br>
    <input type="number" name="game_duration" value="<?php echo $game['game_duration']; ?>"><br>

    <!-- Knop om de wijzigingen op te slaan -->
    <button type="submit">opslaan</button>

</form>

</body>
</html>

