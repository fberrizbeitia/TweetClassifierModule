<?php
require_once("conexion.php");
require_once("../classes/dictionary.php");


$objDic = new dictionary($_GET["sampleSize"]);

$objDic->createBOW();

echo("Dictionary Size: ".count($objDic->bow));
$objDic->testReach(100);

$objDic->reduceDictionary(200,1);

echo("---------------------------------------------------------------------------------");
echo("---------------------- After Dictionary Reduction -------------------------------");
echo("---------------------------------------------------------------------------------");

echo("Dictionary Size: ".count($objDic->bow));
$objDic->testReach(100);

$objDic->saveToDB();


$objDic->parseSample();

closeConnection($conn);


?>