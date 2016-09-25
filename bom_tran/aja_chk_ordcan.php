<?php 

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
 
	if(!isset($_GET['sordno']) || !$method = $_GET['sordno']) exit; 
    if(!isset($_GET['buyercd']) || !$method = $_GET['buyercd']) exit; 
    
	$sordnop = $_GET['sordno'];
	$sordbuy = $_GET['buyercd'];
  
	$var_sql = " SELECT count(*) as cnt from salesentry ";
    $var_sql .= " WHERE sordno = '$sordnop'  And sbuycd = '$sordbuy'";
    $var_sql .= " and stat = 'CANCEL'";
	$result=mysql_query($var_sql);
	$row = mysql_fetch_array($result);

    echo $row[0];
 
?>