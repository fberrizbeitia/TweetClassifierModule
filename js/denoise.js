// JavaScript Document

$(document).ready(function(){
	
	// FORM ACTIONS
	$( "#Denoise" ).click(function() {
			$("#loader").show();
			$("#titulo").hide();
			// Run $.ajax() here
			$.ajax({
	 
				// The URL for the request
				url: "scripts/denoise.php",
			 
				// The data to send (will be converted to a query string)
				data: {
					
				},
			 
				// Whether this is a POST or GET request
				type: "GET",
			 
				// Code to run if the request succeeds;
				// the response is passed to the function
				success: function( data ) {
					$("#loader").hide();
					$("#titulo").show();
					alert( "Noise removal was performed sucessfuly over the sample set" );
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

		}); //$( "#importQuery" ).submit(function( event ) {

});//$(document).ready(function(){
// JavaScript Document