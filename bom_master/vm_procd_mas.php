<?php

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	$var_loginid = $_SESSION['sid'];

    if($var_loginid == "") { 
      echo "<script>";   
      echo "alert('Not Log In to the system');"; 
      echo "</script>"; 

      echo "<script>";
      echo 'top.location.href = "../login.htm"';
      echo "</script>";
    } else {
      $var_stat = $_GET['stat'];
      $var_prodcode = htmlentities($_GET['procd']);
      $var_menucode = $_GET['menucd'];    
    }
    
    if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menudcode'];
         $backloc = "../bom_master/pro_code_master.php?menucd=".$var_menucode;
        
 		 echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
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
<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="../js/imgjs/prototype.js"></script>
<script type="text/javascript" src="../js/imgjs/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="../js/imgjs/lightbox.js"></script>


<script type="text/javascript" charset="utf-8"> 
</script>
</head>
<body>

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
        $prodcreon = date('d-m-Y', strtotime($row[11]));
        $prodmodby = $row[12];
        $prodmodon = date('d-m-Y', strtotime($row[13]));
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
        
        
        if ($costdoz==0 ){
        $sql = "select totcost as costdoz, totamt as exfacdoz, totcost/12 as costpcs, totamt/12 as exfacpcs from prod_matmain ";
        $sql .= " where prod_code ='".$var_prodcode."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $costdoz= sprintf('%0.3f',round($row[0],4));
        $exfacdoz= sprintf('%0.3f',round($row[1],4));
        $costpcs= sprintf('%0.3f',round($row[2],4));
        $exfacpcs= sprintf('%0.3f',round($row[3],4));
        }

        
        $dirimg = "../bom_master/procdimg/";
        $imgname = $dirimg.$prodimg;
?>		
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc">

	<fieldset name="InpProCDMasV" class="style2" style="width: 800px; height: 600px;">
	 <legend class="title">PRODUCT CODE MASTER <?php echo $var_prodcode;?></legend>
	  <br>
	 	<form name="InpProCDMasV" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" enctype="multipart/form-data" style="height: 200px; width: 936px;">
		<input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">

		<table style="width: 928px" >
	  	  <tr>
	  	    <td style="height: 28px"></td>
	  	    <td style="width: 113px;">Product Code</td>
	  	    <td style="width: 10px">:</td>
	  	    <td style="width: 197px;">
			<input class="inputtxt" readonly="readonly" name="prodcode" id ="prodcodeid" type="text" value="<?php echo $var_prodcode;?>" style="width: 150px"></td>
			<td></td>
			<td colspan="3" rowspan="6">
			<a href="<?php echo $imgname; ?>" rel="lightbox">
			<img id="proimgpre" height="120" width="150" src="<?php echo $imgname; ?>">
			</a>
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td style="width: 113px"></td>
	  	    <td style="width: 10px"></td> 
            <td style="width: 197px" colspan="5"></td> 
	   	  </tr> 
	   	   <tr>
	   	    <td></td>
	  	    <td style="width: 113px">Product Buyer</td>
	  	    <td style="width: 10px">:</td>
	  	    <td style="width: 197px" colspan="5">
			<input class="inputtxt" readonly="readonly" name="prodbuy" id ="prodbuyid" type="text" value="<?php echo $prodbuyer;?>" style="width: 53px">
			<label id="Label1"><?php echo $prodbuyerde;?></label>
			</td>
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 113px">Product Type</td>
	  	   <td style="width: 10px">:</td>
	  	   <td style="width: 197px" colspan="5">
           <input class="inputtxt" readonly="readonly" name="prodtyp" id ="prodtypid" type="text" value="<?php echo $prodtype;?>" style="width: 75px; height: 22px;">
		   <label id="Label1"><?php echo $prodtypede;?></label>
		   </td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 113px"></td>
	  	   <td style="width: 10px"></td>
	  	   <td style="width: 197px"><div id="msgcd"></div></td>
	  	  </tr>
	  	  <tr>
	  	    <td></td>
	  	    <td style="width: 113px">Product Category</td>
	  	    <td style="width: 10px">:</td>
	  	    <td style="width: 197px">
			<input class="inputtxt" readonly="readonly" name="procat" id ="procatid" type="text" style="width: 74px" value="<?php echo $prodcat; ?>"></td>
			<td style="width: 8px"></td>
			<td style="width: 229px">&nbsp;</td>
			<td style="width: 7px">&nbsp;</td>
			<td>
			&nbsp;</td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 113px"></td>
	  	   <td style="width: 10px"></td>
	  	   <td style="width: 197px"></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 113px">Size</td>
	  	   <td style="width: 10px">:</td>
	  	   <td style="width: 197px">
		   <input class="inputtxt" readonly="readonly" name="prosiz" id ="prosizid" type="text" style="width: 148px" value="<?php echo $prodsiz; ?>">
		   </td>
		   <td style="width: 8px"></td>
		   <td style="width: 229px">Colour</td>
		   <td style="width: 7px">:</td>
		   <td>
		   <input class="inputtxt" readonly="readonly" name="procol" id ="procol" type="text" style="width: 71px" value="<?php echo $prodcol; ?>">
		   <label id="Label1"><?php echo $prodcolde;?></label>
		   </td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 113px"></td>
	  	   <td style="width: 10px"></td>
	  	   <td style="width: 197px"></td>
	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td style="width: 113px">Description</td>
	  	   <td style="width: 10px">:</td>
	  	   <td style="width: 197px">
   			<input class="inputtxt" readonly="readonly" name="procddesc" id ="procddescid" type="text" style="width: 363px;" value="<?php echo $proddesc; ?>"></td>
	  	   <td style="width: 8px"></td>
           <td style="width: 229px">UOM</td>
           <td style="width: 7px">:</td>
           <td>
		   <input class="inputtxt" readonly="readonly" name="prouom" id ="procol0" type="text" style="width: 71px" value="<?php echo $produom; ?>"></td>
	  	  </tr>
	  	   <tr>
	  	    <td></td>
	  	  </tr>
	  	   <tr>
	  	    <td style="height: 14px"></td>
	  	    <td style="width: 113px; height: 14px;">Remark</td>
            <td style="height: 14px; width: 10px;">:</td>
            <td style="width: 197px; height: 14px;">
		   <input class="inputtxt" readonly="readonly" name="procdrmk" id ="procdrmkid" type="text" style="width: 361px;" value="<?php echo $prodrmk; ?>" /></td>
	  	    <td style="height: 14px; width: 8px;"></td>
	  	    <td style="width: 229px;">Customer Item Code</td>
	  	    <td>:</td>
	  	    <td>
	  	    <input class="inputtxt" name="buyprocd" id ="buyprocd" type="text" maxlength="60" style="width: 150px;" readonly="readonly" value="<?php echo $buyprocd; ?>" />
	  	   	   </td>
	  	  </tr>
	  	    <tr>
	  	   <td></td>
	  	   <td style="width: 113px"></td>
	  	   <td style="width: 10px"></td>
	  	   <td style="width: 197px"></td>
	  	    </tr>
			<tr>
	  	    <td></td>
	  	    <td style="width: 187px;;">Ex-Factory Price (PCS)</td>
            <td>:</td>
            <td style="width: 197px;">

		   <input class="inputtxt" name="exfacpcs" id ="exfacpcs" type="text" maxlength="10" style="width: 100px;" value="<?php echo $exfacpcs; ?>"></td>
	  	    <td style="width: 31px;"></td>
	  	    <td style="width: 229px;;">Ex-Factory Price (DOZ)</td>
            <td>:</td>
            <td style="width: 197px;">
		   <input class="inputtxt" name="exfacdoz" id ="exfacdoz" type="text" maxlength="10" style="width: 100px;" value="<?php echo $exfacdoz; ?>"></td>
	  	    </tr>
			<tr>
	  	    <td></td>
	  	    <td style="width: 187px;;">&nbsp;</td>
            <td>&nbsp;</td>
            <td style="width: 197px;">
		    &nbsp;</td>
	  	    <td style="width: 31px;"></td>
	  	    <td style="width: 229px;"></td>
	  	    <td></td>
	  	    <td></td>
	  	    </tr>
			<tr>
	  	    <td></td>
	  	    <td style="width: 187px;;">Cost Price (PCS)</td>
            <td>:</td>
            <td style="width: 197px;">
		   <input class="inputtxt" name="costpcs" id ="costpcs" type="text" maxlength="10" style="width: 100px;" value="<?php echo $costpcs; ?>"></td>
	  	    <td style="width: 31px;"></td>
	  	    <td style="width: 229px;;">Cost Price (DOZ)</td>
            <td>:</td>
            <td style="width: 197px;">
		   <input class="inputtxt" name="costdoz" id ="costdoz" type="text" maxlength="10" style="width: 100px;" value="<?php echo $costdoz; ?>"></td>
	  	    </tr>
			<tr>
	  	    <td style="height: 20px"></td>
	  	    <td style="width: 187px; height: 20px;"></td>
            <td style="height: 20px"></td>
            <td style="width: 197px; height: 20px;">
		   	 </td>
	  	    <td style="width: 31px; height: 20px;"></td>
	  	    <td style="width: 229px; height: 20px;"></td>
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
	  	    <td style="width: 229px;"></td>
	  	    <td></td>
	  	    <td></td>
	  	    </tr>
	  	  <tr>
	  	    <td></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 113px">Create By</td>
	  	   <td style="width: 10px">:</td>
	  	   <td>
		   <input class="textnoentry1" readonly="readonly" name="procdcreby" id ="procdcrebyid" type="text" style="width: 172px;" value="<?php echo $prodcreby; ?>"></td> 
	  	   <td style="width: 8px"></td>
	  	   <td style="width: 229px">Create On</td>
	  	   <td>:</td>
	  	   <td>
	  	   <input class="textnoentry1" readonly="readonly" name="procdcreon" id ="procdcreonid" type="text" style="width: 172px;" value="<?php echo $prodcreon; ?>"> 
	  	   </td>  			
	  	  </tr>
    	  <tr>
	  	    <td></td>
	  	    <td style="width: 113px">&nbsp;</td>
	  	    <td style="width: 10px"></td>
	  	  </tr>
	  	  <tr>
	  	    <td></td>
	  	    <td style="width: 113px">Modified By</td>
	  	    <td style="width: 10px">:</td>
	  	    <td> 
		    <input class="textnoentry1" readonly="readonly" name="procdmodby" id ="procdcrebyid0" type="text" style="width: 172px;" value="<?php echo $prodmodby; ?>">
		    </td>
		    <td></td>
		    <td style="width: 229px">Modified On</td>
		    <td>:</td>
		    <td>
	  	   <input class="textnoentry1" readonly="readonly" name="procdmodon" id ="procdcreonid0" type="text" style="width: 172px;" value="<?php echo $prodmodon; ?>"></td>
	  	  </tr>
	  	  <tr>
	  	    <td></td>
	  	  </tr>
	  	</table>
	    <table>
  	 	<tr>
		<td align="center" style="width: 858px">
	  	<input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="javascript: location.replace('../main_mas/supp_mas.php')"></td>
        </tr>
	  	</table>
	   </form>	
	   </fieldset>
	  </div> 
</body>
</html>
