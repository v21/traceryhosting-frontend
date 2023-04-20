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

require "../credentials.php";

  /*
  The following function will strip the script name from URL i.e.  http://www.something.com/search/book/fitzgerald will become /search/book/fitzgerald
  */
  function getCurrentUri()
  {
    $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
    $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
    if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
    $uri = '/' . trim($uri, '/');
    return $uri;
  }
 
  $base_url = getCurrentUri();
  $routes = array();
  $routes = explode('/', $base_url);

  $screen_name = $routes[1];
  $key = $routes[2];

$required_key = hash('sha256', $screen_name . "+" . SOURCE_HASH_SECRET);

  $pdo = new PDO('mysql:dbname=traceryhosting;host=127.0.0.1;charset=utf8mb4', 'tracery_php', DB_PASSWORD);

$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if ($screen_name === "")
{
  ?>
  <h1 class="header text-center cursive">Cheap Bots, Done Quick!</h1>
        <br>
        <div class="row">
      <div class="col-md-6 col-md-offset-3">
      <p>Go to /source/<code>botname</code> to view the source for that bot (if available).</p>
      </div>
    </div>
    <?php
}
elseif ($required_key != $key)
{
  ?>
  <h1 class="header text-center cursive">Cheap Bots, Done Quick!</h1>
        <br>
        <div class="row">
      <div class="col-md-6 col-md-offset-3">
      <p>Could not find Tracery source for <b><?php echo($screen_name) ?></b> - if this is your account, please check the link again, or contact v21</p>
      </div>
    </div>
    <?php
}
else
{

//we've got an account
$stmt = $pdo->prepare('SELECT * FROM traceries WHERE screen_name = :screen_name ORDER BY user_id DESC LIMIT 1');

$stmt->execute(array('screen_name' => $screen_name));
$result = $stmt->fetch(PDO::FETCH_ASSOC); 


?>

    <div class="container-fluid">

    <h1 class="header text-center cursive">Cheap Bots, Done Quick!</h1>
        <br>
        <div class="row">
      <div class="col-md-6 col-md-offset-3">
          <p>This is the <a href="https://github.com/galaxykate/tracery">Tracery</a> source for the bot that was running at <?php echo('<a href="https://twitter.com/' . $result['screen_name']. '">') ?>@<?php echo($result['screen_name']) ?></a>. It tweeted
          <?php 
          $frequencypossibilities = array(-1 => "never", 10 => "every 10 minutes", 30 => "every half hour", 60 => "every hour", 120 => "every 3 hours", 180 => "every 3 hours", 360 => "every 6 hours", 720 => "twice a day", 1440 => "once a day", 10080 => "once a week", 43829 => "once a month", 525949 => "once a year", 42 => "when run manually");
          echo($frequencypossibilities[$result['frequency']]);
        ?><?php echo($result['does_replies'] === "1"? " and replies to mentions":"")?>.</p>
      </div>
    </div>
    
        <br><br>
    <form id="tracery-form">

    <div class="form-group">
        <label for="tracery">Tracery JSON</label><br>
        <textarea class="form-control expanding" rows="25" id="tracery" name="tracery">
<?php 
        
  echo(htmlentities($result['tracery'], ENT_QUOTES | ENT_HTML5, "UTF-8")); 

?>

</textarea>
    </div>
<div id="tracery-validator" class="alert alert-danger hidden" role="alert">Parsing error</div>

    <div class="row">
    <div class="col-md-12">
      <div class="pull-right pad-left">
    <button type="button" id="refresh-generated-tweet" class="btn btn-default"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></button>
    </div>
      <div id="generated-tweet" style="overflow: auto;" class="well well-sm">-----
        <div id="tweet-media"> 
        </div>
      </div>
      
   </div>
  </div>
<div class="form-inline">
    

    <div class="form-group">
        <span style="padding-left:20px">for</span> <?php echo('<a class="username" href="https://twitter.com/' . $result['screen_name']. '">') ?>
          
          <span class="username-text">@<?php echo($result['screen_name']) ?></span>
          </a>
        </div>
       
</div>

<?php 
if ($result['does_replies'] === 1) 
{
  ?>
  Replies Tracery:
    <div id="reply_rules_container" name = "reply_rules_container" class="form-group <?php echo(($result['does_replies'] ? "": "hidden")) ?>">
        
        <textarea class="form-control expanding" rows="7" id="reply_rules" name="reply_rules">
<?php 
        
          echo(htmlentities($result['reply_rules'], ENT_HTML5 | ENT_QUOTES , "UTF-8")); 
        
?>

</textarea>
    </div>
    <div id="replyrules-validator" class="alert alert-danger hidden" role="alert">Parsing error</div>

<?php 
}
?>

</form>


<?php
}

//todo handle failing to find user

//read from db

?>





          


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
        <script type="text/javascript">var screen_name = "<?php echo($result['screen_name'])?>"</script>
    </body>
</html>


