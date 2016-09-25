<?php 

     include("../Setting/Configifx.php");
    include("../Setting/Connection.php");	
    
	$main_code=$_GET['main_code'];
	$supp_code=intval($_GET['supp_code']);
	//$main_code=intval($_GET['main_code']);

  $query="SELECT rm_code FROM rawmat_subcode WHERE main_code='$main_code' ORDER BY rm_code";

  $result=mysql_query($query);
?>
<!--<select name="selrmcode" style="width: 200px" onchange="showUser(this.value)" onBlur="AjaxFunction(this.value);" > -->
<select name="selrmcode" style="width: 200px"  onchange="getPrice(<?php echo $supp_code; ?>,this.value)" >
 <option>-Select Main Code-</option>
  <?php while($row=mysql_fetch_array($result)) 
    echo '<option value="'.htmlentities($row['rm_code']).'">'.htmlentities($row['rm_code']).'</option>';
  ?>           
</select>
