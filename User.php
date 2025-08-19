<?php
require_once "./database/Connection.php";
class User
{
    private int $id;
    private string $username;

    private function __construct(int $id, string $username)
    {
        $this->id = $id;
        $this->username = $username;
    }

    /**
     * @param string $username
     * @param string $password
     * @return self|array instance of User or an array of errors
     */
    public static function register(string $username, string $password, string $pwConfirmation): self|array
    {
        $errors = [];
        $username = trim($username);
        $password = trim($password);
        if (!preg_match("/^[a-zA-Z0-9_-]{3,15}$/", $username)) {
            $errors["username"] = "Please choose a username 3–15 characters long using only letters, numbers,
             underscores (_), or hyphens (-).";
        }
        if (!preg_match("/^[a-zA-Z0-9_\-#?!@$ %^&*]{3,}$/", $username)) {
            $errors["password"] = "Password must be at least 3 characters long and can contain letters, numbers,
             underscores, hyphens, and special characters (!@#$%^&*).";
        }
        if ($password !== $pwConfirmation) {
            $errors["password-confirmation"] = "The passwords doesn't match";
        }

        if (count($errors)) {
            return $errors;
        }
        $pdo = Connection::getInstance()->pdo;
        $stmt = $pdo->prepare("SELECT id FROM users WHERE LOWER(username) = :username");
        $stmt->execute(["username" => strtolower($username)]);
        $userExist = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($userExist) {
            $errors["username"] = "This username is already taken.";
            return $errors;
        }
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashedPassword]);
        return new self($pdo->lastInsertId(), $username);
    }

    /**
     * @param string $username
     * @param string $password
     * @return self|array instance of User or an array of errors
     */
    public static function login(string $username, string $password): self|array
    {
        $errors = [];
        $username = trim($username);
        $password = trim($password);
        if (empty($username)) {
            $errors["username"] = "Username field is empty";
        }
        if (empty($password)) {
            $errors["password"] = "Password field is empty";
        }
        if (count($errors)) {
            return $errors;
        }

        $pdo = Connection::getInstance()->pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE LOWER(username) = ?");
        $stmt->execute([strtolower($username)]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            $errors["username"] = "User doesn't exist.";
            return $errors;
        } else if (!password_verify($password, $user["password"])) {
            $errors["password"] = "Password incorrect.";
            return $errors;
        }
        return new self($user["id"], $username);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}