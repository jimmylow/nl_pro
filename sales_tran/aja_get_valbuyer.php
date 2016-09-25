<?php
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

	$buyr = $_GET['b'];
	
	//test here//
	$sql = "SELECT contact_person, telephone from customer_master";
	$sql.= "  WHERE customer_code = '$buyr'";
    $result = mysql_query($sql) or die ("Error : ".mysql_error());
    $data = mysql_fetch_object($result); 
    $r1 = $data->contact_person;     
    $r2 = $data->telephone;    
  
    echo $r1."^".$r2;       
   
	mysql_close($db_link);
?> 