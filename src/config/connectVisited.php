<?php
$servername = "192.168.10.200";
$username = "stationsVisited";
$password = "j7!9Vxy&3^Lv";
$dbname = "candyTrainsNorway";
$databaseConnection = mysqli_connect($servername, $username, $password, $dbname);
$databaseConnection->set_charset("utf8mb4");
if ($databaseConnection->connect_error) {
    die("connection failed: " . $databaseConnection->connect_error);
}
