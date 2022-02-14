<?php
$maintenance = false;

$pageRequiresLogin = false;
include "./config/connect.php";
$pageRequiresTrainManager = 0;
$pageRequiresStationManager = 0;
include "./config/session.php";

include "./config/navbar.php";
$navbarClass = new getNavbar(isset($_SESSION['login_user']), 0, "trainList");
$navbar = $navbarClass->getNavbar();

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
                            var old_arrival = json[i].old_arrival
                            var arrival = json[i].arrival
                            var planned_departure = json[i].planned_departure
                            var old_departure = json[i].old_departure
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
                                var row = "<td colspan='3' class='right'><del>" + planned_arrival + "</del></td>"
                                row += "<td class='center'><del>" + json[i].station_name + "</del></td>"
                                row += "<td colspan='3' class='left'><del>" + planned_departure + "</del></td>"
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
    </script>

    <body id="body">

        <?php echo $navbar; ?>

        <table class="trainListTable" id="trainList">
            <tr>
                <th>Train</th>
                <th>Line</th>
                <th>Operator</th>
                <th colspan="3">Links</th>
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

        getData()

        function updateTable(element, index, array) {

            var thisTrain = element
            var trainNum = thisTrain.train_number
            var type = 0

            var listRow = document.createElement("tr");
            var listRow2 = document.createElement("tr");
            listRow.setAttribute("id", 'tn_' + trainNum)
            listRow2.setAttribute("id", 'tn2_' + trainNum)
            listRow.classList.add("firstRow")
            listRow.classList.add("verified")
            listRow2.classList.add("verified", "shortTable", "A")
            document.getElementById("trainList").appendChild(listRow);
            document.getElementById("trainList").appendChild(listRow2);
            listRow2.innerHTML = '<td colspan="10"><div class="stopTableContainer" id="route_' + trainNum + '"></div></td>'
            setRoute(thisTrain.route_id, trainNum)

            console.log(thisTrain + trainNum + type)
            var rowContent = "";
            var rowContent = "";
            rowContent += '<td >Train&nbsp' + trainNum + '</td>';
            listRow.innerHTML = "";
            if (thisTrain.operator == "") {
                rowContent += "<td>N/A</td>"
            } else {
                rowContent += '<th><img class="operatorImage" alt="' + thisTrain.operator + ' Logo" src="./assets/media/companies/' + thisTrain.operator + '.svg"></th>';
            }
            if (thisTrain.line != "") {
                linetouse = thisTrain.line
                rowContent += '<th class="lineBoxFrame"><div class="linebox trainList ' + thisTrain.line + '"><div class="text">' + thisTrain.line + '</div></div></th>';
            } else {
                linetouse = thisTrain.operator
                rowContent += '<th class="lineBoxFrame"><div class="linebox trainList ' + thisTrain.operator + '"><div class="text">' + thisTrain.operator + '</div></div></th>';
            }
            rowContent += '<th class="stationList"><a class="stationListObject" onclick="viewRoute(' + thisTrain.id + ',' + trainNum + ',\'' + linetouse + '\')">View Route</a></th>'
            rowContent += '<th class="stationList"><a href="https://trains.candycryst.com/stationMap.php?train=' + trainNum + '" class="stationListObject">View on map</a></th>'
            <?php if ($userCanManageTrains == 1) { ?>
                rowContent += '<th class="stationList"><a class="stationListObject" href="https://trains.candycryst.com/viewTrain.php?train=' + trainNum + '">View all</a></th>'
            <?php } else { ?>
                rowContent += '<th class="stationList"><a class="stationListObject" href="https://trains.candycryst.com/viewTrain.php?train=' + trainNum + '">View all</a></th>'
            <?php }
            if ($userCanManageTrains == 1) { ?> rowContent += '<th class="stationList"><a class="stationListObject" onclick="updateTrain(' + trainNum + ')">Update data</a></th>'
            <?php } ?>
            if (thisTrain.line == "") {
                var hasData = thisTrain.operator;
                thisTrain.line = thisTrain.operator;
            } else {
                var hasData = thisTrain.line;
            }
            listRow.innerHTML = rowContent;
        }

        function setRoute(id, trainNum) {
            $.ajax({
                url: "./database/getTrainRoute.php?train=" + id,
                type: "POST",
                success: function(msg) {
                    var trains = JSON.parse(msg);
                    var rowContent;
                    var state = 0;
                    for (var i = 0; i < trains.length; i++) {
                        var arr = new Date(trains[i].arr);
                        var now = new Date();
                        var dep = new Date(trains[i].dep)

                        var arrToUse = ("0" + (arr || dep).getHours()).slice(-2) + ":" + ("0" + (arr || dep).getMinutes()).slice(-2);
                        var depToUse = ("0" + (dep || arr).getHours()).slice(-2) + ":" + ("0" + (dep || arr).getMinutes()).slice(-2);

                        if (arr < now && dep > now) {
                            state = 1
                            if (trains[i].cancellation == 1) {
                                document.getElementById('route_' + trainNum).innerHTML += '<a class="stopBox L14">' + trains[i].station_name.replace(" ", "&nbsp") + "<br/>" + depToUse + "</a>"
                            } else {
                                document.getElementById('route_' + trainNum).innerHTML += '<a class="stopBox R10">🚂&nbsp' + trains[i].station_name.replace(" ", "&nbsp") + "<br/>" + depToUse + "</a>"
                            }
                        } else if (dep < now && arr < now) {
                            if (trains[i].cancellation == 1) {
                                document.getElementById('route_' + trainNum).innerHTML += '<a class="stopBox L14">' + trains[i].station_name.replace(" ", "&nbsp") + "<br/>" + depToUse + "</a>"
                            } else {
                                document.getElementById('route_' + trainNum).innerHTML += '<a class="stopBox R30">' + trains[i].station_name.replace(" ", "&nbsp") + "<br/>" + depToUse + "</a>"
                            }
                        } else {
                            if (trains[i].cancellation == 1) {
                                document.getElementById('route_' + trainNum).innerHTML += '<a class="stopBox L14">' + trains[i].station_name.replace(" ", "&nbsp") + "<br/>" + arrToUse + "</a>"
                            } else {
                                if (state != 1) {
                                    document.getElementById('route_' + trainNum).innerHTML += '<a class="stopBox R10">🚂</a>'
                                    state = 1
                                }
                                document.getElementById('route_' + trainNum).innerHTML += '<a class="stopBox L22">' + trains[i].station_name.replace(" ", "&nbsp") + "<br/>" + arrToUse + "</a>"
                            }
                        }
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    document.getElementById('statusdot_' + trainNum).innerHTML = "🔴"
                }
            })
        }

        function getData() {
            $.ajax({
                url: "./database/getTrainData.php?type=running_trains",
                type: "POST",
                success: function(msg) {
                    console.log(msg)
                    var trains = JSON.parse(msg);
                    trains.forEach(sortData)
                    trains.forEach(updateTable)
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
    </script>

</html>
<?php } ?>