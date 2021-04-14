<?php $pageRequiresLogin = false;
include "./config/session.php";
include "./config/connectNew.php";

include "./database/getPlatformData.php";
include "./database/getStationData.php";
include "./database/getLineData.php";
include "./database/getViaData.php";
include "./database/getViaLineData.php";

$stationDataQuery = new getStationData($databaseConnection);
$lineDataQuery = new getLineData($databaseConnection);
$viaDataQuery = new getViaData($databaseConnection);
$viaLineDataQuery = new getViaLineData($databaseConnection);
$platformDataQuery = new getPlatformData($databaseConnection);

$stationRef = $_GET["stationRef"];
$stationID = $_GET["stationID"];

if ($stationID == "" && $stationRef == "") {
    $stationID = 217;
} else if ($stationRef != "") {
    $stationID = $stationDataQuery->getStationID($stationRef);
}

$vias = $viaDataQuery->getVias($stationID);


$platformsRow = $platformDataQuery->getPlatforms($stationID);

$stationInfo = $stationDataQuery->getStationInformation($stationID);
$stationInfo["stationOpenDate"];
if ($stationInfo["stationOpenDate"] == "") {
    $stationInfo["stationOpenDate"] = "0001-01-01";
}
$stationInfo["stationCloseDate"];
if ($stationInfo["stationCloseDate"] == "") {
    $stationInfo["stationCloseDate"] = "0001-01-01";
}

$stationRef = $stationInfo["stationRef"];
?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400&display=swap" rel="stylesheet">
    <title>Station Information - CandyTrains</title>
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo $stationInfo["stationName"] ?> - Candytrains" />
    <meta property="og:description" content="<?php echo $stationInfo["stationName"] ?> Station on CandyTrains, a website of all things norwegian railways!" />
    <meta property="og:url" content="https://trains.candycryst.com/station.php?station=<?php echo $stationInfo["stationRef"] ?>" />
    <meta property="og:image" content="https://files.candycryst.com/assets/candyTransport/profile.png" />
    <meta name="theme-color" content="#629aa4">
    <link rel="icon" href="https://files.candycryst.com/assets/candyTransport/profile.png" type="image/png" />

</head>

<body>
    <h1><?php echo $stationInfo["stationName"]; ?> Station</h1>
    <?php



    if (isset($_SESSION['login_user']) && $userCanManage == 1) { ?>
        <table class="stationLinks">
            <form action="./database/stations.php" method="post">
                <tr>
                    <th class="rightHeader">ID</th>
                    <td>
                        <input disabled type="text" value="<?php echo $stationID ?>">
                        <input hidden type="text" name="action" value="update">
                        <input hidden type="text" name="stationID" value="<?php echo $stationID ?>">
                        <input hidden type="text" name="returnUrl" value="https://trains.candycryst.com/station.php">
                    </td>
                </tr>
                <tr>
                    <th class="rightHeader">Code</th>
                    <td><input disabled type="text" name="stationRef" value="<?php echo $stationRef ?>"></td>
                </tr>
                <tr>
                    <th class="rightHeader">Name</th>
                    <td><input type="text" value="<?php echo $stationInfo["stationName"] ?>" name="stationName" required></td>
                </tr>
                <tr>
                    <th class="rightHeader">lat</th>
                    <td><input type="text" value="<?php echo $stationInfo["stationLat"] ?>" name="stationLat" required></td>
                </tr>
                <tr>
                    <th class="rightHeader">long</th>
                    <td><input type="text" value="<?php echo $stationInfo["stationLong"] ?>" name="stationLong" required></td>
                </tr>
                <tr>
                    <th class="rightHeader">Open date</th>
                    <td><input type="date" value="<?php echo $stationInfo["stationOpenDate"] ?>" name="stationOpenDate"></td>
                </tr>
                <tr>
                    <th class="rightHeader">Close date</th>
                    <td><input type="date" value="<?php echo $stationInfo["stationCloseDate"] ?>" name="stationCloseDate"></td>
                </tr>
                <tr>
                    <th class="rightHeader">Is closed</th>
                    <td><input type="checkbox" value="<?php echo $stationInfo["stationIsClosed"] ?>" name="stationIsClosed"></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="Done" class="button"></td>
                </tr>
            </form>
        </table>
        <table>
            <tr>
                <th colspan="3">Platforms</th>
            </tr>
            <tr>
                <th>Platform number</th>
                <th>Platform length</th>
                <th></th>
            </tr>
            <tr>
            </tr>
            <?php while ($platformRow = mysqli_fetch_array($platformsRow)) { ?>
                <tr>
                </tr>
                <td>
                    <a href="https://trains.candycryst.com/monitor.php?type=platform&station=<?php echo $stationID ?>&platform=<?php echo $platformRow["platformNumber"] ?>"><?php echo $platformRow["platformNumber"] ?></a>
                </td>
                <td>
                    <?php echo $platformRow["platformLength"] . "m" ?>
                </td>
                <form action="./database/managePlatforms.php" method="post">
                    <td>
                        <input type="checkbox" required>
                        <input hidden type="text" name="platformID" value="<?php echo $platformRow["platformID"] ?>">
                        <input hidden type="text" name="action" value="delete">
                        <input hidden type="text" name="returnUrl" value="https://trains.candycryst.com/station.php?stationRef=<?php echo $stationRef ?>">
                        <input type="submit" value="Delete" class="button">
                    </td>
                </form>
            <?php } ?>

            <tr>
                <form action="./database/managePlatforms.php" method="post">
                    <td>
                        <input type="number" size="1" name="platformNumber">
                    </td>
                    <td>
                        <input type="number" size="1" name="platformLength" value="240">
                        <input type="checkbox" name="platformHasSectors" checked>
                    </td>
                    <td>
                        <input hidden type="text" name="stationID" value="<?php echo $stationID ?>">
                        <input hidden type="text" name="action" value="insert">
                        <input hidden type="text" name="returnUrl" value="https://trains.candycryst.com/station.php?stationRef=<?php echo $stationRef ?>">
                        <input type="submit" value="Add" class="button">
                    </td>
                </form>
            </tr>
        </table>
        <?php
        $num_rows = mysqli_num_rows($vias);
        while ($viaListRow = mysqli_fetch_array($vias)) {
        ?>
            <table>
                <tr>
                    <form action="./database/manageVias.php" method="post">
                        <th colspan="2">
                            <input hidden type="text" name="viaID" value="<?php echo $viaListRow["viaID"] ?>">
                            <input hidden type="text" name="returnUrl" value="https://trains.candycryst.com/station.php?stationRef=<?php echo $stationRef ?>">
                            <input hidden type="text" name="action" value="update">
                            <input size="10" name="viaDestinationText" value="<?php echo $viaListRow["viaDestinationText"] ?>"></input>
                            <input size="30" name="viaNoteText" value="<?php echo $viaListRow["viaNoteText"] ?>"></input>
                            <input type="submit" value="Update" class="button">
                        </th>
                    </form>
                    <form action="./database/manageVias.php" method="post">
                        <td>
                            <input type="checkbox" required>
                            <input hidden type="text" name="viaID" value="<?php echo $viaListRow["viaID"] ?>">
                            <input hidden type="text" name="returnUrl" value="https://trains.candycryst.com/station.php?stationRef=<?php echo $stationRef ?>">
                            <input hidden type="text" name="action" value="delete">
                            <input type="submit" value="Delete" class="button">
                        </td>
                    </form>

                </tr>
                <tr>
                    <th>Line name</th>
                    <th>Destination</th>
                    <th></th>
                </tr>
                <?php
                $viaLinesResult = $viaLineDataQuery->getViaLines($viaListRow["viaID"]);
                while ($viaLineRow = mysqli_fetch_array($viaLinesResult)) { ?>
                    <tr>
                        <form action="./database/manageViaLines.php" method="post">
                            <td>
                                <?php echo $lineDataQuery->getLineDropdown($viaLineRow["viaLineLineID"]); ?>
                            </td>
                            <td>
                                <?php echo $stationDataQuery->getStationDropdown($viaLineRow["viaLineDestinationStationID"]); ?>
                                <input hidden type="text" name="action" value="update">
                                <input hidden type="text" name="viaLineID" value="<?php echo $viaLineRow["viaLineID"] ?>">
                                <input hidden type="text" name="returnUrl" value="https://trains.candycryst.com/station.php?stationRef=<?php echo $stationRef ?>">
                                <input type="submit" value="Submit" class="button">
                            </td>
                        </form>
                        <form action="./database/manageViaLines.php" method="post">
                            <td>
                                <input type="checkbox" required>
                                <input hidden type="text" name="action" value="delete">
                                <input hidden type="text" name="viaLineID" value="<?php echo $viaLineRow["viaLineID"] ?>">
                                <input hidden type="text" name="returnUrl" value="https://trains.candycryst.com/station.php?stationRef=<?php echo $stationRef ?>">
                                <input type="submit" value="Delete" class="button">
                            </td>
                        </form>
                    </tr>
                <?php } ?>
                <tr>
                    <form action="./database/manageViaLines.php" method="post">
                        <td>
                            <?php echo $lineDataQuery->getLineDropdown(0); ?>
                        </td>
                        <td>
                            <?php echo $stationDataQuery->getStationDropdown(0); ?>
                            <input hidden type="text" name="viaLineViaID" value="<?php echo $viaListRow["viaID"] ?>">
                            <input hidden type="text" name="action" value="insert">
                            <input hidden type="text" name="returnUrl" value="https://trains.candycryst.com/station.php?stationRef=<?php echo $stationRef ?>">
                            <input type="submit" value="Add" class="button">
                        </td>
                    </form>
                    <td></td>
                </tr>
            </table>
        <?php }
        if ($num_rows < 4) { ?>
            <table>
                <tr>
                    <th colspan="2">Add via</th>
                </tr>
                <form action="./database/manageVias.php" method="post">
                    <tr>
                        <th class="rightHeader">Text</th>
                        <th>
                            <input hidden type="text" name="returnUrl" value="https://trains.candycryst.com/station.php?stationRef=<?php echo $stationRef ?>">
                            <input hidden type="text" name="action" value="insert">
                            <input hidden type="text" name="stationID" value="<?php echo $stationID ?>">
                            <input required type="text" name="viaDestinationText">
                            <input type="text" name="viaNoteText">
                            <input type="submit" value="Add" class="button">
                        </th>
                    </tr>
                </form>
            </table>
        <?php }
    } else { ?>
        <table>
            <tr>
                <th class="rightHeader">Code</th>
                <td><?php echo $stationInfo["stationRef"] ?></td>
            </tr>
            <tr>
                <th class="rightHeader">Name</th>
                <td><?php echo $stationInfo["stationName"] ?></td>
            </tr>
            <tr>
                <th class="rightHeader">lat</th>
                <td><?php echo $stationInfo["stationLat"] ?></td>
            </tr>
            <tr>
                <th class="rightHeader">long</th>
                <td><?php echo $stationInfo["stationLong"] ?></td>
            </tr>
        </table>
    <?php } ?>
    <table class="stationLinks">
        <tr>
            <th class="rightHeader">Platform&nbspMonitors</th>
            <th>
                <div><a href="./platformViewer.php?station=<?php echo $stationID ?>">All</a></div>
                <?php
                $platformsRow = $platformDataQuery->getPlatforms($stationID);
                while ($row2 = mysqli_fetch_array($platformsRow)) { ?>
                    <div class="platformNumberEdit"><a href="https://trains.candycryst.com/monitor.php?type=platform&station=<?php echo $stationID ?>&platform=<?php echo $row2["platformNumber"] ?>"><?php echo $row2["platformNumber"] ?></div>
                <?php } ?>
                </td>
        </tr>
        <tr>
            <th class="rightHeader">Departures/Arrivals</th>
            <td>
                <a href="https://trains.candycryst.com/monitor.php?type=departures&station=<?php echo $stationID ?>">Main departures</a><br />
                <a href="https://trains.candycryst.com/monitor.php?type=nextTo&station=<?php echo $stationID ?>">Main next departure towards</a><br />
                <a href="https://trains.candycryst.com/monitor.php?type=arrivals&station=<?php echo $stationID ?>">Main arrivals</a><br />
            </td>
        </tr>
        <tr>
            <th class="rightHeader">General links</th>
            <td><a href="./stationList.php">Station List</a><br /><a href="./stationMap.php">Station Map</a></td>
        </tr>
    </table>
</body>

</html>