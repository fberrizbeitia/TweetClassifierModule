<?php

class dictionary{
	
	var $sampleSize; //number of tweets to sample to create de Bag of Words
	var $bow; // array with the words in the dictionary
	
	public function __construct($size){
		$this->sampleSize = $size;	
	}
	
	
	private function inDictionary($word){
		$result = false;
		$stop = false;
		$cont = 0;
		
		while(!$stop){
			if(count($this->bow ) > 0){
				if(strcasecmp($word,$this->bow[$cont]) == 0){
					$stop = true;
					$result = true;
				}
			}			
			if($cont >= count($this->bow)-1){
				$stop = true;
			}
			$cont++;
		}
		
		return($result);	
	}
	
	private function is_valid($word){
		$result = true;
		$forbiden = array("-","—-","---","_","__","___"," ","..","...");
		for($i = 0; $i < count($forbiden); $i++){
			if(stripos($word,$forbiden[$i]) !== false){
				$result = false;
			}
		}
		return $result;
	}
	
	public function createBOW(){
		// **********************************************************************************
		// This method with take a Simple Random Sample of size $this->sampleSize 
		// And create a list in $this->bow with the unique words of the stemmed version 
		// of each Tweet
		//***********************************************************************************
	
		$sql = "SELECT stemmed FROM sample ORDER BY RAND() LIMIT 0,$this->sampleSize";
		$result = mysql_query($sql) or die("dictionary->createBOG: error en consulta".mysql_error()."SQL: ".$sql);
		
		$this->bow = array();
		$cont = 0;
		for($i = 0; $i < mysql_num_rows($result); $i++){
			$tuit = explode(" ",mysql_result($result,$i,"stemmed"));
			for($j = 0; $j < count($tuit);$j++){
				$word = preg_replace('/\s+/', '', $tuit[$j]);
				if(!is_numeric($word) and (strlen($word) > 1) and $this->is_valid($word)){
					if(!$this->inDictionary($word)){
						$this->bow[$cont] = $word;
						$cont++;
					}
				}//if(!is_numeric($tuit[$j]) and (c$wordount($tuit[$j]) > 1)){
			}//for($j = 0; $j < count($tuit);$j++){
		}//for($i = 0; $i < mysql_num_rows($result); $i++){
		
	}//public function createBOW(){
	
	
	private function countOccurences($word,$array){
		$occurences = 0;
		
		for ($i = 0; $i < count($array); $i++){
			if(strcasecmp($array[$i], $word) == 0){
				$occurences++;
			}
		}
		//echo($word.": ".$occurences."<br>");
		return $occurences; 
	}
	
	public function reduceDictionary($sampleSize, $coverage){
		// INPUT: SampleSize. Size of the SRS to perform the tests 
		//        Minimum coverage. Minimum amount of times a word has to appear in the test set to be considered valid
		// RETURN: Percentage of word covered of the sample by the dictionary
		
		$sql = "SELECT stemmed FROM sample ORDER BY RAND() LIMIT 0,$sampleSize";
		$result = mysql_query($sql) or die("dictionary->reduceDictionary: error en consulta".mysql_error()."SQL: ".$sql);
		
		$reduceDic = array();
		$cont = 0;
		$testPool = array();
		
		for($i = 0; $i < mysql_num_rows($result); $i++){
			$words =  explode(" ",mysql_result($result,$i,"stemmed"));
			$testPool = array_merge($testPool,$words); 
		}
		
		for($i = 0; $i < count($this->bow);$i++){
			$occurences = $this->countOccurences($this->bow[$i],$testPool);
			if($occurences >= $coverage){
				$reduceDic[$cont] = $this->bow[$i];
				$cont++;
			}
		}
		
		$this->bow = $reduceDic;
	}
	
	function testReach($sampleSize){
		$sql = "SELECT stemmed FROM sample ORDER BY RAND() LIMIT 0,$sampleSize";
		$result = mysql_query($sql) or die("dictionary->testReach: error en consulta".mysql_error()."SQL: ".$sql);
		
		$coveredTweets = 0;
		$wellCoveredTweets = 0;
		
		$wordsCovered = 0;
		$totalWords= 0;
		$wordCoveredPerTweet = array();
		
		for($i = 0; $i < mysql_num_rows($result); $i++){
			$text = mysql_result($result,$i,"stemmed");
			$words = explode(" ",$text);
			$wordsInDictionary = 0;
			for($j = 0; $j < count($words);$j++){
				if($this->inDictionary($words[$j])){	
					$wordsInDictionary++;
				}
			}
			if($wordsInDictionary >= 1){
				$coveredTweets++;
				if($wordsInDictionary >= 2){
					$wellCoveredTweets++;
				}
			}
			$wordsCovered += $wordsInDictionary;
			$totalWords += count($words);
			$wordCoveredPerTweet[] = $wordsInDictionary/count($words);

		}//for($i = 0; $i < mysql_num_rows($result); $i++)t{
		 
		 echo("Covered Tweets: ".$coveredTweets."<br>");
		 echo("Well Covered Tweets: ".$wellCoveredTweets."<br>");
		 echo("Covered Words: ".$wordsCovered."<br>");
		 echo("Total Words: ".$totalWords."<br>");
		 echo("Average Coverture: ".$wordsCovered/$totalWords."<br>");
				 		
		 return $coveredTweets;
	}
	

	public function saveToDB(){
		//--- empty the table
		$sql = "TRUNCATE dictionary";
		mysql_query($sql) or die("dictionary->saveToDB_1: error en consulta".mysql_error()."SQL: ".$sql);
		
		//-- add the fixed fields (hastags,mentions,links,media)
		$sql = "INSERT INTO dictionary (word) VALUES ('hashtags'),('mentions'),('links'),('media')";
		mysql_query($sql) or die("dictionary->saveToDB_1: error en consulta".mysql_error()."SQL: ".$sql);
		
		for($i = 0;$i < count($this->bow); $i++){					
			$sql = "INSERT INTO dictionary (word) VALUES ('".$this->bow[$i]."')";	
			mysql_query($sql) or die("dictionary->saveToDB_3: error en consulta".mysql_error()."SQL: ".$sql);
		}
		
	}
	
	private function dictionaryPos($word){
	// Returns the position of word in the dictionary or -1 if he word is nor on the dictionary
	
		$stop = false;
		$cont = 0;
		
		while(!$stop){
			if(count($this->bow ) > 0){
				if(strcasecmp($word,$this->bow[$cont]) == 0){
					$stop = true;
				}
			}			
			if($cont >= count($this->bow)-1){
				$stop = true;
				$cont = -2;
			}
			$cont++;
		}
		
		return($cont);
	}
	
	public function parseSample(){
		$sql = "SELECT DISTINCT sample.stemmed,sample.idTuit, tuits.menciones, tuits.hashtags, tuits.url, tuits.media FROM tuits, sample WHERE sample.idTuit = tuits.idTuit";
		$sample = mysql_query($sql) or die("dictionary->parseSample_1: error en consulta".mysql_error()."SQL: ".$sql);
		
		// get the dictionary from the DB
		$sql = "SELECT word FROM dictionary ORDER BY idWord ASC";
		$result = mysql_query($sql) or die("dictionary->parseSample_2: error en consulta".mysql_error()."SQL: ".$sql);
		
		$cont = 0;
		$this->bow = array();
		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
    		$this->bow[$cont] = $row[0]; 
			$cont++; 
		}
		
		$sql = "TRUNCATE bow";
		mysql_query($sql) or die("dictionary->parseSample_3: error en consulta".mysql_error()."SQL: ".$sql);
		
		while ($row = mysql_fetch_array($sample, MYSQL_NUM)) {
			//Iterate among all the tuits in the sample
			$stemmed = explode(" ",$row[0]);
			$idTuit = $row[1];
			
			//1 hashtags, 2 mentions , 3 links, 4 media
			$hastags = count(explode(",",$row[3]));
			if($hastags > 1){
				$sql="INSERT INTO bow (idTuit, idWord) VALUES ($idTuit,1)";
				mysql_query($sql) or die("dictionary->parseSample_4: error en consulta".mysql_error()."SQL: ".$sql);
			}
			
			$mentions = count(explode(",",$row[2]));
			if($mentions > 1){
				$sql="INSERT INTO bow (idTuit, idWord) VALUES ($idTuit,2)";
				mysql_query($sql) or die("dictionary->parseSample_5: error en consulta".mysql_error()."SQL: ".$sql);
			}
			
			$links= count(explode(",",$row[4]));
			if($links > 1){
				$sql="INSERT INTO bow (idTuit, idWord) VALUES ($idTuit,3)";
				mysql_query($sql) or die("dictionary->parseSample_6: error en consulta".mysql_error()."SQL: ".$sql);
			}
			
			$media = count(explode(",",$row[5]));
			if($media > 1){
				$sql="INSERT INTO bow (idTuit, idWord) VALUES ($idTuit,4)";
				mysql_query($sql) or die("dictionary->parseSample_7: error en consulta".mysql_error()."SQL: ".$sql);
			}
			
			for ($i = 0; $i < count($stemmed); $i++){
				$position = $this->dictionaryPos($stemmed[$i]);
				if($position > 0){
					$idWord = $position + 1;
					$sql="INSERT INTO bow (idTuit, idWord) VALUES ($idTuit,$idWord)";
					mysql_query($sql) or die("dictionary->parseSample_7: error en consulta".mysql_error()."SQL: ".$sql);
				}
			}

		}
	
	}
	
	function getDictionarySize(){
		$sql = "SELECT count(idWord) AS total FROM dictionary";
		$result = mysql_query($sql) or die("dictionary->getDictionarySize: error en consulta".mysql_error()."SQL: ".$sql);
		return mysql_result($result,0,"total");
		
	
	}
	
}

?>