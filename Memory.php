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

    /**
     * @param Card[]
     */
    public function __construct(array $deck)
    {
        // TODO test
        $deck = array_unique($deck);
        if (count($deck) < 12) {
            throw new InvalidArgumentException("Deck should at least have 12 different values");
        }
        $this->deck = $deck;
    }

    public function startGame(int $nbOfPairs)
    {
        $randomCards = array_rand($this->deck, $nbOfPairs);
        for ($i = 0; $i < count($randomCards); $i++) {
            $card = array_rand($randomCards, 1)[0];
            // TODO 
            // if (array_find($this->cards, function (Card $card) {
            //     return $id === $card->id;
            // }) !== null) {
        }
    }
}
