<?php

require_once 'includes/Database.php';
require_once 'includes/Game.php';

$db = new Database();
$db->connect('nba');

$gameObj = new Game($db);

if (isset($_GET['id'])) {
    $gameObj->deleteGame($_GET['id']);
}

header("Location: index.php");
exit;