<?php

require_once 'includes/Database.php';
require_once 'includes/Game.php';

$db = new Database();
$db->connect('nba');

$gameObj = new Game($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $gameObj->updateGame(
            $_POST['id'],
            $_POST['arena'],
            $_POST['home_team'],
            $_POST['visitor_team']
    );

    header("Location: index.php");
    exit;
}

if(isset($_GET['id'])) {

    $id = $_GET['id'];
    $game = $gameObj->getGameById($id);

} else {

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

    <input type="hidden" name="id" value="<?php echo ($game['id']); ?>">

    <label>Home Team</label><br>
    <input type="text" name="home_team" value="<?php echo $game['home_team']; ?>"><br>

    <label>Home Points</label><br>
    <input type="number" name="home_pts" value="<?php echo $game['home_pts']; ?>"><br>

    <label>Visitor Team</label><br>
    <input type="text" name="visitor_team" value="<?php echo $game['visitor_team']; ?>"><br>

    <label>Visitor Points</label><br>
    <input type="number" name="visitor_pts" value="<?php echo $game['visitor_pts']; ?>"><br>

    <label>Game Date</label><br>
    <input type="date" name="game_date" value="<?php echo $game['game_date']; ?>"><br>

    <label>Arena</label><br>
    <input type="text" name="arena" value="<?php echo $game['arena']; ?>"><br>

    <label>Game Duration</label><br>
    <input type="number" name="game_duration" value="<?php echo $game['game_duration']; ?>"><br>

    <button type="submit">opslaan</button>

</form>

</body>
</html>

