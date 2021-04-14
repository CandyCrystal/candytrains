
<?php include "./config/global.php";
include './config/connect.php';
$platform = $_GET["platform"];
$station = $_GET["station"];
$currentTime = date("H:i", time());
$currentTimeFull = date("Y-m-d") . "T" . date("H:i:s") . "+01:00";
$url = "https://siri.opm.jbv.no/jbv/sm/stop-monitoring.xml?MonitoringRef=" . $station . "&MaximumStopVisits=10&StopVisitTypes=departures&ServiceFeatureRef=passengerTrain";
$url = str_replace("Æ", "%C3%86", $url);
$url = str_replace("Ø", "%C3%98", $url);
$url = str_replace("Å", "%C3%85", $url);
urlencode($url);

//setting the curl parameters.
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
// Following line is compulsary to add as it is:
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
$data = curl_exec($ch);
curl_close($ch);

//convert the XML result into array
$array_data = simplexml_load_string($data);
$counter = 1;
foreach ($array_data->ServiceDelivery->StopMonitoringDelivery->children() as $row) {
    if ($row->MonitoredVehicleJourney->MonitoredCall->DeparturePlatformName == $platform && $counter == 1) {
        $departureTime = $row->MonitoredVehicleJourney->MonitoredCall->AimedDepartureTime;
        $departureNewTime = $row->MonitoredVehicleJourney->MonitoredCall->ExpectedDepartureTime;
        $arrivalTime = $row->MonitoredVehicleJourney->MonitoredCall->AimedArrivalTime;
        $arrivalNewTime = $row->MonitoredVehicleJourney->MonitoredCall->ExpectedArrivalTime;
        $departureLineRef = $row->MonitoredVehicleJourney->LineRef;
        $departurePlatform = $row->MonitoredVehicleJourney->MonitoredCall->DeparturePlatformName;
        $departureIsBoarding = $row->MonitoredVehicleJourney->MonitoredCall->DepartureBoardingActivity;
        $departureIsCancelled = $row->MonitoredVehicleJourney->MonitoredCall->DepartureStatus;
        $departureStatus = "normal";
        if ($departureIsBoarding == "noBoarding") {
            $departureStatus = "noBoarding";
        } else if ($departureIsCancelled == "cancelled") {
            $departureStatus = "cancelled";
        }
        $departureOperatorRef = $row->MonitoredVehicleJourney->OperatorRef;
        $departureStationRef = $row->MonitoringRef;
        $departureOriginName = $row->MonitoredVehicleJourney->OriginName;
        $departureDestinationRef = $row->MonitoredVehicleJourney->DirectionRef;
        $departureDestinationName = $row->MonitoredVehicleJourney->DirectionName;
        if ($departureLineRef == "F1x" && $departureStationRef != "GAR" || $departureLineRef == "F1" && $departureStationRef != "GAR" || $departureLineRef == "F2" && $departureStationRef != "GAR") {
            if ($departureDestinationRef != "GAR") {
                $departureStatus = "noBoarding";
            }
            if ($departureStationRef == "OSL" && $departureDestinationRef == "GAR" && $departureLineRef != "F2") {
                $viaText = "Direkte til Oslo Lufthavn <i class='fas fa-plane'></i> / Direct to Oslo Airport";
            } else if ($departureDestinationRef == "GAR") {
                $viaText = "Ingen avstigning før Oslo Lufthavn <i class='fas fa-plane'></i> /<br/> No disembarking before Oslo Airport";
            }
        } else {
            $departureVia1 = $row->MonitoredVehicleJourney->Via[0]->PlaceName;
            if ($departureVia1 != NULL) {
                $viaText = "via " . $departureVia1;
            }
            $departureVia2 = $row->MonitoredVehicleJourney->Via[1]->PlaceName;
            if ($departureVia2 != NULL) {
                $viaText = $viaText . " • " . $departureVia2;
            }
            $departureVia3 = $row->MonitoredVehicleJourney->Via[2]->PlaceName;
            if ($departureVia3 != NULL) {
                $viaText = $viaText . " • " . $departureVia3;
            }
            $departureVia4 = $row->MonitoredVehicleJourney->Via[3]->PlaceName;
            if ($departureVia4 != NULL) {
                $viaText = $viaText . " • " . $departureVia4;
            }
            $departureVia5 = $row->MonitoredVehicleJourney->Via[4]->PlaceName;
            if ($departureVia5 != NULL) {
                $viaText = $viaText . " • " . $departureVia5;
            }
        }
        $counter++;
    } else if ($row->MonitoredVehicleJourney->MonitoredCall->DeparturePlatformName == $platform && $counter == 2) {
        $departure2Time = $row->MonitoredVehicleJourney->MonitoredCall->AimedDepartureTime;
        $departure2NewTime = $row->MonitoredVehicleJourney->MonitoredCall->ExpectedDepartureTime;
        $departure2LineRef = $row->MonitoredVehicleJourney->LineRef;
        $departure2DestinationRef = $row->MonitoredVehicleJourney->DirectionRef;
        $departure2DestinationName = $row->MonitoredVehicleJourney->DirectionName;
        break;
    }
}
echo $departureStatus . "|" . SUBSTR($departureTime, -14, 5) . "|" . SUBSTR($departureNewTime, -14, 5) . "|" .$departureLineRef . "|" . $departureOperatorRef . "|" .$departureOriginName . "|" . $departureDestinationName . "|" . $viaText . "|" .  SUBSTR($ArrivalTime, -14, 5) . "|" . SUBSTR($ArrivalNewTime, -14, 5) . "|" . substr($departure2Time, -14, 5) . "|" . substr($departure2NewTime, -14, 5) . "|" . $departure2LineRef . "|" . $departure2DestinationName;