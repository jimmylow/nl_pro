<?php 

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
 
	if(!isset($_GET['sono']) || !$method = $_GET['sono']) exit;

	$vjid = $_GET['sono'];
  
    if ($vjid != ""){
		$query =  "SELECT sbuycd, sorddte FROM salesentry "; 
		$query .=" WHERE sordno='$vjid'";
		$result=mysql_query($query);
		$row = mysql_fetch_array($result);
		
		$row['sorddte'] = date('d-m-Y', strtotime($row['sorddte']));
		
		$values = implode('^',$row);
		echo $values;
	}else{
	 
    }	

?>