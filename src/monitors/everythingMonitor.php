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
$stationDataQuery = new getStationData($databaseConnection);
$stationInfo = $stationDataQuery->getStationInformation($station);
include "../database/getNextToData.php";
$nextToQuery = new getNextToData($databaseConnection);
include "../database/getNextToEntryData.php";
$nextToEntryQuery = new getNextToEntryData($databaseConnection);
$nextTos = $nextToQuery->getNextTosNextTo($station);
echo $stationInfo["station_name"];

?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $stationInfo["station_name"] ?> - Main Display - CandyTransport</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/displayTest.css">
    <link rel="stylesheet" href="../assets/css/lineImages.css">
    <script src="https://kit.fontawesome.com/185bd08920.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
</head>

<body class="display">
    <div class="topBG"></div>
    <a target='_parent' href="https://trains.candycryst.com/station.php?stationRef=<?php echo $station; ?>"><img class="trainImage" id="departuresTrainImage" src="../assets/media/Train.svg"></a>
    <div class="everythingTopBar" id="departuresTopBar"></div>
    <div class="departuresHeader" id="departuresHeader"><b>Ankomster - Avganger</b></div>
    <div id="currentTime" class="localTime"></div>
    <table class="departuresTable" id="departuresTable">
        <tr class="firstRow">
            <th></th>
            <th colspan="4" class="column previousStation"><b>Tog fra</b><br />Train from</th>
            <th colspan="6" class="column platform"><b>Tidligere stasjoner</b><br />Previous stations</th>
            <th colspan="4" class="column previousStation"><b>Denne stasjonen</b><br />This Station</th>
            <th colspan="6" class="column platform"><b>Kommende stasjoner</b><br />next stations</th>
            <th colspan="5" class="column destination"><b>Tog til</b><br />Destination</th>
            <th></th>
            <th></th>
        </tr>
        <?php for ($i = 0; $i < 11; $i++) {
            $row = '<tr>';
            $row .= '<td rowspan="2" class="cell everythingTest" id="trainRef_' . $i . '"></td>';
            $row .= '<td class="cell time right"></td>';
            $row .= '<td class="cell dot" id="origin_Status_' . $i . '"></td>';
            $row .= '<td class="cell time left" id="origin_DepartureTime_' . $i . '"></td>';

            $row .= '<td class="cell time right" id="previousStation2_ArrivalTime_' . $i . '"></td>';
            $row .= '<td class="cell dot" id="previousStation2_Status_' . $i . '"></td>';
            $row .= '<td class="cell time left" id="previousStation2_DepartureTime_' . $i . '"></td>';

            $row .= '<td class="cell time right" id="previousStation1_ArrivalTime_' . $i . '"></td>';
            $row .= '<td class="cell dot" id="previousStation1_Status_' . $i . '"></td>';
            $row .= '<td class="cell time left" id="previousStation1_DepartureTime_' . $i . '"></td>';

            $row .= '<td class="cell time right" id="thisStation_ArrivalTime_' . $i . '"></td>';
            $row .= '<td class="cell dot" id="thisStation_Status_' . $i . '"></td>';
            $row .= '<td class="cell time left" id="thisStation_DepartureTime_' . $i . '"></td>';

            $row .= '<td class="cell time right" id="nextStation1_ArrivalTime_' . $i . '"></td>';
            $row .= '<td class="cell dot" id="nextStation1_Status_' . $i . '"></td>';
            $row .= '<td class="cell time left" id="nextStation1_DepartureTime_' . $i . '"></td>';

            $row .= '<td class="cell time right" id="nextStation2_ArrivalTime_' . $i . '"></td>';
            $row .= '<td class="cell dot" id="nextStation2_Status_' . $i . '"></td>';
            $row .= '<td class="cell time left" id="nextStation2_DepartureTime_' . $i . '"></td>';

            $row .= '<td class="cell time right" id="destination_ArrivalTime_' . $i . '"></td>';
            $row .= '<td class="cell dot" id="destination_Status_' . $i . '"></td>';
            $row .= '<td class="cell time left"></td></td>';

            $row .= '<td rowspan="2" class="cell everythingTest" id="operator_' . $i . '"></td>';
            $row .= '<td rowspan="2" class="cell everythingTest" id="line_' . $i . '">';
            $row .= '</td>';
            $row .= '</tr>';
            $row .= '<tr class="borderRow">';
            $row .= '<td colspan="3" class="cell journeyStationNames" id="origin_' . $i . '"></td>';
            $row .= '<td colspan="3" class="cell journeyStationNames" id="previousStation2_' . $i . '"></td>';
            $row .= '<td colspan="3" class="cell journeyStationNames" id="previousStation1_' . $i . '"></td>';
            $row .= '<td colspan="3" class="cell journeyStationNames" id="thisStation_' . $i . '"></td>';
            $row .= '<td colspan="3" class="cell journeyStationNames" id="nextStation1_' . $i . '"></td>';
            $row .= '<td colspan="3" class="cell journeyStationNames" id="nextStation2_' . $i . '"></td>';
            $row .= '<td colspan="3" class="cell journeyStationNames" id="destination_' . $i . '"></td>';
            $row .= '</tr>';
            echo $row;
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
    updateInfo();
    setInterval(function() {
        updateInfo()
    }, 8000);

    function createTimestamp(input) {
        var full = new Date(input);
        return ("0" + full.getHours()).slice(-2) + ":" + ("0" + full.getMinutes()).slice(-2)
    }

    function updateInfo() {
        $.ajax({
            url: "../queries/mainQuery.php?type=everything&station=" + station,
            type: "POST",
            success: function(msg) {
                var thisTime = msg;
                result = JSON.parse(msg);
                if (thisTime != previousTime) {
                    for (var i = 0; i < 11; i++) {

                        $('#origin_' + i).html("")
                        $('#origin_Status_' + i).html("")
                        $('#origin_DepartureTime_' + i).html("")

                        $('#previousStation2_' + i).html("")
                        $('#previousStation2_ArrivalTime_' + i).html("")
                        $('#previousStation2_Status_' + i).html("")
                        $('#previousStation2_DepartureTime_' + i).html("")

                        $('#previousStation1_' + i).html("")
                        $('#previousStation1_ArrivalTime_' + i).html("")
                        $('#previousStation1_Status_' + i).html("")
                        $('#previousStation1_DepartureTime_' + i).html("")

                        $('#thisStation_ArrivalTime_' + i).html("")
                        $('#thisStation_Status_' + i).html("")
                        $('#thisStation_DepartureTime_' + i).html("")

                        $('#nextStation1_' + i).html("")
                        $('#nextStation1_ArrivalTime_' + i).html("")
                        $('#nextStation1_Status_' + i).html("")
                        $('#nextStation1_DepartureTime_' + i).html("")

                        $('#nextStation2_' + i).html("")
                        $('#nextStation2_ArrivalTime_' + i).html("")
                        $('#nextStation2_Status_' + i).html("")
                        $('#nextStation2_DepartureTime_' + i).html("")

                        $('#destination_' + i).html("")
                        $('#destination_ArrivalTime_' + i).html("")
                        $('#destination_Status_' + i).html("")
                        // console.log(result[i])
                        // if (result[i] == undefined) {
                        //     continue;
                        // }
                        var type = result[i]["departureStatus"];
                        var arrivalType = result[i]["arrivalStatus"];
                        var departureTimeFull = new Date(result[i]["aimedDepartureTime"]);
                        var departureTime = ("0" + departureTimeFull.getHours()).slice(-2) + ":" + ("0" + departureTimeFull.getMinutes()).slice(-2)
                        var arrivalTimeFull = new Date(result[i]["aimedArrivalTime"]);
                        var arrivalTime = ("0" + arrivalTimeFull.getHours()).slice(-2) + ":" + ("0" + arrivalTimeFull.getMinutes()).slice(-2)
                        $('#trainRef_' + i).html("Train " + result[i]["trainRef"])

                        var journeyOrigin = result[i]["stopsBefore"][0];
                        var journeyPreviousStation2 = result[i]["stopsBefore"][result[i]["stopsBefore"].length - 3];
                        var journeyPreviousStation1 = result[i]["stopsBefore"][result[i]["stopsBefore"].length - 2];
                        var journeyThisStation = result[i]["stopsAfter"][0];
                        var journeyNextStation1 = result[i]["stopsAfter"][1];
                        var journeyNextStation2 = result[i]["stopsAfter"][2];
                        var journeyDestination = result[i]["stopsAfter"][result[i]["stopsAfter"].length - 1];
                        var currentIsEnd = 0;
                        if (journeyOrigin != undefined) {
                            var journeyOriginD = createTimestamp(journeyOrigin["aimedD"]);
                            if (result[i]["stopsBefore"].length == 1) {
                                currentIsEnd = 1;
                                $('#thisStation_' + i).html(journeyOrigin["name"].replace(" ", "&nbsp") + "&nbsp[" + journeyOrigin["track"] + "]")
                                $('#thisStation_Status_' + i).html(journeyOrigin["status"])
                                $('#thisStation_DepartureTime_' + i).html(journeyOriginD)
                            }
                            if (result[i]["stopsBefore"].length > 1) {
                                $('#origin_' + i).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + journeyOrigin["code"] + "'>" + journeyOrigin["name"].replace(" ", "&nbsp") + "</a>&nbsp[" + journeyOrigin["track"] + "]")
                                $('#origin_Status_' + i).html(journeyOrigin["status"])
                                $('#origin_DepartureTime_' + i).html(journeyOriginD)
                            }

                            if (result[i]["stopsBefore"].length > 2) {
                                var journeyPreviousStation1A = createTimestamp(journeyPreviousStation1["aimedA"]);
                                var journeyPreviousStation1D = createTimestamp(journeyPreviousStation1["aimedD"]);
                                $('#previousStation1_' + i).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + journeyPreviousStation1["code"] + "'>" + journeyPreviousStation1["name"].replace(" ", "&nbsp") + "</a>&nbsp[" + journeyPreviousStation1["track"] + "]")
                                $('#previousStation1_ArrivalTime_' + i).html(journeyPreviousStation1A)
                                $('#previousStation1_Status_' + i).html(journeyPreviousStation1["status"])
                                $('#previousStation1_DepartureTime_' + i).html(journeyPreviousStation1D)
                            }
                            if (result[i]["stopsBefore"].length > 3) {
                                var journeyPreviousStation2A = createTimestamp(journeyPreviousStation2["aimedA"]);
                                var journeyPreviousStation2D = createTimestamp(journeyPreviousStation2["aimedD"]);
                                $('#previousStation2_' + i).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + journeyPreviousStation2["code"] + "'>" + journeyPreviousStation2["name"].replace(" ", "&nbsp") + "</a>&nbsp[" + journeyPreviousStation2["track"] + "]")
                                $('#previousStation2_ArrivalTime_' + i).html(journeyPreviousStation2A)
                                $('#previousStation2_Status_' + i).html(journeyPreviousStation2["status"])
                                $('#previousStation2_DepartureTime_' + i).html(journeyPreviousStation2D)
                            }
                        } else {
                            if (result[i]["originName"] == <?php echo "'" . $stationInfo["station_name"] . "'"; ?>) {
                                $(`#thisStation_${i}`).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + result[i]["originRef"] + "'>" + result[i]["originName"].replace(" ", "&nbsp") + "</a>&nbsp[" + result[i]["departurePlatform"] + "]")
                                $('#thisStation_ArrivalTime_' + i).html("")
                                $('#thisStation_Status_' + i).html("🔵")
                                $('#thisStation_DepartureTime_' + i).html(departureTime)

                            } else {
                                $('#origin_' + i).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + result[i]["originRef"] + "'>" + result[i]["originName"].replace(" ", "&nbsp") + "</a>&nbsp[?]")
                                // $('#origin_ArrivalTime_' + i).html("")
                                $('#origin_Status_' + i).html("🔵")
                                $('#origin_DepartureTime_' + i).html("xx:xx")
                                $('#previousStation2_' + i).html("")
                                $('#previousStation1_' + i).html("")
                                $('#thisStation_' + i).html("")
                            }
                        }
                        if (journeyDestination != undefined) {
                            var journeyDestinationA = createTimestamp(journeyDestination["aimedA"]);
                            if (result[i]["stopsAfter"].length == 1) {
                                currentIsEnd = 1;
                                $('#thisStation_' + i).html(journeyDestination["name"].replace(" ", "&nbsp") + "&nbsp[" + journeyDestination["track"] + "]")
                                $('#thisStation_ArrivalTime_' + i).html(journeyDestinationA)
                                $('#thisStation_Status_' + i).html(journeyDestination["status"])
                            }
                            if (result[i]["stopsAfter"].length > 1) {
                                $('#destination_' + i).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + journeyDestination["code"] + "'>" + journeyDestination["name"].replace(" ", "&nbsp") + "</a>&nbsp[" + journeyDestination["track"] + "]")
                                $('#destination_ArrivalTime_' + i).html(journeyDestinationA)
                                $('#destination_Status_' + i).html(journeyDestination["status"])
                            }
                            if (result[i]["stopsAfter"].length > 2) {
                                var journeyNextStation1A = createTimestamp(journeyNextStation1["aimedA"]);
                                var journeyNextStation1D = createTimestamp(journeyNextStation1["aimedD"]);
                                $('#nextStation1_' + i).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + journeyNextStation1["code"] + "'>" + journeyNextStation1["name"].replace(" ", "&nbsp") + "</a>&nbsp[" + journeyNextStation1["track"] + "]")
                                $('#nextStation1_ArrivalTime_' + i).html(journeyNextStation1A)
                                $('#nextStation1_Status_' + i).html(journeyNextStation1["status"])
                                $('#nextStation1_DepartureTime_' + i).html(journeyNextStation1D)
                            }
                            if (result[i]["stopsAfter"].length > 3) {
                                var journeyNextStation2A = createTimestamp(journeyNextStation2["aimedA"]);
                                var journeyNextStation2D = createTimestamp(journeyNextStation2["aimedD"]);
                                $('#nextStation2_' + i).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + journeyNextStation2["code"] + "'>" + journeyNextStation2["name"].replace(" ", "&nbsp") + "</a>&nbsp[" + journeyNextStation2["track"] + "]")
                                $('#nextStation2_ArrivalTime_' + i).html(journeyNextStation2A)
                                $('#nextStation2_Status_' + i).html(journeyNextStation2["status"])
                                $('#nextStation2_DepartureTime_' + i).html(journeyNextStation2D)
                            }
                        } else {
                            if (result[i]["destinationName"] == <?php echo "'" . $stationInfo["station_name"] . "'"; ?>) {
                                thisArrivalTime = arrivalTime
                                if (arrivalTime.includes('aN')) {
                                    thisArrivalTime = "xx:xx"
                                }
                                var status = "🔵"
                                if (result[i]["departureStatus"] == "cancelled") {
                                    status = "❌"
                                    thisArrivalTime = "<del>" + thisArrivalTime + "</del>"
                                }

                                $('#thisStation_' + i).html(result[i]["destinationName"].replace(" ", "&nbsp") + "</a>&nbsp[" + result[i]["arrivalPlatform"] + "]")
                                $('#thisStation_ArrivalTime_' + i).html(thisArrivalTime)
                                $('#thisStation_Status_' + i).html(status)
                                $('#thisStation_DepartureTime_' + i).html()
                            } else {
                                $('#destination_' + i).html("<a target='_parent' href='https://trains.candycryst.com/monitor.php?type=everything&station=" + result[i]["destinationRef"] + "'>" + result[i]["destinationName"].replace(" ", "&nbsp") + "</a>&nbsp[?]")

                                $('#destination_ArrivalTime_' + i).html("xx:xx")
                                $('#destination_Status_' + i).html("🔵")
                                $('#destination_DepartureTime_' + i).html()
                            }
                        }
                        if (departureTime.includes('aN') == false && arrivalTime.includes('aN') == false && journeyDestination == undefined && journeyOrigin == undefined) {
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

                        if (journeyThisStation != undefined && currentIsEnd == 0) {
                            var journeyThisStationA = createTimestamp(journeyThisStation["aimedA"]);
                            var journeyThisStationD = createTimestamp(journeyThisStation["aimedD"]);
                            $('#thisStation_' + i).html(journeyThisStation["name"].replace(" ", "&nbsp") + "&nbsp[" + journeyThisStation["track"] + "]")
                            $('#thisStation_ArrivalTime_' + i).html(journeyThisStationA)
                            $('#thisStation_Status_' + i).html(journeyThisStation["status"])
                            $('#thisStation_Time_' + i).html(journeyThisStationD)
                        }
                        $('#line_' + i).html("")
                        var line = result[i]["lineRef"];
                        if (line != "") {
                            line = '<div class="linebox small ' + line + '"><div class="text">' + line.substr(0, 1) + ' ' + line.substr(1) + '</div></div>'
                            // line = '<img id="line" class="departureImage" src="./assets/media/lines/centered/' + line + '.svg">'
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