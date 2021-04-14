<?php
// header("Content-type: application/json");
// $e = new situationQuery("LLS", "831:2021-01-01");
// echo $e->situations("no");

class situationQuery
{
    private $station;
    private $data;

    function __construct($station)
    {
        $this->station = $station;
        $this->data = $this->getData();
        $this->situations();
    }

    function getEnglishText($journey)
    {
        $data = $this->data;
        foreach ($data as $row) {
            if ($journey == $row["affectedJourney"]["journeyRef"]) {
                return $row["advice_en"];
            }
        }
    }
    function getNorwegianText($journey)
    {
        $data = $this->data;
        foreach ($data as $row) {
            if ($journey == $row["affectedJourney"]["journeyRef"]) {
                return $row["advice_no"];
            }
        }
    }

    function situations()
    {

        $data = $this->data;

        $i = 0;

        foreach ($data as $row) {
            if (in_array($this->station, $row["affectedJourney"]["stopPoints"], TRUE)) {
                // echo $i . " ";
                $i++;
                continue;
            }
            unset($data[$i]);
            $i++;
            continue;
        }
    }

    function getData()
    {
        $url = "https://siri.opm.jbv.no/jbv/sx/SituationExchange.xml?PreviewInterval=PT10M";
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
        $situations = array();
        $i = 0;
        foreach ($array_data->ServiceDelivery->SituationExchangeDelivery->Situations->children() as $situation) {
            $consequence = $situation->Consequences->Consequence;
            $situations[$i] = [
                "creationTime" => strval($situation->CreationTime),
                "situationNumber" => strval($situation->SituationNumber),
                "condition" => strval($consequence->Condition),
                "advice_no" => strval($consequence->Advice->Details[0]),
                "advice_en" => strval($consequence->Advice->Details[1]),
                "affectedJourney" => array(),
                // "arrivalTime" => strval($monitoredCall->AimedArrivalTime),
                // "departureTimeNew" => strval($monitoredCall->ExpectedDepartureTime),
                // "arrivalTimeNew" => strval($monitoredCall->ExpectedArrivalTime),
                // "departurePlatform" => strval($monitoredCall->DeparturePlatformName),
                // "arrivalPlatform" => strval($monitoredCall->ArrivalPlatformName),
                // "departureStatus" => strval($monitoredCall->DepartureStatus),
                // "arrivalStatus" => strval($monitoredCall->ArrivalStatus),
                // "destinationRef" => strval($row->MonitoredVehicleJourney->DirectionRef),
                // "destinationName" => strval($row->MonitoredVehicleJourney->DirectionName),
                // "originRef" => $stationQuery->getStationRefFromName($row->MonitoredVehicleJourney->OriginName),
                // "lineRef" => strval($row->MonitoredVehicleJourney->LineRef),
                // "operatorRef" => strval($row->MonitoredVehicleJourney->OperatorRef),
                // "stationRef" => strval($row->MonitoringRef)
            ];
            $j = 0;
            foreach ($consequence->Affects->VehicleJourneys->children() as $affectedJourney) {
                $affected = array(
                    "journeyRef" => strval($affectedJourney->DatedVehicleJourneyRef),
                    "stopPoints" => array(),
                );
                $k = 0;
                foreach ($affectedJourney->Calls->children() as $call) {
                    $thisCall = $call->StopPointRef;

                    $affected["stopPoints"][$k] = strval($thisCall);
                    $k++;
                }
                $situations[$i]["affectedJourney"] = $affected;
                $j++;
            }
            // break;
            $i++;
        }
        return $situations;
    }
}
