<?php

//GET token
$cp = [];

$url = "https://cloud.wago.com/api/token"; // destination of request
$curl = curl_init($url);                   // Initiation of CURL (Complex URL)

// Estabilish Communication with URL
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// Make header as example
$headers = array(
   "Accept: application/json",
   "Content-Type: application/x-www-form-urlencoded",
);

// Send header as Header in CURL config
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

// Authentication phase , $DATA with ommited password and username
$data = "grant_type=password&username=OMITTED&password=OMITTED";

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

// gets token
$resp = curl_exec($curl);
$resp_json = json_decode($resp); // decodes JSON response
// assign to given variables
$access_token = $resp_json->access_token;
$acess_token_type = $resp_json->token_type;
curl_close($curl); // closes CURL method

// SEND Command
$url_device = "https://cloud.wago.com/API/v1/devices/e65c4d50-ef32-4b51-be30-75d507705069/commands";
// https://cloud.wago.com/api/deviceapp/devices/d4ff4c99-338b-4408-a87e-d4e89b48719e/commands/1?api-version=1.0

$curl_device = curl_init($url_device);
curl_setopt($curl_device, CURLOPT_URL, $url_device);
curl_setopt($curl_device, CURLOPT_POST, true);
curl_setopt($curl_device, CURLOPT_RETURNTRANSFER, true);

$auto = "Authorization: ".$acess_token_type." ".$access_token;
  $headers_device = array($auto,"Content-Type: application/json","api-key: 3137cb165561463aa262dfbceea18a1d");
curl_setopt($curl_device, CURLOPT_HTTPHEADER, $headers_device);

$jsonString = file_get_contents("php://input");
$phpObject = json_decode($jsonString);

  foreach($phpObject as $x => $x_value) {
      array_push($cp, array("Name" => $x, "Value"=> $x_value));
}

$data_send = array("CommandId"=>1,"CommandTimeout"=>6000,"CommandParameters"=> $cp);

$data_send_json = json_encode($data_send);
curl_setopt($curl_device, CURLOPT_POSTFIELDS, $data_send_json);
echo($data_send_json);

//for debug only!
curl_setopt($curl_device, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl_device, CURLOPT_SSL_VERIFYPEER, false);

$resp_send = curl_exec($curl_device);
$resp_send_json = json_encode($resp_send);
echo($resp_send_json);
curl_close($curl_device);
?>
