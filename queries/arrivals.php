<?php
include "../config/connectNew.php";
include "../database/getStationData.php";
$stationID = $_GET["station"];
$stationQuery = new getStationData($databaseConnection);
$e = new arrivalQuery($stationID, $stationQuery);
echo json_encode($e->arrivals());

class arrivalQuery
{
    private $stationID;
    private $databaseConnection;

    function __construct($stationID, $stationQuery)
    {
        $this->stationQuery = $stationQuery;
        $this->stationID = $stationID;
    }

    function arrivals()
    {

        $data = $this->getData(30);

        $i = 0;
        $j = 0;
        $k = 0;
        // $firstDeparture = 0;
        // $lastDeparture = 14;
        foreach ($data as $row) {
            if ($row["arrivalTime"] == NULL || $j >= 20) {
                unset($data[$i]);
                $i++;
                $k++;
                continue;
            }
            $j++;
            $i++;
        }
        usort($data, function ($a, $b) {
            return strcmp($a["arrivalTime"], $b["arrivalTime"]);
        });
        return $data;
    }

    function getData($numStopVisits)
    {
        $stationRef = $this->stationQuery->getStationRef($this->stationID);

        $url = "https://siri.opm.jbv.no/jbv/sm/stop-monitoring.xml?MonitoringRef=" . $stationRef . "&MaximumStopVisits=" . $numStopVisits . "&ServiceFeatureRef=passengerTrain&StopVisitTypes=arrivals";
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
        $arrivals = array();
        $i = 0;
        $counter = 0;
        foreach ($array_data->ServiceDelivery->StopMonitoringDelivery->children() as $row) {
            $monitoredCall = $row->MonitoredVehicleJourney->MonitoredCall;
            $arrivals[$i] = [
                "arrivalTime" => strval($monitoredCall->AimedArrivalTime),
                "arrivalTimeNew" => strval($monitoredCall->ExpectedArrivalTime),
                "arrivalPlatform" => strval($monitoredCall->ArrivalPlatformName),
                "arrivalStatus" => strval($monitoredCall->ArrivalStatus),
                "originName" => strval($row->MonitoredVehicleJourney->OriginName),
                "originRef" => strval($this->stationQuery->getStationRefFromName($row->MonitoredVehicleJourney->OriginName)),
                "lineRef" => strval($row->MonitoredVehicleJourney->LineRef),
                "operatorRef" => strval($row->MonitoredVehicleJourney->OperatorRef),
                "stationRef" => strval($row->MonitoringRef)
            ];
            $j = 0;
            $k = 0;
            $i++;
        }
        return $arrivals;
    }
}
