<?php
$thisLink = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
include "./config/session.php";
include "./config/connect.php";
include "./database/getPlatformData.php";
include "./database/getStationData.php";
$station = $_GET["station"];
$stationQuery = new getStationData($databaseConnection);
$stationInfo = $stationQuery->getStationInformation($station);
$query = new getPlatformData($databaseConnection);
$platformsResult = $query->getPlatforms($station);
$num_rows = mysqli_num_rows($platformsResult);
if ($_GET["platforms"] != "") {
    $platforms = explode("-", $_GET["platforms"]);
} else {
    $i = 0;
    while ($platform = mysqli_fetch_array($platformsResult)) {
        $platforms[$i] = $platform["platform_number"];
        $i++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $stationInfo["station_name"] ?> Platforms - CandyTrains</title>
    <meta property="og:type" content="website">
    <meta property="og:title" content="Platform Viewer for <?php echo $stationInfo["station_name"] ?> - Candytrains" />
    <meta property="og:description" content="Platform Viewer for <?php echo $stationInfo["station_name"] ?> on CandyTrains, a website of all things norwegian railways!" />
    <meta property="og:url" content="<?php echo $thisLink ?>" />
    <meta property="og:image" content="https://files.candycryst.com/assets/candyTransport/profile.png" />
    <meta name="theme-color" content="#629aa4">
    <link rel="icon" href="https://files.candycryst.com/assets/candyTransport/profile.png" type="image/png" />
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/platform.css">
    <link rel="stylesheet" href="./assets/css/main.css">
</head>

<body class="viewer">
    <?php
    if (count($platforms) == 1) {
        echo '<div class="iframe-container-num-big"><iframe src="./platformNumber.php?platformName=' . $platforms[0] . '" title="Platform ' . $platforms[0] . ' Number"></iframe></div>';
        echo '<div class="iframe-container-big"><iframe src="./platformDisplay.php?station=' . $station . '&platform=' . $platforms[0] . '" title="Platform ' . $platforms[0] . '"></iframe></div>';
    } else {
        for ($i = 0; $i < count($platforms); $i++) {
            if ($i % 2 !== 0) {
                echo '<div class="iframe-container"><iframe src="./platformDisplay.php?station=' . $station . '&platform=' . $platforms[$i] . '&side=right" title="Platform ' . $platforms[$i] . '"></iframe></div>';
                echo '<div class="iframe-container-num"><iframe src="./platformNumber.php?platformName=' . $platforms[$i] . '" title="Platform ' . $platforms[$i] . ' Number"></iframe></div>';
            } else {
                echo '<div class="iframe-container-num"><iframe src="./platformNumber.php?platformName=' . $platforms[$i] . '" title="Platform ' . $platforms[$i] . ' Number"></iframe></div>';
                echo '<div class="iframe-container"><iframe src="./platformDisplay.php?station=' . $station . '&platform=' . $platforms[$i] . '&side=left" title="Platform ' . $platforms[$i] . '"></iframe></div>';
            }
        }
    }
    ?>
</body>

</html>