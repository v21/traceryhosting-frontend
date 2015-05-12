<?php


header('Content-Type: application/json');

require "twitteroauth/autoload.php";
require "credentials.php";

use Abraham\TwitterOAuth\TwitterOAuth;


define('OAUTH_CALLBACK', "http://v21.io/traceryhosting/");

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);


$pdo = new PDO('mysql:dbname=traceryhosting;host=127.0.0.1;charset=utf8', 'tracery_php', DB_PASSWORD, array(
    PDO::MYSQL_ATTR_FOUND_ROWS => true
));

$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


session_start();

if (isset($_SESSION['oauth_token']))
{
	try
	{
		//todo validate json here



		$stmt = $pdo->prepare('UPDATE traceries SET frequency=:frequency, tracery=:tracery WHERE token=:token');

	  	$stmt->execute(array('frequency' => $_POST['frequency'], 'tracery' => $_POST['tracery'], 'token' => $_SESSION['oauth_token']));

	  	if ($stmt->rowCount() == 1)
	  	{
			die ("{\"success\": true}");
	  	}
	  	else
	  	{
			die ("{\"success\": false, \"reason\" : \"row count mismatch\"}");
	  	}

	}
	catch(PDOException $e)
	{
		

		die ("{\"success\": false, \"reason\" : \"" . $e . "\"}");
		//die($e); //todo clean this
	}

}
else
{
	die ("{\"success\": false, \"reason\" : \"oauth failure\"}");
}



?>