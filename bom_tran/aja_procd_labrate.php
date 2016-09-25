<?php 

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
 
	if(!isset($_GET['procode']) || !$method = $_GET['procode']) exit;

	$vprocode = $_GET['procode'];
  
	$sql = "select sum(prod_jobrate) from pro_jobmodel ";
    $sql .= " where prod_code ='".$vprocode."'";
    $sql_result = mysql_query($sql);
    $row1 = mysql_fetch_array($sql_result);
	
    if (!empty($row1[0])){
    	echo $row1[0];
    }
?>