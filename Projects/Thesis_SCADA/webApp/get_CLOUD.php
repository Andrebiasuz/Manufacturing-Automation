<?php

$data_sort = [];

//GET token
$url = "https://cloud.wago.com/api/token";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   "Accept: application/json",
   "Content-Type: application/x-www-form-urlencoded",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$data = "grant_type=password&username=andre.biasuz@wago.com&password=OMITTED";

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
$resp_json = json_decode($resp);
$access_token = $resp_json->access_token;
//echo($access_token);
$acess_token_type = $resp_json->token_type;
curl_close($curl);

// GET VALUES WITH TELEMETRY FUNCTION OF REST_API.
// We are getting the latest value, hence the "latest" query in the url.
$url_telemetry = 'https://cloud.wago.com/api/telemetry/telemetrydata/latest?queryAmount=1&api-version=1.0';

// Initiates CURL with telemetry url
$curl_telemetry = curl_init($url_telemetry);
curl_setopt($curl_telemetry, CURLOPT_URL, $url_telemetry);
curl_setopt($curl_telemetry, CURLOPT_POST, true);
curl_setopt($curl_telemetry, CURLOPT_RETURNTRANSFER, true);

// Authorization, same as post_CLOUD.php
$auto = "Authorization: ".$acess_token_type." ".$access_token;
$headers_telemetry = array($auto,"Content-Type: application/json","api-key: 3137cb165561463aa262dfbceea18a1d");
curl_setopt($curl_telemetry, CURLOPT_HTTPHEADER, $headers_telemetry);

// Variables
$tagKeySlave1 = "KNX_Temperature";
$tagKeySlave2 = "KNX_Temperature_Set_point";
$tagKeySlave3 = "Output_Room";
$tagKeySlave4 = "Set_point_heater";
$tagKeySlave5 = "Output_boiler";
$tagKeySlave6 = "Output_External_Walls";
$tagKeySlave7 = "BTN_Heater";

$tagKeyMaster1 = "OUT_Outer_tank_supply_input_pump";
$tagKeyMaster2 = "OUT_Outer_tank_water_output_pump";
$tagKeyMaster3 = "Tank_level";
$tagKeyMaster4 = "Status_Supply_input_pump_BTN";
$tagKeyMaster5 = "Status_Water_Output_Pump_BTN";

$masterPlcDeviceID= "a06660ba-187b-48f4-a332-04f5b08cb512";
$slavePlcDeviceID= "e65c4d50-ef32-4b51-be30-75d507705069";


$data_telemetry = array(
              array(
                            "deviceId"=> $slavePlcDeviceID,
                            "collectionKey" => "1",
                            "tagKey" => $tagKeySlave1
              ),
              array(
                            "deviceId"=> $slavePlcDeviceID,
                            "collectionKey" => "1",
                            "tagKey" => $tagKeySlave2
              ),
              array(
                            "deviceId"=> $slavePlcDeviceID,
                            "collectionKey" => "1",
                            "tagKey" => $tagKeySlave3
              ),
              array(
                            "deviceId"=> $slavePlcDeviceID,
                            "collectionKey" => "1",
                            "tagKey" => $tagKeySlave4
              ),array(
                            "deviceId"=> $slavePlcDeviceID,
                            "collectionKey" => "1",
                            "tagKey" => $tagKeySlave5
              ),
              array(
                            "deviceId"=> $slavePlcDeviceID,
                            "collectionKey" => "1",
                            "tagKey" => $tagKeySlave6
              ),
              array(
                            "deviceId"=> $slavePlcDeviceID,
                            "collectionKey" => "1",
                            "tagKey" => $tagKeySlave7
              ),
              array(
                            "deviceId"=> $masterPlcDeviceID,
                            "collectionKey" => "1",
                            "tagKey" => $tagKeyMaster1),
              array(
                            "deviceId"=> $masterPlcDeviceID,
                            "collectionKey" => "1",
                            "tagKey" => $tagKeyMaster2),
              array(
                            "deviceId"=> $masterPlcDeviceID,
                            "collectionKey" => "1",
                            "tagKey" => $tagKeyMaster3),
              array(
                            "deviceId"=> $masterPlcDeviceID,
                            "collectionKey" => "1",
                            "tagKey" => $tagKeyMaster4),
              array(
                            "deviceId"=> $masterPlcDeviceID,
                            "collectionKey" => "1",
                          "tagKey" => $tagKeyMaster5))
              ;


$data_telemetry_json = json_encode($data_telemetry);
curl_setopt($curl_telemetry, CURLOPT_POSTFIELDS, $data_telemetry_json);

curl_setopt($curl_telemetry, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl_telemetry, CURLOPT_SSL_VERIFYPEER, false);

$resp_telemetry_json = curl_exec($curl_telemetry);

$resp_telemetry_array = json_decode($resp_telemetry_json , true); // true decodes as array

if  ((is_null($resp_telemetry_array['values'][0][3]))) {
    for($x = 0; $x <= 1; $x++) {
      array_push($data_sort, $resp_telemetry_array['values'][$x]);
    }
} else {
      for ($y = 1; $y >= 0; $y--) {
        array_push($data_sort, $resp_telemetry_array['values'][$y]);
      }
    }

$data_sort_json = json_encode($data_sort);
echo($data_sort_json);
curl_close($curl_telemetry);




//$access_token = strval($access_token);

// GET TELEMETRY DATA

/* IF TELEMETRY (GRAPHS IN CHART.JS - ADVANCED FEATURE)
// GET time
function getTimeNow() {
    /// RETURNS TIME NOW IN ISO FORMAT
    date_default_timezone_set("Europe/Budapest");
    $today = date("Y-m-d\TH:i:s");
    return($today);
}
function formatTimeBefore($beforeDate,$beforeTime) {
    // GETS A TIME AND DATE AND RETURNS IN ISO FORMAT FOR TELEMETRY
    $formattedTime = $beforeDate . "T" . $beforeTime ;
    return $formattedTime;
}
function getTelemetryTimeFromNow($x,$y){
    $timeArray = [formatTimeBefore($x,$y),getTimeNow()];
    return $timeArray;
}
[$startTime,$endTime] = getTelemetryTimeFromNow('2021-10-21' , '10:18:35');
$url_telemetry = "https://cloud.wago.com/api/telemetry/telemetrydata/raw?" . "StartTime=" . $startTime . '&' . "EndTime=" . $endTime . '&api-version=1.0';
*/

?>
