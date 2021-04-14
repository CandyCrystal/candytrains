<?php
$pageRequiresAdmin = true;

$action = $_REQUEST['action'];
$stationID = $_REQUEST['stationID'];
$stationRef = $_REQUEST['stationRef'];
$returnUrl = $_REQUEST['returnUrl'];
$stationName = $_REQUEST['stationName'];
$stationLat = $_REQUEST['stationLat'];
$stationLong = $_REQUEST['stationLong'];
$stationOpenDate = $_REQUEST['stationOpenDate'];
$stationCloseDate = $_REQUEST['stationCloseDate'];
$stationIsClosed = $_REQUEST['stationIsClosed'];
if ($stationIsClosed == "on") {
    $stationIsClosed = 1;
} else {
    $stationIsClosed = 0;
}

$query = new stationQuery($databaseConnection);
switch ($action) {
    case ("insert"):
        $query->insertStation($returnUrl, $stationRef, $stationName, $stationLat, $stationLong, $stationOpenDate, $stationCloseDate, $stationIsClosed);
        break;
    case ("update"):
        if ($stationID != null) {
            $query->updateStation($returnUrl, $stationID, $stationRef, $stationName, $stationLat, $stationLong, $stationOpenDate, $stationCloseDate, $stationIsClosed);
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
    function insertStation($returnUrl, $stationRef, $stationName, $stationLat, $stationLong, $stationOpenDate, $stationCloseDate, $stationIsClosed)
    {
        $databaseConnection = $this->databaseConnection;
        ob_start();
        $stationRef = mysqli_real_escape_string($databaseConnection, $stationRef);
        $stationName = mysqli_real_escape_string($databaseConnection, $stationName);
        $stationLat = mysqli_real_escape_string($databaseConnection, $stationLat);
        $stationLong = mysqli_real_escape_string($databaseConnection, $stationLong);
        $stationOpenDate = mysqli_real_escape_string($databaseConnection, $stationOpenDate);
        $stationCloseDate = mysqli_real_escape_string($databaseConnection, $stationCloseDate);
        $stationIsClosed = mysqli_real_escape_string($databaseConnection, $stationIsClosed);
        $sql = "INSERT INTO stations (stationRef,stationName,stationLat,stationLong, stationOpenDate,stationCloseDate,stationIsClosed) VALUES ('$stationRef','$stationName','$stationLat','$stationLong','$stationOpenDate','$stationCloseDate','$stationIsClosed')";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $returnUrl);
        } else {
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
        ob_end_flush();
    }
    function updateStation($returnUrl, $stationID, $stationRef, $stationName, $stationLat, $stationLong, $stationOpenDate, $stationCloseDate, $stationIsClosed)
    {
        $databaseConnection = $this->databaseConnection;
        ob_start();

        $stationID = mysqli_real_escape_string($databaseConnection, $stationID);
        $stationRef = mysqli_real_escape_string($databaseConnection, $stationRef);
        $stationName = mysqli_real_escape_string($databaseConnection, $stationName);
        $stationLat = mysqli_real_escape_string($databaseConnection, $stationLat);
        $stationLong = mysqli_real_escape_string($databaseConnection, $stationLong);
        $stationOpenDate = mysqli_real_escape_string($databaseConnection, $stationOpenDate);
        $stationCloseDate = mysqli_real_escape_string($databaseConnection, $stationCloseDate);
        $stationIsClosed = mysqli_real_escape_string($databaseConnection, $stationIsClosed);
        $sql = "UPDATE stations SET stationName='$stationName',stationLat='$stationLat',stationLong='$stationLong', stationOpenDate='$stationOpenDate',stationCloseDate='$stationCloseDate',stationIsClosed='$stationIsClosed' WHERE stationID='$stationID'";
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
