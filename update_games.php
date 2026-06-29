<?php

// Maak verbinding met de database
$pdo = new PDO(
        "mysql:host=localhost;dbname=nba;charset=utf8mb4",
        "root",
        ""
);

// Zorg ervoor dat fouten als exceptions worden weergegeven
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Haal de wedstrijden op uit de gratis ESPN API
$url = "https://site.api.espn.com/apis/site/v2/sports/basketball/nba/scoreboard";

$json = file_get_contents($url);

// Zet de JSON om naar een PHP-array
$games = json_decode($json, true);

// Controleer of de API werkt
if (!$games || !isset($games['events'])) {
    die("Kan geen wedstrijden ophalen.");
}

// Controleer of het formulier is verzonden
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['games'])) {

    // Loop door alle geselecteerde wedstrijden
    foreach ($_POST['games'] as $index) {

        // Haal de gekozen wedstrijd op
        $game = $games['events'][$index];

        // Haal thuis- en uitteam op
        $home = $game['competitions'][0]['competitors'][0];
        $away = $game['competitions'][0]['competitors'][1];

        // Arena ophalen
        $arena = $game['competitions'][0]['venue']['fullName'] ?? "Onbekend";

        // Wedstrijd opslaan
        $stmtGame = $pdo->prepare("
            INSERT INTO games
            (
                game_id,
                game_date,
                arena,
                game_duration
            )
            VALUES
            (
                :game_id,
                :game_date,
                :arena,
                :game_duration
            )
            ON DUPLICATE KEY UPDATE
                game_date = VALUES(game_date),
                arena = VALUES(arena)
        ");

        $stmtGame->execute([
                'game_id' => $game['id'],
                'game_date' => $game['date'],
                'arena' => $arena,
                'game_duration' => null
        ]);

        // Teams opslaan
        $stmtTeam = $pdo->prepare("
            INSERT INTO teams
            (
                home_team,
                visitor_team,
                home_pts,
                visitor_pts
            )
            VALUES
            (
                :home_team,
                :visitor_team,
                :home_pts,
                :visitor_pts
            )
        ");

        $stmtTeam->execute([
                'home_team' => $home['team']['displayName'],
                'visitor_team' => $away['team']['displayName'],
                'home_pts' => $home['score'],
                'visitor_pts' => $away['score']
        ]);
    }

    echo "<div class='alert alert-success'>Games succesvol opgeslagen!</div>";
}

?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>NBA Games</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">NBA Games</a>
    </div>
</nav>

<div class="container">

    <h2 class="mb-4">NBA Wedstrijden</h2>

    <form method="post">

        <button class="btn btn-success mb-4">
            Geselecteerde wedstrijden opslaan
        </button>

        <div class="row">

            <?php foreach ($games['events'] as $index => $game): ?>

                <?php

// Haal thuis- en uitteam op
                $home = $game['competitions'][0]['competitors'][0];
                $away = $game['competitions'][0]['competitors'][1];

// Arena ophalen
                $arena = $game['competitions'][0]['venue']['fullName'] ?? "Onbekend";

                ?>

                <div class="col-md-6 col-lg-4 mb-4">

                    <div class="card h-100">

                        <div class="card-body">

                            <div class="form-check mb-3">

                                <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="games[]"
                                        value="<?= $index ?>">

                                <label class="form-check-label">
                                    Selecteer wedstrijd
                                </label>

                            </div>

                            <h5 class="card-title">

                                <?= $away['team']['displayName']; ?>

                                vs

                                <?= $home['team']['displayName']; ?>

                            </h5>

                            <p>

                                <strong>Datum:</strong><br>

                                <?= date("d-m-Y H:i", strtotime($game['date'])); ?>

                            </p>

                            <p>

                                <strong>Uitteam:</strong>

                                <?= $away['team']['displayName']; ?>

                            </p>

                            <p>

                                <strong>Uit score:</strong>

                                <?= $away['score']; ?>

                            </p>

                            <p>

                                <strong>Thuisteam:</strong>

                                <?= $home['team']['displayName']; ?>

                            </p>

                            <p>

                                <strong>Thuis score:</strong>

                                <?= $home['score']; ?>

                            </p>

                            <p>

                                <strong>Arena:</strong>

                                <?= $arena; ?>

                            </p>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </form>

</div>

</body>
</html>