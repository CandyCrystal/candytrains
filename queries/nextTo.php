<?php
$station = $_GET["station"];
$e = new nextTo($station);
echo json_encode($e->nextTo());

class nextTo
{
    private $station;

    function __construct($station)
    {
        $this->station = $station;
    }
    function nextTo()
    {
        include "../config/connectNew.php";
        include "../database/getStationData.php";
        $stationQuery = new getStationData($databaseConnection);
        include "../database/getViaData.php";
        $viaQuery = new getViaData($databaseConnection);
        include "../database/getViaLineData.php";
        $viaLineQuery = new getViaLineData($databaseConnection);
        include "../database/getLineData.php";
        $lineQuery = new getLineData($databaseConnection);
        $vias = $viaQuery->getViasNextTo($this->station);
        $stationQuery = new getStationData($databaseConnection);
        $data = $this->getData(50, $stationQuery);
        $station = $this->station;
        usort($data, function ($e, $f) {
            return strcmp($e["departureTime"], $f["departureTime"]);
        });
        $status;
        $departures = array();
        $counter = [0, 0, 0, 0];
        foreach ($data as $row) {
            // $thisDep .= $row["lineRef"] . " " . $row["destinationRef"];
            // $thisDep .= $this->checkNextTo($row["lineRef"], $row["destinationRef"]);
            $line =  $lineQuery->getLineID($row["lineRef"]);
            $destination = $stationQuery->getStationID($row["destinationRef"]);
            $status .= "<br/>Line: " . $lineQuery->getLineName($line) . " Destination: " . $stationQuery->getStationRef($destination) . ": ";
            for ($i = 0; $i < 4; $i++) {
                if ($vias[$i] != "" && $counter[$i] < 2) {
                    $viaLines = $viaLineQuery->getViaLines($vias[$i][0]);
                    while ($viaLineRow = mysqli_fetch_array($viaLines)) {
                        // $status .= " viaLineLineID: " . $lineQuery->getLineName($viaLineRow["viaLineLineID"]) . " viaLineDestinationStationID: " . $stationQuery->getStationRef($viaLineRow["viaLineDestinationStationID"]) . "<br/>";
                        if ($viaLineRow["viaLineLineID"] == $line && $viaLineRow["viaLineDestinationStationID"] == $destination) {
                            $departures[$i * 2 + $counter[$i]] = $row;
                            $counter[$i]++;
                        }
                    }
                }
            }
        }
        $a = 0;
        $b = 0;
        $c = 0;
        $d = 0;
        return $departures;
    }
    function checkNextTo($line, $stationRef)
    {
        if ($line == "L1") {
            return "yes";
        } else {
            return "no";
        }
    }

    function getData($numStopVisits, $stationQuery)
    {
        $station = $stationQuery->getStationRef($this->station);
        $url = "https://siri.opm.jbv.no/jbv/sm/stop-monitoring.xml?MonitoringRef=" . $station . "&MaximumStopVisits=" . $numStopVisits . "&ServiceFeatureRef=passengerTrain&StopVisitTypes=departures";
        $url = str_replace("Æ", "%C3%86", $url);
        $url = str_replace("Ø", "%C3%98", $url);
        $url = str_replace("Å", "%C3%85", $url);
        // urlencode($url);

        //setting the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // Following line is compulsary to add as it is:
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
        $data = curl_exec($ch);
        curl_close($ch);

        $array_data = simplexml_load_string($data);
        $departures = array();
        $i = 0;
        foreach ($array_data->ServiceDelivery->StopMonitoringDelivery->children() as $row) {
            if ($row->MonitoredVehicleJourney->LineRef == null) {
                continue;
            }
            $monitoredCall = $row->MonitoredVehicleJourney->MonitoredCall;
            if ($monitoredCall->DepartureStatus == "cancelled") {
                continue;
            }
            $departures[$i] = [
                "departureTime" => strval($monitoredCall->AimedDepartureTime),
                "arrivalTime" => strval($monitoredCall->AimedArrivalTime),
                "departureTimeNew" => strval($monitoredCall->ExpectedDepartureTime),
                "arrivalTimeNew" => strval($monitoredCall->ExpectedArrivalTime),
                "departurePlatform" => strval($monitoredCall->DeparturePlatformName),
                "arrivalPlatform" => strval($monitoredCall->ArrivalPlatformName),
                "departureStatus" => strval($monitoredCall->DepartureStatus),
                "arrivalStatus" => strval($monitoredCall->ArrivalStatus),
                "destinationRef" => strval($row->MonitoredVehicleJourney->DirectionRef),
                "destinationName" => strval($row->MonitoredVehicleJourney->DirectionName),
                "originRef" => $stationQuery->getStationRefFromName($row->MonitoredVehicleJourney->OriginName),
                "lineRef" => strval($row->MonitoredVehicleJourney->LineRef),
                "operatorRef" => strval($row->MonitoredVehicleJourney->OperatorRef),
                "stationRef" => strval($row->MonitoringRef),
            ];
            $j = 0;
            $k = 0;
            $i++;
        }
        return $departures;
    }
}
