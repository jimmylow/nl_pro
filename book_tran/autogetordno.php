<?php
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
 
	$query = mysql_query("SELECT sordno, sbuycd FROM salesentry Where stat != 'CANCEL'");
	$results = array();
	while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
   		$results[] = $row;
   	}
   
	//check the parameter
	
	$term = $_GET['term'];

	$matches = array();
	if(isset($term) and $term != '')
	{	
		//initialize the results array
			
		foreach($results as $sordno)
		{
			
			if(stripos($sordno['sordno'], $term) !== false){
			
				// Add the necessary "value" and "label" fields and append to result set
				$sordno['value'] = $sordno['sordno'];
			    $sordno['label'] = "{$sordno['sordno']}, {$sordno['sbuycd']}";
				$matches[] = $sordno;
	    	}
			
		}
	}
    //print_r ($prod_code);
	// return the array as json with PHP 5.2
	$matches = array_slice($matches, 0, 10);
	
	print json_encode($matches);
?>
