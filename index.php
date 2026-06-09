<?php

require_once '../includes/ApiService.php';

$api = new ApiService();
$games = $api->getGames();

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>NBA Games</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark">

<div class="container py-5">
    <div class="row g-4">

        <?php foreach ($games as $game): ?>

            <div class="col-md-6 col-lg-4 col-xl-3">

                <div class="card h-100">

                    <img src="https://placehold.co/600x300?text=NBA+Game"
                         class="card-img-top"
                         alt="NBA Game">

                    <div class="card-body">

                        <h5 class="card-title">
                            <?= htmlspecialchars($game['visitorTeam']) ?>
                            vs
                            <?= htmlspecialchars($game['homeTeam']) ?>
                        </h5>

                        <p class="card-text">
                            Datum:
                            <?= htmlspecialchars($game['date']) ?>
                            <br>

                            Start:
                            <?= htmlspecialchars($game['startTimeET']) ?>
                            <br>

                            Arena:
                            <?= htmlspecialchars($game['arena']) ?>
                            <br>

                            Score:
                            <?= htmlspecialchars($game['visitorPts']) ?>
                            -
                            <?= htmlspecialchars($game['homePts']) ?>
                        </p>

                        <a href="#" class="btn btn-primary">
                            Bekijk wedstrijd
                        </a>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>
</div>

</body>
</html>