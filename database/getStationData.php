<?php

class getStationData
{
    private $databaseConnection;

    function __construct($databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }
    function getStations()
    {
        $databaseConnection = $this->databaseConnection;
        $sql = "SELECT * FROM stations ORDER BY station_name";
        return $databaseConnection->query($sql);
    }
    function getStationDropdown($stationRef)
    {
        $databaseConnection = $this->databaseConnection;
        $sql = "SELECT * FROM stations ORDER BY station_name";

        $dropDownResults = $databaseConnection->query($sql);
        $dropDown = "<select name='stationRef' required><option></option>";
        while ($dropDownRow = mysqli_fetch_array($dropDownResults)) {
            $dropDown .= "<option value='{$dropDownRow["station_ref"]}'";
            if ($dropDownRow['station_ref'] == "$stationRef") {
                $dropDown .= "selected='selected'";
            }
            $dropDown .= ">{$dropDownRow["station_name"]} </option>";
        }
        $dropDown .= "</select>";
        return $dropDown;
    }
    function getStationInformation($stationRef)
    {
        $databaseConnection = $this->databaseConnection;
        $stationRef = mysqli_real_escape_string($databaseConnection, $stationRef);
        $query = "SELECT * FROM stations WHERE station_ref = '$stationRef'";
        $result = $databaseConnection->query($query);
        return mysqli_fetch_array($result);
    }
    function getStationName($stationRef)
    {
        $databaseConnection = $this->databaseConnection;
        $query = "SELECT station_name FROM stations WHERE station_ref = '$stationRef'";
        $result = $databaseConnection->query($query);
        $row = mysqli_fetch_all($result);
        return $row[0][1];
    }
    function getStationRefFromName($stationName)
    {
        $databaseConnection = $this->databaseConnection;
        $query = "SELECT station_ref FROM stations WHERE station_name = '$stationName'";
        $result = $databaseConnection->query($query);
        $row = mysqli_fetch_all($result);
        return $row[0][0];
    }
}
