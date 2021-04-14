<?php

class getViaLineData
{
    private $databaseConnection;

    function __construct($databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }
    function getViaLines($viaLineViaID)
    {
        $databaseConnection = $this->databaseConnection;
        $query = "SELECT * FROM viaLines WHERE viaLineViaID = $viaLineViaID ORDER BY viaLineLineID";
        return $databaseConnection->query($query);
    }
}
