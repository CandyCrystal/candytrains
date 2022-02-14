<?php $pageRequiresLogin = false;
include "./config/session.php";
include "./config/connect.php";

include "./config/navbar.php";
$navbarClass = new getNavbar(isset($_SESSION['login_user']), $userIsAdmin, "stationList");
$navbar = $navbarClass->getNavbar();

include "./config/candyDirectory.php";
include "./database/getStationData.php";
if (isset($_SESSION['login_user'])) {
    $currentUserName = $_SESSION['login_user'];
    $currentUserQuery = "SELECT * FROM users WHERE userName = '$currentUserName'";
    $currentUserResult = $candyDirectoryConnection->query($currentUserQuery);
    $currentUser = mysqli_fetch_array($currentUserResult);
    $userCanManage = $currentUser["userCanManageCandyTrains"];
};
$searchTerm = $_GET["query"];
include "./database/getPlatformData.php";
$platformDataQuery = new getPlatformData($databaseConnection);

$stationQuery = new getStationData($databaseConnection);
$stationList = $stationQuery->getStations($searchTerm);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="X-UA-Compatible" content="ie=edge"> -->
    <title>List of stations</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/185bd08920.js" crossorigin="anonymous"></script>
    <link rel="icon" href="./assets/media/icon.png" type="image/png" />

    <link rel="stylesheet" href="./assets/css/lineImages.css">
    <link rel="stylesheet" href="./assets/css/topnav.css">
    <script src="./assets/js/topnav.js"></script>
</head>

<body>
    <?php echo $navbar; ?>
    <table>
        <tr>
            <th>Search</th>
        </tr>
        <tr>
            <form action="./stationList.php" method="get">
                <th>
                    <input type="text" name="query">
                    <input type="submit" value="Search" class="button">
                </th>
            </form>
        </tr>
    </table>
    <table>
        <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Type</th>
            <th colspan="3"></th>
        </tr>
        <?php while ($row = mysqli_fetch_array($stationList)) {
            $linesServed = $stationQuery->getStationLines($row["station_ref"])
        ?>
            <tr>
                <td><?php echo $row["station_ref"]; ?></td>
                <td><?php echo $row["station_name"]; ?></td>
                <td><?php echo $row["station_type"]; ?></td>
                <th><a href="./station.php?stationRef=<?php echo $row["station_ref"]; ?>">View&nbspstation</a></th>
                <?php if ($row["station_lat"] != null && $row["station_lng"] != null) {
                    echo '<th><a href="./stationMap.php?zoom=14&lat=' . $row["station_lat"] . '&lng=' . $row["station_lng"] . '">View&nbspon&nbspmap</a></th>';
                } else {
                    echo "<th></th>";
                } ?>

                <th class="listLinesServed">
                    <?php while ($row2 = mysqli_fetch_array($linesServed)) { ?>

                        <a href="./line.php?line=<?php echo $row2["line_name"] ?>">
                            <div class="linebox trainList <?php echo $row2["line_name"]; ?>">
                                <div class="text"><?php echo $row2["line_name"]; ?></div>
                            </div>
                        </a>
                    <?php } ?>
                </th>
            </tr>
        <?php } ?>
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