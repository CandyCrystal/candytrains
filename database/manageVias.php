<?php
$pageRequiresAdmin = true;
include "../config/session.php";
include "../config/candyDirectory.php";
include "../config/connectNew.php";

$action = $_REQUEST['action'];
$returnUrl = $_REQUEST['returnUrl'];
$query = new viaQuery($databaseConnection);
switch ($action) {
    case ("insert"):
        $viaStationID = $_REQUEST['stationID'];
        $viaDestinationText = $_REQUEST['viaDestinationText'];
        $viaNoteText = $_REQUEST['viaNoteText'];
        $query->insertVia($returnUrl, $viaStationID, $viaDestinationText, $viaNoteText);
        break;
    case ("update"):
        $viaID = $_REQUEST['viaID'];
        $viaDestinationText = $_REQUEST['viaDestinationText'];
        $viaNoteText = $_REQUEST['viaNoteText'];
        if ($viaID != null) {
            $query->updateVia($returnUrl, $viaID, $viaDestinationText, $viaNoteText);
        } else {
            echo $viaID;
        }
        break;
    case ("delete"):
        $viaID = $_REQUEST['viaID'];
        if ($viaID != null) {
            $query->deleteVia($returnUrl, $viaID);
        }
        break;
}

class viaQuery
{
    private $databaseConnection;

    function __construct($conn)
    {
        $this->databaseConnection = $conn;
    }
    function insertVia($returnUrl, $viaStationID, $viaDestinationText, $viaNoteText)
    {
        $databaseConnection = $this->databaseConnection;
        ob_start();
        $viaStationID = mysqli_real_escape_string($databaseConnection, $viaStationID);
        $viaDestinationText = mysqli_real_escape_string($databaseConnection, $viaDestinationText);
        $viaNoteText = mysqli_real_escape_string($databaseConnection, $viaNoteText);
        $sql = "INSERT INTO vias (viaStationID,viaDestinationText,viaNoteText) VALUES ('$viaStationID','$viaDestinationText','$viaNoteText')";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $returnUrl);
        } else {
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
        ob_end_flush();
    }
    function updateVia($returnUrl, $viaID, $viaDestinationText, $viaNoteText)
    {
        $databaseConnection = $this->databaseConnection;
        ob_start();

        $viaID = mysqli_real_escape_string($databaseConnection, $viaID);
        $viaDestinationText = mysqli_real_escape_string($databaseConnection, $viaDestinationText);
        $viaNoteText = mysqli_real_escape_string($databaseConnection, $viaNoteText);

        $sql = "UPDATE vias SET viaDestinationText='$viaDestinationText',viaNoteText='$viaNoteText' WHERE viaID = $viaID";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $returnUrl);
        } else {
            echo "hi";
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
        ob_end_flush();
    }
    function deleteVia($returnUrl, $viaID)
    {
        $databaseConnection = $this->databaseConnection;
        ob_start();

        $viaID = mysqli_real_escape_string($databaseConnection, $viaID);
        $sql = "DELETE FROM vias WHERE viaID = $viaID";
        if ($databaseConnection->query($sql) === TRUE) {
            header('Location: ' . $returnUrl);
        } else {
            echo "Error: " . $sql . "<br>" . $databaseConnection->error;
        }
        ob_end_flush();
    }
    function getVias($stationID)
    {
        $databaseConnection = $this->databaseConnection;
        $query = "SELECT * FROM vias WHERE viaStationID = $stationID ORDER BY viaDestinationText";
        return $databaseConnection->query($query);
    }
    function getViasNextTo($stationID)
    {
        $databaseConnection = $this->databaseConnection;
        $query = "SELECT * FROM vias WHERE viaStationID = $stationID ORDER BY viaDestinationText";
        $result = $databaseConnection->query($query);
        return mysqli_fetch_all($result);
    }

    // function getLineDropdown($viaID)
    // {
    //     $databaseConnection = $this->databaseConnection;
    //     $query = "SELECT * FROM vias ORDER BY lineName";

    //     $dropDownResults = $databaseConnection->query($query);
    //     $dropDown = "<select name='lineID' required><option></option>";

    //     while ($dropDownRow = mysqli_fetch_array($dropDownResults)) {
    //         $dropDown .= "<option value='{$dropDownRow["lineID"]}'";
    //         if ($dropDownRow['lineID'] == $viaID) {
    //             $dropDown .= "selected='selected'";
    //         }
    //         $dropDown .= ">{$dropDownRow["lineName"]} </option>";
    //     }
    //     $dropDown .= "</select>";
    //     return $dropDown;
    // }
}
