<?php

require "credentials.php";


session_set_cookie_params(2678000);
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != ADMIN_USER_ID)
{
  die();
}


?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Cheap Bots, Done Quick!</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">

        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <style>
            body {
                /*padding-top: 50px;*/
                padding-bottom: 40px;
            }
        </style>
        <!--<link rel="stylesheet" href="css/bootstrap-theme.min.css">-->
        <link rel="stylesheet" href="/css/main.css">
    <link href='//fonts.googleapis.com/css?family=Yesteryear' rel='stylesheet' type='text/css'>
        <script src="/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <script src="/js/underscore-min.js"></script>
    </head>
    <body>
    

<?php


$include_inactive = isset($_GET["include_inactive"]);


$pdo = new PDO('mysql:dbname=traceryhosting;host=127.0.0.1;charset=utf8mb4', 'tracery_php', DB_PASSWORD);

$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if ($include_inactive)
{
  $stmt = $pdo->prepare('SELECT 
  screen_name, 
  user_id,
  frequency, 
  blocked_status, 
  public_source, 
  does_replies, 
  CHAR_LENGTH(tracery) as "tracery_size", 
  tracery LIKE "%{svg %" as "svg"
  FROM traceries');
}
else
{
  $stmt = $pdo->prepare('SELECT 
  screen_name, 
  user_id,
  frequency, 
  blocked_status, 
  public_source, 
  does_replies, 
  CHAR_LENGTH(tracery) as "tracery_size", 
  tracery LIKE "%{svg %" as "svg"
  FROM traceries
  WHERE frequency > 0');
}


$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC); 

?>

        <h1 class="header text-center cursive">Cheap Bots, Done Quick!</h1>
        <br><br>
        <div class="row">


<div class="col-md-6 col-md-offset-2">
  <?php
if (!$include_inactive)
{
  echo('<a href="?include_inactive=true">(include inactive)</a>');
}
?>
<table class="admintable sortable">
  <tr><th>freq</th> <th>screen_name</th> <th class="sorttable_numeric">user_id</th> <th>tracery size</th> <th>svg</th> <th>blocked</th> <th>public</th> <th>replies</th></tr>
<?php
  foreach ($results as $key => $value) {
    $public_source = $value['public_source'] == 0 ? "no" : "yes";
    $does_replies = $value['does_replies'] == 0 ? "no" : "yes";
    $svg = $value['svg'] == 0 ? "no" : "yes";
    echo("<tr>
      <td>{$value['frequency']}</td>
      <td><a href=\"admin_single.php?screen_name={$value['screen_name']}\" target=\"_blank\">{$value['screen_name']}</a></td>
      <td>{$value['user_id']}</a></td>
      <td>{$value['tracery_size']}</td>
      <td>{$svg}</td>
      <td>{$value['blocked_status']}</td>
      <td>{$public_source}</td>
      <td>{$does_replies}</td>
      </tr>");
  }
?>



</table>
</div>
</div>

<!--
      <hr>

      <footer>
        <p>&copy; Company 2015</p>
      </footer>-->
    </div> <!-- /container -->        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

        <script src="/js/vendor/bootstrap.min.js"></script>

        <script src="/js/tracery.js"></script>
        <script src="/js/twitter-text-1.9.4.min.js"></script>
        <script src="/js/expanding.js"></script>
        <script src="/js/json2.js"></script>
        <script src="/js/jsonlint.js"></script>
        <script src="/js/main.js"></script>
        <script src="/js/admin/sorttable.js"></script>
        <script type="text/javascript">var screen_name = "<?php echo($result['screen_name'])?>"</script>
    </body>
</html>


