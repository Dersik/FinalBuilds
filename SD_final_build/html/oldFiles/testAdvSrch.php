 
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
</script>
	</head>
<body>

<form name="frmSearch" method="post" action="index.php">
	<input type="hidden" id="advance_search_submit" name="advance_search_submit" value="<?php echo $advance_search_submit; ?>">
	<div class="search-box">
		<label class="search-label">With Any One of the Words:</label>
		<div>
			<input type="text" name="search[with_any_one_of]" class="demoInputBox" value="<?php echo $with_any_one_of; ?>"	/>
			
		</div>				
			<label class="search-label">With the Exact String:</label>
			<div>
				<input type="text" name="search[with_the_exact_of]" id="with_the_exact_of" class="demoInputBox" value="<?php echo $with_the_exact_of; ?>"	/>
			</div>
			<label class="search-label">Without:</label>
			<div>
				<input type="text" name="search[without]" id="without" class="demoInputBox" value="<?php echo $without; ?>"	/>
			</div>
			<label class="search-label">Starts With:</label>
			<div>
				<input type="text" name="search[starts_with]" id="starts_with" class="demoInputBox" value="<?php echo $starts_with; ?>"	/>
			</div>
			<label class="search-label">Search Keywords in:</label>
			<div>
				<select name="search[search_in]" id="search_in" class="demoInputBox">
					<option value="">Select Column</option>
					<option value="title" <?php if($search_in=="title") { echo "selected"; } ?>>Title</option>
					<option value="description" <?php if($search_in=="description") { echo "selected"; } ?>>Description</option>
				</select>
			</div>
		</div>
		
		<div>
			<input type="submit" name="go" class="btnSearch" value="Search">
		</div>
	</div>
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
	
	$with_any_one_of = "";
	$with_the_exact_of = "";
	$without = "";
	$starts_with = "";
	$search_in = "";
	$advance_search_submit = "";
	
	$queryCondition = "";
	if(!empty($_POST["search"])) {
		$advance_search_submit = $_POST["advance_search_submit"];
		foreach($_POST["search"] as $k=>$v){
			if(!empty($v)) {

				$queryCases = array("with_any_one_of","with_the_exact_of","without","starts_with");
				if(in_array($k,$queryCases)) {
					if(!empty($queryCondition)) {
						$queryCondition .= " AND ";
					} else {
						$queryCondition .= " WHERE ";
					}
				}
				switch($k) {
					case "with_any_one_of":
						$with_any_one_of = $v;
						$wordsAry = explode(" ", $v);
						$wordsCount = count($wordsAry);
						for($i=0;$i<$wordsCount;$i++) {
							if(!empty($_POST["search"]["search_in"])) {
								$queryCondition .= $_POST["search"]["search_in"] . " LIKE '%" . $wordsAry[$i] . "%'";
							} else {
								$queryCondition .= "title LIKE '" . $wordsAry[$i] . "%' OR description LIKE '" . $wordsAry[$i] . "%'";
							}
							if($i!=$wordsCount-1) {
								$queryCondition .= " OR ";
							}
						}
						break;
					case "with_the_exact_of":
						$with_the_exact_of = $v;
						if(!empty($_POST["search"]["search_in"])) {
							$queryCondition .= $_POST["search"]["search_in"] . " LIKE '%" . $v . "%'";
						} else {
							$queryCondition .= "title LIKE '%" . $v . "%' OR description LIKE '%" . $v . "%'";
						}
						break;
					case "without":
						$without = $v;
						if(!empty($_POST["search"]["search_in"])) {
							$queryCondition .= $_POST["search"]["search_in"] . " NOT LIKE '%" . $v . "%'";
						} else {
							$queryCondition .= "title NOT LIKE '%" . $v . "%' AND description NOT LIKE '%" . $v . "%'";
						}
						break;
					case "starts_with":
						$starts_with = $v;
						if(!empty($_POST["search"]["search_in"])) {
							$queryCondition .= $_POST["search"]["search_in"] . " LIKE '" . $v . "%'";
						} else {
							$queryCondition .= "title LIKE '" . $v . "%' OR description LIKE '" . $v . "%'";
						}
						break;
					case "search_in":
						$search_in = $_POST["search"]["search_in"];
						break;
				}
			}
		}
	}
	$orderby = " ORDER BY id desc"; 
	$sql = "SELECT * FROM events " . $queryCondition;
	$result = mysqli_query($conn,$sql);
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

$conn->close();
?>

</body>
</html>