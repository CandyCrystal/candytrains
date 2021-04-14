<?php
include "./config/global.php";
include './config/connectNew.php';
include './database/getPlatformData.php';
$platform = $_GET["platform"];

if ($platform == "") {
    $platform = 1;
}

$station = $_GET["station"];

if ($station == "") {
    $station = 217;
}

$platformInfoQuery = new getPlatformData($databaseConnection);
$platformInfo = $platformInfoQuery->getPlatformInfo($station, $platform);
$platformLength = $platformInfo["platformLength"];
$platformHasSectors = $platformInfo["platformHasSectors"];
// echo $platformLength;
$side = $_GET["side"];

if ($side != "left") {
    $side = "right";
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Platform departures - CandyTrains</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/platform.css">
    <link rel="icon" href="https://files.candycryst.com/assets/candyTransport/profile.png" type="image/png" />
    <meta name="description" content="Platform display">
    <script src="https://kit.fontawesome.com/185bd08920.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
</head>

<body class="display">
    <div class="departureLineCompany" id="departureLineCompany">
        <img id="line" class="departureImage">
        <img id="operator" class="departureImage">
    </div>
    <div id="currentTime" class="localTime"></div>

    <div id="destination" class="destination">&nbsp</div>
    <div id="departureTime" class="departureTime"></div>

    <div id="currentRemarks" class="departureRemarks"></div>
    <div id="currentDepartureNewTime" class="newTime"></div>
    <div id="currentDepartureNewTimeText" class="newTimeText"><b>Ny tid</b> New time</div>
    <div class="trainDisplay" id="trainDisplay">
        <div id="trainDisplayTrain" class="trainDisplayTrain">
        </div>
        <div id="sectorLettersLeft" class="sectorLetterRow">
            <div class="sectorLetter">A</div>
            <div class="sectorLetter">B</div>
            <div class="sectorLetter">C</div>
            <div class="sectorLetter">D</div>
            <div class="sectorLetter">E</div>
            <div class="sectorLetter">F</div>
            <div class="sectorLetter">G</div>
            <div class="sectorLetter">H</div>
        </div>
        <div id="sectorLettersNo" class="sectorLetterRow">
            <div class="sectorLetter"></div>
            <div class="sectorLetter"></div>
            <div class="sectorLetter"></div>
            <div class="sectorLetter"></div>
            <div class="sectorLetter"></div>
            <div class="sectorLetter"></div>
            <div class="sectorLetter"></div>
            <div class="sectorLetter"></div>
        </div>
        <div id="sectorLettersRight" class="sectorLetterRow">
            <div class="sectorLetter">H</div>
            <div class="sectorLetter">G</div>
            <div class="sectorLetter">F</div>
            <div class="sectorLetter">E</div>
            <div class="sectorLetter">D</div>
            <div class="sectorLetter">C</div>
            <div class="sectorLetter">B</div>
            <div class="sectorLetter">A</div>
        </div>
    </div>
    <div class="secondDeparture">
        <div id="secondDepartureTime" class="time"></div>
        <div id="secondDepartureDestination" class="destination"></div>
        <img id='secondDepartureLine' class='line'>
        <div id="secondDepartureNewTime" class="newTime"></div>
        <div id="secondDepartureNewTimeText" class="newTimeText"><b>Ny tid</b> New time</div>
    </div>


    <div class="departureNoBoardingText" id="noBoardingArrival"><b>Ankomst</b> Arrival</div>

    <div class="departureNoBoardingLine" id="noBoardingLine"></div>
    <div class="departureNoBoardingDestination" id="noBoardingDestination"></div>
    <div id="noBoardingTime" class="departureNoBoardingTime"></div>
    <div id="noBoardingNewTime" class="departureNoBoardingNewTime">14:58</div>
    <div id="noBoardingNewTimeText" class="departureNoBoardingNewTimeText"><b>Ny tid</b> New time</div>
    </div>

    <div class="departureNoBoardingDontBoard" id="noBoardingText"><b>Ingen påstigning</b><br />Please
        do not board
    </div>

    <div class="cancelledText" id="cancelledText"><b>Innstilt</b> Cancelled</div>

</body>
<script>
    var side = "<?php echo $side ?>";
    var platformLength = "<?php echo $platformLength ?>"
    var hasSectors = "<?php echo $platformHasSectors ?> "
    console.log("Platform Length: " + hasSectors)
    if (hasSectors == 0) {
        sectorSide = "sectorLettersNo"
    } else if (side == "right") {
        sectorSide = "sectorLettersRight"
    } else {
        side = "left"
        sectorSide = "sectorLettersLeft"
    }
    var englishText = "";
    var norwegianText = "";

    var previousTime;
    var clockState;
    var languageState = 0;
    setTime()
    setInterval(function() {
        setTime()
    }, 1000);
    updateInfo();
    setInterval(function() {
        updateInfo()
    }, 8000);
    setText();
    setInterval(function() {
        setText()
    }, 4000);



    function trainDisplay(departure) {

        var trainInfo = departure.trainInfo
        var trainParts = trainInfo.trainParts;
        var direction = trainInfo.trainDirection
        var placement = trainInfo.trainStopPoint

        // console.log("Direction and placement: " + direction + " " + placement)

        var train = "";
        if (direction == "FROM") {
            train += '<div class="train" id="trainElement">'
        } else {
            train += '<div class="train" id="trainElement">'
        }
        var trainLength = 0;
        // console.log(departure.trainInfo.trainParts)
        if (departure.trainInfo.trainParts != undefined) {
            var numTrainParts = trainParts.length

            // console.log(numTrainParts)
            // }
            for (var i = 0; i < numTrainParts; i++) {
                var thisWagon = trainParts[i].wagons;
                trainLength += 10
                if (thisWagon != undefined) {
                    for (var j = 0; j < thisWagon.length; j++) {
                        // console.log("Wagon number: " + j)
                        var length = thisWagon[j].length;
                        // console.log("wagon length: " + length)
                        var state = thisWagon[j].state
                        var wagonState = "unknownOccupancy";
                        if (state == "CLOSED") {
                            wagonState = "closed"
                        } else {
                            switch (thisWagon[j].occupancy) {
                                case "1":
                                    wagonState = "lowOccupancy"
                                    break;
                                case "2":
                                    wagonState = "mediumOccupancy"
                                    break;
                                case "3":
                                    wagonState = "highOccupancy"
                                    break;
                            }
                        }
                        // console.log("Occupancy: " + thisWagon[j]["occupancy"]["#text"])
                        trainLength += parseFloat(length);

                        if (j == 0 && direction == "TO" && side == "left" || j == 0 && side == "right" && direction == "FROM") {
                            train += '<div class="trainDisplayFrontLeft ' + wagonState + ' "></div><div style="width: ' + ((length / 2.5) - 3.5) + 'vw;" class="trainDisplayWagon ' + wagonState + ' middle ' + length + 'm">'
                        } else if (j == 0 && direction == "FROM" && side == "left" || side == "right" && j == 0 && direction == "TO") {
                            train += '<div style="width: ' + (length / 2.5) + 'vw;" class="trainDisplayWagon backLeft ' + wagonState + " " + length + 'm">'
                        } else if (j + 1 == thisWagon.length && direction == "TO" && side == "left" || j + 1 == thisWagon.length && direction == "FROM" && side == "left") {
                            train += '<div style="width: ' + (length / 2.5) + 'vw;" class="trainDisplayWagon backRight ' + wagonState + " " + length + 'm">'
                        } else if (j + 1 == thisWagon.length && direction == "FROM" && side == "left" || j + 1 == thisWagon.length && direction == "TO" && side == "left") {
                            train += '<div style="width: ' + ((length / 2.5) - 3.5) + 'vw;" class="trainDisplayWagon frontRight ' + wagonState + " " + length + 'm">'
                        } else {
                            train += '<div style="width: ' + (length / 2.5) + 'vw;" class="trainDisplayWagon middle ' + wagonState + " " + length + 'm">'
                        }

                        var categories = [];

                        var numServices = thisWagon[j].services.length
                        if (state == "CLOSED") {
                            wagonState = "closed"
                            train += '<div class="trainIconClosed"><span class="fa-stack fa-2x"><i class="fas fa-circle fa-stack-1x" style="color:black"></i><i class="fas fa-ban fa-stack-1x" style="color:white"></i></span></div>'
                        } else {
                            // console.log("number of services: " + numServices)
                            for (var k = 0; k < numServices; k++) {
                                var thisService = thisWagon[j].services[k].category;
                                switch (thisService[k]) {
                                    case 'toddlerCompartment':
                                        categories.push('<div class="trainIcon"><i style="color:white;" class="fas fa-baby-carriage"></i></div>')
                                        break;
                                    case "wheelchair":
                                        categories.push('<div class="trainIcon"><i style="color:white;" class="fas fa-wheelchair"></i></div>')
                                        break;
                                    case "quiet":
                                        categories.push('<div class="trainIcon"><i style="color:white;" class="fas fa-volume-mute"></i></div>')
                                        break;
                                }
                                if (categories.length != 0) {
                                    train += '<div style="margin-left: -' + (categories.length * 1.25) + 'vw; width: ' + (categories.length * 2) + '" class="trainIcons ">'
                                    for (var u = 0; u < categories.length; u++) {
                                        train += categories[u]
                                    }
                                    train += "</div>"
                                }
                            }
                        }
                        train += "</div>"
                        if (j == thisWagon.length - 1 && direction == "FROM" && side == "left" || j == thisWagon.length - 1 && direction == "TO" && side == "right") {
                            train += '<div class="trainDisplayFrontRight ' + wagonState + ' "></div>'
                        }
                        // console.log(trainLength)
                    }
                }
            }
            document.getElementById("trainDisplayTrain").innerHTML = train
            document.getElementById("trainElement").style.width = trainLength / 2.5 + "vw"

            if (hasSectors == 0) {
                document.getElementById("trainElement").style.right = (platformLength / 2 - trainLength / 2) / 2.5 + "vw"
            } else if (direction == "FROM" && side == "left") {
                document.getElementById("trainElement").style.right = (platformLength - placement) / 2.5 + "vw"
            } else if (direction == "TO" && side == "right") {
                document.getElementById("trainElement").style.right = placement / 2.5 + "vw"
            } else if (direction == "FROM" && side == "right") {
                document.getElementById("trainElement").style.left = (platformLength - placement) / 2.5 + "vw"
            } else {
                document.getElementById("trainElement").style.left = placement / 2.5 + "vw"
            }
        }
    }

    function setText() {
        if (languageState == 1) {
            if (norwegianText != "") {
                $('#currentRemarks').html('<div class="warningYellow">' + norwegianText + '</div>')
            }
            languageState = 0
        } else {
            if (englishText != "") {
                $('#currentRemarks').html('<div class="warningYellow">' + englishText + '</div>')
            }
            languageState = 1
        }

    }

    function updateInfo() {
        $.ajax({
            url: "https://trains.candycryst.com/queries/platformQuery.php?station=<?php echo $station ?>&platform=<?php echo $platform ?>&side=<?php echo $side ?>",
            type: "POST",
            success: function(msg) {
                // console.log(msg)
                result = JSON.parse(msg);
                var thisTime = result[0];
                var eq = JSON.stringify(thisTime) === JSON.stringify(previousTime);
                if (eq == false) {
                    var dt = new Date();
                    var second = ("0" + dt.getSeconds()).slice(-2)
                    var minute = ("0" + dt.getMinutes()).slice(-2);
                    var hour = ("0" + dt.getHours()).slice(-2)
                    englishText = result[0].englishText
                    norwegianText = result[0].norwegianText
                    resetDisplay();
                    previousTime = result[0];
                    console.log(hour + ":" + minute + ":" + second + " | Updated platform <?php echo $platform ?>")
                    trainDisplay(result[0])
                    switch (result[0].status) {
                        case "normal":
                        case undefined:
                            $('#departureTime').attr("style", "display: block;")
                            $('#departureLineCompany').attr("style", "display: block;")
                            $('#destination').attr("style", "display: block;")
                            $('#trainDisplayTrain').attr("style", "display: block;")
                            $('#departureTime').html(result[0].time)
                            if (result[0].line != "" && result[0].line != undefined) {
                                $('#line').attr('src', "./assets/media/lines/centered/" + result[0].line + ".svg")
                            }
                            if (result[0].time != result[0].newTime && result[0].newTime != "") {
                                $('#currentDepartureNewTime').attr("style", "display: block;")
                                $('#currentDepartureNewTime').html(result[0].newTime)
                                $('#currentDepartureNewTimeText').attr("style", "display: block;")
                            }
                            $('#destination').html(result[0].destination)
                            if (result[0].viaText != null) {
                                $('#currentRemarks').html(result[0].viaText)
                            }
                            $('#currentRemarks').attr("style", "display: block;")
                            if (norwegianText != "") {
                                if (languageState == 1 || englishText == "") {
                                    $('#currentRemarks').html('<div class="warningYellow">' + norwegianText + '</div>')
                                } else {
                                    $('#currentRemarks').html('<div class="warningYellow">' + englishText + '</div>')
                                }
                            } else if (result[0].viaText != null) {
                                $('#currentRemarks').html(result[0].viaText)
                            }
                            if (languageState == 1) {
                                if (norwegianText != "") {
                                    $('#currentRemarks').html('<div class="warningYellow">' + norwegianText + '</div>')
                                }
                            } else {
                                if (englishText != "") {
                                    $('#currentRemarks').html('<div class="warningYellow">' + englishText + '</div>')
                                }
                            }
                            $('#operator').attr('src', "./assets/media/companies/" + result[0].operator + ".svg")

                            $('#' + sectorSide).attr("style", "display: block;")
                            if (result[1] != "" && result[1] != undefined) {
                                $('#trainDisplay').attr("style", "display: block;")
                                $('#secondDepartureTime').attr("style", "display: block;")
                                $('#secondDepartureTime').html(result[1].time);
                                if (result[1].time != result[1].newTime && result[1].newTime != "") {
                                    $('#secondDepartureNewTime').attr("style", "display: block;")
                                    $('#secondDepartureNewTime').html(result[1].newTime)
                                    $('#secondDepartureNewTimeText').attr("style", "display: block;")
                                }

                                if (result[1].line != "") {
                                    $('#secondDepartureDestination').attr("style", "display: block;")
                                    if (result[1].line != "" && result[1].line != undefined) {
                                        $('#secondDepartureLine').attr("style", "display: block;")
                                        $('#secondDepartureLine').attr("src", "./assets/media/lines/centered/" + result[1].line + ".svg")
                                    }
                                    $('#secondDepartureDestination').html(result[1].destination);
                                } else {
                                    $('#secondDepartureTime').html(result[1].time + "&nbsp&nbsp" + result[1].destination);
                                }
                            } else {
                                $('#trainDisplay').attr("style", "display: block; top: 47.8vw;")
                            }

                            break;
                        case "cancelled":
                            $('#departureLineCompany').attr("style", "display: block;")
                            $('#cancelledText').attr("style", "display: block;")
                            $('#destination').attr("style", "display: block;")
                            $('#departureTime').attr("style", "display: block;")
                            $('#departureTime').html('<del id="time">' + result[0].time + '</del>')
                            if (result[0].line != "" || result[0].line != undefined) {
                                $('#line').attr('src', "./assets/media/lines/centered/" + result[0].line + ".svg")
                            }
                            $('#destination').html(result[0].destination)
                            $('#operator').attr('src', "./assets/media/companies/" + result[0].operator + ".svg")
                            $('#' + side).attr("style", "display: block;")
                            if (result[1].time != "" && result[1].time != undefined) {
                                $('#trainDisplay').attr("style", "display: block;")
                                $('#secondDepartureTime').attr("style", "display: block;")
                                $('#secondDepartureTime').html(result[1].time);
                                if (result[1].time != result[1].newTime && result[1].newTime != "") {
                                    $('#secondDepartureNewTime').attr("style", "display: block;")
                                    $('#secondDepartureNewTime').html(result[1].newTime)
                                    $('#secondDepartureNewTimeText').attr("style", "display: block;")
                                }

                                if (result[1].line != "") {
                                    $('#secondDepartureDestination').attr("style", "display: block;")
                                    if (result[1].line != "" && result[1].line != undefined) {
                                        $('#secondDepartureLine').attr("style", "display: block;")
                                        $('#secondDepartureLine').attr("src", "./assets/media/lines/centered/" + result[1].line + ".svg")
                                    }
                                    $('#secondDepartureDestination').html(result[1].destination);
                                } else {
                                    $('#secondDepartureTime').html(result[1].time + "&nbsp&nbsp" + result[1].destination);
                                }
                            } else {
                                $('#trainDisplay').attr("style", "display: block; top: 47.8vw;")
                            }
                            break;
                        case "noBoarding":
                            $('#noBoardingText').attr("style", "display: block;")
                            $('#noBoardingArrival').attr("style", "display: block;")
                            $('#noBoardingTime').attr("style", "display: block;")
                            if (result[0].line != "" || result[0].line != undefined) {
                                $('#noBoardingLine').attr("style", "display: block;")
                                $('#noBoardingLine').html("<img class='image' src='./assets/media/lines/centered/" + result[0].line + ".svg'>")
                            }
                            if (result[0].time != result[0].newTime) {
                                $('#noBoardingNewTime').attr("style", "display: block;")
                                $('#noBoardingNewTime').html(result[0].newTime)
                                $('#noBoardingNewTimeText').attr("style", "display: block;")
                            }
                            $('#noBoardingDestination').attr("style", "display: block;")
                            $('#noBoardingTime').html(result[0].time)
                            $('#noBoardingDestination').html(result[0].origin)
                            break;
                    }
                }
            }
        })
    }

    function setTime() {
        var dt = new Date();
        var minute = ("0" + dt.getMinutes()).slice(-2);
        var hour = ("0" + dt.getHours()).slice(-2)
        if (clockState == 1) {
            $('#currentTime').html('<i class="far fa-clock"></i>&nbsp' + hour + ":" + minute);
            clockState = 0
        } else {
            $('#currentTime').html('<i class="far fa-clock"></i>&nbsp' + hour + "&nbsp" + minute)
            clockState = 1
        }
    }

    function resetDisplay() {
        document.getElementById("trainDisplayTrain").innerHTML = ""
        $('#trainDisplay').attr("style", "display: none;")
        $('#departureTime').attr("style", "display: none;")
        $('#destination').attr("style", "display: none;")
        $('#noBoardingNewTime').attr("style", "display: none;")
        $('#noBoardingNewTimeText').attr("style", "display: none;")
        $('#secondDepartureNewTime').attr("style", "display: none;")
        $('#secondDepartureNewTimeText').attr("style", "display: none;")
        $('#cancelledText').attr("style", "display: none;")
        $('#currentRemarks').attr("style", "display: none;")
        $('#secondDepartureImage').attr("style", "display: none;")
        $('#secondDepartureDestination').attr("style", "display: none;")
        $('#secondDepartureLine').attr("style", "display: none;")
        $('#secondDepartureTime').attr("style", "display: none;")
        $('#departureLineCompany').attr("style", "display: none;")
        $('#noBoardingText').attr("style", "display: none;")
        $('#noBoardingArrival').attr("style", "display: none;")
        $('#noBoardingTime').attr("style", "display: none;")
        $('#noBoardingLine').attr("style", "display: none;")
        $('#noBoardingDestination').attr("style", "display: none;")
        $('#currentDepartureNewTime').attr("style", "display: none;")
        $('#currentDepartureNewTimeText').attr("style", "display: none;")

    }
</script>

</html>