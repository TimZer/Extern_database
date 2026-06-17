    <?php

    $pdo = new PDO(
        "mysql:host=localhost;dbname=nba;charset=utf8mb4",
        "root",
        ""
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // API ophalen
    $json = file_get_contents("https://api.server.nbaapi.com/api/games");
    $games = json_decode($json, true);

    // Opslaan als formulier verzonden is
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['games'])) {
        foreach ($_POST['games'] as $index) {

            $game = $games['data'][$index];

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

            $stmtGame->execute([
                    'game_id' => $game['gameId'],
                    'game_date' => $game['date'],
                    'arena' => $game['arena'],
                    'game_duration' => $game['gameDuration']
            ]);



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

            $stmtTeam->execute([
                    'home_team' => $game['homeTeam'],
                    'visitor_team' => $game['visitorTeam'],
                    'home_pts' => $game['homePts'],
                    'visitor_pts' => $game['visitorPts']
            ]);
        }

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
                        <a class="nav-link active" href="/index.php">Home</a>
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

            <?php foreach ($games['data'] as $index => $game): ?>

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
                                <?= $game['visitorTeam'] ?>
                                vs
                                <?= $game['homeTeam'] ?>
                            </h5>

                            <p class="card-text">
                                <strong>Datum:</strong>
                                <?= $game['date'] ?>
                            </p>

                            <p class="card-text">
                                <strong>Uit Team:</strong>
                                <?= $game['visitorTeam'] ?>
                            </p>

                            <p class="card-text">
                                <strong>Uit Score:</strong>
                                <?= $game['visitorPts'] ?>
                            </p>

                            <p class="card-text">
                                <strong>Thuis Team:</strong>
                                <?= $game['homeTeam'] ?>
                            </p>

                            <p class="card-text">
                                <strong>Thuis Score:</strong>
                                <?= $game['homePts'] ?>
                            </p>

                            <p class="card-text">
                                <strong>Arena:</strong>
                                <?= $game['arena'] ?>
                            </p>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </form>

    </body>
    </html>