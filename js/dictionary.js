// JavaScript Document

$(document).ready(function(){
	
	// FORM ACTIONS
	$( "#makeDIC" ).click(function() {
	
		if ( $( "#DICsize" ).val().length === 0 | !$.isNumeric($( "#DICsize" ).val()) )  {
			alert("The sample size cannot be empty or non numeric");
			event.preventDefault();
		} else {
			// Run $.ajax() here
			//alert ("Please be patient, this process may take several minutes to complete");
			$("#loader").show();
			$.ajax({
	 
				// The URL for the request
				url: "scripts/dictionary.php",
			 
				// The data to send (will be converted to a query string)
				data: {
					sampleSize: $( "#DICsize" ).val()
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
				
				complete: function( xhr, status){
					$("#loader").hide();
				}
			 
				}); //$.ajax({
			} // else
		}); //$( "#importQuery" ).submit(function( event ) {

});//$(document).ready(function(){
// JavaScript Document