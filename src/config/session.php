<?php
include "candyDirectory.php";
session_start();
// $user_check = $_SESSION['login_user'];
$userCanManageStations = 0;
$userCanManageTrains = 0;
if (isset($_SESSION['login_user'])) {
   $currentUserName = $_SESSION['login_user'];
   $currentUserQuery = "SELECT * FROM users WHERE userName = '$currentUserName'";
   $currentUserResult = $candyDirectoryConnection->query($currentUserQuery);
   $currentUser = mysqli_fetch_array($currentUserResult);
   $userCanManageStations = $currentUser["candyTrainsStationManager"];
   $userCanManageTrains = $currentUser["candyTrainsTrainManager"];
};

if (!isset($_SESSION['login_user']) && $pageRequiresLogin != false) {
   header("location: https://trains.candycryst.com/config/login.php");
   die();
}

if ($pageRequiresStationManager == true && $userCanManageStations != 1) {
   header("location: https://trains.candycryst.com");
   die();
}

if ($pageRequiresTrainManager == true && $userCanManageTrains != 1) {
   header("location: https://trains.candycryst.com");
   die();
}
