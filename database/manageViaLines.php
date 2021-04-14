<?php
$pageRequiresLogin = true;
include "../config/session.php";
include "../config/candyDirectory.php";
include "../config/connectNew.php";
if (isset($_SESSION['login_user'])) {
    $currentUserName = $_SESSION['login_user'];
    $currentUserQuery = "SELECT * FROM users WHERE userName = '$currentUserName'";
    $currentUserResult = $candyDirectoryConnection->query($currentUserQuery);
    $currentUser = mysqli_fetch_array($currentUserResult);
    $userCanManage = $currentUser["userCanManageCandyTrains"];
};

$action = $_REQUEST['action'];
$viaLineID = $_REQUEST['viaLineID'];
$viaLineViaID = $_REQUEST['viaLineViaID'];
$viaLineLineID = $_REQUEST['lineID'];
$viaLineDestinationStationID = $_REQUEST['stationID'];
$returnUrl = $_REQUEST['returnUrl'];
$query = new viaLineQuery($databaseConnection, $returnUrl);
switch ($action) {
    case ("insert"):
        $query->insertViaLine($viaLineViaID, $viaLineLineID, $viaLineDestinationStationID);
        break;
    case ("update"):
        if ($viaLineID != null) {
            $query->updateViaLine($viaLineID, $viaLineLineID, $viaLineDestinationStationID);
        } else {
            echo $viaLineID;
        }
        break;
    case ("delete"):
        if ($viaLineID != null) {
            $query->deleteViaLine($viaLineID);
        }
        break;
}

class viaLineQuery
{
    private $databaseConnection;
    private $returnUrl;

    function __construct($conn, $returnUrl)
    {
        $this->databaseConnection = $conn;
        $this->returnUrl = $returnUrl;
    }
    function insertViaLine($viaLineViaID, $viaLineLineID, $viaLineDestinationStationID)
    {
        $databaseConnection = $this->databaseConnection;
        ob_start();
        $viaLineViaID = mysqli_real_escape_string($databaseConnection, $viaLineViaID);
        $viaLineLineID = mysqli_real_escape_string($databaseConnection, $viaLineLineID);
        $viaLineDestinationStationID = mysqli_real_escape_string($databaseConnection, $viaLineDestinationStationID);
        $sql = "INSERT INTO viaLines (viaLineViaID,viaLineLineID,viaLineDestinationStationID) VALUES ('$viaLineViaID','$viaLineLineID','$viaLineDestinationStationID')";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $this->returnUrl);
        } else {
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
        ob_end_flush();
    }
    function updateViaLine($viaLineID, $viaLineLineID, $viaLineDestinationStationID)
    {
        $databaseConnection = $this->databaseConnection;
        ob_start();

        $viaLineID = mysqli_real_escape_string($databaseConnection, $viaLineID);
        $viaLineLineID = mysqli_real_escape_string($databaseConnection, $viaLineLineID);
        $viaLineDestinationStationID = mysqli_real_escape_string($databaseConnection, $viaLineDestinationStationID);

        $sql = "UPDATE viaLines SET viaLineLineID='$viaLineLineID',viaLineDestinationStationID='$viaLineDestinationStationID' WHERE viaLineID = $viaLineID";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $this->returnUrl);
        } else {
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
        ob_end_flush();
    }
    function deleteViaLine($viaLineID)
    {
        $databaseConnection = $this->databaseConnection;
        ob_start();

        $viaLineID = mysqli_real_escape_string($databaseConnection, $viaLineID);
        $sql = "DELETE FROM viaLines WHERE viaLineID = $viaLineID";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $this->returnUrl);
        } else {
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
        ob_end_flush();
    }
}
