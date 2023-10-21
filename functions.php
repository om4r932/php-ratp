<?php
    function timeRemaining($utcDate){
        $utc = new DateTime($utcDate, new DateTimeZone('UTC'));
        $now = new DateTime("now", new DateTimeZone("Europe/Paris"));
        $intervalle = $utc->diff($now);
        return $intervalle->format("%I min");
    }

    function get_type(){
        $types = ["BUS" => "bus", "Métro" => "metro", "Trains" => "rail", "Tramway" => "tram"];
        foreach($types as $print => $val){
            if(isset($_POST['type']) && $_POST['type'] == $val){
                echo "<option selected value='$val'>$print</option>";
            } else {
            echo "<option value='$val'>$print</option>";
            }
        }
    }

    function get_lines($mode){
        $lines = json_decode(file_get_contents("data/lines.json"), true);
        ksort($lines[$mode]);
        foreach($lines[$mode] as $lineName => $lineId){
            if(isset($_POST['line']) && $_POST['line'] == $lineId){
                echo "<option selected value='$lineId'>$lineName</option>";
            } else {
            echo "<option value='$lineId'>$lineName</option>";
            }
        }
    }

    function get_stops($lineId){
        $stops = json_decode(file_get_contents("data/stops.json"), true);
        if(array_key_exists($lineId, $stops)){
            $cur = $stops[$lineId];
            ksort($cur);
            foreach($cur as $stopName => $stopIdList){
                $val = implode("_", $stopIdList);
                if(isset($_POST['stop']) && $_POST['stop'] == $val){
                    echo "<option selected value='$val'>$stopName</option>";
                } else {
                    echo "<option value='$val'>$stopName</option>";
                }
            }
        }
    }

    function curl_get_horaire($ref, $lineId){
        $tokens = json_decode(file_get_contents("tokens.json"), true);
        $url = "https://prim.iledefrance-mobilites.fr/marketplace/stop-monitoring?MonitoringRef=$ref&LineRef=$lineId";
        $request = curl_init($url);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($request, CURLOPT_HTTPHEADER, [
            "Accept: application/json",
            "apiKey: " . $tokens['ratp']
        ]);
        $response = json_decode(curl_exec($request), true);
        curl_close($request);
        
        $general = $response['Siri']['ServiceDelivery']['StopMonitoringDelivery'][0]['MonitoredStopVisit'];
        if(count($general) == 0){
            echo "<p>Infos indispo.</p>";
        } else {
            $para = [];
            foreach($general as $i => $visits){
                $visit = $visits['MonitoredVehicleJourney'];
                $p = "[" . (isset($visit["OperatorRef"]) && array_key_exists("value", $visit['OperatorRef']) && !str_contains($visit['OperatorRef']['value'], "SNCF") ? explode(":", explode(".", $visit['OperatorRef']['value'])[2])[0] : (isset($visit["JourneyNote"][0]['value']) ? $visit['JourneyNote'][0]['value'] : "")) . "]" . $visit['MonitoredCall']['StopPointName'][0]['value'] . "(" . (isset($visit["DirectionName"][0]["value"]) ? $visit['DirectionName'][0]['value'] : $visit['DestinationName'][0]['value']). ")";
                !array_key_exists($p, $para) ? $para[$p] = [] : $para[$p][] = timeRemaining((isset($visit['MonitoredCall']['ExpectedDepartureTime']) ? $visit['MonitoredCall']['ExpectedDepartureTime'] : $visit['MonitoredCall']['ExpectedArrivalTime']));
            }
            foreach($para as $details => $times){
                echo "<p>" . $details . implode(" | ", $times) . "</p>";
            }
        }


    }

    function get_horaires($refs, $lineId){
        $refLists = explode("_", $refs);
        foreach($refLists as $i => $ref){
            curl_get_horaire("STIF:StopPoint:Q:$ref:", "STIF:Line::$lineId:");
        }
    }

?>