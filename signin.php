<?php


require "vendor/autoload.php";
require "credentials.php";

use Abraham\TwitterOAuth\TwitterOAuth;

if (isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
  $protocol = 'https://';
}
else {
  $protocol = 'http://';
}
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'callback.php';
header("Location: $protocol$host$uri/$extra");

define('OAUTH_CALLBACK', "$protocol$host$uri/$extra");

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
