<?php

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	$var_loginid = $_SESSION['sid'];
	$var_loginid = 'admin';
    
    if($var_loginid == "") { 
      echo "<script>";   
      echo "alert('Not Log In to the system');"; 
      echo "</script>"; 

      echo "<script>";
      echo 'top.location.href = "../login.htm"';
      echo "</script>";
    } else {
    
      $var_stat = $_GET['stat'];
      $var_menucode = $_GET['menucd'];
      $var_prodcode = htmlentities($_GET['procd']);
    }
   
    if ($_POST['Submit'] == "Delete") {
     $oprcode = $_POST['prodcode'];
     
     $nprsiz  = $_POST['rprocdsiz'];
     $nprclr  = $_POST['rprocdcol'];
     $nprdesc = $_POST['rprocddesc'];
     $npruom  = $_POST['rprocduom'];
     $nprrmk  = $_POST['procdrmk'];
     $var_menucode  = $_POST['menudcode'];
          
     if ($oprcode <> ""){       

		$sql = "select * from pro_cd_master";
        $sql .= " where prod_code ='".$oprcode."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);

        $oprbuyer  = $row[1];
        $oprtype   = $row[2];
        $oprcatpre = $row[3];
        $oprsiz    = $row[4];
        $oprcol    = $row[5];
        $oprdesc   = $row[6];
        $opruom    = $row[7];
        $oprrmk    = $row[8];
        $oprimg    = $row[9];
		$oprcreby  = $row[10];
        $oprcreon  = $row[11];        
 		$oprmodby  = $row[12];
        $oprmodon  = $row[13];
        $oprcat    = $row[14];
        $oprstat   = $row[15];
        $opbuyprocd = $row['pro_buycd'];
    
    	$vartoday = date("Y-m-d H:i:s");
		$sql = "INSERT INTO logpro_cd_master values 
		       ('$oprcode', '$oprbuyer', '$oprtype','$oprcatpre','$oprsiz','$oprcol', '$oprdesc',
		       	'$opruom','$oprrmk','$oprimg','$oprcreby', '$oprcreon','$oprmodby', '$oprmodon',
		       	'$oprcat', '$oprstat', '$vartoday')";
	    mysql_query($sql) or die(mysql_error);

		$posdash = strpos($oprcode, "-");
		$substrcd = substr($oprcode, 0, $posdash);
        $newprodcd = $substrcd."-".$nprclr."-".$nprsiz;

		$moby= $var_loginid;
        $moon= date("Y-m-d H:i:s");
        $sql = "Update pro_cd_master set prod_code ='$newprodcd', prod_size = '$nprsiz', prod_col = '$nprclr',";
        $sql .= " prod_desc = '$nprdesc', prod_uom = '$npruom', prod_rmk = '$nprrmk', create_by='$moby', create_on = '$moon', modified_by='$moby',";
        $sql .= " modified_on='$moon', actvty = 'A', pro_buycd = '$opbuyprocd' WHERE prod_code = '$oprcode'";
        mysql_query($sql) or die(mysql_error);
        
        echo "<script>";   
        echo "alert('New Product Code: ".$newprodcd."');"; 
        echo "</script>";
              
        $backloc = "../bom_master/pro_code_master.php?menucd=".$var_menucode;
 		echo "<script>";
        echo 'location.replace("'.$backloc.'")';
        echo "</script>";
      }
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<link rel="stylesheet" href="../css/lightbox.css" type="text/css" media="screen">	
<style media="all" type="text/css">@import "../css/styles.css";
.style2 {
	margin-right: 0px;
}
.style3 {
	color: #FF0000;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="../js/imgjs/prototype.js"></script>
<script type="text/javascript" src="../js/imgjs/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="../js/imgjs/lightbox.js"></script>

<script type="text/javascript" charset="utf-8"> 
function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

function chkSubmit() {
 	
 	if (document.getElementById("rprocdsizid").value == "") {
      	alert ("Replace Size For This Product Code Cannot Be Blank");
      	document.InpProCDMasU.rprocdsizid.focus();
      	return false;
    }
     	
 	if (document.getElementById("rprocdcol").value == "") {
      	alert ("Replace Colour For This Product Code Cannot Be Blank");
      	document.InpProCDMasU.rprocdcol.focus();
      	return false;
    }
    
    var oldsiz = document.forms["InpProCDMasU"]["prosiz"].value;
    var oldclr = document.forms["InpProCDMasU"]["procol"].value;
    var newsiz = document.forms["InpProCDMasU"]["rprocdsiz"].value;
    var newclr = document.forms["InpProCDMasU"]["rprocdcol"].value;
	
	if ((oldsiz == newsiz) && (oldclr == newclr)){
		alert ("Replace Size And Replace Colour Cannot Same With Delete Product Code Size And Colour");
      	document.InpProCDMasU.rprocdsiz.focus();
      	return false;
	}
	
}	
</script>
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>--> 

</head>

  <?php
        $sql = "select *";
        $sql .= " from pro_cd_master";
        $sql .= " where prod_code ='".$var_prodcode."'";
        
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);

        $prodbuyer = $row[1];
        $prodtype = $row[2];
        $prodcat = $row[14];
        $prodsiz = $row[4];
        $prodcol = $row[5];
        $proddesc = $row[6];
        $produom = $row[7];
        $prodrmk = $row[8];
        $prodcreby = $row[10];
        $prodcreon = $row[11];
        $prodmodby = $row[12];
        $prodmodon = $row[13];
        $prodimg = $row[9];

        $sql = "select pro_buy_desc from pro_buy_master  ";
        $sql .= " where pro_buy_code ='".$prodbuyer."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $prodbuyerde = $row[0];
        
        $sql = "select type_desc from protype_master ";
        $sql .= " where type_code ='".$prodtype."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $prodtypede = $row[0];
        
        $sql = "select colour_desc from colour_master  ";
        $sql .= " where colour_code ='".$prodcol."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $prodcolde = $row[0];
        
        $sql = "select uom_desc from uom_master  ";
        $sql .= " where uom_code ='".$produom."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $produomde = $row[0];

        $dirimg = "../bom_master/procdimg/";
        $imgname = $dirimg.$prodimg;
?>		
 <body onload="document.InpProCDMasU.rprocdsizid.focus();">
  <div class="contentc">

	<fieldset name="InpProCDMasU" class="style2" style="width: 950px; height: 280px;">
	 <legend class="title">DELETE PRODUCT CODE <?php echo $var_prodcode;?></legend>
	  <br>
	  <form name="InpProCDMasU" method="POST" onSubmit= "return chkSubmit()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" enctype="multipart/form-data">
		<input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<input name="oldimg" type="hidden" value="<?php echo $prodimg;?>">
		<table style="width: 943px">
		   <tr>
	  	    <td></td>
	  	    <td><strong>Product Code Delete</strong></td>
	  	    <td>:</td>
	  	    <td>
			<strong>
			<input class="inputtxt" readonly="readonly" name="prodcode" id ="prodcdid" type="text" value="<?php echo $var_prodcode;?>" style="width: 192px;">
			</strong>
			</td>
			<td></td>
			<td><strong>Size</strong></td>
			<td>:</td>
			<td><input class="inputtxt" readonly="readonly" name="prosiz" id ="prosizid" type="text" style="width: 148px" value="<?php echo $prodsiz; ?>"></td>
			<td></td>
			<td><strong>Colour</strong></td>
			<td>:</td>
			<td><input class="inputtxt" readonly="readonly" name="procol" id ="procol" type="text" style="width: 71px" value="<?php echo $prodcol; ?>">
				<label id="Label1"><?php echo $prodcolde;?></label>
			</td>
	  	  </tr>
	   </table>
	   <br>
	  
	   <fieldset>
	   <legend  class="title">Replace With</legend>
	   <table>	  
	  	  <tr>
	   		<td style="width: 20px"></td>
	   		<td style="width: 105px">Size <span class="style3">*</span></td>
	   		<td>:</td>
	   		<td style="width: 263px">
	   		<input class="inputtxt" name="rprocdsiz" id ="rprocdsizid" type="text" maxlength="10" onchange ="upperCase(this.id)">
	   		</td>
	   		<td style="width: 50px"></td>
	   		<td style="width: 182px">Colour <span class="style3">*</span></td>
	   		<td>:</td>
	   		<td>
	   			<select name="rprocdcol" id="rprocdcol" style="width: 187px" onchange="AjaxFunction(this.value);">
				 <?php
            	  $sql = "select clr_code, clr_desc from pro_clr_master ORDER BY clr_code ASC";
            	  $sql_result = mysql_query($sql);
            	  echo "<option size =30 selected></option>";
                       
				  if(mysql_num_rows($sql_result)) 
				  {
				   while($row = mysql_fetch_assoc($sql_result)) 
				   { 
					echo '<option value="'.$row['clr_code'].'">'.$row['clr_code']." | ".$row['clr_desc'].'</option>';
				   } 
		    	  } 
	        	 ?>				   
	       		</select>
	   		</td>
	   	  </tr>
	   	  <tr><td></td></tr>
	   	  <tr>
	   	  	<td></td>
	   	  	<td>Description</td>
	   	  	<td>:</td>
	   	  	<td>
	   	  	<input class="inputtxt" name="rprocddesc" id ="rprocddescid" type="text" maxlength="60" style="width: 323px;">
	   	  	</td>
	   	  	<td></td>
	   	  	<td>UOM</td>
	   	  	<td>:</td>
	   	  	<td>
	   	  		<select name="rprocduom" style="width: 134px">
			 		<?php
              			$sql = "select uom_code, uom_desc from prod_uommas ORDER BY uom_code";
              			$sql_result = mysql_query($sql);
              			echo "<option size =30 selected></option>";
                       
			  			if(mysql_num_rows($sql_result)) 
			  			{
			  				 while($row = mysql_fetch_assoc($sql_result)) 
			  				 { 
								echo '<option value="'.$row['uom_code'].'">'.$row['uom_desc'].'</option>';
			  				 } 
		      			} 
	         	   ?>				   
	       		</select>
	   	  	</td>
	   	  </tr>	
	   	  <tr><td></td></tr>
	   	  <tr>
	   	  	<td></td>
	   	  	<td>Remark</td>
	   	  	<td>:</td>
	   	  	<td>
	   	  	<input class="inputtxt" name="procdrmk" id ="procdrmkid" type="text" maxlength="60" style="width: 323px;">
	   	  	</td>
	   	  </tr>
	   	  <tr><td></td></tr> 
	  </table>
	  </fieldset>
	  <br>
	  <table>
	     <tr>
	  	  	<td align="center" style="width: 935px">
	  	  		 <?php
	  	 		  $locatr = "pro_code_master.php?menucd=".$var_menucode;			
		 		  echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				 ?>	
	  	  		 <input type=submit name = "Submit" value="Delete" class="butsub" style="width: 60px; height: 32px" >
	  	  	</td>
	  	  </tr>
	  </table>	 
	  </form>	
	</fieldset>
	</div>
</body>
</html>
