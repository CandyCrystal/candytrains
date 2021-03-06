<?php
$station = $_GET["station"];
$displayType = $_GET["displayType"];
if ($displayType == "") {
    $displayType = "departures";
}
if ($station == "") {
    $station = "OSL";
}
include "../config/connect.php";
include "../database/getStationData.php";
$stationQuery = new getStationData($databaseConnection);
include "../database/getNextToData.php";
$nextToQuery = new getNextToData($databaseConnection);
include "../database/getNextToEntryData.php";
$nextToEntryQuery = new getNextToEntryData($databaseConnection);
$stationInfo = $stationQuery->getStationInformation($station);
$nextTos = $nextToQuery->getNextTosNextTo($station);

?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $stationInfo["station_name"] ?> - Main Display - CandyTransport</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="../assets/css/lineImages.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/norwegianMainDisplay.css">
    <script src="https://kit.fontawesome.com/185bd08920.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
</head>

<body class="display">
    <img class="trainImage" id="departuresTrainImage" src="../assets/media/Train.svg">
    <img class="trainImage" id="cancelledTrainImage" src="../assets/media/Exclamation.svg">
    <div class="departuresTopBar" id="departuresTopBar"></div>
    <div class="departuresHeader" id="departuresHeader"><b>Avganger</b> Departures</div>
    <div class="cancelledTopBar" id="cancelledTopBar"></div>
    <div class="cancelledHeader" id="cancelledHeader"><b>Innstilte tog</b> Cancelled departures</div>
    <div id="currentTime" class="localTime"></div>
    <table class="departuresTable" id="departuresTable">
        <tr class="firstRow">
            <th class="departuresTimeColumn"><b>Avgang</b><br />Departure</th>
            <th class="departuresDestinationColumn" colspan="2"><b>Tog til</b><br />Destination</th>
            <th class="departuresNewTimeColumn" id="depNewTime"><b>Ny tid</b><br />New time</th>
            <th class="departuresPlatformColumn" id="depTrack"><b>Spor</b><br />Track</th>
            <th></th>
            <th class="departuresOperatorColumn"></th>
        </tr>
        <tr id="departuresRow0">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="departuresRow1">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="departuresRow2">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="departuresRow3">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="departuresRow4">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="departuresRow5">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="departuresRow6">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="departuresRow7">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="departuresRow8">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="departuresRow9">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="departuresRow10">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="departuresRow11">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="departuresRow12">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="departuresRow13">
            <td class='departureTime'>&nbsp</td>
        </tr>
    </table>
    <!-- Next to -->
    <div class="nextToTopBar" id="nextToTopBar"></div>
    <div class="nextToHeader" id="nextToHeader"><b>Neste tog til</b> Next train to</div>
    <?php if ($nextTos[0] != "") {
        echo '<div class="nextToDestinationOne" id="nextToDestinationOne">
            <div class="nextToDestinationText" id="nextToDestination0Text"><b>' . $nextTos[0][1] . '</b> ' . $nextTos[0][2] . '</div>
            <div class="nextToDestinationDeparture" id="nextToDestinationInfo0"></div>
            <div class="nextToDestinationDeparture" id="nextToDestinationInfo1"></div>
        </div>';
    } else {
        echo '<div class="nextToDestinationOne">
        <div class="nextToDestinationText"><b>Ikke konfigurert</b> Not configured</div>
    </div>';
    }
    if ($nextTos[1] != "") {
        echo '<div class="nextToDestinationTwo" id="nextToDestinationTwo">
            <div class="nextToDestinationText" id="nextToDestination1Text"><b>' . $nextTos[1][1] . '</b> ' . $nextTos[1][2] . '</div>
            <div class="nextToDestinationDeparture" id="nextToDestinationInfo2"></div>
            <div class="nextToDestinationDeparture" id="nextToDestinationInfo3"></div>
        </div>';
    }
    if ($nextTos[2] != "") {
        echo '<div class="nextToDestinationThree" id="nextToDestinationThree">
            <div class="nextToDestinationText" id="nextToDestination2Text"><b>' . $nextTos[2][1] . '</b> ' . $nextTos[2][2] . '</div>
            <div class="nextToDestinationDeparture" id="nextToDestinationInfo4"></div>
            <div class="nextToDestinationDeparture" id="nextToDestinationInfo5"></div>
        </div>';
    }
    if ($nextTos[3] != "") {
        echo '<div class="nextToDestinationFour" id="nextToDestinationFour">
            <div class="nextToDestinationText" id="nextToDestination3Text"><b>' . $nextTos[3][1] . '</b> ' . $nextTos[3][2] . '</b></div>
            <div class="nextToDestinationDeparture" id="nextToDestinationInfo6"></div>
            <div class="nextToDestinationDeparture" id="nextToDestinationInfo7"></div>
        </div>';
    } ?>

    <!-- Arrivals -->
    <div class="arrivalsTopBar" id="arrivalsTopBar"></div>
    <div class="arrivalsHeader" id="arrivalsHeader"><b>Ankomst</b> Arrivals</div>
    <table class="arrivalsTable" id="arrivalsTable">
        <tr class="firstRow">
            <th class="arrivalsTimeColumn">Ankomst<br />Arrival</th>
            <th class="arrivalsOriginColumn" colspan="2">Tog fra<br />Train from</th>
            <th class="arrivalsNewTimeColumn">Ny tid<br />New time</th>
            <th class="arrivalsPlatformColumn">Spor<br />Track</th>
            <th class="arrivalsOperatorColumn"></th>
        </tr>
        <tr id="arrivalsRow0">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="arrivalsRow1">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="arrivalsRow2">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="arrivalsRow3">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="arrivalsRow4">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="arrivalsRow5">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="arrivalsRow6">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="arrivalsRow7">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="arrivalsRow8">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="arrivalsRow9">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="arrivalsRow10">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="arrivalsRow11">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="arrivalsRow12">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <tr id="arrivalsRow13">
            <td class='departureTime'>&nbsp</td>
        </tr>
        <?php if ($displayType == "arrivals") { ?>
            <div id="chart1"></div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js" charset="utf-8"></script>
            <script src="../assets/js/d3clock.js"></script>
            <script>
                d3clock({
                    target: '#chart1',
                    face: 'sbb',
                    width: 150,
                    TZOffset: {
                        hours: 0
                    }
                });
            </script>
        <?php } ?>
    </table>

</body>

<script>
    var isFirst = [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1];
    var previousTime;
    var station = "<?php echo $station ?>";
    var displayType = "<?php echo $displayType ?>";
    var norwegianText = ["", "", "", "", "", "", "", "", "", "", "", "", "", ""]
    var englishText = ["", "", "", "", "", "", "", "", "", "", "", "", "", ""];
    var languageState;
    setText();
    setInterval(function() {
        setText()
    }, 4000);

    function setText() {
        if (languageState == 1) {
            languageState = 0
            for (i = 0; i < 14; i++)
                if (norwegianText[i] != "") {
                    $('#via' + i).html('<div class="warningYellow">' + norwegianText[i] + '</div>')
                }
            console.log(i + " " + languageState)
        } else {
            languageState = 1
            for (i = 0; i < 14; i++)
                if (englishText[i] != "") {
                    $('#via' + i).html('<div class="warningYellow">' + englishText[i] + '</div>')
                }
            console.log(i + " " + languageState)
        }

    }
    switch (displayType) {
        case "departures":
            var clockState;
            setTime()
            setInterval(function() {
                setTime()
            }, 1000);
            $('#departuresTopBar').attr("style", "display: block;")
            $('#departuresHeader').attr("style", "display: block;")
            $('#currentTime').attr("style", "display: block;")
            $('#departuresTable').attr("style", "display: table;")
            $('#departuresTrainImage').attr("style", "display: block;")
            for (var i = 0; i < 14; i++) {
                var row = document.getElementById("departuresRow" + i)
                var time = "<td class='departureTime' id='time" + i + "'>&nbsp</td>"
                var line = "<td class='departureLine' id='line" + i + "'></td>"
                var destination = "<td class='departureDestination' id='destination" + i + "'>&nbsp</td>"
                var newTime = "<td class='departureNewTime' id='newTime" + i + "'></td>"
                var platform = "<td class='departurePlatform' id='platform" + i + "'></td>"
                var via = "<td class='departureVia' id='via" + i + "'></td>"
                var operator = "<td id='operator" + i + "'></td>"
                row.innerHTML = time + line + destination + newTime + platform + via + operator
            }
            updateInfoDepartures();
            setInterval(function() {
                updateInfoDepartures()
            }, 8000);
            break;
        case "noncancelled":
            var clockState;
            setTime()
            setInterval(function() {
                setTime()
            }, 1000);
            $('#departuresTopBar').attr("style", "display: block;")
            $('#departuresHeader').attr("style", "display: block;")
            $('#currentTime').attr("style", "display: block;")
            $('#departuresTable').attr("style", "display: table;")
            $('#departuresTrainImage').attr("style", "display: block;")
            for (var i = 0; i < 14; i++) {
                var row = document.getElementById("departuresRow" + i)
                var time = "<td class='departureTime' id='time" + i + "'>&nbsp</td>"
                var line = "<td class='departureLine' id='line" + i + "'></td>"
                var destination = "<td class='departureDestination' id='destination" + i + "'>&nbsp</td>"
                var newTime = "<td class='departureNewTime' id='newTime" + i + "'></td>"
                var platform = "<td class='departurePlatform' id='platform" + i + "'></td>"
                var via = "<td class='departureVia' id='via" + i + "'></td>"
                var operator = "<td id='operator" + i + "'></td>"
                row.innerHTML = time + line + destination + newTime + platform + via + operator
            }
            updateInfoNonCancelled();
            setInterval(function() {
                updateInfoNonCancelled()
            }, 8000);
            break;
        case "cancelled":
            var clockState;
            setTime()
            setInterval(function() {
                setTime()
            }, 1000);
            $('#depTrack').attr("style", "color: transparent;")
            $('#depNewTime').html("<b>Forventet</b><br />Expected")
            $('#cancelledTopBar').attr("style", "display: block;")
            $('#cancelledHeader').attr("style", "display: block;")
            $('#currentTime').attr("style", "display: block;")
            $('#departuresTable').attr("style", "display: table;")
            $('#cancelledTrainImage').attr("style", "display: block;")
            for (var i = 0; i < 14; i++) {
                var row = document.getElementById("departuresRow" + i)
                var time = "<td class='departureTime' id='time" + i + "'>&nbsp</td>"
                var line = "<td class='departureLine' id='line" + i + "'></td>"
                var destination = "<td class='departureDestination' id='destination" + i + "'>&nbsp</td>"
                var newTime = "<td class='departureNewTime' id='newTime" + i + "'></td>"
                var platform = "<td class='departurePlatform' id='platform" + i + "'></td>"
                var via = "<td class='departureVia' id='via" + i + "'></td>"
                var operator = "<td id='operator" + i + "'></td>"
                row.innerHTML = time + line + destination + newTime + platform + via + operator
            }
            updateInfoCancelled();
            setInterval(function() {
                updateInfoCancelled()
            }, 8000);
            break;
        case "nextTo":
            $('#nextToTopBar').attr("style", "display: block;")
            $('#nextToHeader').attr("style", "display: block;")
            $('#nextToDestinationOne').attr("style", "display: block;")
            $('#nextToDestinationTwo').attr("style", "display: block;")
            $('#nextToDestinationThree').attr("style", "display: block;")
            $('#nextToDestinationFour').attr("style", "display: block;")
            updateInfoNextTo()
            setInterval(function() {
                updateInfoNextTo()
            }, 8000);
            break;
        case "arrivals":
            var clockState;
            setTime()
            setInterval(function() {
                setTime()
            }, 1000);
            updateInfoArrivals();
            $('#arrivalsTopBar').attr("style", "display: block;")
            $('#arrivalsHeader').attr("style", "display: block;")
            $('#currentTime').attr("style", "display: block; right: 10vw;")
            $('#arrivalsTable').attr("style", "display: table;")
            setInterval(function() {
                updateInfoArrivals()
            }, 8000);
            break;
    }



    function updateInfoDepartures() {
        $.ajax({
            url: "../queries/mainQuery.php?type=departures&station=" + station,
            type: "POST",
            success: function(msg) {
                var thisTime = msg;
                result = JSON.parse(msg);
                if (thisTime != previousTime) {
                    for (var i = 0; i < 14; i++) {
                        if (result[i] == undefined) {
                            continue;
                        }
                        var row = document.getElementById("departuresRow" + i)
                        var type = result[i]["departureStatus"];
                        var timeFull = new Date(result[i]["expectedDepartureTime"]);
                        var time = ("0" + timeFull.getHours()).slice(-2) + ":" + ("0" + timeFull.getMinutes()).slice(-2)
                        var line = result[i]["lineRef"];
                        if (line != "") {
                            if (line == "cargo") {
                                line = '<div class="linebox small cargo"><div class="text">cargo</div></div>'
                            } else {
                                line = '<div class="linebox small ' + line + '"><div class="text">' + line.substr(0, 1) + ' ' + line.substr(1) + '</div></div>'
                            }
                            $('#line' + i).html(line)
                        }
                        operator = '<img id="line" class="departureOperatorImage" src="../assets/media/companies/' + result[i]["operatorRef"] + '.svg">'
                        $('#operator' + i).html(operator)
                        var destination = result[i]["destinationName"];
                        $('#destination' + i).html("&nbsp" + destination)
                        var newTimeFull = new Date(result[i]["departureTimeNew"]);
                        time_difference = diff_minutes(timeFull, newTimeFull);
                        if (time_difference > 1) {
                            var newTime = ("0" + newTimeFull.getHours()).slice(-2) + ":" + ("0" + newTimeFull.getMinutes()).slice(-2)
                        } else {
                            var newTime = ""
                        }
                        var platform = result[i]["departurePlatform"];
                        $('#platform' + i).html(platform)
                        var via = ""
                        for (var key in result[i]["vias"]) {
                            via = via + result[i]["vias"][key] + " ??? "
                        }
                        if (via != "") {
                            via = "via " + via.slice(0, -3);
                        }
                        if (type == "cancelled") {
                            time = "<del>" + time + "</del>"
                            var newTime = "Cancelled"
                            via = "";
                        }
                        $('#time' + i).html(time)
                        $('#newTime' + i).html(newTime)
                        norwegianText[i] = ""
                        englishText[i] = ""
                        norwegianText[i] = result[i]["advice"]["norwegian"]
                        englishText[i] = result[i]["advice"]["english"]
                        if (norwegianText[i] != "") {
                            if (languageState == 1 || englishText[i] == "") {
                                $('#via' + i).html('<div class="warningYellow">' + norwegianText[i] + '</div>')
                            } else {
                                $('#via' + i).html('<div class="warningYellow">' + englishText[i] + '</div>')
                            }
                        } else {
                            $('#via' + i).html(via)
                        }
                    }
                }
                previousTime = thisTime;
            }
        })
    }

    function updateInfoNonCancelled() {
        $.ajax({
            url: "../queries/mainQuery.php?type=departures_non_cancelled&station=" + station,
            type: "POST",
            success: function(msg) {
                var thisTime = msg;
                result = JSON.parse(msg);
                if (thisTime != previousTime) {
                    for (var i = 0; i < 14; i++) {
                        if (result[i] == undefined) {
                            continue;
                        }
                        var row = document.getElementById("departuresRow" + i)
                        var type = result[i]["departureStatus"];
                        var timeFull = new Date(result[i]["expectedDepartureTime"]);
                        var time = ("0" + timeFull.getHours()).slice(-2) + ":" + ("0" + timeFull.getMinutes()).slice(-2)
                        var line = result[i]["lineRef"];
                        if (line != "") {
                            if (line == "cargo") {
                                line = '<div class="linebox small cargo"><div class="text">cargo</div></div>'
                            } else {
                                line = '<div class="linebox small ' + line + '"><div class="text">' + line.substr(0, 1) + ' ' + line.substr(1) + '</div></div>'
                            }
                            $('#line' + i).html(line)
                        }
                        operator = '<img id="line" class="departureOperatorImage" src="../assets/media/companies/' + result[i]["operatorRef"] + '.svg">'
                        $('#operator' + i).html(operator)
                        var destination = result[i]["destinationName"];
                        $('#destination' + i).html("&nbsp" + destination)
                        var newTimeFull = new Date(result[i]["departureTimeNew"]);
                        time_difference = diff_minutes(timeFull, newTimeFull);
                        if (time_difference > 1) {
                            var newTime = ("0" + newTimeFull.getHours()).slice(-2) + ":" + ("0" + newTimeFull.getMinutes()).slice(-2)
                        } else {
                            var newTime = ""
                        }
                        var platform = result[i]["departurePlatform"];
                        $('#platform' + i).html(platform)
                        var via = ""
                        for (var key in result[i]["vias"]) {
                            via = via + result[i]["vias"][key] + " ??? "
                        }
                        if (via != "") {
                            via = "via " + via.slice(0, -3);
                        }
                        if (type == "cancelled") {
                            time = "<del>" + time + "</del>"
                            var newTime = "Cancelled"
                            via = "";
                        }
                        $('#time' + i).html(time)
                        $('#newTime' + i).html(newTime)
                        norwegianText[i] = ""
                        englishText[i] = ""
                        norwegianText[i] = result[i]["advice"]["norwegian"]
                        englishText[i] = result[i]["advice"]["english"]
                        if (norwegianText[i] != "") {
                            if (languageState == 1 || englishText[i] == "") {
                                $('#via' + i).html('<div class="warningYellow">' + norwegianText[i] + '</div>')
                            } else {
                                $('#via' + i).html('<div class="warningYellow">' + englishText[i] + '</div>')
                            }
                        } else {
                            $('#via' + i).html(via)
                        }
                    }
                }
                previousTime = thisTime;
            }
        })
    }

    function updateInfoCancelled() {
        $.ajax({
            url: "../queries/mainQuery.php?type=departures_cancelled&station=" + station,
            type: "POST",
            success: function(msg) {
                var thisTime = msg;
                result = JSON.parse(msg);
                if (thisTime != previousTime) {
                    for (var i = 0; i < 14; i++) {
                        if (result[i] == undefined) {
                            continue;
                        }
                        var row = document.getElementById("departuresRow" + i)
                        var timeFull = new Date(result[i]["aimedDepartureTime"]);
                        $('#time' + i).html("<del>" + ("0" + timeFull.getHours()).slice(-2) + ":" + ("0" + timeFull.getMinutes()).slice(-2) + "</del>")
                        var line = result[i]["lineRef"];
                        if (line != "") {
                            if (line == "cargo") {
                                line = '<div class="linebox small cargo"><div class="text">cargo</div></div>'
                            } else {
                                line = '<div class="linebox small ' + line + '"><div class="text">' + line.substr(0, 1) + ' ' + line.substr(1) + '</div></div>'
                            }
                            $('#line' + i).html(line)
                        }
                        $('#operator' + i).html('<img id="line" class="departureOperatorImage" src="../assets/media/companies/' + result[i]["operatorRef"] + '.svg">')
                        $('#destination' + i).html("&nbsp" + result[i]["destinationName"])
                        $('#platform' + i).html("<i class=\"fas fa-exclamation-circle warningYellow\"></i>")
                        $('#newTime' + i).html("Cancelled")
                        norwegianText[i] = ""
                        englishText[i] = ""
                        norwegianText[i] = result[i]["norwegianText"]
                        englishText[i] = result[i]["englishText"]
                        if (norwegianText[i] != "") {
                            if (languageState == 1 || englishText[i] == "") {
                                $('#via' + i).html('<div class="warningYellow">' + norwegianText[i] + '</div>')
                            } else {
                                $('#via' + i).html('<div class="warningYellow">' + englishText[i] + '</div>')
                            }
                        }
                    }
                }
                previousTime = thisTime;
            }
        })
    }

    function updateInfoNextTo() {
        $.ajax({
            url: "../queries/mainQuery.php?type=departures_next_to&station=" + station,
            type: "POST",
            success: function(msg) {
                var thisTime = msg
                result = JSON.parse(msg);
                if (thisTime != previousTime) {
                    var dt = new Date();
                    console.log("different")
                    for (var i = 0; i < 8; i++) {
                        if (result[i] == undefined) {
                            continue;
                        }
                        var time, destination
                        var departure = document.getElementById("nextToDestinationInfo" + i)
                        var timeFull = new Date(result[i]["aimedDepartureTime"]);
                        time_difference = diff_minutes(timeFull, dt);
                        if (time_difference < 10) {
                            time = time_difference + " min."
                        } else {
                            var time = ("0" + timeFull.getHours()).slice(-2) + ":" + ("0" + timeFull.getMinutes()).slice(-2)
                        }
                        if (time_difference <= 0) {
                            time = "N??.";
                        }
                        var time = '<div class="time">' + time + '</div>'
                        var line = result[i]["lineRef"];
                        if (result[i]["lineRef"] == "") {
                            line = "";
                            destination = '<div class="destination">' + result[i]["destinationName"] + '</div>'
                        } else {
                            if (line == "cargo") {
                                line = '<div class="linebox small cargo"><div class="text">cargo</div></div>'
                            } else {
                                line = '<div class="linebox small ' + line + '"><div class="text">' + line.substr(0, 1) + ' ' + line.substr(1) + '</div></div>'
                            }
                            destination = '<div class="destination">&nbsp' + result[i]["destinationName"] + '</div>'
                        }
                        var platform = '<div class="track">Spor ' + result[i]["departurePlatform"] + '</div>'
                        departure.innerHTML = time + line + destination + platform
                    }
                }
                previousTime = thisTime;
            }
        })
    }

    function updateInfoArrivals() {
        $.ajax({
            url: "../queries/mainQuery.php?type=arrivals&station=" + station,
            type: "POST",
            success: function(msg) {
                var thisTime = msg;
                result = JSON.parse(msg);
                console.log(result)
                if (thisTime != previousTime) {
                    for (var i = 0; i < 14; i++) {
                        if (result[i] == undefined) {
                            continue;
                        }
                        var row = document.getElementById("arrivalsRow" + i)
                        var type = result[i]["arrivalStatus"]
                        var timeFull = new Date(result[i]["aimedArrivalTime"]);
                        var time = ("0" + timeFull.getHours()).slice(-2) + ":" + ("0" + timeFull.getMinutes()).slice(-2)
                        var line = result[i]["lineRef"];
                        if (line != "") {
                            if (line == "cargo") {
                                line = '<div class="linebox small cargo"><div class="text">cargo</div></div>'
                            } else {
                                line = '<div class="linebox small ' + line + '"><div class="text">' + line.substr(0, 1) + ' ' + line.substr(1) + '</div></div>'
                            }
                        }
                        var operator = '<img id="line" class="departureOperatorImage" src="../assets/media/companies/' + result[i]["operatorRef"] + '.svg">'
                        var origin = result[i]["originName"];
                        var newTimeFull = new Date(result[i]["arrivalTimeNew"]);
                        time_difference = diff_minutes(timeFull, newTimeFull);
                        if (time_difference > 1) {
                            var newTime = ("0" + newTimeFull.getHours()).slice(-2) + ":" + ("0" + newTimeFull.getMinutes()).slice(-2)
                        } else {
                            var newTime = "";
                        }

                        var platform = result[i]["arrivalPlatform"];
                        if (type == "cancelled") {
                            time = "<del>" + time + "</del>"
                            newTime = "Cancelled";
                        }
                        var time = "<td class='departureTime'>" + time + "&nbsp</td>"
                        var line = "<td class='departureLine'>" + line + "</td>"
                        var origin = "<td class='departureDestination'>&nbsp" + origin + "</td>"
                        var newTime = "<td class='departureNewTime'>" + newTime + "</td>"
                        var platform = "<td class='departurePlatform'>" + platform + "</td>"
                        var operator = "<td>" + operator + "</td>"
                        row.innerHTML = time + line + origin + newTime + platform + operator
                    }
                }
                previousTime = thisTime;
            }
        })
    }

    function setTime() {
        var dt = new Date();
        var minute = ("0" + dt.getMinutes()).slice(-2);
        var hour = ("0" + dt.getHours()).slice(-2)
        if (clockState == 1) {
            $('#currentTime').html("<div style='float:left;'>" + hour + "</div><div style='color:transparent;float:left;'>:</div><div style='float:left;'>" + minute + "</div>");
            clockState = 0
        } else {
            $('#currentTime').html("<div style='float:left;'>" + hour + "</div><div style='float:left;'>:</div><div style='float:left;'>" + minute + "</div>")
            clockState = 1
        }
    }

    function diff_minutes(dt2, dt1) {

        var diff = (dt2.getTime() - dt1.getTime()) / 1000;
        diff /= 60;
        return Math.abs(Math.round(diff));

    }
</script>

</html>