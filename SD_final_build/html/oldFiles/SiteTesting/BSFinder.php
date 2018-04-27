<!DOCTYPE html>

<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
         <link rel="stylesheet" href="SiteTesting/css/bootstrap.min.css">
         <link href="BSCSS.css" type="text/css" rel="stylesheet">
		 <script scr="SiteTesting/js/bootstrap.min.js"></script>
		 <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		 <title>Find events near you!</title>
    </head>

    <body>
       
        <div class = "custom">
        <div class = "container-fluid text-center">
      
            <h1>Event Finder</h1>
            <p>(Your location displayed here!)</p>

        </div>
        </div>
     
        
        
        <div class="info">
        
		<?php
		include('eventSearch.php');
		?>
        
        
        </div>
    
    </body>
</html>