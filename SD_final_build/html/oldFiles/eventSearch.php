 
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="html/SiteTesting/css/bootstrap.min.css">
  <link href="BSCSS.css" type="text/css" rel="stylesheet">
  <script type="text/javascript" src="../SiteTesting/js/jquery-3.2.1.min.js"></script> 
  <script src="../SiteTesting/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../tablesort/jquery.tablesorter.js"></script>
<link href="../blue/style.css" rel="stylesheet" type="text/css" />
	<script type = "text/javascript">
$(document).ready(function() 
    { 
        $("table").tablesorter({ 
		headers:{
			2: {sorter:'customDate'}
}
}); 
    }); 
    	
    	
</script>
	</head>
<body>


<form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<input type="text" class="form-control"  name="search">
<span class="input-group-btn">
<button type="submit" name="submit" value="Submit" class="btn btn-primary btn-block"> Search </button>
</span>
</form>

 <?php
$servername = "localhost";
$username = "devteam";
$password = "=7cessPit2";
$dbname = "eventFinder";

$user_ip = getenv('REMOTE_ADDR');
$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
$lat1 = $geo["geoplugin_latitude"];
$lon1 = $geo["geoplugin_longitude"];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed1: " . $conn->connect_error);
}
if(isset($_GET["search"])){
$searchText = filter_input(INPUT_GET,"search");
$searchText = strtolower($searchText);

$sql = "SELECT * FROM events WHERE lower(name) LIKE '%".$searchText."%' OR 
lower(description) LIKE '%".$searchText."%' OR lower(locationName) LIKE '%".$searchText."%' 
OR lower(cityName) LIKE '%".$searchText."%' ";

$result = $conn->query($sql);


if ($result->num_rows > 0) {
    // output data of each row
	echo "
	<table class = 'table-hover table-bordered tablesorter'>
	<thead>
	<tr>
		<th>ID</th>
        <th>Name</th>
        <th>Date</th>
		<th>Location</th>
		<th>City</th>
		<th>State</th>
		<th>Distance (Miles)</th>
      </tr>
	  </thead>
	  <tbody>";
    while($row = $result->fetch_assoc()) {
		$lat2 = $row['latitude'];
		$lon2 = $row['longitude'];
		 $theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;

		$id = $row['id'];
		$name = $row['name'];
        echo "<tr> 
		<td>" . "ID: " . $row['id'] . "</td>
		<td>" . '<a href="event.php?id=' . $id. '">' 
		. $name . '</a>' . "</td>
		<td>"  . $row['date'] . "</td>
		<td>" . "Location: " . $row['locationName'] . "</td>
		<td>" . "City: " . $row['cityName'] . "</td>
		<td>" . "State: " . $row['stateName'] . "</td>
		<td>" . round($miles) . "</td>
		
		</tr>";
    }
	echo"
	</tbody>
	</table>
	";
	echo "</table>";
} else {
    echo "0 results";
}
}
$conn->close();
?> 

</body>
</html>