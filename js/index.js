// JavaScript Document

$(document).ready(function(){
	
	$("#cuerpo").load("dashboard.php")
	// MENU ACTIONS
	$("#dashboard" ).click(function() {
    	$("#cuerpo").load("dashboard.php")
	});
	
	$("#import-tweets" ).click(function() {
    	$("#cuerpo").load("import-tweets.php")
	});
	
	$("#sampling" ).click(function() {
    	$("#cuerpo").load("sampling.php")
	});
	
	$("#tag" ).click(function() {
    	$("#cuerpo").load("tag-tweet.php")
	});
	
	$("#denoise" ).click(function() {
    	$("#cuerpo").load("denoise.php")
	});


});