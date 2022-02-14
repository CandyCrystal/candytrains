<?php
$pageRequiresTrainManager = true;
include "../config/connect.php";
include "../config/session.php";

$action = $_REQUEST['action'];
$page = $_REQUEST['page'];
$id = $_REQUEST['id'];
$train = $_REQUEST['train_number'];
$number = mysqli_real_escape_string($databaseConnection, $_REQUEST['entry_number']);
$arrival = "'" . mysqli_real_escape_string($databaseConnection, $_REQUEST['arrival_time']) . "'";
$station = mysqli_real_escape_string($databaseConnection, $_REQUEST['stationRef']);
$departure = "'" . mysqli_real_escape_string($databaseConnection, $_REQUEST['departure_time']) . "'";
if ($arrival == "''") {
    $arrival = "NULL";
}
if ($departure == "''") {
    $departure = "NULL";
}

$query = new trainQuery($databaseConnection);
switch ($action) {
    case ("insert_entry"):
        return $query->insertEntry($id, $page, $number, $arrival, $station, $departure,$train);
        break;
}

class trainQuery
{
    private $databaseConnection;

    function __construct($conn)
    {
        $this->databaseConnection = $conn;
    }
    function insertEntry($train_number, $page, $number, $arrival, $station, $departure,$train)
    {

        $sql = "INSERT INTO route_entries (route_id,entry_number,entry_arrival_time,entry_station,entry_departure_time) VALUES ('$train_number',$number,$arrival,'$station', $departure)";
        // echo $sql;
        if ($this->databaseConnection->query($sql) === TRUE) {
            $sql = "UPDATE routes SET status=0 WHERE train_number = $train_number";
            header('Location: https://trains.candycryst.com/trainList.php?page=' . $page . '#tn_' . $train);
        } else {
            echo "Error: " . $sql . "<br>" . $this->databaseConnection->error;
        }
    }
}
