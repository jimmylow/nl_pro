<?php 

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
 
	if(!isset($_GET['ref']) || !$method = $_GET['ref']) exit;
	if(!isset($_GET['pid']) || !$method = $_GET['pid']) exit;

	$vref = $_GET['ref'];
	$vpid = $_GET['pid'];
  
  	if ($vref != ""){
		$query =  "SELECT refno FROM stck_lbl "; 
		$query .=" WHERE refno='$vref'";

		$result=mysql_query($query);
		$row = mysql_fetch_array($result);

    	if (!empty($row[0])){
	    	echo '1';
    	}else{
    		echo '0';
    	}
    }	
    
?>
