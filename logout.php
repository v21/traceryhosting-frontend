<?php

require "twitteroauth/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;


session_start();

//destroy the session

$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();
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
                padding-top: 10px;
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

<div class="alert alert-info" role="alert">Successfully logged out</div>

        <br><br>
        <h1 class="header text-center cursive">Cheap Bots, Done Quick!</h1>
        <br><br>

        
		
        <br><br>
		<div class="row">
		  <div class="center-block">
			<a href="signin.php"><img src="img/sign-in-with-twitter-gray.png" class="center-block"></a>
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

