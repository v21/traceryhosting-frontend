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
	die ("<a href=\"http://v21.io/traceryhosting/signin.php\">Login</a>");
}
//we've got an account
$stmt = $pdo->prepare('SELECT * FROM traceries WHERE token = :token');

$stmt->execute(array('token' => $_SESSION['oauth_token']));
$result = $stmt->fetch(PDO::FETCH_ASSOC); 

//todo handle failing to find user

//read from db

echo("hello " . $result['screen_name']);
	
?>

<form action="update.php" method="post">
    <div>
        <label for="frequency">Frequency</label>
	    <select name="frequency">
	    	<?php 
	    		$frequencypossibilities = array(-1 => "Never", 10 => "Every 10 Minutes");
	    		foreach ($frequencypossibilities as $freqvalue => $freqlabel) {
	    			echo('<option value="' . $freqvalue . '" '. ($result['frequency'] == $freqvalue ? 'selected' : '') .'>' . $freqlabel . '</option>');
	    		}
	    	?>
		</select>
    </div>
    <div>
        <label for="tracery">Tracery JSON</label>
        <textarea id="tracery" name="tracery"><?php echo($result['tracery']) /*todo : XSS vuln? */ ?></textarea>
    </div>
    <br>
    <div class="button">
        <button type="submit">Upload your bot!</button>
    </div>
</form>

