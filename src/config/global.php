<?php
include "./config/session.php";
include "./config/connect.php";
include "./config/candyDirectory.php";
if (isset($_SESSION['login_user'])) {
    $loginState = "<a href='https://www.minrule.com/config/logout.php'>Log out</a>";
} else {
    $loginState = "<a href='https://www.minrule.com/config/login.php'>Log in</a>";
}
$header = '<div class="header">
<p>
<a href="https://www.minrule.com/">Homepage</a>
<a href="https://www.minrule.com/minerule.php">Old Minerule</a>
<a href="https://www.minrule.com/newminerule.php">New Minerule</a>
    <a href="https://discord.gg/0wowoyXBYvlvboMw">Discord</a>
    <a href="https://www.minrule.com/population.php">Population</a>
    <a href="https://www.minrule.com/buildlog.php">Buildlog</a>
    ' . $loginState . '
</p>
</div>';
$style = '<link rel="icon" href="https://www.minrule.com/assets/media/mineruleIcon.png" type="image/png" />
<link rel="stylesheet" href="https://www.minrule.com/assets/css/main.css">
<link href="https://fonts.googleapis.com/css?family=Barlow&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://files.candycryst.com/global/colorPalette.css">
<script src="https://kit.fontawesome.com/185bd08920.js" crossorigin="anonymous"></script>';
