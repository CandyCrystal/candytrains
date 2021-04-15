<?php $pageRequiresLogin = false;
include "./config/session.php";
include "./config/connect.php";

$stationRef = $_GET["stationRef"];

if ($stationRef == "") {
    $stationRef = "OSL";
}

include "./database/getStationData.php";
$stationDataQuery = new getStationData($databaseConnection);
$stationInfo = $stationDataQuery->getStationInformation($stationRef);

include "./database/getPlatformData.php";
$platformDataQuery = new getPlatformData($databaseConnection);
$platformsRow = $platformDataQuery->getPlatforms($stationRef);

include "./database/getNextToData.php";
$nextToDataQuery = new getNextToData($databaseConnection);
$nextTos = $nextToDataQuery->getNextTos($stationRef);

include "./database/getLineData.php";
$lineDataQuery = new getLineData($databaseConnection);

include "./database/getNextToEntryData.php";
$nextToEntryDataQuery = new getNextToEntryData($databaseConnection);

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
    <meta property="og:title" content="<?php echo $stationInfo["station_name"] ?> - Candytrains" />
    <meta property="og:description" content="<?php echo $stationInfo["station_name"] ?> Station on CandyTrains, a website of all things norwegian railways!" />
    <meta property="og:url" content="./station.php?station=<?php echo $stationInfo["station_ref"] ?>" />
    <meta property="og:image" content="https://files.candycryst.com/assets/candyTransport/profile.png" />
    <meta name="theme-color" content="#629aa4">
    <link rel="icon" href="https://files.candycryst.com/assets/candyTransport/profile.png" type="image/png" />

</head>

<body>
    <h1><?php echo $stationInfo["station_name"]; ?> Station</h1>
    <?php



    if (isset($_SESSION['login_user']) && $userCanManage == 1) { ?>
        <table class="stationLinks">
            <form action="./database/stations.php" method="post">
                <tr>
                    <th class="rightHeader">Station Ref</th>
                    <td>
                        <input disabled type="text" value="<?php echo $stationRef ?>">
                        <input hidden type="text" name="action" value="update">
                        <input hidden type="text" name="stationRef" value="<?php echo $stationRef ?>">
                        <input hidden type="text" name="returnUrl" value="../station.php">
                    </td>
                </tr>

                <tr>
                    <th class="rightHeader">Name</th>
                    <td><input type="text" value="<?php echo $stationInfo["station_name"] ?>" name="stationName" required></td>
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
                    <a href="./monitor.php?type=platform&station=<?php echo $stationRef ?>&platform=<?php echo $platformRow["platform_number"] ?>"><?php echo $platformRow["platform_number"] ?></a>
                </td>
                <td>
                    <?php echo $platformRow["platform_length"] . "m" ?>
                </td>
                <form action="./database/managePlatforms.php" method="post">
                    <td>
                        <input type="checkbox" required>
                        <input hidden type="text" name="platformID" value="<?php echo $platformRow["platform_id"] ?>">
                        <input hidden type="text" name="action" value="delete">
                        <input hidden type="text" name="returnUrl" value="../station.php?stationRef=<?php echo $stationRef ?>">
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
                        <input hidden type="text" name="stationRef" value="<?php echo $stationRef ?>">
                        <input hidden type="text" name="action" value="insert">
                        <input hidden type="text" name="returnUrl" value="../station.php?stationRef=<?php echo $stationRef ?>">
                        <input type="submit" value="Add" class="button">
                    </td>
                </form>
            </tr>
        </table>
        <?php
        $num_rows = mysqli_num_rows($nextTos);
        while ($nextToRow = mysqli_fetch_array($nextTos)) {
        ?>
            <table>
                <tr>
                    <form action="./database/manageNextTos.php" method="post">
                        <th colspan="2">
                            <input hidden type="text" name="nextToID" value="<?php echo $nextToRow["next_to_id"] ?>">
                            <input hidden type="text" name="returnUrl" value="../station.php?stationRef=<?php echo $stationRef ?>">
                            <input hidden type="text" name="action" value="update">
                            <input size="10" name="nextToTitle" value="<?php echo $nextToRow["next_to_title"] ?>"></input>
                            <input size="30" name="nextToContent" value="<?php echo $nextToRow["next_to_content"] ?>"></input>
                            <input type="submit" value="Update" class="button">
                        </th>
                    </form>
                    <form action="./database/manageNextTos.php" method="post">
                        <td>
                            <input type="checkbox" required>
                            <input hidden type="text" name="nextToID" value="<?php echo $nextToRow["next_to_id"] ?>">
                            <input hidden type="text" name="returnUrl" value="../station.php?stationRef=<?php echo $stationRef ?>">
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
                $NextToEntriesResult = $nextToEntryDataQuery->getEntries($nextToRow["next_to_id"]);
                while ($NextToEntriesRow = mysqli_fetch_array($NextToEntriesResult)) { ?>
                    <tr>
                        <form action="./database/manageNextToEntries.php" method="post">
                            <td>
                                <?php echo $lineDataQuery->getLineDropdown($NextToEntriesRow["entry_line"]); ?>
                            </td>
                            <td>
                                <?php echo $stationDataQuery->getStationDropdown($NextToEntriesRow["entry_destination"]); ?>
                                <input hidden type="text" name="action" value="update">
                                <input hidden type="text" name="entryID" value="<?php echo $NextToEntriesRow["entry_id"] ?>">
                                <input hidden type="text" name="returnUrl" value="../station.php?stationRef=<?php echo $stationRef ?>">
                                <input type="submit" value="Submit" class="button">
                            </td>
                        </form>
                        <form action="./database/manageNextToEntries.php" method="post">
                            <td>
                                <input type="checkbox" required>
                                <input hidden type="text" name="action" value="delete">
                                <input hidden type="text" name="entryID" value="<?php echo $NextToEntriesRow["entry_id"] ?>">
                                <input hidden type="text" name="returnUrl" value="../station.php?stationRef=<?php echo $stationRef ?>">
                                <input type="submit" value="Delete" class="button">
                            </td>
                        </form>
                    </tr>
                <?php } ?>
                <tr>
                    <form action="./database/manageNextToEntries.php" method="post">
                        <td>
                            <?php echo $lineDataQuery->getLineDropdown(0); ?>
                        </td>
                        <td>
                            <?php echo $stationDataQuery->getStationDropdown(0); ?>
                            <input hidden type="text" name="entryParent" value="<?php echo $nextToRow["next_to_id"] ?>">
                            <input hidden type="text" name="action" value="insert">
                            <input hidden type="text" name="returnUrl" value="../station.php?stationRef=<?php echo $stationRef ?>">
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
                    <th colspan="2">Add Next To</th>
                </tr>
                <form action="./database/manageNextTo.php" method="post">
                    <tr>
                        <th class="rightHeader">Text</th>
                        <th>
                            <input hidden type="text" name="returnUrl" value="../station.php?stationRef=<?php echo $stationRef ?>">
                            <input hidden type="text" name="action" value="insert">
                            <input hidden type="text" name="stationRef" value="<?php echo $stationRef ?>">
                            <input required type="text" name="nextToTitle">
                            <input type="text" name="nextToContent">
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
                <td><?php echo $stationInfo["station_ref"] ?></td>
            </tr>
            <tr>
                <th class="rightHeader">Name</th>
                <td><?php echo $stationInfo["station_name"] ?></td>
            </tr>

        </table>
    <?php } ?>
    <table class="stationLinks">
        <tr>
            <th class="rightHeader">Platform&nbspMonitors</th>
            <th>
                <div><a href="./platformViewer.php?station=<?php echo $stationRef ?>">All</a></div>
                <?php
                $platformsRow = $platformDataQuery->getPlatforms($stationRef);
                while ($row2 = mysqli_fetch_array($platformsRow)) { ?>
                    <div class="platformNumberEdit"><a href="./monitor.php?type=platform&station=<?php echo $stationRef ?>&platform=<?php echo $row2["platform_number"] ?>"><?php echo $row2["platform_number"] ?></div>
                <?php } ?>
                </td>
        </tr>
        <tr>
            <th class="rightHeader">Departures/Arrivals</th>
            <td>
                <a href="./monitor.php?type=departures&station=<?php echo $stationRef ?>">Main departures</a><br />
                <a href="./monitor.php?type=nextTo&station=<?php echo $stationRef ?>">Main next departure towards</a><br />
                <a href="./monitor.php?type=arrivals&station=<?php echo $stationRef ?>">Main arrivals</a><br />
            </td>
        </tr>
        <tr>
            <th class="rightHeader">General links</th>
            <td>
                <a href="./stationList.php">Station List</a>
                <!-- <br /> -->
                <!-- <a href="./stationMap.php">Station Map</a> -->
            </td>
        </tr>
    </table>
</body>

</html>