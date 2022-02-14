<?php

$train = $_GET["train"];

$e = new departureQuery();
echo json_encode($e->getData($train));

class departureQuery
{

    function __construct()
    {
    }

    function getData($train)
    {
        $journeyURL = "https://api.srd.tf/banenor/live?id=" . $train;
        // $thisDeparture["trainRef"] = strval($thisDeparture["trainRef"]);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $journeyURL);
        // Following line is compulsary to add as it is:
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
        $journeyData = json_decode(curl_exec($ch));
        curl_close($ch);

        $journeyURL = "https://api.srd.tf/banenor/vehicle?id=" . $train;
        // $thisDeparture["trainRef"] = strval($thisDeparture["trainRef"]);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $journeyURL);
        // Following line is compulsary to add as it is:
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
        $vehicleData = json_decode(curl_exec($ch));
        curl_close($ch);

        include "../config/connect.php";
        include "../database/getPlatformData.php";
        $platformDataQuery = new getPlatformData($databaseConnection);
        $platformsRow = $platformDataQuery->getPlatformInfo($journeyData[0]->estimated[0]->code, $journeyData[0]->estimated[0]->track);
        $thisDeparture["side"] = $platformsRow["platform_side"];
        $thisDeparture["bus"] = $platformsRow["bus"];
        $thisDeparture["tram"] = $platformsRow["tram"];
        $thisDeparture["ferry"] = $platformsRow["ferry"];
        $thisDeparture["taxi"] = $platformsRow["taxi"];
        $thisDeparture["metro"] = $platformsRow["metro"];
        $thisDeparture["direction"] = $vehicleData->details->direction;
        $thisDeparture["line"] = $journeyData[0]->line;
        $thisDeparture["firstStop"] = 1;
        $stopsBefore = [];
        $stopsAfter = [];
        $thisDeparture["numBefore"] = count($journeyData[0]->recorded);
        $thisDeparture["numAfter"] = count($journeyData[0]->estimated);
        for ($i = 0; $i < count($journeyData[0]->recorded); $i++) {
            $thisDeparture["firstStop"] = 0;
            $stopsBefore[] = $journeyData[0]->recorded[$i];
        };
        for ($i = 0; $i < count($journeyData[0]->estimated); $i++) {
            $currentTime = new DateTime();
            $thisTime = new DateTime($journeyData[0]->estimated[$i]->expectedA);
            if ($currentTime > $thisTime && count($journeyData[0]->estimated) < $i-1) {
                $stopsBefore[] = $journeyData[0]->estimated[$i];
            } else {
                $stopsAfter[] = $journeyData[0]->estimated[$i];
            }
        };
        $stopsBefore = array_reverse($stopsBefore);
        $thisDeparture["stopsAfter"] = $stopsAfter;
        $thisDeparture["stopsBefore"] = $stopsBefore;
        return $thisDeparture;
    }
}
