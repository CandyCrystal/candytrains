<?php
class getOperatorData
{
    private $databaseConnection;

    function __construct($conn)
    {
        $this->databaseConnection = $conn;
    }
    function getOperatorDropdown()
    {
        $databaseConnection = $this->databaseConnection;
        $query = "SELECT * FROM operators ORDER BY operator_name";

        $dropDownResults = $databaseConnection->query($query);
        $dropDown = "<select name='operator_id' id='operator_id' required>";

        while ($dropDownRow = mysqli_fetch_array($dropDownResults)) {
            $dropDown .= "<option value='{$dropDownRow["operator_id"]}'";
            $dropDown .= ">{$dropDownRow["operator_code"]} </option>";
        }
        $dropDown .= "</select>";
        return $dropDown;
    }
    function getOperatorInfo($operatorCode)
    {
        $databaseConnection = $this->databaseConnection;
        $sql = "SELECT * FROM operators WHERE operator_code='$operatorCode'";
        $result = $databaseConnection->query($sql);
        return mysqli_fetch_array($result);
    }
}
