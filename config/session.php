<?php
include "candyDirectory.php";
session_start();
$user_check = $_SESSION['login_user'];
// $login_session = $row['userName'];

if (isset($_SESSION['login_user'])) {
   $currentUserName = $_SESSION['login_user'];
   $currentUserQuery = "SELECT * FROM users WHERE userName = '$currentUserName'";
   $currentUserResult = $candyDirectoryConnection->query($currentUserQuery);
   $currentUser = mysqli_fetch_array($currentUserResult);
   $userCanManage = $currentUser["userCanManageCandyTrains"];
};

if (!isset($_SESSION['login_user']) && $pageRequiresLogin != false) {
   header("location: https://trains.candycryst.com/config/login.php");
   die();
}
