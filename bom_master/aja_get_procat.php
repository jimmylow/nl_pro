<?php 

  include("../Setting/Configifx.php");
  include("../Setting/Connection.php");
 
  if(!isset($_GET['probuy']) || !$method = $_GET['probuy']) exit;
  if(!isset($_GET['protyp']) || !$method = $_GET['protyp']) exit;   

  $vprobuy = $_GET['probuy'];
  $vprotyp = $_GET['protyp'];  
  
  $query =  "SELECT pro_cat_cd, pro_cat_prefix, pro_cat_frnum, pro_cat_tonum  FROM pro_cat_master "; 
  $query .=" WHERE pro_buy_cd='$vprobuy'  And pro_type_cd ='$vprotyp'";

  $result=mysql_query($query);

    if (mysql_num_rows($result) == 0) {
      echo ' No Product Category Found For This Buyer & Product Type; Please Create Product Category In Category Master.';
    } else {
       echo '<select name="procdcat" id= "procdcat" style="width: 187px">';
 
       while($row=mysql_fetch_array($result))
       {
     	   $prefixcd =  $row['pro_cat_prefix'];
     	   $procatcd =  $row['pro_cat_cd'];
      	   $snumcd = $row['pro_cat_frnum'];
      	   $enumcd = $row['pro_cat_tonum'];
       
      	   $values = implode(',', $row);
                echo '<option value="'.$values.'">'.$prefixcd.$procatcd.' '.$snumcd."-".$enumcd.'</option>';
       }  
       echo '</select>';
   }
?>