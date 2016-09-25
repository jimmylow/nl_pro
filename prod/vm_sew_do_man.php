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
      $sew_do_manid= $_GET['donum'];
	  $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");
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
	$sew_do_manid= $_GET['donum'];
	$sql = "select * from sew_do_man";
    $sql .= " where sew_do_manid ='$sew_do_manid' ";        
    $sql_result = mysql_query($sql);
    $row = mysql_fetch_array($sql_result);
//    echo $sql; break;

    $sew_do_manid = $row['sew_do_manid'];
	$buyer= $row['buyer'];
	$totproduct= $row['totproduct'];
	$totdefect= $row['totdefect'];
	$totgrand= $row['totgrand']; 
	$posted= $row['posted']; 

	$frdate= date('d-m-Y', strtotime($row['frdate']));
	$todate= date('d-m-Y', strtotime($row['todate']));
	$dodate= date('d-m-Y', strtotime($row['dodate']));
	//$cretion_time = date('d-m-Y', strtotime($row['creation_time']));
//echo $sew_do_manid; break;
   

?>
<body>
	<?php include("../topbarm.php"); ?> 
<!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc" style="width: 926px;">
    <form name="InpCutDone" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	<fieldset name="Group1" style=" width: 901px; height: 800px;">
	 <legend class="title">SEWING DELIVERY ORDER - <?php $sew_do_manid= $_GET['donum']; echo $sew_do_manid; ?></legend>
	 <table style="width: 878px">
	     <tr>
	  	   <td style="width: 126px; height: 26px;">Delivery Order</td>
	  	   <td style="width: 13px; height: 26px;">:</td>
	  	   <td style="width: 239px; height: 26px;">
		   <input name="sew_do_manid" id ="totproductid3" type="text" style="width: 156px; text-align:center;" tabindex="3" onblur="showoveDecimal(this.value)" value="<?php echo $sew_do_manid;?>" class="textnoentry1"></td>
	  	   <td>
			&nbsp;</td>
		   <td style="width: 125px">&nbsp;</td>
		   <td style="width: 9px">&nbsp;</td>
		    <td>
			&nbsp;</td>
	  		<td style="height: 31px">
	  		&nbsp;</td>

	     </tr>
	  <tr>
	  	   <td style="width: 126px; height: 27px;">Buyer</td>
	  	   <td style="width: 13px; height: 27px;">:</td>
	  	   <td style="width: 272px; height: 27px;">
		   <input name="buyer" id ="totproductid0" type="text" style="width: 156px; text-align:center;" tabindex="3" onblur="showoveDecimal(this.value)" value="<?php echo $buyer;?>" class="textnoentry1"></td>
	  	<td>
			&nbsp;</td>
	  	   <td style="width: 125px">&nbsp;</td>
	  	   <td style="width: 9px">&nbsp;</td>
	  	   <td style="width: 239px">&nbsp;</td>
	  	<td style="height: 31px">
	  	&nbsp;</td>

	  </tr>
	  <tr>
	  	   <td style="width: 126px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td></td></tr>
	     <tr>
	  	   <td style="width: 126px">DO Date</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
		   <input name="dodate" id ="totproductid5" type="text" style="width: 156px; text-align:center;" tabindex="3" onblur="showoveDecimal(this.value)" value="<?php echo $dodate;?>" class="textnoentry1"></td>
	  	<td style="height: 31px">
			&nbsp;</td>
	  	   <td style="width: 125px">&nbsp;</td>
	  	   <td style="width: 9px">&nbsp;</td>
	  	   <td style="width: 239px">
		   &nbsp;</td>
	  	<td style="height: 31px">
	  	&nbsp;</td>
	     </tr>
	  <tr>
	  	   <td>&nbsp;</td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 212px">
		   </td>
	  	<td>
		</td>
	  	   <td style="width: 125px"></td>
	  	   <td style="width: 9px"></td>
	  	   <td style="width: 239px">
		   </td>
	  	<td>
	  	</td>
	  </tr>
	  <tr>
	  	   <td style="width: 126px">Remark</td>
	  	   <td style="width: 13px">:</td>
	  	   <td colspan="6">
		   <input name="remark" id ="remark" type="text" style="width: 445px; text-align:left;" tabindex="3" onblur="showoveDecimal(this.value)" value="<?php echo $remark;?>" class="textnoentry1"></td>

	     </tr>
	  <tr>
	  	   <td>&nbsp;</td>
	  	   <td style="width: 13px">&nbsp;</td>
	  	   <td style="width: 212px">
		   &nbsp;</td>
	  	<td>
			&nbsp;</td>
		   <td style="width: 125px">&nbsp;</td>
		   <td style="width: 9px">&nbsp;</td>
		    <td>
		    &nbsp;</td>
	  	<td>
		&nbsp;</td>
	  </tr>
	   <tr>	
			<td colspan="6" align="center">
			<?php
			//add here knscl
					echo '<table id="itemsTable" class="general-table">
				<thead>
					<tr>
						  <th class="tabheader" style="width: 27px; height: 57px;">#</th>
						  <th class="tabheader">Item Code</th>
						  <th class="tabheader">UOM</th>
						  <th class="tabheader">Description</th>
						  <th class="tabheader">Qty</th>
						  <th class="tabheader" style="width: 78px">Unit Price</th>
						  <th class="tabheader">Amount</th>
					 </tr>
				</thead>
				<tbody>';
		     
			$sql = "SELECT * ";
			$sql.= " FROM sew_do_man_tran ";
			$sql.= " where sew_do_manid = '". $sew_do_manid."'";
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
			<?php
				 $locatr = "m_sew_do_man.php?menucd=".$var_menucode;			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';

			?>


	  	    </td>
	  	  </tr>
	 </table>
	</fieldset>
	</form>
    </div>
    <div class="spacer"></div>
</body>

</html>
