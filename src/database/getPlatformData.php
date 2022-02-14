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
        $query = "SELECT platform_id, platform_number,platform_station,platform_side, stations.station_has_bus AS 'bus', stations.station_has_tram AS 'tram', stations.station_has_taxi AS 'taxi', stations.station_has_ferry AS 'ferry', stations.station_has_metro AS 'metro' FROM platforms INNER JOIN stations ON platforms.platform_station = stations.station_ref WHERE platform_station = '$stationRef' AND platform_number = $platformNumber LIMIT 1";
        $result = $this->databaseConnection->query($query);
        while ($platform = mysqli_fetch_array($result)) {
            return $platform;
        }
    }
}
