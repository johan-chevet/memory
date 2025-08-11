<?php

class Card
{
    public readonly int $id;
    // public readonly string $title;

    public bool $pairFound;
    private int $discovered;

    public readonly string $img_path;

    public function __construct()
    {
        $this->pairFound = false;
        $this->discovered = false;
    }

    public static function byCopy(Card $card): Card
    {
        $copy = new self();
        $copy->id = $card->id;
        $copy->img_path = $card->img_path;
        return $copy;
    }

    public function isDiscovered(): bool {
        return $this->discovered;
    }

    public function setDiscovered(): void {
        $this->discovered = true;
    }
}
