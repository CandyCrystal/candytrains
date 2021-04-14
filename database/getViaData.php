<?php
class getViaData
{
    private $databaseConnection;

    function __construct($databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
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
}
