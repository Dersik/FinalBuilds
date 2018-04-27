
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="html/SiteTesting/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="html/SiteTesting/js/bootstrap.min.js">
 </script>
<script>

$(document).ready(function(){
    $("#0").click(function(){
		var data = 0;
		$.ajax({ url: 'completeEvent.php',
         data: 0,
         type: 'GET',
         success:  {}
		});
    });
	$("#1").click(function(){
		
		var data = 1;
        $.post('completeEvent.php', data);
    });
	$("#2").click(function(){
		
		var data = 2;
        $.post('completeEvent.php', data);
    });
	$("#3").click(function(){
		
		var data = 3;
        $.ajax({ url: 'completeEvent.php',
         data: '3',
         type: 'GET',
         success:  {}
		});
    });
	$("#4").click(function(){
		
		var data = 4;
        $.post('completeEvent.php',data);
    });
	$("#5").click(function(){
		
		var data =5;
        $.post('completeEvent.php', data);
    });
});


</script>

<style>

table,th,td{
	border-radius: 4px;
	border: 2px solid black;
	background-color: #ffe6e6;
	padding: 5px;
	
}
table:hover,th:hover,td:hover{
	border-radius: 4px;
	border: 2px solid black;
	background-color: #ffffff;
	padding: 7px;
	
}


</style>
</head>
<body>
<button id = "0" type="button" name = "0" >0</button>
<button id = "1" type="button" name = "1" >1</button>
<button id = "2" type="button" name = "2" >2</button>
<button id = "3" type="button" name = "3" >3</button>
<button id = "4" type="button" name = "4" >4</button>
<button id = "5" type="button" name = "5" >5</button>

<!--search bar -->
<form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
Search: <input type="text" name="search">
<input type="submit" name="submit" value="Submit">
</form> 
 
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
if(isset($_GET["search"])){
$searchText = filter_input(INPUT_GET,"search");
$searchText = strtolower($searchText);


$data = $_GET['data'];
echo "data = " . $data;

//if nsort is pushed then do search and sort by name
switch($data){
case 0:
	$sql = "SELECT * FROM events_demo WHERE lower(name) LIKE '%".$searchText."%' OR 
	lower(description) LIKE '%".$searchText."%' OR lower(location) LIKE '%".$searchText."%' 
	ORDER BY name ";
	break;
//if tsort is pushed then do search and sort by time
case 1:
	$sql = "SELECT * FROM events_demo WHERE lower(name) LIKE '%".$searchText."%' OR 
	lower(description) LIKE '%".$searchText."%' OR lower(location) LIKE '%".$searchText."%'
	ORDER BY startTime";
	break;
	
//if lsort is pushed then do search and sort by location
case 2:
	$sql = "SELECT * FROM events_demo WHERE lower(name) LIKE '%".$searchText."%' OR 
	lower(description) LIKE '%".$searchText."%' OR lower(location) LIKE '%".$searchText."%' 
	ORDER BY location";
	//sort reverse name
case 3:
	$sql = "SELECT * FROM events_demo WHERE lower(name) LIKE '%".$searchText."%' OR 
	lower(description) LIKE '%".$searchText."%' OR lower(location) LIKE '%".$searchText."%' 
	ORDER BY name DESC";
	break;
//sort reverse time
case 4:
	$sql = "SELECT * FROM events_demo WHERE lower(name) LIKE '%".$searchText."%' OR 
	lower(description) LIKE '%".$searchText."%' OR lower(location) LIKE '%".$searchText."%'
	ORDER BY startTime DESC";
	break;
//sort reverse location
case 5:
	$sql = "SELECT * FROM events_demo WHERE lower(name) LIKE '%".$searchText."%' OR 
	lower(description) LIKE '%".$searchText."%' OR lower(location) LIKE '%".$searchText."%' 
	ORDER BY location DESC";
	break;
}



/*else{

//sql of searching all but time
$sql = "SELECT * FROM events_demo WHERE lower(name) LIKE '%".$searchText."%' OR 
lower(description) LIKE '%".$searchText."%' OR lower(location) LIKE '%".$searchText."%' ";
}
*/
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    // output data of each row
	echo "<table id = tableID>";
    while($row = $result->fetch_assoc()) {
	
        echo "<tr> 
		<td>" . "ID: ". $row['id'] . "</td>
		<td>" . "Name: " . $row['name'] . "</td>
		<td>" . "Locations: " . $row['location'] . "</td>
		<td>" . "Start: " . $row['startTime'] . "</td>
		<td>" . "Description: " . $row['description'] . "</td>
		</tr>";
		
    }
	echo "</table>";
} else {
    echo "0 results";
}
}

$conn->close();
?> 

</body>
</html>