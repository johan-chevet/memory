<?php
require_once "db-conn.php";
require_once "Card.php";
require_once "Memory.php";

session_name("memory");
session_start();
// TODO delete
// session_destroy();

if (isset($_SESSION["game"])) {
    $game = $_SESSION["game"];
} else {
    echo "Create new game";
    $stmt = $db->query("SELECT * FROM cards");
    $cardss = $stmt->fetchAll(PDO::FETCH_CLASS, "Card");
    $game = new Memory($cardss);
    $_SESSION["game"] = $game;
}
// $game->cards = [];
// var_dump($_SESSION);
if (!$game->gameStarted) {
    $game->startGame(6);
    // TODO check change bool / with readonly
}
// var_dump($game);

if (isset($_POST["test"])) {
    echo "submit input";
    // TODO check errors, put in game class?
    $index = $_POST["test"];
    if (!$game->firstCardSelected) {
        $game->firstCardSelected = $game->cards[$index];
        $game->firstCardSelected->revealed = true;
    } else if (!$game->secondCardSelected) {
        // TODO Check found pair
        $game->secondCardSelected = $game->cards[$index];
        $game->secondCardSelected->revealed = true;
        if ($game->firstCardSelected->id === $game->secondCardSelected->id) {
            echo "pair found";
            $game->cards = array_filter($game->cards, function ($card) use ($game) {
                return $card->id !== $game->firstCardSelected->id;
            });
            $game->firstCardSelected = null;
            $game->secondCardSelected = null;
        }
    } else {
        $game->firstCardSelected->revealed = false;
        $game->secondCardSelected->revealed = false;
        $game->firstCardSelected = null;
        $game->secondCardSelected = null;
        $game->firstCardSelected = $game->cards[$index];
        $game->firstCardSelected->revealed = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Memory</title>
</head>

<body>
    <form action="./index.php" method="post">
        <div class="grid-game">
            <?php foreach ($game->cards as $key => $card): ?>
                <div class="tile">
                    <input type="submit" name="test" value="<?= $key ?>">
                    <?php if ($card->revealed): ?>
                        <img src="<?= $card->img_path ?>" alt="image">
                    <?php else: ?>
                        <img src="./assets/card-back-purple.png" alt="image">
                    <?php endif; ?>
                </div>

            <?php endforeach; ?>
        </div>
    </form>
</body>

</html>