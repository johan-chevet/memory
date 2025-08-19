<?php
require_once "./init-session.php";

if (isset($_POST["disconnect"])) {
    $_SESSION["user"] = null;
}
?>
<header>
    <div class="navbar">
        <a href="index.php">
            <h1>Memory</h1>
        </a>
        <?php if (isset($_SESSION["user"])): ?>
            <div class="navbar-right-content">
                <div class="user-connected"><?= $_SESSION["user"]->getUsername() ?></div>
                <form action="./index.php" method="post">
                    <input class="nav-btn" type="submit" name="disconnect" value="Disconnect">
                </form>
            </div>
        <?php else: ?>
            <div class="navbar-right-content">
                <a class="nav-btn" href="./login.php">Login</a>
                <a class="nav-btn" href="./register.php">Register</a>
            </div>
        <?php endif; ?>
    </div>
</header>