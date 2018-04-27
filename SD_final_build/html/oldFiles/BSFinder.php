<!DOCTYPE html>

<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
         <link rel="stylesheet" href="SiteTesting/css/bootstrap.min.css">
         <link href="BSCSS.css" type="text/css" rel="stylesheet">
		 <script scr="SiteTesting/js/bootstrap.min.js"></script>
		 <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		 <script language="JavaScript" src="http://www.geoplugin.net/javascript.gp" type="text/javascript"></script>
		 <title>Find events near you!</title>
    </head>

    <body>
       
	    
    
        <div class = "container-fluid text-center">
		<div class = "custom">
	
      
        <h1>Event Finder</h1>
            <div class = "row">
                <div class = "col-md-4">
                
                    <a href="http://104.131.123.102" class="btn btn-info" role="button">Home</a>
                
                </div>
                <div class = "col-md-4 col-md-offset-4">
                    <script language="Javascript"> 
				        document.write("Welcome to our visitors from "+geoplugin_city()+", "+geoplugin_countryName()); 
			        </script>
                </div>
            
            </div>
        <div class = "row">
            <div class = "col-sm-12"></div>
                
        </div>

        </div>
        </div>
        
        
        <div class="container">
        <div class="info">
        
		<?php
		include('eventSearch.php');
		?>
        
        </div>
        </div>
    
    </body>
</html>