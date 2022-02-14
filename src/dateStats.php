<?php
// header("Content-Type: application/json");
$pageRequiresTrainManager = true;
include "./config/connect.php";
include "./config/session.php";
include "./config/navbar.php";
$navbarClass = new getNavbar(isset($_SESSION['login_user']), $userIsAdmin, "home");
$navbar = $navbarClass->getNavbar();

include "./database/getAdminData.php";
$adminDataQuery = new getAdminData($databaseConnection);
$thisDate = $_GET["date"];

$begin = new DateTime(Date('Y-m-d', strtotime($thisDate . "-3 days")));

$end = new DateTime(Date('Y-m-d', strtotime($thisDate . "+4 days")));
$end2 = new DateTime(Date('Y-m-d', strtotime($thisDate . "+3 days")));

$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);




$date = new DateTime($thisDate);

$dateStats = $adminDataQuery->getSingleDateStats($begin->format("Y-m-d"), $end2->format("Y-m-d"));
$dateStats = $dateStats["total"];





?>

<!DOCTYPE html>
<html lang="en">

<head>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="./assets/css/main.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400&display=swap" rel="stylesheet">
        <title>CandyTrains</title>
        <link rel="icon" href="./assets/media/icon.png" type="image/png" />
        <link rel="stylesheet" href="./assets/css/trainList.css">
        <link rel="stylesheet" href="./assets/css/admin.css">

        <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
        <meta property="og:type" content="website">
        <meta property="og:title" content="Candytrains" />
        <meta property="og:description" content="A website of all things norwegian railways!" />
        <meta property="og:url" content="https://trains.candycryst.com" />
        <meta property="og:image" content="https://trains.candycryst.com/assets/media/icon.png" />
        <meta name="theme-color" content="#c54365">
        <meta property="og:image" content="https://trains.candycryst.com/assets/media/icon.png" />

        <script src="https://kit.fontawesome.com/185bd08920.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="./assets/css/main.css">
        <link rel="stylesheet" href="./assets/css/topnav.css">
        <script src="./assets/js/topnav.js"></script>
    </head>
</head>

<body>
    <?php echo $navbar; ?>
    <div class="addRow">
        <div class="progress">
            <div class="bar" id="progressBar"></div>
        </div>
    </div>

    <h1>Admin page</h1>
    <table class="shortTable" id="toc">
        <tr>
            <th class="thisDay">Date</th>
            <?php

            foreach ($period as $dt) {
                if ($dt == $date) {
                    echo '<th class="thisDay">';
                } else {
                    echo '<th>';
                }
                if ($dt->format("m-d") == "12-24" || $dt->format("m-d") == "12-31" || $dt->format("m-d") == "05-17") {
                    echo "<a href='./dateStats.php?date=" . $dt->format("Y-m-d") . "'>" . $dt->format("d/m/y") . "&nbsp⭐</a></th>";
                } else if ($dt->format("l") == "Monday" || $dt->format("l") == "Tuesday" || $dt->format("l") == "Wednesday" || $dt->format("l") == "Thursday" || $dt->format("l") == "Friday") {
                    echo "<a href='./dateStats.php?date=" . $dt->format("Y-m-d") . "'>" . $dt->format("d/m/y") . "&nbsp🏢</a></th>";
                } else if ($dt->format("l") == "Saturday" || $dt->format("l") == "Sunday") {
                    echo "<a href='./dateStats.php?date=" . $dt->format("Y-m-d") . "'>" . $dt->format("d/m/y") . "&nbsp🌳</a></th>";
                }
            }
            ?>
        </tr>
        <tr>
            <th class="thisDay">Total&nbsptrains</th>
            <?php while ($row = mysqli_fetch_array($dateStats)) {
                if ($row["route_date"] == $thisDate) {
                    echo '<th class="thisDay">';
                } else {
                    echo '<th>';
                }
                echo $row["num"] ?></th>
            <?php } ?>
        </tr>

    </table>

    <script>
        <?php while ($row = mysqli_fetch_array($dateStats)) { ?>
            document.getElementById("d_<?php echo $row["route_date"] ?>").innerHTML = "<?php echo $row["num"] ?>"
        <?php } ?>
    </script>

    <!-- </table> -->
    <!-- <table id="statTable">
        <tr>
            <th rowspan="3">Progress</th>
            <th colspan="3">Progress</th>
            <th colspan="2">Time left</th>
            <th rowspan="3">Update</th>
            <th></th>
        </tr>
        <tr>
            <th id="progressStat" colspan="3">0/0</th>
            <th id="timeStat" colspan="2">0s</th>
            <td>
                <input type="number" required autocomplete="off" id="trainNumber" style="width: 7em" placeholder="Train number">
                <input type="number" autocomplete="off" id="bulkAmount" style="width: 8.5em" placeholder="Amount to check">
                <input type="submit" value="Bulk update" class="button" onclick="updateTrains()">
            </td>
        </tr>
        <tr>
            <th id="greenStat" class="colorStat green">0</th>
            <th id="yellowStat" class="colorStat yellow">0</th>
            <th id="orangeStat" class="colorStat orange">0</th>
            <th id="redStat" class="colorStat red">0</th>
            <th id="blueStat" class="colorStat blue">0</th>
            <td>
                <input type="date" min="2021-01-01" required autocomplete="off" id="date">
                <input type="submit" value="Update all trains on date" class="button" onclick="updateExistingTrains()">
            </td>
        </tr>
        <tr>

        </tr>
        </tr>
    </table> -->

    <div id="statBox" class="statBox"></div>

</body>

</html>
<script>
    var trainData = []

    function sortData(element, index, array) {
        trainData[element.train_number] = element;
    }

    function flashProgressBar() {
        var bar = document.getElementById("progressBar")
        document.getElementById("progressBar").style.backgroundColor = "white"
        setTimeout(function(bar) {
            document.getElementById("progressBar").style.backgroundColor = ""
        }, 250);
        setTimeout(function(bar) {
            document.getElementById("progressBar").style.backgroundColor = "white"
        }, 500);
        setTimeout(function(bar) {
            document.getElementById("progressBar").style.backgroundColor = ""
        }, 750);
        setTimeout(function(bar) {
            document.getElementById("progressBar").style.backgroundColor = "white"
        }, 1000);
        setTimeout(function(bar) {
            document.getElementById("progressBar").style.backgroundColor = ""
        }, 1250);
    }
    var numBlue = 0;
    var numRed = 0;
    var numGreen = 0;
    var numYellow = 0;
    var numOrange = 0;
    var todayAmount;

    function updateExistingTrains() {
        $.ajax({
            url: "./database/getTrainData.php?type=trainNums",
            type: "POST",
            success: function(msg) {
                console.log("g")
                var date = document.getElementById("date").value
                if (date == "1730-01-01" || date == "") {
                    alert("ERR")
                } else {
                    console.log(date)
                    todayAmount = document.getElementById("d_" + date).innerHTML
                    var trains = JSON.parse(msg);
                    var amount = trains.length
                    var time_left = amount * 0.3
                    trains.forEach(sortData)
                    document.getElementById("progressBar").style.width = "0%"
                    var counter = 0;
                    numBlue = 0;
                    numRed = 0;
                    numGreen = 0;
                    numYellow = 0;
                    numOrange = 0;

                    // document.getElementById("statTable").innerHTML = "<tr><td colspan='2'>" + counter + "/" + amount + "</td><th colspan='3'>" + roundNumber(time_left, 1) + "s" + "</th></tr><tr><td>🟢" + window["numGreen"] + "</td><td>🟡" + window["numYellow"] + "</td><td>🔵" + window["numBlue"] + "</td><td>🟠" + window["numOrange"] + "</td><td>🔴" + window["numRed"] + "</td></tr>"
                    document.getElementById("progressStat").innerHTML = counter + "/" + amount
                    document.getElementById("timeStat").innerHTML = roundNumber(time_left, 1) + "s"
                    const interval = setInterval(function() {
                        updateAllData(trains[counter], counter, amount, date, time_left)
                        time_left = time_left - 0.3
                        if (counter == amount - 1) {

                            flashProgressBar()
                            clearInterval(interval)
                        }
                        counter++
                    }, 300);
                }
            }
        })
    }

    function roundNumber(num, scale) {
        if (!("" + num).includes("e")) {
            return +(Math.round(num + "e+" + scale) + "e-" + scale);
        } else {
            var arr = ("" + num).split("e");
            var sig = ""
            if (+arr[1] + scale > 0) {
                sig = "+";
            }
            return +(Math.round(+arr[0] + "e" + sig + (+arr[1] + scale)) + "e-" + scale);
        }
    }
    async function updateAllData(trainNumber, c, amount, date, time_left) {
        counter = c;
        request = $.ajax({
            url: `./database/manageTrains.php`,
            type: "post",
            data: {
                "action": "update_one",
                "id": trainNumber,
                "date": date
            }
        });

        request.done(function(response, textStatus, jqXHR) {
            // Log a message to the console
            console.log(counter + ": " + response);
            var percent = (counter / amount) * 100
            document.getElementById("progressBar").style.width = percent + "%"

            if (response.includes("🔴")) {
                window["numRed"]++
                document.getElementById("statBox").innerHTML += "<div class='dotArt'>🔴</div>"
            } else if (response.includes("🔵")) {
                window["numBlue"]++
                document.getElementById("statBox").innerHTML += "<div class='dotArt'>🔵</div>"
            } else if (response.includes("🟡")) {
                window["numYellow"]++
                document.getElementById("statBox").innerHTML += "<div class='dotArt'>🟡</div>"
            } else if (response.includes("🟢")) {
                window["todayAmount"]++
                window["numGreen"]++
                document.getElementById("statBox").innerHTML += "<div class='dotArt'>🟢</div>"
            } else if (response.includes("🟠")) {
                window["todayAmount"]++
                window["numOrange"]++
                document.getElementById("statBox").innerHTML += "<div class='dotArt'>🟠</div>"
            }

            document.getElementById("greenStat").innerHTML = window["numGreen"]
            document.getElementById("yellowStat").innerHTML = window["numYellow"]
            document.getElementById("orangeStat").innerHTML = window["numOrange"]
            document.getElementById("redStat").innerHTML = window["numRed"]
            document.getElementById("blueStat").innerHTML = window["numBlue"]
            document.getElementById("progressStat").innerHTML = counter + "/" + amount
            document.getElementById("timeStat").innerHTML = roundNumber(time_left, 1) + "s"


            // var thisDate = document.getElementById("date").value
            document.getElementById("d_" + date).innerHTML = window["todayAmount"]

            // document.getElementById("statTable").innerHTML = "<tr><td colspan='2'>" + counter + "/" + amount + "</td><th colspan='3'>" + roundNumber(time_left, 1) + "s" + "</th></tr><tr><td>🟢" + window["numGreen"] + "</td><td>🟡" + window["numYellow"] + "</td><td>🔵" + window["numBlue"] + "</td><td>🟠" + window["numOrange"] + "</td><td>🔴" + window["numRed"] + "</td></tr>"
            document.title = counter + "/" + amount + " | " + roundNumber(time_left, 1) + "s"
        });

        request.fail(function(jqXHR, textStatus, errorThrown) {
            // Log the error to the console
            console.error(
                "The following error occurred: " +
                textStatus, errorThrown
            );
        });
        request.always(function() {
            // Reenable the inputs
        });
    }
</script>