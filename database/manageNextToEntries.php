<?php
$pageRequiresLogin = true;
include "../config/session.php";
include "../config/candyDirectory.php";
include "../config/connect.php";
if (isset($_SESSION['login_user'])) {
    $currentUserName = $_SESSION['login_user'];
    $currentUserQuery = "SELECT * FROM users WHERE userName = '$currentUserName'";
    $currentUserResult = $candyDirectoryConnection->query($currentUserQuery);
    $currentUser = mysqli_fetch_array($currentUserResult);
    $userCanManage = $currentUser["userCanManageCandyTrains"];
};

$action = $_REQUEST['action'];
$entryID = $_REQUEST['entryID'];
$entryParent = $_REQUEST['entryParent'];
$entryLine = $_REQUEST['lineID'];
$entryDestination = $_REQUEST['stationRef'];
$returnUrl = $_REQUEST['returnUrl'];
$query = new nextToEntryQuery($databaseConnection, $returnUrl);
switch ($action) {
    case ("insert"):
        $query->insertEntry($entryParent, $entryLine, $entryDestination);
        break;
    case ("update"):
        if ($entryID != null) {
            $query->updateEntry($entryID, $entryLine, $entryDestination);
        } else {
            echo $entryID;
        }
        break;
    case ("delete"):
        if ($entryID != null) {
            $query->deleteEntry($entryID);
        }
        break;
}

class nextToEntryQuery
{
    private $databaseConnection;
    private $returnUrl;

    function __construct($conn, $returnUrl)
    {
        $this->databaseConnection = $conn;
        $this->returnUrl = $returnUrl;
    }
    function insertEntry($entryParent, $entryLine, $entryDestination)
    {
        $databaseConnection = $this->databaseConnection;
        ob_start();
        $entryParent = mysqli_real_escape_string($databaseConnection, $entryParent);
        $entryLine = mysqli_real_escape_string($databaseConnection, $entryLine);
        $entryDestination = mysqli_real_escape_string($databaseConnection, $entryDestination);
        $sql = "INSERT INTO next_to_entries (entry_parent,entry_line,entry_destination) VALUES ('$entryParent','$entryLine','$entryDestination')";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $this->returnUrl);
        } else {
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
        ob_end_flush();
    }
    function updateEntry($entryID, $entryLine, $entryDestination)
    {
        $databaseConnection = $this->databaseConnection;
        ob_start();

        $entryID = mysqli_real_escape_string($databaseConnection, $entryID);
        $entryLine = mysqli_real_escape_string($databaseConnection, $entryLine);
        $entryDestination = mysqli_real_escape_string($databaseConnection, $entryDestination);

        $sql = "UPDATE next_to_entries SET entry_line='$entryLine',entry_destination='$entryDestination' WHERE entry_id = $entryID";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $this->returnUrl);
        } else {
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
        ob_end_flush();
    }
    function deleteEntry($entryID)
    {
        $databaseConnection = $this->databaseConnection;
        ob_start();

        $entryID = mysqli_real_escape_string($databaseConnection, $entryID);
        $sql = "DELETE FROM next_to_entries WHERE entry_id = $entryID";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $this->returnUrl);
        } else {
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
        ob_end_flush();
    }
}
