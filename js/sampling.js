// JavaScript Document

$(document).ready(function(){
	
	// FORM ACTIONS
	$( "#makeSRS" ).click(function() {
	
		if ( $( "#SRSsize" ).val().length === 0 | !$.isNumeric($( "#SRSsize" ).val()) )  {
			alert("The sample size cannot be empty or non numeric");
			event.preventDefault();
		} else {
			// Run $.ajax() here
			$.ajax({
	 
				// The URL for the request
				url: "scripts/sampling.php",
			 
				// The data to send (will be converted to a query string)
				data: {
					SRSsize: $( "#SRSsize" ).val()
				},
			 
				// Whether this is a POST or GET request
				type: "GET",
			 
				// Code to run if the request succeeds;
				// the response is passed to the function
				success: function( data ) {
					alert( "Sample Created !" );
				},
			 
				// Code to run if the request fails; the raw request and
				// status codes are passed to the function
				error: function( xhr, status, errorThrown ) {
					alert( "Sorry, there was a problem!" );
					console.log( "Error: " + errorThrown );
					console.log( "Status: " + status );
					console.dir( xhr );
				},
			 
				}); //$.ajax({
			} // else
		}); //$( "#importQuery" ).submit(function( event ) {

});//$(document).ready(function(){
// JavaScript Document