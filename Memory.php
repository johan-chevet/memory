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

    public function __construct()
    {
        $this->deck = $this->fetchDeck();
        $this->setRandomCardsFromDeck($this->nbOfPairs);
    }

    public function fetchDeck(): array
    {
        $stmt = Connection::getInstance()->pdo->query("SELECT * FROM cards");
        $deck = $stmt->fetchAll(PDO::FETCH_CLASS, "Card");
        if (count($deck) < 12) {
            throw new InvalidArgumentException("Deck should at least have 12 different values");
        }
        return $deck;
    }

    public function startGame(): void
    {
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
        // var_dump($this->cards);
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
    public function setCardSelected(int $index): void
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

        } else {
            $this->firstSelectedCardIndex = $index;
            $this->secondSelectedCardIndex = -1;
        }
    }

    private function isGameOver(): bool {
        return $this->matches === $this->nbOfPairs;
    }
    public function getScore(): int
    {
        $score = ($this->matches * 100) - ($this->misses * 10);
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
}
