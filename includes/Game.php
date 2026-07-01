<?php

// Klasse voor alle bewerkingen op de tabel games
class Game
{
    // Variabele voor de databaseverbinding
    private $db;

    // Constructor ontvangt de databaseverbinding
    public function __construct($db)
    {
        // Slaat de databaseverbinding op zodat alle functies deze kunnen gebruiken
        $this->db = $db;
    }

    // CREATE
    // Voegt een nieuwe wedstrijd toe

    public function create($game_id, $game_date, $arena, $game_duration)
    {
        // Bereidt de INSERT-query voor
        $stmt = $this->db->conn->prepare("
            INSERT INTO games
            (
                game_id,
                game_date,
                arena,
                game_duration
            )
            VALUES
            (
                ?, ?, ?, ?
            )
        ");

        // Voert de query uit
        $stmt->execute([
            $game_id,
            $game_date,
            $arena,
            $game_duration
        ]);
    }

    // READ
    // Haalt alle wedstrijden op
    public function getAll()
    {
        // Selecteert alle wedstrijden
        $stmt = $this->db->conn->query("
            SELECT *
            FROM games
            ORDER BY game_date DESC
        ");

        // Geeft alle resultaten terug
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // READ
    // Haalt één wedstrijd op

    public function getById($id)
    {
        // Bereidt de SELECT-query voor
        $stmt = $this->db->conn->prepare("
            SELECT *
            FROM games
            WHERE id = ?
        ");

        // Voert de query uit
        $stmt->execute([$id]);

        // Geeft één resultaat terug
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =====================================
    // UPDATE
    // Bewerkt een wedstrijd
    // =====================================
    public function update($id, $arena, $game_date, $game_duration)
    {
        // Bereidt de UPDATE-query voor
        $stmt = $this->db->conn->prepare("
            UPDATE games
            SET
                arena = ?,
                game_date = ?,
                game_duration = ?
            WHERE id = ?
        ");

        // Voert de update uit
        $stmt->execute([
            $arena,
            $game_date,
            $game_duration,
            $id
        ]);
    }


    // DELETE
    // Verwijdert een wedstrijd
    public function delete($id)
    {
        // Bereidt de DELETE-query voor
        $stmt = $this->db->conn->prepare("
            DELETE FROM games
            WHERE id = ?
        ");

        // Voert de query uit
        $stmt->execute([$id]);
    }

    // JOIN
    // Haalt alle wedstrijden inclusief teamgegevens op

    public function getGamesWithTeams()
    {
        // Voert een JOIN uit tussen de tabellen games en teams
        $stmt = $this->db->conn->query("
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

        // Geeft alle resultaten terug
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // JOIN
    // Haalt één wedstrijd inclusief teamgegevens op

    public function getGamesWithTeamsById($id)
    {
        // Bereidt de JOIN-query voor
        $stmt = $this->db->conn->prepare("
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
            WHERE g.id = ?
        ");

        // Voert de query uit
        $stmt->execute([$id]);

        // Geeft één resultaat terug
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}