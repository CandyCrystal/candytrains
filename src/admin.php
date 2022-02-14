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
$dateStatsAt = $adminDataQuery->getDateStats();
$dateStatsPt = $adminDataQuery->getDateStatsPT();
$dateStatsGt = $adminDataQuery->getDateStatsGT();
// include "./TESTDATA.php";
$begin = new DateTime('2021-12-01');

$end = new DateTime(Date('Y-m-d', strtotime("+11 days")));

$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);


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
        <link rel="stylesheet" href="./assets/css/footer.css">

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
            <th></th>
            <th colspan="21">Calendar</th>
        </tr>
        <tr>
            <th></th>
            <th colspan="3" class="Monday">Mon</th>
            <th colspan="3" class="Tuesday">Tue</th>
            <th colspan="3" class="Wednesday">Wed</th>
            <th colspan="3" class="Thursday">Thu</th>
            <th colspan="3" class="Friday">Fri</th>
            <th colspan="3" class="Saturday">Sat</th>
            <th colspan="3" class="Sunday">Sun</th>
        </tr>
        <tr>
            <th></th>
            <th class="pt Monday">PT</th>
            <th class="gt Monday">GT</th>
            <th class="at Monday">all</th>
            <th class="pt Tuesday">PT</th>
            <th class="gt Tuesday">GT</th>
            <th class="at Tuesday">all</th>
            <th class="pt Wednesday">PT</th>
            <th class="gt Wednesday">GT</th>
            <th class="at Wednesday">all</th>
            <th class="pt Thursday">PT</th>
            <th class="gt Thursday">GT</th>
            <th class="at Thursday">all</th>
            <th class="pt Friday">PT</th>
            <th class="gt Friday">GT</th>
            <th class="at Friday">all</th>
            <th class="pt Saturday">PT</th>
            <th class="gt Saturday">GT</th>
            <th class="at Saturday">all</th>
            <th class="pt Sunday">PT</th>
            <th class="gt Sunday">GT</th>
            <th class="at Sunday">all</th>
        </tr>
        <tr>
            <th rowspan="2">Dec</th>
            <th></th>
            <th rowspan='2' colspan="2">Legend</th>
            <th colspan="3" class="pt">Passenger trains</th>
            <!-- <th colspan="6"></th>
            <td colspan="3">pre 31/10/2021</td>
            <th colspan="2"></th> -->

            <?php


            $star_dates_yearly = ["01-01", "05-01", "05-17", "12-24", "12-25", "12-26", "12-31"];
            $star_dates = ["2022-04-14", "2022-04-15",];
            $construction_dates = ["2022-01-15", "2022-01-16", "2022-02-12", "2022-02-13", "2022-04-14", "2022-04-15", "2022-04-16", "2022-04-17", "2022-05-26", "2022-05-27", "2022-05-28", "2022-05-29"];
            $row1;
            // $row2 = "</tr><tr><th></th><th colspan='3' class='gt'>Cargo trains</th><th colspan='6'></th><td class='pt right' id='dp_2021-10-30'>0</td><td class='gt right' id='dg_2021-10-30'></td><td class='at right' id='d_2021-10-30'></td><th colspan='3'></th>";
            $row2 = "</tr><tr><th></th><th colspan='3' class='gt'>Cargo trains</th>";


            foreach ($period as $dt) {
                if ($dt->format("l") == "Monday") {
                    echo $row1 . $row2;
                    $row1 = "</tr><tr><th class='thisDay' rowspan='2'>" . $dt->format("M") . '</th>';
                    $row2 = "</tr><tr>";
                }
                $row1 .= "<th colspan='3' class='thisDay " . $dt->format("l") . "'><b>";
                if (in_array($dt->format("m-d"), $star_dates_yearly) || in_array($dt->format("Y-m-d"), $star_dates)) {
                    $row1 .= $dt->format("d") . "&nbsp⭐</b></th>";
                } else if (in_array($dt->format("Y-m-d"), $construction_dates)) {
                    $row1 .=  $dt->format("d")  .  "&nbsp🚧</b></th>";
                } else if ($dt->format("l") == "Monday" || $dt->format("l") == "Tuesday" || $dt->format("l") == "Wednesday" || $dt->format("l") == "Thursday" || $dt->format("l") == "Friday") {
                    $row1 .=  $dt->format("d") . "&nbsp🏢</b></th>";
                } else if ($dt->format("l") == "Saturday" || $dt->format("l") == "Sunday") {
                    $row1 .=  $dt->format("d") . "&nbsp🌳</b></th>";
                }
                $row2 .= "<td class='right pt' id='dp_" . $dt->format("Y-m-d") . "'></td>";
                $row2 .= "<td class='right gt' id='dg_" . $dt->format("Y-m-d") . "'></td>";
                $row2 .= "<td class='right at " . $dt->format("l") . "' id='d_" . $dt->format("Y-m-d") . "'></td>";
            }
            switch (Date('l', strtotime("+10 days"))) {
                case "Monday":
                    $row1 .= "<th class='Tuesday' colspan='3'></th><th class='Wednesday' colspan='3'></th><th class='Thursday' colspan='3'></th><th class='Friday' colspan='3'></th><th class='Saturday' colspan='3'></th><th class='Sunday' colspan='3'></th>";
                    $row2 .= "<th class='Tuesday' colspan='3'></th><th class='Wednesday' colspan='3'></th><th class='Thursday' colspan='3'></th><th class='Friday' colspan='3'></th><th class='Saturday' colspan='3'></th><th class='Sunday' colspan='3'></th>";
                    break;
                case "Tuesday":
                    $row1 .= "<th class='Wednesday' colspan='3'></th><th class='Thursday' colspan='3'></th><th class='Friday' colspan='3'></th><th class='Saturday' colspan='3'></th><th class='Sunday' colspan='3'></th>";
                    $row2 .= "<th class='Wednesday' colspan='3'></th><th class='Thursday' colspan='3'></th><th class='Friday' colspan='3'></th><th class='Saturday' colspan='3'></th><th class='Sunday' colspan='3'></th>";
                    break;
                case "Wednesday":
                    $row1 .= "<th class='Thursday' colspan='3'></th><th class='Friday' colspan='3'></th><th class='Saturday' colspan='3'></th><th class='Sunday' colspan='3'></th>";
                    $row2 .= "<th class='Thursday' colspan='3'></th><th class='Friday' colspan='3'></th><th class='Saturday' colspan='3'></th><th class='Sunday' colspan='3'></th>";
                    break;
                case "Thursday":
                    $row1 .= "<th class='Friday' colspan='3'></th><th class='Saturday' colspan='3'></th><th class='Sunday' colspan='3'></th>";
                    $row2 .= "<th class='Friday' colspan='3'></th><th class='Saturday' colspan='3'></th><th class='Sunday' colspan='3'></th>";
                    break;
                case "Friday":
                    $row1 .= "<th class='Saturday' colspan='3'></th><th class='Sunday' colspan='3'></th>";
                    $row2 .= "<th class='Saturday' colspan='3'></th><th class='Sunday' colspan='3'></th>";
                    break;
                case "Saturday":
                    $row1 .= "<th class='Sunday' colspan='3'></th>";
                    $row2 .= "<th class='Sunday' colspan='3'></th>";
                    break;
            }
            echo $row1 . $row2;

            echo $at;
            ?>
    </table>
    <script>
        <?php while ($row = mysqli_fetch_array($dateStatsAt)) { ?>
            document.getElementById("d_<?php echo $row["route_date"] ?>").innerHTML = "<?php echo $row["num"] ?>"
        <?php } ?>
        <?php while ($row = mysqli_fetch_array($dateStatsPt)) { ?>
            document.getElementById("dp_<?php echo $row["route_date"] ?>").innerHTML = "<?php echo $row["num"] ?>"
        <?php } ?>
        <?php while ($row = mysqli_fetch_array($dateStatsGt)) { ?>
            document.getElementById("dg_<?php echo $row["route_date"] ?>").innerHTML = "<?php echo $row["num"] ?>"
        <?php } ?>
    </script>
    <table>
        <tr>
            <th colspan="5">Check for new trains <a href="./TESTDATA.php">Here</a></th>
        </tr>
    </table>
    <table id="statTable">
        <tr>
            <th rowspan="3">Progress</th>
            <th colspan="2">Progress</th>
            <th>num</th>
            <th colspan="2">Time left</th>
            <th rowspan="3">Update</th>
            <th></th>
        </tr>
        <tr>
            <th id="progressStat" colspan="2">0/0</th>
            <th id="numStat"></th>
            <th id="timeStat" colspan="2">0s</th>
            <td>
                <input type="number" required autocomplete="off" id="trainNumber" style="width: 7em" placeholder="Train number">
                <input type="number" autocomplete="off" id="bulkAmount" style="width: 8.5em" placeholder="Amount to check">
                <input type="submit" value="Bulk update" class="button" onclick="updateTrains()">
            </td>
        </tr>
        <tr>
            <!-- <th>🟢</th> -->
            <th id="greenStat" class="colorStat green">0</th>
            <!-- <th>🟡</th> -->
            <th id="yellowStat" class="colorStat yellow">0</th>
            <!-- <th>🟠</th> -->
            <th id="orangeStat" class="colorStat orange">0</th>
            <!-- <th>🔴</th> -->
            <th id="redStat" class="colorStat red">0</th>
            <!-- <th>🔵</th> -->
            <th id="blueStat" class="colorStat blue">0</th>
            <td>
                <input type="date" min="2021-12-01" max="<?php echo Date('Y-m-d', strtotime("+10 days")) ?>" required autocomplete="off" id="date">
                <input type="submit" value="Update all trains on date" class="button" onclick="updateExistingTrains()">
            </td>
        </tr>
    </table>

    <div id="statBox" class="statBox"></div>
    <footer class="footerParent">
        <div class="infoFooter">
            <h1>CandyCryst | Trains</h1>

            <div class="footerLink">
                <p>Websites by CandyCrystal</p>
            </div>
            <div class="footerLink">
                <ul>
                    <li><a href="https://www.candycryst.com">CandyCryst.com</a></li>
                    <li><a href="https://trains.candycryst.com">CandyTrains</a></li>
                    <li><a href="https://transportmap.candycryst.com">Transport map</a></li>
                    <li><a href="https://www.minrule.com">Minrule.com</a></li>
                    <li><a href="https://www.candytransport.com">CandyTransport</a></li>
                </ul>
            </div>
        </div>
    </footer>
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
            url: "./database/getTrainData.php?type=existing_train_numbers",
            type: "POST",
            success: function(msg) {
                console.log("g")
                var date = document.getElementById("date").value
                if (date == "1970-01-01" || date == "") {
                    alert("ERR")
                } else {
                    console.log(date)
                    todayAmount = document.getElementById("d_" + date).innerHTML
                    document.getElementById("numStat").innerHTML = todayAmount
                    console.log(msg)
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
                    document.getElementById("statBox").innerHTML = ""
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
                document.getElementById("statBox").innerHTML += "<div class='float colorStat red'>" + response.substr(19) + "</div>"
            } else if (response.includes("🔵")) {
                window["numBlue"]++
                document.getElementById("statBox").innerHTML += "<div class='float colorStat blue'>" + response.substr(19) + "</div>"
            } else if (response.includes("🟡")) {
                window["numYellow"]++
                document.getElementById("statBox").innerHTML += "<div class='float colorStat yellow'>" + response.substr(19) + "</div>"
            } else if (response.includes("🟢")) {
                window["todayAmount"]++
                window["numGreen"]++
                document.getElementById("statBox").innerHTML += "<div class='float colorStat green'>" + response.substr(19) + "</div>"
            } else if (response.includes("🟠")) {
                window["todayAmount"]++
                window["numOrange"]++
                document.getElementById("statBox").innerHTML += "<div class='float colorStat orange'>" + response.substr(19) + "</div>"
            }
            var ct = counter
            document.getElementById("greenStat").innerHTML = window["numGreen"]
            document.getElementById("yellowStat").innerHTML = window["numYellow"]
            document.getElementById("orangeStat").innerHTML = window["numOrange"]
            document.getElementById("redStat").innerHTML = window["numRed"]
            document.getElementById("blueStat").innerHTML = window["numBlue"]
            document.getElementById("progressStat").innerHTML = (ct + 1) + "/" + amount
            document.getElementById("timeStat").innerHTML = roundNumber(time_left, 1) + "s"


            // var thisDate = document.getElementById("date").value
            document.getElementById("numStat").innerHTML = window["todayAmount"]
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