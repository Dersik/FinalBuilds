<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="html/SiteTesting/css/bootstrap.min.css">
  <link href="BSCSS.css" type="text/css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="html/SiteTesting/js/bootstrap.min.js"></script>
	
</head>
<body>


<?php
$servername = "localhost";
$username = "devteam";
$password = "=7cessPit2";
$dbname = "eventFinder";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed1: " . $conn->connect_error);
}

$id = $_GET['id'];

$sql = "SELECT * FROM events WHERE id = $id ";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    // output data of each row
	echo"<div id = 'twid'>";
	echo "
	<table class = 'table'>
	<thead class='thead-dark'>
	<tr>
		<th>ID</th>
       		 <th>Name</th>
       		 <th>Date</th>
		<th>Location</th>
		<th>City</th>
		
		
      </tr>
	  </thead>
	  <tbody>";
	 echo"<div class = 'p-2'>";
    while($row = $result->fetch_assoc()) {
		$name = $row['name'];
		$description = $row['description'];
		$lat = $row['latitude']; //latitude and longitude used for the google map(see below)
		$lon = $row['longitude'];
        echo "<tr> 
		<td>" . "ID: " . $row['id'] . "</td>
		<td>"  . $name . "</td>
		<td>" . "Date: " . $row['date'] . "</td>
		<td>" . "Location: " . $row['locationName'] . "</td>
		<td>" . "City: " . $row['cityName'] . "</td>
		</tr>";
    }
	echo"
	</tbody>
	</table>
	";
	
	echo "</table>";
	echo"</div>";
	echo"<div id = 'des'>";
	echo"<p>" . $description . "</p>";
	echo"</div>";
} else {
    echo "0 results";
}

?> 

	<style>
       #map {
        height: 400px;
        width: 100%;
       }
    </style>
	
	<script>
      function initMap() {
		  
		var latitude = <?php echo $lat; ?>;
		var longitude = <?php echo $lon; ?>;
		  
        var uluru = {lat: latitude, lng: longitude};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 16,
          center: uluru
        });
        var marker = new google.maps.Marker({
          position: uluru,
          map: map
        });
      }
    </script>

	<script async defer
		src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAhsFD5sKaCgRvUlsCkP7xFuWsoac584Ho&callback=initMap">
	</script>
	
<?php 

$conn->close();

?>

</body>
</html>
