<?php
require_once "Card.php";
require_once "Memory.php";
require_once "User.php";

session_name("memory");
session_start();

$user = User::register("test-", "test-");
if ($user instanceof User) {
    var_dump("user" ,$user);
} else {
    var_dump("errors", $user);
}

// TODO delete
// session_destroy();
// TODO left side bar with score etc, right side bar with leaderboard?
if (isset($_SESSION["game"])) {
    $game = $_SESSION["game"];
} else {
    $game = new Memory();
    $_SESSION["game"] = $game;
}

if (isset($_POST["menu"])) {
    if ($_POST["menu"] === "start" && !$game->gameStarted) {
        $game->startGame();
    } else if ($_POST["menu"] === "quit" && $game->gameStarted) {
        $game->stopGame();
    }
}
if (isset($_POST["nb-pairs"]) && !$game->gameStarted) {
    $game->setRandomCardsFromDeck((int)$_POST["nb-pairs"]);
}

if (isset($_POST["reveal"])) {
    $index = (int)$_POST["reveal"];
    $game->setCardSelected($index);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Memory</title>
</head>

<body>
    <div class="navbar">
        <!--        todo login/register-->
    </div>
    <form action="./index.php" method="post">
        <div class="sidebar left">
            <h1>Memory</h1>
            <?php if (!$game->gameStarted): ?>
                <div class="menu">
                    <button type="submit" class="game-mode" name="nb-pairs" value="3">3 PAIRS</button>
                    <button type="submit" class="game-mode" name="nb-pairs" value="6">6 PAIRS</button>
                    <button type="submit" class="game-mode" name="nb-pairs" value="8">8 PAIRS</button>
                    <button type="submit" class="game-mode" name="nb-pairs" value="10">10 PAIRS</button>
                    <button type="submit" class="game-mode" name="nb-pairs" value="12">12 PAIRS</button>
                    <button type="submit" class="game-mode" name="menu" value="start">Start game</button>
                </div>
            <?php else : ?>
                <div class="in-game">
                    <p>Moves: <?= $game->moves ?></p>
                    <p>Misses: <?= $game->misses ?></p>
                    <p>Accuracy: <?= $game->getAccuracy() ?>%</p>
                    <p>Score: <?= $game->getScore() ?></p>
                    <button type="submit" class="game-mode" name="menu" value="quit">Quit game</button>

                </div>
            <?php endif; ?>
        </div>
    </form>
    <div class="sidebar right">
        <h2>Leaderboard</h2>
    </div>
    <form action="./index.php" method="post">
        <div class="grid-wrapper">
            <div class="grid-game grid-col-<?= $game->nbOfPairs ?>">
                <?php foreach ($game->cards as $key => $card): ?>
                    <?php if (!$card->pairFound): ?>
                        <button class="tile pair-not-found" name="reveal" value="<?= $key ?>" type="submit"
                                style="<?= "background-image: url(" . $game->getImageFromIndex($key) . ");" ?>"></button>
                    <?php else: ?>
                        <div class="tile pair-found"></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </form>
</body>
</html>