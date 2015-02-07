<?php
require_once("conexion.php");
require_once("../classes/TweetSample.php");

$objSample = new TweetSample();
$objSample->cleanAndSaveTweets();
closeConnection($conn);

?>