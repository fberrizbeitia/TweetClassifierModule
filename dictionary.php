<link href="stiles.css" rel="stylesheet" type="text/css">
<script language="javascript" src="js/dictionary.js"></script>

<div id="inner-cont">
	 <div id="titulo">
     	<div class="left-align-60" >
        	<h1>
            <ol>
            <li>To create the dictionary please enter the number of tweet to be use in the creation of the BOW. In our <a href="http://www.slideshare.net/fberrizbeitia/presentacion-43159338" target="_blank">previous work</a>, we found that a sample of between 200 and 300 messages is enough to create a dictionary. </li>
            <li>After the dictionary is created an optimization algorithm is run to reduce the word count in the dictionary. </li>
            <li>
            The sample is then parsed using the optimized dictionary.
            </li>
            </ol>
           
            </h1>
       </div>
      </div>
      <div id="loader">
        <img src="images/page-loader.gif" width="150" height="150" />
        <br />
        	 <h1><b>Please be patient. This process may take several minutes to complete</b></h1>
         <br /><br />
      </div>
      
      <div>
		<form action="">
        	<input name="sampleSize" type="text" id="DICsize"/>
            <input name="makeDIC" type="button" id="makeDIC" value="Create dictionary and parce sample"/>
        </form>
  </div>
      
</div>