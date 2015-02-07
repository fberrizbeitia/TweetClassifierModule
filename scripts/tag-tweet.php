<?php	
	
	require_once("conexion.php");
	require_once("../classes/TweetSample.php");
	
	$objTS = new TweetSample();
	$objTS->obtenerPorID($_GET["idTuit"]);
	
	$pas = $_GET["pass"];
	echo("pas = $pas");
	
	if($_GET["pass"] == 1){
		$objTS->clase = $_GET["clase"];
	}else{
		if($_GET["pass"] == 2){
			$objTS->clase2 = $_GET["clase"];
		}else{
			$objTS->clase_def = $_GET["clase"];
			}
	}
	$objTS->guardar();
	
	closeConnection($conn);
		
?>