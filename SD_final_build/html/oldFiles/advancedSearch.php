 
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


<form method="post" action="#">
<input type="text" class="form-control"  name="search">

<select name="Type">
    <option value="1">Select One</option>
    <option value="2">Contains</option>
    <option value="3">Does not contain</option>
 </select> <br />
<input type="checkbox" name="check_list[]" value="name"><label>Name</label><br/>
<input type="checkbox" name="check_list[]" value="description"><label>Description</label><br/>
<input type="checkbox" name="check_list[]" value="date"><label>Date</label><br/>
<input type="checkbox" name="check_list[]" value="locationName"><label>Location</label><br/>
<input type="checkbox" name="check_list[]" value="cityName"><label>City</label><br/>
<input type="checkbox" name="check_list[]" value="stateName"><label>State</label><br/>

<span class="input-group-btn">
<button type="submit" name="submit" value="Submit" class="btn btn-primary btn-block"> Search </button>
</span>

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
if(isset($_POST["search"])){
	
$searchText = $_POST['search'];
$searchText = strtolower($searchText);
$queryCon = "SELECT * FROM events WHERE";
$sMethod = 0;

$sMethod = $_POST['Type'];



//set up for not in settings
if($sMethod == 3){
	$queryCon .= " NOT (";
}
$aCheck = $_POST['check_list'];
if(empty($aCheck)){
	echo ("No checks   ");
}else{
// Loop to store and display values of individual checked checkbox.
	$num = count($aCheck);
	for($i = 0; $i < ($num-1); $i++){
		$queryCon .= " lower(". $aCheck[$i] .") LIKE '%". $searchText. "%' OR";
		
	}
	//account for final or
	$queryCon .= " lower(". $aCheck[$i] .") LIKE '%". $searchText. "%'";

}
if($sMethod == 3){
	$queryCon .= ")";
}
echo ($queryCon);
$sql = $queryCon;
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
      </tr>
	  </thead>
	  <tbody>";
    while($row = $result->fetch_assoc()) {
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