<?php

// Klasse voor alle bewerkingen op de tabel teams
class Team
{
    // Variabele voor de databaseverbinding
    private $db;

    // Constructor ontvangt de databaseverbinding
    public function __construct($db)
    {
        // Slaat de databaseverbinding op
        $this->db = $db;
    }


    // CREATE
    // Voegt een nieuw team toe

    public function create($homeTeam, $visitorTeam, $homePts, $visitorPts)
    {
        // Bereidt de INSERT-query voor
        $stmt = $this->db->conn->prepare("
            INSERT INTO teams
            (home_team, visitor_team, home_pts, visitor_pts)
            VALUES (?, ?, ?, ?)
        ");

        // Voert de query uit
        $stmt->execute([
            $homeTeam,
            $visitorTeam,
            $homePts,
            $visitorPts
        ]);
    }


    // READ
    // Haalt alle teams op

    public function getAll()
    {
        // Selecteert alle teams
        $stmt = $this->db->conn->query("
            SELECT *
            FROM teams
        ");

        // Geeft alle resultaten terug
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // READ
    // Haalt één team op

    public function getById($id)
    {
        // Bereidt de query voor
        $stmt = $this->db->conn->prepare("
            SELECT *
            FROM teams
            WHERE id = ?
        ");

        // Voert de query uit
        $stmt->execute([$id]);

        // Geeft één resultaat terug
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // UPDATE
    // Bewerkt een team

    public function update($id, $homeTeam, $visitorTeam, $homePts, $visitorPts)
    {
        // Bereidt de update-query voor
        $stmt = $this->db->conn->prepare("
            UPDATE teams
            SET
                home_team = ?,
                visitor_team = ?,
                home_pts = ?,
                visitor_pts = ?
            WHERE id = ?
        ");

        // Voert de update uit
        $stmt->execute([
            $homeTeam,
            $visitorTeam,
            $homePts,
            $visitorPts,
            $id
        ]);
    }


    // DELETE
    // Verwijdert een team

    public function delete($id)
    {
        // Bereidt de delete-query voor
        $stmt = $this->db->conn->prepare("
            DELETE FROM teams
            WHERE id = ?
        ");

        // Voert de query uit
        $stmt->execute([$id]);
    }
}