<!DOCTYPE html>

<html>

    <head>
        <title>Find events near you!</title>
        <link href="EFCSS.css" type="text/css" rel="stylesheet">
    </head>

    <body>
       
        
        <div class="header">
        <div class="container">
            <h1>Event Finder</h1>
            <p>(location displayed here)</p>
            
        </div>
        </div>
        
        <div class="nav">
            <ul>
                <li class="ev">Event Preferences</li>
                <li>Indoor events</li>
                <li>Outdoor events</li>
                <li>Music</li>
                <li>Food</li>
                <li>Public/free</li>
            </ul>
        </div>
        
        <div class="info">
        
		<?php
		include('eventSearch.php');
		?>
        
        
        </div>
    
    
    
    
    </body>
</html>