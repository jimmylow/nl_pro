<?php 

  include("../Setting/Configifx.php");
  include("../Setting/Connection.php");
	
  if(!isset($_GET['probcode']) || !$method = $_GET['probcode']) exit; 
  $probcode = $_GET['probcode'];

  $query  = "SELECT pro_buy_pre FROM pro_buy_master ";
  $query .= " WHERE pro_buy_code='$probcode'";
  $result = mysql_query($query) or die(mysql_error());
  
  $varsel = '<select name="procatnumpre" id="procatnumpre" style="width: 50px" onchange="getrange()">';
                 
  if(mysql_num_rows($result)) 
  {
   	 while($row = mysql_fetch_assoc($result)) 
	 { 
	 	$cat = $row['pro_buy_pre'];
	 	
	    $varsel .= '<option value="'.$cat.'">'.$cat.'</option>';
	 } 
  } 
  
	$varsel .= "</select>";
	
	echo $varsel;	
?>           
