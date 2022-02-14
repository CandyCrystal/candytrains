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
        $train_id = mysqli_real_escape_string($this->databaseConnection, $train_id);
        $query = "SELECT 
        entry_number,
        entry_station AS station_ref,
        stations.station_name AS station_name,
        DATE_FORMAT(entry_arrival_time, '%H:%i') AS arrival_time,
        DATE_FORMAT(entry_departure_time, '%H:%i') AS departure_time
    FROM
        route_entries
            INNER JOIN
        stations ON route_entries.entry_station = stations.station_ref
    WHERE
        route_id = '$train_id'
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
            train_lines.line_name AS 'line', route_id, operators.operator_name AS 'operator'
            FROM
            routes
            INNER JOIN
            operators ON routes.route_operator = operators.operator_id
            INNER JOIN
            train_lines ON routes.route_line = train_lines.line_id
            WHERE train_number = '$train_num'";
        $train_num_result = $this->databaseConnection->query($train_num_query);
        $firstrow = mysqli_fetch_row($train_num_result);
        $route_id = $firstrow[1];
        if ($route_id == null) {
            return "this failed";
        }
        $query = "SELECT 
        entry_number,
        entry_station AS station_ref,
        stations.station_name AS station_name,
        DATE_FORMAT(entry_arrival_time, '%H:%i') AS arrival_time,
        DATE_FORMAT(entry_departure_time, '%H:%i') AS departure_time
    FROM
        route_entries
            INNER JOIN
        stations ON route_entries.entry_station = stations.station_ref
    WHERE
        route_id = '$route_id'
    ORDER BY entry_number";
        $result = $this->databaseConnection->query($query);
        $rows = array();
        $rows["line"] =  $firstrow[0];
        $rows["operator"] =  $firstrow[2];
        while ($r = mysqli_fetch_assoc($result)) {
            $rows["stations"][] = $r;
        }
        return json_encode($rows);
    }
}
