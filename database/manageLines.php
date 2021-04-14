<?php
$pageRequiresAdmin = true;

$action = $_GET['action'];
$lineID = $_REQUEST['lineID'];
$returnUrl = $_REQUEST['returnUrl'];
$lineName = $_REQUEST['lineName'];
$stationRef = $_GET["station"];
$query = new lineQuery($databaseConnection);
switch ($action) {
    case ("insert"):
        $query->insertLine($returnUrl, $lineName);
        break;
    case ("update"):
        if ($lineID != null) {
            $query->updateLine($returnUrl, $lineID, $lineName);
        } else {
            echo $lineID;
        }
        break;
    case ("delete"):
        if ($lineID != null) {
            $query->deleteLine($returnUrl, $lineID);
        }
        break;
}

class lineQuery
{
    private $databaseConnection;

    function __construct($conn)
    {
        $this->databaseConnection = $conn;
    }
    function insertLine($returnUrl, $lineName)
    {
        $databaseConnection = $this->databaseConnection;
        ob_start();

        // $lineID = mysqli_real_escape_string($databaseConnection, $lineID);
        $lineName = mysqli_real_escape_string($databaseConnection, $lineName);
        $sql = "INSERT INTO trainLines (lineName) VALUES ('$lineName')";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $returnUrl);
        } else {
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
        ob_end_flush();
    }
    function updateLine($returnUrl, $lineID, $lineName)
    {
        $databaseConnection = $this->databaseConnection;
        ob_start();

        $lineID = mysqli_real_escape_string($databaseConnection, $lineID);
        $lineName = mysqli_real_escape_string($databaseConnection, $lineName);
        $sql = "UPDATE trainLines SET lineName='$lineName' WHERE lineID = $lineID";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $returnUrl);
        } else {
            echo "hi";
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
        ob_end_flush();
    }
    function deleteLine($returnUrl, $lineID)
    {
        $databaseConnection = $this->databaseConnection;
        ob_start();

        $lineID = mysqli_real_escape_string($databaseConnection, $lineID);
        // $lineName = mysqli_real_escape_string($databaseConnection, $lineName);
        $sql = "DELETE FROM trainLines WHERE lineID = $lineID";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $returnUrl);
        } else {
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
        ob_end_flush();
    }
}
