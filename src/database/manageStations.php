<?php
$pageRequiresTrainManager = true;
include "../config/connect.php";
include "../config/session.php";

$action = $_REQUEST['action'];
$stationRef = $_REQUEST['stationRef'];
$station_type = $_REQUEST['station_type'];
$returnUrl = $_REQUEST['returnUrl'];
$stationName = $_REQUEST['stationName'];
$stationBus = $_REQUEST['stationBus'];
$stationTaxi = $_REQUEST['stationTaxi'];
$stationMetro = $_REQUEST['stationMetro'];
$stationTram = $_REQUEST['stationTram'];
$stationFerry = $_REQUEST['stationFerry'];

$query = new stationQuery($databaseConnection);
switch ($action) {
    case ("insert"):
        $query->insertStation($returnUrl, $stationRef, $stationName, $station_type);
        break;
    case ("update"):
        if ($stationRef != null) {
            $query->updateStation($returnUrl, $stationRef, $stationName, $station_type, $stationBus, $stationTaxi, $stationMetro, $stationTram, $stationFerry);
        }
        break;
    case ("delete"):
        if ($stationRef != null) {
            $query->deleteStation($returnUrl, $stationID);
        }
        break;
}

class stationQuery
{
    private $databaseConnection;

    function __construct($conn)
    {
        $this->databaseConnection = $conn;
    }
    function getBool($i)
    {
        if ($i == "on") {
            return 1;
        } else {
            return 0;
        }
    }
    function insertStation($returnUrl, $stationRef, $stationName, $stationType)
    {
        $databaseConnection = $this->databaseConnection;
        ob_start();
        $stationRef = mysqli_real_escape_string($databaseConnection, $stationRef);
        $stationName = mysqli_real_escape_string($databaseConnection, $stationName);
        $sql = "INSERT INTO stations (stationRef,stationName) VALUES ('$stationRef','$stationName')";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $returnUrl);
        } else {
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
        ob_end_flush();
    }
    function updateStation($returnUrl, $stationRef, $stationName, $stationType, $stationBus, $stationTaxi, $stationMetro, $stationTram, $stationFerry)
    {
        $databaseConnection = $this->databaseConnection;
        ob_start();

        $stationType = mysqli_real_escape_string($databaseConnection, $stationType);
        $stationRef = mysqli_real_escape_string($databaseConnection, $stationRef);
        $stationName = mysqli_real_escape_string($databaseConnection, $stationName);
        $stationBus = mysqli_real_escape_string($databaseConnection, $this->getBool($stationBus));
        $stationTaxi = mysqli_real_escape_string($databaseConnection, $this->getBool($stationTaxi));
        $stationMetro = mysqli_real_escape_string($databaseConnection, $this->getBool($stationMetro));
        $stationTram = mysqli_real_escape_string($databaseConnection, $this->getBool($stationTram));
        $stationFerry = mysqli_real_escape_string($databaseConnection, $this->getBool($stationFerry));
        $sql = "UPDATE stations SET station_name='$stationName',station_type='$stationType',station_has_bus='$stationBus',station_has_taxi='$stationTaxi',station_has_metro='$stationMetro',station_has_tram='$stationTram',station_has_ferry='$stationFerry' WHERE station_ref='$stationRef'";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $returnUrl);
        } else {
            echo "hi";
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
        ob_end_flush();
    }
    function deleteStation($returnUrl, $stationID)
    {
        $databaseConnection = $this->databaseConnection;
        ob_start();

        $stationID = mysqli_real_escape_string($databaseConnection, $stationID);
        $sql = "DELETE FROM stations WHERE stationID = '$stationID'";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $returnUrl);
        } else {
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
        ob_end_flush();
    }
}
