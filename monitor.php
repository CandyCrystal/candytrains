<?php
include "./config/connect.php";
include "./database/getStationData.php";
$thisLink = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$stationQuery = new getStationData($databaseConnection);
$hideCopyright = $_GET["hideCopyright"];

$displayType = $_GET["type"];

if ($displayType == "") {
    $displayType = "mainDepartures";
}
$station = $_GET["station"];

if ($station == "") {
    $station = "217";
}
$thisStation = $stationQuery->getStationInformation($station)["stationName"];

$platform = $_GET["platform"];

if ($platform == "") {
    $platform = "1";
}

switch ($displayType) {
    case "departures":
        $iframe = './mainDisplay.php?station=' . $station;
        $ratio[0] = 16;
        $ratio[1] = 9;
        $title = "Departures from " . $thisStation;
        $extendedTitle = $title;
        break;
    case "nextTo":
        $iframe = './mainDisplay.php?displayType=nextTo&station=' . $station;
        $ratio[0] = 16;
        $ratio[1] = 9;
        $title = "Departures next to from " . $thisStation;
        $extendedTitle = "The next two departures to up to four other stations from " . $thisStation;
        break;
    case "arrivals":
        $iframe = './mainDisplay.php?displayType=arrivals&station=' . $station;
        $ratio[0] = 16;
        $ratio[1] = 9;
        $title = "Arrivals at " . $thisStation;
        $extendedTitle = $title;
        break;
    case "departuresSplitFlap":
        $iframe = './departures_splitFlap.php?station=' . $station;
        $ratio[0] = 16;
        $ratio[1] = 9;
        $title = "Departures from " . $thisStation;
        $extendedTitle = $title;
        break;
    case "platform":
        $iframe = './platformViewer.php?station=' . $station . '&platforms=' . $platform;
        $ratio[0] = 282;
        $ratio[1] = 125;
        $title = "Departures from " . $thisStation . " Platform " . $platform;
        $extendedTitle = $title;
        break;
    case "platformNumber":
        $iframe = './platformNumber.php?platformName=' . $platform;
        $ratio[0] = 9;
        $ratio[1] = 16;
        $title = $thisStation . " Platform number " . $platform;
        $extendedTitle = $thisStation . " Platform " . $platform . " Platform display";
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
    <link rel="stylesheet" href="./assets/css/monitor.css">
    <title><?php echo $title ?> - CandyTrains</title>
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo $title ?> - Candytrains" />
    <meta property="og:description" content="<?php echo $extendedTitle ?> on CandyTrains, a website of all things norwegian railways!" />
    <meta property="og:url" content="<?php echo $thisLink ?>" />
    <meta property="og:image" content="https://files.candycryst.com/assets/candyTransport/profile.png" />
    <meta name="theme-color" content="#629aa4">
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
    <?php if ($hideCopyright != true) { ?><div class="copyright">Inneholder data under Norsk lisens for offentlige data (NLOD) tilgjengeliggjort av Bane NOR</div><?php } ?>
    <div class="wrap">
        <div class="wrap2">
            <iframe class="viewer" src="<?php echo $iframe ?>">
        </div>
    </div>
</body>

</html>