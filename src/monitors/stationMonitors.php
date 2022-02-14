<?php
include "../config/connect.php";
include "../database/getStationData.php";

$stationDataQuery = new getStationData($databaseConnection);

$thisLink = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$hideCopyright = $_GET["hideCopyright"];

$displayType = $_GET["type"];
if ($displayType == "") {
    $displayType = "departures";
}
$station = $_GET["station"];

if ($station == "") {
    $station = "OSL";
}
$stationInfo = $stationDataQuery->getStationInformation($station);
$thisStation = $stationInfo["station_name"];

$platform = $_GET["platform"];

if ($platform == "") {
    $platform = "1";
}

$color = "#629aa4";

switch ($displayType) {
    case "departures":
        $iframe = './mainDisplay.php?station=' . $station;
        $ratio[0] = 16;
        $ratio[1] = 9;
        $title = "Departures from " . $thisStation;
        $extendedTitle = $title;
        $color = "194a9f";
        break;
    case "departuresNoCancel":
        $iframe = './mainDisplay.php?displayType=noncancelled&station=' . $station;
        $ratio[0] = 16;
        $ratio[1] = 9;
        $title = "Departures from " . $thisStation;
        $extendedTitle = $title;
        $color = "194a9f";
        break;
    case "cancelled":
        $iframe = './mainDisplay.php?displayType=cancelled&station=' . $station;
        $ratio[0] = 16;
        $ratio[1] = 9;
        $title = "Cancelled departures from " . $thisStation;
        $extendedTitle = $title;
        $color = "ffd300";
        break;
    case "nextTo":
        $iframe = './mainDisplay.php?displayType=nextTo&station=' . $station;
        $ratio[0] = 16;
        $ratio[1] = 9;
        $title = "Departures next to from " . $thisStation;
        $extendedTitle = "The next two departures to up to four other stations from " . $thisStation;
        $color = "3c3c3c";
        break;
    case "arrivals":
        $iframe = './mainDisplay.php?displayType=arrivals&station=' . $station;
        $ratio[0] = 16;
        $ratio[1] = 9;
        $title = "Arrivals at " . $thisStation;
        $extendedTitle = $title;
        $color = "138d5a";
        break;
    case "arrivals_departures":
        $iframe = './arrivalDepartureMonitor.php?station=' . $station;
        $ratio[0] = 16;
        $ratio[1] = 9;
        $title = "Departures from " . $thisStation;
        $extendedTitle = $title;
        $color = "6d41a1";
        break;
    case "webcam":
        $iframe = './webcamMonitor.php?station=' . $station;
        $ratio[0] = 16;
        $ratio[1] = 9;
        $title = "Departures from " . $thisStation;
        $extendedTitle = $title;
        $color = "6d41a1";
        break;
    case "everything":
        $iframe = './everythingMonitor.php?station=' . $station;
        $ratio[0] = 16;
        $ratio[1] = 9;
        $title = "Departures from " . $thisStation;
        $extendedTitle = $title;
        $color = "6d41a1";
        break;
    case "departuresSplitFlap":
        $iframe = './departures_splitFlap.php?station=' . $station;
        $ratio[0] = 16;
        $ratio[1] = 9;
        $title = "Departures from " . $thisStation;
        $extendedTitle = $title;
        $color = "194a9f";
        break;
    case "platform":
        $iframe = './platformViewer.php?station=' . $station . '&platforms=' . $platform;
        $ratio[0] = 282;
        $ratio[1] = 125;
        $title = "Departures from " . $thisStation . " Platform " . $platform;
        $extendedTitle = $title;
        $color = "194a9f";
        break;
    case "platformNumber":
        $iframe = './platformNumber.php?platformName=' . $platform;
        $ratio[0] = 9;
        $ratio[1] = 16;
        $title = $thisStation . " Platform number " . $platform;
        $extendedTitle = $thisStation . " Platform " . $platform . " Platform display";
        $color = "6d41a1";
        break;
}
$paddingTop = $ratio[1] / $ratio[0] * 100;
$aspectRatio = $ratio[0] . "/" . $ratio[1];
$viewerSize = "height: 100vmin; width: " . $ratio[0] / $ratio[1] * 100 . "vmin; ";
?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400&display=swap" rel="stylesheet">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../assets/css/monitor.css">
    <title><?php echo $title ?> - CandyTrains</title>
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo $title ?> - Candytrains" />
    <meta property="og:description" content="<?php echo $extendedTitle ?> on CandyTrains, a website of all things norwegian railways!" />
    <meta property="og:url" content="<?php echo $thisLink ?>" />
    <meta property="og:image" content="https://files.candycryst.com/assets/candyTransport/profile.png" />
    <meta name="theme-color" content="<?php echo $color ?>">
    <link rel="icon" href="https://files.candycryst.com/assets/candyTransport/profile.png" type="image/png" />


    <style>
        .wrap2 {
            padding-top: <?php echo $paddingTop . "%" ?>;
        }

        @media screen and (min-aspect-ratio: <?php echo $aspectRatio ?>) {
            .viewer {
                /* border: none; */
                position: relative;
                display: block;
                margin: 0 auto;
                <?php echo $viewerSize ?>
            }

            .wrap2 {
                height: unset;
                padding-top: unset !important;
                position: unset;
                width: 100vw;
            }
        }
    </style>
</head>

<body>
    <?php
    $extra = "";
    if ($displayType == "everything") {
        $extra = " og data fra srd.tf";
    }
    if ($hideCopyright != true && $displayType != "flirt") { ?><div class="copyright">Inneholder data under Norsk lisens for offentlige data (NLOD) tilgjengeliggjort av Bane NOR<?php echo $extra ?></div><?php } ?>
    <div class="wrap">
        <div class="wrap2">
            <iframe class="viewer" src="<?php echo $iframe ?>">
        </div>
    </div>
</body>

</html>