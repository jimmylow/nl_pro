<?php
$price=htmlentities($_GET['p']);
$i=htmlentities($_GET['i']);
$q=htmlentities($_GET['q']);

 if ($q >0) {
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

     //$sql="SELECT price  FROM rawmat_price_trans ";
     //$sql .= " where supplier = '".$s."'";
     //$sql .= " and rm_code = '".$i."'";
     //$sql .= " and '".$q."' between from_qty and to_qty";
     //$result = mysql_query($sql) or die ("Error : ".mysql_error());
     //$data = mysql_fetch_object($result);

     //$price = 0;     
     $var_amt = 0;
     $var_amt = number_format($price * $q, 2,'.', ''); }
     echo $var_price."k".$var_amt;   
     
     /*if (!empty($data->price)) { 
      $var_amt = number_format($price * $q, 2,'.', ''); }  
     
     	//echo $var_price."k".$var_amt;                  
     	echo $var_amt;                  
		mysql_close($db_link);
  	} else {
    	echo "0.00k0.00";
  	}
  	*/
?> 