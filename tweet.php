<?php


header('Content-Type: application/json');

require "twitteroauth/autoload.php";
require "credentials.php";

use Abraham\TwitterOAuth\TwitterOAuth;




$pdo = new PDO('mysql:dbname=traceryhosting;host=127.0.0.1;charset=utf8mb4', 'tracery_php', DB_PASSWORD, array(
    PDO::MYSQL_ATTR_FOUND_ROWS => true
));

$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


session_set_cookie_params(86400);
session_start();

if (isset($_SESSION['oauth_token']))
{
	try
	{



		$stmt = $pdo->prepare('SELECT * FROM traceries WHERE user_id = :user_id');

		$stmt->execute(array('user_id' => $_SESSION['user_id']));
		$result = $stmt->fetch(PDO::FETCH_ASSOC); 


		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $result['token'], $result['token_secret']);

		$tweet_result = $connection->post("statuses/update", array("status" => $_POST['tweet']));

		if ($connection->getLastHttpCode() == 200) {
		    die ("{\"success\": true}");
		} else {
			die ("{\"success\": false, \"reason\" : \"" . $connection->getLastHttpCode() . "\"}");
		}

	}
	catch(PDOException $e)
	{
		
		error_log($e);
		die ("{\"success\": false, \"reason\" : \"db err\"}");
		//die($e); //todo clean this
	}

}
else
{
	die ("{\"success\": false, \"reason\" : \"oauth failure\"}");
}



?>