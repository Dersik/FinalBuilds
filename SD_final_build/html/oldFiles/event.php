<!DOCTYPE html>

<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="SiteTesting/css/bootstrap.min.css">
        <link href="BSCSS.css" type="text/css" rel="stylesheet">
		<script scr="SiteTesting/js/bootstrap.min.js"></script>
		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="html/SiteTesting/js/bootstrap.min.js"></script>
		<script src="location_detection.js"></script>
		 <title>Find events near you!</title>
		 
    </head>

    <body>
       
        <div class = "container-fluid text-center">
		<div class = "custom">
            <h1>Event Finder</h1>
			<div align = "left"; style = "padding-left: 20px">
			<a href="http://104.131.123.102" class="btn btn-info" role="button">Home</a>
        </div>
			<p>Welcome visitor from <span id="city"></span></p>
        </div>
		
		<div class = "container" id="event_info">
			<div class="row">
				<div class="col-md-4"> <br>Event info</br>
				<div id="map"></div>
				<?php
					include('eventPage.php');
				?>
				</div>
			</div>
		<div>
    
    </body>
</html>