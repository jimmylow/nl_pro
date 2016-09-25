<?php
  include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	
    if(!isset($_GET['fd']) || !$method = $_GET['fd']) exit; 
	if(!isset($_GET['td']) || !$method = $_GET['td']) exit; 
	
	$dtef=$_GET['fd'];
	$dtet=$_GET['td'];
  $trantype=$_GET['t'];
	
	$dtef= date("Y-m-d", strtotime($dtef));
	$dtet= date("Y-m-d", strtotime($dtet));

    if ($dtef <> "" and $dtet <> "") {
    
      switch ($trantype) {   
         case "R" : $var_sql = " SELECT count(*) as cnt from rawmat_receive";
                    $var_sql .= " WHERE grndate between '$dtef' and '$dtet'";
                    break;
         case "I" : $var_sql = " SELECT count(*) as cnt from rawmat_issue";
                    $var_sql .= " WHERE issuedate between '$dtef' and '$dtet'";
                    break;                    
         case "A" : $var_sql = " SELECT count(*) as cnt from rawmat_adj";
                    $var_sql .= " WHERE date(adjdate) between '$dtef' and '$dtet'";
                    break; 
         case "N" : $var_sql = " SELECT count(*) as cnt from rawmat_return";
                    $var_sql .= " WHERE date(returndate) between '$dtef' and '$dtet'";
                    break;  
         case "E" : $var_sql = " SELECT count(*) as cnt from rawmat_reject";
                    $var_sql .= " WHERE rejectdate between '$dtef' and '$dtet'";
                    break;                                               
      }


      $result=mysql_query($var_sql);
      $row = mysql_fetch_array($result);
	  if ($row[0] == 0){
    	echo "0";
      }else{
        echo "1";
      }    	
    }  
?> 