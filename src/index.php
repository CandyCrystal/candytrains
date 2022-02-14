<?php
include "./config/connect.php";
include "./config/session.php";

include "./config/navbar.php";
$navbarClass = new getNavbar(isset($_SESSION['login_user']), $userIsAdmin, "home");
$navbar = $navbarClass->getNavbar();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="./assets/css/main.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400&display=swap" rel="stylesheet">
        <title>CandyTrains</title>
        <link rel="icon" href="./assets/media/icon.png" type="image/png" />

        <meta property="og:type" content="website">
        <meta property="og:title" content="Candytrains" />
        <link rel="stylesheet" href="./assets/css/footer.css">
        <meta property="og:description" content="A website of all things norwegian railways!" />
        <meta property="og:url" content="https://trains.candycryst.com" />
        <meta property="og:image" content="https://trains.candycryst.com/assets/media/icon.png" />
        <meta name="theme-color" content="#c54365">
        <meta property="og:image" content="https://trains.candycryst.com/assets/media/icon.png" />

        <script src="https://kit.fontawesome.com/185bd08920.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="./assets/css/main.css">
        <link rel="stylesheet" href="./assets/css/topnav.css">
        <script src="./assets/js/topnav.js"></script>
    </head>
</head>

<body>
    <?php echo $navbar; ?>
    <h1>Welcome to CandyTrains</h1>
    <h2>On this website you can find lots of norwegian railway stuff!</h2>
    <table>
        <tr>
            <th colspan="2">Useful Links</th>
        </tr>
        <tr>
            <td>Station Search</td>
            <form action="./stationList.php" method="get">
                <th>
                    <input type="text" name="query">
                    <input type="submit" value="Search" class="button">
                </th>
            </form>
        </tr>
        <tr>
            <th colspan="2">Popular stations</th>
        </tr>
        <tr>
            <th><a href="./station.php?stationRef=ASR">Asker</a></th>
            <th><a href="./station.php?stationRef=BRG">Bergen</a></th>
        </tr>
        <tr>
            <th><a href="./station.php?stationRef=BO">Bodø</a></th>
            <th><a href="./station.php?stationRef=DRM">Drammen</a></th>
        </tr>
        <tr>
            <th><a href="./station.php?stationRef=ELV">Elverum</a></th>
            <th><a href="./station.php?stationRef=FRE">Fredrikstad</a></th>
        </tr>
        <tr>
            <th><a href="./station.php?stationRef=NTH">Nationaltheatret</a></th>
            <th><a href="./station.php?stationRef=OSL">Oslo S</a></th>
        </tr>
        <tr>
            <th><a href="./station.php?stationRef=SV">Sandvika</a></th>
            <th><a href="./station.php?stationRef=SKI">Ski</a></th>
        </tr>
        <tr>
            <th><a href="./station.php?stationRef=STV">Stavanger</a></th>
            <th><a href="./station.php?stationRef=TND">Trondheim S</a></th>
        </tr>
    </table>
    <footer class="footerParent">
        <div class="infoFooter">
            <h1>CandyCryst | Trains</h1>

            <div class="footerLink">
                <p>Websites by CandyCrystal</p>
            </div>
            <div class="footerLink">
                <ul>
                    <li><a href="https://www.candycryst.com">CandyCryst.com</a></li>
                    <li><a href="https://trains.candycryst.com">CandyTrains</a></li>
                    <li><a href="https://transportmap.candycryst.com">Transport map</a></li>
                    <li><a href="https://www.minrule.com">Minrule.com</a></li>
                    <li><a href="https://www.candytransport.com">CandyTransport</a></li>
                </ul>
            </div>
        </div>
    </footer>
</body>

</html>