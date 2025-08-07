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
    $cards = $stmt->fetchAll(PDO::FETCH_CLASS, "Card");
    $game = new Memory($cards);
    $_SESSION["game"] = $game;
}

if (isset($_POST["menu"])) {
    var_dump($_POST["menu"]);
    if ($_POST["menu"] === "start" && isset($_POST["nb-pairs"]) && !$game->gameStarted) {
        $game->startGame((int)$_POST["nb-pairs"]);
    } else if ($_POST["menu"] === "quit" && $game->gameStarted) {
        $game->stopGame();
    }
}

if (isset($_POST["reveal"])) {
    echo "submit input";
    // TODO check errors, put in game class?
    $index = (int)$_POST["reveal"];
    var_dump($index);
    var_dump($game->firstSelectedCardIndex);

    // TODO remove or
    if ($game->firstSelectedCardIndex === $index || $game->firstSelectedCardIndex < 0) {
        $game->firstSelectedCardIndex = $index;
        // $game->firstCardSelected->revealed = true;
    } else if ($game->secondSelectedCardIndex < 0) {
        $game->secondSelectedCardIndex = $index;
        // $game->secondCardSelected->revealed = true;

        if ($game->cards[$game->firstSelectedCardIndex]->id === $game->cards[$game->secondSelectedCardIndex]->id) {
            $game->cards = array_filter($game->cards, function ($card) use ($game) {
                return $card->id !== $game->cards[$game->firstSelectedCardIndex]->id;
            });
            $game->firstSelectedCardIndex = -1;
            $game->secondSelectedCardIndex = -1;
        }
    } else {
        $game->firstSelectedCardIndex = $index;
        // $game->firstSelectedCardIndex = -1;
        $game->secondSelectedCardIndex = -1;
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
    <?php if (!$game->gameStarted): ?>
        <form action="./index.php" method="post">
            <label for="nb-pairs">Number of pairs</label>
            <select name="nb-pairs" id="nb-pairs">
                <option value="3" selected>3</option>
                <option value="6">6</option>
                <option value="8">8</option>
                <option value="10">10</option>
                <option value="12">12</option>
            </select>
            <button type="submit" name="menu" value="start">Start game</button>
        </form>
    <?php else: ?>
        <form action="./index.php" method="post">
            <button type="submit" name="menu" value="quit">Quit game</button>
            <div class="grid-game grid-col-<?= $game->nbOfPairs ?>">
                <?php foreach ($game->cards as $key => $card): ?>
                    <button class="tile" name="reveal" value="<?= $key ?>" type="submit" style="<?= "background-image: url(" . $game->getImageFromIndex($key) . ");" ?>"></button>
                <?php endforeach; ?>
            </div>
        </form>
    <?php endif; ?>
</body>

</html>