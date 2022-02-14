<?php
$thisLink = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
include "./config/session.php";
include "./config/connect.php";
include "./queries/platformsQuery.php";
include "./database/getPlatformData.php";
include "./database/getStationData.php";
$station = $_GET["station"];
$stationQuery = new getStationData($databaseConnection);
$stationInfo = $stationQuery->getStationInformation($station);
$query = new getPlatformData($databaseConnection);
$platformsResult = $query->getPlatforms($station);
$num_rows = mysqli_num_rows($platformsResult);
$platformsQuery = new platformDepartures("OSL");
if ($_GET["platforms"] != "") {
    $platforms = explode("-", $_GET["platforms"]);
} else {
    $i = 0;
    while ($platform = mysqli_fetch_array($platformsResult)) {
        $platforms[$i] = $platform["platformNumber"];
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
    <title><?php echo $stationInfo["stationName"] ?> Platforms - CandyTrains</title>
    <meta property="og:type" content="website">
    <meta property="og:title" content="Platform Viewer for <?php echo $stationInfo["stationName"] ?> - Candytrains" />
    <meta property="og:description" content="Platform Viewer for <?php echo $stationInfo["stationName"] ?> on CandyTrains, a website of all things norwegian railways!" />
    <meta property="og:url" content="<?php echo $thisLink ?>" />
    <meta property="og:image" content="https://files.candycryst.com/assets/candyTransport/profile.png" />
    <meta name="theme-color" content="#629aa4">
    <link rel="icon" href="https://files.candycryst.com/assets/candyTransport/profile.png" type="image/png" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/platformViewer.css">
    <!-- <link rel="stylesheet" href="./assets/css/main.css"> -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
</head>

<body class="viewer">
    <?php
    if (count($platforms) == 1) {
    ?>
        <table class="platformDisplayTable">
            <tr>
                <td class="platformNumber large"><?php echo $platformsQuery->getPlatformNumber($platforms[0]); ?></td>
                <td></td>
            </tr>
        </table>
    <?php
        // echo $platformsQuery->getPlatformNumber(1, "large");
        // echo '<div class="iframe-container-big"><iframe src="./platformDisplay.php?station=' . $station . '&platform=' . $platforms[0] . '" title="Platform ' . $platforms[0] . '"></iframe></div>';
    } else { ?>
        <table class="platformDisplayTable">
            <?php
            for ($i = 0; $i < count($platforms); $i++) {
                if ($i % 2 == 0) {
            ?>
                    <tr>
                        <td class="platformNumber"><?php echo $platformsQuery->getPlatformNumber($platforms[$i]); ?></td>
                        <td class="platformDisplay"><?php echo $platformsQuery->getPlatformDisplay($platforms[$i],"left",1); ?></td>
                    <?php } else { ?>
                        <td class="platformDisplay"><?php echo $platformsQuery->getPlatformDisplay($platforms[$i],"right",1); ?></td>
                        <td class="platformNumber"><?php echo $platformsQuery->getPlatformNumber($platforms[$i]); ?></td>
                    </tr>
            <?php }
            } ?>
        </table>

    <?php
        // if ($i % 2 !== 0) {
        // echo '<div class="iframe-container"><iframe src="./platformDisplay.php?station=' . $station . '&platform=' . $platforms[$i] . '&side=right" title="Platform ' . $platforms[$i] . '"></iframe></div>';
        //     echo $platformsQuery->getPlatformNumber(1, null);
        // } else {
        //     echo $platformsQuery->getPlatformNumber(1, null);
        // echo '<div class="iframe-container"><iframe src="./platformDisplay.php?station=' . $station . '&platform=' . $platforms[$i] . '&side=left" title="Platform ' . $platforms[$i] . '"></iframe></div>';
        // }
    }


    ?>
</body>

</html>

<script>
    var platforms = <?php echo json_encode($platforms) ?>;
    var previousTime;
    var clockState = 0;
    setTime()
    setInterval(function() {
        setTime()
    }, 1000);
    updateInfo()
    setInterval(function() {
        updateInfo()
    }, 8000);

    function setTime() {
        var dt = new Date();
        var minute = ("0" + dt.getMinutes()).slice(-2);
        var hour = ("0" + dt.getHours()).slice(-2)
        var x = document.getElementsByClassName("currentTime");
        var i;
        for (i = 0; i < x.length; i++) {
            if (clockState == 1) {
                x[i].innerHTML = '<i class="far fa-clock"></i>&nbsp' + hour + ":" + minute
            } else {
                x[i].innerHTML = '<i class="far fa-clock"></i>&nbsp' + hour + "&nbsp" + minute
            }
        }
        if (clockState == 1) {
            clockState = 0
        } else {
            clockState = 1
        }
    }
    for (i = 0; i < 20; i++) {
        // clearing
        clearDisplay(platforms[i])
    }

    function updateInfo() {
        $.ajax({
            url: "https://trains.candycryst.com/queries/platformDeparturesQuery.php?stationRef=OSL",
            type: "POST",
            success: function(msg) {
                result = JSON.parse(msg);
                // console.log(msg);
                var thisTime = msg;
                var eq = JSON.stringify(thisTime) === JSON.stringify(previousTime);
                if (eq == false) {
                    previousTime = thisTime
                    for (var item in result) {
                        if (result.departures[item] == undefined) {
                            continue;
                        }
                        var thisPlatform = result.departures[item]
                        var platformNumber = thisPlatform.platform;
                        clearDisplay(platformNumber)
                        if (platforms.includes(platformNumber)) {
                            console.log("yes")

                            if (thisPlatform.status == "normal") {
                                prepareRegularDeparture(platformNumber);
                                $('#departure_' + platformNumber + '_operator').attr('src', "./assets/media/companies/" + thisPlatform.operator + ".svg")
                                $('#departure_' + platformNumber + '_destination').html(thisPlatform.destination)
                                $('#departure_' + platformNumber + '_remark').html(thisPlatform.viaText)
                                $('#departure_' + platformNumber + '_time').html(thisPlatform.time)
                                if (thisPlatform.line != "") {
                                    $('#departure_' + platformNumber + '_line').attr('src', "./assets/media/lines/centered/" + thisPlatform.line + ".svg")
                                }
                                if (thisPlatform.time != thisPlatform.newTime) {
                                    enableRegularDepartureNewTime(platformNumber)
                                    $('#departure_' + platformNumber + '_newTime').html(thisPlatform.newTime)
                                }
                                if (thisPlatform.secondDeparture.length != 0) {
                                    prepareSecondDeparture(platformNumber);
                                    $('#departure_' + platformNumber + 'B_time').html(thisPlatform.secondDeparture.time)
                                    $('#departure_' + platformNumber + 'B_destination').html(thisPlatform.secondDeparture.destination)
                                    if (thisPlatform.secondDeparture.line != "") {
                                        $('#departure_' + platformNumber + 'B_line').attr('src', "./assets/media/lines/centered/" + thisPlatform.secondDeparture.line + ".svg")
                                    }
                                    if (thisPlatform.secondDeparture.time != thisPlatform.secondDeparture.newTime) {
                                        enableSecondDepartureNewTime(platformNumber)
                                        $('#departure_' + platformNumber + 'B_newTime').html(thisPlatform.secondDeparture.newTime)
                                    }
                                }
                            } else if (thisPlatform.status == "noBoarding") {
                                prepareArrival(platformNumber);
                                $('#departure_' + platformNumber + '_noBoardingOrigin').html(thisPlatform.origin)
                                $('#departure_' + platformNumber + '_noBoardingTime').html(thisPlatform.arrivalTime)
                                $('#departure_' + platformNumber + '_noBoardingLineImage').attr('src', "./assets/media/lines/centered/" + "F1" + ".svg")
                                if (thisPlatform.arrivalTime != thisPlatform.arrivalNewTime) {
                                    enableArrivalNewTime(platformNumber)
                                    $('#departure_' + platformNumber + '_noBoardingNewTime').html(thisPlatform.arrivalNewTime)
                                }

                            }
                        }
                        console.log(result.departures[item].platform);
                        console.log(result.departures[item]);
                    }
                }
            }
        })
    }


    for (i = 0; i < 0; i++) {
        $('#trainDisplayTrain').attr("style", "display: block;")
    }
    for (i = 0; i < 0; i++) {
        // cancelled departure
        clearDisplay(platforms[i])
        prepareCancelledDeparture(platforms[i]);

        $('#departure_' + platforms[i] + '_time').html("<del id='time'>15:00</del>")

        $('#departure_' + platforms[i] + '_line').attr('src', "./assets/media/lines/centered/" + "R30" + ".svg")
        $('#departure_' + platforms[i] + '_operator').attr('src', "./assets/media/companies/SJ.svg")
        $('#departure_' + platforms[i] + '_destination').html("Gjøvik")


        $('#trainDisplayTrain').attr("style", "display: block;")
    }
    for (i = 0; i < 0; i++) {
        // arrival
        clearDisplay(platforms[i]);
        prepareArrival(platforms[i]);
    }
    for (i = 0; i < 0; i++) {
        // second departure
        prepareSecondDeparture(platforms[i]);
    }

    function enableRegularDepartureNewTime(num) {
        $('#departure_' + num + '_newTime').attr("style", "display: block;")
        $('#departure_' + num + '_newTimeText').attr("style", "display: block;")
    }

    function enableSecondDepartureNewTime(num) {
        $('#departure_' + num + 'B_newTime').attr("style", "display: block;")
        $('#departure_' + num + 'B_newTimeText').attr("style", "display: block;")
    }

    function enableArrivalNewTime(num) {
        $('#departure_' + num + 'B_noBoardingNewTime').attr("style", "display: block;")
        $('#departure_' + num + 'B_noBoardingNewTimeText').attr("style", "display: block;")
    }

    function prepareRegularDeparture(num) {
        $('#departure_' + num + '_time').attr("style", "display: block;")

        $('#departure_' + num + '_lineCompany').attr("style", "display: block;")
        $('#departure_' + num + '_destination').attr("style", "display: block;")
        $('#departure_' + num + '_remark').attr("style", "display: block;")
    }

    function prepareSecondDeparture(num) {
        $('#departure_' + num + '_sectorLetters').attr("style", "display: block;")
        $('#departure_' + num + 'B_time').attr("style", "display: block;")
        $('#departure_' + num + 'B_newTime').attr("style", "display: block;")
        $('#departure_' + num + 'B_destination').attr("style", "display: block;")
        $('#departure_' + num + 'B_line').attr("style", "display: block;")
    }

    function prepareCancelledDeparture(num) {
        $('#departure_' + num + '_time').attr("style", "display: block;")

        $('#departure_' + num + '_lineCompany').attr("style", "display: block;")
        $('#departure_' + num + '_destination').attr("style", "display: block;")
        $('#departure_' + num + '_cancelledText').attr("style", "display: block;")
    }

    function prepareArrival(num) {
        $('#departure_' + num + '_noBoardingOrigin').attr("style", "display: block;")
        $('#departure_' + num + '_noBoardingTime').attr("style", "display: block;")
        $('#departure_' + num + '_noBoardingText').attr("style", "display: block;")
        $('#departure_' + num + '_noBoardingArrival').attr("style", "display: block;")
        $('#departure_' + num + '_noBoardingLine').attr("style", "display: block;")
    }

    function clearDisplay(num) {
        $('#departure_' + num + '_time').attr("style", "display: none;")
        $('#departure_' + num + '_newTime').attr("style", "display: none;")
        $('#departure_' + num + '_newTimeText').attr("style", "display: none;")

        $('#departure_' + num + '_lineCompany').attr("style", "display: none;")
        $('#departure_' + num + '_destination').attr("style", "display: none;")
        $('#departure_' + num + '_remark').attr("style", "display: none;")

        $('#departure_' + num + '_noBoardingOrigin').attr("style", "display: none;")
        $('#departure_' + num + '_noBoardingTime').attr("style", "display: none;")
        $('#departure_' + num + '_noBoardingNewTime').attr("style", "display: none;")
        $('#departure_' + num + '_noBoardingNewTimeText').attr("style", "display: none;")
        $('#departure_' + num + '_noBoardingText').attr("style", "display: none;")
        $('#departure_' + num + '_noBoardingArrival').attr("style", "display: none;")
        $('#departure_' + num + '_noBoardingLine').attr("style", "display: none;")

        $('#departure_' + num + 'B_time').attr("style", "display: none;")
        $('#departure_' + num + 'B_newTime').attr("style", "display: none;")
        $('#departure_' + num + 'B_newTimeText').attr("style", "display: none;")
        $('#departure_' + num + 'B_destination').attr("style", "display: none;")
        $('#departure_' + num + 'B_line').attr("style", "display: none;")

        $('#departure_' + num + '_cancelledText').attr("style", "display: none;")

        $('#departure_' + num + '_time').html("")
        $('#departure_' + num + '_newTime').html("")

        $('#departure_' + num + '_line').removeAttr('src')
        $('#departure_' + num + '_operator').removeAttr('src')
        $('#departure_' + num + '_destination').html("")
        $('#departure_' + num + '_remark').html("")

        $('#departure_' + num + '_noBoardingOrigin').html("")
        $('#departure_' + num + '_noBoardingTime').html("")
        $('#departure_' + num + '_noBoardingNewTime').html("")
        $('#departure_' + num + '_noBoardingLineImage').removeAttr('src')

        $('#departure_' + num + 'B_line').removeAttr('src')
        $('#departure_' + num + 'B_time').html("")
        $('#departure_' + num + 'B_newTime').html("")
        $('#departure_' + num + 'B_destination').html("")
    }
</script>