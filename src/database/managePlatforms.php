<?php
$pageRequiresTrainManager = true;
include "../config/connect.php";
include "../config/session.php";


$action = $_REQUEST['action'];
$returnUrl = $_REQUEST['returnUrl'];
$platformID = $_REQUEST['platformID'];
$platformNumber = $_REQUEST['platformNumber'];
$stationID = $_REQUEST["stationRef"];
$platformSide = $_REQUEST["platformSide"];
$query = new platformQuery($databaseConnection, $returnUrl);
switch ($action) {
    case ("insert"):
        $query->insertPlatform($platformNumber, $stationID, $platformSide);
        break;
    case ("update"):
        if ($platformID != null) {
            $query->updatePlatform($platformID, $platformSide);
        } else {
            echo $platformID;
        }
        break;
    case ("delete"):
        if ($platformID != null) {
            $query->deletePlatform($platformID);
        }
        break;
}

class platformQuery
{
    private $databaseConnection;
    private $returnUrl;

    function __construct($conn, $returnUrl)
    {
        $this->databaseConnection = $conn;
        $this->returnUrl = $returnUrl;
    }
    function insertPlatform($platformNumber, $stationID, $platformSide)
    {
        $databaseConnection = $this->databaseConnection;

        $platformNumber = mysqli_real_escape_string($databaseConnection, $platformNumber);
        $stationID = mysqli_real_escape_string($databaseConnection, $stationID);
        $platformSide = mysqli_real_escape_string($databaseConnection, $platformSide);
        $sql = "INSERT INTO platforms (platform_number,platform_station) VALUES ('$platformNumber','$stationID')";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $this->returnUrl);
        } else {
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
    }
    function updatePlatform($platformID, $platformSide)
    {
        $databaseConnection = $this->databaseConnection;
        $platformID = mysqli_real_escape_string($databaseConnection, $platformID);
        $platformSide = mysqli_real_escape_string($databaseConnection, $platformSide);
        $sql = "UPDATE platforms SET platform_side='$platformSide' WHERE platform_id = $platformID";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $this->returnUrl);
        } else {
            echo "hi";
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
    }
    function deletePlatform($platformID)
    {
        $databaseConnection = $this->databaseConnection;
        $platformID = mysqli_real_escape_string($databaseConnection, $platformID);
        $sql = "DELETE FROM platforms WHERE platform_id = $platformID";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $this->returnUrl);
        } else {
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
    }
}
