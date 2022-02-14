<?php
$stationRef = $_GET["station"];
$queryType = $_GET["type"];
// header('Content-Type: application/json');
$e = new departureQuery($stationRef, $queryType);
if ($queryType == "departures_next_to") {
    echo json_encode($e->nextTo());
} else {
    echo json_encode($e->departures());
}

class departureQuery
{
    private $stationRef;

    function __construct($stationRef, $queryType)
    {
        $this->stationRef = $stationRef;
        $this->queryType = $queryType;
    }
    function nextTo()
    {
        $station = $this->stationRef;
        include "../config/connect.php";
        include "../database/getNextToData.php";
        $nextToQuery = new getNextToData($databaseConnection);
        include "../database/getNextToEntryData.php";
        $nextToEntryQuery = new getNextToEntryData($databaseConnection);
        include "../database/getLineData.php";
        $lineQuery = new getLineData($databaseConnection);

        $nextTos = $nextToQuery->getNextTosNextTo($station);
        $data = $this->getData(30, $this->queryType);
        usort($data, function ($e, $f) {
            return strcmp($e["expectedDepartureTime"], $f["expectedDepartureTime"]);
        });
        $departures = array();
        $counter = [0, 0, 0, 0];
        foreach ($data as $row) {
            $line =  $lineQuery->getLineID($row["lineRef"]);
            $destination = $row["directionRef"];
            for ($i = 0; $i < 4; $i++) {
                if ($nextTos[$i] != "" && $counter[$i] < 2) {
                    $nextToEntries = $nextToEntryQuery->getEntries($nextTos[$i][0]);
                    while ($nextToEntryRow = mysqli_fetch_array($nextToEntries)) {
                        if ($nextToEntryRow["entry_line"] == $line && $nextToEntryRow["entry_destination"] == $destination) {
                            $departures[$i * 2 + $counter[$i]] = $row;
                            $counter[$i]++;
                        }
                    }
                }
            }
        }
        return $departures;
    }

    function departures()
    {
        if ($this->queryType == "everything") {
            $data = $this->getData(11, $this->queryType);
        } else {
            $data = $this->getData(40, $this->queryType);
        }
        $i = 0;
        $j = 0;
        foreach ($data as $row) {
            if ($row["destinationName"] == "" && $row["originName"] == "" || $j >= 50) {
                unset($data[$i]);
                $i++;
                continue;
            }
            $j++;
            $i++;
        }
        usort($data, function ($a, $b) {
            return strcmp($a["sortTime"], $b["sortTime"]);
        });
        return $data;
    }

    function getData($numStopVisits, $queryType)
    {
        $stationRef = $this->stationRef;

        $url = "https://siri.opm.jbv.no/jbv/sm/stop-monitoring.xml?MonitoringRef=" . $stationRef . "&MaximumStopVisits=" . $numStopVisits;

        switch ($queryType) {
            case "departures":
            case "departures_cancelled":
            case "departures_non_cancelled":
            case "departures_next_to":
                $url .= "&StopVisitTypes=departures";
                break;
            case "arrivals":
                $url .= "&StopVisitTypes=arrivals";
                break;
        }



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

        $data = str_replace("tf:", "tf_", $data);
        $array_data = simplexml_load_string($data);
        $departures = array();
        foreach ($array_data->ServiceDelivery->StopMonitoringDelivery->children() as $row) {
            // return $array_data;
            $monitoredCall = $row->MonitoredVehicleJourney->MonitoredCall;
            if ($queryType == "departures_non_cancelled" || $queryType == "departures_next_to") {
                if ($monitoredCall->DepartureStatus == "cancelled") {
                    continue;
                }
            }
            if ($queryType == "departures_cancelled") {
                if ($monitoredCall->DepartureStatus != "cancelled") {
                    continue;
                }
            }
            $thisDeparture = [];
            $trainId = $row->MonitoredVehicleJourney->VehicleRef;
            $line = strval($row->MonitoredVehicleJourney->LineRef);
            $thisDeparture["operatorRef"] = strval($row->MonitoredVehicleJourney->OperatorRef);
            // if ($line == "F2" && $queryType == "webcam") {
            //     continue;
            // }
            if ($trainId == 2281 || $trainId >= 2100 && $trainId <= 2199) {
                $customLine = "L1x";
            } else 
            if ($trainId >= 2100 && $trainId <= 2199) {
                $customLine = "L1x";
            } else 
            if ($trainId == 300 || $trainId == 303 || $trainId == 308 || $trainId == 331 || $trainId == 341 || $trainId >= 351 && $trainId <= 354) {
                $customLine = "R10x";
            } else 
            if ($trainId == 838 || $trainId == 840 || $trainId >= 871 && $trainId <= 883) {
                $customLine = "R11x";
            } else 
            if ($trainId == 552) {
                $customLine = "L12x";
            } else 
            if ($trainId == 1609 || $trainId == 1613 || $trainId == 1642 || $trainId == 1646 || $trainId == 1679) {
                $customLine = "L13x";
            } else 
            if ($trainId >= 2854 && $trainId <= 2860) {
                $customLine = "L2x";
            } else 
            if ($trainId >= 390 && $trainId <= 399) {
                $customLine = "R20x";
            } else 
            if ($trainId == 1156 ||$trainId == 1159 ||$trainId == 1161 ) {
                $customLine = "L21x";
            } else 
            if ($trainId == 1901 || $trainId == 1923 || $trainId == 1928 || $trainId == 1932) {
                $customLine = "L22x";
            } else 
            if ($trainId == 252) {
                $customLine = "L3";
            } else 
            if ($trainId >= 271  && $trainId <= 287) {
                $customLine = "L3x";
            } else 
            if ($trainId == 209) {
                $customLine = "R30";
            } else 
            if ($trainId == 204 ||$trainId == 212 || $trainId == 216) {
                $customLine = "R30x";
            } else 
            if ($trainId >= 630 && $trainId <= 639) {
                // bergen-arna
                $customLine = "R15";
            } else if ($trainId >= 2600 && $trainId <= 2699) {
                // bergen-arna
                $customLine = "L4";
            } else if ($trainId == 603 || $trainId == 607) {
                // oslo s-bergen
                $customLine = "R40x";
            } else if ($trainId >= 60 && $trainId <= 69 || $trainId >= 601 && $trainId <= 609) {
                // oslo s-bergen
                $customLine = "R40";
            } else if ($trainId == 1802 || $trainId == 1808 || $trainId >= 1811 && $trainId <= 1812 || $trainId == 1818 || $trainId == 1821 || $trainId >= 1823 && $trainId <= 1825 || $trainId == 1829 || $trainId == 1833 || $trainId == 1840 || $trainId == 1845 || $trainId == 1848) {
                // bergen-voss-myrdal
                $customLine = "R41x";
            } else if ($trainId >= 1801 && $trainId <= 1848) {
                // bergen-voss
                $customLine = "R41";
            } else if ($trainId >= 1852 && $trainId <= 1885) {
                //flåmsbana
                $customLine = "L42";
            } else if ($trainId >= 3100 && $trainId <= 3163 || $trainId >= 3054 && $trainId <= 3055 || $trainId >= 3058 && $trainId <= 3059 || $trainId >= 3062 && $trainId <= 3063 || $trainId >= 3066 && $trainId <= 3067 || $trainId == 3070 || $trainId == 3074) {
                $customLine = "L5x";
                // Jærbanen
            } else if ($trainId >= 3000 && $trainId <= 3200) {
                $customLine = "L5";
                // Jærbanen
            } else if ($trainId >= 701 && $trainId <= 703 || $trainId >= 718 && $trainId <= 722|| $trainId == 2992 || $trainId == 2992) {
                $customLine = "R50x";
                // oslo s-stavanger
            } else if ($trainId >= 701 && $trainId <= 726) {
                $customLine = "R50";
                // oslo s-stavanger
            } else if ($trainId == 2570 || $trainId == 2589) {
                $customLine = "L51x";
                // Notodden-porsgrunn
            } else if ($trainId >= 2570 && $trainId <= 2589) {
                $customLine = "L51";
                // Notodden-porsgrunn
            } else if ($trainId >= 2060 && $trainId <= 2075) {
                $customLine = "L52";
                // nelaug-arendal
            } else if ($trainId >= 420 && $trainId <= 422 || $trainId >= 427 && $trainId <= 430 || $trainId >= 434 && $trainId <= 435 || $trainId == 438 || $trainId >= 441 && $trainId <= 442 || $trainId == 445 || $trainId >= 449 && $trainId <= 450 || $trainId == 457) {
                $customLine = "L6x";
                // Trønderbanen
            } else if ($trainId >= 422 && $trainId <= 456) {
                $customLine = "L6";
                // Trønderbanen
            } else if ($trainId >= 40 && $trainId <= 59 || $trainId >= 405 && $trainId <= 406) {
                $customLine = "R60";
                // Oslo S-Trondheim
            } else if ($trainId == 416 || $trainId == 413 || $trainId >= 2384 && $trainId <= 2385 || $trainId == 2387) {
                $customLine = "R61x";
                // Hamar-Trondheim 
            } else if ($trainId >= 407 && $trainId <= 416 || $trainId >= 2378 && $trainId <= 2391) {
                $customLine = "R61";
                // Hamar-Trondheim
            } else if ($trainId >= 2340 && $trainId <= 2347 && $trainId != 2343 && $trainId != 2340) {
                $customLine = "L62";
                // Raumabanen
            } else if ($trainId == 2343 || $trainId == 2340) {
                $customLine = "L62x";
                // Raumabanen
            } else if ($trainId == 470 || $trainId  >= 473 && $trainId <= 474 || $trainId >= 477 && $trainId <= 479) {
                $customLine = "R70x";
                // Nordlandsbanen
            } else if ($trainId >= 470 && $trainId <= 479) {
                $customLine = "R70";
                // Nordlandsbanen
            } else if ($trainId >= 1 && $trainId <= 1 || $trainId >= 1700 && $trainId <= 1774) {
                $customLine = "L71";
                // Meråkerbanen
            } else if ($trainId == 470 || $trainId >= 479 && $trainId <= 479 || $trainId == 1786 || $trainId >= 1788 && $trainId <= 1789) {
                $customLine = "L72x";
                // Bodø-Rognan
            } else if ($trainId >= 1781 && $trainId <= 1794) {
                $customLine = "L72";
                // Bodø-Rognan
            } else if ($trainId >= 1 && $trainId <= 1) {
                $customLine = "L8";
                // Ofotbanen
            } else if ($trainId >= 90 && $trainId <= 99) {
                $customLine = "R80";
                // Ofotbanen
            } else if ($line == "") {
                $customLine = strval($row->MonitoredVehicleJourney->OperatorRef);
                $line = strval($row->MonitoredVehicleJourney->OperatorRef);
                // 
            } else {
                $customLine = $line;
            }

            if($queryType == "webcam" && $line == "FLY1") {
                continue;
            }

            if ($queryType == "departures" || $queryType == "departures_non_cancelled") {
                foreach ($row->MonitoredVehicleJourney->children() as $via) {
                    if ($via->PlaceName != "") {
                        $thisDeparture["vias"][] = strval($via->PlaceName) . "";
                    }
                }
            }
            if ($queryType == "departures" || $queryType == "departures_non_cancelled" || $queryType == "departures_cancelled") {
                $thisDeparture["advice"] = [];
                $thisDeparture["advice"]["english"] = strval("");
                $thisDeparture["advice"]["norwegian"] = strval("");
            }
            if ($queryType == "departures_next_to") {
                $thisDeparture["directionRef"] = strval($row->MonitoredVehicleJourney->DirectionRef);
            }
            if ($queryType == "arrivals" || $queryType == "everything" || $queryType == "arrivals_departures") {
                $thisDeparture["arrivalPlatform"] = strval($monitoredCall->ArrivalPlatformName);
            }
            if ($queryType == "arrivals" || $queryType == "arrivals_departures" || $queryType == "webcam") {
                $thisDeparture["arrivalStatus"] = strval($monitoredCall->ArrivalStatus);
                $thisDeparture["arrivalPlatform"] = strval($monitoredCall->ArrivalPlatformName);
            }
            if ($queryType == "arrivals_departures" || $queryType == "webcam" || $queryType == "everything") {
                $thisDeparture["trainRef"] = strval($row->MonitoredVehicleJourney->VehicleRef);
            }
            if ($queryType == "departures" || $queryType == "departures_non_cancelled" || $queryType == "arrivals_departures" || $queryType == "webcam" || $queryType == "everything") {
                $thisDeparture["departureStatus"] = strval($monitoredCall->DepartureStatus);
            }

            if ($queryType == "departures" || $queryType == "departures_non_cancelled" || $queryType == "departures_cancelled" || $queryType == "departures_next_to" || $queryType == "arrivals_departures" || $queryType == "webcam" || $queryType == "everything") {
                $thisDeparture["aimedDepartureTime"] = strval($monitoredCall->AimedDepartureTime);
                $thisDeparture["departurePlatform"] = strval($monitoredCall->DeparturePlatformName);
                $thisDeparture["destinationName"] = strval($row->MonitoredVehicleJourney->DestinationName);
                $thisDeparture["destinationRef"] = strval($row->MonitoredVehicleJourney->DestinationRef);
                $thisDeparture["expectedDepartureTime"] = strval($monitoredCall->ExpectedDepartureTime);
            }
            if ($queryType == "arrivals" || $queryType == "arrivals_departures" || $queryType == "webcam" || $queryType == "everything") {
                $thisDeparture["arrivalStatus"] = strval($monitoredCall->ArrivalStatus);
                $thisDeparture["actualArrivalTime"] = strval($monitoredCall->ActualArrivalTime);
                $thisDeparture["aimedArrivalTime"] = strval($monitoredCall->AimedArrivalTime);
                $thisDeparture["expectedArrivalTime"] = strval($monitoredCall->ExpectedArrivalTime);
                $thisDeparture["originName"] = strval($row->MonitoredVehicleJourney->OriginName);
            }
            if ($queryType == "departures" || $queryType == "departures_non_cancelled" || $queryType == "departures_cancelled" || $queryType == "arrivals" || $queryType == "arrivals_departures" || $queryType == "webcam" || $queryType == "everything") {
                $thisDeparture["operatorRef"] = strval($row->MonitoredVehicleJourney->OperatorRef);
                if ($thisDeparture["operatorRef"] == "VYT" || $thisDeparture["operatorRef"] == "VYG") {
                    $thisDeparture["operatorRef"] = "VY";
                } else if ($thisDeparture["operatorRef"] == "SJN") {
                    $thisDeparture["operatorRef"] = "SJ";
                }
            }
            if ($queryType == "departures" || $queryType == "departures_non_cancelled" || $queryType == "departures_cancelled" || $queryType == "departures_next_to" || $queryType == "arrivals" || $queryType == "arrivals_departures" || $queryType == "webcam" || $queryType == "everything") {
                $thisDeparture["lineRef"] = strval($line);
                $thisDeparture["customLineRef"] = strval($customLine);
            }
            $thisDeparture["sortTime"] = $thisDeparture["aimedDepartureTime"];
            if ($thisDeparture["sortTime"] == "") {
                $thisDeparture["sortTime"] = $thisDeparture["aimedArrivalTime"];
            }

            $trainFormation = $monitoredCall->Extensions->SiriExtensionsContainer->tf_TrainFormation;
            $vehicle = $trainFormation->tf_Vehicle;
            if (1 == 2) {
                $thisDeparture["trainDetails"] = [];
                $thisDeparture["trainDetails"]["direction"] = $trainFormation["direction"] . "";
                $thisDeparture["trainDetails"]["stopPoint"] = $trainFormation["stopPoint"] . "";
            }
            if ($queryType == "everything") {
                $journeyURL = "https://api.srd.tf/banenor/train?id=" . $trainId;
                // $thisDeparture["trainRef"] = strval($thisDeparture["trainRef"]);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $journeyURL);
                // Following line is compulsary to add as it is:
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
                $journeyData = json_decode(curl_exec($ch));
                curl_close($ch);

                $stopsBefore = [];
                $stopsAfter = [];
                $status = 0;
                for ($i = 0; $i < count($journeyData[0]->stops); $i++) {
                    if ($journeyData[0]->stop[$i]->code == $stationRef) {
                        $status = 1;
                        $stopsBefore[] = $journeyData[0]->stops[$i];
                    }
                    if ($status == 0) {
                        $stopsBefore[] = $journeyData[0]->stops[$i];
                    } else if ($status == 1) {
                        $stopsAfter[] = $journeyData[0]->stops[$i];
                    }
                    if ($status == 0 && $i == count($journeyData[0]->stops)) {
                        $stopsAfter[] = $journeyData[0]->stops[$i];
                    }
                };
                $thisDeparture["stopsAfter"] = $stopsAfter;
                $thisDeparture["stopsBefore"] = $stopsBefore;
            }
            if ($queryType == "arrivals_departures" || $queryType == "webcam") {
                $trainType = strval($vehicle->tf_TrainPart->tf_Wagon["type"]);
                $trainType2 = strval($vehicle->tf_TrainPart[1]->tf_Wagon["type"]);
                if ($trainType == "") {
                    $thisDeparture["trainType"] = strval($vehicle->tf_TrainPart->tf_Engine["type"]);
                }
                $trainData = [];
                if ($vehicle != null) {
                    $train = "";
                    $traini = 0;
                    foreach ($vehicle->children() as $trainPart) {
                        $thisPart["wagons"] = [];
                        // $thisPart["destination"] = $trainPart["destination"] . "";
                        foreach ($trainPart->children() as $wagon) {
                            $thisWagon = [];
                            if ($traini == 5) {
                                $train .= "<br/>";
                            } else {
                            }
                            $traini++;
                            $train .= "<div style='float:left; margin-bottom:0.1vw;height:0.5vw;'>" . $wagon["type"] . "-</div>";
                            $thisWagon["type"] = $wagon["type"] . "";
                            $trainData[] = $thisWagon["type"] . "-" . $thisDeparture["operatorRef"];
                            $thisWagon["id"] = $wagon["id"] . "";
                            // $thisWagon["length"] = $wagon["length"] . "";
                            // $thisWagon["state"] = $wagon["state"] . "";
                            // $thisWagon["occupancy"] = $wagon["occupancy"] . "";
                            // $thisWagon["commercialNumber"] = $wagon["commercialNumber"] . "";
                            // $thisWagon["services"] = [];
                            // foreach ($wagon->tf_Passenger->children() as $service) {
                            //     $thisService = $service["category"] . "";
                            //     $thisWagon["services"][] = $thisService;
                            // }
                            $thisPart["wagons"][] = $thisWagon;
                        }
                        $thisVehicle[] = $thisPart;
                    }
                    $thisDeparture["trainDetails"]["train"][] = $thisPart;
                    $thisDeparture["train"] = $train;
                }
                $thisDeparture["trainData"] = $trainData;
                switch ($trainType) {
                    case "BM 69CII":
                    case "BS 69CII":
                        $trainType = "Type 69 C";
                        break;
                    case "BM 69D":
                    case "BS 69D":
                        $trainType = "Type 69 D";
                        break;
                    case "BM69H":
                    case "BS69H":
                        $trainType = "Type 69 H";
                        break;
                    case "BM71 BM":
                    case "BM71 BFM":
                        $trainType = "Type 71";
                        break;
                    case "BMA72":
                    case "BMB72":
                        $trainType = "Type 72";
                        break;
                    case "BM73":
                    case "BFM73":
                        $trainType = "Type 73";
                        break;
                    case "BM73A":
                    case "BFM73A":
                        $trainType = "Type 73 A";
                        break;
                    case "BM73B":
                    case "BFM73B":
                        $trainType = "Type 73 B";
                        break;
                    case "BMA74":
                    case "BMB74":
                        $trainType = "Type 74";
                        break;
                    case "BMA75":
                    case "BMB75":
                        $trainType = "Type 75";
                        break;
                    case "BMA75-2":
                    case "BMB75-2":
                        $trainType = "Type 75-2";
                        break;
                    case "BMA76":
                    case "BMB76":
                        $trainType = "Type 76";
                        break;
                    case "BM92":
                    case "BS92":
                        $trainType = "Type 92";
                        break;
                    case "BM93":
                    case "BCM93":
                        $trainType = "Type 93";
                        break;
                    case "EL18":
                    case "EL 18":
                    case "Di4":
                        $trainType = "Long distance train";
                        break;
                    default:
                        $trainType = $trainType;
                        break;
                }
                if ($trainType2 != "") {
                    $trainType .= " + ";
                    switch ($trainType2) {
                        case "BM 69CII":
                        case "BS 69CII":
                            $trainType .= "Type 69 C";
                            break;
                        case "BM 69D":
                        case "BS 69D":
                            $trainType .= "Type 69 D";
                            break;
                        case "BM69H":
                        case "BS69H":
                            $trainType .= "Type 69 H";
                            break;
                        case "BM71 BM":
                        case "BM71 BFM":
                            $trainType .= "Type 71";
                            break;
                        case "BMA72":
                        case "BMB72":
                            $trainType .= "Type 72";
                            break;
                        case "BM73":
                        case "BFM73":
                            $trainType .= "Type 73";
                            break;
                        case "BM73A":
                        case "BFM73A":
                            $trainType .= "Type 73 A";
                            break;
                        case "BM73B":
                        case "BFM73B":
                            $trainType .= "Type 73 B";
                            break;
                        case "BMA74":
                        case "BMB74":
                            $trainType .= "Type 74";
                            break;
                        case "BMA75":
                        case "BMB75":
                            $trainType .= "Type 75";
                            break;
                        case "BMA75-2":
                        case "BMB75-2":
                            $trainType .= "Type 75-2";
                            break;
                        case "BMA76":
                        case "BMB76":
                            $trainType .= "Type 76";
                            break;
                        case "BM92":
                        case "BS92":
                            $trainType .= "Type 92";
                            break;
                        case "BM93":
                        case "BCM93":
                            $trainType .= "Type 93";
                            break;
                        case "EL18":
                        case "EL 18":
                        case "Di4":
                            $trainType .= "Long distance train";
                            break;
                    }
                }
                $thisDeparture["trainType"] = $trainType;
            }
            // $thisDeparture["trainType"] = $trainType;
            // if ($thisDeparture["aimedDepartureTime"] == "") {
            //     $thisDeparture["sortTime"] = $thisDeparture["aimedArrivalTime"];
            // }

            $departures[] = $thisDeparture;
        }
        return $departures;
    }
}
