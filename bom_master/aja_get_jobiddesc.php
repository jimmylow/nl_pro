<?php 

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
 
	if(!isset($_GET['verjobid']) || !$method = $_GET['verjobid']) exit;

	$vjid = $_GET['verjobid'];
  
    if ($vjid != ""){
		$query =  "SELECT jobfile_desc, jobfile_rate FROM jobfile_master "; 
		$query .=" WHERE jobfile_id='$vjid'";

		$result=mysql_query($query);
		$row = mysql_fetch_array($result);
		
		$values = implode('|',$row);
		echo $values;
	}else{
	 
    }	

?>