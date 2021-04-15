<?php
class getNextToData
{
    private $databaseConnection;

    function __construct($databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }
    function getNextTos($stationRef)
    {
        $databaseConnection = $this->databaseConnection;
        $stationRef = mysqli_real_escape_string($this->databaseConnection, $stationRef);
        $query = "SELECT * FROM next_to WHERE next_to_station = '$stationRef' ORDER BY next_to_title";
        return $databaseConnection->query($query);
    }
    function getNextTosNextTo($stationRef)
    {
        $databaseConnection = $this->databaseConnection;
        $stationRef = mysqli_real_escape_string($this->databaseConnection, $stationRef);
        $query = "SELECT * FROM next_to WHERE next_to_station = '$stationRef' ORDER BY next_to_title";
        $result = $databaseConnection->query($query);
        return mysqli_fetch_all($result);
    }
}
