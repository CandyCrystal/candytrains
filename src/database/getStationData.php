<?php

class getStationData
{
    private $databaseConnection;

    function __construct($databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }
    function getStations($searchTerm)
    {
        $databaseConnection = $this->databaseConnection;
        $searchTerm = mysqli_real_escape_string($databaseConnection, $searchTerm);
        $sql = "SELECT 
        station_ref, station_name,station_lat,station_lng, station_types.type_name AS station_type, stations.station_type AS station_type_id
    FROM
        stations
    INNER JOIN station_types ON stations.station_type = station_types.type_id
    WHERE
        station_name LIKE '%$searchTerm%'
    ORDER BY station_name";
        return $databaseConnection->query($sql);
    }
    function getStationLines($input)
    {
        $input = mysqli_real_escape_string($this->databaseConnection, $input);
        $databaseConnection = $this->databaseConnection;
        $sql = "SELECT stations.station_name,lines_served.line_name AS 'line_name',lines_served.line_id, lines_served.line_shorthand AS 'line_code', lines_on_station.line_station_id AS 'entry_id',line_station_id FROM lines_on_station INNER JOIN stations ON lines_on_station.station_ref = stations.station_ref INNER JOIN lines_served ON lines_on_station.line_id = lines_served.line_id WHERE lines_on_station.station_ref = '$input' ORDER BY lines_served.line_shorthand";
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
    function getStationLinesDropdown($input)
    {
        $databaseConnection = $this->databaseConnection;
        $sql = "SELECT * FROM lines_served ORDER BY line_shorthand,line_name;";
        $dropDownResults = $databaseConnection->query($sql);
        $dropDown = "";
        while ($dropDownRow = mysqli_fetch_array($dropDownResults)) {
            $dropDown .= '<form class="delete" action="./database/manageStationLines.php" method="post">' .
                '<input hidden type="text" name="stationRef" value="' . $input . '">' .
                '<input hidden type="text" name="action" value="insert">' .
                '<input hidden type="text" name="lineID" value="' . $dropDownRow["line_id"] . '">' .
                '<input hidden type="text" name="returnUrl" value="../station.php?stationRef=' . $input . '">' .
                '<input type="submit" value="' . $dropDownRow["line_name"] . '" class="button"></form>';
        }

        return $dropDown;
    }
    function getStationTypeDropdown($id)
    {
        $databaseConnection = $this->databaseConnection;
        $sql = "SELECT * FROM station_types ORDER BY type_name";

        $dropDownResults = $databaseConnection->query($sql);
        $dropDown = "<select name='station_type' required><option></option>";
        while ($dropDownRow = mysqli_fetch_array($dropDownResults)) {
            $dropDown .= "<option value='{$dropDownRow["type_id"]}'";
            if ($dropDownRow['type_id'] == "$id") {
                $dropDown .= "selected='selected'";
            }
            $dropDown .= ">{$dropDownRow["type_name"]} </option>";
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
        $stationRef = mysqli_real_escape_string($this->databaseConnection, $stationRef);
        $query = "SELECT station_name FROM stations WHERE station_ref = '$stationRef'";
        $result = $databaseConnection->query($query);
        $row = mysqli_fetch_all($result);
        return $row[0][1];
    }
    function getStationRefFromName($stationName)
    {
        $databaseConnection = $this->databaseConnection;
        $stationName = mysqli_real_escape_string($this->databaseConnection, $stationName);
        $query = "SELECT station_ref FROM stations WHERE station_name = '$stationName'";
        $result = $databaseConnection->query($query);
        $row = mysqli_fetch_all($result);
        return $row[0][0];
    }
}
