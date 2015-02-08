<?php
require_once("scripts/conexion.php");
require_once("classes/tuit.php");
require_once("classes/TweetSample.php");
require_once("classes/dictionary.php");


$objTuit = new Tuit();
$objTuit->obtenerTodos();

$objSample = new TweetSample();

$objSample->obtenerTodos();
$sampleSize = $objSample->total;

$objSample->obtenerSinEtiquetar();
$taggedTweets = $sampleSize - $objSample->total;

$objSample->obtenerSinDenoise();
$denoised = $sampleSize - $objSample->total;

$objDictionary = new dictionary(0);
$dictionarySize = $objDictionary->getDictionarySize();

$objSample->obtenerTrainingSet();
$trainingSetSize = $objSample->total;

?>
<div>
  <table width="400" border="0" align="center" cellpadding="5" cellspacing="0">
	  <tr>
	    <td width="133" align="left">Imported Tweets</td>
	    <td width="247" align="left"><?php echo($objTuit->total)?></td>
    </tr>
	  <tr>
	    <td align="left">Sample Size</td>
	    <td align="left"><?php echo($sampleSize)?></td>
    </tr>
	  <tr>
	    <td align="left">Tagged Tweets</td>
	    <td align="left"><?php echo($taggedTweets)?></td>
    </tr>
	  <tr>
	    <td align="left">Denoised Tweets</td>
	    <td align="left"><?php echo($denoised)?></td>
    </tr>
	  <tr>
	    <td align="left">Dictionary size</td>
	    <td align="left"><?php echo($dictionarySize)?></td>
    </tr>
	  <tr>
	    <td align="left">Training set size</td>
	    <td align="left"><?php echo($trainingSetSize)?></td>
    </tr>
	 
	  <tr>
	    <td align="left">Test Result (Presition)</td>
	    <td align="left">&nbsp;</td>
    </tr>
  </table>
</div>