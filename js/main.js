$('#tracery').bind('input propertychange', function() {
	generate();
	if (generateTime < 1100)
	{
		_.throttle(generate, generateTime, {leading: false});
	}
	unsaved = true;
	changeSaveButtonColour();
});

$('#frequency').change(function() {
	unsaved = true;
	changeSaveButtonColour();
});


$('#public_source').change(function() {
	unsaved = true;
	changeSaveButtonColour();
});


$( "#refresh-generated-tweet" ).bind( "click", function() {
  generate();
});


$(window).load(function() {
	if (tracery.createGrammar)
	{
		generate();
	}
	else
	{
		_.defer(generate, 500);
	}
  
});

var valid = true;

nl2br = function (str, is_xhtml) {   
	var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
	return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
};


// this is much more complex than i thought it would be
// but this function will find our image tags 
// full credit to BooDooPerson - https://twitter.com/BooDooPerson/status/683450163608817664
// Reverse the string, check with our fucked up regex, return null or reverse matches back
var matchBrackets = function(text) {
  
  // simple utility function
  function reverseString(s) {
    return s.split('').reverse().join('');
  }

  // this is an inverstion of the natural order for this RegEx:
  var bracketsRe = /(\}(?!\\)(.+?)\{(?!\\))/g;

  text = reverseString(text);
  var matches = text.match(bracketsRe);
  if(matches === null) {
    return null;
  }
  else {
    return matches.map(reverseString).reverse();
  }
}


//see matchBrackets for why this is like this
function removeBrackets (text) {
  
  // simple utility function
  var reverseString = function(s) {
    return s.split('').reverse().join('');
  }

  // this is an inverstion of the natural order for this RegEx:
  var bracketsRe = /(\}(?!\\)(.+?)\{(?!\\))/g;

  text = reverseString(text);
  return reverseString(text.replace(bracketsRe, ""));
}


var generate = function()
{
	var parser = new DOMParser();
    var parsererrorNS = parser.parseFromString('INVALID', 'text/xml').getElementsByTagName("parsererror")[0].namespaceURI;

	var startGenerate = _.now();
	var string = $('textarea#tracery').val();
	try{
		var parsed = jQuery.parseJSON(string);
		try
		{

			$('#tracery-validator').addClass('hidden').text("Parsed successfully");


			var processedGrammar = tracery.createGrammar(parsed);

			processedGrammar.addModifiers(tracery.baseEngModifiers);
			var tweet = processedGrammar.flatten("#origin#");
			var media = matchBrackets(tweet);


			
			tweet = removeBrackets(tweet);
			tweet = _.escape(tweet);
			$('#generated-tweet').html(nl2br(tweet) + "<div id=\"tweet-media\"></div>");

			if (twttr.txt.getTweetLength(tweet) > 140)
			{
				$('#generated-tweet').addClass('too-long');

				$('#tweet-generated-tweet').attr('disabled','disabled').addClass('disabled');
			}
			else
			{
				$('#generated-tweet').removeClass('too-long');
				$('#tweet-generated-tweet').removeAttr('disabled').removeClass('disabled');
			}
 

			_.each(media, function(media){

				$('#tweet-generated-tweet').attr('disabled','disabled').addClass('disabled');
				if (media.indexOf("svg ") === 1)
				{
					var actualSVG = media.substr(5,media.length - 6);
					var doc = parser.parseFromString(actualSVG, "image/svg+xml");
					if(doc.getElementsByTagNameNS(parsererrorNS, 'parsererror').length > 0) {
				        $('#tracery-validator').removeClass('hidden').html("SVG parsing error<br>" + nl2br(doc.getElementsByTagName('parsererror')[0].innerHTML));
				    }


					$('#tweet-media').append("<div class=\"svg-media\">" + actualSVG + "</div>");
					if (media.indexOf("viewBox") == -1)
					{
						$('#tracery-validator').removeClass('hidden').text("SVGs should specify a viewBox attribute");
					}
				}
				else if (media.indexOf("img ") === 1)
				{
					fetch_img, media.substr(5)
				}
				else
				{
					$('#tracery-validator').removeClass('hidden').text("Unknown media type " + media.substr(1,4));
				}
			});

			valid = true;

		}
		catch (e)
		{



			$('#tracery-validator').removeClass('hidden').text("Tracery parse error: " + e);
			valid = false;
		}
	}
	catch (e) {


		try {
			var result = jsonlint.parse(string);
			if (result) {
				//valid via jsonlint?!
				$('#tracery-validator').removeClass('hidden').text("Unknown JSON parse error: " + e);
			}
		} catch(e) {
			$('#tracery-validator').removeClass('hidden').html("JSON parse error:  <pre>" + e + "</pre>");
		}

		valid = false;
	}

	$('#save-button').toggleClass('disabled', !valid);
	generateTime = _.now() - startGenerate + 100;
};
var generateTime = 100;
var unsaved = false;

$( window ).unload(function() {
	if (unsaved)
	{
		return "Unsaved changes";
	}
});

var changeSaveButtonColour = function()
{
	if (unsaved) $('#save-button').removeClass('btn-default').addClass('btn-primary');
	else $('#save-button').removeClass('btn-primary').addClass('btn-default');
};

$('#tweet-generated-tweet').click(function()
{
	$.ajax({
	  url: "tweet.php",
	  method : "POST",
	  data : {"tweet": $('#generated-tweet').text()},
	  dataType: "json"	  
	})
	  .done(function( data ) {
		if (data.hasOwnProperty('success') && data['success'])
		{

			$('#tweet-generated-tweet').attr('disabled','disabled').addClass('disabled');
			$('#tracery-validator').addClass('hidden');
		}
		else {
			$('#tracery-validator').removeClass('hidden').text("Failed to tweet: " + (data.hasOwnProperty('reason') && data['reason']));
		}
	  })
	  .fail( function( jqXHR, textStatus ) {
			$('#tracery-validator').removeClass('hidden').text("Failed to tweet: " + textStatus);
		});
});



$( "#tracery-form" ).submit(function( event ) {
  event.preventDefault();
  if (valid)
  {
	var freq = $('#frequency').val();
	var tracery = $('#tracery').val();
	var public_source = $('#public_source').val();
	$.ajax({
	  url: "update.php",
	  method : "POST",
	  data : {"frequency": freq , "tracery" : tracery, "public_source" : public_source},
	  dataType: "json"
	})
	  .done(function( data ) {
		if (data.hasOwnProperty('success') && data['success'])
		{
			$('#tracery-validator').addClass('hidden');
			unsaved = false;
			changeSaveButtonColour();
		}
		else {
			$('#tracery-validator').removeClass('hidden').text("Failure uploading: " + (data.hasOwnProperty('reason') && data['reason']));
		}
	  }) 
	  .fail( function( jqXHR, textStatus ) {
			$('#tracery-validator').removeClass('hidden').text("Failure uploading: " + textStatus);
		});
	
  }
});

$(document).delegate('#tracery', 'keydown', function(e) {
  var keyCode = e.keyCode || e.which;

  if (keyCode == 9) {
	e.preventDefault();
	var start = $(this).get(0).selectionStart;
	var end = $(this).get(0).selectionEnd;

	// set textarea value to: text before caret + tab + text after caret
	$(this).val($(this).val().substring(0, start)
				+ "\t"
				+ $(this).val().substring(end));

	// put caret at right position again
	$(this).get(0).selectionStart =
	$(this).get(0).selectionEnd = start + 1;
  }
});

