<?php
$pageRequiresLogin = true;
include "../config/session.php";
include "../config/connectNew.php";
// include "../config/candyDirectory.php";
// if (isset($_SESSION['login_user'])) {
//     $currentUserName = $_SESSION['login_user'];
//     $currentUserQuery = "SELECT * FROM users WHERE userName = '$currentUserName'";
//     $currentUserResult = $candyDirectoryConnection->query($currentUserQuery);
//     $currentUser = mysqli_fetch_array($currentUserResult);
//     $userCanManage = $currentUser["userCanManageCandyTrains"];
// };

$action = $_REQUEST['action'];
$returnUrl = $_REQUEST['returnUrl'];
$platformID = $_REQUEST['platformID'];
$platformNumber = $_REQUEST['platformNumber'];
$platformLength = $_REQUEST['platformLength'];
$platformHasSectors = $_REQUEST['platformHasSectors'];
if ($platformHasSectors != "on") {
    $platformHasSectors = 0;
} else {
    $platformHasSectors = 1;
}
$stationID = $_REQUEST["stationID"];
$query = new platformQuery($databaseConnection, $returnUrl);
switch ($action) {
    case ("insert"):
        $query->insertPlatform($platformNumber, $platformLength, $stationID, $platformHasSectors);
        break;
    case ("update"):
        if ($platformID != null) {
            $query->updatePlatform($platformID, $platformNumber);
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
    function insertPlatform($platformNumber, $platformLength, $stationID, $platformHasSectors)
    {
        $databaseConnection = $this->databaseConnection;

        $platformNumber = mysqli_real_escape_string($databaseConnection, $platformNumber);
        $stationID = mysqli_real_escape_string($databaseConnection, $stationID);
        $platformHasSectors = mysqli_real_escape_string($databaseConnection, $platformHasSectors);
        $sql = "INSERT INTO platforms (platformNumber,platformLength,platformStationID,platformHasSectors) VALUES ('$platformNumber','$platformLength','$stationID','$platformHasSectors')";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $this->returnUrl);
        } else {
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
    }
    function updatePlatform($platformID, $platformNumber)
    {
        $databaseConnection = $this->databaseConnection;
        $platformID = mysqli_real_escape_string($databaseConnection, $platformID);
        $platformNumber = mysqli_real_escape_string($databaseConnection, $platformNumber);
        $sql = "UPDATE platforms SET platformNumber='$platformNumber', WHERE platformID = $platformID";
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
        $sql = "DELETE FROM platforms WHERE platformID = $platformID";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $this->returnUrl);
        } else {
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
    }
}
