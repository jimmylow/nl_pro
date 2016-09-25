<?php 

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
 
	//if(!isset($_GET['itmcod']) || !$method = $_GET['itmcod']) exit;
	//if(!isset($_GET['ref']) || !$method = $_GET['ref']) exit;
	//if(!isset($_GET['refd']) || !$method = $_GET['refd']) exit;
	
	$vitmcode = htmlentities($_GET['itmcod']);
	$vref     = $_GET['ref'];
	$vrefd    = $_GET['refd'];

    if ($vitmcode != ""){    
		$sql = "select count(*) from stck_lbl ";
    	$sql .= " where sub_code ='".$vitmcode."'";
    	$sql .= " and refno ='".$vref."'";
    	$sql .= " and refopt ='".$vrefd."'";
    	$sql_result = mysql_query($sql);
    	$row1 = mysql_fetch_array($sql_result);
    	$cntcre = $row1[0];

		if ($cntcre != 0){
			echo "1";
		}else{
			echo "0";
		}	
		
  	}
?>