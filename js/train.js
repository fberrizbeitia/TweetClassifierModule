// JavaScript Document

$(document).ready(function(){
	
	// FORM ACTIONS
	$( "#Train" ).click(function() {
	
		if ( $( "#size" ).val().length === 0 | !$.isNumeric($( "#size" ).val()) | $("#size").val < 10 | $("#size").val > 99  )  {
			alert("The training set size cannot be empty, non numeric, too small or too big");
			event.preventDefault();
		} else {
			// Run $.ajax() here
			//alert ("Please be patient, this process may take several minutes to complete");
			$("#loader").show();
			$.ajax({
	 
				// The URL for the request
				url: "scripts/train.php",
			 
				// The data to send (will be converted to a query string)
				data: {
					size: $( "#size" ).val()
				},
			 
				// Whether this is a POST or GET request
				type: "GET",
			 
				// Code to run if the request succeeds;
				// the response is passed to the function
				success: function( data ) {
					alert( "Training finished" );
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