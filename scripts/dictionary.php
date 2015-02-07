<?php
require_once("conexion.php");
require_once("../classes/dictionary.php");

$objDic = new dictionary($_GET["sampleSize"]);

/*
$objDic->createBOW();


echo("Tamaño del diccionario: ".count($objDic->bow)."<br>");
$objDic->testReach(100);

$objDic->reduceDictionary(200,1);

echo("---------------------------------------------------------------------------------<br>");
echo("---------------------- After Dictionary Reduction -------------------------------<br>");
echo("---------------------------------------------------------------------------------<br>");

echo("Tamaño del diccionario: ".count($objDic->bow)."<br>");
$objDic->testReach(100);

$objDic->saveToDB();

*/

$objDic->parseSample();

closeConnection($conn);


?>