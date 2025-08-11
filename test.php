<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
<style>
    body {
        margin: 0;
        font-family: sans-serif;
    }

    .navbar {
        display: flex;
        background: #333;
        color: white;
        align-items: center;
        height: 10%;
    }

    html, body {
        height: 100%;
        margin: 0;
    }

    .content-wrapper {
        display: flex;
        min-height: 0;
        height: 90%;
        overflow: hidden;
    }


    .sidebar {
        width: 200px;
        padding: 1rem;
        background: #f0f0f0;
    }

    .main-content {
        flex: 1;
        padding: 1rem;
        background: aqua;
    }



</style>
</head>
<body>
<div class="navbar">
    <h1>Memory Game</h1>
</div>

<div class="content-wrapper">
    <div class="sidebar left">
        Left Sidebar
    </div>
    <div class="main-content">
        Main Game Board
    </div>
    <div class="sidebar right">
        Leaderboard
    </div>
</div>


</body>
</html>