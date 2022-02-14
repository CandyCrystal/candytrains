<?php

include "../config/connect.php";
$trainNumber = $_GET["train"];
$e = new getTrainData($databaseConnection);
if ($_GET["type"] == "map") {
    echo $e->getMapTrainRoute($trainNumber);
} else {
    echo $e->getTrainRoute($trainNumber);
}
class getTrainData
{
    private $databaseConnection;

    function __construct($conn)
    {
        $this->databaseConnection = $conn;
    }
    function getTrainRoute($train_id)
    {
        $query = "SELECT 
        entry_number,
        cancellation,
        entry_station AS station_ref,
        stations.station_name AS station_name,
        (SELECT DATE_FORMAT(route_date,'%a %d/%m/%Y') FROM routes WHERE entry_route = '$train_id' LIMIT 1) AS dt,
        DATE_FORMAT(planned_arrival, '%H:%i') AS planned_arrival,
        DATE_FORMAT(COALESCE(actual_arrival, expected_arrival), '%H:%i') AS arrival,
        DATE_FORMAT(planned_departure, '%H:%i') AS planned_departure,
        DATE_FORMAT(COALESCE(actual_departure, expected_departure), '%H:%i') AS departure,
        COALESCE(actual_departure, expected_departure, planned_departure) AS dep,
        COALESCE(actual_arrival, expected_arrival, planned_arrival) AS arr
    FROM
        route_entries
            INNER JOIN
        stations ON route_entries.entry_station = stations.station_ref
    WHERE
        entry_route = '$train_id'
    ORDER BY entry_number";
        $result = $this->databaseConnection->query($query);
        $rows = array();
        while ($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        }
        return json_encode($rows);
    }
    function getMapTrainRoute($train_number)
    {
        $train_num = mysqli_real_escape_string($this->databaseConnection, $train_number);
        $train_num_query = "SELECT 
            train_lines.line_name AS 'line', entry_route, operators.operator_name AS 'operator'
            FROM
            routes
            INNER JOIN
            operators ON routes.route_operator = operators.operator_id
            INNER JOIN
            train_lines ON routes.route_line = train_lines.line_id
            WHERE train_number = '$train_num' LIMIT 1";
        $train_num_result = $this->databaseConnection->query($train_num_query);
        $firstrow = mysqli_fetch_row($train_num_result);
        $route_id = $firstrow[1];
        if ($route_id == null) {
            return "this failed";
        }
        return $this->getTrainRoute($route_id);
    }
}
