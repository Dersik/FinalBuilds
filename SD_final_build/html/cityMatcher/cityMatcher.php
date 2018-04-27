<?php
include "../common/databaseConnection.php";

$query = "SELECT `id`,`latitude`,`longitude` FROM `events` WHERE `cityName` is Null LIMIT 100";
$events = mysqli_query($connection, $query);

while($event = mysqli_fetch_array($events)){
  $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_USERAGENT, $agent);
  curl_setopt($ch, CURLOPT_URL, "nominatim.openstreetmap.org/reverse?format=json&lat=" . $event['latitude'] . "&lon=" . $event['longitude'] . "&zoom=18&addressdetails=1");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $city = curl_exec($ch);
  curl_close($ch);
  $city = json_decode($city, true);
  
  if(isset($city['address']['city'])){
    $cityName = $city['address']['city'];
  }
  if(isset($city['address']['hamlet'])){
    $cityName = $city['address']['hamlet'];
  }
  if(isset($city['address']['town'])){
    $cityName = $city['address']['town'];
  }
  if(isset($city['address']['village'])){
    $cityName = $city['address']['village'];
  }
  
  $query = "UPDATE `events` SET `cityName`= \"" . $cityName . "\", `stateName`=\"" . $city['address']['state'] . "\" WHERE `id` = " . $event['id'];
  mysqli_query($connection, $query);
}
?>
