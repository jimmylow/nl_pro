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
      $var_menucode = $_GET['menucd'];
      $var_prodcd = $_GET['procd'];
      include("../Setting/ChqAuth.php");
    }
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<style media="all" type="text/css">
@import "../css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css";
@import "../css/styles.css";

.general-table #procomat                        { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}
.general-table #procoucost                      { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}
.general-table #prococompt                      { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}

.style2 {
	margin-right: 0px;
}
</style>

<!-- jQuery libs -->
<script type="text/javascript" src="../js/multitable/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>

<script type="text/javascript"> 


function calsalrm(){
	var exftramt  = document.InpJobFMas.totalexft0.value;
	var persaltax = document.InpJobFMas.totaltaxd.value;
	var saltaxamt;
	var saltotamt;
	
	if (exftramt == ""){
	 	exftramt = 0;
	 	document.InpJobFMas.totalexft0.value = parseFloat(exftramt).toFixed(4);
	}
	if (persaltax == ""){
	 	exftramt = 0;
	 	document.InpJobFMas.totaltaxd.value = parseFloat(persaltax);
	}
	saltaxamt = parseFloat(exftramt) * (parseFloat(persaltax)/100);
	saltotamt = parseFloat(exftramt) + parseFloat(saltaxamt);
	document.InpJobFMas.totalsaltid.value = parseFloat(saltaxamt).toFixed(4);
	document.InpJobFMas.totalamtid.value = parseFloat(saltotamt).toFixed(4);
}

</script>
</head>
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>-->

<body>
  <?php
  	 $sql = "select * from prod_matmain";
     $sql .= " where prod_code ='".$var_prodcd."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $docdte = date('d-m-Y', strtotime($row['docdate']));
     $rmcost = $row['rmcost'];
     $labcst = $row['labcost'];
     $ovecst = $row['overcost'];
     $mscbcst = $row['totmscb'];
     $tcost = $row['totcost'];
     $extamt = $row['exftrycost'];
     $saltper = $row['salestper'];
     $saltamt = $row['salesamt'];
	 $tamt = $row['totamt'];
	 
     $modified_by = $row['modified_by'];
     $modified_on = date("d-m-Y", strtotime($row['create_on']));              
     $create_by = $row['create_by'];
     $creation_time = date("d-m-Y", strtotime($row['modified_on']));
	
  ?>
  <div class ="contentc">

	<fieldset name="Group1" style=" width: 1150px;" class="style2">
	 <legend class="title">PRODUCT COSTING DETAIL - <?php echo $var_prodcd;?></legend>
	  <br>	 
	  
	  <form name="InpJobFMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
	   
		<table style="width: 886px">
	   	  <tr>
	  	   <td></td>
	  	   <td style="width: 126px">Product Code </td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
		   <input name="prod_code" id="prod_code" type="text" style="width: 129px" readonly="readonly" value="<?php echo $var_prodcd; ?>">
		   </td>
		   <td></td>
		   <td>Date</td>
		   <td>:</td>
		   <td>
		    <input class="inputtxt" readonly="readonly" name="prorevdte" id ="prorevdte" type="text" style="width: 106px;" value="<?php  echo $docdte; ?>"></td>
	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td style="width: 126px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 239px"></td>
	  	  </tr>
		  <tr>
	  	   <td></td>
	  	   <td style="width: 126px">Raw Material</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
		   <input readonly="readonly" name="totalmatc" id ="totalmatcid" type="text" style="width: 156px; color:black" class="textnoentry1" value="<?php echo $rmcost;?>"></td>
		   <td></td>
		   <td style="width: 136px">Ex-Factory</td>
		   <td>:</td>
		   <td>
		   <input name="totalexft" id ="totalexft0" type="text" style="width: 156px; text-align:center;" readonly="readonly" value="<?php echo $extamt; ?>"></td>
		  </tr> 
		  <tr>
	  	   <td></td>
	  	   <td style="width: 126px">Labour</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
		   <input readonly="readonly" name="totallabc" id ="totallabcid" type="text" style="width: 156px;" class="textnoentry1" value="<?php echo $labcst; ?>"></td>
		   <td></td>
		   <td style="width: 136px">Sales Tax (%)</td>
		   <td>:</td>
		   <td>
		   <input name="totaltaxc" id ="totaltaxd" type="text" style="width: 156px; " class="textnoentry1" value="<?php echo $saltper; ?>" readonly="readonly"></td>
	  	  </tr>
		  <tr>
	  	   <td></td>
	  	   <td>Overhead</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
		   <input name="totaloveh" id ="totalovehid" type="text" style="width: 156px; text-align:center;" value="<?php echo $ovecst;?>">
		   </td>
		   <td></td>
		   <td style="width: 136px">Sales Tax (RM)</td>
		   <td>:</td>
		   <td>
		   <input readonly="readonly" name="totalsalt" id ="totalsaltid" type="text" style="width: 156px;" class="textnoentry1" value="<?php echo $saltamt; ?>"></td>
	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td>Total M,S,C,B</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
		   <input readonly="readonly" name="totalmix" id ="totalmixid" type="text" style="width: 156px;" class="textnoentry1" value="<?php echo $mscbcst; ?>">
		   </td>
		   <td></td>
		   <td style="width: 136px"></td>
		   <td></td>
		   <td></td>
	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td>Total Cost</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
		   <input readonly="readonly" name="totalcos" id ="totalcosid" type="text" style="width: 156px;" class="textnoentry1" value="<?php echo $tcost;?>">
		   </td>
		   <td></td>
		   <td style="width: 136px">Total Amount</td>
		   <td>:</td>
		   <td>
		   <input readonly="readonly" name="totalamt" id ="totalamtid" type="text" style="width: 156px;" class="textnoentry1" value="<?php echo $tamt; ?>"></td>
	  	  </tr>
	
	  	    <tr>
	  	   <td></td>
		   <td style="width: 745px">Create By</td>
           <td>:</td>
           <td style="width: 264px">
			<input class="textnoentry1" name="create_by" id ="create_byid" type="text" readonly="readonly" style="width: 150px" value="<?php echo $create_by;?>"></td>
		   <td></td>
           <td style="width: 290px">Create On</td>
           <td style="width: 109px">:</td>
           <td style="width: 468px">
		   <input class="textnoentry1" name="creation_time" id ="creation_timeid" type="text" readonly="readonly" style="width: 160px" value="<?php echo $creation_time;?>"></td>
		    </tr>
			<tr>
	  	    <td style="width: 2px"></td>
	  	    <td class="tdlabel" style="width: 745px">Modified By</td>
	  	    <td>:</td>
	  	    <td style="width: 264px">
	  	    <input class="textnoentry1" name="modified_by" id ="modified_byid" type="text" readonly="readonly" style="width: 150px" value="<?php echo $modified_by;?>">
	  	    </td>
			<td></td>
			<td class="tdlabel" style="width: 290px">Modified On</td>
			<td style="width: 109px">:</td>
            <td style="width: 468px">
	  	     <input class="textnoentry1" name="modified_on" id ="suppstateid0" type="text" readonly="readonly" style="width: 160px" value="<?php echo $modified_on; ?>"></td>
		    </tr>
	
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table">
          	<thead>
          	 <tr>
              <th class="tabheader" style="width: 27px">#</th>
              <th class="tabheader" style="width: 140px">Raw Material Item</th>
              <th class="tabheader" style="width: 303px">Description</th>
              <th class="tabheader" style="width: 48px">UOM</th>
              <th class="tabheader" style="width: 75px">Unit <br>Cost(RM)</th>
              <th class="tabheader" style="width: 75px">Unit<br>Consumption</th>
              <th class="tabheader" style="width: 75px">Cost</th>
              <th class="tabheader" style="width: 75px">Mark</th>
              <th class="tabheader" style="width: 75px">Spread</th>
			  <th class="tabheader" style="width: 75px">Cut</th>
			  <th class="tabheader" style="width: 75px">Bundle</th>
             </tr>
            </thead>
            <tbody>            
             <?php
             	$sql = "SELECT * FROM prod_matlis";
             	$sql .= " Where prod_code='".$var_prodcd."'"; 
	    		$sql .= " ORDER BY rm_seqno";  
				$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){
					echo '<tr class="item-row">';	
					echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>'; 
					echo '<td><input name="procomat[]" value="'.htmlentities($rowq['rm_code']).'" readonly="readonly" id="procomat1" readonly="readonly" style="width: 161px"></td>';
                	echo '<td><input name="procodesc[]" value="'.htmlentities($rowq['rm_desc'], ENT_QUOTES).'" id="procomat1" style="width: 303px; border-style: none;" readonly="readonly"></td>';
             		echo '<td><input name="procouom[]" value="'.$rowq['rm_uom'].'" id="procouom" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 48px;"></td>';
                    echo '<td><input name="procoucost[]" id="procoucost1" value="'.$rowq['rm_ucost'].'"style="width: 75px; text-align:right;" readonly="readonly"></td>';             	
                	echo '<td><input name="prococompt[]" value="'.$rowq['rm_comps'].'" id="prococompt1" style="width: 75px; text-align:right;" readonly="readonly"></td>';
                	echo '<td><input name="prococost[]" value="'.$rowq['rm_costing'].'" id="prococost1" readonly="readonly" style="width: 75px; border:0; text-align:right;"></td>';
                	echo '<td><input name="procomark[]" id="procomark" readonly="readonly" style="width: 75px; border:0; text-align:right;" value="'.$rowq['rm_mark'].'"></td>';
                	echo '<td><input name="procospre[]" value="'.$rowq['rm_spre'].'" id="procospre" readonly="readonly" style="width: 75px; border:0; text-align:right;"> </td>';
                	echo '<td><input name="prococut[]" value="'.$rowq['rm_cut'].'" id="prococut" readonly="readonly" style="width: 75px; border:0; text-align:right;"></td>';
                	echo '<td><input name="procobund[]" value="'.$rowq['rm_bundl'].'" id="procobund" readonly="readonly" style="width: 75px; border:0; text-align:right;"></td>';
                	echo ' </tr>';
                	$i = $i + 1;
                }
             ?>          
            </tbody>
           </table>
      	
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "procost_appr.php?menucd=".$var_menucode;
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				?>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
	  	</table>
	   </form>	
	</fieldset>
	</div>
	<div class="spacer"></div>
</body>

</html>
