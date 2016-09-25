<?php
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	$var_loginid = $_SESSION['sid'];
	    
    if($var_loginid == "") { 
      echo "<script>";   
      echo "alert('Not Log In to the system');"; 
      echo "</script>"; 

      echo "<script>";
      echo 'top.location.href = "../index.php"';
      echo "</script>";
    } else {
      $var_typecd = htmlentities($_GET['typecd']);
	  $var_typede = htmlentities($_GET['typede']);
	  $var_menucode = $_GET['menucd'];
	}
    
     if ($_POST['Submit'] == "Update") {   
 		$vartypecd = mysql_real_escape_string($_POST['typecd']);
        $vartypede = mysql_real_escape_string($_POST['typede']);
        $var_stat  = $_POST['tstat'];
        $var_menucode  = $_POST['menudcode'];

         if ($vartypecd <> "") {
        
         $vartoday = date("Y-m-d H:i:s");
         $sql = "Update protype_master set type_desc ='$vartypede',";
         $sql .= " modified_by='$var_loginid',";
         $sql .= " modified_on='$vartoday', stat = '$var_stat' WHERE type_code = '$vartypecd'";
           
       	 mysql_query($sql); 
                
         $backloc = "../bom_master/product_type_master.php?menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
      }      
    }
    
    if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menudcode'];
         $backloc = "../bom_master/product_type_master.php?menucd=".$var_menucode;
        
 		 echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<style media="all" type="text/css">@import "../css/styles.css";
</style>

<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript"> 

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

</script>
</head>

<?php
 	$sql = "select create_by, create_on, modified_by, modified_on, stat ";
   	$sql .= " from protype_master";
   	$sql .= " where type_code ='$var_typecd'";   
   	$sql_result = mysql_query($sql) or die(mysql_error()); 
   	$row = mysql_fetch_array($sql_result);

	$create_by = $row[0];
    $create_on = date("d-m-Y", strtotime($row[1]));
    $modified_by= $row[2];
 	$modified_on = date("d-m-Y", strtotime($row[3]));
 	$stat      = $row[4];
 	$sewtypecd = $row[5];
 	
	if ($stat == "A"){
		$statdesc = 'ACTIVE';
	}else{
		$statdesc = 'DEACTIVE';
	} 

?>

  <!--<?php include("../sidebarm.php"); ?> -->
<body OnLoad="document.InpTypeMas.typede.focus();">
  <?php include("../topbarm.php"); ?>
  <div class="contentc">
	<fieldset name="Group1" style="height: 439px; width: 714px;" class="style2">
	 <legend class="title">EDIT TYPE MASTER</legend>
	  <br>
	
	  <form name="InpTypeMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px">
		<input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Type Code</td>
	  	    <td>:</td>
	  	    <td>
	  	      <input readonly="readonly" class="inputtxt" name="typecd" id ="typecdid" type="text" value="<?php echo $var_typecd; ?>" style="width: 128px">
			</td>
			<td></td>
			<td>Status</td>
			<td>:</td>
			<td>
				<select name="tstat" id="tstat" style="width: 86px">
					<option value="<?php echo $stat;?>"><?php echo $statdesc;?></option>
					<option value="A">ACTIVE</option>
					<option value="D">DEACTIVE</option>
				</select>
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td>
	  	    </td> 
	  	    <td style="width: 138px" class="tdlabel"></td>
	   	  </tr> 
			<tr>
				<td></td>
			</tr>
	   	   <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Description</td>
	  	    <td>:</td>
	  	    <td>
	  	     <input class="inputtxt" name="typede" id ="typedeid" type="text" maxlength="60" style="width: 354px" value="<?php echo $var_typede; ?>">
        	</td>
	  	  </tr>
	  	  <tr><td></td></tr>  	
	  	  <tr>
	  	  	<td></td>
	  	  	<td>Created By</td>
	  	  	<td>:</td>
	  	  	<td>
			<input type="text" class="textnoentry1" readonly="readonly" value="<?php echo $create_by; ?>" name="createby"></td>
	  	  </tr>
	  	  <tr><td></td></tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td>Created On</td>
	  	  	<td>:</td>
	  	  	<td>
			<input type="text" class="textnoentry1" readonly="readonly" value="<?php echo $create_on; ?>" name="createon"></td>
		  </tr>	
	  	  <tr><td></td></tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td>Modified By</td>
	  	  	<td>:</td>
	  	  	<td>
			<input type="text" class="textnoentry1" readonly="readonly" value="<?php echo $modified_by; ?>" name="modby"></td>
	  	  </tr>
	  	  <tr><td></td></tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td>Modified On</td>
	  	  	<td>:</td>
	  	  	<td>
			<input type="text" class="textnoentry1" readonly="readonly" value="<?php echo $modified_on; ?>" name="modon"></td>
	  	  </tr>
	  	  <tr><td></td></tr>
	  	  <tr>
	  	   <td></td>
	  	   <td></td>
	  	   <td></td>
	  	   <td>
	  	   <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px" ><input type=submit name = "Submit" value="Update" class="butsub" style="width: 60px; height: 32px" >
	  	   </td>
	  	  </tr>
	  	</table>
	   </form>	
	</fieldset>
   </div>
</body>

</html>

