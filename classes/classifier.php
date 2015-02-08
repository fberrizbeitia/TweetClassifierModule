<?php
/*
Implementation of the Naïve Bayes Classifier
*/

class Classifier {
	
	function generateTrainingSet($size){
	/*
	Description: Takes a SRS of size $size form sample and tag those as parte of the traing Set
	*/	
	
	$sql = "UPDATE sample SET trainingSet = 0";
	mysql_query($sql) or die("Classifier->generateTrainingSet_1: error en consulta".mysql_error()."SQL: ".$sql);
	
	$sizeNormalized = $size/100; 
	
	$sql = "UPDATE sample SET trainingSet = 1 WHERE RAND() < $sizeNormalized";
	mysql_query($sql) or die("Classifier->generateTrainingSet_1: error en consulta".mysql_error()."SQL: ".$sql);
	
	}
	
	function train(){
		//primero los no documentales
		$sql = "SELECT bow.idWord,COUNT(bow.idWord) as total from bow,sample WHERE bow.idTuit = sample.idTuit and sample.trainingSet  = 1 and sample.class = 0 GROUP BY idWord";
		$result = mysql_query($sql) or die("Classifier->train: error en consulta".mysql_error()."SQL: ".$sql);
		
		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
    		$idWord = $row[0]; 
			$count = $row[1]; 
			$sql = "UPDATE dictionary SET n_nondoc = $count WHERE idWord = $idWord";
			mysql_query($sql) or die("Classifier->train_2: error en consulta".mysql_error()."SQL: ".$sql);
		}
		
		//luego los no documentales
		$sql = "SELECT bow.idWord,COUNT(bow.idWord) as total from bow,sample WHERE bow.idTuit = sample.idTuit and sample.trainingSet  = 1 and sample.class = 1 GROUP BY idWord";
		$result = mysql_query($sql) or die("Classifier->train_3: error en consulta".mysql_error()."SQL: ".$sql);
		
		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
    		$idWord = $row[0]; 
			$count = $row[1]; 
			$sql = "UPDATE dictionary SET n_doc = $count WHERE idWord = $idWord";
			mysql_query($sql) or die("Classifier->train_4: error en consulta".mysql_error()."SQL: ".$sql);
		}
	
	}

}
