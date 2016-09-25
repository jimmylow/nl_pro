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
      $var_proctyp = $_GET['procattyp'];
	  $var_procpre = $_GET['propref'];
	  $var_prosnum = $_GET['prosnum'];
	  $var_proenum = $_GET['proenum'];
	  $var_menucode = $_GET['menucd'];
	}
    
     if ($_POST['Submit'] == "Update") {
    
 		$pcatbuy = $_POST['procatbuy'];
        $pcattyp = $_POST['procattyp'];
        $pcatcd = $_POST['procatcd'];
        $pcatpre = $_POST['procatnumpre'];
        $pcatfnum = $_POST['procatfrnum'];
        $pcattnum = $_POST['procattonum'];
        $var_menucode  = $_POST['menudcode'];
    
         if ($pcatcd <> "") {
        
         $vartoday = date("Y-m-d H:i:s");
         $sql = "Update pro_cat_master set pro_buy_cd ='$pcatbuy',";
         $sql .= " pro_type_cd='$pcattyp',";
         $sql .= " modified_by='$var_loginid',";
         $sql .= " modified_on='$vartoday' WHERE pro_cat_cd = '$pcatcd' ";
         $sql .= " And pro_cat_prefix='$pcatpre' And pro_cat_frnum='$pcatfnum'";
         $sql .= " And pro_cat_tonum='$pcattnum'";
           
       	 mysql_query($sql); 
        
         $backloc = "../bom_master/pro_cat_master.php?menucd=".$var_menucode;
       
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
        
      }      
    }
    
    if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menudcode'];
         $backloc = "../bom_master/pro_cat_master.php?menucd=".$var_menucode;
        
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
<script type="text/javascript"> 

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

</script>
</head>

<body OnLoad="document.InpProCatMas.procatbuy.focus();">
<?php
        $sql = "select pro_buy_cd, pro_type_cd";
        $sql .= " from pro_cat_master";
        $sql .= " where pro_cat_cd ='".$var_proccd."' And pro_cat_prefix='".$var_procpre."'";
        $sql .= " And pro_cat_frnum ='".$var_prosnum."' And pro_cat_tonum='".$var_proenum."'";
        
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);

        $procbuycd = $row[0];
        $procbuytyp = $row[1];
        
        $sql = "select pro_buy_desc from pro_buy_master ";
        $sql .= " where pro_buy_code ='".$procbuycd."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $probdesc = $row[0];
        
        $sql = "select type_desc from protype_master ";
        $sql .= " where type_code ='".$procbuytyp."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $proctyp = $row[0];
?>	
  <?php include("../topbarm.php"); ?> 
  <?php include("../sidebarm.php"); ?> 
  <div style="position:absolute; top:120px; left:320px;">
	
	<fieldset name="Group1" style="height: 200px; width: 943px;" class="style2">
	 <legend class="title">EDIT PRODUCT CATEGORY MASTER : <?php echo $var_procpre.$var_proccd.' '.$var_prosnum.'-'.$var_proenum; ?> </legend>
	  <br>
	 
	  <form name="InpProCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 200px; width: 696px;">
		<input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table style="width: 923px; height: 118px">
	  	  <tr>
	  	    <td style="width: 5px"></td>
	  	    <td style="width: 123px">Product Buyer </td>
	  	    <td style="width: 12px">:</td>
	  	    <td>
	  	    
			<select name="procatbuy" style="width: 143px" >
			 <option  value="<?php echo $procbuycd; ?>"><?php echo $probdesc; ?></option>
			 <?php
              $sql = "select pro_buy_code, pro_buy_desc from pro_buy_master ORDER BY pro_buy_code ASC";
              $sql_result = mysql_query($sql);
              
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option disabled="disabled" value="'.$row['pro_buy_code'].'">'.$row['pro_buy_desc'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>

			</td>
	  	  </tr>
	  	  <tr>
	  	    <td style="width: 5px"></td> 
	  	    <td style="width: 123px"></td>
	  	    <td style="width: 12px"></td> 
            <td></td> 
	   	  </tr> 
	   	   <tr>
	   	    <td style="width: 5px"></td>
	  	    <td style="width: 123px">Product Type </td>
	  	    <td style="width: 12px">:</td>
	  	    <td>
			<select name="procattyp" style="width: 346px">
			 <option  value="<?php echo $procbuytyp; ?>"><?php echo $proctyp; ?></option>
			 <?php
              $sql = "select type_code, type_desc from protype_master ORDER BY type_code ASC";
              $sql_result = mysql_query($sql);
                                     
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['type_code'].'">'.$row['type_desc'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>

			</td>
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	  </tr>
	  	  
	  	  <tr>
	  	    <td></td>
	  	    <td>Code Number</td>
	  	    <td>:</td>
	  	    <td><strong>Prefix</strong> <strong>&nbsp;
			<input class="inputtxt" readonly="readonly" name="procatnumpre" id ="procatnumpre" type="text" style="width: 25px" value="<?php echo $var_procpre; ?>">&nbsp; &nbsp; Start&nbsp; </strong>&nbsp;<input class="inputtxt" readonly="readonly" name="procatfrnum" id ="procatfrnumid" type="text" maxlength="6" onchange ="upperCase(this.id)" style="width: 59px" onBlur="is_int(this.value, 1);" value="<?php echo $var_prosnum; ?>">
			<strong>&nbsp;
            End&nbsp;&nbsp; </strong>
   		   <input class="inputtxt" readonly="readonly" name="procattonum" id ="procattonumid" type="text" maxlength="6" onchange ="upperCase(this.id)" style="width: 65px" onBlur="is_int(this.value, 2);" value="<?php echo $var_proenum; ?>">

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
	  	   <td></td>
	  	   <td></td>
	  	   <td>
	  	
	  	   <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px" ><input type=submit name = "Submit" value="Update" class="butsub" style="width: 60px; height: 32px" ></td>
	  	  </tr>
	  	  <tr>
	  	   <td style="width: 5px"></td>
	  	   <td style="width: 123px"></td>
	  	   <td style="width: 12px"></td>
	  	   <td>
	  	   </td>
	  	  </tr>
	  	   <tr>
	  	    <td style="width: 5px"></td>  
	  	  </tr>
	  	</table>
	   </form>	
	   </fieldset>
	   </div>
</body>
</html>

