<?php
class getLineData
{
    private $databaseConnection;

    function __construct($conn)
    {
        $this->databaseConnection = $conn;
    }
    function getLines()
    {
        $query = "SELECT * FROM trainLines ORDER BY lineName";
        return $this->databaseConnection->query($query);
    }
    function getLineDropdown($lineID)
    {
        $databaseConnection = $this->databaseConnection;
        $query = "SELECT * FROM trainLines ORDER BY lineName";

        $dropDownResults = $databaseConnection->query($query);
        $dropDown = "<select name='lineID' required><option></option>";

        while ($dropDownRow = mysqli_fetch_array($dropDownResults)) {
            $dropDown .= "<option value='{$dropDownRow["lineID"]}'";
            if ($dropDownRow['lineID'] == $lineID) {
                $dropDown .= "selected='selected'";
            }
            $dropDown .= ">{$dropDownRow["lineName"]} </option>";
        }
        $dropDown .= "</select>";
        return $dropDown;
    }
    function getLineID($lineName)
    {
        $databaseConnection = $this->databaseConnection;
        $query = "SELECT * FROM trainLines WHERE lineName = '$lineName'";
        $result = $databaseConnection->query($query);
        $row = mysqli_fetch_all($result);
        return $row[0][0];
    }
    function getLineName($lineID)
    {
        $databaseConnection = $this->databaseConnection;
        $query = "SELECT lineName FROM trainLines WHERE lineID = '$lineID'";
        $result = $databaseConnection->query($query);
        $row = mysqli_fetch_all($result);
        return $row[0][0];
    }
}
