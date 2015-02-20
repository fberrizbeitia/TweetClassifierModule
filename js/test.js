// JavaScript Document

$(document).ready(function(){
	
	// FORM ACTIONS
	$( "#BTN_test" ).click(function() {
	
		if ( $( "#tweet" ).val().length === 0)  {
			alert("The tweet cannot be empty");
			event.preventDefault();
		} else {
			// Run $.ajax() here
			$.ajax({
	 
				// The URL for the request
				url: "scripts/test.php",
			 
				// The data to send (will be converted to a query string)
				data: {
					tweet: $( "#tweet" ).val()
				},
			 
				// Whether this is a POST or GET request
				type: "GET",
			 
				// Code to run if the request succeeds;
				// the response is passed to the function
				success: function( data ) {
					alert( data );
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
			
			
		$( "#BTN_test_set" ).click(function() {
			$("#loader").show();
		// Run $.ajax() here
			$.ajax({
	 
				// The URL for the request
				url: "scripts/testSet.php",
			 
				// The data to send (will be converted to a query string)
				data: {
					
				},
			 
				// Whether this is a POST or GET request
				type: "GET",
			 
				// Code to run if the request succeeds;
				// the response is passed to the function
				success: function( data ) {
					$("#loader").hide();
					alert( data );
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