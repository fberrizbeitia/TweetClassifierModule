<?php
require_once("conexion.php");
require_once("../classes/classifier.php");

$objClassifier = new Classifier();
$objClassifier->generateTrainingSet($_GET["size"]);
$objClassifier->train();

closeConnection($conn);

?>