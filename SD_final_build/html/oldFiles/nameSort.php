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

$sql = "SELECT * FROM events_demo ORDER BY name";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
	echo "<table>";
    while($row = $result->fetch_assoc()) {
		
        echo "<tr> 
		<td>" . "ID: ". $row['id'] . "</td>
		<td>" . "Name: " . $row['name'] . "</td>
		<td>" . "Locations: " . $row['location'] . "</td>
		<td>" . "Start: " . $row['startTime'] . "</td>
		<td>" . "Description" . $row['description'] . "</td>
		</tr>";
		
    }
	echo "</table>";
} else {
    echo "0 results";
}
$conn->close();
?> 