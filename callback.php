<?php

require "twitteroauth/autoload.php";
require "credentials.php";

use Abraham\TwitterOAuth\TwitterOAuth;

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);


$pdo = new PDO('mysql:dbname=traceryhosting;host=127.0.0.1;charset=utf8', 'tracery_php', DB_PASSWORD);

$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


session_start();

$request_token = array();
$request_token['oauth_token'] = $_SESSION['oauth_token'];
$request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];

if (isset($_REQUEST['oauth_token']) && $request_token['oauth_token'] !== $_REQUEST['oauth_token']) {
    // Abort! Something is wrong.
    die("Error! Returned OAuth token didn't match");
}
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);
$access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));

//todo verify that we succeeded

  $stmt = $pdo->prepare('INSERT INTO traceries (token,token_secret, screen_name) VALUES(:token, :token_secret, :screen_name) ON DUPLICATE KEY UPDATE token=:token2, token_secret=:token_secret2, screen_name=:screen_name2');

  $stmt->execute(array('token' => $access_token["oauth_token"], 
                       'token_secret' => $access_token["oauth_token_secret"], 
                       'screen_name' => $access_token["screen_name"],
                       'token2' => $access_token["oauth_token"], 
                       'token_secret2' => $access_token["oauth_token_secret"], 
                       'screen_name2' => $access_token["screen_name"]
                      ));

$_SESSION['oauth_token'] = $access_token["oauth_token"]; //this should be this already?

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
$user_data = $connection->get("users/show", array("screen_name" => $access_token["screen_name"]));

$_SESSION['profile_pic'] = $user_data->profile_image_url; 

header('Location: http://v21.io/traceryhosting');
die();


?>

