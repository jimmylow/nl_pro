<?php 

  include("../Setting/Configifx.php");
  include("../Setting/Connection.php");
	
  if(!isset($_GET['o']) || !$method = $_GET['o']) exit;
  if(!isset($_GET['b']) || !$method = $_GET['b']) exit;
    
  $ord = $_GET['o'];
  $buy = $_GET['b'];

  $query  = "SELECT distinct y.prod_catpre, y.pronumcode FROM salesentrydet x, pro_cd_master y ";
  $query .= " WHERE x.sordno='$ord' and x.sbuycd='$buy'";
  $query .= " and   y.prod_code = x.sprocd";	
  $result = mysql_query($query) or die(mysql_error());

  
  $varsel = '<select name="cutprodcd" id="cutprodcd" style="width: 200px" onchange="getprodcol(this.value)">';
  $varsel .= '<option value =""></option>';                    
  if(mysql_num_rows($result)) 
  {
   	 while($row = mysql_fetch_assoc($result)) 
	 { 
	 	$prodcat = $row['prod_catpre'];
	 	$prodnum = $row['pronumcode'];
	 	
	 	$stycode = $prodcat.$prodnum;
	 	$values = implode('^',$row);
	 	$values = $values.'^'.$ord.'^'.$buy;
	 	
	    $varsel .= '<option value="'.$values.'">'.$stycode.'</option>';
	 } 
  } 
  
	$varsel .= "</select>";
		  
	 		  
  echo $varsel;			
?>           

