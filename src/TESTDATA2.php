<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/lineImages.css">
    <link rel="stylesheet" href="./assets/css/main.css">
    <title>Document</title>
    <style>
        body {
            color: #fff;
            background-color: #000;
        }
    </style>
</head>

<body>




    <table>
        <tr>
            <th>Train num</th>
            <th>Stops</th>
        </tr>
        <?php

        $pageRequiresStationManager = true;
        include "./config/connect.php";
        include "./customLines.php";
        include "./config/session.php";
        $customLineQuery = new getCustomLine();
        $query = "SELECT train_number FROM train_numbers";
        $ex = $databaseConnection->query($query);
        $stats = array();
        while ($r = mysqli_fetch_assoc($ex)) {
            $thisRow = $r;

            $num = $thisRow["train_number"];
            echo "<tr><th>" . $num . "</th>";
            $query2 = "SELECT  train_lines.line_name AS line,operators.operator_name AS operator,operators.operator_code AS operator_ref,route_id FROM routes INNER JOIN train_lines ON routes.route_line = train_lines.line_id INNER JOIN operators ON routes.route_operator = operators.operator_id WHERE train_number = $num AND route_date >= '2021-12-01' ORDER BY route_date DESC LIMIT 1";
            $ex2 = $databaseConnection->query($query2);
            $numRows = mysqli_num_rows($ex2);
            // if ($numRows != 0) {
            // }

            while ($r2 = mysqli_fetch_assoc($ex2)) {
                $thisRow2 = $r2;
                $thisID = $thisRow2["route_id"];
                $query3 = "SELECT entry_station,stations.station_type AS station_type,activity from route_entries INNER JOIN stations ON route_entries.entry_station = stations.station_ref WHERE entry_route = $thisID AND (station_type = 1 OR station_type = 7) ORDER BY entry_number";
                $ex3 = $databaseConnection->query($query3);
                $stopList = "";
                $stops = "";
                while ($r3 = mysqli_fetch_assoc($ex3)) {
                    $thisRow3 = $r3;
                    $stops .= "<th>" . $thisRow3["entry_station"] . "</th>";
                    $stopList .= $thisRow3["entry_station"];
                }
                $line = $customLineQuery->getCustomLine(1, $num, $stopList);
                $stats[$line][] = $stopList;
                $lineOperator = '<th class="lineBoxFrame"><div class="linebox trainList ' . $thisRow2["operator_ref"] . '"><div class="text">' . $thisRow2["operator_ref"] . '</div></div></th>';
                $lineOperator .= '<th class="lineBoxFrame"><div class="linebox trainList ' . $thisRow2["line"] . '"><div class="text">' . $thisRow2["line"] . '</div></div></th>';
                $lineOperator .= '<th class="lineBoxFrame"><div class="linebox trainList ' . $line . '"><div class="text">' . $line . '</div></div></th>';
                echo $lineOperator . $stops . "<td colspan='30'>" . $stopList . "</td></tr>";
            }
        }
        echo json_encode($stats)



        ?>
</body>

</html>