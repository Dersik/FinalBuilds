<!DOCTYPE html>
<html>
<head>
<script language="JavaScript" src="http://www.geoplugin.net/javascript.gp" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

</head>
<body>

 <?php
 $user_ip = getenv('REMOTE_ADDR');
$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
$a = $geo["geoplugin_latitude"];
$b = $geo["geoplugin_longitude"];
 echo "input =" . $a . " and " . $b ;
?> 

</body>
</html>