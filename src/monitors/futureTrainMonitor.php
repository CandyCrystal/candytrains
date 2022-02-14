<?php
$train = $_GET["train"];
if ($train == "") {
    $train = 2281;
}

?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Train display - CandyTrains</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="../assets/css/lineImages.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/futureTrainDisplay.css">
    <script src="https://kit.fontawesome.com/185bd08920.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
</head>

<body class="display">
    <div class="topBar">
        <div class="leftPane">
            <div class="stations">
                <div class="stationBefore" id="prevStation2">
                    <div class="stationName" id="prevStation2Name"></div>
                    <div class="routeLine">
                        <div class="verticalLine"></div>
                        <div class="horizontalLine first"></div>
                    </div>
                </div>
                <div class="stationBefore" id="prevStation1">
                    <div class="stationName" id="prevStation1Name"></div>
                    <div class="routeLine">
                        <div class="verticalLine"></div>
                        <div class="horizontalLine first"></div>
                    </div>
                </div>
                <div class="stationNext" id="thisStation">
                    <div class="stationName" id="thisStationName"></div>
                    <div class="routeLine">
                        <div class="verticalLine"></div>
                        <div class="horizontalLine next"></div>
                    </div>
                    <div class="stationTime" id="thisStationTime"></div>
                </div>
                <div class="stationUpcomming" id="nextStation1">
                    <div class="stationName" id="nextStation1Name"></div>
                    <div class="routeLine">
                        <div class="verticalLine"></div>
                        <div class="horizontalLine"></div>
                    </div>
                    <div class="stationTime" id="nextStation1Time"></div>
                </div>
                <div class="stationUpcomming" id="nextStation2">
                    <div class="stationName" id="nextStation2Name"></div>
                    <div class="routeLine">
                        <div class="verticalLine"></div>
                        <div class="horizontalLine"></div>
                    </div>
                    <div class="stationTime" id="nextStation2Time"></div>
                </div>
                <div class="stationUpcomming" id="nextStation3">
                    <div class="stationName" id="nextStation3Name"></div>
                    <div class="routeLine">
                        <div class="verticalLine"></div>
                        <div class="horizontalLine"></div>
                    </div>
                    <div class="stationTime" id="nextStation3Time"></div>
                </div>
                <div class="stationUpcomming" id="nextStation4">
                    <div class="stationName" id="nextStation4Name"></div>
                    <div class="routeLine">
                        <div class="verticalLine"></div>
                        <div class="horizontalLine"></div>
                    </div>
                    <div class="stationTime" id="nextStation4Time"></div>
                </div>
                <div class="stationUpcomming" id="nextStation5">
                    <div class="stationName" id="nextStation5Name"></div>
                    <div class="routeLine">
                        <div class="verticalLine"></div>
                        <div class="horizontalLine"></div>
                    </div>
                    <div class="stationTime" id="nextStation5Time"></div>
                </div>
                <div class="stationUpcomming" id="nextStation6">
                    <div class="stationName" id="nextStation6Name"></div>
                    <div class="routeLine">
                        <div class="verticalLine"></div>
                        <div class="horizontalLine"></div>
                    </div>
                    <div class="stationTime" id="nextStation6Time"></div>
                </div>
                <div class="stationDashes" id="stationDashes" style="display:none">
                    <div class="stationName"></div>
                    <div class="routeLine dotted">
                        <div class="dottedLine"></div>
                    </div>
                </div>
                <div class="stationDestination">
                    <div class="stationName" id="stationDestinationName"></div>
                    <div class="routeLine">
                        <div class="verticalLine"></div>
                        <div class="horizontalLine last"></div>
                    </div>
                    <div class="stationTime" id="stationDestinationTime"></div>
                </div>
            </div>
        </div>
        <div class="centerPane">
            <table class="bigTextTable">
                <tr>
                    <td class="nextStationBig" id="nextStationBig">Loading ...</td>
                    <td class="nextStationTimeBig" id="nextStationTimeBig"></td>
                </tr>
                <tr>
                    <td class="destinationBig" id="destinationBig"></td>
                    <td class="destinationTimeBig" id="destinationTimeBig"></td>
                </tr>
            </table>
        </div>
        <div class="rightPane">
            <div class="time" id="currentTime"></div>
            <div class="wc" id="wc"  onclick="toggleWc()">WC</div>
        </div>
    </div>
    <div class="trainNumber"><?php echo $train ?></div>
</body>

<script>
    var previousTime;
    var train = "<?php echo $train ?>";
    var clockState;
    setTime();
    setInterval(function() {
        setTime()
    }, 1000);

    updateInfo();
    setInterval(function() {
        updateInfo()
    }, 8000);
    var trainData = [];

    function toggleDotted(state) {
        if (state == "true") {
            $('#stationDashes').css("display", "unset")
        } else {
            $('#stationDashes').css("display", "none")
        }

    }

    function enableStation(station, data) {
        if (data["aimedA"] == "") {
            var time = new Date(data["aimedD"]);
        } else {
            var time = new Date(data["aimedA"]);
        }
        var time = ("0" + time.getHours()).slice(-2) + ":" + ("0" + time.getMinutes()).slice(-2)
        $(`#${station}`).css("display", "unset")
        $(`#${station}Name`).html(data["name"].replace(" ", "&nbsp"))
        $(`#${station}Time`).html(time)
    }

    function disableStation(station) {
        $(`#${station}`).css("display", "none")
        $(`#${station}Name`).html("")
        $(`#${station}Time`).html("")
    }

    function setBigText(isLast, lastData, nextData, line) {
        if (isLast != "true") {
            if (lastData["aimedA"] == "") {
                var lastTime = new Date(lastData["aimedD"]);
            } else {
                var lastTime = new Date(lastData["aimedA"]);
            }
            var lastTime = ("0" + lastTime.getHours()).slice(-2) + ":" + ("0" + lastTime.getMinutes()).slice(-2)
            if (result["line"] == "-") {
                $('#destinationBig').html(lastData["name"].replace(" ", "&nbsp"))
            } else {
                $('#destinationBig').html('<div class="linebox future ' + line + '" id="line">' + result["line"] + '</div>' + lastData["name"].replace(" ", "&nbsp"))
            }
            $('#destinationTimeBig').html(lastTime)
        } else {
            $('#destinationBig').html('<div class="linebox future ' + line + '" id="line">' + result["line"] + '</div>')
            $('#destinationTimeBig').html("")

        }
        if (nextData["aimedA"] == "") {
            var nextTime = new Date(nextData["aimedD"]);
        } else {
            var nextTime = new Date(nextData["aimedA"]);
        }
        var nextTime = ("0" + nextTime.getHours()).slice(-2) + ":" + ("0" + nextTime.getMinutes()).slice(-2)

        $('#nextStationBig').html(nextData["name"].replace(" ", "&nbsp"))
        $('#nextStationTimeBig').html(nextTime)
    }

    var wcState = 0

    function toggleWc() {
        if (wcState == 0) {
            $('#wc').css("color", "red")
            wcState = 1
        } else {
            $('#wc').css("color", "green")
            wcState = 0
        }
    }

    function updateInfo() {
        $.ajax({
            url: "../queries/train.php?train=" + train,
            type: "POST",
            success: function(msg) {
                result = JSON.parse(msg);
                var thisTime = result["stopsAfter"];
                console.log("Reloaded: " + thisTime == previousTime)
                if (thisTime != previousTime) {
                    if (result["stopsAfter"][0] == undefined) {
                        $('#nextStationBig').html("Error loading content")
                        $('#destinationBig').html("Unable to fetch data or train not running in the next 15 hours.")
                    }
                    var line = result["line"];

                    console.log(result["numBefore"] + " " + result["numAfter"])
                    $('#stationDashes').css("display", "none")
                    result["numBefore"] = parseInt(result["numBefore"])
                    result["numAfter"] = parseInt(result["numAfter"])

                    if (result["numAfter"] > 8) {
                        disableStation("prevStation2")
                        disableStation("prevStation1")
                        enableStation("thisStation", result["stopsAfter"][0])
                        setBigText("false", result["stopsAfter"][result["stopsAfter"].length - 1], result["stopsAfter"][0], result["line"])

                        enableStation("nextStation1", result["stopsAfter"][1])
                        enableStation("nextStation2", result["stopsAfter"][2])
                        enableStation("nextStation3", result["stopsAfter"][3])
                        enableStation("nextStation4", result["stopsAfter"][4])
                        enableStation("nextStation5", result["stopsAfter"][5])
                        disableStation("nextStation6")
                        enableStation("stationDestination", result["stopsAfter"][result["stopsAfter"].length - 1])
                        toggleDotted("true")
                    } else if (result["numAfter"] == 8) {
                        disableStation("prevStation2")
                        disableStation("prevStation1")
                        enableStation("thisStation", result["stopsAfter"][0])
                        setBigText("false", result["stopsAfter"][result["stopsAfter"].length - 1], result["stopsAfter"][0], result["line"])
                        enableStation("nextStation1", result["stopsAfter"][1])
                        enableStation("nextStation2", result["stopsAfter"][2])
                        enableStation("nextStation3", result["stopsAfter"][3])
                        enableStation("nextStation4", result["stopsAfter"][4])
                        enableStation("nextStation5", result["stopsAfter"][5])
                        enableStation("nextStation6", result["stopsAfter"][6])
                        enableStation("stationDestination", result["stopsAfter"][result["stopsAfter"].length - 1])
                        toggleDotted("false")
                    } else if (result["numAfter"] == 7) {
                        disableStation("prevStation2")
                        disableStation("prevStation1")
                        enableStation("thisStation", result["stopsAfter"][0])
                        setBigText("false", result["stopsAfter"][result["stopsAfter"].length - 1], result["stopsAfter"][0], result["line"])
                        enableStation("nextStation1", result["stopsAfter"][1])
                        enableStation("nextStation2", result["stopsAfter"][2])
                        enableStation("nextStation3", result["stopsAfter"][3])
                        enableStation("nextStation4", result["stopsAfter"][4])
                        enableStation("nextStation5", result["stopsAfter"][5])
                        disableStation("nextStation6")
                        enableStation("stationDestination", result["stopsAfter"][result["stopsAfter"].length - 1])
                        toggleDotted("false")
                    } else if (result["numAfter"] == 6) {
                        disableStation("prevStation2")
                        disableStation("prevStation1")
                        enableStation("thisStation", result["stopsAfter"][0])
                        setBigText("false", result["stopsAfter"][result["stopsAfter"].length - 1], result["stopsAfter"][0], result["line"])
                        enableStation("nextStation1", result["stopsAfter"][1])
                        enableStation("nextStation2", result["stopsAfter"][2])
                        enableStation("nextStation3", result["stopsAfter"][3])
                        enableStation("nextStation4", result["stopsAfter"][4])
                        disableStation("nextStation5")
                        disableStation("nextStation6")
                        enableStation("stationDestination", result["stopsAfter"][result["stopsAfter"].length - 1])
                        toggleDotted("false")
                    } else if (result["numAfter"] == 5) {
                        disableStation("prevStation2")
                        disableStation("prevStation1")
                        enableStation("thisStation", result["stopsAfter"][0])
                        setBigText("false", result["stopsAfter"][result["stopsAfter"].length - 1], result["stopsAfter"][0], result["line"])
                        enableStation("nextStation1", result["stopsAfter"][1])
                        enableStation("nextStation2", result["stopsAfter"][2])
                        enableStation("nextStation3", result["stopsAfter"][3])
                        disableStation("nextStation4")
                        disableStation("nextStation5")
                        disableStation("nextStation6")
                        enableStation("stationDestination", result["stopsAfter"][result["stopsAfter"].length - 1])
                        toggleDotted("false")
                    } else if (result["numAfter"] == 4) {
                        disableStation("prevStation2")
                        disableStation("prevStation1")
                        enableStation("thisStation", result["stopsAfter"][0])
                        setBigText("false", result["stopsAfter"][result["stopsAfter"].length - 1], result["stopsAfter"][0], result["line"])
                        enableStation("nextStation1", result["stopsAfter"][1])
                        enableStation("nextStation2", result["stopsAfter"][2])
                        disableStation("nextStation3")
                        disableStation("nextStation4")
                        disableStation("nextStation5")
                        disableStation("nextStation6")
                        enableStation("stationDestination", result["stopsAfter"][result["stopsAfter"].length - 1])
                        toggleDotted("false")
                    } else if (result["numAfter"] == 3) {
                        disableStation("prevStation2")
                        disableStation("prevStation1")
                        enableStation("thisStation", result["stopsAfter"][0])
                        setBigText("false", result["stopsAfter"][result["stopsAfter"].length - 1], result["stopsAfter"][0], result["line"])
                        enableStation("nextStation1", result["stopsAfter"][1])
                        disableStation("nextStation2")
                        disableStation("nextStation3")
                        disableStation("nextStation4")
                        disableStation("nextStation5")
                        disableStation("nextStation6")
                        enableStation("stationDestination", result["stopsAfter"][result["stopsAfter"].length - 1])
                        toggleDotted("false")
                    } else if (result["numAfter"] == 2) {
                        disableStation("prevStation2")
                        disableStation("prevStation1")
                        enableStation("thisStation", result["stopsAfter"][0])
                        setBigText("false", result["stopsAfter"][result["stopsAfter"].length - 1], result["stopsAfter"][0], result["line"])
                        disableStation("nextStation1")
                        disableStation("nextStation2")
                        disableStation("nextStation3")
                        disableStation("nextStation4")
                        disableStation("nextStation5")
                        disableStation("nextStation6")
                        enableStation("stationDestination", result["stopsAfter"][result["stopsAfter"].length - 1])
                        toggleDotted("false")
                    } else if (result["numAfter"] == 1) {
                        disableStation("prevStation2")
                        disableStation("prevStation1")
                        disableStation("thisStation")
                        setBigText("true", result["stopsAfter"][result["stopsAfter"].length - 1], result["stopsAfter"][0], result["line"])
                        disableStation("nextStation1")
                        disableStation("nextStation2")
                        disableStation("nextStation3")
                        disableStation("nextStation4")
                        disableStation("nextStation5")
                        disableStation("nextStation6")
                        enableStation("stationDestination", result["stopsAfter"][result["stopsAfter"].length - 1])
                        toggleDotted("false")
                    }
                    if (result["numBefore"] == 1) {
                        enableStation("prevStation1", result["stopsBefore"][0])
                        disableStation("nextStation5")
                        disableStation("nextStation6")
                        if (result["numAfter"] == 5) {
                            enableStation("nextStation5", result["stopsAfter"][5])
                            toggleDotted("false")
                        } else if (result["numAfter"] == 8) {
                            toggleDotted("true")
                        }
                    } else if (result["numBefore"] >= 2) {
                        enableStation("prevStation1", result["stopsBefore"][0])
                        enableStation("prevStation2", result["stopsBefore"][1])
                        disableStation("nextStation4")
                        disableStation("nextStation5")
                        disableStation("nextStation6")
                        if (result["numAfter"] == 6) {
                            enableStation("nextStation4", result["stopsAfter"][4])
                            toggleDotted("false")
                        } else if (result["numAfter"] >= 7) {
                            toggleDotted("true")
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