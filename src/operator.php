<?php

include "./config/footer.php";
include "./config/navbar.php";
$navbarClass = new getNavbar(isset($_SESSION['login_user']), $userIsAdmin, "");
$navbar = $navbarClass->getNavbar();
$operator = $_GET["operator"];

include "./config/connect.php";
include "./config/session.php";
include "./database/getOperatorData.php";
$operatorDataQuery = new getOperatorData($databaseConnection);

$operatorInfo = $operatorDataQuery->getOperatorInfo($operator);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/lineImages.css">
    <link rel="stylesheet" href="./assets/css/main.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="./assets/css/topnav.css">
    <title>Document</title>
</head>

<body>
    <?php echo $navbar; ?>
    <h1><?php echo $operatorInfo["operator_name"] ?></h1>
</body>

</html>