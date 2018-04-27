<?php
include "../common/databaseConnection.php";

$query = "DELETE FROM `events` WHERE `date` < " . time();
mysqli_query($connection, $query);

$query = "DELETE FROM `events` WHERE `latitude` = \"\" OR `longitude` = \"\"";
mysqli_query($connection, $query)
?>
