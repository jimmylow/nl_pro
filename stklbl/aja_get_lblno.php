<?php 

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
 
	if(!isset($_GET['ref']) || !$method = $_GET['ref']) exit;
	if(!isset($_GET['titm']) || !$method = $_GET['titm']) exit;

	$vref = $_GET['ref'];
	$vitm = $_GET['titm'];
  
  	if ($vref != ""){
		$query  = "SELECT count(*) FROM stck_lbl "; 
		$query .= " WHERE refno='$vref'";
		$query .= " AND   sub_code='$vitm'";
		$result=mysql_query($query);
		$row = mysql_fetch_array($result);

    	echo $row[0];
    }	
?>
