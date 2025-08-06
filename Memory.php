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
    public bool $gameStarted;
    public int $idOfLastCardRevealed;
    //TODO do with index to prevent click on same card 
    public Card | null $firstCardSelected;
    public Card | null $secondCardSelected;
    public array $cards;

    /**
     * @param Card[]
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
        $this->idOfLastCardRevealed = -1;
        $this->firstCardSelected = null;
        $this->secondCardSelected = null;
    }

    public function startGame(int $nbOfPairs)
    {
        $this->cards = [];
        $this->setRandomCardsFromDeck($nbOfPairs);
        $this->gameStarted = true;
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
}
