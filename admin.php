<?php

require "credentials.php";


session_set_cookie_params(2678000);
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != ADMIN_USER_ID)
{
  die();
}

/**
 * Input: HSL in format Deg, Perc, Perc
 * Output: An array containing HSL in ranges 0-1
 * 
 * Divides $h by 60, and $s and $l by 100.
 * 
 * hslToRgb calls this by default.
*/
function degPercPercToHsl($h, $s, $l) { 
  //convert h, s, and l back to the 0-1 range

  //convert the hue's 360 degrees in a circle to 1
  $h /= 360;

  //convert the saturation and lightness to the 0-1 
  //range by multiplying by 100
  $s /= 100;
  $l /= 100;

  $hsl['h'] =  $h;
  $hsl['s'] = $s;
  $hsl['l'] = $l;

  return $hsl;
}

/**
 * Converts an HSL hue to it's RGB value.  
 *
 * Input: $temp1 and $temp2 - temperary vars based on 
 * whether the lumanence is less than 0.5, and 
 * calculated using the saturation and luminence
 * values.
 *  $hue - the hue (to be converted to an RGB 
 * value)  For red, add 1/3 to the hue, green 
 * leave it alone, and blue you subtract 1/3 
 * from the hue.
 *
 * Output: One RGB value.
 *
 * Thanks to Easy RGB for this function (Hue_2_RGB).
 * http://www.easyrgb.com/index.php?X=MATH&$h=19#text19
 *
*/
function hueToRgb($temp1, $temp2, $hue) {
  if ($hue < 0) { 
      $hue += 1;
  }
  if ($hue > 1) {
      $hue -= 1;
  }

  if ((6 * $hue) < 1 ) {
      return ($temp1 + ($temp2 - $temp1) * 6 * $hue);
  } elseif ((2 * $hue) < 1 ) {
      return $temp2;
  } elseif ((3 * $hue) < 2 ) {
      return ($temp1 + ($temp2 - $temp1) * ((2 / 3) - $hue) * 6);
  }
  return $temp1;
}

/**
 * Converts an HSL color value to RGB. Conversion formula
 * adapted from http://www.niwa.nu/2013/05/math-behind-colorspace-conversions-rgb-hsl/.
 * Assumes h, s, and l are in the format Degrees,
 * Percent, Percent, and returns r, g, and b in 
 * the range [0 - 255].
 *
 * Called by hslToHex by default.
 *
 * Calls: 
 *   degPercPercToHsl
 *   hueToRgb
 *
 * @param   Number  h       The hue value
 * @param   Number  s       The saturation level
 * @param   Number  l       The luminence
 * @return  Array           The RGB representation
 */
function hslToRgb($h, $s, $l){
  $hsl = degPercPercToHsl($h, $s, $l);
  $h = $hsl['h'];
  $s = $hsl['s'];
  $l = $hsl['l'];

  //If there's no saturation, the color is a greyscale,
  //so all three RGB values can be set to the lightness.
  //(Hue doesn't matter, because it's grey, not color)
  if ($s == 0) {
      $r = $l * 255;
      $g = $l * 255;
      $b = $l * 255;
  }
  else {
      //calculate some temperary variables to make the 
      //calculation eaisier.
      if ($l < 0.5) {
          $temp2 = $l * (1 + $s);
      } else {
          $temp2 = ($l + $s) - ($s * $l);
      }
      $temp1 = 2 * $l - $temp2;

      //run the calculated vars through hueToRgb to
      //calculate the RGB value.  Note that for the Red
      //value, we add a third (120 degrees), to adjust 
      //the hue to the correct section of the circle for
      //red.  Simalarly, for blue, we subtract 1/3.
      $r = 255 * hueToRgb($temp1, $temp2, $h + (1 / 3));
      $g = 255 * hueToRgb($temp1, $temp2, $h);
      $b = 255 * hueToRgb($temp1, $temp2, $h - (1 / 3));
  }

  $rgb['r'] = $r;
  $rgb['g'] = $g;
  $rgb['b'] = $b;

  return $rgb;
}

/**
 * Converts HSL to Hex by converting it to 
 * RGB, then converting that to hex.
 * 
 * string hslToHex($h, $s, $l[, $prependPound = true]
 * 
 * $h is the Degrees value of the Hue
 * $s is the Percentage value of the Saturation
 * $l is the Percentage value of the Lightness
 * $prependPound is a bool, whether you want a pound 
 *  sign prepended. (optional - default=true)
 *
 * Calls: 
 *   hslToRgb
 *
 * Output: Hex in the format: #00ff88 (with 
 * pound sign).  Rounded to the nearest whole
 * number.
*/
function hslToHex($h, $s, $l, $prependPound = true) {
  //convert hsl to rgb
  $rgb = hslToRgb($h,$s,$l);

  //convert rgb to hex
  $hexR = $rgb['r'];
  $hexG = $rgb['g'];
  $hexB = $rgb['b'];

  //round to the nearest whole number
  $hexR = round($hexR);
  $hexG = round($hexG);
  $hexB = round($hexB);

  //convert to hex
  $hexR = dechex($hexR);
  $hexG = dechex($hexG);
  $hexB = dechex($hexB);

  //check for a non-two string length
  //if it's 1, we can just prepend a
  //0, but if it is anything else non-2,
  //it must return false, as we don't 
  //know what format it is in.
  if (strlen($hexR) != 2) {
      if (strlen($hexR) == 1) {
          //probably in format #0f4, etc.
          $hexR = "0" . $hexR;
      } else {
          //unknown format
          return false;
      }
  }
  if (strlen($hexG) != 2) {
      if (strlen($hexG) == 1) {
          $hexG = "0" . $hexG;
      } else {
          return false;
      }
  }
  if (strlen($hexB) != 2) {
      if (strlen($hexB) == 1) {
          $hexB = "0" . $hexB;
      } else {
          return false;
      }
  }

  //if prependPound is set, will prepend a
  //# sign to the beginning of the hex code.
  //(default = true)
  $hex = "";
  if ($prependPound) {
      $hex = "#";
  }

  $hex = $hex . $hexR . $hexG . $hexB;

  return $hex;
}

function stringToColor($str) {
  $hash = md5($str);
  $hue = hexdec(substr($hash, 0, 6))/hexdec("ffffff");
  $sat = hexdec(substr($hash, 6, 6))/hexdec("ffffff");
  $val = hexdec(substr($hash, 12, 6))/hexdec("ffffff");
  return hslToHex($hue * 360, 40 + $sat * 30, 60 + $val * 30);
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
      <td style=\"background-color:". stringToColor($value['last_ip']) ."\">{$value['last_ip']}</td>
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


