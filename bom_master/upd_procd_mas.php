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
   
    if ($_POST['Submit'] == "Update") {
     $prcode = $_POST['prodcode'];
     $prcat  = $_POST['procat']; 
     $prdesc = stripslashes(mysql_real_escape_string($_POST['procddesc']));
     $pruom  = $_POST['procduom'];
     $prrmk  = stripslashes(mysql_real_escape_string($_POST['procdrmk']));
     $prolddimg  = $_POST['oldimg'];
     $var_menucode  = $_POST['menudcode'];
     
     
     $exfacpcs = stripslashes(mysql_real_escape_string($_POST['exfacpcs']));
     $exfacdoz = stripslashes(mysql_real_escape_string($_POST['exfacdoz']));
     $costpcs = stripslashes(mysql_real_escape_string($_POST['costpcs']));
     $costdoz = stripslashes(mysql_real_escape_string($_POST['costdoz']));
     $tax = stripslashes(mysql_real_escape_string($_POST['tax']));
	 $buyprocd = stripslashes(mysql_real_escape_string($_POST['buyprocd']));
          
     if ($prcode <> ""){
     	$prdesc = str_replace("'", '^', $prdesc);
		$prrmk = str_replace("'", '^', $prrmk);

     
        if ($_FILES['uploadfile']['name'] <> "") {  
	      $dir = '../bom_master/procdimg/';
	      $oldimgdi = $dir.$prolddimg;
	      unlink($oldimgdi); 
	      $imgnm = htmlentities($prcode);
	      include("../Setting/uploadFuc.php");
	      $prolddimg = $imgnm.$ext; 
	    }	
	    
	    
    	if ($exfacpcs =='' or $exfacpcs== ' ' or $exfacpcs==NULL){$exfacpcs= 0; }
        if ($exfacdoz =='' or $exfacdoz == ' ' or $exfacdoz ==NULL){$exfacdoz = 0; }
        if ($unitdoz =='' or $unitdoz== ' ' or $$unitdoz==NULL){$$unitdoz= 0; }
        if ($unitpcs=='' or $unitpcs== ' ' or $unitpcs==NULL){$unitpcs= 0; }
        if ($tax=='' or $tax== ' ' or $tax==NULL){$tax= 0; }
        if ($costpcs=='' or $costpcs== ' ' or $costpcs==NULL){$costpcs= 0; }
        if ($costdoz=='' or $costdoz== ' ' or $costdoz==NULL){$costdoz= 0; }
        
        if ($exfacdoz <> 0)
        {
        	$exfacpcs = $exfacdoz / 12;
        }
        
        if ($costdoz<> 0)
        {
        	$costpcs= $costdoz / 12;
        }


        $moby= $var_loginid;
        $moon= date("Y-m-d H:i:s");
        $sql = "Update pro_cd_master set prod_desc ='$prdesc', prod_imgname= '$prolddimg', ";
        $sql .= " prod_uom = '$pruom', prod_rmk = '$prrmk', pro_cat='$prcat' ,modified_by='$moby',";
        $sql .= " modified_on='$moon', ";
        $sql .= " tax = '$tax', pro_buycd = '$buyprocd', ";
        $sql .= " xfac_dozprice = '$exfacdoz', ";
        $sql .= " xfac_pcsprice = '$exfacpcs', ";
        $sql .= " cost_pcsprice = '$costpcs', ";
        $sql .= " cost_dozprice = '$costdoz' ";       
        
        $sql .= " WHERE prod_code = '$prcode'"; 
       
        //mysql_query($sql) or die(mysql_error(), $sql);
        mysql_query($sql) or die("Error Update pro_cd_master : ".mysql_error(). ' Failed SQL is --> '. $sql);	
       
        $var_stat = 1;
        $backloc = "../bom_master/pro_code_master.php?menucd=".$var_menucode;
        
 		echo "<script>";
        echo 'location.replace("'.$backloc.'")';
        echo "</script>";
     }else{
       $var_stat = 4;
     }
    }
    
    if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menudcode'];
         $backloc = "../bom_master/pro_code_master.php?menucd=".$var_menucode;
        
 		 echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
    
   		$here = getcwd();
       // Redirect browser
        $fname = "pro_cat_master.rptdesign&__title=myReport"; 
        $dest = "http://".$var_server.":8080/birt-viewer/frameset?__report=pro_cat_master.rptdesign";
        $dest .= urlencode(realpath($fname));
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
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

function readURL(input) {

	if (input.files && input.files[0]) {
       var reader = new FileReader();

       reader.onload = function (e) {
       $('#proimgpre')
          .attr('src', e.target.result)
          .width(150)
          .height(120);
       };
       reader.readAsDataURL(input.files[0]);
       
    }
}
</script>
</head>
 
  <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="document.InpProCDMasU.procddescid.focus();">
<?php include("../topbarm.php"); ?> 
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
        $proddesc = htmlentities($row[6]);
        $produom = $row[7];
        $prodrmk = htmlentities($row[8]);
        $prodcreby = $row[10];
        $prodcreon = $row[11];
        $prodmodby = $row[12];
        $prodmodon = $row[13];
        $prodimg = $row[9];

        $exfacpcs = $row[17];
        $exfacdoz = $row[18];
        $costpcs = $row[19];
        $costdoz= $row[20];
        $tax= $row[21];
        $buyprocd = htmlentities($row[22]);
        
        $proddesc = str_replace("^", "'", $proddesc);
		$prodrmk = str_replace("^", "'", $prodrmk);

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
        
        $sql = "select clr_desc from pro_clr_master  ";
        $sql .= " where clr_code ='".$prodcol."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $prodcolde = $row[0];
        
        $sql = "select uom_desc from prod_uommas  ";
        $sql .= " where uom_code ='".$produom."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $produomde = $row[0];

        $dirimg = "../bom_master/procdimg/";
        $imgname = $dirimg.$prodimg;
?>		
 
  <div class="contentc">

	<fieldset name="InpProCDMasU" class="style2" style="width: 950px; height: 600px;">
	 <legend class="title">EDIT PRODUCT CODE <?php echo $var_prodcode;?></legend>
	  <br>
	  <form name="InpProCDMasU" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" enctype="multipart/form-data" style="height: 200px; width: 837px;">
		<input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<input name="oldimg" type="hidden" value="<?php echo $prodimg;?>">
		<table style="width: 903px" >
		   <tr>
	  	    <td style="width: 10px"></td>
	  	    <td style="width: 213px;">Product Code</td>
	  	    <td>:</td>
	  	    <td style="width: 603px;">
			<strong>
			<input class="inputtxt" readonly="readonly" name="prodcode" id ="prodbuyid" type="text" value="<?php echo $var_prodcode;?>" style="width: 192px;"></strong>
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td style="width: 10px"></td> 
	   	  </tr> 
	  	  <tr>
	  	    <td style="width: 10px"></td>
	  	    <td style="width: 213px;">Product Buyer 
			</td>
	  	    <td>:</td>
	  	    <td style="width: 603px;">
			<input class="inputtxt" readonly="readonly" name="prodbuy" id ="prodbuyid" type="text" value="<?php echo $prodbuyer;?>" style="width: 53px">
			<label id="Label1"><?php echo $prodbuyerde;?></label>
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td style="width: 10px"></td> 
	  	    <td style="width: 213px"></td>
	  	    <td></td> 
            <td style="width: 603px"></td> 
	   	  </tr> 
	   	   <tr>
	   	    <td style="width: 10px"></td>
	  	    <td style="width: 213px">Product Type </td>
	  	    <td>:</td>
	  	    <td style="width: 603px">
			<input class="inputtxt" readonly="readonly" name="prodtyp" id ="prodtypid" type="text" value="<?php echo $prodtype;?>" style="width: 75px;">
		    <label id="Label1"><?php echo $prodtypede;?></label>
			</td>
	  	  </tr>  
	  	  <tr>
	  	   <td style="width: 10px"></td>
	  	  </tr>
	  	  <tr>
	  	   <td style="width: 10px"></td>
	  	   <td style="width: 213px">Product Category</td>
	  	   <td>:</td>
	  	   <td style="width: 197px" colspan="5">
	  	   <input class="inputtxt" name="procat" id ="procatid" type="text" style="width: 74px" onchange ="upperCase(this.id)" value="<?php echo $prodcat; ?>">
		   </td>
	  	  </tr>
	  	  <tr>
	  	   <td style="width: 10px"></td>
	  	   <td style="width: 213px"></td>
	  	   <td></td>
	  	   <td style="width: 603px"><div id="msgcd"></div></td>
	  	  </tr>
	  	  <tr>
	  	    <td style="width: 10px"></td>
	  	    <td style="width: 213px">Size</td>
	  	    <td>:</td>
	  	    <td style="width: 603px">
			<input class="inputtxt" readonly="readonly" name="prosiz" id ="prosizid" type="text" style="width: 148px" value="<?php echo $prodsiz; ?>">
			</td>
			<td></td>
			<td style="width: 71px">Colour</td>
			<td>:</td>
			<td>
			<input class="inputtxt" readonly="readonly" name="procol" id ="procol" type="text" style="width: 71px" value="<?php echo $prodcol; ?>">
		    <label id="Label1"><?php echo $prodcolde;?></label>
			</td>
	  	  </tr>
	  	  <tr>
	  	   <td style="width: 10px; height: 22px;"></td>
	  	   <td style="width: 213px; height: 22px;"></td>
	  	   <td style="height: 22px"></td>
	  	   <td style="width: 603px; height: 22px;"><div id="msgchk"></div></td>
	  	   <td></td>
	  	   <td></td>
	  	  </tr>
	  	   <tr>
	  	   <td style="width: 10px; height: 26px;"></td>
	  	   <td style="width: 213px; height: 26px;">Description</td>
	  	   <td style="height: 26px">:</td>
	  	   <td style="width: 603px; height: 26px;">
   			<input class="inputtxt" name="procddesc" id ="procddescid" type="text" maxlength="60" style="width: 323px;" value="<?php echo $proddesc; ?>"></td>
	  	   <td style="height: 26px"></td>
           <td style="height: 26px">UOM</td>
           <td style="height: 26px">:</td>
           <td style="height: 26px">
	  	   <select name="procduom" style="width: 134px">
	  	     <option value="<?php echo $produom; ?>"><?php echo $produomde; ?></option>
			 <?php
              $sql = "select uom_code, uom_desc from prod_uommas ORDER BY uom_code";
              $sql_result = mysql_query($sql);
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['uom_code'].'">'.$row['uom_desc'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select></td>
	  	  </tr>
	  	   <tr>
	  	    <td style="width: 10px"></td>
	  	  </tr>
	  	   <tr>
	  	    <td style="height: 14px; width: 10px;"></td>
	  	    <td style="width: 213px; height: 14px;">Remark</td>
            <td style="height: 14px">:</td>
            <td style="width: 603px; height: 14px;">
		   <input class="inputtxt" name="procdrmk" id ="procdrmkid" type="text" maxlength="60" style="width: 323px;" value="<?php echo $prodrmk; ?>"></td>
	  	    <td style="height: 14px"></td>
	  	    <td style="width: 71px; height: 14px;">Customer Item Code</td>
	  	    <td style="height: 14px">:</td>
	  	    <td style="height: 14px">
	  	    <input class="inputtxt" name="buyprocd" id ="buyprocd" type="text" maxlength="60" style="width: 150px;" value="<?php echo $buyprocd; ?>" />
	  	   	   </td>
	  	  </tr>
	  	    <tr>
	  	    <td></td>
	  	    </tr>
			<tr>
	  	    <td></td>
	  	    <td style="width: 187px;;">Cost Price (Doz)</td>
            <td>:</td>
            <td style="width: 197px;">
			<input class="inputtxt" name="costdoz" id ="costdoz" type="text" maxlength="10" onchange ="upperCase(this.id)" value="<?php echo $costdoz; ?>"></td>
	  	    <td style="width: 31px;"></td>
	  	    <td style="width: 124px;">Ex Fac. Price (Doz)</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="exfacdoz" id ="exfacdoz" type="text" maxlength="10" onchange ="upperCase(this.id)" value="<?php echo $exfacdoz; ?>"></td>
	  	    </tr>
	  	    <tr>
	  	   <td></td>
	  	   <td style="width: 113px"></td>
	  	   <td style="width: 10px"></td>
	  	   <td style="width: 197px"></td>
	  	    </tr>
			<tr>
	  	    <td style="height: 20px"></td>
	  	    <td style="width: 187px; height: 20px;"></td>
            <td style="height: 20px"></td>
            <td style="width: 197px; height: 20px;">
		   	 </td>
	  	    <td style="width: 31px; height: 20px;"></td>
	  	    <td style="width: 124px; height: 20px;"></td>
	  	    <td style="height: 20px"></td>
	  	    <td style="height: 20px"></td>
	  	    </tr>
			<tr>
	  	    <td></td>
	  	    <td style="width: 187px;;">Tax (%)</td>
            <td>: </td>
            <td style="width: 197px;">
		   <input class="inputtxt" name="tax" id ="tax" type="text" maxlength="10" style="width: 100px;" value="<?php echo $tax; ?>"></td>
	  	    <td style="width: 31px;"></td>
	  	    <td style="width: 124px;"></td>
	  	    <td></td>
	  	    <td></td>
	  	    </tr>
	  	  <tr>
	  	    <td style="width: 10px"></td>
	  	  </tr>
	  	  <tr>
	  	   <td style="width: 10px"></td>
	  	   <td style="width: 213px">Product Image<br>(Max Image Size 2MB)</td>
	  	   <td>:</td>
	  	   <td style="width: 603px">
		   <input name="uploadfile" style="width: 315px" type="file" onchange="readURL(this);"></td> 
	  	   <td></td>
	  	   <td colspan="3" rowspan="6">
	  	   <a href="<?php echo $imgname; ?>" rel="lightbox">
	  	   <img id="proimgpre" height="120" width="150" src="<?php echo $imgname; ?>">
	  	   </a>
	  	   </td>  			
	  	  </tr>
    	  <tr>
	  	    <td style="width: 10px"></td>
	  	    <td>(.jpg, .png, .gif only)</td>
	  	    <td></td>
	  	  </tr>
	  	  <tr>
	  	    <td style="width: 10px"></td>
	  	    <td style="width: 213px">&nbsp;</td>
	  	    <td>&nbsp;</td>
	  	    <td style="width: 603px"> 
		    &nbsp;</td>  
	  	  </tr>
	  	  <tr>
	  	    <td style="width: 10px"></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td></td>
	  	   <td></td>
	  	   <td align="center" style="width: 603px">
	  	   <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px" >
	  	   <input type=submit name = "Submit" value="Update" class="butsub" style="width: 60px; height: 32px" >
	  	   </td>
	  	  </tr>
	  	 </table>
	   </form>	
	</fieldset>
	</div>
</body>
</html>
