<?php 

     include("../Setting/Configifx.php");
    include("../Setting/Connection.php");	
    
	$main_code=$_GET['main_code'];
	$supp_code=intval($_GET['supp_code']);
	//$main_code=intval($_GET['main_code']);

  $query="SELECT rm_code FROM rawmat_subcode WHERE main_code='$main_code' ORDER BY rm_code";

  $result=mysql_query($query);
  $num_rows = mysql_num_rows($result);
  
  if ($num_rows > 10) {
  	$num_rows = 10;
  }
?>
<select name="selrmcode[]" id="selrmcode" multiple style="width: 200px" size="<?php echo $num_rows; ?>">
  <?php while($row=mysql_fetch_array($result)) 
    echo '<option value="'.htmlentities($row['rm_code']).'">'.htmlentities($row['rm_code']).'</option>';
  ?>           
</select>
