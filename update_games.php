<?php

// Maak verbinding met de database
$pdo = new PDO(
        "mysql:host=localhost;dbname=nba;charset=utf8mb4",
        "root",
        ""
);

// Zorg ervoor dat databasefouten als exceptions worden weergegeven
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Haal de NBA-games op uit de API
$json = file_get_contents("https://api.server.nbaapi.com/api/games");

// Zet de JSON-data om naar een PHP-array
$games = json_decode($json, true);

// Controleer of het formulier is verzonden
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['games'])) {

    // Loop door alle geselecteerde wedstrijden
    foreach ($_POST['games'] as $index) {

        // Haal de geselecteerde wedstrijd op uit de array
        $game = $games['data'][$index];

        // Query om een game op te slaan in de tabel games
        $stmtGame = $pdo->prepare("
            INSERT INTO games (
                game_id,
                game_date,
                arena,
                game_duration
            )
            VALUES (
                :game_id,
                :game_date,
                :arena,
                :game_duration
            )
            ON DUPLICATE KEY UPDATE
                game_date = VALUES(game_date),
                arena = VALUES(arena),
                game_duration = VALUES(game_duration)
        ");

        // Voer de query uit met de gegevens van de game
        $stmtGame->execute([
                'game_id' => $game['gameId'],
                'game_date' => $game['date'],
                'arena' => $game['arena'],
                'game_duration' => $game['gameDuration']
        ]);

        // Query om de teamgegevens op te slaan
        $stmtTeam = $pdo->prepare("
            INSERT INTO teams (
                home_team,
                visitor_team,
                home_pts,
                visitor_pts
            )
            VALUES (
                :home_team,
                :visitor_team,
                :home_pts,
                :visitor_pts
            )
        ");

        // Voer de query uit met de teamgegevens
        $stmtTeam->execute([
                'home_team' => $game['homeTeam'],
                'visitor_team' => $game['visitorTeam'],
                'home_pts' => $game['homePts'],
                'visitor_pts' => $game['visitorPts']
        ]);
    }

    // Toon een melding wanneer alles succesvol is opgeslagen
    echo "<p style='color:green'>Games opgeslagen!</p>";
}

?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>NBA Games Importeren</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-black border-bottom border-warning">
        <div class="container">
            <a class="navbar-brand fw-bold text-warning" href="index.php">NBA Games</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="update_games.php">Game toevoegen</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <h1>NBA Games</h1>

    <form method="post">

        <button type="submit" class="btn btn-success mb-4">
            Geselecteerde wedstrijden opslaan
        </button>

        <!-- Cards hier -->

        <div class="row">

            <!-- Loop door alle games uit de API -->
            <?php foreach ($games['data'] as $index => $game): ?>

                <!-- Maak een kaart voor elke wedstrijd -->
                <div class="col-md-6 col-lg-4 mb-4">

                    <div class="card h-100">

                        <div class="card-body">

                            <!-- Checkbox om een wedstrijd te selecteren -->
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

                            <!-- Toon de teams -->
                            <h5 class="card-title">
                                <?php echo $game['visitorTeam'] ?>
                                vs
                                <?php echo $game['homeTeam'] ?>
                            </h5>

                            <!-- Toon de wedstrijddatum -->
                            <p class="card-text">
                                <strong>Datum:</strong>
                                <?php echo $game['date'] ?>
                            </p>

                            <!-- Toon het uitteam -->
                            <p class="card-text">
                                <strong>Uit Team:</strong>
                                <?php echo $game['visitorTeam'] ?>
                            </p>

                            <!-- Toon de score van het uitteam -->
                            <p class="card-text">
                                <strong>Uit Score:</strong>
                                <?php echo $game['visitorPts'] ?>
                            </p>

                            <!-- Toon het thuisteam -->
                            <p class="card-text">
                                <strong>Thuis Team:</strong>
                                <?php echo $game['homeTeam'] ?>
                            </p>

                            <!-- Toon de score van het thuisteam -->
                            <p class="card-text">
                                <strong>Thuis Score:</strong>
                                <?php echo $game['homePts'] ?>
                            </p>

                            <!-- Toon de arena -->
                            <p class="card-text">
                                <strong>Arena:</strong>
                                <?php echo $game['arena'] ?>
                            </p>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </form>

    </body>
    </html>