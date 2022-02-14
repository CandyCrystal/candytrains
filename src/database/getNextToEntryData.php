<?php

class getNextToEntryData
{
    private $databaseConnection;

    function __construct($databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }
    function getEntries($parent)
    {
        $databaseConnection = $this->databaseConnection;
        $parent = mysqli_real_escape_string($this->databaseConnection, $parent);
        $query = "SELECT * FROM next_to_entries WHERE entry_parent = $parent ORDER BY entry_line";
        return $databaseConnection->query($query);
    }
}
