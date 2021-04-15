<?php
include "../config/connect.php";
include "../database/getStationData.php";
$stationRef = $_GET["station"];
$stationQuery = new getStationData($databaseConnection);
$e = new departureQuery($stationRef, $stationQuery);
echo json_encode($e->departures());

class departureQuery
{
    private $stationRef;
    private $databaseConnection;

    function __construct($stationRef, $stationQuery)
    {
        $this->stationQuery = $stationQuery;
        $this->stationRef = $stationRef;
    }

    function departures()
    {

        $data = $this->getData(30, $this->stationQuery);

        $i = 0;
        $j = 0;
        $k = 0;
        // $firstDeparture = 0;
        // $lastDeparture = 14;
        foreach ($data as $row) {
            if ($row["departureTime"] == NULL || $j >= 20 || $row["stationRef"] != "GAR" && $row["destinationRef"] != "GAR" && $row["operatorRef"] == "FLY") {
                unset($data[$i]);
                $i++;
                $k++;
                continue;
            }
            $j++;
            $i++;
        }
        usort($data, function ($a, $b) {
            return strcmp($a["departureTime"], $b["departureTime"]);
        });
        return $data;
    }

    function getData($numStopVisits, $stationQuery)
    {
        include "./situationQuery.php";
        $stationRef = $this->stationRef;
        $e = new situationQuery($stationRef);

        $url = "https://siri.opm.jbv.no/jbv/sm/stop-monitoring.xml?MonitoringRef=" . $stationRef . "&MaximumStopVisits=" . $numStopVisits . "&ServiceFeatureRef=passengerTrain&StopVisitTypes=departures";
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
        $counter = 0;
        foreach ($array_data->ServiceDelivery->StopMonitoringDelivery->children() as $row) {
            $journeyID = strval($row->MonitoredVehicleJourney->FramedVehicleJourneyRef->DatedVehicleJourneyRef);

            $norwegianText = $e->getNorwegianText($journeyID);
            $englishText = $e->getEnglishText($journeyID);

            $monitoredCall = $row->MonitoredVehicleJourney->MonitoredCall;
            $departures[$i] = [
                "departureTime" => strval($monitoredCall->AimedDepartureTime),
                "norwegianText" => strval($norwegianText),
                "englishText" => strval($englishText),
                "departureTimeNew" => strval($monitoredCall->ExpectedDepartureTime),
                "departurePlatform" => strval($monitoredCall->DeparturePlatformName),
                "departureStatus" => strval($monitoredCall->DepartureStatus),
                "destinationRef" => strval($row->MonitoredVehicleJourney->DirectionRef),
                "destinationName" => strval($row->MonitoredVehicleJourney->DirectionName),
                "lineRef" => strval($row->MonitoredVehicleJourney->LineRef),
                "operatorRef" => strval($row->MonitoredVehicleJourney->OperatorRef),
                "stationRef" => strval($row->MonitoringRef)
            ];
            $j = 0;
            $k = 0;
            foreach ($row->MonitoredVehicleJourney->children() as $via) {
                if ($stationQuery->getStationRefFromName($via->PlaceName) != NULL) {
                    $departures[$i]["viaText"][$j]["code"] = $stationQuery->getStationRefFromName($via->PlaceName);
                    $departures[$i]["viaText"][$j]["name"] = strval($via->PlaceName);
                    $j++;
                }
                $k++;
            }
            $i++;
        }
        return $departures;
    }
}
