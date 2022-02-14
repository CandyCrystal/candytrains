<?php $pageRequiresLogin = false;
include "./config/session.php";
include "./config/connect.php";

include "./config/navbar.php";
$navbarClass = new getNavbar(isset($_SESSION['login_user']), $userIsAdmin, "haome");
$navbar = $navbarClass->getNavbar();

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

$numPlatforms = mysqli_num_rows($platformsRow);

include "./database/getNextToData.php";
$nextToDataQuery = new getNextToData($databaseConnection);
$nextTos = $nextToDataQuery->getNextTos($stationRef);

include "./database/getLineData.php";
$lineDataQuery = new getLineData($databaseConnection);

include "./database/getNextToEntryData.php";
$nextToEntryDataQuery = new getNextToEntryData($databaseConnection);

$linesServed = $stationDataQuery->getStationLines($stationRef)

?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./assets/css/main.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400&display=swap" rel="stylesheet">
    <title>Station Information - CandyTrains</title>
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo $stationInfo["station_name"] ?> - Candytrains" />
    <meta property="og:description" content="<?php echo $stationInfo["station_name"] ?> Station on CandyTrains, a website of all things norwegian railways!" />
    <meta property="og:url" content="./station.php?station=<?php echo $stationInfo["station_ref"] ?>" />
    <meta property="og:image" content="https://files.candycryst.com/assets/candyTransport/profile.png" />
    <meta name="theme-color" content="#000000">
    <link rel="icon" href="./assets/media/icon.png" type="image/png" />

    <script src="https://kit.fontawesome.com/185bd08920.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./assets/css/lineImages.css">
    <link rel="stylesheet" href="./assets/css/topnav.css">
    <script src="./assets/js/topnav.js"></script>
</head>

<body>
    <?php echo $navbar; ?>
    <table>
        <tr>
            <th>Search stations</th>
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

    <h1><?php echo $stationInfo["station_name"]; ?> Station</h1>
    <?php



    if (isset($_SESSION['login_user']) && $userCanManageStations == 1) { ?>
        <table class="stationLinks">
            <form action="./database/manageStations.php" method="post">
                <tr>
                    <th class="rightHeader">Station Ref</th>
                    <td>
                        <input disabled type="text" value="<?php echo $stationRef ?>">
                        <input hidden type="text" name="action" value="update">
                        <input hidden type="text" name="stationRef" value="<?php echo $stationRef ?>">
                        <input hidden type="text" name="returnUrl" value="../station.php?stationRef=<?php echo $stationRef ?>">
                    </td>
                </tr>

                <tr>
                    <th class="rightHeader">Name</th>
                    <td><input type="text" value="<?php echo $stationInfo["station_name"] ?>" name="stationName" required></td>
                </tr>
                <tr>
                    <th class="rightHeader">Type</th>
                    <td><?php echo $stationDataQuery->getStationTypeDropdown($stationInfo["station_type"]) ?></td>
                </tr>
                <tr>
                    <th class="rightHeader">Bus</th>
                    <td><input type="checkbox" <?php if ($stationInfo["station_has_bus"] == 1) {
                                                    echo 'checked';
                                                } ?> name="stationBus"></td>
                </tr>
                <tr>
                    <th class="rightHeader">Taxi</th>
                    <td><input type="checkbox" <?php if ($stationInfo["station_has_taxi"] == 1) {
                                                    echo 'checked';
                                                } ?> name="stationTaxi"></td>
                </tr>
                <tr>
                    <th class="rightHeader">Tram</th>
                    <td><input type="checkbox" <?php if ($stationInfo["station_has_tram"] == 1) {
                                                    echo 'checked';
                                                } ?> name="stationTram"></td>
                </tr>
                <tr>
                    <th class="rightHeader">Metro</th>
                    <td><input type="checkbox" <?php if ($stationInfo["station_has_metro"] == 1) {
                                                    echo 'checked';
                                                } ?> name="stationMetro"></td>
                </tr>
                <tr>
                    <th class="rightHeader">Ferry</th>
                    <td><input type="checkbox" <?php if ($stationInfo["station_has_ferry"] == 1) {
                                                    echo 'checked';
                                                } ?> name="stationFerry"></td>
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
                <!-- <th colspan="2">Platform number</th> -->
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
                    <form action="./database/managePlatforms.php" method="post">
                        <select name="platformSide">
                            <?php if ($platformRow["platform_side"] == 0) { ?>
                                <option selected value="0">Unknown</option>
                            <?php } else { ?>
                                <option value="0">Unknown</option>
                            <?php }
                            if ($platformRow["platform_side"] == 1) { ?>
                                <option selected value="1">TO left FROM right</option>
                            <?php } else { ?>
                                <option value="1">TO left FROM right</option>
                            <?php }
                            if ($platformRow["platform_side"] == 2) { ?>
                                <option selected value="2">TO Right FROM left</option>
                            <?php } else { ?>
                                <option value="2">TO Right FROM left</option>

                            <?php } ?>
                        </select>
                        <input hidden type="text" name="returnUrl" value="../station.php?stationRef=<?php echo $stationRef ?>">
                        <input hidden type="text" name="platformID" value="<?php echo $platformRow["platform_id"] ?>">
                        <input hidden type="text" name="action" value="update">
                        <input type="submit" value="Update" class="button">
                    </form>
                </td>

            <?php } ?>

            <tr>
                <form action="./database/managePlatforms.php" method="post">
                    <td>
                        <input type="number" size="1" name="platformNumber">
                    </td>
                    <!-- <td>
                        <input type="number" size="1" name="platformLength" value="240">
                        <input type="checkbox" name="platformHasSectors" checked>
                    </td> -->
                    <td>
                        <input hidden type="text" name="stationRef" value="<?php echo $stationRef ?>">
                        <input hidden type="text" name="action" value="insert">
                        <input hidden type="text" name="returnUrl" value="../station.php?stationRef=<?php echo $stationRef ?>">
                        <input type="submit" value="Add" class="button">
                    </td>
                </form>
            </tr>
        </table>
        <table class="stationLinks">
            <tr>
                <th colspan="8">Lines Served</th>
            </tr>
            <?php
            $count = 1;
            while ($lineRow = mysqli_fetch_array($linesServed)) {
                if ($count % 4 == 1) {
                    echo "<tr>";
                } ?>
                <td>
                    <div class="linebox trainList <?php echo $lineRow["line_name"]; ?>">
                        <div class="text"><?php echo $lineRow["line_name"]; ?></div>
                    </div>
                </td>
                <td>
                    <form action="./database/manageStationLines.php" method="post" class="delete">
                        <!-- <input type="checkbox" required> -->
                        <input hidden type="text" name="ID" value="<?php echo $lineRow["entry_id"] ?>">
                        <input hidden type="text" name="action" value="delete">
                        <input hidden type="text" name="returnUrl" value="../station.php?stationRef=<?php echo $stationRef ?>">
                        <input type="submit" value="Delete" class="button">
                    </form>
                </td>
            <?php
                if ($count % 4 == 0) {
                    echo "</tr>";
                }
                $count++;
            } ?>
            </tr>
            </td>


            <tr>
                <!-- <form action="./database/manageStationLines.php" method="post"> -->
                <td colspan="8">
                    <?php echo $stationDataQuery->getStationLinesDropdown($stationRef); ?>
                </td>
                <!-- <td>
                    <input type="number" size="1" name="platformLength" value="240">
                    <input type="checkbox" name="platformHasSectors" checked>
                </td> -->
                <!-- <td colspan="5">
                        <input hidden type="text" name="stationRef" value="<?php echo $stationRef ?>">
                        <input hidden type="text" name="action" value="insert">
                        <input hidden type="text" name="returnUrl" value="../station.php?stationRef=<?php echo $stationRef ?>">
                        <input type="submit" value="Add" class="button">
                    </td> -->
                <!-- </form> -->
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
                <form action="./database/manageNextTos.php" method="post">
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
        <?php if ($numPlatforms != 0) { ?>
            <tr>
                <th class="rightHeader">Platform&nbspMonitors</th>
                <th>
                    <div><a href="./monitors/platformViewer.php?size=small&station=<?php echo $stationRef ?>">All Small</a> <a href="./monitors/platformViewer.php?station=<?php echo $stationRef ?>">All normal</a> <a href="./monitors/platformViewer.php?size=big&monitors=true&station=<?php echo $stationRef ?>">All Large</a></div>
                    <?php
                    $platformsRow = $platformDataQuery->getPlatforms($stationRef);
                    while ($row2 = mysqli_fetch_array($platformsRow)) { ?>
                        <!-- <div class="platformNumberEdit"><a href="./monitor.php?type=platform&station=<?php echo $stationRef ?>&platform=<?php echo $row2["platform_number"] ?>"><?php echo $row2["platform_number"] ?></div> -->
                        <div class="platformNumberEdit"><a href="https://rtd.kv.banenor.no/index.html#/?id=<?php echo $stationRef ?>%2F<?php echo $row2["platform_number"] ?>"><?php echo $row2["platform_number"] ?></div>
                    <?php } ?>
                    </td>
            </tr>
        <?php } ?>
        <tr>
            <th class="rightHeader">Departures/Arrivals</th>
            <td>
                <a href="./monitors/stationMonitors.php?type=departures&station=<?php echo $stationRef ?>">Departures</a><br />
                <a href="./monitors/stationMonitors.php?type=departuresNoCancel&station=<?php echo $stationRef ?>">Non-cancelled departures</a><br />
                <a href="./monitors/stationMonitors.php?type=cancelled&station=<?php echo $stationRef ?>">Cancelled departures</a><br />
                <a href="./monitors/stationMonitors.php?type=nextTo&station=<?php echo $stationRef ?>">Next departure towards</a><br />
                <a href="./monitors/stationMonitors.php?type=arrivals&station=<?php echo $stationRef ?>">Arrivals</a><br />
                <a href="./monitors/stationMonitors.php?type=arrivals_departures&station=<?php echo $stationRef ?>">Arrivals/Departures combo</a><br />
                <a href="./monitors/stationMonitors.php?type=everything&station=<?php echo $stationRef ?>">Arrivals/Departures journey tracker</a><br />
            </td>
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