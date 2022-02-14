<?php $pageRequiresLogin = false;
include "./config/connect.php";
$userCanManageTrains = 0;
include "./config/session.php";

include "./config/footer.php";
include "./config/navbar.php";
$navbarClass = new getNavbar(isset($_SESSION['login_user']), $userIsAdmin, "trainList");
$navbar = $navbarClass->getNavbar();


$startDate = new DateTime(Date('Y-m-d', strtotime("2021-12-01")));
$endDate = new DateTime(Date('Y-m-d', strtotime("+10 days")));
$interval = $startDate->diff($endDate);

$begin = new DateTime('2021-12-01');
$begin2 = new DateTime('2021-12-01');
$end = new DateTime(Date('Y-m-d', strtotime("+11 days")));
$endStr = Date('Y-m-d', strtotime("+11 days"));

$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);
$interval2 = DateInterval::createFromDateString('1 day');
$period2 = new DatePeriod($begin2, $interval2, $end);


// $page = $_GET["page"];
// $date = $_GET["date"];
$train = $_GET["train"];
// $showInactive = "true";
if ($page == "" || $page < 0) {
    $page = 0;
} else if ($page > 99) {
    $page = 99;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/lineImages.css">
    <link rel="stylesheet" href="./assets/css/main.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="./assets/css/trainList.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
    <link rel="icon" href="./assets/media/icon.png" type="image/png" />

    <title>Train list</title>
    <script src="https://kit.fontawesome.com/185bd08920.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./assets/css/main.css">
    <link rel="stylesheet" href="./assets/css/topnav.css">
    <script src="./assets/js/topnav.js"></script>

</head>
<script>
    function viewRoute(id, date, line) {
        $.ajax({
            url: "./database/getTrainRoute.php?train=" + id,
            type: "POST",
            success: function(msg) {
                if (document.getElementById("popup_" + id) == undefined) {
                    var thisTime = msg;
                    var json = JSON.parse(msg)
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
                        date = json[i].dt
                    }
                    thisPopup.classList.add("popup")
                    thisPopup.id = "popup_" + id
                    thisPopup.style.left = "20px"
                    thisPopup.style.top = "20px"
                    thisPopup.innerHTML = '<div class="popupCloseButton" onclick="closePopup(' + id + ')"><div class="cross">&times;</div></div><div id="popupheader_' + id + ' line" class="popupheader ' + line + '">' + date + '</div><p>' + table + '</p>'
                    document.getElementById("body").appendChild(thisPopup)
                    dragElement(document.getElementById("popup_" + id));
                }
            }
        })
    }

    function bulkPopup() {
        if (document.getElementById("popup_bulk") == undefined) {
            var table = '<input type="submit" value="Bulk update" class="button" onclick="updateTrains(<?php echo $train ?>)">'
            var thisPopup = document.createElement("div");
            thisPopup.classList.add("flirtPopup")
            thisPopup.id = "popup_bulk"
            thisPopup.style.left = "20px"
            thisPopup.style.top = "50px"
            thisPopup.style.height = "120px"
            thisPopup.style.width = "220px"
            thisPopup.innerHTML = '<div class="popupCloseButton" onclick="closePopup(\'popup_bulk\')"><div class="cross">&times;</div></div><div id="popupheader_bulk" class="popupheader L12">Bulk update</div><p>' + table + '</p>'
            document.getElementById("body").appendChild(thisPopup)
            // dragElement(document.getElementById("popup_bulk"));
        }
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
    <div class="addRow">
        <div class="progress">
            <div class="bar" id="progressBar"></div>
        </div>
        <a href="./viewTrain.php?train=<?php echo $train - 1 ?>">&lt;</a>
        <a>train <?php echo $train ?></a>
        <a href="./viewTrain.php?train=<?php echo $train + 1 ?>">&gt;</a>

    </div>
    <table class="calendarTable" id="toc">
        <tr>
            <th></th>
            <th colspan="7">Calendar</th>
        </tr>
        <tr>
            <th></th>
            <th>Mon</th>
            <th>Tue</th>
            <th>Wed</th>
            <th>Thu</th>
            <th>Fri</th>
            <th>Sat</th>
            <th>Sun</th>
        </tr>
        <tr>
            <th>Oct</th>
            <th></th>
            <th></th>
            <?php

            foreach ($period as $dt) {
                if ($dt->format("l") == "Monday") {
                    echo "</tr><tr><th>" . $dt->format("M") . '</th>';
                }
                echo "<th id='d_" . $dt->format("Y-m-d") . "'>⚫" . $dt->format("d") . "</th>";
            } ?>
    </table>

    <table class="trainListTable" id="trainList">
        <tr>
            <th></th>
            <td></td>
            <!-- <th>Train</th> -->
            <th>route date</th>
            <th>Line</th>
            <th>Operator</th>
            <th colspan="2" class="train_origin">Origin</th>
            <th>Links</th>
            <th colspan="2" class="train_origin">Destination</th>
            <?php if ($userCanManageTrains == 1) { ?>
                <!-- <th></th> -->
            <?php } ?>
        </tr>
        <?php foreach ($period as $dt) {
            // echo $dt->format("Y-m-d");
            echo '<tr id="tn_' . $dt->format("Y-m-d") . '">';
            echo '<td id="statusdot_' . $dt->format("Y-m-d") . '">🔴</td>';
            echo '<td>';
            switch ($dt->format("m-d")) {
                case "12-24":
                case "12-31":
                    echo "⭐";
                    break;
                default:
                    switch ($dt->format("l")) {
                        case "Monday":
                        case "Tuesday":
                        case "Wednesday":
                        case "Thursday":
                        case "Friday":
                            echo "🏢";
                            break;
                        case "Saturday":
                        case "Sunday":
                            echo "🌳";
                            break;
                    }
                    break;
            }
            echo '</td>';
            echo '<td>' . $dt->format("Y-m-d") . '</th>';
            if ($userCanManageTrains == 1) {
                echo '<th colspan="2"><a class="stationListObject" onclick="updateTrain(' . $train . ',\'' . $dt->format("Y-m-d") . '\')">Check for data</a></th>';
            } else {
                echo '<th colspan="2"></th>';
            }
            echo '<th colspan="5">Train number not in use or no data found</th>';
            echo '</tr>';
            echo '<tr id="tn2_' . $dt->format("Y-m-d") . '">';
        } ?>
    </table>
    <?php echo $footer; ?>
</body>
<script>
    var startDate = new Date("2021-12-01");
    var endDate = new Date("<?php echo $endStr ?>");
    const months = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
    console.log(startDate)
    console.log(endDate)
    var test = '<tr><th colspan="5">Train number not in use or no data found</th></tr>'

    function closePopup(train) {
        $('#popup_' + train).remove();
    }

    var trainData = []

    function sortData(element, index, array) {
        trainData[element.train_number] = element;
    }

    var trainNumber = <?php echo $train ?>;

    getData()

    function updateRow(trainNum, date) {
        $.ajax({
            url: "./database/getTrainData.php?type=single_train_number&num=" + trainNum + "&date=" + date,
            type: "POST",
            success: function(msg) {
                var trains = JSON.parse(msg);
                var rowContent;
                var row2Content;
                if (trains != undefined) {
                    updateTable(trains[0], date, 1)
                } else {
                    document.getElementById('statusdot_' + date).innerHTML = "🔴"
                    document.getElementById('d_' + thisTrain.sort_date).innerHTML = "🔴" + thisTrain.sort_date.substring(8, 10)
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                document.getElementById('statusdot_' + date).innerHTML = "🔴"
            }
        })
    }

    function updateTable(thisTrain, date, type) {
        var listRow = document.getElementById("tn_" + thisTrain.sort_date);
        var listRow2 = document.getElementById("tn2_" + thisTrain.sort_date);
        listRow.innerHTML = '<td rowspan="2" id="statusdot_' + thisTrain.sort_date + '">🟢</td>'
        document.getElementById('d_' + thisTrain.sort_date).innerHTML = "🟢" + thisTrain.sort_date.substring(8, 10)
        switch (thisTrain.sort_date.substring(5, 10)) {
            case "12-24":
            case "12-31":
                listRow.innerHTML += "<td rowspan='2'>⭐</td>"
                break;
            default:
                switch (thisTrain.route_date.substring(0, 3)) {
                    case "Mon":
                    case "Tue":
                    case "Wed":
                    case "Thu":
                    case "Fri":
                        listRow.innerHTML += "<td rowspan='2'>🏢</td>"
                        break;
                    case "Sat":
                    case "Sun":
                        listRow.innerHTML += "<td rowspan='2'>🌳</td>";
                        break;
                }
                break;
        }
        // listRow.innerHTML += '<td>' + thisTrain.route_date + '</td>';

        listRow.innerHTML += '<td rowspan="2" id="tns_' + thisTrain.sort_date + '">' + thisTrain.sort_date + '</td>'
        if (thisTrain.sort_status == 1) {
            listRow.classList.add("verified")
        }
        if (thisTrain.line != "") {
            linetouse = thisTrain.line
            listRow.innerHTML += '<td rowspan="2"><div class="linebox trainList ' + thisTrain.line + '"><div class="text">' + thisTrain.line + '</div></div></td>';
        } else {
            linetouse = thisTrain.operator
            listRow.innerHTML += '<td rowspan="2"><div class="linebox trainList ' + thisTrain.operator + '"><div class="text">' + thisTrain.operator + '</div></div></td>';
        }
        if (thisTrain.operator == "") {
            listRow.innerHTML += "<td rowspan='2'>N/A</td>"
        } else {
            listRow.innerHTML += '<th rowspan="2"><img class="operatorImage" alt="' + thisTrain.operator + ' Logo" src="./assets/media/companies/' + thisTrain.operator + '.svg"></th>';
        }
        var departure
        var arrival
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
        if (thisTrain.destination_planned_arrival == thisTrain.destination_arrival || thisTrain.destination_arrival == null) {
            arrival += '<td class="stationListObject2 yellow"></td>';
        } else {
            arrival += '<td class="stationListObject2 yellow">' + thisTrain.destination_arrival + '</td>';
        }
        if (thisTrain.origin_planned_departure == thisTrain.origin_departure || thisTrain.origin_departure == null) {
            departure += '<td class="stationListObject2 yellow"></td>';
        } else {
            departure += '<td class="stationListObject2 yellow">' + thisTrain.origin_departure + '</td>';
        }
        if (thisTrain.origin_station != null) {
            listRow.innerHTML += departure + '<td class="stationListObject2">' + thisTrain.origin_station.replace(' ', '&nbsp;') + "</td>"
        } else {
            listRow.innerHTML += "<th class='stationListObject2'>No data</th>"
        }
        if (thisTrain.destination_station != null) {
            listRow2.innerHTML += arrival + '<td class="stationListObject2">' + thisTrain.destination_station.replace(' ', '&nbsp;') + "</td>"
        } else {
            listRow2.innerHTML += "<th class='stationListObject2'>No data</th>"
        }

        var departure
        var arrival
        if (thisTrain.origin_planned_departure == thisTrain.origin_departure || thisTrain.origin_departure == null) {
            departure = "";
        } else {
            departure = thisTrain.origin_departure
        }
        if (thisTrain.destination_planned_arrival == thisTrain.destination_arrival || thisTrain.destination_arrival == null) {
            arrival = "";
        } else {
            arrival = thisTrain.destination_arrival
        }
        if (thisTrain.origin_station != null || thisTrain.destination_station != null) {
            <?php if ($userCanManageTrains == 1) { ?>
                listRow.innerHTML += '<th><a class="stationListObject" onclick="viewRoute(' + thisTrain.id + ',\'' + thisTrain.sort_date + '\',\'' + linetouse + '\')">View Route</a></th>'
                listRow2.innerHTML += '<th><a class="stationListObject" href="https://trains.candycryst.com/stationMap.php?train=' + trainNumber + '">View on map</a></th>'
                listRow.innerHTML += '<th rowspan="2"><a class="stationListObject" onclick="updateTrain(' + trainNumber + ',\'' + thisTrain.sort_date + '\')">Update data</a></th>'
            <?php } else { ?>
                listRow.innerHTML += '<th rowspan="2"><a class="stationListObject" onclick="viewRoute(' + thisTrain.id + ',\'' + thisTrain.sort_date + '\',\'' + linetouse + '\')">View Route</a></th>'
                listRow.innerHTML += '<th rowspan="2"><a class="stationListObject" href="https://trains.candycryst.com/stationMap.php?train=' + trainNumber + '">View on map</a></th>'
            <?php } ?>
        } else {
            listRow.innerHTML += "<td></td>"
        }

        if (thisTrain.line == "") {
            var hasData = thisTrain.operator;
            thisTrain.line = thisTrain.operator;
        } else {
            var hasData = thisTrain.line;
        }
    }

    function getData() {
        $.ajax({
            url: "./database/getTrainData.php?type=single_train_number&num=" + trainNumber,
            type: "POST",
            success: function(msg) {
                var trainData = JSON.parse(msg);
                console.log(trainData)
                trainData.forEach(sortData)
                var totalStatus = 0;
                var variant = "A"
                for (var i = 0; i < trainData.length; i++) {

                    var thisTrain = trainData[i];
                    var thisStatus = 0;
                    console.log(thisTrain.sort_date)
                    updateTable(thisTrain, thisTrain.sort_date, 1)
                }

                if (totalStatus == 0) {
                    var row = document.createElement("tr");
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

    // var progressBar = 0;

    // function flashProgressBar() {
    //     var bar = document.getElementById("progressBar")
    //     document.getElementById("progressBar").style.backgroundColor = "white"
    //     setTimeout(function(bar) {
    //         document.getElementById("progressBar").style.backgroundColor = ""
    //     }, 250);
    //     setTimeout(function(bar) {
    //         document.getElementById("progressBar").style.backgroundColor = "white"
    //     }, 500);
    //     setTimeout(function(bar) {
    //         document.getElementById("progressBar").style.backgroundColor = ""
    //     }, 750);
    //     setTimeout(function(bar) {
    //         document.getElementById("progressBar").style.backgroundColor = "white"
    //     }, 1000);
    //     setTimeout(function(bar) {
    //         document.getElementById("progressBar").style.backgroundColor = ""
    //     }, 1250);
    // }

    function updateTrain(trainNumber, date) {
        updateData(trainNumber, date)
    }

    function updateTrains(num) {
        console.log(num)
        <?php foreach ($period2 as $dt) { ?>
            updateTrain(num, "<?php echo $dt->format("Y-m-d") ?>");
        <?php } ?>
    }

    async function updateData(trainNumber, date) {
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
            if (response.includes("CODE100") || response.includes("CODE101") || response.includes("CODE200") || response.includes("CODE201")) {
                document.getElementById('statusdot_' + date).innerHTML = "⚪"
                document.getElementById('d_' + date).innerHTML = "⚪" + date.substring(8, 10)
                updateRow(trainNumber, date)
            } else {
                document.getElementById('statusdot_' + date).innerHTML = "🔵"
                document.getElementById('d_' + date).innerHTML = "🔵" + date.substring(8, 10)

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
        // updateTrains(trainNumber);
    <?php } ?>
</script>

</html>