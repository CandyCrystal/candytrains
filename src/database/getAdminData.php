<?php
class getAdminData
{
    private $databaseConnection;

    function __construct($conn)
    {
        $this->databaseConnection = $conn;
    }
    function getDateStats()
    {
        $query = "SELECT route_date, COUNT(*) AS 'num' FROM routes WHERE route_date >= '2021-12-01' GROUP BY route_date ORDER BY route_date ASC;";
        return $this->databaseConnection->query($query);
    }
    function getSingleDateStats($date, $date2)
    {
        $results = [];
        $query = "SELECT route_date, COUNT(*) AS 'num' FROM routes WHERE route_date >= '$date' AND route_date <= '$date2' GROUP BY route_date ORDER BY route_date ASC;";
        $results["total"] = $this->databaseConnection->query($query);
        return $results;
    }
    function getDateStatsPT()
    {
        $query = "SELECT route_date, COUNT(*) AS 'num' FROM routes WHERE route_date >= '2021-12-01' AND (route_operator = 1 OR route_operator = 2 OR route_operator = 3 OR route_operator = 4 OR route_operator = 5 OR route_operator = 6 OR route_operator = 9 OR route_operator = 21) GROUP BY route_date ORDER BY route_date ASC;";
        return $this->databaseConnection->query($query);
    }
    function getDateStatsGT()
    {
        $query = "SELECT route_date, COUNT(*) AS 'num' FROM routes WHERE route_date >= '2021-12-01' AND route_operator != 1 AND route_operator != 2 AND  route_operator != 3 AND route_operator != 4 AND route_operator != 5 AND  route_operator != 6 AND route_operator != 9 AND route_operator != 21 GROUP BY route_date ORDER BY route_date ASC;";
        return $this->databaseConnection->query($query);
    }
}
