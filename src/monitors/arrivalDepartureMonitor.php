<?php
$station = $_GET["station"];
$useCustomLines = $_GET["customLines"];

if($useCustomLines != "false") {
    $useCustomLines == "true";
}

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

<body class="display">
    <div class="topBG"></div>
    <img class="trainImage" id="departuresTrainImage" src="../assets/media/Train.svg">
    <div class="departuresTopBar" id="departuresTopBar"></div>
    <div class="departuresHeader" id="departuresHeader"><b>Ankomster - Avganger</b></div>
    <div id="currentTime" class="localTime"></div>
    <table class="departuresTable" id="departuresTable">
        <tr class="firstRow">
            <th class="column"><b>Tognummer</b><br />Train number</th>
            <th class="column origin"><b>Tog fra</b><br />Train from</th>
            <th class="column arrivalTime"><b>Ankomst</b><br />Arrival</th>
            <th class="column operator"></th>
            <th class="column line"></th>
            <th class="column platform" id="depTrack"><b>Spor</b><br />Track</th>
            <th class="column departureTime"><b>Avgang</b><br />Departure</th>
            <th class="column destination"><b>Tog til</b><br />Destination</th>
            <th class="column trainType"><b>Toginfo</b><br />Train info</th>
        </tr>
        <?php for ($i = 0; $i < 35; $i++) {
            echo '<tr id="departuresRow' . $i . '"><td class="departureTime">&nbsp</td></tr>';
        } ?>
    </table>
    <div class="trainPreviewBar">
        <div id="previewTrain" class="trainPreviewBarTrain"></div>
    </div>
    <!-- <div class="trainPreviewBarCover"></div> -->
</body>

<script>
    function previewTrainExterior(type) {
        // type = type.substr(0, type.length - 1)
        // type = type.split("_")
        // var tmp = [];
        var tmp2 = [];
        var tmp = type;
        // tmp = tmp.reverse();
        if (tmp[0].includes("BM92") || tmp[0].includes("BCM93") || tmp[0].includes("BS69H")) {
            [tmp[1], tmp[0]] = [tmp[0], tmp[1]]
        } else if (tmp[0].includes("BS 69CII") || tmp[0].includes("BS 69D")) {
            [tmp[2], tmp[0]] = [tmp[0], tmp[2]]
        } else if (tmp[0].includes("BMA74") || tmp[0].includes("BMA75") || tmp[0].includes("BMA75-2")) {
            [tmp[0], tmp[1], tmp[3], tmp[4]] = [tmp[4], tmp[3], tmp[1], tmp[0]]
            console.log("flirt")
        } else if (tmp[0].includes("BMB72") || tmp[0].includes("BFM73B") || tmp[0].includes("BFM73A") || tmp[0].includes("BFM73")) {
            [tmp[0], tmp[1], tmp[2], tmp[3]] = [tmp[3], tmp[2], tmp[1], tmp[0]]
        }
        if (tmp[2] != undefined) {
            if (tmp[2].includes("BM92") || tmp[2].includes("BCM93") || tmp[2].includes("BS69H")) {
                [tmp[2], tmp[3]] = [tmp[3], tmp[2]]
            }
        }
        if (tmp[5] != undefined) {
            if (tmp[3].includes("BS 69CII") || tmp[3].includes("BS 69D")) {
                [tmp[3], tmp[5]] = [tmp[5], tmp[3]]
            } else if (tmp[5].includes("BMA74") || tmp[5].includes("BMA75") || tmp[5].includes("BMA75-2")) {
                [tmp[5], tmp[6], tmp[8], tmp[9]] = [tmp[9], tmp[8], tmp[6], tmp[5]]
            } else if (tmp[4].includes("BMB72") || tmp[4].includes("BFM73B") || tmp[4].includes("BFM73A") || tmp[4].includes("BFM73")) {
                [tmp[7], tmp[6], tmp[5], tmp[4]] = [tmp[4], tmp[5], tmp[6], tmp[7]]
            }
        }
        for (i = 0; i < tmp.length; i++) {
            tmp2[i] = "<img class='previewTrainImg' src='../assets/media/trains/exteriors/" + tmp[i].replace(" ", "_") + ".svg'>"
        }
        tmp2 = tmp2.join("")
        if (tmp2 != "") {
            document.getElementById("previewTrain").innerHTML = '<div id="previewTrain">' + tmp2 + '</div>'
        }
    }

    function previewTrainInterior(type) {
        type = type.substr(0, type.length - 1)
        type = type.split("_")
        var tmp = [];
        if (type[0].includes("BM92") || type[0].includes("BCM93") || type[0].includes("BS69H")) {
            [type[1], type[0]] = [type[0], type[1]]
        } else if (type[0].includes("BS 69CII") || type[0].includes("BS 69D")) {
            [type[2], type[0]] = [type[0], type[2]]
        } else if (type[0].includes("BMA74") || type[0].includes("BMA75") || type[0].includes("BMA75-2")) {
            [type[0], type[1], type[3], type[4]] = [type[4], type[3], type[1], type[0]]
            console.log("flirt")
        } else if (type[0].includes("BMB72") || type[0].includes("BFM73B") || type[0].includes("BFM73A") || type[0].includes("BFM73")) {
            [type[0], type[1], type[2], type[3]] = [type[3], type[2], type[1], type[0]]
        }
        if (type[2].includes("BM92") || type[2].includes("BCM93") || type[2].includes("BS69H")) {
            [type[2], type[3]] = [type[3], type[2]]
        } else if (type[3].includes("BS 69CII") || type[3].includes("BS 69D")) {
            [type[3], type[5]] = [type[5], type[3]]
        } else if (type[5].includes("BMA74") || type[5].includes("BMA75") || type[5].includes("BMA75-2")) {
            [type[5], type[6], type[8], type[9]] = [type[9], type[8], type[6], type[5]]
        } else if (type[4].includes("BMB72") || type[4].includes("BFM73B") || type[4].includes("BFM73A") || type[4].includes("BFM73")) {
            [type[7], type[6], type[5], type[4]] = [type[4], type[5], type[6], type[7]]
        }
        for (i = 0; i < type.length; i++) {

            type[i] = type[i].replace("-VY", "")
            type[i] = type[i].replace("-GAG", "")
            type[i] = type[i].replace("-NSB", "")
            type[i] = type[i].replace("-SJ", "")
            type[i] = "<img class='previewTrainImgInt' src='../assets/media/trains/interiors/" + type[i].replace(" ", "_") + ".svg'>"
        }
        type = type.join("")
        if (type != "") {
            document.getElementById("previewTrain").innerHTML = '<div id="previewTrain">' + type + '</div>'
        }
    }
    var previousTime;
    var customLines = "<?php echo $useCustomLines ?>";
    var station = "<?php echo $station ?>";
    var clockState;
    var trainData = [];
    setTime()
    setInterval(function() {
        setTime()
    }, 1000);
    for (var i = 0; i < 35; i++) {
        var row = document.getElementById("departuresRow" + i)
        var arrivalTime = "<td class='cell arrivalTime' id='arrivalTime" + i + "'>&nbsp</td>"
        var origin = "<td class='cell origin' id='origin" + i + "'>&nbsp</td>"
        var line = "<td class='cell line' id='line" + i + "'></td>"
        var departureTime = "<td class='cell departureTime' id='departureTime" + i + "'>&nbsp</td>"
        var destination = "<td class='cell destination' id='destination" + i + "'>&nbsp</td>"
        var newTime = "<td class='cell departureNewTime' id='newTime" + i + "'></td>"
        var platform = "<td class='cell platform' id='platform" + i + "'></td>"
        var operator = "<td class='cell operator' id='operator" + i + "'></td>"
        var trainType = "<td id='trainType" + i + "'class='cell trainType'></td>"
        var trainRef = "<td id='trainRef" + i + "'class='cell origin'></td>"
        row.innerHTML = trainRef + origin + arrivalTime + operator + line + platform + departureTime + destination + trainType

    }
    updateInfo();
    setInterval(function() {
        updateInfo()
    }, 8000);
    var trainData = [];

    function updateInfo() {
        $.ajax({
            url: "../queries/mainQuery.php?type=arrivals_departures&station=" + station,
            type: "POST",
            success: function(msg) {
                var thisTime = msg;
                result = JSON.parse(msg);
                if (thisTime != previousTime) {
                    for (var i = 0; i < 35; i++) {
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
                        if( customLines == "true") {
                        var line = result[i]["lineRef"];
                        } else {
                            var line = result[i]["customLineRef"];
                        }
                        if (line != "") {
                            if (line == "cargo") {
                                line = '<div class="linebox small cargo"><div class="text">cargo</div></div>'
                            } else {
                                if (line.startsWith("L") || line.startsWith("R") && line.startsWith("RC") == false || line.startsWith("FLY")) {
                                    line = '<div class="linebox small ' + line + '"><div class="text">' + line.substr(0, 1) + ' ' + line.substr(1) + '</div></div>'
                                } else {
                                    line = '<div class="linebox small ' + line + '"><div class="text">' + line + '</div></div>'
                                }
                                // line = '<img id="line" class="departureImage" src="./assets/media/lines/centered/' + line + '.svg">'
                            }
                            $('#line' + i).html(line)
                        }
                        if (result[i]["trainType"] != "" && result[i]["trainType"] != "N/A") {
                            var trainTypeLinks = result[i]["trainType"] + "<a onclick='previewTrainExterior(trainData[" + i + "])'" + i + "'> Ext </a><a onclick='previewTrainInterior(trainData[" + i + "])'>Int</a>"
                        } else {
                            var trainTypeLinks = ""
                        }
                        $('#trainType' + i).html(trainTypeLinks)
                        trainData[i] = result[i]["trainData"]
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