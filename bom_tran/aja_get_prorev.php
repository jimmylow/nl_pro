<?php 

  include("../Setting/Configifx.php");
  include("../Setting/Connection.php");
 
  if(!isset($_GET['procode']) || !$method = $_GET['procode']) exit;

  $vprocd = $_GET['procode']; 
  
  $query =  "SELECT revno FROM prod_matmain "; 
  $query .=" WHERE prod_code='$vprocd'";

  $result=mysql_query($query);

    if (mysql_num_rows($result) == 0) {
      echo ' No Rev No Found For This Product Code.';
    } else {
       echo '<select name="fprocdrev" id= "fprocdrev" style="width: 100px">';
 
       while($row=mysql_fetch_array($result))
       {
     	   $prorev =  $row['revno'];
     	  
      	   echo '<option value="'.$prorev.'">'.$prorev.'</option>';
       }  
       echo '</select>';
   }
?>