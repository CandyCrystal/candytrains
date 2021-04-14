<?php
header("Content-Type: application/xml");
include "./config/global.php";
include './config/connect.php';
$platform = $_GET["platform"];
$station = $_GET["station"];
$side = $_GET["side"];
$url = "https://siri.opm.jbv.no/jbv/sm/stop-monitoring.xml?MonitoringRef=" . $station . "&MaximumStopVisits=80&StopVisitTypes=departures&ServiceFeatureRef=passengerTrain";
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
$counter = 1;
$departures = array();
foreach ($array_data->ServiceDelivery->StopMonitoringDelivery->children() as $row) {
    if ($row->MonitoredVehicleJourney->MonitoredCall->DeparturePlatformName == $platform && $counter == 1) {

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
        $departurePlatform = $row->MonitoredVehicleJourney->MonitoredCall->DeparturePlatformName;
        $departureIsBoarding = $row->MonitoredVehicleJourney->MonitoredCall->DepartureBoardingActivity;
        $departureIsCancelled = $row->MonitoredVehicleJourney->MonitoredCall->DepartureStatus;
        $departureStatus = "normal";
        if ($departureIsBoarding == "noBoarding") {
            $departureStatus = "noBoarding";
        } else if ($departureIsCancelled == "cancelled") {
            $departureStatus = "cancelled";
        }
        if ($departureLineRef == "F1x"  || $departureLineRef == "F1"  || $departureLineRef == "F2" ) {
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
        if ($side == "right") {
            $departures[0] = array(
                "departure" => array(
                    "time" => strval(SUBSTR($departureTime, -14, 5)),
                    "newTime" => strval(SUBSTR($departureNewTime, -14, 5)),
                    "arrivalTime" => strval(SUBSTR($arrivalTime, -14, 5)),
                    "arrivalNewTime" => strval(SUBSTR($arrivalNewTime, -14, 5)),
                    "line" => strval($departureLineRef),
                    "destination" => strval($departureDestinationName),
                    "status" => strval($departureStatus),
                    "viaText" => strval($viaText),
                    "operator" => strval($departureOperatorRef),
                    "origin" => strval($departureOriginName),
                    "trainInfo" => array(
                        "trainDirection" => $departureVehicle["direction"],
                        "trainStopPoint" => $departureVehicle["stopPoint"],
                        "trainFormation" => array()
                    )
                )
            );
            $numParts = count($departureVehicle->tf_Vehicle->tf_TrainPart);
            for ($i = 0; $i < $numParts; $i++) {
                $thisPart = $departureVehicle->tf_Vehicle->tf_TrainPart[$numParts - 1 - $i];
                $trainPart = array(
                    "trainPart" => array(
                        "destination" => strval($thisPart["destination"]),
                        "type" => strval($thisPart["type"])
                    )
                );
                $numWagons = count($thisPart->tf_Wagon);
                for ($j = 0; $j < $numWagons; $j++) {
                    $thisWagon = $thisPart->tf_Wagon[$numWagons - 1 - $j];
                    $wagon = array(
                        "wagon" => array(
                            "id" => strval($thisWagon["id"]),
                            "type" => strval($thisWagon["type"]),
                            "length" => strval($thisWagon["length"]),
                            "state" => strval($thisWagon["state"]),
                            "occupancy" => strval($thisWagon["occupancy"]),
                            "commercialNumber" => strval($thisWagon["commercialNumber"]),
                            "service" => array()
                        )
                    );
                    for ($k = 0; $k < count($thisWagon->tf_Passenger->tf_Service); $k++) {
                        $thisService = $thisWagon->tf_Passenger->tf_Service[$k];
                        $service = array(
                            "category" => strval($thisService["category"])
                        );

                        $wagon["wagon"]["service"][$k] = $service;
                    }
                    $trainPart["trainPart"][$j] = $wagon;
                }
                $departures[0]["departure"]["trainInfo"]["trainFormation"][$i] = $trainPart;
            }
        } else {
            $departures[0] = array(
                "departure" => array(
                    "time" => strval(SUBSTR($departureTime, -14, 5)),
                    "newTime" => strval(SUBSTR($departureNewTime, -14, 5)),
                    "arrivalTime" => strval(SUBSTR($arrivalTime, -14, 5)),
                    "arrivalNewTime" => strval(SUBSTR($arrivalNewTime, -14, 5)),
                    "line" => strval($departureLineRef),
                    "destination" => strval($departureDestinationName),
                    "status" => strval($departureStatus),
                    "viaText" => strval($viaText),
                    "operator" => strval($departureOperatorRef),
                    "origin" => strval($departureOriginName),
                    "trainInfo" => array(
                        "trainDirection" => $departureVehicle["direction"],
                        "trainStopPoint" => $departureVehicle["stopPoint"],
                        "trainFormation" => array()
                    )
                )
            );
            for ($i = 0; $i < count($departureVehicle->tf_Vehicle->tf_TrainPart); $i++) {
                $thisPart = $departureVehicle->tf_Vehicle->tf_TrainPart[$i];
                $trainPart = array(
                    "trainPart" => array(
                        "destination" => strval($thisPart["destination"]),
                        "type" => strval($thisPart["type"])
                    )
                );
                for ($j = 0; $j < count($thisPart->tf_Wagon); $j++) {
                    $thisWagon = $thisPart->tf_Wagon[$j];
                    $wagon = array(
                        "wagon" => array(
                            "id" => strval($thisWagon["id"]),
                            "type" => strval($thisWagon["type"]),
                            "length" => strval($thisWagon["length"]),
                            "state" => strval($thisWagon["state"]),
                            "occupancy" => strval($thisWagon["occupancy"]),
                            "commercialNumber" => strval($thisWagon["commercialNumber"]),
                            "service" => array()
                        )
                    );
                    for ($k = 0; $k < count($thisWagon->tf_Passenger->tf_Service); $k++) {
                        $thisService = $thisWagon->tf_Passenger->tf_Service[$k];
                        $service = array(
                            "category" => strval($thisService["category"])
                        );

                        $wagon["wagon"]["service"][$k] = $service;
                    }
                    $trainPart["trainPart"][$j] = $wagon;
                }
                $departures[0]["departure"]["trainInfo"]["trainFormation"][$i] = $trainPart;
            }
        }
        $departures[1] = array();
        $counter++;
    } else if ($row->MonitoredVehicleJourney->MonitoredCall->DeparturePlatformName == $platform && $counter == 2) {
        $departure2Time = $row->MonitoredVehicleJourney->MonitoredCall->AimedDepartureTime;
        $departure2NewTime = $row->MonitoredVehicleJourney->MonitoredCall->ExpectedDepartureTime;
        $departure2LineRef = $row->MonitoredVehicleJourney->LineRef;
        $departure2DestinationName = $row->MonitoredVehicleJourney->DirectionName;
        // $departure2DestinationRef = $row->MonitoredVehicleJourney->DirectionRef;
        $departures[1] = array(
            "departure" => array(
                "time" => strval(SUBSTR($departure2Time, -14, 5)),
                "newTime" => strval(SUBSTR($departure2NewTime, -14, 5)),
                "line" => strval($departure2LineRef),
                "destination" => strval($departure2DestinationName),
            )
        );
        break;
    }
}

function array_to_xml($student_info, &$xml_student_info)
{
    foreach ($student_info as $key => $value) {
        if (is_array($value)) {
            if (!is_numeric($key)) {
                $subnode = $xml_student_info->addChild("$key");
                array_to_xml($value, $subnode);
            } else {
                array_to_xml($value, $xml_student_info);
            }
        } else {
            $xml_student_info->addChild("$key", "$value");
        }
    }
}

// initializing or creating array
$data = array('total_stud' => 500);

// creating object of SimpleXMLElement
$xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');

// function call to convert array to xml
array_to_xml($departures, $xml_data);

//saving generated xml file; 
echo $xml_data->asXML();
