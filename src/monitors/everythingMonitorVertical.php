<?php
$station = $_GET["station"];
$displayType = $_GET["displayType"];
if ($displayType == "") {
    $displayType = "departures";
}
if ($station == "") {
    $station = "OSL";
}
$beforeSize = $_GET["beforeSize"];
$afterSize = $_GET["afterSize"];
$numDepartures = $_GET["numberOfDepartures"];
include "./config/connect.php";
include "./database/getStationData.php";
$stationDataQuery = new getStationData($databaseConnection);
$stationInfo = $stationDataQuery->getStationInformation($station);
include "./database/getNextToData.php";
$nextToQuery = new getNextToData($databaseConnection);
include "./database/getNextToEntryData.php";
$nextToEntryQuery = new getNextToEntryData($databaseConnection);
$nextTos = $nextToQuery->getNextTosNextTo($station);

?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $stationInfo["station_name"] ?> - Main Display - CandyTransport</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/displayTest.css">
    <link rel="stylesheet" href="./assets/css/lineImages.css">
    <script src="https://kit.fontawesome.com/185bd08920.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
</head>

<body class="display">
    <div class="topBG"></div>
    <a target='_parent' href="https://trains.candycryst.com/station.php?stationRef=<?php echo $station; ?>"><img class="trainImage" id="departuresTrainImage" src="./assets/media/Train.svg"></a>
    <div class="everythingTopBar" id="departuresTopBar"></div>
    <div class="departuresHeader" id="departuresHeader"><b>Ankomster - Avganger</b></div>
    <div id="currentTime" class="localTime"></div>
    <table class="departuresTable" id="departuresTable">
        <?php
        echo "<tr class='firstRow'>";
        echo "<th></th>";
        for ($i = 0; $i < $numDepartures; $i++) {
            echo '<th colspan="3" class="column previousStation" id="trainRef_' . $i . '"></td>';
        }
        echo "</tr>";
        echo "<tr>";
        echo '<th class="column previousStation"><b>Toginformasjon</b><br />Train info</td>';
        for ($i = 0; $i < $numDepartures; $i++) {
            echo '<td class="cell everythingTest" id="operator_' . $i . '"></td>';
            echo '<td colspan="2" class="cell everythingTest" id="line_' . $i . '"></td>';
        }
        echo "</tr>";
        echo "<tr class='borderRowTop'>";

        echo '<th class="column previousStation" rowspan="2"><b>Tog fra</b><br />Train from</td>';

        for ($i = 0; $i < $numDepartures; $i++) {
            echo '<td class="cell time right"></td>';
            echo '<td class="cell dot" id="origin_Status_' . $i . '"></td>';
            echo '<td class="cell time left" id="origin_DepartureTime_' . $i . '"></td>';
        }
        echo "</tr>";
        echo "<tr class='borderRow'>";
        for ($i = 0; $i < $numDepartures; $i++) {
            echo '<td colspan="3" class="cell journeyStationNames" id="origin_' . $i . '"></td>';
        }
        echo "</tr>";
        for ($i = $beforeSize; $i > 0; $i--) {
            echo "<tr><td></td>";
            for ($j = 0; $j < $numDepartures; $j++) {
                echo '<td class="cell time right" id="previousStation' . $i . '_ArrivalTime_' . $j . '"></td>';
                echo '<td class="cell dot" id="previousStation' . $i . '_Status_' . $j . '"></td>';
                echo '<td class="cell time left" id="previousStation' . $i . '_DepartureTime_' . $j . '"></td>';
            }
            echo "</tr>";
            echo "<tr><td></td>";
            for ($j = 0; $j < $numDepartures; $j++) {
                echo '<td colspan="3" class="cell journeyStationNames" id="previousStation' . $i . '_' . $j . '"></td>';
            }
            echo "</tr>";
        }
        echo "<tr class='borderRowTop'>";
        echo '<th class="column previousStation" rowspan="2"><b>Denne&nbspstasjonen</b><br />This station</td>';
        for ($i = 0; $i < $numDepartures; $i++) {
            echo '<td class="cell time right" id="thisStation_ArrivalTime_' . $i . '"></td>';
            echo '<td class="cell dot" id="thisStation_Status_' . $i . '"></td>';
            echo '<td class="cell time left" id="thisStation_DepartureTime_' . $i . '"></td>';
        }
        echo "</tr>";
        echo "<tr class='borderRow'>";
        for ($i = 0; $i < $numDepartures; $i++) {
            echo '<td colspan="3" class="cell journeyStationNames" id="thisStation_' . $i . '"></td>';
        }
        echo "</tr>";
        for ($i = 0; $i < $afterSize; $i++) {
            echo "<tr><td></td>";
            for ($j = 0; $j < $numDepartures; $j++) {
                echo '<td class="cell time right" id="nextStation' . $i . '_ArrivalTime_' . $j . '"></td>';
                echo '<td class="cell dot" id="nextStation' . $i . '_Status_' . $j . '"></td>';
                echo '<td class="cell time left" id="nextStation' . $i . '_DepartureTime_' . $j . '"></td>';
            }
            echo "</tr>";
            echo "<tr><td></td>";
            for ($j = 0; $j < $numDepartures; $j++) {
                echo '<td colspan="3" class="cell journeyStationNames" id="nextStation' . $i . '_' . $j . '"></td>';
            }
            echo "</tr>";
        }
        echo "<tr class='borderRowTop'>";
        echo '<th class="column previousStation" rowspan="2"><b>Tog til</b><br />Train to</td>';

        for ($i = 0; $i < $numDepartures; $i++) {
            echo '<td class="cell time right" id="destinationArrivalTime_' . $i . '"></td>';
            echo '<td class="cell dot" id="destinationStatus_' . $i . '"></td>';
            echo '<td class="cell time left"></td>';
        }
        echo "</tr>";
        echo "<tr class='borderRow'>";
        for ($i = 0; $i < $numDepartures; $i++) {
            echo '<td colspan="3" class="cell journeyStationNames" id="destination_' . $i . '"></td>';
        }
        echo "</tr>";
        ?>

    </table>
</body>

<script>
    var beforeSize = <?php echo $beforeSize ?>;
    var afterSize = <?php echo $afterSize ?>;
    var numDepartures = <?php echo $numDepartures ?>;

    var previousTime;
    var station = "<?php echo $station ?>";
    var clockState;
    var trainData = [];
    setTime()
    setInterval(function() {
        setTime()
    }, 1000);
    updateInfo();
    setInterval(function() {
        updateInfo()
    }, 12000);

    function createTimestamp(input) {
        var full = new Date(input);
        return ("0" + full.getHours()).slice(-2) + ":" + ("0" + full.getMinutes()).slice(-2)
    }

    function updateInfo() {
        $.ajax({
            url: "./queries/mainQuery.php?type=everything&station=" + station,
            type: "POST",
            success: function(msg) {
                var thisTime = msg;
                result = JSON.parse(msg);
                if (thisTime != previousTime) {
                    for (var i = 0; i < numDepartures; i++) {
                        $('#origin_' + i).html("")
                        $('#origin_Status_' + i).html("")
                        $('#origin_DepartureTime_' + i).html("")

                        $('#thisStation_ArrivalTime_' + i).html("")
                        $('#thisStation_Status_' + i).html("")
                        $('#thisStation_DepartureTime_' + i).html("")

                        $('#destination_' + i).html("")
                        $('#destinationArrivalTime_' + i).html("")
                        $('#destinationStatus_' + i).html("")

                        for (var j = beforeSize; j > 0; j--) {
                            $('#previousStation' + j + '_' + i).html("")
                            $('#previousStation' + j + '_ArrivalTime_' + i).html("")
                            $('#previousStation' + j + '_Status_' + i).html("")
                            $('#previousStation' + j + '_DepartureTime_' + i).html("")
                        }
                        for (var j = afterSize; j > 0; j--) {
                            $('#nextStation' + j + '_' + i).html("")
                            $('#nextStation' + j + '_ArrivalTime_' + i).html("")
                            $('#nextStation' + j + '_Status_' + i).html("")
                            $('#nextStation' + j + '_DepartureTime_' + i).html("")
                        }


                        // console.log(result[i])
                        // if (result[i] == undefined) {
                        //     continue;
                        // }
                        if (result[i]["stopsAfter"][0] != undefined) {
                            $('#thisStation_' + i).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + result[i]["stopsAfter"][0]["code"] + "'>" + result[i]["stopsAfter"][0]["name"].replace(" ", "&nbsp") + "</a>&nbsp[" + result[i]["stopsAfter"][0]["track"] + "]")
                            if (createTimestamp(result[i]["stopsAfter"][0]["aimedA"]).includes('aN')) {
                                $('#thisStation_ArrivalTime_' + i).html("");
                            } else {
                                $('#thisStation_ArrivalTime_' + i).html(createTimestamp(result[i]["stopsAfter"][0]["aimedA"]))
                            }
                            if (createTimestamp(result[i]["stopsAfter"][0]["aimedD"]).includes('aN')) {
                                $('#thisStation_DepartureTime_' + i).html("");
                            } else {
                                $('#thisStation_DepartureTime_' + i).html(createTimestamp(result[i]["stopsAfter"][0]["aimedD"]))
                            }
                            $('#thisStation_Status_' + i).html(result[i]["stopsAfter"][0]["status"])
                        }
                        var type = result[i]["departureStatus"];
                        var arrivalType = result[i]["arrivalStatus"];
                        var departureTimeFull = new Date(result[i]["aimedDepartureTime"]);
                        var departureTime = ("0" + departureTimeFull.getHours()).slice(-2) + ":" + ("0" + departureTimeFull.getMinutes()).slice(-2)
                        var arrivalTimeFull = new Date(result[i]["aimedArrivalTime"]);
                        var arrivalTime = ("0" + arrivalTimeFull.getHours()).slice(-2) + ":" + ("0" + arrivalTimeFull.getMinutes()).slice(-2)
                        $('#trainRef_' + i).html("<b>Tog " + result[i]["trainRef"] + "</b><br/>Train " + result[i]["trainRef"])
                        if (result[i]["stopsBefore"].length > 1) {
                            $('#origin_' + i).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + result[i]["stopsBefore"][0]["code"] + "'>" + result[i]["stopsBefore"][0]["name"].replace(" ", "&nbsp") + "</a>&nbsp[" + result[i]["stopsBefore"][0]["track"] + "]")
                            $('#origin_Status_' + i).html(result[i]["stopsBefore"][0]["status"])
                            $('#origin_DepartureTime_' + i).html(createTimestamp(result[i]["stopsBefore"][0]["aimedD"]))
                            delete result[i]["stopsBefore"][0];
                        } else if (result[i]["stopsBefore"].length == 1) {
                            delete result[i]["stopsBefore"][0];
                        }
                        result[i]["stopsBefore"] = result[i]["stopsBefore"].reverse()
                        for (var j = 0; j < beforeSize; j++) {
                            if (result[i]["stopsBefore"][j] != undefined) {
                                $('#previousStation' + j + '_ArrivalTime_' + i).html(createTimestamp(result[i]["stopsBefore"][j]["aimedA"]));
                                $('#previousStation' + j + '_Status_' + i).html(result[i]["stopsBefore"][j]["status"])
                                $('#previousStation' + j + '_DepartureTime_' + i).html(createTimestamp(result[i]["stopsBefore"][j]["aimedD"]))
                                $('#previousStation' + j + '_' + i).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + result[i]["stopsBefore"][j]["code"] + "'>" + result[i]["stopsBefore"][j]["name"].replace(" ", "&nbsp") + "</a>&nbsp[" + result[i]["stopsBefore"][j]["track"] + "]")
                            } else {
                                $('#previousStation' + j + '_ArrivalTime_' + i).html("");
                                $('#previousStation' + j + '_Status_' + i).html("")
                                $('#previousStation' + j + '_DepartureTime_' + i).html("")
                            }
                        }
                        if (result[i]["stopsAfter"].length > 1) {
                            $('#destination_' + i).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + result[i]["stopsAfter"][result[i]["stopsAfter"].length - 1]["code"] + "'>" + result[i]["stopsAfter"][result[i]["stopsAfter"].length - 1]["name"].replace(" ", "&nbsp") + "</a>&nbsp[" + result[i]["stopsAfter"][result[i]["stopsAfter"].length - 1]["track"] + "]")
                            $('#destinationArrivalTime_' + i).html(createTimestamp(result[i]["stopsAfter"][result[i]["stopsAfter"].length - 1]["aimedA"]))
                            $('#destinationStatus_' + i).html(result[i]["stopsAfter"][result[i]["stopsAfter"].length - 1]["status"])
                            delete result[i]["stopsAfter"][result[i]["stopsAfter"].length - 1];
                        } else if (result[i]["stopsAfter"].length == 1) {
                            delete result[i]["stopsAfter"][0];
                        }
                        for (var j = 1; j <= afterSize; j++) {
                            if (result[i]["stopsAfter"][j] != undefined) {
                                $('#nextStation' + j + '_ArrivalTime_' + i).html(createTimestamp(result[i]["stopsAfter"][j]["aimedA"]));
                                $('#nextStation' + j + '_Status_' + i).html(result[i]["stopsAfter"][j]["status"])
                                $('#nextStation' + j + '_DepartureTime_' + i).html(createTimestamp(result[i]["stopsAfter"][j]["aimedD"]))
                                $('#nextStation' + j + '_' + i).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + result[i]["stopsAfter"][j]["code"] + "'>" + result[i]["stopsAfter"][j]["name"].replace(" ", "&nbsp") + "</a>&nbsp[" + result[i]["stopsAfter"][j]["track"] + "]")
                            } else {
                                $('#nextStation' + j + '_ArrivalTime_' + i).html("");
                                $('#nextStation' + j + '_Status_' + i).html("")
                                $('#nextStation' + j + '_DepartureTime_' + i).html("")
                            }
                        }
                        var journeyOrigin = result[i]["stopsBefore"][0];
                        var journeyPreviousStation2 = result[i]["stopsBefore"][result[i]["stopsBefore"].length - 3];
                        var journeyPreviousStation1 = result[i]["stopsBefore"][result[i]["stopsBefore"].length - 2];
                        var journeyThisStation = result[i]["stopsAfter"][0];
                        var journeyNextStation1 = result[i]["stopsAfter"][1];
                        var journeyNextStation2 = result[i]["stopsAfter"][2];
                        var journeyDestination = result[i]["stopsAfter"][result[i]["stopsAfter"].length - 1];
                        var currentIsEnd = 0;
                        // if (journeyOrigin != undefined) {
                        //     var journeyOriginD = createTimestamp(journeyOrigin["aimedD"]);
                        //     if (result[i]["stopsBefore"].length == 1) {
                        //         currentIsEnd = 1;
                        //         $('#thisStation_' + i).html(journeyOrigin["name"].replace(" ", "&nbsp") + "&nbsp[" + journeyOrigin["track"] + "]")
                        //         $('#thisStation_Status_' + i).html(journeyOrigin["status"])
                        //         $('#thisStation_DepartureTime_' + i).html(journeyOriginD)
                        //     }
                        //     if (result[i]["stopsBefore"].length > 1) {
                        //         $('#origin_' + i).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + journeyOrigin["code"] + "'>" + journeyOrigin["name"].replace(" ", "&nbsp") + "</a>&nbsp[" + journeyOrigin["track"] + "]")
                        //         $('#origin_Status_' + i).html(journeyOrigin["status"])
                        //         $('#origin_DepartureTime_' + i).html(journeyOriginD)
                        //     }

                        //     if (result[i]["stopsBefore"].length > 2) {
                        //         var journeyPreviousStation1A = createTimestamp(journeyPreviousStation1["aimedA"]);
                        //         var journeyPreviousStation1D = createTimestamp(journeyPreviousStation1["aimedD"]);
                        //         $('#previousStation1_' + i).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + journeyPreviousStation1["code"] + "'>" + journeyPreviousStation1["name"].replace(" ", "&nbsp") + "</a>&nbsp[" + journeyPreviousStation1["track"] + "]")
                        //         $('#previousStation1_ArrivalTime_' + i).html(journeyPreviousStation1A)
                        //         $('#previousStation1_Status_' + i).html(journeyPreviousStation1["status"])
                        //         $('#previousStation1_DepartureTime_' + i).html(journeyPreviousStation1D)
                        //     }
                        //     if (result[i]["stopsBefore"].length > 3) {
                        //         var journeyPreviousStation2A = createTimestamp(journeyPreviousStation2["aimedA"]);
                        //         var journeyPreviousStation2D = createTimestamp(journeyPreviousStation2["aimedD"]);
                        //         $('#previousStation2_' + i).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + journeyPreviousStation2["code"] + "'>" + journeyPreviousStation2["name"].replace(" ", "&nbsp") + "</a>&nbsp[" + journeyPreviousStation2["track"] + "]")
                        //         $('#previousStation2_ArrivalTime_' + i).html(journeyPreviousStation2A)
                        //         $('#previousStation2_Status_' + i).html(journeyPreviousStation2["status"])
                        //         $('#previousStation2_DepartureTime_' + i).html(journeyPreviousStation2D)
                        //     }
                        // } else {
                        //     if (result[i]["originName"] == <?php echo "'" . $stationInfo["station_name"] . "'"; ?>) {
                        //         $(`#thisStation_${i}`).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + result[i]["originRef"] + "'>" + result[i]["originName"].replace(" ", "&nbsp") + "</a>&nbsp[" + result[i]["departurePlatform"] + "]")
                        //         $('#thisStation_ArrivalTime_' + i).html("")
                        //         $('#thisStation_Status_' + i).html("🔵")
                        //         $('#thisStation_DepartureTime_' + i).html(departureTime)

                        //     } else {
                        //         $('#origin_' + i).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + result[i]["originRef"] + "'>" + result[i]["originName"].replace(" ", "&nbsp") + "</a>&nbsp[?]")
                        //         // $('#origin_ArrivalTime_' + i).html("")
                        //         $('#origin_Status_' + i).html("🔵")
                        //         $('#origin_DepartureTime_' + i).html("xx:xx")
                        //         $('#previousStation2_' + i).html("")
                        //         $('#previousStation1_' + i).html("")
                        //         $('#thisStation_' + i).html("")
                        //     }
                        // }
                        // if (journeyDestination != undefined) {
                        //     var journeyDestinationA = createTimestamp(journeyDestination["aimedA"]);
                        //     if (result[i]["stopsAfter"].length == 1) {
                        //         currentIsEnd = 1;
                        //         $('#thisStation_' + i).html(journeyDestination["name"].replace(" ", "&nbsp") + "&nbsp[" + journeyDestination["track"] + "]")
                        //         $('#thisStation_ArrivalTime_' + i).html(journeyDestinationA)
                        //         $('#thisStation_Status_' + i).html(journeyDestination["status"])
                        //     }
                        //     if (result[i]["stopsAfter"].length > 1) {
                        //         $('#destination_' + i).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + journeyDestination["code"] + "'>" + journeyDestination["name"].replace(" ", "&nbsp") + "</a>&nbsp[" + journeyDestination["track"] + "]")
                        //         $('#destination_ArrivalTime_' + i).html(journeyDestinationA)
                        //         $('#destination_Status_' + i).html(journeyDestination["status"])
                        //     }
                        //     if (result[i]["stopsAfter"].length > 2) {
                        //         var journeyNextStation1A = createTimestamp(journeyNextStation1["aimedA"]);
                        //         var journeyNextStation1D = createTimestamp(journeyNextStation1["aimedD"]);
                        //         $('#nextStation1_' + i).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + journeyNextStation1["code"] + "'>" + journeyNextStation1["name"].replace(" ", "&nbsp") + "</a>&nbsp[" + journeyNextStation1["track"] + "]")
                        //         $('#nextStation1_ArrivalTime_' + i).html(journeyNextStation1A)
                        //         $('#nextStation1_Status_' + i).html(journeyNextStation1["status"])
                        //         $('#nextStation1_DepartureTime_' + i).html(journeyNextStation1D)
                        //     }
                        //     if (result[i]["stopsAfter"].length > 3) {
                        //         var journeyNextStation2A = createTimestamp(journeyNextStation2["aimedA"]);
                        //         var journeyNextStation2D = createTimestamp(journeyNextStation2["aimedD"]);
                        //         $('#nextStation2_' + i).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + journeyNextStation2["code"] + "'>" + journeyNextStation2["name"].replace(" ", "&nbsp") + "</a>&nbsp[" + journeyNextStation2["track"] + "]")
                        //         $('#nextStation2_ArrivalTime_' + i).html(journeyNextStation2A)
                        //         $('#nextStation2_Status_' + i).html(journeyNextStation2["status"])
                        //         $('#nextStation2_DepartureTime_' + i).html(journeyNextStation2D)
                        //     }
                        // } else {
                        //     if (result[i]["destinationName"] == <?php echo "'" . $stationInfo["station_name"] . "'"; ?>) {
                        //         thisArrivalTime = arrivalTime
                        //         if (arrivalTime.includes('aN')) {
                        //             thisArrivalTime = "xx:xx"
                        //         }
                        //         var status = "🔵"
                        //         if (result[i]["departureStatus"] == "cancelled") {
                        //             status = "❌"
                        //             thisArrivalTime = "<del>" + thisArrivalTime + "</del>"
                        //         }

                        //         $('#thisStation_' + i).html(result[i]["destinationName"].replace(" ", "&nbsp") + "</a>&nbsp[" + result[i]["arrivalPlatform"] + "]")
                        //         $('#thisStation_ArrivalTime_' + i).html(thisArrivalTime)
                        //         $('#thisStation_Status_' + i).html(status)
                        //         $('#thisStation_DepartureTime_' + i).html()
                        //     } else {
                        //         $('#destination_' + i).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + result[i]["destinationRef"] + "'>" + result[i]["destinationName"].replace(" ", "&nbsp") + "</a>&nbsp[?]")

                        //         $('#destination_ArrivalTime_' + i).html("xx:xx")
                        //         $('#destination_Status_' + i).html("🔵")
                        //         $('#destination_DepartureTime_' + i).html()
                        //     }
                        // }
                        if (departureTime.includes('aN') == false && arrivalTime.includes('aN') == false && result[i]["stopsAfter"][0] == undefined) {
                            var status = "🔵"
                            thisArrivalTime = arrivalTime
                            if (result[i]["departureStatus"] == "cancelled") {
                                status = "❌"
                                thisArrivalTime = "<del>" + thisArrivalTime + "</del>"
                            }
                            $('#thisStation_' + i).html(<?php echo "'" . $stationInfo["station_name"] . "'"; ?>.replace(" ", "&nbsp") + "</a>&nbsp[" + result[i]["arrivalPlatform"] + "]")
                            $('#thisStation_ArrivalTime_' + i).html(thisArrivalTime)
                            $('#thisStation_Status_' + i).html(status)
                            $('#thisStation_DepartureTime_' + i).html(departureTime)
                        }
                        $('#line_' + i).html("")
                        var line = result[i]["lineRef"];
                        if (line != "") {
                            if (line == "cargo") {
                                line = '<div class="linebox small cargo"><div class="text">cargo</div></div>'
                            } else {
                                line = '<div class="linebox small ' + line + '"><div class="text">' + line.substr(0, 1) + ' ' + line.substr(1) + '</div></div>'
                                // line = '<img id="line" class="departureImage" src="./assets/media/lines/centered/' + line + '.svg">'
                            }
                            $('#line_' + i).html(line)
                        }
                        operator = '<img id="line" class="journey operatorImage" src="../assets/media/companies/' + result[i]["operatorRef"] + '.svg">'
                        $('#operator_' + i).html(operator)
                        var destination = result[i]["destinationName"];
                        var origin = result[i]["originName"];
                        $('#platform' + i).html(result[i]["departurePlatform"])
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
                        if (type == "cancelled") {
                            departureTime = "<del>" + departureTime + "</del>"
                        }
                        if (arrivalType == "cancelled") {
                            arrivalTime = "<del>" + arrivalTime + "</del>"
                        }

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