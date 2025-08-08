<?php
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
    public bool $gameStarted;
    // public bool $gameStarted;
    public int $firstSelectedCardIndex;
    public int $secondSelectedCardIndex;
    public int $nbOfPairs;

    /**
     * @param Card[] $deck
     */
    public function __construct(array $deck)
    {
        // TODO test
        // $deck = array_unique($deck);
        if (count($deck) < 12) {
            throw new InvalidArgumentException("Deck should at least have 12 different values");
        }
        $this->deck = $deck;
        $this->gameStarted = false;
        $this->firstSelectedCardIndex = -1;
        $this->secondSelectedCardIndex = -1;
        $this->nbOfPairs = 6;
        $this->setRandomCardsFromDeck($this->nbOfPairs);
    }

    public function startGame()
    {
        $this->gameStarted = true;
    }

    public function stopGame()
    {
        $this->gameStarted = false;
        $this->firstSelectedCardIndex = -1;
        $this->secondSelectedCardIndex = -1;
        $this->setRandomCardsFromDeck($this->nbOfPairs);
    }

    public function setRandomCardsFromDeck(int $nbOfPairs)
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
        // Todo put card back in game
        return "";
        // return "./assets/card-back-purple.png";
    }

    /** 
     * @param int $index Index of the card selected from the current array of cards
     */
    public function setCardSelected($index)
    {
        if (!$this->gameStarted)
            return;
        if ($this->firstSelectedCardIndex === $index || $this->firstSelectedCardIndex < 0) {
            $this->firstSelectedCardIndex = $index;
        } else if ($this->secondSelectedCardIndex < 0) {
            $this->secondSelectedCardIndex = $index;

            if ($this->cards[$this->firstSelectedCardIndex]->id === $this->cards[$this->secondSelectedCardIndex]->id) {
                $this->cards[$this->firstSelectedCardIndex]->pairFound = true;
                $this->cards[$this->secondSelectedCardIndex]->pairFound = true;
                $this->firstSelectedCardIndex = -1;
                $this->secondSelectedCardIndex = -1;
            }
        } else {
            $this->firstSelectedCardIndex = $index;
            $this->secondSelectedCardIndex = -1;
        }
    }
}
