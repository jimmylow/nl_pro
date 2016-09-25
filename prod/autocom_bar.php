<?php
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

 
	$query = mysql_query("SELECT x.barcodeno, x.ticketno, x.prod_code, x.prod_jobsec, x.prod_jobid, x.prod_jobrate, productqty FROM sew_barcode x, sew_entry y WHERE x.ticketno = y.ticketno");
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
		
		foreach($results as $sales_ord)
		{
		
			if(stripos($sales_ord['barcodeno'], $term) !== false){
			
				$sql = "select sum(x.jobfile_rate) from jobfile_master x, pro_jobmodel y ";
        		$sql .= " where y.prod_code ='".$sales_ord['prod_code']."'";
        		$sql .= " and   x.jobfile_id = y.prod_jobid And x.actvty <> 'Z'";
        		$sql_result = mysql_query($sql);
        		$row1 = mysql_fetch_array($sql_result);
        		$sales_ord['prod_labcst'] = $row1[0];
        	   			
				// Add the necessary "value" and "label" fields and append to result set
				$sales_ord['value'] = $sales_ord['barcodeno'];
			    $sales_ord['label'] = "{$sales_ord['barcodeno']}, {$sales_ord['ticketno']}";
				$matches[] = $sales_ord;
	    	}
			
		}
	}
	
    //print_r ($sales_ord);
	// return the array as json with PHP 5.2
	$matches = array_slice($matches, 0, 5);
	
	print json_encode($matches);
?>
