<?php
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

	$result = mysql_query("SELECT prod_code FROM pro_cd_master Where actvty != 'Z'");
	while ($row = mysql_fetch_assoc($result)) {
   		$colors[]=$row['prod_code'];
	}
    		
	// check the parameter
	if(isset($_GET['part']) and $_GET['part'] != '')
	{	
		// initialize the results array
		$results = array();

		// search colors
		foreach($colors as $color)
		{
			// if it starts with 'part' add to results
			if(strlen(stristr($color,$_GET['part'])) > 0 ){
			$results[] = $color;
		}
	}

	// return the array as json with PHP 5.2
	echo json_encode($results);
}