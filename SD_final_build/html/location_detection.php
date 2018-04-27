<?php
require_once('geoplugin.class.php');
$geoplugin = new geoPlugin();
 
$geoplugin->locate();
 
echo "City: {$geoplugin->city} <br />\n";
    
?>