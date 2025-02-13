<?php

//GET token
$cp = [];

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

$data = "grant_type=password&username=andre.biasuz@wago.com&password=@Cramento123";

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
$resp_json = json_decode($resp);
$access_token = $resp_json->access_token;
//echo($access_token);
$acess_token_type = $resp_json->token_type;
curl_close($curl);
$access_token = strval($access_token);

// SEND Command

// Initiate CURL with the Device URL from WAGO CLOUD
$url_device = "https://cloud.wago.com/API/v1/devices/a06660ba-187b-48f4-a332-04f5b08cb512/commands";
$curl_device = curl_init($url_device);
curl_setopt($curl_device, CURLOPT_URL, $url_device);
curl_setopt($curl_device, CURLOPT_POST, true);
curl_setopt($curl_device, CURLOPT_RETURNTRANSFER, true);

// Authenticate the entry with token and api-key : MULTI FACTOR AUTHENTICATION
$auto = "Authorization: ".$acess_token_type." ".$access_token;
$headers_device = array($auto,"Content-Type: application/json","api-key: 3137cb165561463aa262dfbceea18a1d");
curl_setopt($curl_device, CURLOPT_HTTPHEADER, $headers_device);

// GETS CONTENT FROM AJAX IN MAIN.JS
$jsonString = file_get_contents("php://input");
$phpObject = json_decode($jsonString);

// convert phpObject into an associative array to formulate REST_API style array
foreach($phpObject as $x => $x_value) {
      array_push($cp, array("Name" => $x, "Value"=> $x_value));
}

// Sending all values to CommandId 1 with Timeout as suggested by Wago
$data_send = array("CommandId"=>1,"CommandTimeout"=>6000,"CommandParameters"=> $cp);

$data_send_json = json_encode($data_send); // encoding into JSON
curl_setopt($curl_device, CURLOPT_POSTFIELDS, $data_send_json); // Postfields
echo($data_send_json); // echoing Json to Wago cloud

//for debug only!
curl_setopt($curl_device, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl_device, CURLOPT_SSL_VERIFYPEER, false);

$resp_send = curl_exec($curl_device); // executes Device URL
$resp_send_json = json_encode($resp_send); // Json encodes
echo($resp_send_json); // echoes out for debugging
curl_close($curl_device); // closes Device URL
?>
