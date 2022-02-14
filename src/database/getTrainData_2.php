<?php
include "../config/connect.php";

$page = $_GET["page"];
$type = $_GET["type"];
$num = $_GET["num"];
$date = $_GET["date"];
if ($type == "date" && $date == "") {
    $type = "trains";
}
$query = new getTrainData($databaseConnection);
switch ($type) {
    case "train":
        echo json_encode($query->getTrainExtended($num));
        break;
    case "single":
        // echo json_encode($query->getSingleTrainNumber($num, $date));
        echo json_encode($query->trainDataQuery("one_num", "", $num, "", ""));
        break;
    case "single_train":
        echo json_encode($query->getSingleTrainNumberRow($num, $date));
        break;
    case "single_date":
        // echo json_encode($query->trainDataQuery("single_train", "", "", $date, ""));
        echo json_encode($query->getSingleTrainNumberDate($num, $date));
        break;
    case "trains":
        // echo json_encode($query->getTrainsExtended($page));
        echo json_encode($query->trainDataQuery("page", $page, "", "", ""));
        break;
    case "date":
        // echo json_encode($query->trainDataQuery("date_page", $page, "", $date, ""));
        echo json_encode($query->getSingleDateTrains($page, $date));
        break;
    case "trainNum":
        // echo json_encode($query->getTrainsExtended($page));
        echo json_encode($query->trainDataQuery("date_page", $page, "", "", ""));
        break;
    case "trainNums":
        echo json_encode($query->getTrainsNums());
        break;

    case "new":
        echo json_encode($query->trainDataQuery("one_num", "", $num, "", ""));
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
    function getSingleTrainNumber($trainNumber)
    {

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



        $query = "SELECT
                route_status,
                route_id AS id,
                DATE_FORMAT(route_date,'%a %d/%m/%Y') AS route_date,
                DATE_FORMAT(route_date,'%Y-%m-%d') AS sort_date,
                train_number t,
                train_number,
                train_lines.line_name AS line,
                operators.operator_code AS operator,
                (
                    SELECT
                        stations.station_name
                    FROM
                        route_entries
                        INNER JOIN stations ON route_entries.entry_station = stations.station_ref
                    WHERE
                        route_id = id
                    ORDER BY
                        entry_number ASC
                    LIMIT
                        1
                ) AS origin_station,
                (
                    SELECT
                        DATE_FORMAT(COALESCE(actual_arrival, expected_arrival, planned_arrival, entry_arrival_time), '%H:%i')
                    FROM
                        route_entries
                    WHERE
                        route_id = id
                    ORDER BY
                        entry_number ASC
                    LIMIT
                        1
                ) AS origin_arrival,
                (
                    SELECT
                    DATE_FORMAT(COALESCE(actual_departure, expected_departure, planned_departure, entry_departure_time), '%H:%i')
                    FROM
                        route_entries
                    WHERE
                        route_id = id
                    ORDER BY
                        entry_number ASC
                    LIMIT
                        1
                ) AS origin_departure,
                (
                    SELECT
                        stations.station_name
                    FROM
                        route_entries
                        INNER JOIN stations ON route_entries.entry_station = stations.station_ref
                    WHERE
                        route_id = id
                    ORDER BY
                        entry_number DESC
                    LIMIT
                        1
                ) AS destination_station,
                (
                    SELECT
                        DATE_FORMAT(COALESCE(actual_arrival, expected_arrival, planned_arrival, entry_arrival_time), '%H:%i')
                    FROM
                        route_entries
                    WHERE
                        route_id = id
                    ORDER BY
                        entry_number DESC
                    LIMIT
                        1
                ) AS destination_arrival,
                (
                    SELECT
                    DATE_FORMAT(COALESCE(actual_departure, expected_departure, planned_departure, entry_departure_time), '%H:%i')
                    FROM
                        route_entries
                    WHERE
                        route_id = id
                    ORDER BY
                        entry_number DESC
                    LIMIT
                        1
                ) AS destination_departure,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today'
                    LIMIT 1) AS today0,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today1'
                    LIMIT 1) AS today1,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today2'
                    LIMIT 1) AS today2,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today3'
                    LIMIT 1) AS today3,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today4'
                    LIMIT 1) AS today4,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today5'
                    LIMIT 1) AS today5,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today6'
                    LIMIT 1) AS today6,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today7'
                    LIMIT 1) AS today7,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today8'
                    LIMIT 1) AS today8,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today9'
                    LIMIT 1) AS today9,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today10'
                    LIMIT 1) AS today10
                FROM routes
                INNER JOIN operators ON routes.route_operator = operators.operator_id
                INNER JOIN train_lines ON routes.route_line = train_lines.line_id WHERE train_number = $trainNumber ORDER BY sort_date DESC;";
        $ex2 = $this->databaseConnection->query($query);

        while ($r = mysqli_fetch_assoc($ex2)) {
            $rows[] = $r;
        }
        return $rows;
    }
    function getSingleTrainNumberRow($trainNumber, $date)
    {
        if ($date != "") {
            $today = Date('Y-m-d', strtotime($date . "-5 days"));
            $today1 = Date('Y-m-d', strtotime($date . "-4 days"));
            $today2 = Date('Y-m-d', strtotime($date . "-3 days"));
            $today3 = Date('Y-m-d', strtotime($date . "-2 days"));
            $today4 = Date('Y-m-d', strtotime($date . "-1 days"));
            $today5 = Date('Y-m-d', strtotime($date . "+0 days"));
            $today6 = Date('Y-m-d', strtotime($date . "+1 days"));
            $today7 = Date('Y-m-d', strtotime($date . "+2 days"));
            $today8 = Date('Y-m-d', strtotime($date . "+3 days"));
            $today9 = Date('Y-m-d', strtotime($date . "+4 days"));
            $today10 = Date('Y-m-d', strtotime($date . "+5 days"));
        } else {
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
        }


        $query = "SELECT
                route_status,
                route_id AS id,
                DATE_FORMAT(route_date,'%a %d/%m/%Y') AS route_date,
                DATE_FORMAT(route_date,'%Y-%m-%d') AS sort_date,
                train_number t,
                train_number,
                train_lines.line_name AS line,
                operators.operator_code AS operator,
                (
                    SELECT
                        stations.station_name
                    FROM
                        route_entries
                        INNER JOIN stations ON route_entries.entry_station = stations.station_ref
                    WHERE
                        route_id = id
                    ORDER BY
                        entry_number ASC
                    LIMIT
                        1
                ) AS origin_station,
                (
                    SELECT
                        DATE_FORMAT(COALESCE(actual_arrival, expected_arrival, planned_arrival, entry_arrival_time), '%H:%i')
                    FROM
                        route_entries
                    WHERE
                        route_id = id
                    ORDER BY
                        entry_number ASC
                    LIMIT
                        1
                ) AS origin_arrival,
                (
                    SELECT
                    DATE_FORMAT(COALESCE(actual_departure, expected_departure, planned_departure, entry_departure_time), '%H:%i')
                    FROM
                        route_entries
                    WHERE
                        route_id = id
                    ORDER BY
                        entry_number ASC
                    LIMIT
                        1
                ) AS origin_departure,
                (
                    SELECT
                        stations.station_name
                    FROM
                        route_entries
                        INNER JOIN stations ON route_entries.entry_station = stations.station_ref
                    WHERE
                        route_id = id
                    ORDER BY
                        entry_number DESC
                    LIMIT
                        1
                ) AS destination_station,
                (
                    SELECT
                        DATE_FORMAT(COALESCE(actual_arrival, expected_arrival, planned_arrival, entry_arrival_time), '%H:%i')
                    FROM
                        route_entries
                    WHERE
                        route_id = id
                    ORDER BY
                        entry_number DESC
                    LIMIT
                        1
                ) AS destination_arrival,
                (
                    SELECT
                    DATE_FORMAT(COALESCE(actual_departure, expected_departure, planned_departure, entry_departure_time), '%H:%i')
                    FROM
                        route_entries
                    WHERE
                        route_id = id
                    ORDER BY
                        entry_number DESC
                    LIMIT
                        1
                ) AS destination_departure,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today'
                    LIMIT 1) AS today0,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today1'
                    LIMIT 1) AS today1,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today2'
                    LIMIT 1) AS today2,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today3'
                    LIMIT 1) AS today3,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today4'
                    LIMIT 1) AS today4,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today5'
                    LIMIT 1) AS today5,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today6'
                    LIMIT 1) AS today6,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today7'
                    LIMIT 1) AS today7,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today8'
                    LIMIT 1) AS today8,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today9'
                    LIMIT 1) AS today9,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today10'
                    LIMIT 1) AS today10
                FROM routes
                INNER JOIN operators ON routes.route_operator = operators.operator_id
                INNER JOIN train_lines ON routes.route_line = train_lines.line_id WHERE train_number = $trainNumber;";
        $ex2 = $this->databaseConnection->query($query);

        while ($r = mysqli_fetch_assoc($ex2)) {
            $rows[] = $r;
        }


        return $rows;
    }
    function getSingleTrainNumberDate($trainNumber, $date)
    {

        $query = "SELECT
                route_status,
                route_id AS id,
                DATE_FORMAT(route_date,'%a %d/%m/%Y') AS route_date,
                DATE_FORMAT(route_date,'%Y-%m-%d') AS sort_date,
                train_number,
                train_lines.line_name AS line,
                operators.operator_code AS operator,
                (
                    SELECT
                        stations.station_name
                    FROM
                        route_entries
                        INNER JOIN stations ON route_entries.entry_station = stations.station_ref
                    WHERE
                        route_id = id
                    ORDER BY
                        entry_number ASC
                    LIMIT
                        1
                ) AS origin_station,
                (
                    SELECT
                        DATE_FORMAT(COALESCE(actual_arrival, expected_arrival, planned_arrival, entry_arrival_time), '%H:%i')
                    FROM
                        route_entries
                    WHERE
                        route_id = id
                    ORDER BY
                        entry_number ASC
                    LIMIT
                        1
                ) AS origin_arrival,
                (
                    SELECT DATE_FORMAT(COALESCE(actual_departure, expected_departure, planned_departure, entry_departure_time), '%H:%i')
                    FROM route_entries
                    WHERE route_id = id
                    ORDER BY entry_number ASC
                    LIMIT 1
                ) AS origin_departure,
                (
                    SELECT stations.station_name
                    FROM route_entries
                        INNER JOIN stations ON route_entries.entry_station = stations.station_ref
                    WHERE route_id = id
                    ORDER BY entry_number DESC
                    LIMIT 1
                ) AS destination_station,
                (
                    SELECT DATE_FORMAT(COALESCE(actual_arrival, expected_arrival, planned_arrival, entry_arrival_time), '%H:%i')
                    FROM route_entries
                    WHERE route_id = id
                    ORDER BY entry_number DESC
                    LIMIT 1
                ) AS destination_arrival,
                (
                    SELECT DATE_FORMAT(COALESCE(actual_departure, expected_departure, planned_departure, entry_departure_time), '%H:%i')
                    FROM route_entries
                    WHERE route_id = id
                    ORDER BY entry_number DESC
                    LIMIT
                        1
                ) AS destination_departure 
                FROM routes
                INNER JOIN operators ON routes.route_operator = operators.operator_id
                INNER JOIN train_lines ON routes.route_line = train_lines.line_id WHERE train_number = $trainNumber AND route_date = '$date' ORDER BY sort_date ASC;";
        $ex2 = $this->databaseConnection->query($query);

        while ($r = mysqli_fetch_assoc($ex2)) {
            $rows[] = $r;
        }
        return $rows;
    }
    function getTrainsExtended($page)
    {
        $urlDate = Date('Y-m-d', strtotime("+" . 0 . " days"));
        if ($page == "") {
            $page = 0;
        }
        $pageStart = $page * 1000;
        $pageEnd = ($page * 1000) + 999;
        $preQuery = "SELECT 
            train_number AS 'num',
            IFNULL((SELECT 
                    route_id
                FROM
                    routes
                WHERE
                    train_number = num
                        AND route_date <= '$urlDate'
                ORDER BY route_date DESC
                LIMIT 1),
            (SELECT 
                    route_id
                FROM
                    routes
                WHERE
                    train_number = num
                        AND route_date >= '$urlDate'
                ORDER BY route_date ASC
                LIMIT 1)) AS 'route_id'
        FROM
            train_numbers WHERE train_number >= $pageStart AND train_number <= $pageEnd";
        $rows = array();
        $ex = $this->databaseConnection->query($preQuery);
        $route_ids = array();

        $today = Date('Y-m-d', strtotime("today"));
        $today1 = Date('Y-m-d', strtotime("+" . 1 . " days"));
        $today2 = Date('Y-m-d', strtotime("+" . 2 . " days"));
        $today3 = Date('Y-m-d', strtotime("+" . 3 . " days"));
        $today4 = Date('Y-m-d', strtotime("+" . 4 . " days"));
        $today5 = Date('Y-m-d', strtotime("+" . 5 . " days"));
        $today6 = Date('Y-m-d', strtotime("+" . 6 . " days"));
        $today7 = Date('Y-m-d', strtotime("+" . 7 . " days"));
        $today8 = Date('Y-m-d', strtotime("+" . 8 . " days"));
        $today9 = Date('Y-m-d', strtotime("+" . 9 . " days"));
        $today10 = Date('Y-m-d', strtotime("+" . 10 . " days"));

        while ($r = mysqli_fetch_assoc($ex)) {
            $thisRow = $r;
            $route_ids[] = $thisRow["num"] . " " . $thisRow["route_id"];

            $route_id = $thisRow["route_id"];


            if ($route_id != null) {
                $query = "SELECT 
                route_status,
                route_id AS id,
                DATE_FORMAT(route_date, '%a %d/%m/%Y') AS route_date,
                DATE_FORMAT(route_date, '%Y-%m-%d') AS sort_date,
                train_number t,
                train_number,
                train_lines.line_name AS line,
                operators.operator_code AS operator,
                (SELECT stations.station_name 
                    FROM route_entries 
                    INNER JOIN stations ON route_entries.entry_station = stations.station_ref
                    WHERE entry_route = id 
                    ORDER BY entry_number ASC 
                    LIMIT 1) AS origin_station,
                (SELECT DATE_FORMAT(COALESCE(actual_arrival, expected_arrival, planned_arrival, entry_arrival_time), '%H:%i') 
                    FROM route_entries
                    WHERE entry_route = id
                    ORDER BY entry_number ASC
                    LIMIT 1) AS origin_arrival,
                (SELECT DATE_FORMAT(COALESCE(actual_departure, expected_departure, planned_departure, entry_departure_time), '%H:%i') 
                    FROM route_entries 
                    WHERE entry_route = id 
                    ORDER BY entry_number ASC 
                    LIMIT 1) AS origin_departure,
                (SELECT stations.station_name 
                    FROM route_entries 
                    INNER JOIN stations ON route_entries.entry_station = stations.station_ref 
                    WHERE entry_route = id 
                    ORDER BY entry_number DESC 
                    LIMIT 1) AS destination_station,
                (SELECT DATE_FORMAT(COALESCE(actual_arrival, expected_arrival, planned_arrival, entry_arrival_time), '%H:%i') 
                    FROM route_entries 
                    WHERE entry_route = id 
                    ORDER BY entry_number DESC 
                    LIMIT 1) AS destination_arrival,
                (SELECT DATE_FORMAT(COALESCE(actual_departure, expected_departure, planned_departure, entry_departure_time), '%H:%i') 
                    FROM route_entries 
                    WHERE entry_route = id 
                    ORDER BY entry_number DESC 
                    LIMIT 1) AS destination_departure,
                (SELECT stations.station_name FROM route_entries INNER JOIN stations ON route_entries.entry_station = stations.station_ref WHERE entry_route = id ORDER BY entry_number ASC LIMIT 1) AS origin_station,
                (SELECT COUNT(train_number) 
                    FROM routes 
                    WHERE train_number = t AND route_date = '$today' 
                    LIMIT 1) AS today0,
                (SELECT COUNT(train_number) 
                    FROM routes 
                    WHERE train_number = t AND route_date = '$today1' 
                    LIMIT 1) AS today1,
                (SELECT COUNT(train_number) 
                    FROM routes 
                    WHERE train_number = t AND route_date = '$today2' 
                    LIMIT 1) AS today2,
                (SELECT COUNT(train_number) 
                    FROM routes 
                    WHERE train_number = t AND route_date = '$today3' 
                    LIMIT 1) AS today3,
                (SELECT COUNT(train_number) 
                    FROM routes 
                    WHERE train_number = t AND route_date = '$today4' 
                    LIMIT 1) AS today4,
                (SELECT COUNT(train_number) 
                    FROM routes 
                    WHERE train_number = t AND route_date = '$today5' 
                    LIMIT 1) AS today5,
                (SELECT COUNT(train_number) 
                    FROM routes 
                    WHERE train_number = t AND route_date = '$today6' 
                    LIMIT 1) AS today6,
                (SELECT COUNT(train_number) 
                    FROM routes 
                    WHERE train_number = t AND route_date = '$today7' 
                    LIMIT 1) AS today7,
                (SELECT COUNT(train_number) 
                    FROM routes 
                    WHERE train_number = t AND route_date = '$today8' 
                    LIMIT 1) AS today8,
                (SELECT COUNT(train_number) 
                    FROM routes 
                    WHERE train_number = t AND route_date = '$today9' 
                    LIMIT 1) AS today9,
                (SELECT COUNT(train_number) 
                    FROM routes 
                    WHERE train_number = t AND route_date = '$today10'
                    LIMIT 1) AS today10
            FROM routes
            INNER JOIN operators ON routes.route_operator = operators.operator_id
            INNER JOIN train_lines ON routes.route_line = train_lines.line_id
            WHERE entry_route = $route_id;";
                $ex2 = $this->databaseConnection->query($query);

                while ($r = mysqli_fetch_assoc($ex2)) {
                    $rows[] = $r;
                }
            }
        }

        return $rows;
    }
    function getTrainExtended($train)
    {
        $urlDate = Date('Y-m-d', strtotime("+" . 0 . " days"));

        $preQuery = "SELECT 
        train_number AS 'num',
        IFNULL((SELECT 
                route_id
            FROM
                routes
            WHERE
                train_number = num
                    AND route_date <= '$urlDate'
            ORDER BY route_date DESC
            LIMIT 1),
        (SELECT 
                route_id
            FROM
                routes
            WHERE
                train_number = num
                    AND route_date >= '$urlDate'
            ORDER BY route_date ASC
            LIMIT 1)) AS 'route_id'
        FROM
        train_numbers WHERE train_number = $train";
        $row = array();
        $ex = $this->databaseConnection->query($preQuery);

        while ($r = mysqli_fetch_assoc($ex)) {
            $thisRow = $r;
            $route_id = $thisRow["route_id"];
            if ($route_id != null) {
                $query = "SELECT
                route_status,
                route_id AS id,
                DATE_FORMAT(route_date,'%a %d/%m/%Y') AS route_date,
                DATE_FORMAT(route_date,'%Y-%m-%d') AS sort_date,
                train_number,
                train_lines.line_name AS line,
                operators.operator_code AS operator,
                (
                    SELECT
                        stations.station_name
                    FROM
                        route_entries
                        INNER JOIN stations ON route_entries.entry_station = stations.station_ref
                    WHERE
                        route_id = id
                    ORDER BY
                        entry_number ASC
                    LIMIT
                        1
                ) AS origin_station,
                (
                    SELECT
                        DATE_FORMAT(COALESCE(planned_arrival,entry_arrival_time), '%H:%i')
                    FROM
                        route_entries
                    WHERE
                        route_id = id
                    ORDER BY
                        entry_number ASC
                    LIMIT
                        1
                ) AS origin_planned_arrival,
                (
                    SELECT
                        DATE_FORMAT(COALESCE(actual_arrival,expected_arrival), '%H:%i')
                    FROM
                        route_entries
                    WHERE
                        route_id = id
                    ORDER BY
                        entry_number ASC
                    LIMIT
                        1
                ) AS origin_arrival,
                (
                    SELECT
                    DATE_FORMAT(COALESCE(actual_departure, expected_departure, planned_departure, entry_departure_time), '%H:%i')
                    FROM
                        route_entries
                    WHERE
                        route_id = id
                    ORDER BY
                        entry_number ASC
                    LIMIT
                        1
                ) AS origin_departure,
                (
                    SELECT
                        stations.station_name
                    FROM
                        route_entries
                        INNER JOIN stations ON route_entries.entry_station = stations.station_ref
                    WHERE
                    entry_id = id
                    ORDER BY
                        entry_number DESC
                    LIMIT
                        1
                ) AS destination_station,
                (
                    SELECT
                        DATE_FORMAT(COALESCE(actual_arrival, expected_arrival, planned_arrival, entry_arrival_time), '%H:%i')
                    FROM
                        route_entries
                    WHERE
                    entry_id = id
                    ORDER BY
                        entry_number DESC
                    LIMIT
                        1
                ) AS destination_arrival,
                (
                    SELECT
                    DATE_FORMAT(COALESCE(actual_departure, expected_departure, planned_departure, entry_departure_time), '%H:%i')
                    FROM
                        route_entries
                    WHERE
                    entry_id = id
                    ORDER BY
                        entry_number DESC
                    LIMIT
                        1
                ) AS destination_departure 
                FROM routes
                INNER JOIN operators ON routes.route_operator = operators.operator_id
                INNER JOIN train_lines ON routes.route_line = train_lines.line_id WHERE entry_id =$route_id;";
                $ex2 = $this->databaseConnection->query($query);

                while ($r = mysqli_fetch_assoc($ex2)) {
                    $row[] = $r;
                }
            }
        }

        return $row;
    }

    function mainQuery($date, $route_id, $trainNumber, $type)
    {
        $arguments = "";
        if ($date != "" && $trainNumber != "") {
            $arguments = " train_number = $trainNumber AND route_date = '$date'";
        } else if ($route_id != "") {
            $arguments = " route_id = $route_id";
        } else if ($trainNumber != "") {
            $arguments = " train_number = $trainNumber";
        }
        if ($arguments == "") {
            return 0;
        } else {
            if ($date != "") {
                $today = Date('Y-m-d', strtotime("$date -5 days"));
                $today1 = Date('Y-m-d', strtotime("$date -4 days"));
                $today2 = Date('Y-m-d', strtotime("$date -3 days"));
                $today3 = Date('Y-m-d', strtotime("$date -2 days"));
                $today4 = Date('Y-m-d', strtotime("$date -1 days"));
                $today5 = Date('Y-m-d', strtotime("$date +0 days"));
                $today6 = Date('Y-m-d', strtotime("$date +1 days"));
                $today7 = Date('Y-m-d', strtotime("$date +2 days"));
                $today8 = Date('Y-m-d', strtotime("$date +3 days"));
                $today9 = Date('Y-m-d', strtotime("$date +4 days"));
                $today10 = Date('Y-m-d', strtotime("$date +5 days"));
            } else {
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
            }
            $query = "SELECT 
            route_status,
            route_id AS id,
            DATE_FORMAT(route_date, '%a %d/%m/%Y') AS route_date,
            DATE_FORMAT(route_date, '%Y-%m-%d') AS sort_date,
                DATE_FORMAT(route_date, '%Y-%m-%d') AS dt,
            train_number t,
            train_number,
            train_lines.line_name AS line,
            operators.operator_code AS operator,
            (SELECT stations.station_name FROM route_entries 
                INNER JOIN stations ON route_entries.entry_station = stations.station_ref
                WHERE entry_id = id ORDER BY entry_number ASC LIMIT 1) AS origin_station,
            (SELECT DATE_FORMAT(COALESCE(planned_arrival, entry_arrival_time), '%H:%i') 
                FROM route_entries WHERE entry_id = id 
                ORDER BY entry_number ASC LIMIT 1) AS origin_planned_arrival,
            (SELECT DATE_FORMAT(COALESCE(actual_arrival, expected_arrival), '%H:%i') 
                FROM route_entries WHERE entry_id = id 
                ORDER BY entry_number ASC LIMIT 1) AS origin_arrival,
            (SELECT DATE_FORMAT(COALESCE(planned_departure, entry_departure_time), '%H:%i') 
                FROM route_entries WHERE entry_id = id 
                ORDER BY entry_number ASC LIMIT 1) AS origin_planned_departure,
            (SELECT DATE_FORMAT(COALESCE(actual_departure, expected_departure), '%H:%i') 
                FROM route_entries WHERE entry_id = id 
                ORDER BY entry_number ASC LIMIT 1) AS origin_departure,
            (SELECT stations.station_name FROM route_entries 
                INNER JOIN stations ON route_entries.entry_station = stations.station_ref 
                WHERE entry_id = id ORDER BY entry_number DESC LIMIT 1) AS destination_station,
            (SELECT DATE_FORMAT(COALESCE(planned_arrival, entry_arrival_time), '%H:%i') 
                FROM entry_id.route_entries WHERE entry_id = id 
                ORDER BY entry_number DESC LIMIT 1) AS destination_planned_arrival,
            (SELECT DATE_FORMAT(COALESCE(actual_arrival, expected_arrival), '%H:%i') 
                FROM entry_id.route_entries WHERE entry_id = id 
                ORDER BY entry_number DESC LIMIT 1) AS destination_arrival";
            if ($type != "one_num") {
                $query .= ",(SELECT COUNT(train_number) FROM routes WHERE train_number = t AND route_date = '$today' LIMIT 1) AS today0,
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
            $query .= " FROM routes
            INNER JOIN operators ON routes.route_operator = operators.operator_id
            INNER JOIN train_lines ON routes.route_line = train_lines.line_id
            WHERE $arguments;";
            $ex = $this->databaseConnection->query($query);
            while ($r = mysqli_fetch_assoc($ex)) {
                $rows[] = $r;
            }


            return $rows;
        }
    }


    function preQuery($urlDate, $pageStart, $pageEnd, $trainNum)
    {
        $arguments = "";
        if ($pageStart != "" && $pageEnd != "") {
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
                WHERE train_number = num AND route_date <= '$urlDate' 
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
            case "page":
            case "date_page":
                $preQuery = $this->preQuery($date, $pageStart, $pageEnd, "");
                $rows = array();
                while ($thisRow = mysqli_fetch_assoc($preQuery)) {
                    $route_id = $thisRow["route_id"];
                    if ($route_id != null) {
                        $rows[] = $this->mainQuery("", $route_id, "", "");
                    }
                }
                return $rows;
                break;
            case "date_train":
            case "view_train":
                $preQuery = $this->preQuery($date, "", "", $trainNumber);
                break;
            case "single_train":
                return $this->mainQuery($date, "", $trainNumber, "single_train");
                break;
            case "one_num":
                // $preQuery = $this->preQuery("","","", $trainNumber);

                return $this->mainQuery("", "", $trainNumber, "one_num");
                break;
        }
    }

    function getSingleDateTrains($page, $date)
    {
        $urlDate = Date('Y-m-d', strtotime($date));
        if ($page == "") {
            $page = 0;
        }
        $pageStart = $page * 1000;
        $pageEnd = ($page * 1000) + 999;
        $preQuery = "SELECT 
            train_number AS 'num',
            IFNULL((SELECT route_id
                FROM routes
                WHERE train_number = num AND route_date <= '$urlDate'
                ORDER BY route_date DESC
                LIMIT 1),
            (SELECT route_id
                FROM routes
                WHERE train_number = num AND route_date >= '$urlDate'
                ORDER BY route_date ASC
                LIMIT 1)) AS 'route_id'
        FROM
            train_numbers WHERE train_number >= $pageStart AND train_number <= $pageEnd";
        $rows = array();
        $ex = $this->databaseConnection->query($preQuery);
        $route_ids = array();

        $today = Date('Y-m-d', strtotime($urlDate . "-5 days"));
        $today1 = Date('Y-m-d', strtotime($urlDate . "-4 day"));
        $today2 = Date('Y-m-d', strtotime($urlDate . "-3 days"));
        $today3 = Date('Y-m-d', strtotime($urlDate . "-2 days"));
        $today4 = Date('Y-m-d', strtotime($urlDate . "-1 days"));
        $today5 = Date('Y-m-d', strtotime($urlDate . "+0 days"));
        $today6 = Date('Y-m-d', strtotime($urlDate . "+1 days"));
        $today7 = Date('Y-m-d', strtotime($urlDate . "+2 days"));
        $today8 = Date('Y-m-d', strtotime($urlDate . "+3 days"));
        $today9 = Date('Y-m-d', strtotime($urlDate . "+4 days"));
        $today10 = Date('Y-m-d', strtotime($urlDate . "+5 days"));

        while ($r = mysqli_fetch_assoc($ex)) {
            $thisRow = $r;
            $route_ids[] = $thisRow["num"] . " " . $thisRow["route_id"];

            $route_id = $thisRow["route_id"];


            if ($route_id != null) {
                $query = "SELECT 
                route_status,
                route_id AS id,
                DATE_FORMAT(route_date, '%a %d/%m/%Y') AS route_date,
                DATE_FORMAT(route_date, '%Y-%m-%d') AS sort_date,
                DATE_FORMAT(route_date, '%Y-%m-%d') AS dt,
                train_number t,
                train_number,
                train_lines.line_name AS line,
                operators.operator_code AS operator,
                (SELECT 
                        stations.station_name
                    FROM
                        route_entries
                            INNER JOIN
                        stations ON route_entries.entry_station = stations.station_ref
                    WHERE
                        route_id = id
                    ORDER BY entry_number ASC
                    LIMIT 1) AS origin_station,
                (SELECT 
                    DATE_FORMAT(COALESCE(actual_arrival, expected_arrival, planned_arrival, 'a'), '%H:%i')
                    FROM
                        route_entries
                    WHERE
                        route_id = id
                    ORDER BY entry_number ASC
                    LIMIT 1) AS origin_arrival,
                (SELECT 
                DATE_FORMAT(COALESCE(actual_departure, expected_departure, planned_departure, 'a'), '%H:%i')
                    FROM
                        route_entries
                    WHERE
                        route_id = id
                    ORDER BY entry_number ASC
                    LIMIT 1) AS origin_departure,
                (SELECT 
                        stations.station_name
                    FROM
                        route_entries
                            INNER JOIN
                        stations ON route_entries.entry_station = stations.station_ref
                    WHERE
                        route_id = id
                    ORDER BY entry_number DESC
                    LIMIT 1) AS destination_station,
                (SELECT 
                DATE_FORMAT(COALESCE(actual_arrival, expected_arrival, planned_arrival, 'a'), '%H:%i')
                    FROM
                        route_entries
                    WHERE
                        route_id = id
                    ORDER BY entry_number DESC
                    LIMIT 1) AS destination_arrival,
                (SELECT 
                DATE_FORMAT(COALESCE(actual_departure, expected_departure, planned_departure, 'a'), '%H:%i')
                    FROM
                        route_entries
                    WHERE
                        route_id = id
                    ORDER BY entry_number DESC
                    LIMIT 1) AS destination_departure,
                (SELECT 
                        stations.station_name
                    FROM
                        route_entries
                            INNER JOIN
                        stations ON route_entries.entry_station = stations.station_ref
                    WHERE
                        route_id = id
                    ORDER BY entry_number ASC
                    LIMIT 1) AS origin_station,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today'
                    LIMIT 1) AS today0,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today1'
                    LIMIT 1) AS today1,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today2'
                    LIMIT 1) AS today2,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today3'
                    LIMIT 1) AS today3,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today4'
                    LIMIT 1) AS today4,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today5'
                    LIMIT 1) AS today5,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today6'
                    LIMIT 1) AS today6,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today7'
                    LIMIT 1) AS today7,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today8'
                    LIMIT 1) AS today8,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today9'
                    LIMIT 1) AS today9,
                (SELECT 
                        COUNT(train_number)
                    FROM
                        routes
                    WHERE
                        train_number = t
                            AND route_date = '$today10'
                    LIMIT 1) AS today10
            FROM
                routes
                    INNER JOIN
                operators ON routes.route_operator = operators.operator_id
                    INNER JOIN
                train_lines ON routes.route_line = train_lines.line_id
            WHERE
                route_id = $route_id;";
                $ex2 = $this->databaseConnection->query($query);

                while ($r = mysqli_fetch_assoc($ex2)) {
                    $rows[] = $r;
                }
            }
        }

        return $rows;
    }



    // function getAllTrainsExtended()
    // {
    //     $query = "SELECT
    //     route_status,
    //     route_id AS id,
    //     DATE_FORMAT(route_date,'%a %d/%m/%Y') AS route_date,
    //     train_number,
    //     train_lines.line_name AS line,
    //     operators.operator_code AS operator,
    //     (
    //         SELECT
    //             stations.station_name
    //         FROM
    //             route_entries
    //             INNER JOIN stations ON route_entries.entry_station = stations.station_ref
    //         WHERE
    //             route_id = id
    //         ORDER BY
    //             entry_number ASC
    //         LIMIT
    //             1
    //     ) AS origin_station,
    //             (
    //                 SELECT
    //                     DATE_FORMAT(COALESCE(planned_arrival,entry_arrival_time), '%H:%i')
    //                 FROM
    //                     route_entries
    //                 WHERE
    //                     route_id = id
    //                 ORDER BY
    //                     entry_number ASC
    //                 LIMIT
    //                     1
    //             ) AS origin_planned_arrival,
    //             (
    //                 SELECT
    //                     DATE_FORMAT(COALESCE(actual_arrival,expected_arrival), '%H:%i')
    //                 FROM
    //                     route_entries
    //                 WHERE
    //                     route_id = id
    //                 ORDER BY
    //                     entry_number ASC
    //                 LIMIT
    //                     1
    //             ) AS origin_arrival,
    //     (
    //         SELECT
    //         DATE_FORMAT(COALESCE(actual_departure, expected_departure, planned_departure, entry_departure_time), '%H:%i')
    //         FROM
    //             route_entries
    //         WHERE
    //             route_id = id
    //         ORDER BY
    //             entry_number ASC
    //         LIMIT
    //             1
    //     ) AS origin_departure,
    //     (
    //         SELECT
    //             stations.station_name
    //         FROM
    //             route_entries
    //             INNER JOIN stations ON route_entries.entry_station = stations.station_ref
    //         WHERE
    //             route_id = id
    //         ORDER BY
    //             entry_number DESC
    //         LIMIT
    //             1
    //     ) AS destination_station,
    //     (
    //         SELECT
    //             DATE_FORMAT(COALESCE(actual_arrival, expected_arrival, planned_arrival, entry_arrival_time), '%H:%i')
    //         FROM
    //             route_entries
    //         WHERE
    //             route_id = id
    //         ORDER BY
    //             entry_number DESC
    //         LIMIT
    //             1
    //     ) AS destination_arrival,
    //     (
    //         SELECT
    //         DATE_FORMAT(COALESCE(actual_departure, expected_departure, planned_departure, entry_departure_time), '%H:%i')
    //         FROM
    //             route_entries
    //         WHERE
    //             route_id = id
    //         ORDER BY
    //             entry_number DESC
    //         LIMIT
    //             1
    //     ) AS destination_departure 
    //     FROM routes
    //     INNER JOIN operators ON routes.route_operator = operators.operator_id
    //     INNER JOIN train_lines ON routes.route_line = train_lines.line_id ORDER BY train_number;";
    //     $ex = $this->databaseConnection->query($query);
    //     $rows = array();
    //     while ($r = mysqli_fetch_assoc($ex)) {
    //         $rows[] = $r;
    //     }
    //     return json_encode($rows);
    // }



    // function getTrainsOnDate($page, $date)
    // {

    //     $date = Date('Y-m-d', strtotime($date));

    //     if ($page == "") {
    //         $page = 0;
    //     }
    //     $pageStart = $page * 1000;
    //     $pageEnd = ($page * 1000) + 999;
    //     $preQuery = "SELECT 
    //         train_number AS 'num',
    //         IFNULL((SELECT 
    //                 route_id
    //             FROM
    //                 routes
    //             WHERE
    //                 train_number = num
    //                     AND route_date <= '$date'
    //             ORDER BY route_date DESC
    //             LIMIT 1),
    //         (SELECT 
    //                 route_id
    //             FROM
    //                 routes
    //             WHERE
    //                 train_number = num
    //                     AND route_date >= '$date'
    //             ORDER BY route_date ASC
    //             LIMIT 1)) AS 'route_id'
    //     FROM
    //         train_numbers WHERE train_number >= $pageStart AND train_number <= $pageEnd";

    //     // $preQuery = "SELECT 
    //     // train_number AS 'num',
    //     // (SELECT 
    //     //         route_id
    //     //     FROM
    //     //         routes
    //     //     WHERE
    //     //         train_number = num
    //     //             AND route_date >= '$date'
    //     //     ORDER BY route_date DESC
    //     //     LIMIT 1) AS route_id 

    //     // FROM
    //     //     train_numbers WHERE train_number >= $pageStart AND train_number <= $pageEnd";

    //     $rows = array();
    //     $ex = $this->databaseConnection->query($preQuery);
    //     $route_ids = array();
    //     while ($r = mysqli_fetch_assoc($ex)) {
    //         $thisRow = $r;
    //         $route_ids[] = $thisRow["num"] . " " . $thisRow["route_id"];

    //         $route_id = $thisRow["route_id"];

    //         if ($route_id != null) {
    //             $query = "SELECT
    //             route_status,
    //             route_id AS id,
    //             DATE_FORMAT(route_date,'%a %d/%m/%Y') AS route_date,
    //             DATE_FORMAT(route_date,'%Y-%m-%d') AS sort_date,
    //             train_number,
    //             train_lines.line_name AS line,
    //             operators.operator_code AS operator,
    //             (
    //                 SELECT
    //                     stations.station_name
    //                 FROM
    //                     route_entries
    //                     INNER JOIN stations ON route_entries.entry_station = stations.station_ref
    //                 WHERE
    //                     route_id = id
    //                 ORDER BY
    //                     entry_number ASC
    //                 LIMIT
    //                     1
    //             ) AS origin_station,
    //             (
    //                 SELECT
    //                     DATE_FORMAT(COALESCE(planned_arrival,entry_arrival_time), '%H:%i')
    //                 FROM
    //                     route_entries
    //                 WHERE
    //                     route_id = id
    //                 ORDER BY
    //                     entry_number ASC
    //                 LIMIT
    //                     1
    //             ) AS origin_planned_arrival,
    //             (
    //                 SELECT
    //                     DATE_FORMAT(COALESCE(actual_arrival,expected_arrival), '%H:%i')
    //                 FROM
    //                     route_entries
    //                 WHERE
    //                     route_id = id
    //                 ORDER BY
    //                     entry_number ASC
    //                 LIMIT
    //                     1
    //             ) AS origin_arrival,
    //             (
    //                 SELECT
    //                 DATE_FORMAT(COALESCE(actual_departure, expected_departure, planned_departure, entry_departure_time), '%H:%i')
    //                 FROM
    //                     route_entries
    //                 WHERE
    //                     route_id = id
    //                 ORDER BY
    //                     entry_number ASC
    //                 LIMIT
    //                     1
    //             ) AS origin_departure,
    //             (
    //                 SELECT
    //                     stations.station_name
    //                 FROM
    //                     route_entries
    //                     INNER JOIN stations ON route_entries.entry_station = stations.station_ref
    //                 WHERE
    //                     route_id = id
    //                 ORDER BY
    //                     entry_number DESC
    //                 LIMIT
    //                     1
    //             ) AS destination_station,
    //             (
    //                 SELECT
    //                     DATE_FORMAT(COALESCE(actual_arrival, expected_arrival, planned_arrival, entry_arrival_time), '%H:%i')
    //                 FROM
    //                     route_entries
    //                 WHERE
    //                     route_id = id
    //                 ORDER BY
    //                     entry_number DESC
    //                 LIMIT
    //                     1
    //             ) AS destination_arrival,
    //             (
    //                 SELECT
    //                 DATE_FORMAT(COALESCE(actual_departure, expected_departure, planned_departure, entry_departure_time), '%H:%i')
    //                 FROM
    //                     route_entries
    //                 WHERE
    //                     route_id = id
    //                 ORDER BY
    //                     entry_number DESC
    //                 LIMIT
    //                     1
    //             ) AS destination_departure 
    //             FROM routes
    //             INNER JOIN operators ON routes.route_operator = operators.operator_id
    //             INNER JOIN train_lines ON routes.route_line = train_lines.line_id WHERE route_id = $route_id AND sort_date = '$date';";
    //             echo $query;
    //             $ex2 = $this->databaseConnection->query($query);

    //             while ($r = mysqli_fetch_assoc($ex2)) {
    //                 $rows[] = $r;
    //             }
    //         }
    //     }

    //     return $rows;
    // }


}
