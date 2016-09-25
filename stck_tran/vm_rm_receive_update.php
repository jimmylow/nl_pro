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
      $rm_receive_id = $_GET['rm_receive_id'];
      include("../Setting/ChqAuth.php");
    }
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<style media="all" type="text/css">
@import url('../../../xampp/htdocs/nl_pro/css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css');
@import url('../../../xampp/htdocs/nl_pro/css/styles.css');

.general-table #procomat                        { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}
.general-table #procoucost                      { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}
.general-table #prococompt                      { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}

.style2 {
	margin-right: 0px;
}
</style>

<!-- jQuery libs -->
<script type="text/javascript" src="../../../xampp/htdocs/nl_pro/js/multitable/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../../../xampp/htdocs/nl_pro/js/multitable/jquery-ui-1.8.14.custom.min.js"></script>

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
  <?php include("../../../xampp/htdocs/nl_pro/topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->
<body>

  <?php
  	 $sql = "select * from rawmat_receive";
     $sql .= " where rm_receive_id ='".$rm_receive_id."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $grndate = date('d-m-Y', strtotime($row['grndate']));
     $refno = $row['refno'];
     $pono = $row['po_number'];
     $itemcode = $row['item_code'];
     $receiveqty = $row['totalqty'];
     $description = $row['description'];
     $requestby = $row['request_by'];
     $sendto = $row['send_to'];
     $invno = $row['invno'];
     $create_by = $row['create_by'];
     $create_on = $row['create_on'];
     $modified_by= $row['upd_by'];
 	 $modified_on = $row['upd_on'];
 	 
 	 $basecst = 0;
 	 $unitcost =0;
 	 $brate = 0;
 	 //------------------------------------
 		$sql = "SELECT x.rm_receive_id, x.po_number, y.item_code, y.unit_cost, grndate ";
	    $sql .= " FROM rawmat_receive x,  rawmat_receive_tran y ";
		$sql .= " where x.rm_receive_id = y.rm_receive_id ";   
		$rs_result = mysql_query($sql); 

	    $numi = 1;
		while ($rowq = mysql_fetch_assoc($rs_result)) 
		{ 	 
		
			$rm_receive_id = htmlentities($rowq['rm_receive_id']);
			$pono = htmlentities($rowq['po_number']);
			$itemcode = htmlentities($rowq['item_code']);	
			$unitcost = htmlentities($rowq['unit_cost']);
			$grndate= htmlentities($rowq['grndate']);
			
			
			
			// to select supplier based on PO, then only can find our what currency used //
			
			$sql_po = "select supplier from po_master x ";
			$sql_po .= "where x.po_no = '$pono' ";
			//echo $sql_po;
		
			$sql_result_po = mysql_query($sql_po) or die("Cant Query Supplier From PO Master ".mysql_error());;
			$row_po = mysql_fetch_array($sql_result_po);
			$supplier = $row_po[0];
			
			
			$sql2 = "SELECT currency_code FROM rawmat_price_ctrl ";
			$sql2 .= "where supplier  = '$supplier' ";
			$sql2 .= "and  rm_code = '$itemcode'";
			
		
							
			//$sql2 = "select currency_code from rawmat_master x , rawmat_subcode y ";
			//$sql2 .= "where x.rm_code = main_code ";
			//$sql2 .= "and  y.rm_code = '$matcode'";
			//echo $sql2. "</br>";
		
			$sql_result2 = mysql_query($sql2) or die("Cant Query Curr From Main Code Table ".mysql_error());;
			$row2 = mysql_fetch_array($sql_result2);
			$curr       = $row2[0];
		
			#-------------Begin convert price to based currency buy rate--------------------------------------
		 	$exhmth = date("n",strtotime($grndate)); 
		 	$exhyr  = date("Y",strtotime($grndate));
		 	
		 	if ($curr == "MYR"){
		 		$brate = 1;
		 	}else{	
		 		$sql4 = "select buyrate from curr_xrate ";
				$sql4 .= " where xmth ='$exhmth' and xyr ='$exhyr' ";
				$sql4 .= " and curr_code = '$curr'";
				$sql_result4 = mysql_query($sql4) or die("Cant Get Data From Exchange Rate Table ".mysql_error());;
				$row4 = mysql_fetch_array($sql_result4);
				$brate = $row4[0];
			}	
			
			echo $sql4. "</br>";
		
			$basecst = $unitcost * $brate;
			
			
			 $sql = "UPDATE rawmat_receive_tran "; 
			 $sql .= " SET myr_unit_cost = '".$basecst."'";
		     $sql .= " WHERE rm_receive_id ='".$rm_receive_id."'";  
		     $sql .= " AND item_code ='".$itemcode."'";
		 	 mysql_query($sql); 

			
			echo $sql;
			
		}
		
	
	
	 //-----------------------------------


  ?>
  <div class="contentc">

	<fieldset name="Group1" style=" width: 988px;" class="style2">
	 <legend class="title">RAW MAT RECEIVE DETAILS&nbsp; :<?php echo $var_revno;?></legend>
	  <br>	 
	  
	  <form name="InpJobFMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
	   
		<table style="width: 886px">
	   	   <tr>
	   	    <td></td>
	  	    <td style="width: 126px">Receive No.</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 239px">
			<input name="receiveno" id="receivenoid" type="text" maxlength="10" style="width: 189px;" readonly="readonly" value="<?php echo $rm_receive_id; ?>" class="textnoentry1">
	  	  </tr>  
	   	   <tr>
	   	    <td></td>
	  	    <td style="width: 126px">Ref No.</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 239px">
			<input name="refno" id="refnoid" type="text" maxlength="10" style="width: 189px;" readonly="readonly" value="<?php echo $refno; ?>" class="textnoentry1">
			</td>
			<td style="width: 29px"></td>
			<td style="width: 136px">Receive Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 270px">
		    <input class="textnoentry1" readonly="readonly" name="grndate" id ="grndateid" type="text" style="width: 176px;" value="<?php  echo $grndate; ?>">
		    </td>
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 126px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 239px"></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 126px">PO No.</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
		   <input name="pono" id="ponoid" type="text" style="width: 189px" readonly="readonly" value="<?php echo $pono; ?>" class="textnoentry1">
		   </td>
		   <td style="width: 29px"></td>
			<td style="width: 136px">Invoice No.</td>
			<td style="width: 16px">:</td>
			<td style="width: 270px">
		    <input class="textnoentry1" readonly="readonly" name="invno" id ="grndateid" type="text" style="width: 176px;" value="<?php  echo $invno; ?>">
		    </td>

	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td style="width: 126px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 239px">&nbsp;</td>
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
		   <input class="textnoentry1" name="create_on" id ="create_onid" type="text" readonly="readonly" style="width: 160px" value="<?php echo $create_on;?>"></td>
		    </tr>
			<tr>
	  	    <td style="width: 2px"></td>
	  	    <td class="tdlabel" style="width: 745px">Modified By&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	  	    <td>:</td>
	  	    <td style="width: 264px">
	  	    <input class="textnoentry1" name="modified_by" id ="modified_byid" type="text" readonly="readonly" style="width: 150px" value="<?php echo $modified_by;?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  	    </td>
			<td></td>
			<td class="tdlabel" style="width: 290px">Modified On&nbsp;&nbsp;&nbsp; </td>
			<td style="width: 109px">:</td>
            <td style="width: 468px">
	  	     <input class="textnoentry1" name="modified_on" id ="suppstateid0" type="text" readonly="readonly" style="width: 160px" value="<?php echo $modified_on; ?>"></td>
		    </tr>
		  		  	

		  	
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table" style="width:887px">
          	<thead>
          	 <tr>
              <th class="tabheader" style="width: 27px">#</th>
              <th class="tabheader" style="width: 138px">Raw Material Item</th>
              <th class="tabheader">Description</th>
              <th class="tabheader" style="width: 53px">UOM</th>
              <th class="tabheader" style="width: 101px">Unit Cost</th>
              <th class="tabheader" style="width: 98px">Order Qty</th>
              <th class="tabheader" style="width: 109px">Qty/Pack</th>
              <th class="tabheader" style="width: 83px">Receive Qty</th>
             </tr>
            </thead>
            <tbody>            
             <?php
             	$sql = "SELECT * FROM rawmat_receive_tran";
             	$sql .= " Where rm_receive_id='".$rm_receive_id ."'"; 
	    		$sql .= " ORDER BY seqno";  
				$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){
					echo '<tr class="item-row">';	
					echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>'; 
					echo '<td><input name="promat[]" value="'.$rowq['item_code'].'" id="procomatid" style="width: 250px; border-style: none;" readonly="readonly"></td>';
                	echo '<td><input name="procodesc[]" value="'.$rowq['description'].'" id="procomat1" style="width: 300px; border-style: none;" readonly="readonly"></td>';
             		echo '<td><input name="procoum[]" value="'.$rowq['uom'].'" id="procoum" style="width: 75px; border-style: none;" readonly="readonly"></td>';       	
             		echo '<td><input name="uprice[]" tMark="1" id="uprice" readonly="readonly" style="width: 75px; border:0;" value="'.$rowq['unit_cost'].'"></td>';

                	echo '<td><input name="procomark[]" id="procomark" readonly="readonly" style="width: 75px; border:0;" value="'.$rowq['orderqty'].'"></td>';
                	echo '<td><input name="qtyperpack[]" value="'.$rowq['qtyperpack'].'" id="qtyperpack'.$i.'" style="width: 75px; border:0; "></td>'; 
                	echo '<td><input name="prococost[]" value="'.$rowq['totalqty'].'" id="prococost1" readonly="readonly" style="width: 75px; border:0;"></td>';
                	
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
				 $locatr = "m_rm_receive.php?menucd=".$var_menucode;
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
