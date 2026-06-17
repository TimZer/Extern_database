<?php

require_once 'includes/Database.php';

$pdo = new Database();
$pdo->connect('nba');


$stmt = $pdo->conn->query("
    SELECT
        g.id,
        g.game_id,
        g.game_date,
        g.arena,
        g.game_duration,
        t.home_team,
        t.visitor_team,
        t.home_pts,
        t.visitor_pts
    FROM games g
    JOIN teams t
        ON g.id = t.id
    ORDER BY g.game_date DESC
");

$games = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>NBA Games</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark">

<!-- Navbar -->
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
                    <a class="nav-link" href="includes/update_games.php">Game toevoegen</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container ">

    <h1 class="text-white">
        Opgeslagen NBA Games
    </h1>

    <?php if (empty($games)): ?>

        <div class="alert alert-warning">
            Er zijn nog geen wedstrijden opgeslagen.
        </div>

    <?php else: ?>

        <div class="row">

            <?php foreach ($games as $game): ?>

                <div class="col-md-6 col-lg-4 mb-4">

                    <div class="card h-100 bg-secondary text-white border-0 shadow">

                        <div class="card-body">

                            <h5 class="card-title fw-bold text-warning">
                                <?php echo ($game['visitor_team']) ?>
                                vs
                                <?php echo ($game['home_team']) ?>
                            </h5>

                            <p class="mb-1">
                                <strong>Datum:</strong>
                                <?php echo ($game['game_date']) ?>
                            </p>

                            <p class="mb-1">
                                <strong>game duration:</strong>
                                <?php echo ($game['game_duration']) ?>
                            </p>

                            <p class="mb-1">
                                <strong>️Arena:</strong>
                                <?php echo ($game['arena']) ?>
                            </p>

                            <p class="mb-1">
                                <strong>Score:</strong>
                                <?php echo ($game['visitor_pts']) ?>
                                -
                                <?php echo ($game['home_pts']) ?>
                            </p>

                            <a href="verandering.php?id=<?php echo $game['id'] ?>" class="btn btn-warning">
                                Bewerk
                            </a>


                            <a href="delete.php?id=<?php echo $game['id']; ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Weet je zeker dat je deze game wilt verwijderen');">
                                Verwijderen
                            </a>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>