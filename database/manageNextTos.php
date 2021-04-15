<?php
$pageRequiresAdmin = true;
include "../config/session.php";
include "../config/candyDirectory.php";
include "../config/connect.php";

$action = $_REQUEST['action'];
$returnUrl = $_REQUEST['returnUrl'];
$query = new nextToQuery($databaseConnection);
switch ($action) {
    case ("insert"):
        $nextToStation = $_REQUEST['nextToStation'];
        $nextToTitle = $_REQUEST['nextToTitle'];
        $nextToContent = $_REQUEST['nextToContent'];
        $query->insertVia($returnUrl, $entryDestination, $nextToTitle, $nextToContent);
        break;
    case ("update"):
        $nextToID = $_REQUEST['nextToID'];
        $nextToTitle = $_REQUEST['nextToTitle'];
        $nextToContent = $_REQUEST['nextToContent'];
        if ($nextToID != null) {
            $query->updateVia($returnUrl, $nextToID, $nextToTitle, $nextToContent);
        } else {
            echo $nextToID;
        }
        break;
    case ("delete"):
        $nextToID = $_REQUEST['nextToID'];
        if ($nextToID != null) {
            $query->deleteVia($returnUrl, $nextToID);
        }
        break;
}

class nextToQuery
{
    private $databaseConnection;

    function __construct($conn)
    {
        $this->databaseConnection = $conn;
    }
    function insertVia($returnUrl, $entryDestination, $nextToTitle, $nextToContent)
    {
        $databaseConnection = $this->databaseConnection;
        ob_start();
        $nextToStation = mysqli_real_escape_string($databaseConnection, $entryDestination);
        $nextToTitle = mysqli_real_escape_string($databaseConnection, $nextToTitle);
        $nextToContent = mysqli_real_escape_string($databaseConnection, $nextToContent);
        $sql = "INSERT INTO next_to (next_to_station,next_to_title,next_to_content) VALUES ('$entryDestination','$nextToTitle','$nextToContent')";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $returnUrl);
        } else {
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
        ob_end_flush();
    }
    function updateVia($returnUrl, $nextToID, $nextToTitle, $nextToContent)
    {
        $databaseConnection = $this->databaseConnection;
        ob_start();

        $nextToID = mysqli_real_escape_string($databaseConnection, $nextToID);
        $nextToTitle = mysqli_real_escape_string($databaseConnection, $nextToTitle);
        $nextToContent = mysqli_real_escape_string($databaseConnection, $nextToContent);

        $sql = "UPDATE next_to SET next_to_title='$nextToTitle',next_to_content='$nextToContent' WHERE next_to_id = $nextToID";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $returnUrl);
        } else {
            echo "hi";
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
        ob_end_flush();
    }
    function deleteVia($returnUrl, $nextToID)
    {
        $databaseConnection = $this->databaseConnection;
        ob_start();

        $nextToID = mysqli_real_escape_string($databaseConnection, $nextToID);

        $sql = "DELETE FROM next_to WHERE next_to_id = $nextToID";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $returnUrl);
        } else {
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
        ob_end_flush();
    }
    function getVias($stationRef)
    {
        $databaseConnection = $this->databaseConnection;
        $query = "SELECT * FROM next_to WHERE next_to_station = '$stationRef' ORDER BY next_to_title";
        return $databaseConnection->query($query);
    }
    function getViasNextTo($stationRef)
    {
        $databaseConnection = $this->databaseConnection;
        $query = "SELECT * FROM next_to WHERE next_to_station = '$stationRef' ORDER BY next_to_title";
        $result = $databaseConnection->query($query);
        return mysqli_fetch_all($result);
    }
}
