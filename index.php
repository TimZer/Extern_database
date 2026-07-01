<?php

// Laad de Database-klasse
require_once 'includes/Database.php';

// Laad de Game-klasse
require_once 'includes/Game.php';

// Laad de Team-klasse
require_once 'includes/Team.php';

// Maak een nieuw Database-object aan
$pdo = new Database();

// Maak verbinding met de database 'nba'
$pdo->connect('nba');

// Maak een nieuw Game-object aan
$gameObj = new Game($pdo);

// Maak een nieuw Team-object aan
// (Deze class heeft zijn eigen CRUD-functies)
$teamObj = new Team($pdo);

// Haal alle wedstrijden inclusief teamgegevens op met een JOIN
$games = $gameObj->getGamesWithTeams();

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>NBA Games</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark">

<!-- Navigatiebalk -->
<nav class="navbar navbar-expand-lg navbar-dark bg-black border-bottom border-warning">

    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand fw-bold text-warning" href="index.php">
            NBA Games
        </a>

        <!-- Hamburger-menu -->
        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>

        </button>

        <!-- Navigatie -->
        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link active" href="index.php">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="update_games.php">
                        Game toevoegen
                    </a>
                </li>

            </ul>

        </div>

    </div>

</nav>

<div class="container mt-4">

    <!-- Titel -->
    <h1 class="text-white mb-4">
        Opgeslagen NBA Games
    </h1>

    <!-- Controleert of er wedstrijden gevonden zijn -->
    <?php if (empty($games)): ?>

        <div class="alert alert-warning">

            Er zijn nog geen wedstrijden opgeslagen.

        </div>

    <?php else: ?>

        <div class="row">

            <!-- Loopt door alle wedstrijden -->
            <?php foreach ($games as $game): ?>

                <div class="col-md-6 col-lg-4 mb-4">

                    <div class="card h-100 bg-secondary text-white shadow">

                        <div class="card-body">

                            <!-- Thuisteam en uitteam -->
                            <h5 class="card-title text-warning fw-bold">

                                <?php echo $game['visitor_team']; ?>

                                vs

                                <?php echo $game['home_team']; ?>

                            </h5>

                            <!-- Wedstrijddatum -->
                            <p class="mb-1">

                                <strong>Datum:</strong>

                                <?php echo $game['game_date']; ?>

                            </p>

                            <!-- Wedstrijdduur -->
                            <p class="mb-1">

                                <strong>Duur:</strong>

                                <?php echo $game['game_duration']; ?>

                            </p>

                            <!-- Arena -->
                            <p class="mb-1">

                                <strong>Arena:</strong>

                                <?php echo $game['arena']; ?>

                            </p>

                            <!-- Score -->
                            <p class="mb-3">

                                <strong>Score:</strong>

                                <?php echo $game['visitor_pts']; ?>

                                -

                                <?php echo $game['home_pts']; ?>

                            </p>

                            <!-- Knop om de wedstrijd te bewerken -->
                            <a href="verandering.php?id=<?php echo $game['id']; ?>"
                               class="btn btn-warning">

                                Bewerk

                            </a>

                            <!-- Knop om de wedstrijd te verwijderen -->
                            <a href="delete.php?id=<?php echo $game['id']; ?>"
                               class="btn btn-danger"
                               onclick="return confirm('Weet je zeker dat je deze game wilt verwijderen?');">

                                Verwijderen

                            </a>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>