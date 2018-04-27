<?php
include "../common/databaseConnection.php";

$oauth_key = "MM64VZWCMF6HOKO5NU34";

//pull file configuration from database
$query = "SELECT `col1` FROM `configuration` WHERE `name` = \"eventbriteDownload\"";
$page = mysqli_fetch_array(mysqli_query($connection, $query))[0]+1;

$end = $page + 20;
if($end >= 200){
  //reset page value to the start
  $page = 1;
  $end = 21;
  
  //remove all previous eventbrite events, prevents duplicates
  //$query = "DELETE FROM `events` WHERE `source` = \"eventbrite\"";
  //mysqli_query($connection, $query);
}


for($page; $page <= $end; $page++){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://www.eventbriteapi.com/v3/events/search/?include_unavailable_events=True&page=$page");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $oauth_key"));
  $results = curl_exec($ch);
  curl_close($ch);
  $results = json_decode($results, true);
  
  foreach($results['events'] as $result){
    if(strcmp($result['locale'], "en_US") == 0){
      //get venue information
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "https://www.eventbriteapi.com/v3/venues/" . $result['venue_id'] . "/");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $oauth_key"));
      $venue = curl_exec($ch);
      curl_close($ch);
      $venue = json_decode($venue, true);
      
      //write to database
      $columns = "`name`,`date`,`locationName`,`latitude`,`longitude`,`description`,`imageLink`,`externalLink`,`source`";
      $values = "\"" . $result['name']['text'] . "\",\"" . strtotime($result['start']['local']) . "\",\"" . $venue['name'] . "\",\"" . $venue['latitude'] . "\",\"" . $venue['longitude'] . "\",\"" . $result['description']['text'] . "\",\"" . $result['logo']['url'] . "\",\"" . $result['url'] . "\",\"eventbrite\"";
      $query = "INSERT INTO `events` ($columns) values ($values)";
      
      echo $query . "<br />";
      mysqli_query($connection, $query);
    }
  }
}
//write the leave off point to file configuration in database
$query = "UPDATE `configuration` SET `col1` = $end WHERE `name` = \"eventbriteDownload\"";
mysqli_query($connection, $query);
?>
