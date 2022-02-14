<?php
$stationRef = $_GET["stationRef"];


if ($stationRef == "") {
    $stationRef = "OSL";
}

$side = $_GET["side"];

if ($side != "left") {
    $side = "right";
}

$h = new getPlatformDepartures();
$data = $h->getInfo($stationRef, $side);
echo json_encode($data);
class getPlatformDepartures
{

    function getInfo($stationRef, $side)
    {
        // include "./situationQuery.php";
        $url = "https://siri.opm.jbv.no/jbv/sm/stop-monitoring.xml?MonitoringRef=" .  $stationRef . "&MaximumStopVisits=80&StopVisitTypes=departures&ServiceFeatureRef=passengerTrain";
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
        $data = str_replace("tf:", "tf_", $data);
        //convert the XML result into array
        $array_data = simplexml_load_string($data);
        $departures = array(
            "departures" => array()
        );
        // $departures = array();
        foreach ($array_data->ServiceDelivery->StopMonitoringDelivery->children() as $row) {
            $departurePlatform = strval($row->MonitoredVehicleJourney->MonitoredCall->DeparturePlatformName);
            if($departurePlatform == null) {
                continue;
            }
            if ($departures["departures"][$departurePlatform] == null) {
                $journeyID = strval($row->MonitoredVehicleJourney->FramedVehicleJourneyRef->DatedVehicleJourneyRef);

                // $e = new situationQuery($stationRef);
                // $norwegianText = $e->getNorwegianText($journeyID);
                // $englishText = $e->getEnglishText($journeyID);
                $norwegianText = "test";
                $englishText = "test";

                $departureOperatorRef = $row->MonitoredVehicleJourney->OperatorRef;
                $departureStationRef = $row->MonitoringRef;
                $departureOriginName = $row->MonitoredVehicleJourney->OriginName;
                $departureDestinationRef = $row->MonitoredVehicleJourney->DirectionRef;
                $departureDestinationName = $row->MonitoredVehicleJourney->DirectionName;

                $departureVehicle = $row->MonitoredVehicleJourney->MonitoredCall->Extensions->SiriExtensionsContainer->tf_TrainFormation;
                $departureTime = $row->MonitoredVehicleJourney->MonitoredCall->AimedDepartureTime;
                $departureNewTime = $row->MonitoredVehicleJourney->MonitoredCall->ExpectedDepartureTime;
                $arrivalTime = $row->MonitoredVehicleJourney->MonitoredCall->AimedArrivalTime;
                $arrivalNewTime = $row->MonitoredVehicleJourney->MonitoredCall->ExpectedArrivalTime;
                $departureLineRef = $row->MonitoredVehicleJourney->LineRef;
                $departureIsBoarding = $row->MonitoredVehicleJourney->MonitoredCall->DepartureBoardingActivity;
                $departureIsCancelled = $row->MonitoredVehicleJourney->MonitoredCall->DepartureStatus;

                $departureStatus = "normal";
                if ($departureIsBoarding == "noBoarding") {
                    $departureStatus = "noBoarding";
                } else if ($departureIsCancelled == "cancelled") {
                    $departureStatus = "cancelled";
                }
                if ($departureLineRef == "F1x"  || $departureLineRef == "F1"  || $departureLineRef == "F2") {
                    if ($departureDestinationRef != "GAR" && $departureStationRef != "GAR") {
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
                $departures["departures"][$departurePlatform] = array(
                    "time" => strval(SUBSTR($departureTime, -14, 5)),
                    "newTime" => strval(SUBSTR($departureNewTime, -14, 5)),
                    "arrivalTime" => strval(SUBSTR($arrivalTime, -14, 5)),
                    "arrivalNewTime" => strval(SUBSTR($arrivalNewTime, -14, 5)),
                    "line" => strval($departureLineRef),
                    "platform" => strval($departurePlatform),
                    "destination" => strval($departureDestinationName),
                    "status" => strval($departureStatus),
                    "viaText" => strval($viaText),
                    "operator" => strval($departureOperatorRef),
                    "origin" => strval($departureOriginName),
                    "norwegianText" => strval($norwegianText),
                    "englishText" => strval($englishText),
                    "trainInfo" => array(
                        "trainDirection" => strval($departureVehicle["direction"]),
                        "trainStopPoint" => strval($departureVehicle["stopPoint"]),
                        "trainParts" => array()
                    ),
                    "secondDeparture" => array()
                );
                if ($norwegianText != null) {
                    $departures[$departurePlatform]["viaText"] = "";
                }
                $numParts = count($departureVehicle->tf_Vehicle->tf_TrainPart);
                if ($side == "right") {
                    for ($i = 0; $i < $numParts; $i++) {
                        $thisPart = $departureVehicle->tf_Vehicle->tf_TrainPart[$numParts - 1 - $i];
                        $trainPart = array(
                            "destination" => strval($thisPart["destination"]),
                            "type" => strval($thisPart["type"])

                        );
                        $numWagons = count($thisPart->tf_Wagon);
                        for ($j = 0; $j < $numWagons; $j++) {
                            $thisWagon = $thisPart->tf_Wagon[$numWagons - 1 - $j];
                            $wagon = array(
                                "id" => strval($thisWagon["id"]),
                                "type" => strval($thisWagon["type"]),
                                "length" => strval($thisWagon["length"]),
                                "state" => strval($thisWagon["state"]),
                                "occupancy" => strval($thisWagon["occupancy"]),
                                "commercialNumber" => strval($thisWagon["commercialNumber"]),
                                "services" => array()

                            );
                            for ($k = 0; $k < count($thisWagon->tf_Passenger->tf_Service); $k++) {
                                $thisService = $thisWagon->tf_Passenger->tf_Service[$k];
                                $service = array(
                                    "category" => strval($thisService["category"])
                                );

                                $wagon["services"][$k] = $service;
                            }
                            $trainPart["wagons"][$j] = $wagon;
                        }
                        $departures["departures"][$departurePlatform]["trainInfo"]["trainParts"][$i] = $trainPart;
                    }
                } else {
                    for ($i = 0; $i < $numParts; $i++) {
                        $thisPart = $departureVehicle->tf_Vehicle->tf_TrainPart[$i];
                        $trainPart = array(
                            "destination" => strval($thisPart["destination"]),
                            "type" => strval($thisPart["type"])

                        );
                        for ($j = 0; $j < count($thisPart->tf_Wagon); $j++) {
                            $thisWagon = $thisPart->tf_Wagon[$j];
                            $wagon = array(
                                "id" => strval($thisWagon["id"]),
                                "type" => strval($thisWagon["type"]),
                                "length" => strval($thisWagon["length"]),
                                "state" => strval($thisWagon["state"]),
                                "occupancy" => strval($thisWagon["occupancy"]),
                                "commercialNumber" => strval($thisWagon["commercialNumber"]),
                                "services" => array()
                            );
                            for ($k = 0; $k < count($thisWagon->tf_Passenger->tf_Service); $k++) {
                                $thisService = $thisWagon->tf_Passenger->tf_Service[$k];
                                $service = array(
                                    "category" => strval($thisService["category"])
                                );

                                $wagon["services"][$k] = $service;
                            }
                            $trainPart["wagons"][$j] = $wagon;
                        }
                        $departures["departures"][$departurePlatform]["trainInfo"]["trainParts"][$i] = $trainPart;
                    }
                }
            } else if ($departures["departures"][$departurePlatform]["secondDeparture"]["time"] == null && $departurePlatform != null) {
                $departure2Time = $row->MonitoredVehicleJourney->MonitoredCall->AimedDepartureTime;
                $departure2NewTime = $row->MonitoredVehicleJourney->MonitoredCall->ExpectedDepartureTime;
                $departure2LineRef = $row->MonitoredVehicleJourney->LineRef;
                $departure2DestinationName = $row->MonitoredVehicleJourney->DirectionName;
                $departure2Status = $row->MonitoredVehicleJourney->MonitoredCall->DepartureStatus;
                $departure = array(
                    "time" => strval(SUBSTR($departure2Time, -14, 5)),
                    "newTime" => strval(SUBSTR($departure2NewTime, -14, 5)),
                    "line" => strval($departure2LineRef),
                    "destination" => strval($departure2DestinationName),
                    "status" => strval($departure2Status),
                );
                $departures["departures"][$departurePlatform]["secondDeparture"] = $departure;
            }
        }

        return $departures;
    }
}
