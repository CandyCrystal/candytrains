<?php
$thisLink = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
include "./config/session.php";
include "./config/connect.php";
include "./database/getPlatformData.php";
include "./database/getStationData.php";
$station = $_GET["station"];
$layout = explode("_", $_GET["layout"]);
$links = [
    "bane_nor" => 'https://rtd.kv.banenor.no/index.html#/?hideNotice=#noNotice&page=#page&id=' . $station . '%2F#type',
    "ct" => 'https://trains.candycryst.com/monitor.php?type=#type&station=' . $station . '&hideCopyright=#noNotice&page=#page',
];
$linksToUse = [];
$replaceFrom = array("#noNotice", "#page", "#type");
$stationQuery = new getStationData($databaseConnection);
$stationInfo = $stationQuery->getStationInformation($station);
$query = new getPlatformData($databaseConnection);
$platformsResult = $query->getPlatforms($station);
$num_rows = mysqli_num_rows($platformsResult);

foreach ($layout as $key => $value) {
    $type = substr($value, 0, 3);
    $number = substr($value, 3);
    if ($number == "") {
        $number = 1;
    }
    switch ($type) {
        case "arr":
            $linksToUse[$key] = str_replace($replaceFrom, array("true", $number - 1, "Arrival"), $links["bane_nor"]);
            break;
        case "arb":
            $linksToUse[$key] = str_replace($replaceFrom, array("true", $number - 1, "arrivals"), $links["ct"]);
            break;
        case "dpb":
            $linksToUse[$key] = str_replace($replaceFrom, array("true", $number - 1, "departures"), $links["ct"]);
            break;
        case "dnc":
            $linksToUse[$key] = str_replace($replaceFrom, array("true", $number - 1, "departuresNoCancel"), $links["ct"]);
            break;
        case "dpc":
            $linksToUse[$key] = str_replace($replaceFrom, array("true", $number - 1, "cancelled"), $links["ct"]);
            break;
        case "ntb":
            $linksToUse[$key] = str_replace($replaceFrom, array("true", $number - 1, "nextTo"), $links["ct"]);
            break;
        case "dep":
            $linksToUse[$key] = str_replace($replaceFrom, array("true", $number - 1, "Departure"), $links["bane_nor"]);
            break;
        case "plt":
            $linksToUse[$key] = str_replace($replaceFrom, array("true", "1", $number), $links["bane_nor"]);
            break;
    }
}
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
    <link rel="stylesheet" href="./assets/css/platformViewer.css">
    <link rel="stylesheet" href="./assets/css/main.css">
</head>

<body class="quadViewer">
    <?php
    echo '<div class="iframe-container-tl"><iframe src="' . $linksToUse[0] . '"></iframe></div>';
    echo '<div class="iframe-container-tr"><iframe src="' . $linksToUse[1] . '"></iframe></div>';
    echo '<div class="iframe-container-bl"><iframe src="' . $linksToUse[2] . '"></iframe></div>';
    echo '<div class="iframe-container-br"><iframe src="' . $linksToUse[3] . '"></iframe></div>';
    ?>
</body>

</html>