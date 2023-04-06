<?php

require "vendor/autoload.php";
require "credentials.php";

use Abraham\TwitterOAuth\TwitterOAuth;



$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);


$pdo = new PDO('mysql:dbname=traceryhosting;host=127.0.0.1;charset=utf8mb4', 'tracery_php', DB_PASSWORD);

$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


session_set_cookie_params(2678000);
session_start();


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

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                /*padding-top: 50px;*/
                padding-bottom: 40px;
            }
        </style>
        <!--<link rel="stylesheet" href="css/bootstrap-theme.min.css">-->
        <link rel="stylesheet" href="css/main.css">
		<link href='//fonts.googleapis.com/css?family=Yesteryear' rel='stylesheet' type='text/css'>
        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <script src="js/underscore-min.js"></script>
    </head>
    <body>

<?php
if (!isset($_SESSION['user_id']))
{
  ?>

    <div class="container-fluid">

        <h1 class="header text-center cursive">Cheap Bots, Done Quick!</h1>
        <br><br>
        <div class="row">
		  <div class="col-md-6 col-md-offset-3">
		  

	<p>This site will help you make a Twitterbot! They're easy to make and free to run.
      </p>

		  <p>To use it, <a href="https://twitter.com/signup">create a Twitter account</a> for your bot to run under and then sign in below. 
		  The bots are written in <a href="http://www.brightspiral.com">Tracery</a>, a tool for writing generative grammars developed by <a href="http://www.galaxykate.com/">Kate Compton</a>. This site is run by <a href="https://v21.io">v buckenham</a> - they can be contacted at <a href="mailto:vtwentyone@gmail.com">vtwentyone@gmail.com</a>. You can support this site on <a href="https://www.patreon.com/v21">Patreon</a>.</p>
		  </p>
<br>
      <p>
        <b>Update (6th April 2023)</b>: CBDQ has closed down! Twitter <a href="https://twitter.com/TwitterDev/status/1641222786894135296">has ended all large-scale API usage</a>, and this means that CBDQ is unable to operate. Please <a href="mailto:vtwentyone+cbdq@gmail.com">get in touch</a> if you would like access to the source of your bot.
      </p>
      <p>
      If you are interested in moving your bot to Mastodon, @boodooperson has set up <a href="https://cheapbotstootsweet.com">Cheap Bots, Toot Sweet!</a>, which operates using the same syntax.
      </p>
      <p>
        I am currently at work on a spritual sequel to CBDQ called <a href="https://downpour.games/">Downpour</a>, where you can collage images together to make videogames. It's aiming to be just as fun and accessible as CBDQ. <a href="https://downpour.games/">You can sign up to be notified once it releases.</a>
      </p>
      <p>
        And finally... if you made a Twitterbot with Cheap Bots, Done Quick!... thank you! The site would've been nothing without you.
      </p>
		  </div>
		</div>
		
        <br><br>
		<!-- <div class="row">
		  <div class="center-block">
			<a href="signin.php"><img src="img/sign-in-with-twitter-gray.png" class="center-block"></a>
		  </div>
		</div> -->

        <br><br>
        <div class="row">
          <div class="col-md-6 col-md-offset-3">
          Some examples of twitterbots made with this site:
          <ul id="shuffle">
          <li><a href="https://twitter.com/hashfacade">@hashfacade</a> <a href="//cheapbotsdonequick.com/source/hashfacade">(source)</a></li>
          <li><a href="https://twitter.com/gnuerror">@gnuerror</a> <a href="//cheapbotsdonequick.com/source/gnuerror">(source)</a></li>
          <li><a href="https://twitter.com/unicode_garden">@unicode_garden</a> <a href="//cheapbotsdonequick.com/source/unicode_garden">(source)</a></li>
          <li><a href="https://twitter.com/softlandscapes">@softlandscapes</a> <a href="//cheapbotsdonequick.com/source/softlandscapes">(source)</a></li>
          <li><a href="https://twitter.com/thetinygallery">@thetinygallery</a> <a href="//cheapbotsdonequick.com/source/thetinygallery">(source)</a></li>
          <li><a href="https://twitter.com/bot_teleport">@bot_teleport</a> <a href="//cheapbotsdonequick.com/source/bot_teleport">(source)</a></li>
          <li><a href="https://twitter.com/autoflaneur">@autoflaneur</a> <a href="//cheapbotsdonequick.com/source/autoflaneur">(source)</a></li>
          <li><a href="https://twitter.com/lotsofeyes">@lotsofeyes</a> <a href="//cheapbotsdonequick.com/source/lotsofeyes">(source)</a></li>
          <li><a href="https://twitter.com/thinkpiecebot">@thinkpiecebot</a></li>
          <li><a href="https://twitter.com/infinitedeserts">@infinitedeserts</a></li>
          <li><a href="https://twitter.com/FoleyArtists">@FoleyArtists</a> <a href="//cheapbotsdonequick.com/source/FoleyArtists">(source)</a></li>
          <li><a href="https://twitter.com/What_Hastings">@What_Hastings</a></li>
          <li><a href="https://twitter.com/petitsmotifs">@petitsmotifs</a></li>
          <!--<li><a href="https://twitter.com/AbhorrentSexBot">@AbhorrentSexBot</a></li>-->
          
          </ul>

<script type="text/javascript">
var ul = document.getElementById("shuffle");
for (var i = ul.children.length; i >= 0; i--) {
    ul.appendChild(ul.children[Math.random() * i | 0]);
}
</script> 
          </div>
        </div>


<!--
      <hr>

      <footer>
        <p>&copy; Company 2015</p>
      </footer>-->
    </div> <!-- /container -->        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

    </body>
</html>


<?php
die();

}
//we've got an account
$stmt = $pdo->prepare('SELECT * FROM traceries WHERE user_id = :user_id');

$stmt->execute(array('user_id' => $_SESSION['user_id']));
$result = $stmt->fetch(PDO::FETCH_ASSOC); 

//todo handle failing to find user

//read from db

	
?>




    <div class="container-fluid">

    <h1 class="header text-center cursive">Cheap Bots, Done Quick!</h1>
        <br>
        <div class="row">
		  <div class="col-md-8 col-md-offset-2">
          <p>Bots are written in <a href="http://brightspiral.com/">Tracery</a>, a generative grammar specified as a <a href="http://www.tutorialspoint.com/json/json_syntax.htm">JSON</a> string. This site will automatically expand your text, starting from the "origin" node, and then tweet it on a fixed schedule. If it generates a duplicate tweet, or a tweet over 280 characters, it will retry up to 5 times. Line breaks can be entered with the special sequence <code>\n</code>, and hashtags with <code>\\#</code>.</p>

          <p>You can also include images in your tweets. The simplest way to do this is to specify a URL, like so <code>{img https://cheapbotsdonequick.com/example.jpg}</code> (this also works with video: <code>{vid https://cheapbotsdonequick.com/example.mp4}</code>). Alternatively, to generate an image within CBDQ, you can use <a href="https://developer.mozilla.org/en-US/docs/Web/SVG">SVGs</a>. A good simple example to start from is the source of <a href="//cheapbotsdonequick.com/source/hashfacade">@hashfacade</a>. The syntax looks like this: <code>{svg  &lt;svg ...&gt; ... &lt;/svg&gt;}</code>. SVGs will need to specify a <code>width</code> and <code>height</code> attribute. Note that <code>"</code>s within SVG files need to be escaped as <code>\"</code>, as does <code>#</code>s (<code>\\#</code>). <code>{</code>s and <code>}</code>s can be escaped as <code>\\\\{</code> and <code>\\\\}</code>. </p>

          <p>If you create a bot I deem spammy, abusive or otherwise unpleasant (for example, @mentioning people who have not consented, posting insults or using slurs) I will take it down. If you have any questions, bug reports or comments then you can reach me at <a href="https://twitter.com/v21">@v21</a> or at <a href="mailto:vtwentyone@gmail.com">vtwentyone@gmail.com</a></p>
		  <ul>
        <li><a href="http://air.decontextualize.com/tracery/">Tracery tutorial</a></li>
  		  <li><a href="http://www.crystalcodepalace.com/traceryTut.html">Interactive tutorial</a></li>
  		  <li><a href="http://www.brightspiral.com/tracery/">Tracery visual editor</a></li>
        <li><a href="https://github.com/dariusk/corpora">Useful word collections</a></li>
  		  <li><a href="https://github.com/v21/tracerybot">Example of a self-hosted bot running on Tracery</a></li>
		  </ul>
		  <p>
        If you would like to help pay for server costs, maintenance and further development of this service, you can do so by <a href="https://www.patreon.com/v21">supporting Cheap Bots, Done Quick! on Patreon</a>. Happy botting!
      </p>
      <p></p>
		  </div>
		</div>
		
        <br><br>
    <form id="tracery-form">

<?php 
if  (!is_null($result['last_error_code']) && $result['last_error_code'] != 187) { //don't show duplicate error

$error_msg = "Unknown error :" . $result['last_error_code'];

switch($result['last_error_code']) {
  case 64:
    $error_msg = "Account suspended";
  break;
  case 89:
    $error_msg = "CBDQ token is invalid: please reauthenticate";
  break;
  case 170:
    $error_msg = "Twitter error: sent empty status";
  break;
  case 186:
    $error_msg = "Twitter error: status is too long";
  break;
  case 187:
    $error_msg = "Twitter error: status is a duplicate, trying again";
  break;
  case 324:
    $error_msg = "Twitter error: couldn't attach image or video";
  break;
  case 326:
    $error_msg = "Twitter temporarily locked your account. Cheap Bots, Done Quick! has stopped attempting to post — press SAVE to re-enable.";
  break;
}


  echo('<div id="server-error" class="alert alert-danger" role="alert">'.$error_msg.'</div>');
}
?>

    

    <div class="form-group">
        <label for="tracery">Tracery JSON</label><br>
        <textarea class="form-control expanding" rows="25" id="tracery" name="tracery">
<?php 
        if (is_null($result['tracery']))
        {
        	echo('{
	"origin": ["this could be a tweet", "this is #alternatives# tweet", "#completely different#"],
	"alternatives" : ["an example", "a different", "another", "a possible", "a generated", "your next"],
	"completely different" : ["and now for something completely different", "so long and thanks for all the fish", "or, maybe, #alternatives# badger"]
}
');
        }
        else
        {
        	echo(htmlspecialchars($result['tracery'], ENT_HTML5 | ENT_QUOTES , "UTF-8")); /*todo : XSS vuln? */
        }
?>

</textarea>
    </div>



<div id="tracery-validator" class="alert alert-danger hidden" role="alert">Parsing error</div>

    <div class="row">
    <div class="col-md-12">
    	<div class="pull-right pad-left">
		<button type="button" id="refresh-generated-tweet" class="btn btn-default"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></button>
	 	<button type="button" id="tweet-generated-tweet" class="btn btn-tweet">Tweet</button>
		</div>
	  	<div id="generated-tweet" style="overflow: auto;" class="well well-sm">-----
        <div id="tweet-media"> 
        </div>
      </div>
	  	
	 </div>
	</div>
<div class="form-inline">
    <div class="form-group">
        
	    <select class="form-control" id="frequency" name="frequency">
	    	<?php 
	    		$frequencypossibilities = array(-1 => "Never", 10 => "Every half hour (was every 10 minutes)", 30 => "Every half hour", 60 => "Every hour", 120 => "Every 2 hours", 180 => "Every 3 hours", 360 => "Every 6 hours", 720 => "Twice a day", 1440 => "Once a day", 10080 => "Once a week", 43829 => "Once a month", 525949 => "Once a year");
	    		foreach ($frequencypossibilities as $freqvalue => $freqlabel) {
	    			echo('<option value="' . $freqvalue . '" '. ($result['frequency'] == $freqvalue ? 'selected' : '') .'>' . $freqlabel . '</option>');
	    		}
	    	?>
		</select>
    </div>

    <div class="form-group">
        post a tweet as <?php echo('<a class="username" href="https://twitter.com/' . $result['screen_name']. '">') ?>
	        <?php echo('<img src="' . $_SESSION['profile_pic'] . '" width=32> '); ?>
	        <span class="username-text"><?php echo($result['screen_name']) ?></span>
	        </a>
    </div>
    <br>

    <div class="form-group">
    Replies have been disabled, in accordance with <a href="https://twitter.com/TwitterDev/status/1623467618400374784">Twitter's API changes</a>. However, if you have previously set replies, you can still view them:
          
          <select class="form-control" id="does_replies" name="does_replies">
          <?php 
            $replypossibilities = array(1 => "Show", 0 => "Hide");

            foreach ($replypossibilities as $replyvalue => $replylabel) {
              echo('<option value="' . $replyvalue . '" '. ($result['does_replies'] == $replyvalue ? 'selected' : '') .'>' . $replylabel . '</option>');
            }
          ?>
          </select>
          </a>

        </div>


</div>
    <div id="reply_rules_container" name = "reply_rules_container" class="form-group <?php echo(($result['does_replies'] ? "": "hidden")) ?>">
<div class="row">
      <div class="col-md-7 col-md-offset-3">
        <p style="color:#c7254e"> Reply functionality has been disabled! This section is only for use retriving any rules you might have put here.</p>
      <br>
          <p>This is also in <a href="https://www.tutorialspoint.com/json/json_syntax.htm">JSON</a> format. When a mention is received, it's checked against the keys (the left hand part) for a match. The keys are specified with <a href="https://developer.mozilla.org/en/docs/Web/JavaScript/Guide/Regular_Expressions">RegExp syntax</a> - this also straightforwardly matches any letters (as long as there's no punctuation). If you want to catch all replies, use <code>"."</code>. The value (the right hand part) is used as the Tracery syntax for the reply - this can be plain text or a symbol such as <code>"#origin#"</code>. Mentions are checked every 5 minutes, and have a 5% chance of being ignored (to prevent bots from responding to each other forever).</p> 
      </div>
      </div>

            <div class="row">
    <div class="col-md-11 col-md-offset-1">

    <div class="form-group">
        <textarea class="form-control" rows="7" id="reply_rules" name="reply_rules" disabled>
<?php 
        if (is_null($result['reply_rules']))
        {
          echo('{
	"hello":"hello there!",
	".":"#origin#"
}
');
        }
        else
        {
          echo(htmlspecialchars($result['reply_rules'], ENT_HTML5 | ENT_QUOTES , "UTF-8")); 
        }
?>


</textarea>
</div>
    <!-- <div id="replyrules-validator" class="alert alert-danger hidden" role="alert">Parsing error</div>
      Test mention: <textarea class="form-control" rows="1" id="test_mention" name="test_mention">@<?php echo($result['screen_name']) ?> </textarea>
      <div class="pull-right pad-left"><br>
    <button type="button" id="refresh-generated-reply" class="btn btn-default"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></button>
      </div>
      Response:<div id="generated-reply" style="overflow: auto;" class="well well-sm">-----
        <div id="reply-media"> 
        </div>
      </div> -->
      
   </div>
  </div>
</div>
    <div class="form-inline">


    <div class="form-group">
          <select class="form-control" id="public_source" name="public_source">
          <?php 
            $sharepossibilities = array(1 => "Share", 0 => "Don't share");

            foreach ($sharepossibilities as $sharevalue => $sharelabel) {
              echo('<option value="' . $sharevalue . '" '. ($result['public_source'] == $sharevalue ? 'selected' : '') .'>' . $sharelabel . '</option>');
            }
          ?> 
          </select> Tracery source at <a target="_blank" href="/source/<?php echo($result['screen_name']) ?>">cheapbotsdonequick.com/source/<?php echo($result['screen_name']) ?></a>.

        </div>
    <br>
    <button id="save-button" class="btn btn-default">Save</button>
    <div class="button form-group pull-right">
        <a class="btn  btn-default logout" href="logout.php"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span> Log Out</a>
    </div>    
    
</div>

</form>


	        


<!--
      <hr>

      <footer>
        <p>&copy; Company 2015</p>
      </footer>-->
    </div> <!-- /container -->        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/tracery.js"></script>
        <script src="js/twitter-text-1.9.4.min.js"></script>
        <script src="js/expanding.js"></script>
        <script src="js/json2.js"></script>
        <script src="js/jsonlint.js"></script>
        <script src="js/main.js"></script>
        <script type="text/javascript">var screen_name = "<?php echo($result['screen_name'])?>"</script>
<?php
if  (!is_null($result['last_error_code']) && $result['last_error_code'] == 326) {
  echo('<script type="text/javascript">window.onload = function() {unsaved = true;changeSaveButtonColour();};</script>');
}
?>
    </body>
</html>


