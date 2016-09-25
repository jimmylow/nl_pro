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
        $sql .= " tax = '$tax', ";
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
		<table style="width: 853px" >
		   <tr>
	  	   <td rowspan="2">
	  	   &nbsp;</td>  			
	  	    <td colspan="4">
	  	    <?php
	  	       echo '<table id="itemsTable" class="general-table">
          	<thead>
          	 <tr>
              <th class="tabheader" style="width: 27px; height: 57px;">#</th>
              <th class="tabheader">Raw Material Item</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Unit Cost</th>
             </tr>
            </thead>
            <tbody>';
     
             	$sql = "SELECT prod_code  ";
             	$sql.= " FROM pro_cd_master ";
             	$sql.= " WHERE xfac_dozprice = 0 or xfac_dozprice is null ";
             	$sql.= " ORDER BY x.seqno";
             	echo $sql;
             	

 
				$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){
					$sql2 = " select sum(totalqty) as receivedqty  ";
					$sql2.= " FROM rawmat_receive_tran ";
					$sql2.= " WHERE po_number =  '". $supp_code. "'";
					$sql2.= " AND item_code =  '". $rowq['itemcode']."'";
					
					$receivedqty = 0;
					$sql_result2 = mysql_query($sql2) or die("Cant Get Total Received Qty ".mysql_error());;
					$row2 = mysql_fetch_array($sql_result2);
					$receivedqty = $row2[0];
					if ($receivedqty=='' or $receivedqty== ' ' or $receivedqty ==NULL){ $receivedqty=0; }

             	
					echo '<tr class="item-row">';	
					echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>'; 
					echo '<td><input name="procomat[]" value="'.htmlentities($rowq['itemcode']).'" id="procomat'.$i.'" class="autosearch" style="width: 161px"></td>';
					echo '<td><input name="procodesc[]" value="'.$rowq['description'].'" id="procodesc" style="width: 303px; border-style: none;" readonly="readonly"></td>';
					echo '<td><input name="procouom[]" value="'.$rowq['uom'].'" id="procouom" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 48px;"></td>';
					echo '<td><input name="uprice[]" tMark="1" id="uprice" readonly="readonly" style="width: 75px; border:0;" value="'.$rowq['uprice'].'"></td>';           	
					echo ' </tr>';
               	
          $i = $i + 1;
         }
  echo '</tbody></table>';
 ?>
	  	    
	  	    
	  	   
	  	    </td>
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
