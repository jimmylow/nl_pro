<?php
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	$var_loginid = $_SESSION['sid'];
    
    if($var_loginid == "") { 
      echo "<script>";   
      echo "alert('Not Log In to the system');"; 
      echo 'window.close()';
      echo "</script>"; 
    } else {
      $var_probcd  = $_GET['probuyccd'];
	  $var_probde = $_GET['probuycde'];
	  $var_probpre = $_GET['probuypre'];
	  $var_menucode = $_GET['menucd'];
	}
    
     if ($_POST['Submit'] == "Update") {
    
 		$var_probcd = $_POST['probuycd'];
        $var_probde = $_POST['probuyde'];
        $var_probpr = $_POST['probuycdpre'];
        $var_menucode  = $_POST['menudcode'];
         if ($var_probcd <> "") {
        
         $vartoday = date("Y-m-d H:i:s");
         $sql = "Update pro_buy_master set pro_buy_desc ='$var_probde', pro_buy_pre='$var_probpr',";
         $sql .= " modified_by='$var_loginid',";
         $sql .= " modified_on='$vartoday' WHERE pro_buy_code = '$var_probcd' and pro_buy_pre='$var_probpr'";
           
       	 mysql_query($sql); 
        
         $backloc = "../bom_master/pro_buy_master.php?menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
        
      }      
    }
    
    if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menudcode'];
         $backloc = "../bom_master/pro_buy_master.php?menucd=".$var_menucode;
        
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

function AjaxFunction(prefix)
{
   var probuycd = document.getElementById("probuycdid").value;	
   var strURL= "aja_chk_probuycd.php?probuycd="+probuycd+"&pre="+prefix;

   var req = getXMLHTTP();
   if (req)
   {
     req.onreadystatechange = function()
     {
      if (req.readyState == 4)
      {
	 	// only if "OK"
	 	if (req.status == 200)
        {
        	if (req.responseText == 1){
        		document.getElementById("msgcd").innerHTML = "<font color=red> This Product Buyer Code And Prefix Has Been Use</font>";	
        	}else{
        		document.getElementById("msgcd").innerHTML = "<font color=green>This Product Buyer Code And Prefix Is Valid</font>";	
        	}
        	    		
	 	}else{
   	   		alert("There was a problem while using XMLHTTP:\n" + req.statusText);
	 	}
      }
     }
   	req.open("GET", strURL, true);
   	req.send(null);
   }
}


function validateForm()
{
   
    var x=document.forms["InpProBuyMas"]["probuycdpre"].value;
    
	if (!x.match(/\S/))
	{
		alert("Prefix Cannot Not Be Blank");
		InpProBuyMas.probuycdpre.focus();

		return false;
	}
	
}


</script>
</head>

<?php
 	$sql = "select create_by, create_on, modified_by, modified_on";
   	$sql .= " from pro_buy_master";
   	$sql .= " where pro_buy_code ='$var_probcd'";   
   	$sql_result = mysql_query($sql) or die(mysql_error()); 
   	$row = mysql_fetch_array($sql_result);

	$create_by = $row[0];
    $create_on = date("d-m-Y", strtotime($row[1]));
    $modified_by= $row[2];
 	$modified_on = date("d-m-Y", strtotime($row[3]));

?>

  <!--<?php include("../sidebarm.php"); ?> -->
<body onload="document.InpProBuyMas.probuyde.focus();">
 <?php include("../topbarm.php"); ?> 
  <div class="contentc">

	<fieldset name="Group1" style="height: 300px; width: 718px;" class="style2">
	 <legend class="title">EDIT PRODUCT BUYER MASTER : <?php echo $var_probcd; ?></legend>
	  <br>
	    <form name="InpProBuyMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>"  onsubmit="return validateForm()" style="height: 134px" >
	     <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">

		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 210px" class="tdlabel">Product Buyer Code</td>
	  	    <td>:</td>
	  	    <td style="width: 533px">
			<input class="inputtxt" readonly="readonly" name="probuycd" id ="probuycdid" type="text" style="width: 58px;" value="<?php echo $var_probcd; ?>">
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td style="width: 210px" class="tdlabel"></td>
	  	    <td></td> 
            <td style="width: 533px"></td> 
	   	  </tr> 
	   	   <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 210px" class="tdlabel">Description</td>
	  	    <td>:</td>
	  	    <td style="width: 533px">
			<input class="inputtxt" name="probuyde" id ="probuyde" type="text" maxlength="60" style="width: 437px" value="<?php echo $var_probde; ?>" onchange ="upperCase(this.id)">
			</td>
	  	  </tr>  
	  	  <tr>
	  	    <td></td> 
	  	    <td></td>
	  	    <td></td> 
            <td></td> 
	   	  </tr> 
		   <tr>
	  	    <td></td> 
	  	    <td>Prefix</td>
	  	    <td>:</td> 
            <td>
			<input class="inputtxt" name="probuycdpre" id ="probuycdpreid" type="text"  readonly="readonly" onchange ="upperCase(this.id)" style="width: 37px" value="<?php echo $var_probpre; ?>"></td> 
	   	  </tr> 
           <tr>
	  	    <td></td> 
	  	    <td></td>
	  	    <td></td> 
            <td></td> 
	   	  </tr> 
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
	  	    <?php
	  	      $locatr = "pro_buy_master.php?menucd=".$var_menucode;
			  echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
            ?>
	  	   <input type=submit name = "Submit" value="Update" class="butsub" style="width: 60px; height: 32px" ></td>
	  	  </tr>
	  	</table>
	  	</form>
	</fieldset>
   </div>
</body>

</html>

