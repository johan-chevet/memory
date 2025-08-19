<?php
require_once "init-session.php";
require_once "User.php";

function getFormValue($name): string
{
    return htmlspecialchars($_POST[$name] ?? "");
}

if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST["submit-login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $errors = [];
    if (isset($username) && isset($password)) {
        $userOrErrors = User::login($username, $password);
        if ($userOrErrors instanceof User) {
            $_SESSION["user"] = $userOrErrors;
            header("Location: index.php");
            exit();
        } else {
            $errors = $userOrErrors;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="auth.css">
</head>

<body>
    <header>
        <?php include "navbar.php"; ?>
    </header>
    <form method="post">
        <div class="auth-form-container">
            <div class="auth-form-content">
                <h1>Login</h1>
                <div class="auth-form-row">
                    <label for="username">Username: </label>
                    <input type="text" name="username" id="username" value=<?= getFormValue("username") ?>>
                    <?php if (isset($errors["username"])) {
                        echo "<p>" . $errors["username"] . "</p>";
                    }
                    ?>
                </div>
                <div class="auth-form-row">
                    <label for="password">Password: </label>
                    <input type="password" name="password" id="password" value=<?= getFormValue("password") ?>>
                    <?php if (isset($errors["password"])) {
                        echo "<p>" . $errors["password"] . "</p>";
                    }
                    ?>
                </div>
                <div class="submit-button">
                    <input type="submit" name="submit-login" value="Sign in" />
                </div>
            </div>
        </div>
    </form>
</body>

</html>