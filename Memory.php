<?php
require_once "./database/Connection.php";
class Memory
{
    /**
     * @var Card[]
     */
    public array $deck;

    /**
     * @var Card[]
     */
    public array $cards;
    public bool $gameStarted = false;
    // public bool $gameStarted;
    public int $firstSelectedCardIndex = -1;
    public int $secondSelectedCardIndex = -1;
    public int $nbOfPairs = 6;

    public int $moves = 0;
    private int $matches = 0;
    public int $misses = 0;

    private array $categories = [];

    private array $leaderboard = [];

    public function __construct()
    {
        $this->categories = Connection::getInstance()->pdo->query("SELECT category FROM cards GROUP BY category")->fetchAll(PDO::FETCH_COLUMN);
        $this->deck = $this->fetchDeck();
        $this->setRandomCardsFromDeck($this->nbOfPairs);
        $this->updateLeaderboard();
    }

    public function fetchDeck(string $category = "default"): array
    {
        if (!in_array($category, $this->categories)) {
            $category = "default";
        }
        $stmt = Connection::getInstance()->pdo->prepare("SELECT * FROM cards WHERE category=?");
        $stmt->execute([$category]);
        $deck = $stmt->fetchAll(PDO::FETCH_CLASS, "Card");
        if (count($deck) < 12) {
            throw new InvalidArgumentException("Deck should at least have 12 different values");
        }
        return $deck;
    }

    public function startGame(string $category): void
    {
        if ($category !== $this->deck[0]->getCategory()) {
            $this->deck = $this->fetchDeck($category);
            $this->setRandomCardsFromDeck($this->nbOfPairs);
        }
        $this->gameStarted = true;
    }

    public function stopGame(): void
    {
        // todo reset at start of new game?
        $this->gameStarted = false;
        $this->firstSelectedCardIndex = -1;
        $this->secondSelectedCardIndex = -1;
        $this->moves = 0;
        $this->misses = 0;
        $this->matches = 0;
        $this->setRandomCardsFromDeck($this->nbOfPairs);
    }

    public function setRandomCardsFromDeck(int $nbOfPairs): void
    {
        if ($this->gameStarted) {
            return;
        }
        if (
            $nbOfPairs !== 3 && $nbOfPairs !== 6 && $nbOfPairs !== 8 &&
            $nbOfPairs !== 10 && $nbOfPairs !== 12
        ) {
            // default value
            $this->nbOfPairs = 6;
        } else {
            $this->nbOfPairs = $nbOfPairs;
        }
        $this->cards = [];
        shuffle($this->deck);
        for ($i = 0; $i < $this->nbOfPairs; $i++) {
            $this->cards[] = Card::byCopy($this->deck[$i]);
            $this->cards[] = Card::byCopy($this->deck[$i]);
        }
        shuffle($this->cards);
    }

    public function getImageFromIndex(int $index): string
    {
        if ($index === $this->firstSelectedCardIndex || $index === $this->secondSelectedCardIndex) {
            return $this->cards[$index]->img_path;
        }
        return "";
    }

    /** 
     * @param int $index Index of the card selected from the current array of cards
     */
    public function setCardSelected(int $index, ?int $userId): void
    {
        if (!$this->gameStarted)
            return;
        if ($this->firstSelectedCardIndex === $index || $this->firstSelectedCardIndex < 0) {
            $this->secondSelectedCardIndex = -1;
            $this->firstSelectedCardIndex = $index;
        } else if ($this->secondSelectedCardIndex < 0) {
            $this->secondSelectedCardIndex = $index;
            $firstCardIndex = $this->firstSelectedCardIndex;
            $secondCardIndex = $this->secondSelectedCardIndex;
            $this->moves++;
            // Pair found
            if ($this->cards[$firstCardIndex]->id === $this->cards[$secondCardIndex]->id) {
                $this->cards[$firstCardIndex]->pairFound = true;
                $this->cards[$secondCardIndex]->pairFound = true;
                $this->firstSelectedCardIndex = -1;
                $this->secondSelectedCardIndex = -1;
                $this->matches++;
            } else if ($this->cards[$firstCardIndex]->isDiscovered() || $this->cards[$secondCardIndex]->isDiscovered()) {
                $this->misses++;
            }
            $this->cards[$firstCardIndex]->setDiscovered();
            $this->cards[$secondCardIndex]->setDiscovered();
            if ($this->isGameOver()) {
                $this->insertToLeaderboard($userId);
            }

        } else {
            $this->firstSelectedCardIndex = $index;
            $this->secondSelectedCardIndex = -1;
        }
    }

    private function isGameOver(): bool
    {
        return $this->matches === $this->nbOfPairs;
    }

    private function updateLeaderboard(): void
    {
        $stmt = Connection::getInstance()->pdo->query(
            "SELECT score, users.username as username FROM leaderboard LEFT JOIN users ON users.id = user_id ORDER BY score DESC LIMIT 10"
        );
        $this->leaderboard = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function insertToLeaderboard(?int $userId): void
    {
        if (!$userId || $this->getScore() === 0)
            return;
        $stmt = Connection::getInstance()->pdo->prepare("INSERT INTO leaderboard (score, user_id) VALUES (?,?)");
        $stmt->execute([$this->getScore(), $userId]);
        $this->updateLeaderboard();
    }
    public function getScore(): int
    {
        $score = $this->matches * 100 - $this->misses * 10;
        if ($score <= 0)
            return 0;
        return $score;
    }

    public function getAccuracy(): int
    {
        if ($this->moves === 0)
            return 0;
        return round(($this->matches / $this->moves) * 100);
    }

    public function getLeaderboard(): array
    {
        return $this->leaderboard;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }
}
