<code><?php
        // header("Content-Type: application/json");
        $pageRequiresTrainManager = true;
        include "./config/connect.php";
        include "./config/session.php";

        $url = "https://siri.opm.jbv.no/jbv/et/EstimatedTimetable.xml";

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

        foreach ($array_data->ServiceDelivery->EstimatedTimetableDelivery->EstimatedJourneyVersionFrame->children() as $row) {
            $journeyID = strval($row->VehicleRef);

            $check_query = "SELECT train_number FROM routes WHERE train_number = '$journeyID' AND route_status = 1 LIMIT 1";
            $check = $databaseConnection->query($check_query);
            $check = mysqli_num_rows($check);
            if ($check == 0 && $journeyID != null) {
                $departures["'" . $journeyID . "'"] =  str_pad($journeyID, 5, "0", STR_PAD_LEFT) . "";
                $departures["'" . $journeyID . "'"] .= '<td>' . $journeyID . "</td>";
                $departures["'" . $journeyID . "'"] .= '<td>' . $row->OperatorRef . "</td>";
                $departures["'" . $journeyID . "'"] .= '<td>' . $row->LineRef . "</td>";
                $departures["'" . $journeyID . "'"] .= '<td><a href="./trainList.php?page=' . floor((int)$journeyID / 1000) . '#tn_' . $journeyID . '">Go to page</a></td>';
                $departures["'" . $journeyID . "'"] .= '<td><a href=".//viewTrain.php?train=' . $journeyID . '">Train page</a></td></tr>';
            }
        }

        sort($departures);
        $newTrains = "    <table>
        <tr>
            <th colspan='5'>New trains in estimated</th>
        </tr>
        <tr>
            <th>Train Number</th>
            <th>Operator</th>
            <th>Line</th>
            <th>List page</th>
            <th>Train page</th>
        </tr>";
        foreach ($departures as $departure) {
            // echo substr($departure, 5) . "<br/>";
            $newTrains .= substr($departure, 5);
        }
        echo $newTrains;

// echo json_encode($departures);
