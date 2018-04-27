<?php
//include database connection
include $_SERVER['DOCUMENT_ROOT'] . "/common/databaseConnection.php";

//get user coordinates
$user_ip = getenv('REMOTE_ADDR');
$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
$userLatitude = $geo["geoplugin_latitude"];
$userLongitude = $geo["geoplugin_longitude"];

//constants
$limit = 20;

//build query if search is set
if(true){//find new indicator for valid search
  //get search text from url
  $searchText = filter_input(INPUT_GET,"search");
  $searchText = strtolower($searchText);
  
  //get search radius from url
  $radius = round(filter_input(INPUT_GET, "radius"));
  if($radius == 0){
    $radius = 3000;
  }
  
  //get page value from url
  $page = round(filter_input(INPUT_GET,"page"));
  
  //correct for page value being too low, not set, or not a number
  if(!is_numeric($page) || !$page || $page < 1){
    $page = 1;
  }
  
  //get total number of results for this search
  $query = "SELECT `id`,(69 * DEGREES(ACOS(COS(RADIANS(latitude)) * COS(RADIANS(" . $userLatitude . ")) *
             COS(RADIANS(longitude) - RADIANS(" . $userLongitude . ")) +
             SIN(RADIANS(latitude)) * SIN(RADIANS(" . $userLatitude . "))))) AS distance FROM `events` WHERE lower(`name`) LIKE \"%".$searchText."%\" OR lower(`description`) LIKE \"%".$searchText."%\" OR lower(`locationName`) LIKE \"%".$searchText."%\" OR lower(`cityName`) LIKE \"%".$searchText."%\" HAVING ". $radius . " >= distance";
  $totalResults = mysqli_num_rows(mysqli_query($connection, $query));
  
  //correct for page value being too high
  $highestPage = $totalResults / $limit;
  if($page > $highestPage){
    $page = ceil($highestPage);
  }
  
  //calculate offset
  $offset = ($page-1) * $limit;
  
  //get records for this search while accounting for limit and offset and search radius
  $query = "SELECT `id`, `name`, (69 * DEGREES(ACOS(COS(RADIANS(latitude)) * COS(RADIANS(" . $userLatitude . ")) *
             COS(RADIANS(longitude) - RADIANS(" . $userLongitude . ")) +
             SIN(RADIANS(latitude)) * SIN(RADIANS(" . $userLatitude . "))))) AS distance  FROM `events` WHERE lower(`name`) LIKE \"%".$searchText."%\" OR lower(`description`) LIKE \"%".$searchText."%\" OR lower(`locationName`) LIKE \"%".$searchText."%\" OR lower(`cityName`) LIKE \"%".$searchText."%\" HAVING ". $radius . " >= distance";
  
  if(isset($_GET['sort']) && !is_null($_GET['sort']) && !empty($_GET['sort'])){
    $sort = filter_input(INPUT_GET,"sort");
    $order = filter_input(INPUT_GET,"order");
    
    if(!isset($_GET['order'])){
      $order = "";
    }
    $query .= " ORDER BY `" . $sort . "`" . " " . $order;
  }
  
  $query .= " LIMIT " . $limit . " OFFSET " . $offset;
  
  $eventsRS = mysqli_query($connection, $query);
}
?>

<html>
  <head>
    <title>Event Finder - Events</title>
    <link href='https://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="Eventtile.css">
  </head>
  
  <body>
      <div class="header">
        <h1>EventFinder</h1>
        <form>
		  <input class="search-input" placeholder="Search for events..." type="search" >
	   </form>
      </div>
      
    <div>
      <?php
        if($page > 1){
          echo "<a href=\"eventsList.php?search=" . $searchText . "&page=" . ($page - 1) . "&sort=" . $sort . "&order=" . $order . "&radius=" . $radius . "\">Previous Page</a>";
        }
      ?>
    
      <?php
        if(!($page >= ceil($highestPage))){
          echo "<a href=\"eventsList.php?search=" . $searchText . "&page=" . ($page + 1) . "&sort=" . $sort . "&order=" . $order . "&radius=" . $radius . "\">Next Page</a>";
        }
      ?>
      
    </div>
  
    <?php 
    if(mysqli_num_rows($eventsRS) > 0){
      echo "<ul class=\"flex-container\">";
      for($x = 0; $x <= 3; $x++) {
        echo "<div class=\"simrow\">";
        for($y = 0; $y <= 3; $y++) {
          if($event = mysqli_fetch_array($eventsRS)){
            //get distance from user to event
            /*$lat2 = $event['latitude'];
		        $lon2 = $event['longitude'];
		        $theta = $userLongitude - $lon2;
		        $dist = sin(deg2rad($userLatitude)) * sin(deg2rad($lat2)) +  cos(deg2rad($userLatitude)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		        $dist = acos($dist);
		        $dist = rad2deg($dist);
		        $miles = $dist * 60 * 1.1515;*/
		        
		        //print event information to tile box
		        echo "<li class=\"flex-item\">";
		          echo "<span class=\"eventName\">" . $event['name'] . "</span><br />";
		          echo "<span class=\"eventDistance\">Distance: " . round($event['distance']) . " miles</span><br />";
		        echo "</li>";
		        
          }
        }
        echo "</div>";
      }
      echo "</ul>";
    } else {
      echo "<h2>No Results Found</h2>";
    }
    ?>
  </body>
</html>
