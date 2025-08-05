<?php
require_once "db-conn.php";
require_once "Card.php";
require_once "Memory.php";

session_name("memory");
session_start();

if (isset($_SESSION["game"])) {
    $game = $_SESSION["game"];
} else {
    $stmt = $db->query("SELECT * FROM cards");
    $cards = $stmt->fetchAll(PDO::FETCH_CLASS, "Card");
    $game = new Memory($cards);
    $_SESSION["game"] = $game;
}
// $game->cards = [];
var_dump($game);
var_dump($_SESSION);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory</title>
</head>

<body>

</body>

</html>