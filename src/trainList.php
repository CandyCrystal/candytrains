<?php
$maintenance = false;
$pageRequiresLogin = false;
include "./config/connect.php";
$userCanManageTrains = 0;
include "./config/session.php";

include "./config/navbar.php";
$navbarClass = new getNavbar(isset($_SESSION['login_user']), $userIsAdmin, "trainList");
$navbar = $navbarClass->getNavbar();

$page = $_GET["page"];
$date = $_GET["date"];
if ($date == "") {
    $date = Date('Y-m-d', strtotime("today"));
}
$showInactive = $_GET["inactive"];
// $showInactive = "true";
if ($page == "" || $page <= 0) {
    $page = 0;
} else if ($page > 99) {
    $page = 99;
}
$prevNum = ($page * 1000);
$nextNum = ($page * 1000) + 999;

$sql = "SELECT floor(train_number/1000) p FROM routes WHERE train_number < $prevNum ORDER BY train_number DESC LIMIT 1";
$ex = $databaseConnection->query($sql);

while ($r = mysqli_fetch_assoc($ex)) {
    $thisRow = $r;
    $prevPage = $thisRow["p"];
}

$sql = "SELECT floor(train_number/1000) p FROM routes WHERE train_number > $nextNum ORDER BY train_number ASC LIMIT 1";
$ex = $databaseConnection->query($sql);

while ($r = mysqli_fetch_assoc($ex)) {
    $thisRow = $r;
    $nextPage = $thisRow["p"];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/lineImages.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="./assets/css/main.css">
    <link rel="stylesheet" href="./assets/css/trainList.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
    <link rel="icon" href="./assets/media/icon.png" type="image/png" />

    <title>Train list</title>
    <script src="https://kit.fontawesome.com/185bd08920.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./assets/css/main.css">
    <link rel="stylesheet" href="./assets/css/topnav.css">
    <script src="./assets/js/topnav.js"></script>

</head>
<?php if ($maintenance == true) {
    echo '<body id="body">' . $navbar;
    echo "<h1>This page is down for maintenance<h1>";
} else { ?>
    <script>
        function viewRoute(id, train, line) {
            $.ajax({
                url: "./database/getTrainRoute.php?train=" + id,
                type: "POST",
                success: function(msg) {
                    if (document.getElementById("popup_" + train) == undefined) {
                        var thisTime = msg;
                        var json = JSON.parse(msg)
                        console.log(json)
                        // document.getElementById("routeView").innerHTML = msg;
                        var table = "<table class='routeTable'><tr><th colspan='2'>Arrival</th><th>Station</th><th colspan='2'>Departure</th></tr>"
                        var thisPopup = document.createElement("div");
                        for (var i = 0; i < json.length; i++) {
                            var planned_arrival = json[i].planned_arrival
                            var arrival = json[i].arrival
                            var planned_departure = json[i].planned_departure
                            var departure = json[i].departure
                            if (planned_arrival == null) {
                                planned_arrival = ""
                            }
                            if (arrival == null || arrival == planned_arrival) {
                                arrival = ""
                            }
                            if (planned_departure == null) {
                                planned_departure = ""
                            }
                            if (departure == null || departure == planned_departure) {
                                departure = ""
                            }
                            if (json[i].cancellation == 1) {
                                var row = "<td colspan='2' class='right'><del>" + planned_arrival + "</del></td>"
                                row += "<td class='center'><del>" + json[i].station_name + "</del></td>"
                                row += "<td colspan='2' class='left'><del>" + planned_departure + "</del></td>"
                            } else {
                                var row = "<td class='right'>" + planned_arrival + "</td>"
                                row += "<td class='right stopList yellow'>" + arrival + "</td>"
                                row += "<td class='center'>" + json[i].station_name + " <a href='https://trains.candycryst.com/station.php?stationRef=" + json[i].station_ref + "'><i class='fas fa-share-square'></i></a></td>"
                                row += "<td class='left'>" + planned_departure + "</td>"
                                row += "<td class='left stopList yellow'>" + departure + "</td>"
                            }
                            table += "<tr>" + row + "</tr>"
                        }
                        thisPopup.classList.add("popup")
                        thisPopup.id = "popup_" + train
                        thisPopup.style.left = "20px"
                        thisPopup.style.top = "20px"
                        thisPopup.innerHTML = '<div class="popupCloseButton" onclick="closePopup(' + train + ')"><div class="cross">&times;</div></div><div id="popupheader_' + train + ' line" class="popupheader ' + line + '">Train ' + train + '</div><p>' + table + '</p>'
                        document.getElementById("body").appendChild(thisPopup)
                        dragElement(document.getElementById("popup_" + train));
                    }
                }
            })
        }

        function flirtPopup(train, line) {
            if (document.getElementById("Popup_flirt" + train) == undefined) {
                var table = '<iframe class="viewer" src="./monitors/trainMonitors.php?type=flirt&train=' + train + '">'
                var thisPopup = document.createElement("div");
                thisPopup.classList.add("flirtPopup")
                thisPopup.id = "popup_flirt" + train
                thisPopup.style.left = "20px"
                thisPopup.style.top = "20px"
                thisPopup.innerHTML = '<div class="popupCloseButton" onclick="closePopup(\'flirt' + train + '\')"><div class="cross">&times;</div></div><div id="popupheader_flirt' + train + ' line" class="popupheader ' + line + '">Train ' + train + '</div><p>' + table + '</p>'
                document.getElementById("body").appendChild(thisPopup)
                dragElement(document.getElementById("popup_flirt" + train));
            }
        }

        function bulkPopup() {
            if (document.getElementById("popup_bulk") == undefined) {
                var table = '<input type="number" required autocomplete="off" id="trainNumber" style="width: 7em" placeholder="Train number"><input type="number" autocomplete="off" id="bulkAmount" style="width: 8.5em" placeholder="Amount to check"><input type="submit" value="Bulk update" class="button" onclick="updateTrains()">'
                // table += '<input type="date" min="2021-01-01" required autocomplete="off" id="date"><input type="submit" value="Update all trains on date (first 1000)" class="button" onclick="updateExistingTrains(1)"><input type="submit" value="(Second 1000)" class="button" onclick="updateExistingTrains(2)"><input type="submit" value="(Third 1000)" class="button" onclick="updateExistingTrains(3)">'
                var thisPopup = document.createElement("div");
                thisPopup.classList.add("flirtPopup")
                thisPopup.id = "popup_bulk"
                thisPopup.style.left = "20px"
                thisPopup.style.top = "50px"
                thisPopup.style.height = "120px"
                thisPopup.innerHTML = '<div class="popupCloseButton" onclick="closePopup(\'popup_bulk\')"><div class="cross">&times;</div></div><div id="popupheader_bulk" class="popupheader L12">Bulk update data</div><p>' + table + '</p>'
                document.getElementById("body").appendChild(thisPopup)
                // dragElement(document.getElementById("popup_bulk"));
            }
        }
    </script>

    <body id="body">

        <?php echo $navbar; ?>
        <div class="addRow">
            <div class="progress">
                <div class="bar" id="progressBar"></div>
            </div>
            <?php if ($page > 0) { ?>
                <a href="./trainList.php?page=0&date=<?php echo $date ?>&inactive=<?php echo $showInactive ?>">First</a> |
                <?php if ($prevPage != "") { ?>
                    <a href="./trainList.php?page=<?php echo $prevPage ?>&date=<?php echo $date ?>&inactive=<?php echo $showInactive ?>">Prev content</a> |
                <?php } ?>
                <a href="./trainList.php?page=<?php echo $page - 1 ?>&date=<?php echo $date ?>&inactive=<?php echo $showInactive ?>">Prev</a> |
            <?php } ?>
            <a>train <?php echo $page * 1000 ?>-<?php echo ($page * 1000) + 999 ?></a>
            <?php if ($page < 99) { ?>
                | <a href="./trainList.php?page=<?php echo $page + 1 ?>&date=<?php echo $date ?>&inactive=<?php echo $showInactive ?>">Next</a>
                <?php if ($nextPage != "") { ?>
                    | <a href="./trainList.php?page=<?php echo $nextPage ?>&date=<?php echo $date ?>&inactive=<?php echo $showInactive ?>">Next content</a>
                <?php } ?>
                | <a href="./trainList.php?page=99&date=<?php echo $date ?>&inactive=<?php echo $showInactive ?>">Last</a>
            <?php } ?>
        </div>
        <table>
            <tr>
                <th colspan="2">List filters</th>
            </tr>
            <tr>
                <th>Date</th>
                <th>Submit</th>
            </tr>
            <tr>
                <form action="./trainList.php" method="get">
                    <!-- <input type="checkbox" required autocomplete="off" id="showInactive">Show inactive -->
                    <th>
                        <input type="date" value="<?php echo $date ?>" min="2021-12-01" max="<?php echo Date('Y-m-d', strtotime("+10 days")) ?>" required autocomplete="off" name="date">
                    </th>
                    <th>
                        <input type="submit" value="Apply" class="button">
                    </th>
                </form>
                </th>
            </tr>
        </table>
        <table class="shortTable" id="toc">
            <tr>
                <th>Table of trains</th>
            </tr>
        </table>

        <table class="trainListTable" id="trainList">
            <tr>
                <th rowspan="2"></th>
                <th rowspan="2">Train</th>
                <th rowspan="2"></th>
                <th rowspan="2">Line</th>
                <th class="train_origin" colspan="3">Origin</th>
                <th colspan="2" rowspan="2">Links</th>
                <th>Latest&nbspdate</th>
            </tr>
            <tr>
                <th class="train_origin" colspan="3">Destination</th>
                <th>today + next 10 days</th>
            </tr>
        </table>
        <footer class="footerParent">
            <div class="infoFooter">
                <h1>CandyCryst.com</h1>

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

    <script>
        function closePopup(train) {
            $('#popup_' + train).remove();
        }

        var trainData = []

        function sortData(element, index, array) {
            trainData[element.train_number] = element;
        }
        var showInactive = "<?php echo $showInactive ?>"

        var page = <?php echo $page ?>;
        var start = page * 100
        var end = page * 100 + 100
        getData()

        function updateTable(thisTrain, trainNum, type) {

            console.log(thisTrain + trainNum + type)
            var listRow = document.getElementById("tn_" + trainNum)
            var listRow2 = document.getElementById("tn2_" + trainNum)
            var row2Content = "";
            var rowContent = "";
            if ("<?php echo $date ?>" != "" && "<?php echo $date ?>" != thisTrain.dt) {
                rowContent += '<td rowspan="2" id="statusdot_' + trainNum + '">🔴</td>'
                listRow.classList.add("dimmed2")
                listRow2.classList.add("dimmed2")
            } else {
                rowContent += '<td rowspan="2" id="statusdot_' + trainNum + '">⚪</td>'
            }
            rowContent += '<td rowspan="2">Train&nbsp' + trainNum + '</td>';
            listRow.innerHTML = "";
            // if (thisTrain.route_status == 1) {
            // }
            if (thisTrain.operator == "") {
                rowContent += "<td rowspan='2'>N/A</td>"
            } else {
                rowContent += '<th rowspan="2"><img class="operatorImage" alt="' + thisTrain.operator + ' Logo" src="./assets/media/companies/' + thisTrain.operator + '.svg"></th>';
            }
            if (thisTrain.line != "") {
                linetouse = thisTrain.line
                rowContent += '<th rowspan="2" class="lineBoxFrame"><div class="linebox trainList ' + thisTrain.line + '"><div class="text">' + thisTrain.line + '</div></div></th>';
            } else {
                linetouse = thisTrain.operator
                rowContent += '<th rowspan="2" class="lineBoxFrame"><div class="linebox trainList ' + thisTrain.operator + '"><div class="text">' + thisTrain.operator + '</div></div></th>';
            }
            var departure
            var arrival
            var routeStart = new Date(thisTrain.route_start).getTime();
            var now = new Date().getTime();
            var routeEnd = new Date(thisTrain.route_end)
            
            if (thisTrain.origin_cancellation == 1) {
                departure = '<td class="stationListObject2 yellow"><del>' + thisTrain.origin_planned_time + '</del></td>';
            } else {
                departure = '<td class="stationListObject2">' + thisTrain.origin_planned_time + '</td>';
            }
            if (thisTrain.destination_cancellation == 1) {
                arrival = '<td class="stationListObject2 yellow"><del>' + thisTrain.destination_planned_time + '</del></td>';
            } else {
                arrival = '<td class="stationListObject2">' + thisTrain.destination_planned_time + '</td>';
            }
            if (thisTrain.destination_planned_time == thisTrain.destination_real_time || thisTrain.destination_real_time == null) {
                arrival += '<td class="stationListObject2"></td>';
            } else {
                arrival += '<td class="stationListObject2 yellow">' + thisTrain.destination_real_time + '</td>';
            }
            if (thisTrain.origin_planned_time == thisTrain.origin_real_time || thisTrain.origin_real_time == null) {
                departure += '<td class="stationListObject2"></td>';
            } else {
                departure += '<td class="stationListObject2 yellow">' + thisTrain.origin_real_time + '</td>';
            }
            if (thisTrain.origin_station != null) {
                rowContent += departure + '<td class="stationListObject2">' + thisTrain.origin_station.replace(' ', '&nbsp;') + "</td>"
            } else {
                rowContent += "<th class='stationListObject2'>No data</th>"
            }
            if (thisTrain.destination_station != null) {
                row2Content += arrival + '<td class="stationListObject2">' + thisTrain.destination_station.replace(' ', '&nbsp;') + "</td>"
            } else {
                row2Content += "<th class='stationListObject2'>No data</th>"
            }
            if (thisTrain.origin_station != null || thisTrain.destination_station != null) {
                rowContent += '<th class="stationList"><a class="stationListObject" onclick="viewRoute(' + thisTrain.id + ',' + trainNum + ',\'' + linetouse + '\')">View Route</a></th>'
                row2Content += '<th class="stationList"><a href="https://trains.candycryst.com/stationMap.php?train=' + trainNum + '" class="stationListObject">View on map</a></th>'
                <?php if ($userCanManageTrains == 1) { ?>
                    row2Content += '<th class="stationList"><a class="stationListObject" href="https://trains.candycryst.com/viewTrain.php?train=' + trainNum + '">View all</a></th>'
                <?php } else { ?>
                    rowContent += '<th rowspan="2" class="stationList"><a class="stationListObject" href="https://trains.candycryst.com/viewTrain.php?train=' + trainNum + '">View all</a></th>'
                <?php } ?>
            } else {
                rowContent += "<td rowspan='2'></td>"
                <?php if ($userCanManageTrains == 1) { ?>
                    row2Content += "<td></td>"
                <?php } else { ?>
                    rowContent += "<td rowspan='2'></td>"
                <?php } ?>
            }
            <?php if ($userCanManageTrains == 1) { ?>
                rowContent += '<th class="stationList"><a class="stationListObject" onclick="updateTrain(' + trainNum + ')">Update data</a></th>'
            <?php } ?>
            if (thisTrain.line == "") {
                var hasData = thisTrain.operator;
                thisTrain.line = thisTrain.operator;
            } else {
                var hasData = thisTrain.line;
            }
            rowContent += '<th class="stationListObject2">' + thisTrain.route_date + '</th>'
            var nextDays = "";
            <?php


            for ($i = 0; $i <= 10; $i++) { ?>
                var thisDay = "<?php echo Date('l', strtotime("today + $i days")) ?>"
                if (thisTrain.today<?php echo  $i ?> == 1) {
                    switch (thisDay) {
                        case "Saturday":
                            nextDays += "🌳";
                            break;
                        case "Sunday":
                            nextDays += "🌳";
                            break;
                        default:
                            nextDays += "🏢"
                    }
                } else {
                    switch (thisDay) {
                        case "Saturday":
                        case "Sunday":
                            nextDays += "🚫";
                            break;
                        default:
                            nextDays += "❌"
                    }
                    nextDays += ""
                }
            <?php } ?>
            row2Content += '<td class="dayEmojies">' + nextDays + '</td>'
            listRow.innerHTML = rowContent;
            listRow2.innerHTML = row2Content
            if ("<?php echo $date ?>" != "" && "<?php echo $date ?>" != thisTrain.dt) {
                document.getElementById('statusdot_' + trainNum).innerHTML = "🔴"
            } else if (routeStart < now && now < routeEnd) {
                document.getElementById('statusdot_' + trainNum).innerHTML = "🟢"
                listRow.classList.add("verified")
                listRow2.classList.add("verified")
            } else if (routeEnd < now) {
                document.getElementById('statusdot_' + trainNum).innerHTML = "✅"
            } else if (routeStart > now) {
                document.getElementById('statusdot_' + trainNum).innerHTML = "🔃"
            }
            if (type == 1) {
                document.getElementById('statusdot_' + trainNum).innerHTML = "🟢"
            }
        }

        function updateRow(trainNum) {
            $.ajax({
                url: "./database/getTrainData.php?type=single&num=" + trainNum,
                type: "POST",
                success: function(msg) {
                    var trains = JSON.parse(msg);
                    var rowContent;
                    if (trains[0] != undefined) {
                        updateTable(trains[0], trainNum, 1)
                    } else {
                        document.getElementById('statusdot_' + trainNum).innerHTML = "🔴"
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    document.getElementById('statusdot_' + trainNum).innerHTML = "🔴"
                }
            })
        }

        function getData() {
            $.ajax({
                url: "./database/getTrainData.php?type=single_page_trains&page=" + page + "&date=<?php echo $date ?>",
                type: "POST",
                success: function(msg) {
                    console.log(msg)
                    var trains = JSON.parse(msg);
                    trains.forEach(sortData)
                    var totalStatus = 0;
                    var variant = "A"
                    for (var i = start; i < end; i++) {
                        var row = document.createElement("tr");
                        if (i % 10 === 0) {
                            if (variant == "A") {
                                variant = "B"
                            } else {
                                variant = "A"
                            }
                        }
                        row.classList.add("shortTable" + variant)
                        var thisRow = "<td><div class='shortTableContainer'>";
                        var thisStatusToc = 0;
                        for (var j = 0; j < 10; j++) {
                            var thisStatus = 0;
                            var trainNumber = ((i * 10) + j)
                            var listRow = document.createElement("tr");
                            listRow.setAttribute("id", 'tn_' + trainNumber)
                            var listRow2 = document.createElement("tr");
                            listRow2.setAttribute("id", 'tn2_' + trainNumber)
                            listRow.classList.add("firstRow")

                            if (showInactive == "true" || trainData[trainNumber] != undefined) {
                                document.getElementById("trainList").appendChild(listRow);
                                document.getElementById("trainList").appendChild(listRow2);
                            }
                            if (trainData[trainNumber] != undefined) {
                                var thisTrain = trainData[trainNumber];
                                console.log(thisTrain + trainNumber + 0)
                                updateTable(thisTrain, trainNumber, 0)
                                thisStatus = 1
                                thisStatusToc = 1
                                totalStatus = 1
                                if (thisTrain.line == "") {
                                    var hasData = thisTrain.operator;
                                    thisTrain.line = thisTrain.operator;
                                } else {
                                    var hasData = thisTrain.line;
                                }
                                console.log("<?php echo $date ?>" + thisTrain.dt)
                                if ("<?php echo $date ?>" != "" && "<?php echo $date ?>" != thisTrain.dt) {
                                    thisRow += "<a class='vehicleBox dimmed " + hasData + " " + thisTrain.line + "' href='#tn_" + ((i * 10) + j) + "'>" + ((i * 10) + j) + "</a>";
                                } else {
                                    thisRow += "<a class='vehicleBox " + hasData + " " + thisTrain.line + "' href='#tn_" + ((i * 10) + j) + "'>" + ((i * 10) + j) + "</a>";
                                }
                            } else {
                                <?php if ($userCanManageTrains == 1) { ?>
                                    listRow.innerHTML += '<td id="statusdot_' + trainNumber + '">⚫</td>'
                                <?php } ?>
                                listRow.innerHTML += '<td class="stationListObject2" id="tns_' + trainNumber + '">Train&nbsp' + trainNumber + '</td>'

                                thisRow += " <a class='vehicleBox missing' href='#tn_" + ((i * 10) + j) + "'>" + ((i * 10) + j) + "</a>";
                                listRow.innerHTML += '<th class="stationListObject2" colspan="2" rowspan="2">No data found</th>'
                                <?php if ($userCanManageTrains == 1) { ?>
                                    listRow.innerHTML += '<th colspan="2" class="stationList"><a class="stationListObject" onclick="updateTrain(' + ((i * 10) + j) + ')">Update data</a></th>';

                                <?php } ?>
                                listRow.innerHTML += '<th colspan="6"></th>'
                            }

                        }
                        thisRow += "</div></td>";
                        if (showInactive == "true" || thisStatusToc == 1) {
                            row.innerHTML = thisRow;
                        }
                        document.getElementById("toc").appendChild(row);
                    }
                }
            })
        }
        // Make the DIV element draggable:

        function dragElement(elmnt) {
            var pos1 = 0,
                pos2 = 0,
                pos3 = 0,
                pos4 = 0;
            if (document.getElementById(elmnt.id + "header")) {
                // if present, the header is where you move the DIV from:
                document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
            } else {
                // otherwise, move the DIV from anywhere inside the DIV:
                elmnt.onmousedown = dragMouseDown;
            }

            function dragMouseDown(e) {
                e = e || window.event;
                e.preventDefault();
                // get the mouse cursor position at startup:
                pos3 = e.clientX;
                pos4 = e.clientY;
                document.onmouseup = closeDragElement;
                // call a function whenever the cursor moves:
                document.onmousemove = elementDrag;
            }

            function elementDrag(e) {
                e = e || window.event;
                e.preventDefault();
                // calculate the new cursor position:
                pos1 = pos3 - e.clientX;
                pos2 = pos4 - e.clientY;
                pos3 = e.clientX;
                pos4 = e.clientY;
                // set the element's new position:
                elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
                elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
            }

            function closeDragElement() {
                // stop moving when mouse button is released:
                document.onmouseup = null;
                document.onmousemove = null;
            }
        }

        var progressBar = 0;

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

        function updateTrain(trainNumber) {
            document.getElementById('statusdot_' + trainNumber).innerHTML = "🟠"
            updateData(trainNumber, 0, 1)
        }

        function updateTrains() {

            document.getElementById("progressBar").style.width = "0%"
            var counter = 0;
            var startNumber = parseInt(document.getElementById("trainNumber").value);
            var amount = parseInt(document.getElementById("bulkAmount").value);
            var endNumber = (startNumber + amount) - 1
            if (startNumber >= 0 && startNumber <= 99999) {
                if (endNumber >= 0 && endNumber <= 99999) {
                    for (var i = startNumber; i <= endNumber; i++) {
                        updateData(i, counter, amount)
                        // console.log(i)
                    }
                } else {
                    alert("Invalid end number!")
                }
            } else {
                alert("Invalid train number!")
            }
        }

        async function updateData(trainNumber, c, amount) {
            counter = c;
            request = $.ajax({
                url: `./database/manageTrains.php`,
                type: "post",
                data: {
                    "action": "main",
                    "id": trainNumber
                }
            });

            request.done(function(response, textStatus, jqXHR) {
                // Log a message to the console
                counter++
                console.log(counter + "/" + amount + ": " + response);
                var percent = (counter / amount) * 100

                document.getElementById("progressBar").style.width = percent + "%"

                if (response.includes("CODE100") || response.includes("CODE101") || response.includes("CODE200") || response.includes("CODE201")) {
                    document.getElementById('statusdot_' + trainNumber).innerHTML = "🟡"
                    updateRow(trainNumber)
                } else {
                    document.getElementById('statusdot_' + trainNumber).innerHTML = "🔴"
                }
                if (counter == amount) {
                    flashProgressBar()
                }
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
        <?php if ($userCanManageTrains == 1) { ?>
            bulkPopup();
        <?php } ?>
    </script>

</html>

<?php }
?>