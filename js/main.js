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


$(window).bind('beforeunload', function(e){
	if (unsaved) return "This page is asking you to confirm that you want to leave - data you have entered may not be saved";
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


var tweet; //global so we can see it when we press the tweet button
var generate = function()
{
	var startGenerate = _.now();
	var string = $('textarea#tracery').val();
	try{
		var parsed = jQuery.parseJSON(string);
		try
		{

			$('#tracery-validator').addClass('hidden').text("Parsed successfully");


			var processedGrammar = tracery.createGrammar(parsed);

			processedGrammar.addModifiers(tracery.baseEngModifiers);
			tweet = processedGrammar.flatten("#origin#");
			var media = matchBrackets(tweet);


			
			var just_text_tweet = removeBrackets(tweet);
			$('#generated-tweet').html(nl2br(_.escape(just_text_tweet)) + "<div id=\"tweet-media\"></div>");

			if (twttr.txt.getTweetLength(just_text_tweet) > 140)
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

				if (media.indexOf("svg ") === 1)
				{
					var actualSVG = media.substr(5,media.length - 6);

					var parser = new DOMParser();
					var doc = parser.parseFromString(actualSVG, "image/svg+xml");
					

				    validateSVG(doc, actualSVG);


					$('#tweet-media').append("<div class=\"svg-media\">" + actualSVG + "</div>");
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



			$('#tracery-validator').removeClass('hidden').text("Tracery parse error: " + _.escape(e));
			valid = false;
		}
	}
	catch (e) {


		try {
			var result = jsonlint.parse(string);
			if (result) {
				//valid via jsonlint?!
				$('#tracery-validator').removeClass('hidden').text("Unknown JSON parse error: " + _.escape(e));
			}
		} catch(e) {
			$('#tracery-validator').removeClass('hidden').html("JSON parse error:  <pre>" + _.escape(e) + "</pre>");
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

var validateSVG = function(doc, actualSVG)
{
	var parser = new DOMParser();
	var parsererrorNS = parser.parseFromString('INVALID', 'text/xml').getElementsByTagName("parsererror")[0].namespaceURI;


    if (doc.documentElement.getAttribute("width") === null)
    {
    	$('#tracery-validator').removeClass('hidden').html("SVG element must specify a <code>width</code>");
    }
    if (doc.documentElement.getAttribute("height") === null)
    {
    	$('#tracery-validator').removeClass('hidden').html("SVG element must specify a <code>height</code>");
    }
/*
    if (doc.documentElement.getAttribute("xmlns") === null)
    {
    	$('#tracery-validator').removeClass('hidden').html("SVG element should probably specify a <code>xmlns</code> attribute.");
    }
    if (doc.documentElement.getAttribute("xmlns:xlink") === null)
    {
    	$('#tracery-validator').removeClass('hidden').html("SVG element should probably specify a <code>xmlns:xlink</code> attribute.");
    }*/


	if(doc.getElementsByTagNameNS(parsererrorNS, 'parsererror').length > 0) {

	var excerpt = "";
	//chrome
	var bracketsRe = /line (\d+) at column (\d+)/;
	var errorText = new XMLSerializer().serializeToString(doc.documentElement);
	var matches = errorText.match(bracketsRe);
	if(matches !== null) {
	var line = matches[1];
	var col = matches[2];
		excerpt = excerptAtLineCol(actualSVG, matches[1] - 1, matches[2] - 1, 1);
	}

	


        $('#tracery-validator').removeClass('hidden').html("SVG parsing error<br><pre>" + _.escape(excerpt) + "</pre><span class=\"parsererror\">" + nl2br(doc.getElementsByTagName('parsererror')[0].innerHTML) + "</span>");
    }

}

//from https://github.com/smallhelm/excerpt-at-line-col/blob/master/index.js

var excerptAtLineCol = function(text, line_n, col_n, n_surrounding_lines){
  n_surrounding_lines = n_surrounding_lines || 0;

  return text.split("\n").map(function(line, line_i){
    return {
      line: line,
      line_n: line_i
    };
  }).filter(function(l){
    return Math.abs(l.line_n - line_n) <= n_surrounding_lines;
  }).map(function(l){
    if(l.line_n !== line_n){
      return l.line;
    }
    var col_position_whitespace = '';
    var j;
    for(j=0; j<Math.min(col_n, l.line.length); j++){
      col_position_whitespace += l.line[j].replace(/[^\s]/g, " ");
    }
    return l.line + "\n" + col_position_whitespace + '^';
  }).join("\n");
};


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
	  data : {"tweet": tweet},
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

