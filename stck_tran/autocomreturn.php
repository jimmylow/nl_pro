<?php
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

 
	$query = mysql_query("SELECT rm_issue_id, refno from rawmat_issue");
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
		
		foreach($results as $prod_code)
		{
		
			if(stripos($prod_code['rm_issue_id'], $term) !== false){
        	   			
				// Add the necessary "value" and "label" fields and append to result set
				$prod_code['value'] = $prod_code['rm_issue_id'];
			    $prod_code['label'] = "{$prod_code['rm_issue_id']} >>> Ref No: {$prod_code['refno']}";
				$matches[] = $prod_code;
	    	}
			
		}
	}
	
    //print_r ($prod_code);
	// return the array as json with PHP 5.2
	$matches = array_slice($matches, 0, 5);
	
	print json_encode($matches);
?>
