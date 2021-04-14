<?php

class getStationData
{
    private $databaseConnection;

    function __construct($databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }
    function getStations($status)
    {
        switch ($status) {
            case "all":
                $filter = "";
                break;
            case "open":
                $filter = "WHERE stationIsClosed = 0";
                break;
            case "closed":
                $filter = "WHERE stationIsClosed = 1";
                break;
        }
        $databaseConnection = $this->databaseConnection;
        $sql = "SELECT * FROM stations $filter ORDER BY stationName";
        return $databaseConnection->query($sql);
    }
    function getStationDropdown($stationID)
    {
        $databaseConnection = $this->databaseConnection;
        $sql = "SELECT * FROM stations WHERE stationIsClosed=0 ORDER BY stationName";

        $dropDownResults = $databaseConnection->query($sql);
        $dropDown = "<select name='stationID' required><option></option>";
        while ($dropDownRow = mysqli_fetch_array($dropDownResults)) {
            $dropDown .= "<option value='{$dropDownRow["stationID"]}'";
            if ($dropDownRow['stationID'] == $stationID) {
                $dropDown .= "selected='selected'";
            }
            $dropDown .= ">{$dropDownRow["stationName"]} </option>";
        }
        $dropDown .= "</select>";
        return $dropDown;
    }
    function getStationInformation($stationID)
    {
        $databaseConnection = $this->databaseConnection;
        $stationID = mysqli_real_escape_string($databaseConnection, $stationID);
        $query = "SELECT * FROM stations WHERE stationID = $stationID";
        $result = $databaseConnection->query($query);
        return mysqli_fetch_array($result);
    }
    function getStationID($stationRef)
    {
        $databaseConnection = $this->databaseConnection;
        $query = "SELECT * FROM stations WHERE stationRef = '$stationRef'";
        $result = $databaseConnection->query($query);
        $row = mysqli_fetch_all($result);
        return $row[0][0];
    }
    function getStationName($stationRef)
    {
        $databaseConnection = $this->databaseConnection;
        $query = "SELECT stationName FROM stations WHERE stationRef = '$stationRef'";
        $result = $databaseConnection->query($query);
        $row = mysqli_fetch_all($result);
        return $row[0][0];
    }
    function getStationRef($stationID)
    {
        $databaseConnection = $this->databaseConnection;
        $query = "SELECT stationRef FROM stations WHERE stationID = '$stationID'";
        $result = $databaseConnection->query($query);
        $row = mysqli_fetch_all($result);
        return $row[0][0];
    }
    function getStationRefFromName($stationName)
    {
        $databaseConnection = $this->databaseConnection;
        $query = "SELECT stationRef FROM stations WHERE stationName = '$stationName'";
        $result = $databaseConnection->query($query);
        $row = mysqli_fetch_all($result);
        return $row[0][0];
    }
}
