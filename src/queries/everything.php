<?php
// include "../config/connect.php";
// include "../database/getStationData.php";
// $stationRef = $_GET["station"];
// $e = new departureQuery($stationRef);
// echo json_encode($e->departures(), JSON_UNESCAPED_UNICODE);

// class departureQuery
// {
//     private $stationRef;

//     function __construct($stationRef)
//     {
//         $this->stationRef = $stationRef;
//     }

//     function departures()
//     {

//         $data = $this->getData(10);

//         $i = 0;
//         $j = 0;
//         $k = 0;
//         // $firstDeparture = 0;
//         // $lastDeparture = 14;
//         foreach ($data as $row) {
//             if ($row["departureTime"] == "" && $row["arrivalTime"] == "" || $j >= 50) {
//                 unset($data[$i]);
//                 $i++;
//                 $k++;
//                 continue;
//             }
//             $j++;
//             $i++;
//         }
//         usort($data, function ($a, $b) {
//             return strcmp($a["sortTime"], $b["sortTime"]);
//         });
//         return $data;
//     }

//     function getData($numStopVisits)
//     {
//         $stationRef = $this->stationRef;

//         $url = "https://siri.opm.jbv.no/jbv/sm/stop-monitoring.xml?MonitoringRef=" . $stationRef . "&MaximumStopVisits=" . $numStopVisits;
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

//         $data = str_replace("tf:", "tf_", $data);
//         $array_data = simplexml_load_string($data);
//         $departures = array();
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
//             $journeyURL = "https://t.srd.tf/api/live.php?id=" . $trainId;
//             $trainId = strval($trainId);
//             $ch = curl_init();
//             curl_setopt($ch, CURLOPT_URL, $journeyURL);
//             // Following line is compulsary to add as it is:
//             curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//             curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
//             $journeyData = json_decode(curl_exec($ch));
//             $data = str_replace("tf:", "tf_", $data);
//             curl_close($ch);

//             $stopsBefore = [];
//             $stops = [];
//             $stopsAfter = [];
//             $status = 0;
//             for ($i = 0; $i < count($journeyData[0]->recorded); $i++) {
//                 if ($journeyData[0]->recorded[$i]->code == $stationRef) {
//                     $status = 1;
//                     $stopsBefore[] = $journeyData[0]->recorded[$i];
//                 }
//                 if ($status == 0) {
//                     $stopsBefore[] = $journeyData[0]->recorded[$i];
//                 } else if ($status == 1) {
//                     $stopsAfter[] = $journeyData[0]->recorded[$i];
//                 }
//                 if ($status == 0 && $i == count($journeyData[0]->recorded)) {
//                     $stopsAfter[] = $journeyData[0]->recorded[$i];
//                 }
//             };

//             for ($i = 0; $i < count($journeyData[0]->estimated); $i++) {
//                 if ($journeyData[0]->estimated[$i]->code == $stationRef) {
//                     $status = 1;
//                     $stopsBefore[] = $journeyData[0]->estimated[$i];
//                 }
//                 if ($status == 0) {
//                     $stopsBefore[] = $journeyData[0]->estimated[$i];
//                 } else if ($status == 1) {
//                     $stopsAfter[] = $journeyData[0]->estimated[$i];
//                 }
//                 if ($status == 0 && $i == count($journeyData[0]->estimated)) {
//                     $stopsAfter[] = $journeyData[0]->estimated[$i];
//                 }
//             };
//             $monitoredCall = $row->MonitoredVehicleJourney->MonitoredCall;
//             $departureStatus = strval($monitoredCall->DepartureStatus);
//             $arrivalStatus = strval($monitoredCall->ArrivalStatus);
//             $arrivalTime = strval($monitoredCall->AimedArrivalTime);
//             $departureTime = strval($monitoredCall->AimedDepartureTime);
//             $sortTime = $departureTime;
//             $operator = strval($row->MonitoredVehicleJourney->OperatorRef);
//             if ($operator == "VYT" || $operator == "VYG") {
//                 $operator = "VY";
//             } else if ($operator == "SJN") {
//                 $operator = "SJ";
//             }
//             if ($departureTime == "") {
//                 $sortTime = $arrivalTime;
//             }
//             $platform = strval($monitoredCall->DeparturePlatformName);
//             if (strlen($platform) == 0) {
//                 $platform = strval($monitoredCall->ArrivalPlatformName);
//             }
//             $departures[] = [
//                 "trainRef" => $trainId,
//                 "sortTime" => $sortTime,
//                 "arrivalTime" => $arrivalTime,
//                 "originName" => strval($row->MonitoredVehicleJourney->OriginName),
//                 "lineRef" => $line,
//                 "destinationName" => strval($row->MonitoredVehicleJourney->DirectionName),
//                 "departureTime" => strval($monitoredCall->AimedDepartureTime),
//                 "departurePlatform" => $platform,
//                 "departureTimeNew" => strval($monitoredCall->ExpectedDepartureTime),
//                 "departureStatus" => $departureStatus,
//                 "arrivalStatus" => $arrivalStatus,
//                 // "destinationRef" => strval($row->MonitoredVehicleJourney->DirectionRef),
//                 "operatorRef" => $operator,
//                 "stopsBefore" => $stopsBefore,
//                 "stopsAfter" => $stopsAfter,
//                 // "journeyData" => $journeyData[0]->estimated,
//                 // "stationRef" => strval($row->MonitoringRef)
//             ];
//         }
//         return $departures;
//     }
// }
