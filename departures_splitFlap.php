<?php
$pageRequiresLogin = false;
include "./config/session.php";
include "./config/connect.php";
include "./config/candyDirectory.php";
include "./databaseQueries.php";
if (isset($_SESSION['login_user'])) {
    $currentUserName = $_SESSION['login_user'];
    $currentUserQuery = "SELECT * FROM users WHERE userName = '$currentUserName'";
    $currentUserResult = $candyDirectoryConnection->query($currentUserQuery);
    $currentUser = mysqli_fetch_array($currentUserResult);
    $userCanManage = $currentUser["userCanManageCandyTrains"];
};
$station = $_GET["station"];

if ($station == "") {
    $station = "OSL";
}
$query = new databaseQueries($station, $conn);

$row = $query->getStationInfo();

$numRows = 10;
if ($station == "") {
    $station = "OSL";
}
$file = file_get_contents("stations.json");
$json_decoded = json_decode($file);
$json_encoded = json_encode($json_decoded);
$thisStationName = $row["stationName"];
?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $thisStationName ?> - departures - CandyTrains</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/norwegianMainDisplay.css">
    <script src="https://kit.fontawesome.com/185bd08920.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>

    <script src="./flapper/numberformatter/jquery.numberformatter-1.2.4.jsmin.js"></script>
    <link href="./flapper/css/flapper.css" type="text/css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script src="./flapper/transform/dist/jquery.transform-0.9.3.min.js"></script>
    <script src="./flapper/src/jquery.flapper.js"></script>
    <!-- <script src="./flapper/src/flapdemo.js"></script> -->
    <style>
        .flapFloat {
            float: left;
            padding: 5px;
        }
    </style>
</head>

<body class="display">
    <table>
        <?php for ($i = 0; $i < $numRows; $i++) { ?>
            <tr>
                <td id='departureTime<?php echo $i ?>' class="flapFloat XS"></td>
                <td id='departureLine<?php echo $i ?>' class="flapFloat XS"></td>
                <td id='departureDestination<?php echo $i ?>' class="flapFloat XS"></td>
                <td id='departureNewTime<?php echo $i ?>' class="flapFloat XS yellow"></td>
                <td id='departurePlatform<?php echo $i ?>' class="flapFloat XS"></td>
            </tr>
        <?php } ?>

    </table>
    <div id="currentTime" class="localTime"></div>
    <div id="header_display"></div>

</body>
<script>
    $(document).ready(function() {
        <?php for ($i = 0; $i < $numRows; $i++) { ?>
            var $departureTime<?php echo $i ?> = $('#departureTime<?php echo $i ?>');
            $departureTime<?php echo $i ?>.flapper({
                width: 5,
                chars_preset: 'num'
            });
            var $departureLine<?php echo $i ?> = $('#departureLine<?php echo $i ?>');
            $departureLine<?php echo $i ?>.flapper({
                width: 4,
                chars_preset: 'departureLine',
                align: "left"
            });
            var $departureDestination<?php echo $i ?> = $('#departureDestination<?php echo $i ?>');
            $departureDestination<?php echo $i ?>.flapper({
                width: 13,
                chars_preset: 'alphanum',
                align: "left"
            });
            var $departureNewTime<?php echo $i ?> = $('#departureNewTime<?php echo $i ?>');
            $departureNewTime<?php echo $i ?>.flapper({
                width: 5,
                chars_preset: 'num',
                align: "left"
            });
            var $departurePlatform<?php echo $i ?> = $('#departurePlatform<?php echo $i ?>');
            $departurePlatform<?php echo $i ?>.flapper({
                width: 2,
                chars_preset: 'num',
                align: "left"
            });
        <?php } ?>

    });

    var stations = JSON.parse('<?php echo $json_encoded ?>')
    var previousTime;
    var station = "<?php echo $station ?>";
    var displayType = "<?php echo $displayType ?>";
    var clockState;
    setTime()
    setInterval(function() {
        setTime()
    }, 1000);
    updateInfoDepartures();
    $('#departuresTopBar').attr("style", "display: block;")
    $('#departuresHeader').attr("style", "display: block;")
    $('#currentTime').attr("style", "display: block;")
    $('#departuresTable').attr("style", "display: table;")
    $('#departuresTrainImage').attr("style", "display: block;")
    setInterval(function() {
        updateInfoDepartures()
    }, 8000);




    function updateInfoDepartures() {

        $.ajax({
            url: "./queries/mainDisplayQuery.php?station=" + station + "&displayType=departures",
            type: "POST",
            success: function(msg) {
                var thisTime = msg;
                result = JSON.parse(msg);
                if (thisTime != previousTime) {
                    for (var i = 0; i < <?php echo $numRows ?>; i++) {
                        var row = document.getElementById("departuresRow" + i)
                        var type = result[i]["departureStatus"];
                        var timeFull = new Date(result[i]["departureTime"]);
                        var time = ("0" + timeFull.getHours()).slice(-2) + ":" + ("0" + timeFull.getMinutes()).slice(-2)
                        var line = result[i]["lineRef"];
                        operator = '<img id="line" class="departureOperatorImage" src="./assets/media/companies/' + result[i]["operatorRef"] + '.svg">'
                        var destination = stations[result[i]["destinationRef"]]["name"];
                        var PlatformFull = new Date(result[i]["departureTimeNew"]);
                        time_difference = diff_minutes(timeFull, PlatformFull);
                        if (time_difference > 1) {
                            var newTime = ("0" + PlatformFull.getHours()).slice(-2) + ":" + ("0" + PlatformFull.getMinutes()).slice(-2)
                        } else {
                            var newTime = "";
                        }
                        var platform = result[i]["departurePlatform"];
                        var via = ""
                        for (var key in result[i]["viaText"]) {
                            via = via + result[i]["viaText"][key]["name"] + " • "
                        }
                        if (via != "") {
                            via = "via " + via.slice(0, -3);
                        }
                        // if (type == "cancelled") {
                        //     time = "<del>" + time + "</del>"
                        //     Platform = "Cancelled";
                        //     viaText = "";
                        // }
                        var $departureTime = $('#departureTime' + i);
                        $departureTime.val(time).change();
                        var $departureLine = $('#departureLine' + i);
                        $departureLine.val(line).change();
                        var $departureDestination = $('#departureDestination' + i)
                        $departureDestination.val(destination.toUpperCase()).change();
                        var $departureNewTime = $('#departureNewTime' + i);
                        $departureNewTime.val(newTime).change();
                        var $departurePlatform = $('#departurePlatform' + i);
                        $departurePlatform.val(platform).change();
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