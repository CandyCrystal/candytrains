<?php
class getSearchResults
{
    private $databaseConnection;

    function __construct($databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }
    function search($query)
    {
        $query = mysqli_real_escape_string($this->databaseConnection, $query);
        $search_results = array();
        $search_results["stations"] = $this->searchStations($query);
        $search_results["trains"] = $this->searchTrains($query);
    }
    function searchStations($searchTerm)
    {
        $databaseConnection = $this->databaseConnection;
        $searchTerm = mysqli_real_escape_string($databaseConnection, $searchTerm);
        $sql = "SELECT station_ref, station_name,station_lat,station_lng, station_types.type_name AS station_type
                FROM stations 
                INNER JOIN station_types ON stations.station_type = station_types.type_id 
                WHERE station_name LIKE '%$searchTerm%' OR station_ref LIKE '%$searchTerm%'
                ORDER BY station_name";
        return $databaseConnection->query($sql);
    }

    function searchTrains($searchTerm)
    {
        $trains = array();
        $databaseConnection = $this->databaseConnection;
        $urlDate = Date('Y-m-d', strtotime("today"));
        $preQuery = "SELECT 
        train_number AS 'num',
        IFNULL((SELECT route_id 
            FROM routes 
            WHERE train_number = num AND route_date <= '$urlDate' AND route_date >= '2021-12-01' 
            ORDER BY route_date DESC 
            LIMIT 1),
        (SELECT route_id 
            FROM routes 
            WHERE train_number = num 
            AND route_date >= '$urlDate' 
            ORDER BY route_date ASC 
            LIMIT 1)) AS 'route_id'
        FROM train_numbers WHERE train_number LIKE '%$searchTerm%'";
        $result = $this->databaseConnection->query($preQuery);
        while ($row = mysqli_fetch_assoc($result)) {
            $route_id = $row["route_id"];
            $sql = "SELECT 
                    r.route_id AS id,
                    DATE_FORMAT(r.route_date, '%a %d\/%m\/%Y') AS route_date,
                    DATE_FORMAT(r.route_date, '%Y-%m-%d') AS sort_date,
                    DATE_FORMAT(r.route_date, '%Y-%m-%d') AS dt,
                    r.train_number t,
                    r.train_number,
                    r.route_start,
                    r.route_end,
                    l.line_name AS line,
                    o.operator_code AS operator,
                    so.station_name  AS origin_station,
                    sd.station_name AS destination_station,
                    DATE_FORMAT(COALESCE(origin.planned_arrival,origin.planned_departure), '%H:%i')  AS origin_planned_time,
                    DATE_FORMAT(COALESCE(destination.planned_arrival,destination.planned_departure), '%H:%i') AS destination_planned_time,
                    FROM routes r
                    INNER JOIN route_entries origin ON r.route_id = origin.entry_route AND origin.entry_id = (SELECT entry_id FROM route_entries WHERE entry_route = r.route_id ORDER BY entry_number ASC LIMIT 1)
                    INNER JOIN route_entries destination ON r.route_id = destination.entry_route AND destination.entry_id = (SELECT entry_id FROM route_entries WHERE entry_route = r.route_id ORDER BY entry_number DESC LIMIT 1)
                    INNER JOIN stations so ON origin.entry_station = so.station_ref
                    INNER JOIN stations sd ON destination.entry_station = sd.station_ref
                    INNER JOIN operators o ON r.route_operator = o.operator_id
                    INNER JOIN train_lines l ON r.route_line = l.line_id
                    WHERE r.route_id = $route_id;";
            $result2 = $databaseConnection->query($sql);
            $route = mysqli_fetch_array($result2);
            $trains[] = $route;
        }

        return $trains;
    }
}
