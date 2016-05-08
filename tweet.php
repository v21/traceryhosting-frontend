<?php


//header('Content-Type: application/json');

require "twitteroauth/autoload.php";
require "credentials.php";

use Abraham\TwitterOAuth\TwitterOAuth;




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



		$stmt = $pdo->prepare('SELECT * FROM traceries WHERE user_id = :user_id');

		$stmt->execute(array('user_id' => $_SESSION['user_id']));
		$result = $stmt->fetch(PDO::FETCH_ASSOC); 

		if ($result['blocked_status'] != 0) //are they blocked
		{
			switch ($result['blocked_status']) {
				case 1: //hellbanned
					die ("{\"success\": true}");
					break;
				
				default:
					die ("{\"success\": false, \"reason\" : \"This account has been blocked.\"}");
					break;
			}
		    	
		}

		$descriptorspec = array(
		   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
		   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
		   //2 => array("file", "/tmp/error-output.txt", "a") // stderr is a file to write to
		);

		$cwd = '/tmp';
		$env = array('TWITTER_CONSUMER_KEY' => CONSUMER_KEY,
					 'TWITTER_CONSUMER_SECRET' => CONSUMER_SECRET,
					 'ACCESS_TOKEN' => $result['token'],
					 'ACCESS_TOKEN_SECRET' =>  $result['token_secret']);


		$process = proc_open("/home/v21/.nvm/versions/node/v5.5.0/bin/node /home/v21/bots/send_tweet/send_tweet.js", $descriptorspec, $pipes, $cwd, $env);

		if (is_resource($process)) {
		    // $pipes now looks like this:
		    // 0 => writeable handle connected to child stdin
		    // 1 => readable handle connected to child stdout
		    // Any error output will be appended to /tmp/error-output.txt

		    fwrite($pipes[0], $_POST['tweet']);
		    fclose($pipes[0]);

		    $result = stream_get_contents($pipes[1]);
		    fclose($pipes[1]);

		    // It is important that you close any pipes before calling
		    // proc_close in order to avoid a deadlock
		    $return_value = proc_close($process);

		    if ($return_value === 0)
		    {
		    	die ("{\"success\": true}");
		    }
		    else
		    {
		    	die ("{\"success\": false, \"reason\" : " . json_encode($result) . "}");
		    }
		}
		else
		{
			die ("{\"success\": false, \"reason\" : \"can't find node\"}");
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