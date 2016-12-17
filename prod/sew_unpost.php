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
      $cutno = $_GET['c'];
      $sew_doid= $_GET['donum'];
	  $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");
    }
    
    $sew_doid= $_GET['donum'];
    if ($_POST['Submit'] == "Get" && !empty($_POST['donum'])) {   	   	
    	$sew_doid= $_POST['donum'];
    }
    if ($_POST['Submit'] == "UnPost" && !empty($_POST['donum'])) {
    	$sew_doid= $_POST['donum'];
    	$sql = "UPDATE sew_do";
    	$sql .= " SET posted = 'N'";
    	$sql .= "  WHERE sew_doid ='$sew_doid'";
    	mysql_query($sql) or die("Error update DO post flag :".mysql_error(). ' Failed SQL is --> '. $sql);
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />

<style media="all" type="text/css">
@import "../css/styles.css";
@import "../css/demo_table.css";
thead th input { width: 90% }

</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>
<script type="text/javascript" language="javascript" src="../js/datetimepicker_css.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>
<script type="text/javascript" src="../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../js/Parsers.js"></script>



<script type="text/javascript" charset="utf-8"> 	
function poptastic(url)
{
	var newwindow;
	newwindow=window.open(url,'name','height=600,width=1011,left=100,top=100');
	if (window.focus) {newwindow.focus()}
}
	
</script>
</head>
<?php
			
if (!empty($sew_doid)) {
	$sql = "select * from sew_do";
    $sql .= " where sew_doid ='$sew_doid' ";        
    $sql_result = mysql_query($sql);
    $num=mysql_numrows($sql_result);
    if ($num==0) {
    	echo "<script>";
    	echo "alert('Delivery Order ".$sew_doid. " not exist!')";
    	echo "</script>";
    }

    $row = mysql_fetch_array($sql_result);

    $sew_doid = $row['sew_doid'];
	$buyer= $row['buyer'];
	$totproduct= $row['totproduct'];
	$totdefect= $row['totdefect'];
	$totgrand= $row['totgrand']; 
	$posted= $row['posted']; 

	$frdate= date('d-m-Y', strtotime($row['frdate']));
	$todate= date('d-m-Y', strtotime($row['todate']));
	$dodate= date('d-m-Y', strtotime($row['dodate']));
	$gstper= $row['gstper']; 
	//$cretion_time = date('d-m-Y', strtotime($row['creation_time']));
//echo $sew_doid; break;
}  

?>
<body>
	<?php include("../topbarm.php"); ?> 


  <div class="contentc" style="width: 926px;">
    <form name="InpCutDone" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">		   
	<fieldset name="Group1" style=" width: 901px;">
	 <legend class="title">SEWING DELIVERY ORDER - <?php echo $sew_doid; ?></legend>
	 <table>
	     <tr>
	  	   <td style="width: 126px;">Delivery Order</td>
	  	   <td style="width: 13px;">:</td>
	  	   <td style="width: 250px;">
	  	   		<input name="donum" id ="totproductid3" type="text" style="width: 156px; text-align:center;" tabindex="3" value="<?php echo $sew_doid;?>" class="textnoentry1">
	  	   		<input type=submit name = "Submit" value="Get" class="butsub" style="width: 60px; height: 32px" >
	  	   </td>
	  	   <td style="width: 13px;"></td>
		   <td style="width: 126px;"></td>
		   <td style="width: 13px;"></td>
		   <td style="width: 182px;"></td>
	     </tr>
	 	 <tr>
	  	   <td>Buyer</td>
	  	   <td>:</td>
	  	   <td>
		   <input name="buyer" id ="totproductid0" type="text" style="width: 156px; text-align:center;" tabindex="3" onblur="showoveDecimal(this.value)" value="<?php echo $buyer;?>" class="textnoentry1"></td>
	  	<td>
			</td>
	  	   <td></td>
	  	   <td></td>
	  	   <td></td>
	  </tr>
	  <tr>
	  	   <td></td>
	  	   <td></td>
	  	   <td></td></tr>
	  <tr>
	  	   <td>From QC Date</td>
	  	   <td>:</td>
	  	   <td>
		   <input name="frdate" id ="totproductid1" type="text" style="width: 156px; text-align:center;" tabindex="3" onblur="showoveDecimal(this.value)" value="<?php echo $frdate;?>" class="textnoentry1"></td>
	  	<td>
			</td>
	  	   <td>To QC Date</td>
	  	   <td>:</td>
	  	   <td>
		   <input name="todate" id ="totproductid2" type="text" style="width: 156px; text-align:center;" tabindex="3" onblur="showoveDecimal(this.value)" value="<?php echo $todate;?>" class="textnoentry1"></td>
	  </tr>
	     <tr>
	  	   <td>DO Date</td>
	  	   <td>:</td>
	  	   <td>
		   <input name="dodate" id ="totproductid5" type="text" style="width: 156px; text-align:center;" tabindex="3" onblur="showoveDecimal(this.value)" value="<?php echo $dodate;?>" class="textnoentry1"></td>
	  	<td>
			</td>
	  	   <td></td>
	  	   <td></td>
	  	   <td>
		   </td>
	     </tr>
	  <tr>
	  	   <td>Product Total</td>
	  	   <td>:</td>
	  	   <td>
		   <input name="totproduct" id ="totproductid" type="text" style="width: 156px; text-align:center;" tabindex="3" onblur="showoveDecimal(this.value)" value="<?php echo $totproduct;?>" class="textnoentry1">
		   </td>
	  	<td>
			</td>
	  	   <td>Posted</td>
	  	   <td>:</td>
	  	   <td>
		   <input name="posted" id ="totproductid4" type="text" style="width: 156px; text-align:center;" tabindex="3" onblur="showoveDecimal(this.value)" value="<?php echo $posted;?>" class="textnoentry1" /></td>
	  </tr>
	  <tr>
	  	   <td>Defect Total</td>
	  	   <td>:</td>
	  	   <td>
		   <input readonly="readonly" name="totdefect" id ="totdefectid" type="text" style="width: 156px;" class="textnoentry1" value="<?php echo $totdefect; ?>">
		   </td>
	  	   <td></td>
		    <td class="tdlabel">GST Rate (%)</td>
		    			<td>:</td>
	  	    <td><input type="text" name="gstper" id ="gstper" style="width: 30px; text-align:right" value="<?php echo $gstper; ?>" readonly="readonly" class="textnoentry1"/></td>
	     </tr>
	  <tr>
	  	   <td>Grand Total</td>
	  	   <td>:</td>
	  	   <td>
		   <input readonly="readonly" name="totgrand" id ="totgrandid" type="text" style="width: 156px;" class="textnoentry1" value="<?php echo $totgrand;?>">
		   </td>
	  	<td>
			</td>
		   <td></td>
		   <td></td>
		    <td>
		    </td>
	  </tr>
	  </table>
	  <table>
	   <tr>	
			<td colspan="6" align="center">
			<?php
			//add here knscl
					echo '<table id="itemsTable" class="general-table">
				<thead>
					<tr>
						  <th class="tabheader" style="width: 27px; height: 57px;">#</th>
						  <th class="tabheader">Ticket No.</th>
						  <th class="tabheader">Product</th>
						  <th class="tabheader">UOM</th>
						  <th class="tabheader">Description</th>
						  <th class="tabheader">Qty</th>
						  <th class="tabheader" style="width: 78px">Unit Price</th>
						  <th class="tabheader">Amount</th>
					 </tr>
				</thead>
				<tbody>';
		     
			$sql = "SELECT * ";
			$sql.= " FROM sew_do_tran ";
			$sql.= " where sew_doid = '". $sew_doid."'";
			$sql.= " ORDER BY sequence";
			//echo $sql;
		             	
			//$sql = "SELECT x.ticketno, x.productcode, x.seqno, qty FROM sew_qc_tran x, sew_entry y where x.ticketno = y.ticketno and buyer = 'MDF' and x.qcdate between '2013-10-01' and '2013-10-23' ORDER BY x.ticketno, x.seqno";
		
		
			$rs_result = mysql_query($sql); 
		   
			$i = 1;
			while ($rowq = mysql_fetch_assoc($rs_result))
			{
				$sql = "select prod_desc from pro_cd_master ";
				$sql .= " where prod_code = '". $rowq['productcode']."'";
				$sql_result = mysql_query($sql);
				if ($sql_result <> FALSE)
				{
					$row = mysql_fetch_array($sql_result);
					$prod_desc= $row[0];
				}
				
				$amt = 0;
				$amt = number_format($totamt* $rowq['qty'], 2);
				
				$ticketno = $rowq['ticketno'];
				if ($rowq['seqno']==2)
				{
					$rowq['ticketno']= '';
				}
					  
				echo '<tr class="item-row">';	
				echo '<td><input name="sequence[]" id="sequence" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>'; 
				echo '<td><input name="ticketno2[]" value="'.$rowq['ticketno'].'" id="ticketno2" readonly="readonly" style="border-style: none; border-color: none; border-width: 0; width: 65px;"></td>';
			  
				echo '<td><input name="productcode[]" value="'.htmlentities($rowq['productcode']).'" id="productcode'.$i.'" readonly="readonly" style="width: 161px; border-style: none;"></td>';
				echo '<td><input name="procouom[]" value="'.$rowq['uom'].'" id="procouom" style="width: 35px; border-style: none;" readonly="readonly"></td>'; 
				echo '<td><input name="procodesc[]" value="'.$prod_desc.'" id="procodesc" style="width: 303px; border-style: none;" readonly="readonly"></td>'; 
				echo '<td><input name="issueqty[]" class="tInput" value="'.$rowq['issueqty'].'" id="issueqtyid'.$i.'" onBlur="calcCost('.$i.');" style="width: 75px; border:0;"></td>';   
				echo '<td><input name="uprice[]" class="tInput" value="'.$rowq['uprice'].'" id="upriceid'.$i.'"  readonly="readonly" style="width: 75px; border:0;"></td>';   
				echo '<td ><input name="amount[]" tMark="1" id="amount'.$i.'" readonly="readonly" style="width: 75px; border:0; " value="'.$rowq['amount'].'"></td>';         
				echo '<td><input name="seqno[]" class="tInput" value="'.$rowq['seqno'].'" id="seqnoid'.$i.'"  readonly="readonly" style="display:none; width: 75px; border:0;"></td>';   
				echo '<td><input name="ticketno[]" class="tInput" value="'.$ticketno.'" id="ticketno'.$i.'"  readonly="readonly" style="display:none; width: 75px; border:0;"></td>';
			
		
				echo ' </tr>';
				
				$i = $i + 1;
			}
			echo '</tbody></table>';
			
			//end add
			?>
	  	    </td>
	  	   </tr>
	  	   <tr>
	  	   <?php if ($num!=0 && $posted=="Y") {?>
	  	    <td colspan="8" align="center">
				<input type="submit" name = "Submit" value="UnPost" class="butsub" style="width: 60px; height: 32px" 					
					onclick="return confirm('Are you sure that UnPost this Devlivery Order <?php echo $sew_doid; ?>?')">
	  	    </td>
	  	    <?php } ?>
	  	  </tr>
	 </table>
	</fieldset>
	</form>
    </div>
    <div class="spacer"></div>
</body>

</html>
