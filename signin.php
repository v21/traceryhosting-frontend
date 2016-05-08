<?php


require "twitteroauth/autoload.php";
require "credentials.php";

use Abraham\TwitterOAuth\TwitterOAuth;


define('OAUTH_CALLBACK', "http://cheapbotsdonequick.com/callback.php");

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