<?php
require_once("conexion.php");
require_once("../classes/classifier.php");
require_once("../classes/TweetSample.php");

$objClassifier = new Classifier();
$objClassifier->setTotals();

$objTS = new TweetSample();
$objTS->obtenerTestSet();
$correctas = 0;
$falsePositives = 0; // non documentaries taged as documentaries
$falseNegatives = 0; // documentaries tagged as non docuemntaries
$totalDocs = 0;

$total = 0;
for($i = 0; $i < $objTS->total; $i++){
	$objTS->ir($i);
	$result = $objClassifier->classify($objTS->stemmed);
	if($result == $objTS->clase){
		$correctas ++;
		//echo(" correcta ###");
	}else{
		if($objTS->clase == 1){
			// the tweet is tagged as documentary -> false negative
			$falseNegatives++;
			//echo(" docu ###");	
		}else{
			$falsePositives++;
			//echo(" Nodocu ###");
			}
	}
	$total++;
	$totalDocs += $objTS->clase; 
}

echo("$correctas tweet from $total where correctly classified. $falseNegatives documentaries tweets out of $totalDocs where classified as non documentary. $falsePositives non documentary tweet were classified as documentary");

closeConnection($conn);

?>