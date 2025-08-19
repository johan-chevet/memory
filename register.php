<?php
require_once "init-session.php";
require_once "User.php";

function getFormValue($name): string
{
    return htmlspecialchars($_POST[$name] ?? "");
}

//TODO if user redirect

if (isset($_POST["submit-register"])) {
    $errors = [];
    if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["password-confirmation"])) {
        $userOrErrors = User::register($_POST["username"], $_POST["password"], $_POST["password-confirmation"]);
        if ($userOrErrors instanceof User) {
            $_SESSION["user"] = $userOrErrors;
            header("Location: index.php");
            exit;
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
    <title>Register</title>
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
                <h1>Register</h1>
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
                <div class="auth-form-row">
                    <label for="password-confirmation">Password confirmation: </label>
                    <input type="password" name="password-confirmation" id="password-confirmation"
                        value=<?= getFormValue("password-confirmation") ?>>
                    <?php if (isset($errors["password-confirmation"])) {
                        echo "<p>" . $errors["password-confirmation"] . "</p>";
                    }
                    ?>
                </div>
                <div class="submit-button">
                    <input type="submit" name="submit-register" value="Create account" />
                </div>
            </div>
        </div>
    </form>
</body>

</html>