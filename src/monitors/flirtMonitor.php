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
    <link rel="stylesheet" href="../assets/css/flirtDisplay.css">
    <script src="https://kit.fontawesome.com/185bd08920.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
</head>

<body class="display">
    <div class="topBar">
        <div class="linebox flirt" id="line"></div>
        <div class="time" id="currentTime"></div>
    </div>
    <div class="rightPane">
        <img id="doorImage" class="doorDisplay" src="../assets/media/doorLeft.svg" style="display:none;">
        <div id="doorText" class="doorText" style="display:none;">Utgang<br />Exit</div>
        <div class="news">
            <div class="newsContent" id="news">
                <h1 class="newsHeader"></h1>
            </div>
        </div>
    </div>
    <table class="stations">
        <tr class="firstStation">
            <td class="timeColumn">
                <div class="stationTime" id="firstStationTime"></div>
            </td>
            <td class="dotColumn">
                <div class="stationDot"></div>
            </td>
            <td class="stationColumn">
                <div class="firstStationName" id="firstStationName">Loading ...</div>
                <div class="firstStationDepartureText" id="firstDeparture"></div>
            </td>
        </tr>
        <tr class="middleStation">
            <td class="timeColumn">
                <div class="stationTime" id="secondStationTime"></div>
            </td>
            <td class="dotColumn">
                <div id="dot2"></div>
            </td>
            <td class="stationColumn">
                <div class="middleStationName" id="secondStationName"></div>
            </td>
        </tr>
        <tr class="middleStation">
            <td class="timeColumn">
                <div class="stationTime" id="thirdStationTime"></div>
            </td>
            <td class="dotColumn">
                <div id="dot3"></div>
            </td>
            <td class="stationColumn">
                <div class="middleStationName" id="thirdStationName"></div>
            </td>
        </tr>
        <tr class="middleStation">
            <td class="timeColumn">
                <div class="stationTime" id="fourthStationTime"></div>
            </td>
            <td class="dotColumn">
                <div id="dot4"></div>
            </td>
            <td class="stationColumn">
                <div class="middleStationName" id="fourthStationName"></div>
            </td>
        </tr>
        <tr class="lastStation">
            <td class="timeColumn">
                <div class="stationTime" id="lastStationTime"></div>
            </td>
            <td class="dotColumn">
                <div id="dot5" class="lastDot"></div>
            </td>
            <td class="stationColumn">
                <div class="lastStationName" id="lastStationName"></div>
            </td>
        </tr>
    </table>
    <div class="routeLineBefore" id="lineBefore"></div>
    <div id="lineCenter"></div>
    <div id="lineAfter"></div>
    <div class="trainNumber"><?php echo $train ?></div>
    <div class="newsSource">NRK Nyheter</div>
</body>

<script>
    const RSS_URL = `https://www.nrk.no/nyheter/siste.rss`;
    var newsCounter = 0;
    update_news()

    setInterval(function() {
        update_news()
    }, 20000);

    function update_news() {
        fetch(RSS_URL)
            .then(response => response.text())
            .then(str => new window.DOMParser().parseFromString(str, "text/xml"))
            .then(data => {

                const items = data.querySelectorAll("item");
                if (items[newsCounter] == undefined) {
                    newsCounter == 0;
                }
                console.log(items[newsCounter].querySelector("description").innerHTML.length)
                if (items[newsCounter].querySelector("description").innerHTML.length >= 850) {
                    newsCounter++
                } else {
                    var newsTitle = items[newsCounter].querySelector("title").innerHTML;
                    var newsContent = items[newsCounter].querySelector("description").innerHTML;
                    var newsTimeFull = new Date(items[newsCounter].querySelector("pubDate").innerHTML);
                    var newsTime = ("0" + newsTimeFull.getHours()).slice(-2) + ":" + ("0" + newsTimeFull.getMinutes()).slice(-2)
                    $('#news').html('<h1 class="newsHeader">' + newsTitle + '</h1>' + newsTime + ' <b>' + newsContent + '</b>')

                    newsCounter++;
                }
                if (newsCounter == 10) {
                    newsCounter = 0
                }

            });
    }

    var previousTime;
    var train = "<?php echo $train ?>";
    var clockState;
    var trainData = [];

    var dt

    setTime();
    setInterval(function() {
        setTime()
    }, 1000);

    updateInfo();
    setInterval(function() {
        updateInfo()
    }, 8000);
    var trainData = [];



    function updateInfo() {
        $.ajax({
            url: "../queries/train.php?train=" + train,
            type: "POST",
            success: function(msg) {
                console.log(dt)
                result = JSON.parse(msg);
                var thisTime = result["stopsAfter"];
                console.log("Reloaded: " + thisTime == previousTime)
                if (thisTime != previousTime) {
                    var line = result["line"];
                    if (result["firstStop"] == 0) {
                        $('#lineBefore').addClass("routeLineBefore")
                    } else {
                        $('#lineBefore').removeClass()
                    }
                    if (result["stopsAfter"][0] != undefined) {
                        var connections = "";
                        if (result["bus"] == 1) {
                            connections += "<img class='stationConnections' src='../assets/media/transport/bus.svg'>"
                        }
                        if (result["tram"] == 1) {
                            connections += "<img class='stationConnections' src='../assets/media/transport/tram.svg'>"
                        }
                        if (result["ferry"] == 1) {
                            connections += "<img class='stationConnections' src='../assets/media/transport/ferry.svg'>"
                        }
                        if (result["taxi"] == 1) {
                            connections += "<img class='stationConnections' src='../assets/media/transport/taxi.svg'>"
                        }
                        if (result["stopsAfter"][0]["expectedA"] == "") {
                            var arrival1full = new Date(result["stopsAfter"][0]["expectedD"]);
                            var departure1D = ("0" + arrival1full.getHours()).slice(-2) + ":" + ("0" + arrival1full.getMinutes()).slice(-2)
                        } else {
                            var departure1full = new Date(result["stopsAfter"][0]["expectedD"]);
                            var arrival1full = new Date(result["stopsAfter"][0]["expectedA"]);
                            var departure1D = ("0" + departure1full.getHours()).slice(-2) + ":" + ("0" + departure1full.getMinutes()).slice(-2)
                        }
                        if (result["stopsAfter"][0]["expectedA"] != "" && result["stopsAfter"][0]["expectedD"] != "") {
                            var expectedA = new Date(result["stopsAfter"][0]["expectedA"]);
                            if (dt >= expectedA) {
                                $('#news').css("display", "none")
                                $('#doorImage').css("display", "unset")
                                console.log(result["direction"] + result["side"])
                                if (result["direction"] == "TO" && result["side"] == 2 || result["direction"] == "FROM" && result["side"] == 1) {
                                    $('#doorImage').attr("src", "../assets/media/doorRight.svg")
                                } else if (result["direction"] == "TO" && result["side"] == 1 || result["direction"] == "FROM" && result["side"] == 2) {
                                    $('#doorImage').attr("src", "../assets/media/doorLeft.svg")
                                } else {
                                    $('#doorImage').attr("src", "../assets/media/doorCenter.svg")
                                }
                                $('#doorText').css("display", "unset")
                                $('#firstDeparture').html("Avgang " + departure1D)
                            } else {
                                $('#firstDeparture').html(connections)
                                $('#news').css("display", "unset")
                                $('#doorImage').css("display", "none")
                                $('#doorText').css("display", "none")
                            }
                        } else if (result["stopsAfter"][0]["expectedA"] != "" && result["stopsAfter"][0]["expectedD"] == "") {
                            var expectedA = new Date(result["stopsAfter"][0]["expectedA"]);
                            if (dt >= expectedA) {
                                $('#news').css("display", "none")
                                $('#doorImage').css("display", "unset")
                                console.log(result["direction"] + result["side"])
                                if (result["direction"] == "TO" && result["side"] == 2 || result["direction"] == "FROM" && result["side"] == 1) {
                                    $('#doorImage').attr("src", "../assets/media/doorRight.svg")
                                } else if (result["direction"] == "TO" && result["side"] == 1 || result["direction"] == "FROM" && result["side"] == 2) {
                                    $('#doorImage').attr("src", "../assets/media/doorLeft.svg")
                                } else {
                                    $('#doorImage').attr("src", "../assets/media/doorCenter.svg")
                                }
                                $('#doorText').css("display", "unset")
                                if (result["stopsAfter"][1] != undefined) {
                                    $('#firstDeparture').html("Avgang " + departure1D)
                                } else {
                                    $('#firstDeparture').html(connections)

                                }
                            } else {
                                $('#firstDeparture').html(connections)
                                $('#news').css("display", "unset")
                                $('#doorImage').css("display", "none")
                                $('#doorText').css("display", "none")

                            }
                        } else if (result["stopsAfter"][0]["expectedA"] == "" && result["stopsAfter"][0]["expectedD"] != "") {
                            var expectedD = new Date(result["stopsAfter"][0]["expectedD"]);
                            if (dt < expectedD) {
                                $('#news').css("display", "none")
                                $('#doorImage').css("display", "unset")
                                console.log(result["direction"] + result["side"])
                                if (result["direction"] == "TO" && result["side"] == 2 || result["direction"] == "FROM" && result["side"] == 1) {
                                    $('#doorImage').attr("src", "../assets/media/doorRight.svg")
                                } else if (result["direction"] == "TO" && result["side"] == 1 || result["direction"] == "FROM" && result["side"] == 2) {
                                    $('#doorImage').attr("src", "../assets/media/doorLeft.svg")
                                } else {
                                    $('#doorImage').attr("src", "../assets/media/doorCenter.svg")
                                }
                                $('#doorText').css("display", "unset")
                                $('#firstDeparture').html("Avgang " + departure1D)
                            } else {
                                $('#firstDeparture').html(connections)
                                $('#news').css("display", "unset")
                                $('#doorImage').css("display", "none")
                                $('#doorText').css("display", "none")

                            }
                        }
                        var arrival1Time = ("0" + arrival1full.getHours()).slice(-2) + ":" + ("0" + arrival1full.getMinutes()).slice(-2)

                        $('#firstStationTime').html(arrival1Time)
                        $('#firstStationName').html(result["stopsAfter"][0]["name"])
                        if (result["stopsAfter"][5] != undefined) {
                            if (result["stopsAfter"][1]["expectedA"] == "") {
                                var arrival2full = new Date(result["stopsAfter"][1]["expectedD"]);
                            } else {
                                var arrival2full = new Date(result["stopsAfter"][1]["expectedA"]);
                            }
                            var arrival2Time = ("0" + arrival2full.getHours()).slice(-2) + ":" + ("0" + arrival2full.getMinutes()).slice(-2)
                            if (result["stopsAfter"][2]["expectedA"] == "") {
                                var arrival3full = new Date(result["stopsAfter"][2]["expectedD"]);
                            } else {
                                var arrival3full = new Date(result["stopsAfter"][2]["expectedA"]);
                            }
                            var arrival3Time = ("0" + arrival3full.getHours()).slice(-2) + ":" + ("0" + arrival3full.getMinutes()).slice(-2)
                            if (result["stopsAfter"][3]["expectedA"] == "") {
                                var arrival4full = new Date(result["stopsAfter"][3]["expectedD"]);
                            } else {
                                var arrival4full = new Date(result["stopsAfter"][3]["expectedA"]);
                            }
                            var arrival4Time = ("0" + arrival4full.getHours()).slice(-2) + ":" + ("0" + arrival4full.getMinutes()).slice(-2)
                            if (result["stopsAfter"][result["stopsAfter"].length - 1]["expectedA"] == "") {
                                var arrival5full = new Date(result["stopsAfter"][result["stopsAfter"].length - 1]["expectedD"]);
                            } else {
                                var arrival5full = new Date(result["stopsAfter"][result["stopsAfter"].length - 1]["expectedA"]);
                            }
                            var arrival5Time = ("0" + arrival5full.getHours()).slice(-2) + ":" + ("0" + arrival5full.getMinutes()).slice(-2)

                            $('#secondStationName').html(result["stopsAfter"][1]["name"]);
                            $('#thirdStationName').html(result["stopsAfter"][2]["name"]);
                            $('#fourthStationName').html(result["stopsAfter"][3]["name"]);
                            $('#lastStationName').html(result["stopsAfter"][result["stopsAfter"].length - 1]["name"]);
                            $('#firstStationTime').html(arrival1Time)
                            $('#secondStationTime').html(arrival2Time)
                            $('#thirdStationTime').html(arrival3Time)
                            $('#fourthStationTime').html(arrival4Time)
                            $('#lastStationTime').html(arrival5Time)
                            $('#dot2').addClass("stationDot")
                            $('#dot3').addClass("stationDot")
                            $('#dot4').addClass("stationDot")
                            $('#dot5').addClass("stationDot")
                            $('#lineCenter').addClass("routeLineCenter")
                            $('#lineAfter').addClass("routeLineAfter")
                        } else if (result["stopsAfter"][4] != undefined) {
                            if (result["stopsAfter"][1]["expectedA"] == "") {
                                var arrival2full = new Date(result["stopsAfter"][1]["expectedD"]);
                            } else {
                                var arrival2full = new Date(result["stopsAfter"][1]["expectedA"]);
                            }
                            var arrival2Time = ("0" + arrival2full.getHours()).slice(-2) + ":" + ("0" + arrival2full.getMinutes()).slice(-2)
                            if (result["stopsAfter"][2]["expectedA"] == "") {
                                var arrival3full = new Date(result["stopsAfter"][2]["expectedD"]);
                            } else {
                                var arrival3full = new Date(result["stopsAfter"][2]["expectedA"]);
                            }
                            var arrival3Time = ("0" + arrival3full.getHours()).slice(-2) + ":" + ("0" + arrival3full.getMinutes()).slice(-2)
                            if (result["stopsAfter"][3]["expectedA"] == "") {
                                var arrival4full = new Date(result["stopsAfter"][3]["expectedD"]);
                            } else {
                                var arrival4full = new Date(result["stopsAfter"][3]["expectedA"]);
                            }
                            var arrival4Time = ("0" + arrival2full.getHours()).slice(-2) + ":" + ("0" + arrival2full.getMinutes()).slice(-2)
                            if (result["stopsAfter"][4]["expectedA"] == "") {
                                var arrival5full = new Date(result["stopsAfter"][4]["expectedD"]);
                            } else {
                                var arrival5full = new Date(result["stopsAfter"][4]["expectedA"]);
                            }
                            var arrival5Time = ("0" + arrival2full.getHours()).slice(-2) + ":" + ("0" + arrival5full.getMinutes()).slice(-2)
                            // $('#firstDeparture').html("Avgang " + departure1D)
                            $('#secondStationName').html(result["stopsAfter"][1]["name"]);
                            $('#thirdStationName').html(result["stopsAfter"][2]["name"]);
                            $('#fourthStationName').html(result["stopsAfter"][3]["name"]);
                            $('#lastStationName').html(result["stopsAfter"][4]["name"]);
                            $('#secondStationTime').html(arrival2Time)
                            $('#thirdStationTime').html(arrival3Time)
                            $('#fourthStationTime').html(arrival4Time)
                            $('#lastStationTime').html(arrival5Time)
                            $('#dot2').addClass("stationDot")
                            $('#dot3').addClass("stationDot")
                            $('#dot4').addClass("stationDot")
                            $('#dot5').addClass("stationDot")
                            $('#lineCenter').addClass("routeLineCenter")
                            $('#lineAfter').addClass("routeLineAfter full")
                        } else if (result["stopsAfter"][3] != undefined) {
                            if (result["stopsAfter"][1]["expectedA"] == "") {
                                var arrival2full = new Date(result["stopsAfter"][1]["expectedD"]);
                            } else {
                                var arrival2full = new Date(result["stopsAfter"][1]["expectedA"]);
                            }
                            var arrival2Time = ("0" + arrival2full.getHours()).slice(-2) + ":" + ("0" + arrival2full.getMinutes()).slice(-2)
                            if (result["stopsAfter"][2]["expectedA"] == "") {
                                var arrival3full = new Date(result["stopsAfter"][2]["expectedD"]);
                            } else {
                                var arrival3full = new Date(result["stopsAfter"][2]["expectedA"]);
                            }
                            var arrival3Time = ("0" + arrival3full.getHours()).slice(-2) + ":" + ("0" + arrival3full.getMinutes()).slice(-2)
                            if (result["stopsAfter"][3]["expectedA"] == "") {
                                var arrival4full = new Date(result["stopsAfter"][3]["expectedD"]);
                            } else {
                                var arrival4full = new Date(result["stopsAfter"][3]["expectedA"]);
                            }
                            var arrival4Time = ("0" + arrival4full.getHours()).slice(-2) + ":" + ("0" + arrival4full.getMinutes()).slice(-2)
                            // $('#firstDeparture').html("Avgang " + departure1D)
                            $('#secondStationName').html(result["stopsAfter"][1]["name"]);
                            $('#thirdStationName').html(result["stopsAfter"][2]["name"]);
                            $('#fourthStationName').html("");
                            $('#lastStationName').html(result["stopsAfter"][3]["name"]);
                            $('#secondStationTime').html(arrival2Time)
                            $('#thirdStationTime').html(arrival3Time)
                            $('#fourthStationTime').html("")
                            $('#lastStationTime').html(arrival4Time)
                            $('#dot2').addClass("stationDot")
                            $('#dot3').addClass("stationDot")
                            $('#dot4').removeClass()
                            $('#dot5').addClass("stationDot")
                            $('#lineCenter').addClass("routeLineCenter")
                            $('#lineAfter').addClass("routeLineAfter full")
                        } else if (result["stopsAfter"][2] != undefined) {
                            if (result["stopsAfter"][1]["expectedA"] == "") {
                                var arrival2full = new Date(result["stopsAfter"][1]["expectedD"]);
                            } else {
                                var arrival2full = new Date(result["stopsAfter"][1]["expectedA"]);
                            }
                            var arrival2Time = ("0" + arrival2full.getHours()).slice(-2) + ":" + ("0" + arrival2full.getMinutes()).slice(-2)
                            if (result["stopsAfter"][2]["expectedA"] == "") {
                                var arrival3full = new Date(result["stopsAfter"][2]["expectedD"]);
                            } else {
                                var arrival3full = new Date(result["stopsAfter"][2]["expectedA"]);
                            }
                            var arrival3Time = ("0" + arrival3full.getHours()).slice(-2) + ":" + ("0" + arrival3full.getMinutes()).slice(-2)
                            // $('#firstDeparture').html("Avgang " + departure1D)
                            $('#secondStationName').html(result["stopsAfter"][1]["name"]);
                            $('#thirdStationName').html("");
                            $('#fourthStationName').html("");
                            $('#lastStationName').html(result["stopsAfter"][2]["name"]);
                            $('#secondStationTime').html(arrival2Time)
                            $('#thirdStationTime').html("")
                            $('#fourthStationTime').html("")
                            $('#lastStationTime').html(arrival3Time)
                            $('#dot2').addClass("stationDot")
                            $('#dot3').removeClass()
                            $('#dot4').removeClass()
                            $('#dot5').addClass("stationDot")
                            $('#lineCenter').addClass("routeLineCenter")
                            $('#lineAfter').addClass("routeLineAfter full")
                        } else if (result["stopsAfter"][1] != undefined) {
                            if (result["stopsAfter"][1]["expectedA"] == "") {
                                var arrival2full = new Date(result["stopsAfter"][1]["expectedD"]);
                            } else {
                                var arrival2full = new Date(result["stopsAfter"][1]["expectedA"]);
                            }
                            var arrival2Time = ("0" + arrival2full.getHours()).slice(-2) + ":" + ("0" + arrival2full.getMinutes()).slice(-2)
                            // $('#firstDeparture').html("Avgang " + departure1D)
                            $('#secondStationTime').html("")
                            $('#thirdStationTime').html("")
                            $('#fourthStationTime').html("")
                            $('#lastStationTime').html(arrival2Time);
                            $('#secondStationName').html("");
                            $('#thirdStationName').html("");
                            $('#fourthStationName').html("");
                            $('#lastStationName').html(result["stopsAfter"][1]["name"]);
                            $('#dot2').removeClass()
                            $('#dot3').removeClass()
                            $('#dot4').removeClass()
                            $('#dot5').addClass("stationDot")
                            $('#lineCenter').addClass("routeLineCenter")
                            $('#lineAfter').addClass("routeLineAfter full")


                        } else if (result["stopsAfter"][0] != undefined) {
                            // $('#firstDeparture').html("")
                            $('#lineCenter').removeClass()
                            $('#lineAfter').removeClass()
                            $('#secondStationTime').html("")
                            $('#thirdStationTime').html("")
                            $('#fourthStationTime').html("")
                            $('#lastStationTime').html("");
                            $('#secondStationName').html("");
                            $('#thirdStationName').html("");
                            $('#fourthStationName').html("");
                            $('#lastStationName').html("");
                            $('#dot2').removeClass()
                            $('#dot3').removeClass()
                            $('#dot4').removeClass()
                            $('#dot5').removeClass()
                            $('#lineCenter').removeClass()
                            $('#lineAfter').removeClass()

                        }
                    } else {
                        $('#doorImage').css("display", "none")
                        $('#doorText').css("display", "none")
                        $('#news').css("display", "unset")
                        $('#firstDeparture').html("Unable to fetch data or train not running in the next 15 hours.")
                        $('#firstDeparture').addClass("normalSpacing")
                        $('#firstStationTime').html("")
                        $('#firstStationName').html("Error loading content")

                        $('#dot2').removeClass()
                        $('#secondStationTime').html("")
                        $('#secondStationName').html("");

                        $('#dot3').removeClass()
                        $('#thirdStationTime').html("")
                        $('#thirdStationName').html("");

                        $('#dot4').removeClass()
                        $('#fourthStationTime').html("")
                        $('#fourthStationName').html("");

                        $('#dot5').removeClass()
                        $('#lastStationTime').html("")
                        $('#lastStationName').html("")

                        $('#lineBefore').addClass("routeLineBefore")
                        $('#lineCenter').removeClass()
                        $('#lineAfter').removeClass()
                    }
                    $('#line').html(line)
                    $('#line').removeClass()
                    $('#line').addClass("linebox flirt " + line)
                }
                previousTime = thisTime;
            }
        })
    }

    function setTime() {
        dt = new Date();
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