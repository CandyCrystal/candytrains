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
        $query = "SELECT * FROM train_lines ORDER BY line_shorthand";
        return $this->databaseConnection->query($query);
    }
    function getLineDropdown($lineID)
    {
        $databaseConnection = $this->databaseConnection;
        $query = "SELECT * FROM train_lines ORDER BY line_name";

        $dropDownResults = $databaseConnection->query($query);
        $dropDown = "<select name='lineID' id='line_id' required><option></option>";

        while ($dropDownRow = mysqli_fetch_array($dropDownResults)) {
            $dropDown .= "<option value='{$dropDownRow["line_id"]}'";
            if ($dropDownRow['line_id'] == $lineID) {
                $dropDown .= "selected='selected'";
            }
            $dropDown .= ">{$dropDownRow["line_name"]} </option>";
        }
        $dropDown .= "</select>";
        return $dropDown;
    }
    function getLineID($lineName)
    {
        $databaseConnection = $this->databaseConnection;
        $lineName = mysqli_real_escape_string($this->databaseConnection, $lineName);
        $query = "SELECT line_id FROM train_lines WHERE line_name = '$lineName'";
        $result = $databaseConnection->query($query);
        $row = mysqli_fetch_all($result);
        return $row[0][0];
    }
    function getLineName($lineID)
    {
        $databaseConnection = $this->databaseConnection;
        $lineID = mysqli_real_escape_string($this->databaseConnection, $lineID);
        $query = "SELECT line_name FROM train_lines WHERE line_id = $lineID";
        $result = $databaseConnection->query($query);
        $row = mysqli_fetch_all($result);
        return $row[0][0];
    }
}
