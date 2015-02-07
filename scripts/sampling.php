<?php
require_once("conexion.php");
require_once("../classes/TweetSample.php");

$objSample = new TweetSample();
$objSample->generateMainSample($_GET["SRSsize"]);
closeConnection($conn);
?>