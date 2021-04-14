<?php
class getPlatformData
{
    private $databaseConnection;

    function __construct($conn)
    {
        $this->databaseConnection = $conn;
    }
    function getPlatforms($stationID)
    {
        $stationID = mysqli_real_escape_string($this->databaseConnection, $stationID);
        $query = "SELECT * FROM platforms WHERE platformStationID = $stationID ORDER BY platformNumber";
        return $this->databaseConnection->query($query);
    }
    function getPlatformInfo($stationID, $platformNumber)
    {
        $stationID = mysqli_real_escape_string($this->databaseConnection, $stationID);
        $platformNumber = mysqli_real_escape_string($this->databaseConnection, $platformNumber);
        $query = "SELECT * FROM platforms WHERE platformStationID = $stationID AND platformNumber = $platformNumber LIMIT 1";
        $result = $this->databaseConnection->query($query);
        while ($platform = mysqli_fetch_array($result)) {
            return $platform;
        }
    }
}
