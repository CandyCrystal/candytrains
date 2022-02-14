<?php
// include "../config/connect.php";
// include "../database/getStationData.php";
// $stationRef = $_GET["station"];
// $stationQuery = new getStationData($databaseConnection);
// $e = new departureQuery($stationRef, $stationQuery);
// echo json_encode($e->departures());

// class departureQuery
// {
//     private $stationRef;
//     private $databaseConnection;

//     function __construct($stationRef, $stationQuery)
//     {
//         $this->stationQuery = $stationQuery;
//         $this->stationRef = $stationRef;
//     }

//     function departures()
//     {

//         $data = $this->getData(30, $this->stationQuery);

//         $i = 0;
//         $j = 0;
//         $k = 0;
//         // $firstDeparture = 0;
//         // $lastDeparture = 14;
//         foreach ($data as $row) {
//             if ($row["departureTime"] == NULL || $j >= 20 || $row["stationRef"] != "GAR" && $row["destinationRef"] != "GAR" && $row["operatorRef"] == "FLY") {
//                 unset($data[$i]);
//                 $i++;
//                 $k++;
//                 continue;
//             }
//             $j++;
//             $i++;
//         }
//         usort($data, function ($a, $b) {
//             return strcmp($a["departureTime"], $b["departureTime"]);
//         });
//         return $data;
//     }

//     function getData($numStopVisits, $stationQuery)
//     {
//         include "./situationQuery.php";
//         $stationRef = $this->stationRef;
//         $e = new situationQuery($stationRef);

//         $url = "https://siri.opm.jbv.no/jbv/sm/stop-monitoring.xml?MonitoringRef=" . $stationRef . "&MaximumStopVisits=" . $numStopVisits . "&ServiceFeatureRef=passengerTrain&StopVisitTypes=departures";
//         $url = str_replace("Æ", "%C3%86", $url);
//         $url = str_replace("Ø", "%C3%98", $url);
//         $url = str_replace("Å", "%C3%85", $url);
//         // urlencode($url);

//         //setting the curl parameters.
//         $ch = curl_init();
//         curl_setopt($ch, CURLOPT_URL, $url);
//         // Following line is compulsary to add as it is:
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
//         $data = curl_exec($ch);
//         curl_close($ch);

//         $array_data = simplexml_load_string($data);
//         $departures = array();
//         $i = 0;
//         $counter = 0;
//         foreach ($array_data->ServiceDelivery->StopMonitoringDelivery->children() as $row) {
//             $trainId = $row->MonitoredVehicleJourney->VehicleRef;
//             $line = strval($row->MonitoredVehicleJourney->LineRef);
//             if ($trainId >= 2611 && $trainId <= 2682) {
//                 $line = "L4";
//                 // bergen-arna
//             } else if ($trainId >= 61 && $trainId <= 64 || $trainId >= 601 && $trainId <= 606) {
//                 $line = "R40";
//                 // oslo s-bergen
//             } else if ($trainId >= 1801 && $trainId <= 1838) {
//                 $line = "R41";
//                 // bergen-voss-myrdal
//             } else if ($trainId >= 1852 && $trainId <= 1862) {
//                 $line = "L42";
//                 //flåmsbana
//             } else if ($trainId >= 3000 && $trainId <= 3163) {
//                 $line = "L5";
//                 // Jærbanen
//             } else if ($trainId >= 701 && $trainId <= 726) {
//                 $line = "R50";
//                 // oslo s-stavanger
//             } else if ($trainId >= 2570 && $trainId <= 2589) {
//                 $line = "L51";
//                 // Notodden-porsgrunn
//             } else if ($trainId >= 2060 && $trainId <= 2075) {
//                 $line = "L52";
//                 // nelaug-arendal
//             } else if ($trainId >= 421 && $trainId <= 457 || $trainId >= 1700 && $trainId <= 1773) {
//                 $line = "L6";
//                 // Trønderbanen
//             } else if ($trainId >= 41 && $trainId <= 47 || $trainId >= 405 && $trainId <= 406) {
//                 $line = "R60";
//                 // Oslo S-Trondheim
//             } else if ($trainId >= 407 && $trainId <= 416 || $trainId >= 2378 && $trainId <= 2391) {
//                 $line = "R61";
//                 // Hamar-Trondheim
//             } else if ($trainId >= 2340 && $trainId <= 2347) {
//                 $line = "L62";
//                 // Raumabanen
//             } else if ($trainId >= 470 && $trainId <= 479) {
//                 $line = "R70";
//                 // Nordlandsbanen
//             } else if ($trainId >= 0 && $trainId <= 0) {
//                 $line = "L71";
//                 // Meråkerbanen
//             } else if ($trainId >= 1781 && $trainId <= 1792) {
//                 $line = "L72";
//                 // Bodø-Rognan
//             } else if ($trainId >= 0 && $trainId <= 0) {
//                 $line = "L80";
//                 // Ofotbanen
//             }
//             $trainId = strval($trainId . " - ");
//             $journeyID = strval($row->MonitoredVehicleJourney->FramedVehicleJourneyRef->DatedVehicleJourneyRef);

//             $norwegianText = $e->getNorwegianText($journeyID);
//             $englishText = $e->getEnglishText($journeyID);

//             $monitoredCall = $row->MonitoredVehicleJourney->MonitoredCall;
//             $departures[$i] = [
//                 "departureTime" => strval($monitoredCall->AimedDepartureTime),
//                 "norwegianText" => strval($norwegianText),
//                 "englishText" => strval($englishText),
//                 "departureTimeNew" => strval($monitoredCall->ExpectedDepartureTime),
//                 "departurePlatform" => strval($monitoredCall->DeparturePlatformName),
//                 "departureStatus" => strval($monitoredCall->DepartureStatus),
//                 "destinationRef" => strval($row->MonitoredVehicleJourney->DirectionRef),
//                 "destinationName" => strval($row->MonitoredVehicleJourney->DirectionName),
//                 "lineRef" => strval($line),
//                 "operatorRef" => strval($row->MonitoredVehicleJourney->OperatorRef),
//                 "stationRef" => strval($row->MonitoringRef)
//             ];
//             $j = 0;
//             $k = 0;
//             foreach ($row->MonitoredVehicleJourney->children() as $via) {
//                 if ($stationQuery->getStationRefFromName($via->PlaceName) != NULL) {
//                     $departures[$i]["viaText"][$j]["code"] = $stationQuery->getStationRefFromName($via->PlaceName);
//                     $departures[$i]["viaText"][$j]["name"] = strval($via->PlaceName);
//                     $j++;
//                 }
//                 $k++;
//             }
//             $i++;
//         }
//         return $departures;
//     }
// }
