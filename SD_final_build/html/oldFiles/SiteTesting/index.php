<!DOCTYPE html>
<head>
    <title>Event Finder</title>
    <link rel="stylesheet" href="KBR.css">
    <link href='https://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700|Open+Sans:300,300i,400,400i,700,700i" rel="stylesheet">
</head>
<body>
<div class="slideshow">
<div class="slideshow-image" style="background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/a_wyq3d86ic-florian-giorgio.jpg')"></div>
<div class="slideshow-image" style="background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/OTwgwURiQN6DLk8zIr8E_DSC00953.jpg')"></div>
<div class="slideshow-image" style="background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/photo-1414265247351-4afd13a3b4e6.jpeg')"></div>
<div class="slideshow-image" style="background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/photo-1428851473740-05d09c5f70c0.jpeg')"></div>
</div>
        <div class="headlink">
            <h1>Event Finder</h1>
        </div>
        <div class = "uidiv">
          <form action="events.php" method="get">
              <div class="question">
                  <input name="search" type="text" required/>
                  <label>Search events</label>
                </div>
                <button type="button" onclick="window.location='events.php?radius=35&sort=distance'">Show Events Near Me</button>
          </form>

        </div>
</body>
