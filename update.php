<?php


header('Content-Type: application/json');

require "twitteroauth/autoload.php";
require "credentials.php";

use Abraham\TwitterOAuth\TwitterOAuth;



$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);


$pdo = new PDO('mysql:dbname=traceryhosting;host=127.0.0.1;charset=utf8mb4', 'tracery_php', DB_PASSWORD, array(
    PDO::MYSQL_ATTR_FOUND_ROWS => true
));

$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


session_set_cookie_params(2678000);
session_start();

if (isset($_SESSION['oauth_token']))
{
	try
	{
		//todo validate json here

		$stmt = $pdo->prepare('UPDATE traceries SET frequency=:frequency, tracery=:tracery, public_source=:public_source, does_replies=:does_replies, reply_rules=:reply_rules WHERE token=:token');

	  	$stmt->execute(array('frequency' => $_POST['frequency'], 'tracery' => $_POST['tracery'],'public_source' => $_POST['public_source'],'does_replies' => $_POST['does_replies'],'reply_rules' => $_POST['reply_rules'], 'token' => $_SESSION['oauth_token']));

	  	if ($stmt->rowCount() == 1)
	  	{
	  		mail("vtwentyone+php@gmail.com", "Bot update: " . $_SESSION['screen_name'] . " every " . $_POST['frequency'] . " minutes" . ($_POST['does_replies'] == "1"? " and replies" : ""), $_POST['tracery'] . ($_POST['does_replies'] == "1"? "\n\n replies:" . $_POST['reply_rules'] : ""));
			die ("{\"success\": true}");
	  	}
	  	else
	  	{
			die ("{\"success\": false, \"reason\" : \"row count mismatch\"}");
	  	}

	}
	catch(PDOException $e)
	{
		
		error_log($e);
		die ("{\"success\": false, \"reason\" : \"db err " . $e->getCode() . "\"}");
		//die($e); //todo clean this
	}

}
else
{
	die ("{\"success\": false, \"reason\" : \"Not signed in\"}");
}



?>