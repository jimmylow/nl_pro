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
      $var_catcd  = $_GET['catcd'];
      $var_catde  = $_GET['catde'];
      $var_menucode = $_POST['menucd'];
	}
    
     if ($_POST['Submit'] == "Update") {
    
 		$var_catcd    = mysql_real_escape_string($_POST['catcd']);
        $catdescd     = mysql_real_escape_string($_POST['catdeu']);
        $var_itmgrp   = mysql_real_escape_string($_POST['selitmgrp']);
        $catmark      = $_POST['itmgrpmark'];
     	$catcut       = $_POST['itmgrpcut'];
     	$catspread    = $_POST['itmgrpspread'];
     	$catbundle    = $_POST['itmgrpbundle'];
		$var_menucode = $_POST['menudcode'];
		
        if ($var_catcd <> "") {
        
        	if ($catmark == ""){$catmark = 0;}	
		    if ($catcut == ""){$catcut = 0;}	
		    if ($catspread == ""){$catspread = 0;}	
		    if ($catbundle == ""){$catbundle = 0;}	

         	$vartoday = date("Y-m-d H:i:s");
         	$sql = "Update cat_master set cat_desc ='$catdescd',";
         	$sql .= " mark = '$catmark', cut = '$catcut', spread = '$catspread', bundle ='$catbundle', ";
         	$sql .= " upd_by='$var_loginid',";
         	$sql .= " upd_on='$vartoday', itm_grd_cd ='$var_itmgrp' WHERE cat_code = '$var_catcd'";  
       	 	mysql_query($sql) or die(mysql_error()); 
        
         	$backloc = "../stck_mas/catmas.php?menucd=".$var_menucode;
         	echo "<script>";
         	echo 'location.replace("'.$backloc.'")';
         	echo "</script>";
      	}      
    }
    
    if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menudcode'];

         $backloc = "../stck_mas/catmas.php?menucd=".$var_menucode;
    
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

function chkValue(vid)
{
    var col2 = document.getElementById(vid).value;
	
   	if (col2 !== ""){

		if(isNaN(col2)) {	
    	   alert('Please Enter a valid number:' + col2);
    	   document.getElementById(vid).focus();
    	   col2 = 0;
    	}
    	document.getElementById(vid).value = parseFloat(col2).toFixed(2);
    }
}

</script>
</head>
 <?php
   	$sql = "select mark, cut, spread, bundle, itm_grd_cd, create_by, create_on, upd_by, upd_on ";
   	$sql .= " from cat_master";
   	$sql .= " where cat_code ='".$var_catcd ."'";       
   	$sql_result = mysql_query($sql);
   	$row = mysql_fetch_array($sql_result);

   	$var_grpmark   = $row[0];
	$var_grpcut    = $row[1];
	$var_grpspread = $row[2];
	$var_grpbundle = $row[3];
	$var_itmgrp    = $row[4];
	$create_by = $row['create_by'];
    $create_on = $row['create_on'];
    $modified_by= $row['upd_by'];
 	$modified_on = $row['upd_on'];

	
	$sql = "select itm_grp_desc from item_group_master ";
   	$sql .= " where itm_grp_cd ='".$var_itmgrp."'";       
   	$sql_result = mysql_query($sql);
   	$row = mysql_fetch_array($sql_result);
   	$var_itmgrpdesc = $row[0];

?>
<body OnLoad="document.InpColMas.catdeu.focus();">
	<?php include("../topbarm.php"); ?> 
 <!--	<?php include("../sidebarm.php"); ?> -->

	<div class="contentc">

	<fieldset name="Group1" style="height: 360px; width: 600px;">
	 <legend class="title">EDIT CATEGORY MASTER</legend>
	 	 
	
	  <form name="InpColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px; width: 607px;">
		 <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 209px" class="tdlabel">Category Code</td>
	  	    <td>:</td>
	  	    <td><input readonly="readonly" class="inputtxt" name="catcd" id ="catcdid" type="text" value="<?php echo $var_catcd;?>">
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td style="width: 209px" class="tdlabel"></td>
	   	  </tr> 
	   	  <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 209px" class="tdlabel">Category Description</td>
	  	    <td>:</td>
	  	    <td><input class="inputtxt" name="catdeu" id ="catdeid" type="text" maxlength="50" style="width: 354px" onchange ="upperCase(this.id)" value="<?php echo $var_catde; ?>">
			</td>
	  	  </tr>
	  	  <tr><td></td></tr>
	  	   <tr>
	   	  	<td></td>
	   	  	<td>Item Group</td>
	   	  	<td>:</td>
	   	  	<td>
	   	  		<select name="selitmgrp" style="width: 165px">
			    	<?php
			    	    if ($var_itmgrp != ""){
			    			echo '<option value="'.$var_itmgrp.'">'.$var_itmgrp.' | '.$var_itmgrpdesc.'</option>';
			    			echo '<option></option>';
			    		}else{
			    			echo '<option></option>';
			    		}	 
                   		$sql = "select itm_grp_cd, itm_grp_desc from item_group_master ORDER BY itm_grp_cd ASC";
                   		$sql_result = mysql_query($sql);
                                       
				   		if(mysql_num_rows($sql_result)) 
				   		{
				   			 while($row = mysql_fetch_assoc($sql_result)) 
				   			  { 
							  echo '<option value="'.$row['itm_grp_cd'].'">'.$row['itm_grp_cd'].' | '.$row['itm_grp_desc'].'</option>';
				 			 } 
				   		}
	            	?>				   
			  		</select>

	   	  	</td>
	   	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td style="width: 209px" class="tdlabel"></td>
	  	    <td></td> 
            <td>&nbsp;</td> 
	   	    </tr>
  
	  	    <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 209px" class="tdlabel">Mark</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="itmgrpmark" id ="itmgrpmark" type="text" maxlength="10" style="width: 152px" value="<?php echo $var_grpmark; ?>" onBlur="chkValue(this.id);">
			</td>
	  	    </tr>
			<tr>
	   	    	<td></td>
	  	    	<td style="width: 209px" class="tdlabel">Cut</td>
	  	    	<td>:</td>
	  	    	<td>
				<input class="inputtxt" name="itmgrpcut" id ="itmgrpcut" type="text" maxlength="10" style="width: 152px" value="<?php echo $var_grpcut; ?>" onBlur="chkValue(this.id);"></td>
	  	    </tr>
			<tr>
	   	    	<td></td>
	  	    	<td style="width: 209px" class="tdlabel">Spread</td>
	  	    	<td style="height: 23px">:</td>
	  	    	<td style="height: 23px">
				<input class="inputtxt" name="itmgrpspread" id ="itmgrpspread" type="text" maxlength="10" style="width: 152px" value="<?php echo $var_grpspread; ?>" onBlur="chkValue(this.id);"></td>
	  	    </tr>
			<tr>
	   	    	<td></td>
	  	    	<td style="width: 209px" class="tdlabel">Bundle</td>
	  	    	<td>:</td>
	  	    	<td>
				<input class="inputtxt" name="itmgrpbundle" id ="itmgrpbundle" type="text" maxlength="10" style="width: 152px" value="<?php echo $var_grpbundle; ?>" onBlur="chkValue(this.id);">
				</td>
	  	    </tr>
	  	    <tr>
	  	    	<td style="height: 22px"></td>
		   <td style="width: 745px; height: 22px;">Create By</td>
           <td style="height: 22px">:</td>
           <td style="width: 264px; height: 22px;">
			<input class="textnoentry1" name="create_by" id ="create_byid" type="text" readonly="readonly" style="width: 152px" value="<?php echo $create_by;?>"></td>
	  	    </tr>
	  	    <tr>
	  	    	<td style="height: 24px"></td>
		   <td style="width: 745px; height: 24px;">Create On</td>
           <td style="height: 24px">:</td>
           <td style="width: 264px; height: 24px;">
		   <input class="textnoentry1" name="create_on" id ="create_onid" type="text" readonly="readonly" style="width: 152px" value="<?php echo $create_on;?>"></td>
	  	    </tr>
			<tr>
	  	    	<td>&nbsp;</td>
		   <td style="width: 745px">Modified By</td>
           <td>:</td>
           <td style="width: 264px">
	  	    <input class="textnoentry1" name="modified_by" id ="modified_byid" type="text" readonly="readonly" style="width: 152px" value="<?php echo $modified_by;?>"></td>
	  	    </tr>
			<tr>
	  	    	<td>&nbsp;</td>
		   <td style="width: 745px">Modified On</td>
           <td>:</td>
           <td style="width: 264px">
	  	     <input class="textnoentry1" name="modified_on" id ="suppstateid0" type="text" readonly="readonly" style="width: 152px" value="<?php echo $modified_on; ?>"></td>
	  	    </tr>

	  	    
	  	  	<tr>
	  	  		<td></td>
	  	   		<td style="width: 209px"></td>
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

