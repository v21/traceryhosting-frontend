<?php


require "twitteroauth/autoload.php";
require "credentials.php";

use Abraham\TwitterOAuth\TwitterOAuth;


define('OAUTH_CALLBACK', "http://v21.io/traceryhosting/");

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);


$pdo = new PDO('mysql:dbname=traceryhosting;host=127.0.0.1;charset=utf8', 'tracery_php', DB_PASSWORD);

$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


session_start();

if (!isset($_SESSION['oauth_token']))
{
	?>

<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                /*padding-top: 50px;*/
                padding-bottom: 40px;
            }
        </style>
        <!--<link rel="stylesheet" href="css/bootstrap-theme.min.css">-->
        <link rel="stylesheet" href="css/main.css">
		<link href='http://fonts.googleapis.com/css?family=Yesteryear' rel='stylesheet' type='text/css'>
        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>
    <body>



    <div class="container-fluid">

        <h1 class=" text-center cursive">Cheap Bots, Done Quick!</h1>
        <br><br>
        <div class="row">
		  <div class="col-md-6 col-md-offset-3">Short explanation of what the site is and why you might care</div>
		</div>
		
        <br><br>
		<div class="row">
		  <div class="center-block">
			<a href="http://v21.io/traceryhosting/signin.php"><img src="img/sign-in-with-twitter-gray.png" class="center-block"></a>
		  </div>
		</div>


<!--
      <hr>

      <footer>
        <p>&copy; Company 2015</p>
      </footer>-->
    </div> <!-- /container -->        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

    </body>
</html>


<?php
die();

}
//we've got an account
$stmt = $pdo->prepare('SELECT * FROM traceries WHERE token = :token');

$stmt->execute(array('token' => $_SESSION['oauth_token']));
$result = $stmt->fetch(PDO::FETCH_ASSOC); 

//todo handle failing to find user

//read from db

	
?>

<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                /*padding-top: 50px;*/
                padding-bottom: 40px;
            }
        </style>
        <!--<link rel="stylesheet" href="css/bootstrap-theme.min.css">-->
        <link rel="stylesheet" href="css/main.css">
		<link href='http://fonts.googleapis.com/css?family=Yesteryear' rel='stylesheet' type='text/css'>
        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>
    <body>



    <div class="container-fluid">

        <h1 class=" text-center cursive">Cheap Bots, Done Quick!</h1>

    <form id="tracery-form">

    <div class="form-group">
        <label for="tracery">Tracery JSON</label><br>
        <textarea class="form-control expanding" rows="25" id="tracery" name="tracery"><?php echo($result['tracery']) /*todo : XSS vuln? */ ?></textarea>
    </div>
<div id="tracery-validator" class="alert alert-danger hidden" role="alert">Parsing error</div>

    <div class="row">
	  <div class="col-xs-10">
	  	<div id="generated-tweet" class="well well-sm">-----</div>
	  </div>
	  <div class="col-xs-1">
		<button type="button" id="refresh-generated-tweet" class="btn btn-default btn-block"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></button>
	  </div>
	  <div class="col-xs-1">
		<button type="button" class="btn btn-tweet btn-block">Tweet</button>
	  </div>
	</div>
<div class="form-inline">
    <div class="form-group">
        <label for="frequency">Send a tweet </label>
	    <select class="form-control" id="frequency" name="frequency">
	    	<?php 
	    		$frequencypossibilities = array(-1 => "Never", 10 => "Every 10 Minutes");
	    		foreach ($frequencypossibilities as $freqvalue => $freqlabel) {
	    			echo('<option value="' . $freqvalue . '" '. ($result['frequency'] == $freqvalue ? 'selected' : '') .'>' . $freqlabel . '</option>');
	    		}
	    	?>
		</select>
    </div>

    <div class="form-group">
        <span style="padding-left:20px">as</span> <?php echo('<a class="username" href="http://twitter.com/' . $result['screen_name']. '">') ?>
	        <?php echo('<img src="' . $_SESSION['profile_pic'] . '" width=32> '); ?>
	        <span class="username-text"><?php echo($result['screen_name']) ?></span>
	        </a>
        </div>
        <a class="btn  btn-warning logout" href="logout.php"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span> Log Out</a>
    <div class="button form-group pull-right">
    
        <button id="save-button" class="btn btn-default">Save</button>
    </div>
</div>

</form>


	        


      <!-- Example row of columns -->
      <!--
      <div class="row">
        <div class="col-md-4">
          <h2>Heading</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
        <div class="col-md-4">
          <h2>Heading</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
       </div>
        <div class="col-md-4">
          <h2>Heading</h2>
          <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
      </div>-->
<!--
      <hr>

      <footer>
        <p>&copy; Company 2015</p>
      </footer>-->
    </div> <!-- /container -->        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/tracery.min.js"></script>
        <script src="js/expanding.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>


