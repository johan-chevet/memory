<?php

class Card
{
    public readonly int $id;
    // public readonly string $title;

    public bool $revealed;

    public function __construct()
    {
        $this->revealed = false;
    }
}
