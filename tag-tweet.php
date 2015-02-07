<link href="stiles.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="js/tag.js"></script>
<?php
	require_once("scripts/conexion.php");
	require_once("classes/tuit.php");
	require_once("classes/TweetSample.php");
	$objTuit = new Tuit();
	$objTS = new TweetSample();
	$objTS->obtenerSinEtiquetar();
	if($objTS->total > 0){
		$objTuit->obtenerPorID($objTS->idTuit);
	
	?>
	<div id="inner-cont">
		 <div id="titulo">
					<h1>First Pass<br>
					</h1>
					<div class="left-align-60">
					To be documentary a tweet must meet the followign criteria:<br>
					<ol>
						<li> Must provide information about an event that is happening at the moment of the post </li>
						<li> Must be well writen </li>
						<li> Must resemble a news paper headline </li>
						<li> Must answer some the W's (Who, What, When, Why, How) </li>
					</ol>
					</div>
		  </div>

		  <div>
			<form action="">
				<div id="tuit">
					<?php
						echo($objTuit->texto);
					?>
				</div>
				<p>
				  <label for="clase">Does the above tweet document an event ? </label>
				  <select name="clase" id="clase" >
					<option value="-1" selected="selected">Select</option>
					<option value="0">No</option>
					<option value="1">Yes</option>
				  </select>
				  <input type="hidden" name="idTuit" id="idTuit" value="<?php echo($objTuit->idTuit)?>">
                  <input type="hidden" name="pass" id="pass" value="1">
				  <input name="next" type="button" id="next" value="Next"/>
			  </p>
			</form>
		  </div>

	</div>
<?php
}

$objTS->obtenerSinEtiquetar2();
if($objTS->total > 0){
	$objTuit->obtenerPorID($objTS->idTuit);
?>
<!------------------------------------- SECOND PASS ----------------------------------------->


<div id="inner-cont">
		 <div id="titulo">
					<h1>Second Pass<br>
					</h1>
					<div class="left-align-60">
					To be documentary a tweet must meet the followign criteria:<br>
					<ol>
						<li> Must provide information about an event that is happening at the moment of the post </li>
						<li> Must be well writen </li>
						<li> Must resemble a news paper headline </li>
						<li> Must answer some the W's (Who, What, When, Why, How) </li>
					</ol>
					</div>
		  </div>

		  <div>
			<form action="">
				<div id="tuit">
					<?php
						echo($objTuit->texto);
					?>
				</div>
				<p>
				  <label for="clase">Does the above tweet document an event ? </label>
				  <select name="clase" id="clase" >
					<option value="-1" selected="selected">Select</option>
					<option value="0">No</option>
					<option value="1">Yes</option>
				  </select>
				  <input type="hidden" name="idTuit" id="idTuit" value="<?php echo($objTuit->idTuit)?>">
                  <input type="hidden" name="pass" id="pass" value="2">
				  <input name="next" type="button" id="next" value="Next"/>
			  </p>
			</form>
		  </div>

	</div>

<?php
}
?>

<!------------------------------------- FINAL PASS ----------------------------------------->

<?php
closeConnection($conn);
?>