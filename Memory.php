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
        $this->nbOfPairs = 4;
    }

    public function startGame(int $nbOfPairs)
    {
        if (
            $nbOfPairs !== 3 && $nbOfPairs !== 6 && $nbOfPairs !== 8 &&
            $nbOfPairs !== 10 && $nbOfPairs !== 12
        ) {
            // default value
            $this->nbOfPairs = 4;
        } else {
            $this->nbOfPairs = $nbOfPairs;
        }
        $this->cards = [];
        $this->setRandomCardsFromDeck($this->nbOfPairs);
        $this->gameStarted = true;
    }

    public function stopGame()
    {
        $this->cards = [];
        $this->gameStarted = false;
        $this->firstSelectedCardIndex = -1;
        $this->secondSelectedCardIndex = -1;
    }

    private function setRandomCardsFromDeck(int $nbofPairs)
    {
        shuffle($this->deck);
        for ($i = 0; $i < $nbofPairs; $i++) {
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
        return "./assets/card-back-purple.png";
    }
}
