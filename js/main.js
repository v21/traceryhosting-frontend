$('#tracery').bind('input propertychange', function() {
	generate();
	unsaved = true;
	changeSaveButtonColour();
});

$('#frequency').change(function() {
	generate();
	unsaved = true;
	changeSaveButtonColour();
});

$( "#refresh-generated-tweet" ).bind( "click", function() {
  generate();
});


$(window).load(function() {
  generate();

});

var valid = true;

var generate = function()
{

	var string = $('textarea#tracery').val();
	try{
		var parsed = jQuery.parseJSON(string);
		try
		{

			$('#tracery-validator').addClass('hidden').text("Parsed successfully");


			var processedGrammar = tracery.createGrammar(parsed);

			var tweet = processedGrammar.flatten("#origin#");
			$('#generated-tweet').text(tweet);
			valid = true;

		}
		catch (e)
		{
			$('#tracery-validator').removeClass('hidden').text("Tracery parse error: " + e);
			valid = false;
		}
	}
	catch (e) {
		$('#tracery-validator').removeClass('hidden').text("JSON parse error: " + e);
		valid = false;
	}

	$('#save-button').toggleClass('disabled', !valid);
};

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

$( "#tracery-form" ).submit(function( event ) {
  event.preventDefault();
  if (valid)
  {
  	var freq = $('#frequency').val();
  	var tracery = $('#tracery').val();
	$.ajax({
	  url: "update.php",
	  method : "POST",
	  data : {"frequency": freq , "tracery" : tracery},
	  dataType: "json",
	  beforeSend: function( xhr ) {
	    xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
	  }
	})
	  .done(function( data ) {
	    if (data.hasOwnProperty('success') && data['success'])
	    {
			$('#tracery-validator').addClass('hidden');
			unsaved = false;
			changeSaveButtonColour();
	    }
	    else {
	    	$('#tracery-validator').removeClass('hidden').text("Failure uploading");
	    }
	  })
	  .fail( function( jqXHR, textStatus ) {
	    	$('#tracery-validator').removeClass('hidden').text("Failure uploading: " + textStatus);
		});
  	
  }
});