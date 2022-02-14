<?php
$pageRequiresTrainManager = true;
include "../config/connect.php";
include "../config/session.php";

$action = $_REQUEST['action'];
$id = $_REQUEST['id'];

$date = $_REQUEST["date"];
if ($action == "") {
    $action = "main";
}
$query = new trainQuery($databaseConnection);
switch ($action) {
    case ("main"):
        echo $query->updateData($id);
        break;
    case ("update_one"):
        echo $query->updateDataOnDate($id, $date);
        break;
}

class trainQuery
{
    private $databaseConnection;
    private $status;

    function __construct($conn)
    {
        $this->databaseConnection = $conn;
    }

    function tfToBin($input)
    {
        if ($input == "true") {
            return 1;
        } else {
            return 2;
        }
    }
    function activityToId($input)
    {
        switch ($input) {
            case "stopping":
            case "alighting":
            case "boarding":
                return 1;
                break;
            case "X":
            case "noAlighting":
            case "noBoarding":
                return 2;
                break;
            case "X":
            case "passthru":
            case "passthru":
                return 3;
                break;
            case "unknown":
                return 4;
                break;
        }
    }
    function statusToId($input)
    {
        switch ($input) {
            case "onTime":
                return 1;
                break;
            case "onTime":
                return 2;
                break;
            case "arrived,":
                return 3;
                break;
            case "departed,":
                return 4;
                break;
        }
    }

    function insertRouteEntries($stops, $train_id, $urlDate, $train_number, $action)
    {
        $entry_sql = "INSERT INTO route_entries (entry_route,entry_number,entry_station,cancellation,request_stop,activity,arrival_track,planned_arrival,expected_arrival,actual_arrival,arrival_activity,arrival_status,arrival_delay,departure_track,planned_departure,expected_departure,actual_departure,departure_activity,departure_status,departure_delay) VALUES ";
        for ($j = 0; $j < count($stops); $j++) {
            $plannedArr = "NULL";
            $expectedArr = "NULL";
            $actualArr = "NULL";
            $plannedDep = "NULL";
            $expectedDep = "NULL";
            $actualDep = "NULL";
            if ($stops[$j]->arrivalPlannedTime != "") {
                $plannedArr = "'" . date_format(new DateTime(mysqli_real_escape_string($this->databaseConnection, $stops[$j]->arrivalPlannedTime)), 'Y-m-d H:i:s') . "'";
            }
            if ($stops[$j]->arrivalExpectedTime != "") {
                $expectedArr = "'" . date_format(new DateTime(mysqli_real_escape_string($this->databaseConnection, $stops[$j]->arrivalExpectedTime)), 'Y-m-d H:i:s') . "'";
            }
            if ($stops[$j]->arrivalActualTime != "") {
                $actualArr =  "'" . date_format(new DateTime(mysqli_real_escape_string($this->databaseConnection, $stops[$j]->arrivalActualTime)), 'Y-m-d H:i:s') . "'";
            }
            if ($stops[$j]->departurePlannedTime != "") {
                $plannedDep =  "'" . date_format(new DateTime(mysqli_real_escape_string($this->databaseConnection, $stops[$j]->departurePlannedTime)), 'Y-m-d H:i:s') . "'";
            }
            if ($stops[$j]->departureExpectedTime != "") {
                $expectedDep =  "'" . date_format(new DateTime(mysqli_real_escape_string($this->databaseConnection, $stops[$j]->departureExpectedTime)), 'Y-m-d H:i:s') . "'";
            }
            if ($stops[$j]->departureActualTime != "") {
                $actualDep =  "'" . date_format(new DateTime(mysqli_real_escape_string($this->databaseConnection, $stops[$j]->departureActualTime)), 'Y-m-d H:i:s') . "'";
            }





            $thisEntry = "(";
            $thisEntry .= "'" . $train_id . "',";
            $thisEntry .= "'" . $j . "',";
            $thisEntry .= "'" . mysqli_real_escape_string($this->databaseConnection, $stops[$j]->stationRef) . "',";
            $thisEntry .= "'" . intval(mysqli_real_escape_string($this->databaseConnection, $this->tfToBin($stops[$j]->cancellation))) . "',";
            $thisEntry .= "'" . intval(mysqli_real_escape_string($this->databaseConnection, $this->tfToBin($stops[$j]->requestStop))) . "',";
            $thisEntry .= "'" . intval(mysqli_real_escape_string($this->databaseConnection, $this->activityToId($stops[$j]->activity))) . "',";
            $thisEntry .= "'" . intval(mysqli_real_escape_string($this->databaseConnection, $stops[$j]->track)) . "',";
            $thisEntry .= $plannedArr . ",";
            $thisEntry .= $expectedArr . ",";
            $thisEntry .= $actualArr . ",";
            $thisEntry .= "'" . intval(mysqli_real_escape_string($this->databaseConnection, $this->activityToId($stops[$j]->arrivalActivity))) . "',";
            $thisEntry .= "'" . intval(mysqli_real_escape_string($this->databaseConnection, $this->statusToId($stops[$j]->arrivalStatus))) . "',";
            $thisEntry .= "'" . intval(mysqli_real_escape_string($this->databaseConnection, $stops[$j]->arrivalDelay)) . "',";
            $thisEntry .= "'" . intval(mysqli_real_escape_string($this->databaseConnection, $stops[$j]->track)) . "',";
            $thisEntry .= $plannedDep . ",";
            $thisEntry .= $expectedDep . ",";
            $thisEntry .= $actualDep . ",";
            $thisEntry .= "'" . intval(mysqli_real_escape_string($this->databaseConnection, $this->activityToId($stops[$j]->departureActivity))) . "',";
            $thisEntry .= "'" . intval(mysqli_real_escape_string($this->databaseConnection, $this->statusToId($stops[$j]->departureStatus))) . "',";
            $thisEntry .= "'" . intval(mysqli_real_escape_string($this->databaseConnection, $stops[$j]->departureDelay)) . "'";
            $thisEntry .= "),";



            $entry_sql .= $thisEntry;
        };
        $entry_sql = substr($entry_sql, 0, -1) . ";";
        // echo $entry_sql;
        if ($action == "update") {
            $action = 2;
        } else if ($action == "add") {
            $action = 1;
        }
        if ($this->databaseConnection->query($entry_sql) === TRUE) {
            if ($action == 2) {
                echo $urlDate . "🟡CODE201 " . $train_number;
            } else if ($action == 1) {
                echo $urlDate . "🟢CODE101 " . $train_number;
            }
        } else {
            echo $urlDate . "🟠CODE" . $action . "03 " . $train_number;
        }
    }

    function updateDataOnDate($train_number, $date)
    {
        $urlDate = mysqli_real_escape_string($this->databaseConnection, $date);
        $train_number = mysqli_real_escape_string($this->databaseConnection, $train_number);
        $check_query = "SELECT train_number FROM routes WHERE train_number = '$train_number' AND route_date = '$urlDate' LIMIT 1";

        $check = $this->databaseConnection->query($check_query);
        $check = mysqli_num_rows($check);
        $foundTrain = 0;
        if ($check == 0) {
            $journeyURL = "https://api.srd.tf/banenor/train?id=" . $train_number . "&date=" . $urlDate;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $journeyURL);
            // Following line is compulsary to add as it is:
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
            $journeyData = json_decode(curl_exec($ch));
            curl_close($ch);

            if ($journeyData[0]->id != null) {

                $line = $this->checkLine($journeyData[0]->line);
                $operator = $this->checkOperator($journeyData[0]->operator);
                $numstops = count($journeyData[0]->stops)-1;
                $startDate = date_format(new DateTime($journeyData[0]->stops[0]->departureActualTime ?: $journeyData[0]->stops[0]->departureExpectedTime ?: $journeyData[0]->stops[0]->departurePlannedTime ?: $journeyData[0]->stops[0]->arrivalActualTime ?: $journeyData[0]->stops[0]->arrivalExpectedTime ?: $journeyData[0]->stops[0]->arrivalPlannedTime), 'Y-m-d H:i:s');
                $endDate = date_format(new DateTime($journeyData[0]->stops[$numstops]->arrivalActualTime ?: $journeyData[0]->stops[$numstops]->arrivalExpectedTime ?: $journeyData[0]->stops[$numstops]->arrivalPlannedTime ?: $journeyData[0]->stops[$numstops]->departureActualTime ?: $journeyData[0]->stops[$numstops]->departureExpectedTime ?: $journeyData[0]->stops[$numstops]->departurePlannedTime), 'Y-m-d H:i:s');


                $train_number = mysqli_real_escape_string($this->databaseConnection, $train_number);
                $line = mysqli_real_escape_string($this->databaseConnection, $line);
                $operator = mysqli_real_escape_string($this->databaseConnection, $operator);
                $startDate = mysqli_real_escape_string($this->databaseConnection, $startDate);
                $endDate = mysqli_real_escape_string($this->databaseConnection, $endDate);

                $train_num = "INSERT INTO train_numbers (train_number) VALUES  ('" . $train_number . "')";
                $this->databaseConnection->query($train_num);
                $sql = "INSERT INTO routes (train_number,route_line,route_operator,route_date,route_start,route_end) VALUES ('$train_number',$line,$operator,'$urlDate','$startDate','$endDate');";
                if ($this->databaseConnection->query($sql) === TRUE) {
                    $this->status = 1;
                } else {
                    echo $urlDate . "🔴CODE2 " . $train_number;
                }
                $train_id_query = "SELECT route_id FROM routes WHERE train_number = '$train_number' AND route_date = '$urlDate'";
                $train_id = $this->databaseConnection->query($train_id_query);
                $train_id_row = mysqli_fetch_all($train_id);
                $train_id = $train_id_row[0][0];
                if ($train_id != null && $this->status == 1) {
                    echo $this->insertRouteEntries($journeyData[0]->stops, $train_id, $urlDate, $train_number, "add");
                }
            } else {
                echo $urlDate . "🔵CODE000 " . $train_number;
            }
        } else if ($check == 1) {
            $journeyURL = "https://api.srd.tf/banenor/train?id=" . $train_number . "&date=" . $urlDate;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $journeyURL);
            // Following line is compulsary to add as it is:
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
            $journeyData = json_decode(curl_exec($ch));
            curl_close($ch);

            if ($journeyData[0]->id != null && $foundTrain == 0) {
                $foundTrain = 1;

                $line = $this->checkLine($journeyData[0]->line);
                $operator = $this->checkOperator($journeyData[0]->operator);

                $numstops = count($journeyData[0]->stops)-1;
                $startDate = date_format(new DateTime($journeyData[0]->stops[0]->departureActualTime ?: $journeyData[0]->stops[0]->departureExpectedTime ?: $journeyData[0]->stops[0]->departurePlannedTime ?: $journeyData[0]->stops[0]->arrivalActualTime ?: $journeyData[0]->stops[0]->arrivalExpectedTime ?: $journeyData[0]->stops[0]->arrivalPlannedTime), 'Y-m-d H:i:s');
                $endDate = date_format(new DateTime($journeyData[0]->stops[$numstops]->arrivalActualTime ?: $journeyData[0]->stops[$numstops]->arrivalExpectedTime ?: $journeyData[0]->stops[$numstops]->arrivalPlannedTime ?: $journeyData[0]->stops[$numstops]->departureActualTime ?: $journeyData[0]->stops[$numstops]->departureExpectedTime ?: $journeyData[0]->stops[$numstops]->departurePlannedTime), 'Y-m-d H:i:s');

                $train_number = mysqli_real_escape_string($this->databaseConnection, $train_number);
                $line = mysqli_real_escape_string($this->databaseConnection, $line);
                $operator = mysqli_real_escape_string($this->databaseConnection, $operator);
                $startDate = mysqli_real_escape_string($this->databaseConnection, $startDate);
                $endDate = mysqli_real_escape_string($this->databaseConnection, $endDate);


                $train_number = mysqli_real_escape_string($this->databaseConnection, $train_number);
                $sql = "UPDATE routes SET route_line=$line,route_operator=$operator,route_start='$startDate',route_end='$endDate' WHERE train_number = $train_number AND route_date = '$urlDate'";
                if ($this->databaseConnection->query($sql) === TRUE) {
                    $this->status = 1;
                } else {
                    echo "Error: " . $sql . "<br>" . $this->databaseConnection->error;
                }
                $train_id_query = "SELECT route_id FROM routes WHERE train_number = '$train_number' AND route_date = '$urlDate'";
                $train_id = $this->databaseConnection->query($train_id_query);
                $train_id_row = mysqli_fetch_all($train_id);
                $train_id = $train_id_row[0][0];
                if ($this->status == 1) {
                    $delete_sql = "DELETE FROM route_entries WHERE entry_route = $train_id ";
                    if ($this->databaseConnection->query($delete_sql) === TRUE) {
                    } else {
                        echo "Error: " . $delete_sql . "<br>" . $this->databaseConnection->error;
                    }
                    echo $this->insertRouteEntries($journeyData[0]->stops, $train_id, $urlDate, $train_number, "update");
                }
            } else {
                echo $urlDate . "🔴CODE203 " . $train_number;
            }
        }
    }
    function updateData($train_number)
    {
        for ($i = 0; $i <= 10; $i++) {
            $urlDate = Date('Y-m-d', strtotime("+" . $i . " days"));
            echo $this->updateDataOnDate($train_number, $urlDate);
        }
    }
    function checkLine($line)
    {
        $line = mysqli_real_escape_string($this->databaseConnection, $line);
        $line_query = "SELECT line_id FROM train_lines WHERE line_name = '$line'";
        $line = $this->databaseConnection->query($line_query);
        $line_row = mysqli_fetch_all($line);
        $line = $line_row[0][0];
        if ($line == false) {
            $line = 0;
        }
        return $line;
    }
    function checkOperator($operator)
    {
        $operator = mysqli_real_escape_string($this->databaseConnection, $operator);
        $operator_query = "SELECT operator_id FROM operators WHERE operator_code = UPPER('$operator')";
        $operator = $this->databaseConnection->query($operator_query);
        $operator_row = mysqli_fetch_all($operator);
        $operator = $operator_row[0][0];
        if ($operator == false) {
            $operator = 0;
        }
        return $operator;
    }
}
