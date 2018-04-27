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

//build query if search or radius is set
if(isset($_GET['search']) || isset($_GET['radius']) || isset($_GET['location'])){
  //get search text from url
  $searchText = filter_input(INPUT_GET,"search");
  $searchText = strtolower($searchText);

  //get search radius from url
  $radius = round(filter_input(INPUT_GET, "radius"));
  if($radius == 0){
    $radius = 3000;
  }

  //get location from url
  $location = filter_input(INPUT_GET, "location");

  //get page value from url
  $page = round(filter_input(INPUT_GET,"page"));

  //correct for page value being too low, not set, or not a number
  if(!is_numeric($page) || !$page || $page < 1){
    $page = 1;
  }

  //build regex search sections

  //build description and name section
  $regex = "(";
  $first = true;
  $words = explode(" ", $searchText);
  foreach($words as $word){
    if($first){
      $regex .= "(lower(`name`) LIKE \"%".$word."%\" OR lower(`description`) LIKE \"%".$word."%\")";
      $first = false;
    } else {
      $regex .= "AND (lower(`name`) LIKE \"%".$word."%\" OR lower(`description`) LIKE \"%".$word."%\")";
    }
  }
  $regex .= ")";

  //build location section
  if(isset($_GET['location'])){
    $regex .= " AND (lower(`stateName`) LIKE \"%".$location."%\" OR lower(`cityName`) LIKE \"%".$location."%\")";
  }

  //get total number of results for this search
  $query = "SELECT `id`,(69 * DEGREES(ACOS(COS(RADIANS(latitude)) * COS(RADIANS(" . $userLatitude . ")) *
             COS(RADIANS(longitude) - RADIANS(" . $userLongitude . ")) +
             SIN(RADIANS(latitude)) * SIN(RADIANS(" . $userLatitude . "))))) AS distance FROM `events` WHERE " . $regex . " HAVING ". $radius . " >= distance";
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
             SIN(RADIANS(latitude)) * SIN(RADIANS(" . $userLatitude . "))))) AS distance, `cityName`, `stateName`, `locationName`, `date`, `description`, `latitude`, `longitude`  FROM `events` WHERE " . $regex . " HAVING ". $radius . " >= distance";

  $sort = filter_input(INPUT_GET,"sort");
  $order = filter_input(INPUT_GET,"order");

  if(!isset($_GET['sort'])){
    $sort = "distance";
  }

  if(!isset($_GET['order'])){
    $order = "ASC";
  }
  $query .= " ORDER BY `" . $sort . "`" . " " . $order;

  $query .= " LIMIT " . $limit . " OFFSET " . $offset;

  //echo $query . "<br />";

  $eventsRS = mysqli_query($connection, $query);
}
?>

<!doctype html>
<html>
<head>
    <title>Event Finder</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="final.css">
    <link href='https://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700|Open+Sans:300,300i,400,400i,700,700i" rel="stylesheet">
    <script src="jquery-3.3.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
</head>
<body>
  <div class = "backgrounddiv">
  <section id= "header">
    <div class = "container">
      <div id="titletext">
        <h2><a href="index.php">EVENT FINDER</a></h2>
      </div>
    <div id="searchform">
      <form action="events.php" method="get" id="demo-2">
          <input class="help" name="search" type="search" value="<?php echo $searchText; ?>" placeholder="Search for Events">
          <input type="submit" value="Search" style="display: none;">
      </form>
    </div>
    <div id="filtering">
      <input name="radius" form="demo-2" type="search1" <?php if($radius != 3000){echo "value=\"$radius\"";} ?> placeholder="Radius" id="Radi">
      <input name="location" form="demo-2" type="search1" value="<?php echo $location; ?>" placeholder="Location" id="Loca">
      <div class ="filter" id="Proximity" value = '0'>
        <a href="events.php?search=<?php echo $searchText; ?>&radius=<?php echo $radius; ?>&location=<?php $location; ?>&sort=<?php echo "distance"; ?>&order=<?php if(strcmp("distance", $sort) == 0){if(strcmp("ASC", $order) == 0){echo "DESC";}else{echo "ASC";}}else{echo "ASC";}?>">
          <p>Proximity<?php if(strcmp("distance", $sort) == 0){if(strcmp("ASC", $order) == 0){echo "/\\";}else{echo "\\/";}} ?></p>
        </a>
      </div>
      <div class ="filter" id="Date" value = '0'>
        <a href="events.php?search=<?php echo $searchText; ?>&radius=<?php echo $radius; ?>&location=<?php $location; ?>&sort=<?php echo "date"; ?>&order=<?php if(strcmp("date", $sort) == 0){if(strcmp("ASC", $order) == 0){echo "DESC";}else{echo "ASC";}}else{echo "ASC";}?>">
          <p>Date<?php if(strcmp("date", $sort) == 0){if(strcmp("ASC", $order) == 0){echo "/\\";}else{echo "\\/";}} ?></p>
        </a>
      </div>
    </div>
    <div id="pagination">
      <?php if($page >= 6) {?>
      <div class ="pages" id="prev5">
        <a href="<?php echo "events.php?search=" . $searchText . "&page=" . ($page - 5) . "&sort=" . $sort . "&order=" . $order . "&radius=" . $radius . "&location=" . $location?>">
          <p> <<5 </p>
        </a>
      </div>
      <?php } ?>
      <?php if($page > 1){ ?>
      <div class ="pages" id="prev">
        <a href="<?php echo "events.php?search=" . $searchText . "&page=" . ($page - 1) . "&sort=" . $sort . "&order=" . $order . "&radius=" . $radius . "&location=" . $location; ?>">
          <p>Prev</p>
        </a>
      </div>
      <?php } ?>
      <?php if(!($page >= ceil($highestPage))){ ?>
        <div class ="pages" id="next">
          <a href="<?php echo "events.php?search=" . $searchText . "&page=" . ($page + 1) . "&sort=" . $sort . "&order=" . $order . "&radius=" . $radius . "&location=" . $location; ?>">
            <p>Next</p>
          </a>
       </div>
       <?php } ?>
      <?php if(!($page + 4 >= ceil($highestPage))){ ?>
      <div class ="pages" id="next5">
        <a href="<?php echo "events.php?search=" . $searchText . "&page=" . ($page + 5) . "&sort=" . $sort . "&order=" . $order . "&radius=" . $radius . "&location=" . $location; ?>">
          <p> 5>> </p>
        </a>
      </div>
      <?php } ?>
    </div>
    <div class="Pagetext">
      <?php if(ceil($highestPage > 0)){ echo "<br />Page $page of " . ceil($highestPage) . ""; } ?>
    </div>
  </div>
  </section>
  <section id="tiles">
    <div class ="tilecontainer">
      <?php
      if($eventsRS && mysqli_num_rows($eventsRS) > 0){
        while($event = mysqli_fetch_array($eventsRS)){
          echo "<article>";
          echo "  <p class ='eventname'>" . $event['name'] . "</p>";
          echo "  <p class ='eventloc'>" . $event['cityName'] . ", " . $event['stateName'] . " </p>";
          echo "  <p class ='eventprox'>" . round($event['distance']) . " miles away </p>";
          echo "  <p class ='eventdate'>" . date("m/d/y g:i a", $event['date']) . "</p>";
          echo "  <form class='mapbutton' action='http://google.com/maps/search/".$event['latitude'].",".$event['longitude']."' target='blank'> <input type='submit' value='Get directions' /> </form>";
          echo "  <div class ='coords'>";
          echo "    <p class=\"lat\">" . $event['longitude'] . "</p>";
          echo "    <p class=\"lon\">" . $event['latitude'] . "</p>";
          echo "  </div>";
          echo "  <div class = 'hiddendata'>";
          echo "    <p class ='eventvenue'>" . $event['location'] . "</p>";
          echo "    <p class ='eventdesc'>" . $event['description'] . "</p>";
          echo "    <div class ='eventmap'></div>";
          echo "  </div>";
          echo "</article>";
        }
      } else {
        echo "<h2>No Results Found</h2>";
      }
      ?>
    </div>
  </section>
  </div>
  <section id="modal">
    <div class = "modalbg">
    </div>
    <div class = "contentcontainer">
      <div class ="centeringdiv">
        <div class = "closebutton"> X </div>
        <div class = "modalcontent"></div>

      </div>
    </div>
  </section>

  <script src="final.js"></script>
</body>
</html>
