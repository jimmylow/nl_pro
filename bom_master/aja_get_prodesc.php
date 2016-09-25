<?php 

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
 
	if(!isset($_GET['procode']) || !$method = $_GET['procode']) exit;

	$vprocode = $_GET['procode'];
  
	$query =  "SELECT prod_desc FROM pro_cd_master "; 
	$query .=" WHERE prod_code='$vprocode'";

	$result=mysql_query($query);
	$row = mysql_fetch_array($result);

    if (!empty($row[0])){
    	echo $row[0];
    }
?>