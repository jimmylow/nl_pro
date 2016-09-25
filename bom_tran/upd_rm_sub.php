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
      $var_rm_cd = $_GET['rawmatcd'];
      $var_menucode = $_GET['menucd'];
    }
    
     if ($_POST['Submit'] == "Update") {
       $var_rm_cd 	  = htmlentities(mysql_real_escape_string($_POST['rawmatcd']));
       $var_menucode  = htmlentities(mysql_real_escape_string($_POST['menudcode']));

       if ($var_rm_cd <> "") {
         $rawmatcd    = htmlentities(mysql_real_escape_string($_POST['rawmatcd']));
         $size        = htmlentities(mysql_real_escape_string($_POST['size']));
         //$colour = $_POST['selcolour'];
         $density     = htmlentities(mysql_real_escape_string($_POST['density']));
         $moq         = htmlentities(mysql_real_escape_string($_POST['moq']));
         $mcq         = htmlentities(mysql_real_escape_string($_POST['mcq']));
         $minqty      = htmlentities(mysql_real_escape_string($_POST['minqty']));
         $maxqty      = htmlentities(mysql_real_escape_string($_POST['maxqty']));

         $description = htmlentities(mysql_real_escape_string($_POST['description']));
         $lead_time   = htmlentities(mysql_real_escape_string($_POST['lead_time']));
         $active_flag = htmlentities(mysql_real_escape_string($_POST['selactive']));
         $location    = htmlentities(mysql_real_escape_string($_POST['sellocation']));
         $cost_price  = htmlentities(mysql_real_escape_string($_POST['cost_price']));
         $suppmoby= $var_loginid;
         $suppmoon= date("Y-m-d H:i:s");
         
         $sql = "Update rawmat_subcode set ";
         $sql .= " active_flag = '$active_flag', density = '$density', ";
         $sql .= " moq='$moq', mcq='$mcq',";
         $sql .= " minqty='$minqty', maxqty='$maxqty', ";
         $sql .= " description='$description',";
         $sql .= " lead_time = '$lead_time', ";
         $sql .= " location = '$location ', ";
         $sql .= " cost_price = '$cost_price', ";
         $sql .= " modified_by='$suppmoby',";
         $sql .= " modified_on='$suppmoon' WHERE rm_code = '$var_rm_cd'";
         mysql_query($sql);
         
         $backloc = "../stck_mas/m_rm_subcode.php?menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
      }      
    }
    
    if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menudcode'];
         $backloc = "../stck_mas/m_rm_subcode.php?menucd=".$var_menucode;
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
.style2 {
	margin-right: 0px;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>

<script type="text/javascript" charset="utf-8"> 
function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

function showmoqDecimal(moq){
 	if (moq != ""){
		if(isNaN(moq)) {
    	   alert('Please Enter a number for MOQ :' + moq);
    	   document.UpdSuppMas.moqid.focus();
    	   return false;
    	}
	
    }
}

function showmcqDecimal(mcq){
 	if (mcq != ""){
		if(isNaN(mcq)) {
    	   alert('Please Enter a number for MCQ :' + mcq);
    	   document.UpdSuppMas.mcqid.focus();
    	   return false;
    	}

    }
}

function showcostDecimal(cost_price){
 	if (cost_price!= ""){
		if(isNaN(cost_price)) {
    	   alert('Please Enter a number for Cost Price :' + cost_price);
    	   document.UpdSuppMas.cost_priceid.focus();
    	   return false;
    	}

    }
}


</script>
</head>
<?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->
  
   <?php
     	$var_rm_cd= str_replace( array('~'), '', $var_rm_cd);
      
		$sql = "select active_flag, density,  moq, mcq, lead_time, ";
        $sql .= " colour, size, main_code, modified_by, modified_on, description, location, cost_price, minqty, maxqty ";
        $sql .= " from rawmat_subcode";
        $sql .= " where rm_code ='".mysql_real_escape_string($var_rm_cd)."'";
        $sql_result = mysql_query($sql);
        
        $row = mysql_fetch_array($sql_result);

        $active_flag = $row[0];
        $density = $row[1];
        $moq = $row[2];
        $mcq = $row[3];
        
        $lead_time = $row[4];
        $colourcd = $row[5];
        $size = $row[6];
        $main_code = $row[7];
        $description = mysql_real_escape_string($row[10]);
        $location    = $row[11];
        $cost_price  = $row[12];
        $minqty = $row[13];
        $maxqty = $row[14];

       
        $sql = "select colour_desc from colour_master";
        $sql .= " where colour_code ='".$colourcd."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $colour = $row[0];
        
        $sql = "select category, uom, currency_code, remark from rawmat_master";
        $sql .= " where rm_code ='".$main_code."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $category = $row[0];
        $uom = $row[1];
        $currency_code = $row[2];
        $remark= $row[3];

        $sql = "select cat_desc from cat_master";
        $sql .= " where cat_code ='".$category."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $category_desc = $row[0];
        
        $sql = "select loca_desc from stk_location";
        $sql .= " where loca_code ='".$location."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $location_desc = $row[0];

       
    ?>	
<body OnLoad="document.UpdSuppMas.selactive.focus();">
  
  <div class="contentc">
	<fieldset name="Group1" style=" width: 1011px; height: 455px;">
	 <legend class="title">EDIT RAWMAT SUB CODE DETAILS</legend>
	 
    	
	  <form name="UpdSuppMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px; width: 970px;">
	    <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>"> 
		<table style="width: 980px; height: 436px;">
	  	  <tr>
	  	    <td style="width: 2px; height: 5px;">
	  	    </td>
	  	    <td style="width: 114px; height: 5px;" class="tdlabel">Sub Code Number</td>
	  	    <td style="width: 4px; height: 5px;">:</td>
	  	    <td style="width: 375px; height: 5px;">
			<input class="textnoentry1" name="rawmatcd" id ="rawmatcdid" readonly="readonly" type="text" style="width: 161px" value="<?php echo htmlentities($var_rm_cd); ?>">
			</td>
			<td style="width: 15px; height: 5px;">
			</td>
		    <td style="width: 106px; height: 5px;" class="tdlabel">Status</td>
	  	    <td style="width: 6px; height: 5px;">:</td>
	  	    <td style="width: 108px; height: 5px;">
			   <select name="selactive" style="width: 125px" >
			    <option><?php echo $active_flag;?></option>
			    <option>ACTIVE</option>
			    <option>DEACTIVATE</option>
			   </select>
			</td>
	  	  </tr> 
	   	   <tr>
	   	    <td style="width: 2px; height: 28px;">
	  	    </td>
	  	    <td style="width: 114px; height: 28px;" class="tdlabel">Colour</td>
	  	    <td style="width: 4px; height: 28px;">:</td>
	  	    <td style="width: 375px; height: 28px;">
			<input class="textnoentry1" name="colour" id ="unit_priceid6" type="text" maxlength="50" style="width: 250px" value="<?php echo $colour; ?>" readonly="readonly" disabled="disabled"></td>
			<td style="height: 28px; width: 15px;">
			</td>
		    <td style="width: 106px; height: 28px;" class="tdlabel">Width</td>
	  	    <td style="width: 6px; height: 28px;">:</td>
	  	    <td style="width: 108px; height: 28px;">
			<input class="textnoentry1" name="size" id ="unit_priceid7" type="text" maxlength="50" style="width: 250px" value="<?php echo $size; ?>" disabled="disabled"></td>
	  	  </tr>
	  	  <tr>
	   	    <td style="width: 2px; height: 28px;">
	  	    </td>
	  	    <td style="width: 114px; height: 28px;" class="tdlabel">Density</td>
	  	    <td style="width: 4px; height: 28px;">:</td>
	  	    <td style="width: 375px; height: 28px;">
			<input class="inputtxt" name="density" id ="unit_priceid3" type="text" maxlength="50" style="width: 250px" value="<?php echo $density; ?>"></td>
			<td style="height: 28px; width: 15px;">
			</td>
		    <td style="width: 106px; height: 28px;" class="tdlabel">MOQ</td>
	  	    <td style="width: 6px; height: 28px;">:</td>
	  	    <td style="width: 108px; height: 28px;">
			<input class="inputtxt" name="moq" id ="moqid" type="text" maxlength="50" style="width: 240px; height: 19px;" value="<?php echo $moq; ?>" align="texttop" onblur="showmoqDecimal(this.value)"></td>
	  	  </tr>
		  <tr>
	   	    <td style="width: 2px; height: 28px;">
	  	    </td>
	  	    <td style="width: 114px; height: 28px;" class="tdlabel">Supplier 
			Lead Time (Days)</td>
	  	    <td style="width: 4px; height: 28px;">:</td>
	  	    <td style="width: 375px; height: 28px;">
			<input class="inputtxt" name="lead_time" id ="unit_priceid4" type="text" maxlength="50" style="width: 250px" value="<?php echo $lead_time; ?>"></td>
			<td style="height: 28px; width: 15px;">
			</td>
		    <td style="width: 106px; height: 28px;" class="tdlabel">MCQ</td>
	  	    <td style="width: 6px; height: 28px;">:</td>
	  	    <td style="width: 108px; height: 28px;">
			<input class="inputtxt" name="mcq" id ="mcqid" type="text" maxlength="50" style="width: 240px; height: 19px;" value="<?php echo $mcq; ?>" align="texttop" onblur="showmcqDecimal(this.value)"></td>
	  	  </tr>
	  	    <tr>
	   	    <td style="width: 2px; height: 28px;">
	  	    </td>
	  	    <td style="width: 114px; height: 28px;" class="tdlabel">Min Qty</td>
	  	    <td style="width: 4px; height: 28px;">:</td>
	  	    <td style="width: 375px; height: 28px;">
			<input class="inputtxt" name="minqty" id ="unit_priceid8" type="text" maxlength="50" style="width: 250px" value="<?php echo $minqty; ?>"></td>
			<td style="height: 28px; width: 15px;">
			</td>
		    <td style="width: 106px; height: 28px;" class="tdlabel">Max Qty</td>
	  	    <td style="width: 6px; height: 28px;">:</td>
	  	    <td style="width: 108px; height: 28px;">
			<input class="inputtxt" name="maxqty" id ="mcqid0" type="text" maxlength="50" style="width: 240px; height: 19px;" value="<?php echo $maxqty; ?>" align="texttop" onblur="showmcqDecimal(this.value)"></td>
	  	    </tr>
	  	  <tr>
	   	    <td style="width: 2px; height: 19px;">
	  	    </td>
	  	    <td style="width: 114px; height: 19px;">Category</td>
	  	    <td style="width: 4px; height: 19px;">:</td>
	  	    <td style="width: 375px; height: 19px;">
			<input class="textnoentry1" name="category" id ="unit_priceid1" type="text" maxlength="50" style="width: 250px" value="<?php echo $category; ?>" readonly="readonly" disabled="disabled"></td>
			<td style="width: 15px; height: 19px;">
			</td>
		    <td style="width: 106px; height: 19px;">Currency Code</td>
	  	    <td style="width: 6px; height: 19px;">:</td>
	  	    <td style="width: 108px; height: 19px;">
			<input class="textnoentry1" name="currency_code" id ="unit_priceid5" type="text" maxlength="50" style="width: 240px; height: 19px;" value="<?php echo $currency_code; ?>" align="texttop" disabled="disabled"></td>
	  	  </tr>

		  <tr>
	  	    <td style="width: 2px; height: 28px;">
	  	    </td>
	  	    <td style="width: 114px; height: 28px;">Unit of Measurement</td>
	  	    <td style="width: 4px; height: 28px;">:</td>
	  	    <td style="width: 375px; height: 28px;">
	  	    <input class="textnoentry1" name="uom" id ="unit_priceid2" type="text" maxlength="50" style="width: 250px" value="<?php echo $uom; ?>" readonly="readonly" disabled="disabled"></td>
			<td style="width: 15px; height: 28px;"></td>
					    <td style="width: 106px; height: 28px;" class="tdlabel">
						Location</td>
	  	    <td style="width: 6px; height: 28px;">:</td>
	  	    <td style="width: 108px; height: 28px;">
			   <select name="sellocation" style="width: 235px">
			    <?php
                   $sql = "select loca_code, loca_desc from stk_location ORDER BY loca_desc ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected value = '$location'>$location_desc</option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['loca_code'].'">'.$row['loca_desc'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select></td>
	  	 
		  </tr>
		  <tr>
	  	    <td style="width: 2px; height: 16px;">
	  	    </td>
	  	    <td style="width: 114px; height: 16px;" class="tdlabel">Remark</td>
	  	    <td style="width: 4px; height: 16px;">:</td>
	  	    <td style="width: 375px; height: 16px;">
			<input class="textnoentry1" name="remark" id ="remarkid" type="text" maxlength="50" style="width: 365px" value="<?php echo $remark; ?>" readonly="readonly" disabled="disabled">
			</td>
			<td style="width: 15px; height: 16px;">
			</td>
			<td style="width: 106px; height: 28px;" class="tdlabel">Cost Price</td>
	  	    <td style="width: 6px; height: 28px;">:</td>
	  	    <td style="width: 108px; height: 28px;">
			<input class="inputtxt" name="cost_price" id ="cost_priceid" type="text" maxlength="50" style="width: 240px; height: 19px;" value="<?php echo $cost_price; ?>" align="texttop" onblur="showcostDecimal(this.value)">
			</td>
	  	 
		  </tr>
            <tr>
	  	    <td style="width: 2px; height: 16px;">
	  	    </td>
	  	    <td style="width: 114px; height: 16px;" class="tdlabel">Description</td>
	  	    <td style="width: 4px; height: 16px;">:</td>
	  	    <td style="width: 375px; height: 16px;">
			<input class="inputtxt" name="description" id ="descriptionid" type="text" maxlength="50" style="width: 365px" value="<?php echo $description; ?>">
			</td>
			<td style="width: 15px; height: 16px;">
			</td>
		    </tr>
          <tr>
           <td colspan="8" align="center">
	  	   <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px">
	  	   <input type=submit name = "Submit" value="Update" class="butsub" style="width: 60px; height: 32px">
	  	   </td>
	  	  </tr>
	  	</table>
	   </form>	
	  </fieldset>
	 </div>      
</body>
</html>
