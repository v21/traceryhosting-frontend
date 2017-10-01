<?php


require "twitteroauth/autoload.php";
require "credentials.php";

use Abraham\TwitterOAuth\TwitterOAuth;


$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'callback.php';
header("Location: http://$host$uri/$extra");

define('OAUTH_CALLBACK', "http://$host$uri/$extra");

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
session_set_cookie_params(2678000);
session_start();

$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));

$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

$url = $connection->url('oauth/authenticate', array('oauth_token' => $request_token['oauth_token']));


//redirect to $url

header('Location: ' . $url);
die();


?>