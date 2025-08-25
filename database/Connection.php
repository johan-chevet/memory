<?php

class Connection
{
    private static ?Connection $instance = null;
    public ?PDO $pdo = null;

    private function __construct(array $config)
    {
        $this->pdo = new PDO(...$config);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            $config = include __DIR__ . "/config.php";
            self::$instance = new self($config);
        }
        return self::$instance;
    }
}
