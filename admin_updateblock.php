<?php

require "credentials.php";


session_set_cookie_params(2678000);
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != ADMIN_USER_ID)
{
  die();
}




$pdo = new PDO('mysql:dbname=traceryhosting;host=127.0.0.1;charset=utf8mb4', 'tracery_php', DB_PASSWORD, array(
    PDO::MYSQL_ATTR_FOUND_ROWS => true
));

$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


	try
	{
    $stmt = $pdo->prepare('UPDATE traceries SET blocked_status=:blocked_status WHERE user_id=:user_id');

    $stmt->execute(array('user_id' => $_POST['user_id'], 'blocked_status' => $_POST['blocked_status']));

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
		
		error_log($e);
		die ("{\"success\": false, \"reason\" : \"db err " . $e->getCode() . "\"}");
		//die($e); //todo clean this
	}



?>