// JavaScript Document

$(document).ready(function(){

	$("#next").click(function(){
		if($("#clase").val() >= 0){
			$.ajax({
				url: "scripts/tag-tweet.php",
				data: {
					idTuit: $( "#idTuit" ).val(),  
					clase: $( "#clase" ).val(),
					pass: $("#pass").val()
				},
				type: "GET",
				success: function( data ) {
					//alert(data);
					$("#cuerpo").load("tag-tweet.php");
				},

				error: function( xhr, status, errorThrown ) {
					alert( "Sorry, there was a problem!" );
					console.log( "Error: " + errorThrown );
					console.log( "Status: " + status );
					console.dir( xhr );
				},
			 
				}); //$.ajax(
		}else{
			alert("Please select for the Tweet");
		}
	});
	
}); //$(document).ready(function(){
