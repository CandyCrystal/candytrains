<?php $pageRequiresTrainManager = true;
include "./config/connect.php";
include "./config/session.php";

include "./config/navbar.php";
$navbarClass = new getNavbar(isset($_SESSION['login_user']), $userIsAdmin, "home");
$navbar = $navbarClass->getNavbar();

$num = $_GET["num"];

include "./database/getLineData.php";
$lineDataQuery = new getLineData($databaseConnection);

include "./database/getOperatorData.php";
$operatorDataQuery = new getOperatorData($databaseConnection);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/lineImages.css">
    <link rel="stylesheet" href="./assets/css/main.css">
    <link rel="stylesheet" href="./assets/css/trainList.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
    <link rel="icon" href="./assets/media/icon.png" type="image/png" />


    <script src="https://kit.fontawesome.com/185bd08920.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./assets/css/main.css">
    <link rel="stylesheet" href="./assets/css/topnav.css">
    <script src="./assets/js/topnav.js"></script>

    <title>Document</title>
</head>

<body>
    <?php echo $navbar; ?>
    <table>
        <tr>
            <th>
                <input type="number" required autocomplete="off" id="trainNumber" style="width: 7em" placeholder="Train number">
            </th>
            <th>
                <input type="number" autocomplete="off" id="bulkAmount" style="width: 8.5em" placeholder="Amount to check">
            </th>
            <th>
                <input type="submit" value="Add one" class="button" onclick="oneAdd()">
            </th>
            <th>
                <input type="submit" value="Bulk Add" class="button" onclick="bulkAdd()">
            </th>
            <th>
                <input type="submit" value="Bulk Update" class="button" onclick="bulkUpdate()">
            </th>
        </tr>
        <tr>
            <th>
                <?php echo $lineDataQuery->getLineDropdown(0); ?>
            </th>
            <th>
                <?php echo $operatorDataQuery->getOperatorDropdown(""); ?>
            </th>
            <th>
                <input type="submit" value="Add one manually" class="button" onclick="manualAdd()">
            </th>
            <th>
                <input type="submit" value="Update from live" class="button" onclick="manualUpdate()">
            </th>
            <th>
            </th>
            <!-- <th>
                <input type="submit" value="Add all manually" class="button" onclick="bulkManualAdd()">
            </th> -->
        </tr>
    </table>
    </div>
    <script>
        var lineNumbers = [390, 391, 392, 393, 394, 395, 396, 397, 398, 399]
        var counter = 0
        var amount = lineNumbers.length
        lineNumbers = lineNumbers.sort(function(a, b) {
            return a - b;
        });
        // for (var i = 0; i < lineNumbers.length; i++) {
        //     editCollection(lineNumbers[i], counter, amount)
        //     // console.log(lineNumbers[i])
        // }

        function oneAdd() {
            var trainNumber = parseInt(document.getElementById("trainNumber").value);
            if (trainNumber >= 0 && trainNumber <= 99999) {
                editCollection(trainNumber, 1, 1)
            } else {
                alert("Invalid train number!")
            }
        }

        function manualAdd() {
            var trainNumber = parseInt(document.getElementById("trainNumber").value);
            var operator = document.getElementById("operator_id").value;
            var line = document.getElementById("line_id").value;
            if (trainNumber >= 0 && trainNumber <= 99999) {
                editManualCollection(trainNumber, 1, 1, line, operator)
            } else {
                alert("Invalid train number!")
            }
        }


        function bulkAdd() {
            var counter = 0;
            var startNumber = parseInt(document.getElementById("trainNumber").value);
            var amount = parseInt(document.getElementById("bulkAmount").value);
            var endNumber = (startNumber + amount) - 1
            if (startNumber >= 0 && startNumber <= 99999) {
                if (endNumber >= 0 && endNumber <= 99999) {
                    for (var i = startNumber; i <= endNumber; i++) {
                        editCollection(i, counter, amount)
                        // console.log(i)
                    }
                } else {
                    alert("Invalid end number!")
                }
            } else {
                alert("Invalid train number!")
            }
        }

        function bulkUpdate() {
            var counter = 0;
            var startNumber = parseInt(document.getElementById("trainNumber").value);
            var amount = parseInt(document.getElementById("bulkAmount").value);
            var endNumber = (startNumber + amount) - 1
            if (startNumber >= 0 && startNumber <= 99999) {
                if (endNumber >= 0 && endNumber <= 99999) {
                    for (var i = startNumber; i <= endNumber; i++) {
                        updateCollection(i, counter, amount)
                        // console.log(i)
                    }
                } else {
                    alert("Invalid end number!")
                }
            } else {
                alert("Invalid train number!")
            }
        }

        function manualUpdate() {
            var trainNumber = parseInt(document.getElementById("trainNumber").value);
            if (trainNumber >= 0 && trainNumber <= 99999) {
                updateManual(trainNumber)
            } else {
                alert("Invalid train number!")
            }
        }

        function bulkManualAdd() {
            var counter = 0
            var amount = lineNumbers.length
            var operator = document.getElementById("operatorID").value;
            var line = document.getElementById("lineID").value;
            // console.log(operator + " " + line)
            for (var i = 0; i < lineNumbers.length; i++) {
                editManualCollection(lineNumbers[i], counter, amount, line, operator)
                // console.log(lineNumbers[i])
            }
        }


        async function editCollection(id, c, amount) {
            counter = c;
            request = $.ajax({
                url: `./database/manageTrains.php`,
                type: "post",
                data: {
                    "action": "insert",
                    "id": id
                }
            });

            request.done(function(response, textStatus, jqXHR) {
                // Log a message to the console
                counter++
                console.log(counter + "/" + amount + ": " + response);
                if (counter == amount) {
                    console.log("🟣Done");
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
        async function updateCollection(id, c, amount) {
            counter = c;
            request = $.ajax({
                url: `./database/manageTrains.php`,
                type: "post",
                data: {
                    "action": "update",
                    "id": id
                }
            });

            request.done(function(response, textStatus, jqXHR) {
                // Log a message to the console
                counter++
                console.log(counter + "/" + amount + ": " + response);
                if (counter == amount) {
                    console.log("🟣Done");
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
        async function updateManual(id) {
            request = $.ajax({
                url: `./database/manageTrains.php`,
                type: "post",
                data: {
                    "action": "update_live",
                    "id": id
                }
            });

            request.done(function(response, textStatus, jqXHR) {
                // Log a message to the console
                console.log(response);
                console.log("🟣Done");
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
        async function editManualCollection(id, c, amount, line, operator) {
            counter = c;
            request = $.ajax({
                url: `./database/manageTrains.php`,
                type: "post",
                data: {
                    "action": "manual_insert",
                    "id": id,
                    "lineID": line,
                    "operator_id": operator
                }
            });

            request.done(function(response, textStatus, jqXHR) {
                // Log a message to the console
                counter++
                console.log(counter + "/" + amount + ": " + response);
                if (counter == amount) {
                    console.log("🟣Done");
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
    </script>

</body>


</html>