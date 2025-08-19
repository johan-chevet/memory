<?php
require_once "Card.php";
require_once "Memory.php";
require_once "User.php";
require_once "init-session.php";

$user = $_SESSION["user"] ?? null;

if (isset($_SESSION["game"])) {
    $game = $_SESSION["game"];
    if (!($game instanceof Memory)) {
        throw new Exception("Game is not instance Memory");
    }
} else {
    $game = new Memory();
    $_SESSION["game"] = $game;
}

if (!empty($_POST["category"])) {
    $_SESSION["category"] = $_POST["category"];
}

if (isset($_POST["menu"])) {
    if ($_POST["menu"] === "start" && isset($_POST["category"]) && !$game->gameStarted) {
        $game->startGame($_POST["category"]);
    } else if ($_POST["menu"] === "quit" && $game->gameStarted) {
        $game->stopGame();
    }
}
if (isset($_POST["nb-pairs"]) && !$game->gameStarted) {
    $game->setRandomCardsFromDeck((int) $_POST["nb-pairs"]);
}

if (isset($_POST["reveal"])) {
    $index = (int) $_POST["reveal"];
    $game->setCardSelected($index, $user?->getId());
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
    <?php include "./navbar.php"; ?>
    <div class="content-wrapper">
        <div class="sidebar left">

            <form action="./index.php" method="post">
                <?php if (!$game->gameStarted): ?>
                    <div class="menu">
                        <h2>Category</h2>
                        <select class="game-mode" name="category" id="category">
                            <?php foreach ($game->getCategories() as $category): ?>
                                <?php $selected = isset($_SESSION['category']) && $_SESSION['category'] === $category ? 'selected' : ''; ?>
                                <option class="game-mode" value="<?= htmlspecialchars($category) ?>" <?= $selected ?>>
                                    <?= htmlspecialchars($category) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <h2>Game modes</h2>
                        <button type="submit" class="game-mode" name="nb-pairs" value="3">3 PAIRS</button>
                        <button type="submit" class="game-mode" name="nb-pairs" value="6">6 PAIRS</button>
                        <button type="submit" class="game-mode" name="nb-pairs" value="8">8 PAIRS</button>
                        <button type="submit" class="game-mode" name="nb-pairs" value="10">10 PAIRS</button>
                        <button type="submit" class="game-mode" name="nb-pairs" value="12">12 PAIRS</button>
                        <button type="submit" class="game-mode" name="menu" value="start">Start game</button>
                    </div>
                <?php else: ?>
                    <div class="in-game">
                        <p>Moves: <?= $game->moves ?></p>
                        <p>Misses: <?= $game->misses ?></p>
                        <p>Accuracy: <?= $game->getAccuracy() ?>%</p>
                        <p>Score: <?= $game->getScore() ?></p>
                        <button type="submit" class="game-mode" name="menu" value="quit">Quit game</button>

                    </div>
                <?php endif; ?>
            </form>
        </div>
        <div class="grid-wrapper">
            <form action="./index.php" method="post">
                <div
                    class="grid-game grid-col-<?= $game->nbOfPairs ?> <?= $game->gameStarted ? "game-started" : "game-not-started" ?>">
                    <?php foreach ($game->cards as $key => $card): ?>
                        <?php if (!$card->pairFound): ?>
                            <button class="tile pair-not-found" name="reveal" value="<?= $key ?>" type="submit"
                                style="<?= "background-image: url(" . $game->getImageFromIndex($key) . "); background-size: cover;" ?>"></button>
                        <?php else: ?>
                            <div class="tile pair-found"></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </form>
        </div>
        <div class="sidebar right">
            <h2>Leaderboard</h2>
            <div class="leaderboard-content">
                <?php
                $learderboard = $game->getLeaderboard();
                ?>
                <?php foreach ($learderboard as $index => $row): ?>
                    <div class="leaderboard-row">
                        <div class="leaderboard-username">
                            <?php if (strlen($row["username"]) > 7) {
                                $row["username"] = substr($row["username"], 0, 7) . "..";
                            }
                            ?>
                            <?= $index + 1 . ". " . $row["username"] ?>
                        </div>
                        <div class="leaderboard-score">
                            <?= $row["score"] ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>

</html>