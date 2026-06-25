<?php

// Definitie van de klasse Game
class Game
{
    // Private variabele voor de databaseverbinding
    private $db;

    // Constructor: ontvangt de databaseverbinding
    public function __construct($db)
    {
        // Slaat de databaseverbinding op in de klasse
        $this->db = $db;
    }

    // Haalt alle wedstrijden op uit de database
    public function getAllGames()
    {
        // Voert een SQL-query uit om alle wedstrijden en teamgegevens op te halen
        $stmt = $this->db->conn->query("
            SELECT
                g.id,                 -- Uniek ID van de wedstrijd
                g.game_id,            -- Extern wedstrijd-ID
                g.game_date,          -- Datum van de wedstrijd
                g.arena,              -- Speellocatie
                g.game_duration,      -- Duur van de wedstrijd
                t.home_team,          -- Thuisteam
                t.visitor_team,       -- Uitteam
                t.home_pts,           -- Punten thuisteam
                t.visitor_pts         -- Punten uitteam
            FROM games g
            JOIN teams t ON g.id = t.id   -- Koppelt games en teams via hetzelfde ID
            ORDER BY g.game_date DESC     -- Sorteert op nieuwste datum eerst
        ");

        // Geeft alle resultaten terug als een associatieve array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Haalt één wedstrijd op aan de hand van het ID
    public function getGameById($id)
    {
        // Bereidt een SQL-query voor
        $stmt = $this->db->conn->prepare("
            SELECT
                g.id,                 -- Wedstrijd-ID
                g.arena,              -- Arena
                g.game_duration,      -- Wedstrijdduur
                g.game_date,          -- Wedstrijddatum
                t.home_team,          -- Thuisteam
                t.home_pts,           -- Punten thuisteam
                t.visitor_team,       -- Uitteam
                t.visitor_pts         -- Punten uitteam
            FROM games g
            JOIN teams t ON g.id = t.id   -- Koppelt games en teams
            WHERE g.id = ?                -- Zoekt op opgegeven ID
        ");

        // Voert de query uit met het opgegeven ID
        $stmt->execute([$id]);

        // Geeft één resultaat terug als associatieve array
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Werkt een bestaande wedstrijd bij
    public function updateGame($id, $arena, $homeTeam, $visitorTeam, $home_pts, $visitor_pts, $game_date, $game_duration)
    {
        // Bereidt een update-query voor de tabel games voor
        $stmt = $this->db->conn->prepare("
            UPDATE games
            SET arena = ?,            -- Nieuwe arena
                game_date = ?,        -- Nieuwe datum
                game_duration = ?     -- Nieuwe duur
            WHERE id = ?              -- Wedstrijd met dit ID
        ");

        // Voert de update uit
        $stmt->execute([$arena, $game_date, $game_duration, $id]);

        // Bereidt een update-query voor de tabel teams voor
        $stmt2 = $this->db->conn->prepare("
            UPDATE teams
            SET home_team = ?,        -- Nieuw thuisteam
                visitor_team = ?,     -- Nieuw uitteam
                home_pts = ?,         -- Nieuwe punten thuisteam
                visitor_pts = ?       -- Nieuwe punten uitteam
            WHERE id = ?              -- Teamgegevens met hetzelfde ID
        ");

        // Voert de update uit
        $stmt2->execute([
            $homeTeam,
            $visitorTeam,
            $home_pts,
            $visitor_pts,
            $id
        ]);
    }

    // Verwijdert een wedstrijd uit de database
    public function deleteGame($id)
    {
        // Bereidt de DELETE-query voor
        $stmt = $this->db->conn->prepare("
            DELETE FROM games
            WHERE id = ?              -- Verwijdert de wedstrijd met dit ID
        ");

        // Voert de verwijdering uit
        $stmt->execute([$id]);
    }
}