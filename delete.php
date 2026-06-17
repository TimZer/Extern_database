<?php

require_once 'includes/Database.php';

$db = new Database();
$db->connect('nba');

if (isset($_GET['id'])) {

    $id = (int)$_GET['id'];
    $stmt = $db->conn->prepare("
    DELETE FROM games WHERE id = ?
    ");

    $stmt->execute([$id]);
}

    header("Location: index.php");
    exit;