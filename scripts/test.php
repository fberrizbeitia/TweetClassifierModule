<?php
require_once("conexion.php");
require_once("../classes/classifier.php");
require_once("../classes/TweetSample.php");

$objClassifier = new Classifier();
$objClassifier->setTotals();

$objTS = new TweetSample();
$result = $objClassifier->classify($objTS->denoiseTweet($_GET["tweet"]));

if($result == 1){
	echo("The tweet is documentary");
}else{
	echo("The tweet is non documentary");
	}

closeConnection($conn);

?>