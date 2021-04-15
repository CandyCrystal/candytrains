<?php
class getPlatformData
{
    private $databaseConnection;

    function __construct($conn)
    {
        $this->databaseConnection = $conn;
    }
    function getPlatforms($stationRef)
    {
        $stationRef = mysqli_real_escape_string($this->databaseConnection, $stationRef);
        $query = "SELECT * FROM platforms WHERE platform_station = '$stationRef' ORDER BY platform_number";
        return $this->databaseConnection->query($query);
    }
    function getPlatformInfo($stationRef, $platformNumber)
    {
        $stationRef = mysqli_real_escape_string($this->databaseConnection, $stationRef);
        $platformNumber = mysqli_real_escape_string($this->databaseConnection, $platformNumber);
        $query = "SELECT * FROM platforms WHERE platform_station = '$stationRef' AND platform_number = $platformNumber LIMIT 1";
        $result = $this->databaseConnection->query($query);
        while ($platform = mysqli_fetch_array($result)) {
            return $platform;
        }
    }
}
