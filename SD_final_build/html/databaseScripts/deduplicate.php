<?php
include "../common/databaseConnection.php";

$query = "SELECT `name`, count(*) as num FROM `events` GROUP BY `name`";
$eventsRS = mysqli_query($connection, $query);

while($row = mysqli_fetch_array($eventsRS)){
  if($row['num'] > 1){
    $query = "SELECT `id` FROM `events` WHERE `name` LIKE \"%" . $row['name'] . "%\"";
    $duplicateRS = mysqli_query($connection, $query);
    
    $x = 1;
    while($event = mysqli_fetch_array($duplicateRS)){
      if($x > 1){
        $query = "DELETE FROM `events` WHERE `id` = " . $event['id'];
        mysqli_query($connection, $query);
      }
      $x = 2;
    }
  }
}
?>
