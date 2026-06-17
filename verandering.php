<?php

require_once 'includes/Database.php';



$db = new Database();
$db->connect('nba');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // UPDATE games (only arena)
    $stmt = $db->conn->prepare("
        UPDATE games
        SET arena = ?
        WHERE id = ?
    ");

    $stmt->execute([
            $_POST['arena'],
            $_POST['id']
    ]);

    // UPDATE teams (home + visitor)
    $stmt2 = $db->conn->prepare("
        UPDATE teams
        SET home_team = ?,
            visitor_team = ?
        WHERE id = ?
    ");

    $stmt2->execute([
            $_POST['home_team'],
            $_POST['visitor_team'],
            $_POST['id']
    ]);

    header("Location: index.php");
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $db->conn->prepare("
        SELECT 
            g.id,
            g.arena,
            t.home_team,
            t.visitor_team
        FROM games g
        JOIN teams t ON g.id = t.id
        WHERE g.id = ?
    ");

    $stmt->execute([$id]);
}
else{
    die("Geen geldige id.");
}

    $game = $stmt->fetch(PDO::FETCH_ASSOC);



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

    <label>Visitor Team</label><br>
    <input type="text" name="visitor_team" value="<?php echo $game['visitor_team']; ?>"><br>

    <label>Arena</label><br>
    <input type="text" name="arena" value="<?php echo $game['arena']; ?>"><br>

    <button type="submit">opslaan</button>

</form>

</body>
</html>

