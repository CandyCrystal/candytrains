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
    <link rel="stylesheet" href="../assets/css/displayTest.css">
    <script src="https://kit.fontawesome.com/185bd08920.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
</head>
<style>
    .webcam.headerCell {
        text-align: center;
        font-weight: 300;
        font-size: 1vw;
    }

    .departuresTable {
        /* border: 5px solid #77293e !important; */
        top: 0.3vw !important;
        left: 0vw !important;
        width: unset;
    }

    .webcam.contentCell {
        font-size: 1.5vw;
    }

    .webcam.contentCell.operator {
        width: 6vw;
    }

    .webcam.contentCell.trainRef {
        text-align: center;
        width: 7.5vw;
    }

    .webcam.contentCell.departure,
    .webcam.contentCell.arrival {
        text-align: center;
        width: 6vw;
    }

    
    .webcam.contentCell.track {
        text-align: center;
        width: 4vw;
    }

    .webcam.contentCell.line {
        text-align: center;
        width: 4vw;
        margin-left: 2vw;
    }

    .webcam.contentCell.origin {
        text-align: right;
        width: 10vw;
    }

    .webcam.contentCell.destination {
        text-align: left;
        width: 10vw;
    }

    .webcam.row {
        border-top: 0.2vw solid #111;
    }

    body {
        width: unset;
    }
</style>

<body class="display">
    <table class="departuresTable" id="departuresTable">
        <tr>
            <th class="webcam headerCell origin">Train from</th>
            <th class="webcam headerCell arrival">Arrival</th>
            <th class="webcam headerCell operator">Operator</th>
            <th class="webcam headerCell trainNumber">Train&nbspnumber</th>
            <th class="webcam headerCell line"></th>
            <th class="webcam headerCell track">Track</th>
            <th class="webcam headerCell departure">Departure</th>
            <th class="webcam headerCell destination">Destination</th>
            <th></th>
            <!-- <th class="column trainType">Train info</th> -->
        </tr>
        <?php for ($i = 0; $i < 6; $i++) {
            echo '<tr class="webcam row" id="departuresRow' . $i . '"><td class="departureTime">&nbsp</td></tr>';
        } ?>
    </table>
</body>

<script>
    var previousTime;
    var station = "<?php echo $station ?>";
    var clockState;
    var trainData = [];
    setTime()
    setInterval(function() {
        setTime()
    }, 1000);
    for (var i = 0; i < 6; i++) {
        var row = document.getElementById("departuresRow" + i)
        var arrivalTime = "<td class='webcam contentCell arrival' id='arrivalTime" + i + "'>&nbsp</td>"
        var origin = "<td class='webcam contentCell origin' id='origin" + i + "'>&nbsp</td>"
        var line = "<td class='webcam contentCell line' id='line" + i + "'></td>"
        var departureTime = "<td class='webcam contentCell departure' id='departureTime" + i + "'>&nbsp</td>"
        var destination = "<td class='webcam contentCell destination' id='destination" + i + "'>&nbsp</td>"
        // var newTime = "<td class='webcam contentCell arrival' id='newTime" + i + "'></td>"
        var platform = "<td class='webcam contentCell track' id='platform" + i + "'></td>"
        var operator = "<td class='webcam contentCell operator' id='operator" + i + "'></td>"
        // var trainType = "<td class='webcam contentCell arrival' id='trainType" + i + "'></td>"
        var trainRef = "<td class='webcam contentCell trainRef' id='trainRef" + i + "'></td>"
        row.innerHTML = origin + arrivalTime + operator + trainRef + line + platform + departureTime + destination
        // row.innerHTML = operator + trainRef + line + origin + arrivalTime + platform + departureTime + destination

    }
    updateInfo();
    setInterval(function() {
        updateInfo()
    }, 8000);
    var trainData = [];

    function updateInfo() {
        $.ajax({
            url: "../queries/mainQuery.php?type=webcam&station=" + station,
            type: "POST",
            success: function(msg) {
                var thisTime = msg;
                result = JSON.parse(msg);
                if (thisTime != previousTime) {
                    for (var i = 0; i < 6; i++) {
                        var row = document.getElementById("departuresRow" + i)
                        console.log(result[i])
                        if (result[i] == undefined) {
                            continue;
                        }
                        var type = result[i]["departureStatus"];
                        var arrivalType = result[i]["arrivalStatus"];
                        var departureTimeFull = new Date(result[i]["expectedDepartureTime"]);
                        var departureTime = ("0" + departureTimeFull.getHours()).slice(-2) + ":" + ("0" + departureTimeFull.getMinutes()).slice(-2)
                        var arrivalTimeFull = new Date(result[i]["expectedArrivalTime"]);
                        var arrivalTime = ("0" + arrivalTimeFull.getHours()).slice(-2) + ":" + ("0" + arrivalTimeFull.getMinutes()).slice(-2)
                        console.log(arrivalTime)
                        if (arrivalTime == "aN:aN") {
                            var arrivalTimeFull = new Date(result[i]["aimedArrivalTime"]);
                            var arrivalTime = ("0" + arrivalTimeFull.getHours()).slice(-2) + ":" + ("0" + arrivalTimeFull.getMinutes()).slice(-2)
                        }
                        $('#line' + i).html("")
                        $('#trainRef' + i).html(result[i]["trainRef"])
                        var line = result[i]["lineRef"];
                        if (line != "") {
                            if (line == "cargo") {
                                line = '<div class="linebox small cargo"><div class="text">cargo</div></div>'
                            } else {
                                line = '<div class="linebox small ' + line + '"><div class="text">' + line.substr(0, 1) + ' ' + line.substr(1) + '</div></div>'
                                // line = '<img id="line" class="departureImage" src="./assets/media/lines/centered/' + line + '.svg">'
                            }
                            $('#line' + i).html(line)
                        }
                        if (result[i]["trainType"] != "" && result[i]["trainType"] != "N/A") {
                            // var trainTypeLinks = result[i]["train"]
                            console.log(result[i]["train"][0])
                        } else {
                            var trainTypeLinks = ""
                        }
                        // $('#trainType' + i).html(trainTypeLinks)
                        // trainData[i] = result[i]["trainData"]
                        operator = '<img id="line" class="departureOperatorImage" src="../assets/media/companies/' + result[i]["operatorRef"] + '.svg">'
                        $('#operator' + i).html(operator)
                        var destination = result[i]["destinationName"];
                        var origin = result[i]["originName"];
                        if (result[i]["departurePlatform"] != "") {
                            $('#platform' + i).html(result[i]["departurePlatform"])
                        } else {
                            $('#platform' + i).html(result[i]["arrivalPlatform"])
                        }
                        if (arrivalTime.includes('aN')) {
                            arrivalTime = "";
                        }
                        if (departureTime.includes('aN')) {
                            departureTime = "";
                        }
                        if (destination == "<?php echo $stationInfo["station_name"] ?>" && arrivalTime == "") {
                            arrivalTime = departureTime
                            departureTime = ""
                        }
                        if (destination == "<?php echo $stationInfo["station_name"] ?>") {
                            destination = ""
                        }
                        if (origin == "<?php echo $stationInfo["station_name"] ?>") {
                            origin = ""
                        }
                        $('#destination' + i).html("&nbsp" + destination)
                        $('#origin' + i).html("&nbsp" + origin)
                        if (type == "cancelled") {
                            departureTime = "<del>" + departureTime + "</del>"
                        }
                        if (arrivalType == "cancelled") {
                            arrivalTime = "<del>" + arrivalTime + "</del>"
                        }
                        $('#departureTime' + i).html(departureTime)
                        $('#arrivalTime' + i).html(arrivalTime)
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