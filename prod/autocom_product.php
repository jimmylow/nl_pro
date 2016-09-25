<?php
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

 
	$query = mysql_query("SELECT ticketno, productcode, productqty from sew_entry");
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
		
		foreach($results as $ticketno)
		{
		
			if(stripos($ticketno['ticketno'], $term) !== false){
			      	   			
				// Add the necessary "value" and "label" fields and append to result set
				$ticketno['value'] = $ticketno['ticketno'];
			    $ticketno['label'] = "{$ticketno['ticketno']}, {$ticketno['productcode']}";
				$matches[] = $ticketno;
	    	}
			
		}
	}
	
    //print_r ($ticketno);
	// return the array as json with PHP 5.2
	$matches = array_slice($matches, 0, 5);
	
	print json_encode($matches);
?>
