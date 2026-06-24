<?php

class Game
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllGames()
    {
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
            JOIN teams t ON g.id = t.id
            ORDER BY g.game_date DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGameById($id)
    {
        $stmt = $this->db->conn->prepare("
            SELECT
                g.id,
                g.arena,
                g.game_duration,
                g.game_date,
                t.home_team,
                t.home_pts,
                t.visitor_team,
                t.visitor_pts
            FROM games g
            JOIN teams t ON g.id = t.id
            WHERE g.id = ?
        ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateGame($id, $arena, $homeTeam, $visitorTeam, $home_pts, $visitor_pts,$game_date, $game_duration)
    {
        $stmt = $this->db->conn->prepare("
            UPDATE games
            SET arena = ?,
                game_id = ?,
                game_date = ?,
                game_duration = ?
            WHERE id = ?
        ");

        $stmt->execute([$arena, $game_date, $game_duration, $id]);

        $stmt2 = $this->db->conn->prepare("
            UPDATE teams
            SET home_team = ?,
                visitor_team = ?,
                home_pts = ?,
                visitor_pts = ?
            WHERE id = ?
        ");

        $stmt2->execute([
            $homeTeam,
            $visitorTeam,
            $home_pts,
            $visitor_pts,
            $id
        ]);
    }

    public function deleteGame($id)
    {
        $stmt = $this->db->conn->prepare("
            DELETE FROM games
            WHERE id = ?
        ");

        $stmt->execute([$id]);
    }
}