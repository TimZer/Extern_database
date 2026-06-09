<?php

// DB connectie (XAMPP standaard)
$pdo = new PDO(
    "mysql:host=localhost;dbname=nba;charset=utf8mb4",
    "root",
    ""
);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// API ophalen
$json = file_get_contents("https://api.server.nbaapi.com/api/games");
$games = json_decode($json, true);

// Opslaan
foreach ($games['data'] as $game) {

    $stmt = $pdo->prepare("
        INSERT INTO games (
            game_id,
            game_date,
            visitor_team,
            visitor_pts,
            home_team,
            home_pts,
            arena,
            game_duration
        )
        VALUES (
            :game_id,
            :game_date,
            :visitor_team,
            :visitor_pts,
            :home_team,
            :home_pts,
            :arena,
            :game_duration
        )
        ON DUPLICATE KEY UPDATE
            visitor_pts = VALUES(visitor_pts),
            home_pts = VALUES(home_pts)
    ");

    $stmt->execute([
        'game_id' => $game['gameId'],
        'game_date' => $game['date'],
        'visitor_team' => $game['visitorTeam'],
        'visitor_pts' => $game['visitorPts'],
        'home_team' => $game['homeTeam'],
        'home_pts' => $game['homePts'],
        'arena' => $game['arena'],
        'game_duration' => $game['gameDuration']
    ]);
}

echo "✅ Games opgeslagen in database!";