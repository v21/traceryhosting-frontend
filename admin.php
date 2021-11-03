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
        <title>ADMIN for Cheap Bots, Done Quick!</title>
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
  last_error_code,
  screen_name, 
  user_id,
  last_ip,
  round((length(tracery )-length(replace(tracery ,"#","")))/length("#")) as "hash",
  round((length(tracery )-length(replace(tracery ,"http","")))/length("http")) as "http",
  frequency, 
  last_updated,
  created_on,
  blocked_status, 
  public_source, 
  does_replies, 
  CHAR_LENGTH(tracery) as "tracery_size", 
  tracery LIKE "%{svg %" as "svg"
  FROM traceries
  WHERE created_on  > DATE_ADD(NOW(), INTERVAL -30 DAY)
  ORDER BY last_updated DESC');
}
else
{
  $stmt = $pdo->prepare('SELECT 
  last_error_code,
  screen_name, 
  user_id,
  last_ip,
  frequency, 
  last_updated,
  created_on,
  blocked_status, 
  public_source, 
  does_replies, 
  CHAR_LENGTH(tracery) as "tracery_size", 
  tracery LIKE "%{svg %" as "svg"
  FROM traceries
  WHERE frequency > 0
  AND created_on  > DATE_ADD(NOW(), INTERVAL -30 DAY)
  ORDER BY last_updated DESC');
}


$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC); 

?>

        <h1 class="header text-center cursive">Cheap Bots, Done Quick!</h1>
        <br><br>
        <div class="row">


<div class="col-md-10 col-md-offset-1">
  <?php
if (!$include_inactive)
{
  echo('<a href="?include_inactive=true">(include inactive)</a>');
}
?>
<table class="admintable sortable">
  <tr>
    <th>block</th>
    <th>blocked</th>
    <th>last err</th>
    <th>freq</th>
    <th>screen_name</th>
    <th class="sorttable_numeric">user_id</th>
    <th>last_ip</th>
    <th>created</th>
    <th>updated</th>
    <th>size</th>
    <th>#</th>
    <th>http</th>
    <th>svg</th>
    <th>public</th>
    <th>replies</th>
  </tr>


<?php
  foreach ($results as $key => $value) {
    $public_source = $value['public_source'] == 0 ? "no" : "<a href=\"/source/{$value['screen_name']}\" target=\"_blank\">yes</a>";
    $does_replies = $value['does_replies'] == 0 ? "no" : "yes";
    $svg = $value['svg'] == 0 ? "no" : "yes";
    $blocked_toggled = $value['blocked_status'] === 1 ? "0" : "1";
    $block_label = $value['blocked_status'] === 1 ? "Unblock" : "Block";
    $block_class = $value['blocked_status'] === 1 ? "btn-primary" : "btn-warning";
    echo("<tr>
      <td>
        <button type=\"button\" class=\"block_user btn btn-sm btn-default {$block_class}\" name=\"{$value['user_id']}\" value=\"{$blocked_toggled}\">{$block_label}</button>
      </td>
      <td>{$value['blocked_status']}</td>
      <td>{$value['last_error_code']}</td>
      <td>{$value['frequency']}</td>
      <td><a href=\"admin_single.php?screen_name={$value['screen_name']}\" target=\"_blank\">{$value['screen_name']}</a></td>
      <td><a href=\"https://twitter.com/{$value['screen_name']}\" target=\"_blank\">{$value['user_id']}</a></td>
      <td>{$value['last_ip']}</td>
      <td>{$value['created_on']}</td>
      <td>{$value['last_updated']}</td>
      <td>{$value['tracery_size']}</td>
      <td>{$value['hash']}</td>
      <td>{$value['http']}</td>
      <td>{$svg}</td>
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
        <script>
        $('.block_user').click(function() {
          let btn = $(this);
          $.ajax({
            url: "admin_updateblock.php",
            method : "POST",
            data : {"user_id": btn.prop('name'), "blocked_status":  btn.val() },
            dataType: "json"	  
          })
            .done(function( data ) {
            if (data.hasOwnProperty('success') && data['success'])
            {
              if (btn.val() === '1') { //was unblocked, now blocked
                btn.val(0);
                btn.addClass("btn-primary");
                btn.removeClass("btn-warning");
                btn.text("Unblock");
              } 
              else {
                btn.val(1);
                btn.addClass("btn-warning");
                btn.removeClass("btn-primary");
                btn.text("Block");
              }

            }
            else {
              alert(" update block failed: " + (data.hasOwnProperty('reason') && data['reason']));
            }
            })
            .fail( function( jqXHR, textStatus ) {
              alert(" update block failed: " + textStatus);
            });
        });

        </script>
    </body>
</html>


