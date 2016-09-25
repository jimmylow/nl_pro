<?php 

  include("../Setting/Configifx.php");
  include("../Setting/Connection.php");
	
  if(!isset($_GET['o']) || !$method = $_GET['o']) exit;
  if(!isset($_GET['b']) || !$method = $_GET['b']) exit;
    
  $ord = $_GET['o'];
  $buy = $_GET['b'];

  $query  = "SELECT distinct costingno FROM costing_matdet";
  $query .= " WHERE ordno ='$ord' and buycd ='$buy'";
  $result = mysql_query($query) or die(mysql_error());
  
  $varsel = '<select name="cstno[]" id="cstno" style="width: 125px">';
  $varsel .= '<option value =""></option>';                    
  if(mysql_num_rows($result)) 
  {
   	 while($row = mysql_fetch_assoc($result)) 
	 { 
	 	$cstno = $row['costingno'];	 	
	    $varsel .= '<option value="'.$cstno.'">'.$cstno.'</option>';
	 } 
  } 
  
	$varsel .= "</select>";
		  
	 		  
  echo $varsel;			
?>  