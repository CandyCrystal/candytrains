<?php
$pageRequiresTrainManager = true;
include "../config/connect.php";
include "../config/session.php";


$action = $_REQUEST['action'];
$stationRef = $_REQUEST['stationRef'];
$lineID = $_REQUEST['lineID'];
$entryID = $_REQUEST['ID'];
$returnUrl = $_REQUEST['returnUrl'];

$query = new stationLineQuery($databaseConnection);
switch ($action) {
    case ("insert"):
        $query->insertStationLine($returnUrl, $stationRef, $lineID);
        break;
    case ("delete"):
        if ($entryID != null) {
            $query->deleteStationLine($returnUrl, $entryID);
        }
        break;
}

class stationLineQuery
{
    private $databaseConnection;

    function __construct($conn)
    {
        $this->databaseConnection = $conn;
    }
    function insertStationLine($returnUrl, $stationRef, $lineID)
    {
        $databaseConnection = $this->databaseConnection;
        ob_start();
        $stationRef = mysqli_real_escape_string($databaseConnection, $stationRef);
        $lineID = mysqli_real_escape_string($databaseConnection, $lineID);
        $sql = "INSERT INTO lines_on_station (station_ref,line_id) VALUES ('$stationRef','$lineID')";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $returnUrl);
        } else {
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
        ob_end_flush();
    }
    function deleteStationLine($returnUrl, $entryID)
    {
        $databaseConnection = $this->databaseConnection;
        ob_start();

        $entryID = mysqli_real_escape_string($databaseConnection, $entryID);
        $sql = "DELETE FROM lines_on_station WHERE line_station_id = '$entryID'";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $returnUrl);
        } else {
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
        ob_end_flush();
    }
}
