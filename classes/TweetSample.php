<?php
include_once("dbObjeto.php");
include_once("JSearchString.php");
include_once("PorterStemmer.php");
include_once("tuit.php");

class TweetSample extends dbObjeto{
	
	var $idTuit;
	var $stemmed;
	var $clase;
	var $clase2;
	var $clase_def;
	var $trainingSet;

	public function actualizar(){
		if ($this->total > 0){
				$this->idTuit= mysql_result($this->lista,$this->indice,"idTuit");
				$this->stemmed = mysql_result($this->lista,$this->indice,"stemmed");
				$this->clase = mysql_result($this->lista,$this->indice,"class");
				$this->clase2 = mysql_result($this->lista,$this->indice,"class2");
				$this->clase_def = mysql_result($this->lista,$this->indice,"class_def");
				$this->trainingSet = mysql_result($this->lista,$this->indice,"trainingSet");
			}
		}
	
	public function obtenerTodos(){
			$sql = "SELECT * FROM sample";
			$result = mysql_query($sql) or die("tuit->obtenerTodos: error en consulta".mysql_error()."SQL: ".$sql);
			$this->lista = $result;
			$this->total = mysql_num_rows($result);
			$this->indice = 0;
			$this->actualizar();
		}
		
	public function obtenerSinEtiquetar(){
			$sql = "SELECT * FROM sample WHERE ISNULL(class)";
			$result = mysql_query($sql) or die("tuit->obtenerSinEtiquetar: error en consulta".mysql_error()."SQL: ".$sql);
			$this->lista = $result;
			$this->total = mysql_num_rows($result);
			$this->indice = 0;
			$this->actualizar();
		}
		
		public function obtenerSinEtiquetar2(){
			$sql = "SELECT * FROM sample WHERE ISNULL(class2)";
			$result = mysql_query($sql) or die("tuit->obtenerSinEtiquetar2: error en consulta".mysql_error()."SQL: ".$sql);
			$this->lista = $result;
			$this->total = mysql_num_rows($result);
			$this->indice = 0;
			$this->actualizar();
		}
	
	public function obtenerSinDenoise(){
			$sql = "SELECT * FROM sample WHERE ISNULL(stemmed)";
			$result = mysql_query($sql) or die("tuit->obtenerSinDenoise: error en consulta".mysql_error()."SQL: ".$sql);
			$this->lista = $result;
			$this->total = mysql_num_rows($result);
			$this->indice = 0;
			$this->actualizar();
		}
	
	public function obtenerPorID($id){
			$sql = "SELECT * FROM sample where idTuit = $id";
			$result = mysql_query($sql) or die("tuit->obtenerTodos: error en consulta".mysql_error()."SQL: ".$sql);
			$this->lista = $result;
			$this->total = mysql_num_rows($result);
			$this->indice = 0;
			$this->actualizar();
		}
	
	
	public function obtenerTrainingSet(){
			$sql = "SELECT * FROM sample where trainingSet = 1";
			$result = mysql_query($sql) or die("tuit->obtenerTrainingSet: error en consulta".mysql_error()."SQL: ".$sql);
			$this->lista = $result;
			$this->total = mysql_num_rows($result);
			$this->indice = 0;
			$this->actualizar();
		}
	
	
	public function guardar(){
		
			if($this->clase == ''){$this->clase = 'NULL';}
			if($this->clase2 == ''){$this->clase2 = 'NULL';}
			if($this->clase_def == ''){$this->clase_def = 'NULL';}
			
			$sql = "UPDATE sample set stemmed = '$this->stemmed' , class = $this->clase,class2 = $this->clase2, class_def= $this->clase_def WHERE idTuit = $this->idTuit";
			
			mysql_query($sql) or die("tuit->obtenerTodos: error en consulta".mysql_error()."SQL: ".$sql);
			
		}
	
	
	public function generateMainSample($SampleSize){
		$sql = "TRUNCATE sample";
		mysql_query($sql) or die("TweetSample->generateMainSample_1: error en consulta".mysql_error()."SQL: ".$sql);
		$sql = "INSERT INTO sample (idTuit) (SELECT idTuit FROM tuits ORDER BY RAND() LIMIT 0,$SampleSize)";
		mysql_query($sql) or die("TweetSample->generateMainSample_2: error en consulta".mysql_error()."SQL: ".$sql);
		
	}
	
	private function removeMentions($tweet){
		$token = explode(" ",$tweet);
		$noMentions = "";
		for($i = 0; $i < count($token); $i++){
			$pos = strpos($token[$i], '@');
			$posURL = strpos($token[$i], 'http');
			if($pos === false and $posURL === false){
				$noMentions .= $token[$i]." ";
			}
		}
		return $noMentions;
	}
	
	private function stemmer($tweet){
		$token = explode(" ",$tweet);
		$steemed = "";
		for($i = 0; $i < count($token); $i++){
			$steemed .= PorterStemmer::Stem($token[$i])." ";
		}
		return $steemed;
	}
	
	public function cleanAndSaveTweets(){
		
		$tweet = new Tuit();
		$jSS = new jSearchString();
		
		$this->obtenerSinDenoise();
		
		for($i = 0; $i < $this->total; $i++){
			$this->ir($i);
			$tweet->obtenerPorID($this->idTuit);
			$noMentions = $this->removeMentions($tweet->texto);
			$noStopWords = $jSS->parseString( strtolower($noMentions));
			$stemmed = $this->stemmer($noStopWords);
			$sql = "UPDATE sample SET stemmed ='$stemmed' WHERE idTuit=$this->idTuit";
			mysql_query($sql) or die("TweetSample->cleanAndSaveTweets: error en consulta".mysql_error()."SQL: ".$sql);					
		}
			
	}
	
	private function inDictionary($word){
		$result = false;
		$stop = false;
		$cont = 0;
		
		while(!$stop){
			if(count($this->dictionary ) > 0){
				if(strcasecmp($word,$this->dictionary[$cont]) == 0){
					$stop = true;
					$result = true;
				}
			}			
			if($cont >= count($this->dictionary)-1){
				$stop = true;
			}
			$cont++;
		}
		
		return($result);	
	}
	
	public function calculateSampleSize(){
		$size = 0;
		$sql = "SELECT * FROM limpio";
		$result = 	mysql_query($sql) or die("TweetSample->calculateSampleSize: error en consulta".mysql_error()."SQL: ".$sql);
		$stop = false;
		$cont = 0;
		$wordCount = 0;
		$gain = 0;
		$threshold = 0;
		
		while(!$stop){
			$text = mysql_result($result,$cont,"stemmed");
			$words = explode(" ",$text);
			$gain = 0;
			for($i = 0; $i < count($words);$i++){
				if(!$this->inDictionary($words[$i])){	
					//echo("no esta $words[$i]|");
					$this->dictionary[] = $words[$i];
					$gain++;
				}
			}
			
			if($gain < 3){
				$threshold++;
			}else{
				$threshold = 0;
				}
			
			if($threshold >= 10){
				$stop = true;
			}
				
			if($cont == mysql_num_rows($result)-1){
				$stop = true;
			}
			$cont++;
	
		}//while(!$stop){
		return $cont;
	} //public function calculateSampleSize(){
		
	function createDictionary($size){
		$this->dictionary =  array();
		
		//Tomar un MAS de tamaño $size los tuits limpios
		$sql = "SELECT stemmed FROM limpio ORDER BY RAND() LIMIT 0,$size";
		$result = 	mysql_query($sql) or die("TweetSample->createDictionary: error en consulta".mysql_error()."SQL: ".$sql);
		for($i = 0; $i < mysql_num_rows($result); $i++){
			$text = mysql_result($result,$i,"stemmed");
			$words = explode(" ",$text);
			for($j = 0; $j < count($words);$j++){
				if(!$this->inDictionary($words[$j])){	
					$this->dictionary[] = $words[$j];
				}
			}
		}//for($i = 0; $i < mysql_num_rows($result); $i++){
	}
	
	function calculateReach($testSetSize){
		$sql = "SELECT stemmed FROM limpio ORDER BY RAND() LIMIT 0,$testSetSize";
		$result = 	mysql_query($sql) or die("TweetSample->calculateReach: error en consulta".mysql_error()."SQL: ".$sql);
		$coveredTweets = 0;
		
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
			if($wordsInDictionary >= 2){
				$coveredTweets++;
			}
			$wordsCovered += $wordsInDictionary;
			$totalWords += count($words);
			$wordCoveredPerTweet[] = $wordsInDictionary/count($words);

		}//for($i = 0; $i < mysql_num_rows($result); $i++)t{
		 
		 echo("Palabras Cubiertas: ".$wordsCovered."<br>");
		 echo("Total de palabras: ".$totalWords."<br>");
		 for($i = 0; $i < count($wordCoveredPerTweet); $i++){
			echo($wordCoveredPerTweet[$i].",");		
		}
		 echo("<br>");
		 return $coveredTweets;
	}
	
//-------------------------------------------------------------------------------------------------------------
//-------------------------------- STRATIFIED -----------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------	
	
	function createDictionaryStr($size){
		$this->dictionary =  array();
		
		//Tomar un STR, haciendo un MAS por cada estrado de acuerdo a su peso
		
		//primero debemos calcular los pesos
		//debe haber una correspondencia delos pesos y los terminos
		$weights = array();
		$terminos = array();
		
		$sql =  "SELECT idTermino, count(text) AS total FROM limpio GROUP BY idTermino";
		$result = mysql_query($sql) or die("TweetSample->createDictionaryStr: error en consulta".mysql_error()."SQL: ".$sql);
		$total = 0;
		//echo("----------------- TERMINOS TOTALES:".mysql_num_rows($result)." ---------------<br>");
		$numeroDeEstratos = mysql_num_rows($result);
		for($i = 0; $i < $numeroDeEstratos;$i++){
			$terminos[$i] = mysql_result($result,$i,"idTermino");
			$weights[$i] = mysql_result($result,$i,"total");
			$total += mysql_result($result,$i,"total");

		}
		
		//dividir todo entre el total
		for($i = 0; $i < $numeroDeEstratos;$i++){
			$weights[$i] = $weights[$i]/$total;
		}
		
		
		for($k= 0;$k < $numeroDeEstratos;$k++){
			$sizeStr = round($size*$weights[$k]);
			$idTemino = $terminos[$k];
			echo("idTemino=$idTemino - size=".$sizeStr."|-|");
			$sql = "SELECT stemmed FROM limpio WHERE idTermino = $idTemino ORDER BY RAND() LIMIT 0,$sizeStr";
			$result = 	mysql_query($sql) or die("TweetSample->createDictionaryStr_2: error en consulta".mysql_error()."SQL: ".$sql);
			for($i = 0; $i < mysql_num_rows($result); $i++){
				$text = mysql_result($result,$i,"stemmed");
				$words = explode(" ",$text);
				for($j = 0; $j < count($words);$j++){
					if(!$this->inDictionary($words[$j])){	
						$this->dictionary[] = $words[$j];
					}
				}
			}//for($i = 0; $i < mysql_num_rows($result); $i++){
			
		}//for($i= 0;$i < count($weights);$i++){
	} //	function createDictionaryStr($size){
	
	
} 

?>