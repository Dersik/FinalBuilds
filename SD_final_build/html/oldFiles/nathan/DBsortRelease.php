<!DOCTYPE html>
<html>
<head>


<style>

table,th,td{
	border-radius: 4px;
	border: 2px solid black;
	background-color: #f4b5e5;
	padding: 3px;
	
}
table:hover,th:hover,td:hover{
	background-color: white;
	opacity: 50%;
}
a{
	padding:3px 15px;
}
</style>
</head>
<body>

 <?php
$servername = "localhost";
$username = "devteam";
$password = "=7cessPit2";
$dbname = "nathanDatabase";



// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed1: " . $conn->connect_error);
}

$searchText = $_GET['search'];
echo'
<p>
<a href="nmcgarveyDBproject.php?search=' . $searchText. '"> Home/Default Page </a>
</p>';

echo '<a href="DBsortAName.php?search=' . $searchText. '"> Actor Sort </a> 
 <a href="DBsortADOB.php?search=' . $searchText. '"> ActorDOB Sort </a>
 <a href="DBsortDName.php?search=' . $searchText. '"> Director Sort </a>
 <a href="DBsortDDOB.php?search=' . $searchText. '"> Director DOB Sort </a>
 <a href="DBsortTitle.php?search=' . $searchText. '"> Movie Sort </a>
 <a href="DBsortRelease.php?search=' . $searchText. '"> Movie Release Sort </a>
 <a href="DBsortRating.php?search=' . $searchText. '"> Rating Sort </a>
 <a href="DBsortGenre.php?search=' . $searchText. '"> Genre Sort </a>';
 
 echo '<a href="actorTable.php?search=' . $searchText. '"> Actor Table </a>
 <a href="directorTable.php?search=' . $searchText. '"> Director Table </a>
 <a href="movieTable.php?search=' . $searchText. '"> Movie Table </a>';
	$sql = "SELECT 
	Actor.Name AS ActorName,
	Actor.DOB AS ActorDOB,
	Director.Name AS DirectorName,
	Director.DOB AS DirectorDOB,
	Movie.TITLE AS Title,
	Movie.Date AS Date,
	Movie.Genre AS Genre,
	Movie.OpeningWeek AS OpeningWeek,
	Movie.Rating AS Rating
	FROM Relations,Movie,Actor,Director
	
	WHERE Movie.TITLE = Relations.MovieTitle AND Actor.ID = Relations.ActorID AND Director.ID = Relations.DirectorID
	AND (lower(Actor.Name) LIKE '%".$searchText."%' OR 
	lower(Director.Name) LIKE '%".$searchText."%' OR lower(Movie.TITLE) LIKE '%".$searchText."%' OR
	 lower(Movie.Genre) LIKE '%".$searchText."%' OR lower(Movie.Rating) LIKE '%".$searchText."%' OR
	 lower(Movie.Date) LIKE '%".$searchText."%') ORDER BY Movie.Date
	";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    // output data of each row
	echo "<table id = tableID>";
    while($row = $result->fetch_assoc()) {
			echo "<tr> 
			<td>" . "Actor: ". $row['ActorName'] . "</td>
			<td>" . "Actor DOB: ". $row['ActorDOB'] . "</td>
			<td>" . "Title: ". $row['Title'] . "</td>
			<td>" . "Date: " . $row['Date'] . "</td>
			<td>" . "Rating: " . $row['Rating'] . "</td>
			<td>" . "Genre: " . $row['Genre'] . "</td>
			<td>" . "Opening Week: " . $row['OpeningWeek'] . "</td>
			<td>" . "Director: " . $row['DirectorName'] . "</td>
			<td>" . "Director DOB: " . $row['DirectorDOB'] . "</td>
			</tr>";
    }
	echo "</table>";
} else {
    echo "0 results";
}


$conn->close();
?> 

</body>
</html>