<?php
include "../config/connect.php";

$page = $_GET["page"];
$type = $_GET["type"];
$num = $_GET["num"];
$date = $_GET["date"];
if ($type == "date" && $date == "") {
    $type = "trains";
}
if ($page == "") {
    $page = "0";
}
$query = new getTrainData($databaseConnection);
switch ($type) {
    case "existing_train_numbers":
        echo json_encode($query->getTrainsNums());
        break;
    case "single_page_trains":
        echo json_encode($query->trainDataQuery("single_page_trains", $page, "", $date, ""));
        break;
    case "running_trains":
        echo json_encode($query->temp());
        break;
    case "single_train_number":
        echo json_encode($query->trainDataQuery("single_train_number", "", $num, "", ""));
        break;
}
class getTrainData
{
    private $databaseConnection;

    function __construct($conn)
    {
        $this->databaseConnection = $conn;
    }
    function getTrainsNums()
    {
        $preQuery = "SELECT train_number FROM train_numbers";
        $ex = $this->databaseConnection->query($preQuery);

        $rows = array();
        while ($r = mysqli_fetch_assoc($ex)) {
            $thisRow = $r;
            $rows[] = $thisRow["train_number"];
        }
        return $rows;
    }
    function mainQuery($date, $route_id, $trainNumber, $type)
    {
        $date = mysqli_real_escape_string($this->databaseConnection, $date);
        $route_id = mysqli_real_escape_string($this->databaseConnection, $route_id);
        $trainNumber = mysqli_real_escape_string($this->databaseConnection, $trainNumber);
        $type = mysqli_real_escape_string($this->databaseConnection, $type);
        $arguments = "";
        if ($date != "" && $trainNumber != "") {
            $arguments = " r.train_number = $trainNumber AND r.route_date = '$date'";
        } else if ($route_id != "") {
            $arguments = " r.route_id = $route_id";
        } else if ($trainNumber != "") {
            $arguments = " r.train_number = $trainNumber";
        }
        if ($arguments == "") {
            return 0;
        } else {
            // if ($date != "today") {
            //     $today = Date('Y-m-d', strtotime("$date -5 days"));
            //     $today1 = Date('Y-m-d', strtotime("$date -4 days"));
            //     $today2 = Date('Y-m-d', strtotime("$date -3 days"));
            //     $today3 = Date('Y-m-d', strtotime("$date -2 days"));
            //     $today4 = Date('Y-m-d', strtotime("$date -1 days"));
            //     $today5 = Date('Y-m-d', strtotime("$date +0 days"));
            //     $today6 = Date('Y-m-d', strtotime("$date +1 days"));
            //     $today7 = Date('Y-m-d', strtotime("$date +2 days"));
            //     $today8 = Date('Y-m-d', strtotime("$date +3 days"));
            //     $today9 = Date('Y-m-d', strtotime("$date +4 days"));
            //     $today10 = Date('Y-m-d', strtotime("$date +5 days"));
            // } else {
            $today = Date('Y-m-d', strtotime("today"));
            $today1 = Date('Y-m-d', strtotime("+1 days"));
            $today2 = Date('Y-m-d', strtotime("+2 days"));
            $today3 = Date('Y-m-d', strtotime("+3 days"));
            $today4 = Date('Y-m-d', strtotime("+4 days"));
            $today5 = Date('Y-m-d', strtotime("+5 days"));
            $today6 = Date('Y-m-d', strtotime("+6 days"));
            $today7 = Date('Y-m-d', strtotime("+7 days"));
            $today8 = Date('Y-m-d', strtotime("+8 days"));
            $today9 = Date('Y-m-d', strtotime("+9 days"));
            $today10 = Date('Y-m-d', strtotime("+10 days"));
            // }
            $query = "SELECT 
            r.route_id AS id,
            DATE_FORMAT(r.route_date, '%a %d\/%m\/%Y') AS route_date,
            DATE_FORMAT(r.route_date, '%Y-%m-%d') AS sort_date,
            DATE_FORMAT(r.route_date, '%Y-%m-%d') AS dt,
            r.train_number t,
            r.train_number,
            r.route_start,
            r.route_end,
            l.line_name AS line,
            o.operator_code AS operator,
            so.station_name  AS origin_station,
            sd.station_name AS destination_station,
            origin.cancellation  AS origin_cancellation,
            destination.cancellation AS destination_cancellation, 
            DATE_FORMAT(COALESCE(origin.planned_arrival,origin.planned_departure), '%H:%i')  AS origin_planned_time,
            DATE_FORMAT(COALESCE(destination.planned_arrival,destination.planned_departure), '%H:%i') AS destination_planned_time,
            DATE_FORMAT(COALESCE(origin.actual_arrival, origin.expected_arrival,origin.actual_departure, origin.expected_departure), '%H:%i') AS origin_real_time,
            DATE_FORMAT(COALESCE(destination.actual_arrival, destination.expected_arrival,destination.actual_departure, destination.expected_departure), '%H:%i') AS destination_real_time";
            if ($type != "one_num") {
                $query .= ", (SELECT COUNT(train_number) FROM routes WHERE train_number = t AND route_date = '$today' LIMIT 1) AS today0,
            (SELECT COUNT(train_number) FROM routes WHERE train_number = t AND route_date = '$today1' LIMIT 1) AS today1,
            (SELECT COUNT(train_number) FROM routes WHERE train_number = t AND route_date = '$today2' LIMIT 1) AS today2,
            (SELECT COUNT(train_number) FROM routes WHERE train_number = t AND route_date = '$today3' LIMIT 1) AS today3,
            (SELECT COUNT(train_number) FROM routes WHERE train_number = t AND route_date = '$today4' LIMIT 1) AS today4,
            (SELECT COUNT(train_number) FROM routes WHERE train_number = t AND route_date = '$today5' LIMIT 1) AS today5,
            (SELECT COUNT(train_number) FROM routes WHERE train_number = t AND route_date = '$today6' LIMIT 1) AS today6,
            (SELECT COUNT(train_number) FROM routes WHERE train_number = t AND route_date = '$today7' LIMIT 1) AS today7,
            (SELECT COUNT(train_number) FROM routes WHERE train_number = t AND route_date = '$today8' LIMIT 1) AS today8,
            (SELECT COUNT(train_number) FROM routes WHERE train_number = t AND route_date = '$today9' LIMIT 1) AS today9,
            (SELECT COUNT(train_number) FROM routes WHERE train_number = t AND route_date = '$today10' LIMIT 1) AS today10";
            }
            $query .= " FROM routes r
            INNER JOIN route_entries origin ON r.route_id = origin.entry_route AND origin.entry_id = (SELECT entry_id FROM route_entries WHERE entry_route = r.route_id ORDER BY entry_number ASC LIMIT 1)
            INNER JOIN route_entries destination ON r.route_id = destination.entry_route AND destination.entry_id = (SELECT entry_id FROM route_entries WHERE entry_route = r.route_id ORDER BY entry_number DESC LIMIT 1)
            INNER JOIN stations so ON origin.entry_station = so.station_ref
            INNER JOIN stations sd ON destination.entry_station = sd.station_ref
            INNER JOIN operators o ON r.route_operator = o.operator_id
            INNER JOIN train_lines l ON r.route_line = l.line_id
            WHERE $arguments AND route_date >= '2021-12-01';";

            $ex = $this->databaseConnection->query($query);
            if ($route_id != "") {
                while ($r = mysqli_fetch_assoc($ex)) {
                    $rows = $r;
                }
            } else {
                $rows = array();
                while ($r = mysqli_fetch_assoc($ex)) {
                    $rows[] = $r;
                }
            }
            return $rows;
        }
    }


    function preQuery($urlDate, $pageStart, $pageEnd, $trainNum, $type)
    {
        $urlDate = mysqli_real_escape_string($this->databaseConnection, $urlDate);
        $pageStart = mysqli_real_escape_string($this->databaseConnection, $pageStart);
        $pageEnd = mysqli_real_escape_string($this->databaseConnection, $pageEnd);
        $trainNum = mysqli_real_escape_string($this->databaseConnection, $trainNum);
        $type = mysqli_real_escape_string($this->databaseConnection, $type);
        $arguments = "";
        if (strval($pageStart) != "" && strval($pageEnd) != "") {
            $arguments = " train_number >= $pageStart AND train_number <= $pageEnd";
        } else if ($trainNum != "") {
            $arguments = " train_number == $trainNum";
        }
        if ($arguments == "") {
            return 0;
        } else {

            $preQuery = "SELECT 
            train_number AS 'num',
            IFNULL((SELECT route_id 
                FROM routes 
                WHERE train_number = num AND route_date <= '$urlDate' AND route_date >= '2021-12-01' 
                ORDER BY route_date DESC 
                LIMIT 1),
            (SELECT route_id 
                FROM routes 
                WHERE train_number = num 
                AND route_date >= '$urlDate' 
                ORDER BY route_date ASC 
                LIMIT 1)) AS 'route_id'
            FROM train_numbers WHERE $arguments";
            $ex = $this->databaseConnection->query($preQuery);
            return $ex;
        }
    }
    function trainDataQuery($type, $page, $trainNumber, $date, $route_id)
    {

        if ($page != "") {
            $pageStart = $page * 1000;
            $pageEnd = ($page * 1000) + 999;
        }
        switch ($type) {
            case "single_page_trains":
                if ($date == "") {
                    $date = Date('Y-m-d', strtotime("today"));
                    $date2 = "today";
                }
                $preQuery = $this->preQuery($date, $pageStart, $pageEnd, "", "");
                $rows = array();
                while ($thisRow = mysqli_fetch_assoc($preQuery)) {
                    $route_id = $thisRow["route_id"];
                    if ($route_id != null) {
                        $rows[] = $this->mainQuery($date2, $route_id, "", "");
                    }
                }
                return $rows;
                break;
            case "single_train_number":
                if ($trainNumber != null) {
                    $rows = $this->mainQuery("", "", $trainNumber, "");
                }
                return $rows;
                break;
            case "running_trains":
                if ($trainNumber != null) {
                    $rows = $this->mainQuery("", "", $trainNumber, "running");
                }
                return $rows;
                break;
        }
    }
    function temp()
    {
        $query = "SELECT 
                    route_id,
                    train_number,
                    l.line_name AS line,
                    o.operator_code AS operator
                FROM routes
                    INNER JOIN operators o ON route_operator = o.operator_id
                    INNER JOIN train_lines l ON route_line = l.line_id
                WHERE current_timestamp() BETWEEN route_start AND route_end
                ORDER BY route_start;";
        $ex = $this->databaseConnection->query($query);
        $rows = array();
        while ($r = mysqli_fetch_assoc($ex)) {
            $rows[] = $r;
        }
        return $rows;
    }
}
