<?php

class Card
{
    public readonly int $id;
    // public readonly string $title;

    public bool $revealed;

    public readonly string $img_path;

    public function __construct()
    {
        $this->revealed = false;
    }

    public static function byCopy(Card $card): Card
    {
        $copy = new self();
        $copy->id = $card->id;
        $copy->img_path = $card->img_path;
        return $copy;
    }
}
