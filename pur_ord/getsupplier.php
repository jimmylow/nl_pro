<?php
$q=htmlentities($_GET['q']);

 if ($q <> "s") {
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

     $sql="SELECT address_1_1, address_2_1, telephone_1, telephone_2, country_1, city_1, postal_code_1, state_1, fax1, fax2  FROM supplier_master ";
     $sql .= " where supplier_code = '".$q."'";

     $result = mysql_query($sql) or die ("Error : ".mysql_error());

     $data = mysql_fetch_object($result);

     $var_add = "";
     
     if (!empty($data->address_1_1)) { $var_add .= $data->address_1_1.", \n"; }
     if (!empty($data->address_2_1)) { $var_add .= $data->address_2_1.", \n"; }
     if (!empty($data->postal_code_1)) { $var_add .= $data->postal_code_1.","; }      
     if (!empty($data->city_1)) { $var_add .= $data->city_1.", \n"; } 
     if (!empty($data->state_1)) { $var_add .= $data->state_1." \n"; } 
     $var_add .= "\nTel : "; 
     if (!empty($data->telephone_1)) { $var_add .= $data->telephone_1.","; }  
     if (!empty($data->telephone_2)) { $var_add .= $data->telephone_2; }   
     $var_add .= "\nFax : "; 
     if (!empty($data->fax1)) { $var_add .= $data->fax1.","; }  
     if (!empty($data->fax2)) { $var_add .= $data->fax2; }    
     
     echo $var_add;                  
   
mysql_close($db_link);

  } else {
  
    echo "";
  }

?> 