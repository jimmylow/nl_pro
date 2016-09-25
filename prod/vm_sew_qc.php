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
      $ticketno= $_GET['ticketno'];
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
	$sql = "select * from sew_qc";
    $sql .= " where ticketno ='$ticketno' ";        
    $sql_result = mysql_query($sql);
    $row = mysql_fetch_array($sql_result);
    //echo $sql; break;

    $ticketno = $row['ticketno'];
	$okqty= $row['okqty'];
	$defectqty= $row['defectqty'];
	$missingqty= $row['missingqty'];
	$newproduct = $row['newproduct'];

	$qcdate = date('d-m-Y', strtotime($row['qcdate']));
	//$cretion_time = date('d-m-Y', strtotime($row['creation_time']));

//echo $ticketno; break;
   
    $sql = "select batchno, buyer, productcode, creation_time, productqty, orderdate from sew_entry ";
    $sql .= " where ticketno = '$ticketno'";
    $sql_result = mysql_query($sql);
    $row = mysql_fetch_array($sql_result);
    $batchno= $row[0];
    $buyer= $row[1];
    $productcode= $row[2];
    //$ticketdate= $row[3];
    $ticketdate= date('d-m-Y', strtotime($row[3]));
    $productqty = $row[4];
    $orderdate = $row[5];

?>
<body>
	<?php include("../topbarm.php"); ?> 
<!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc" style="width: 926px;">
    <form name="InpCutDone" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	<fieldset name="Group1" style=" width: 901px; height: 320px;">
	 <legend class="title">SEWING QC - <?php $ticketno= $_GET['ticketno']; echo $ticketno; ?></legend>
	 <table style="width: 878px">
	  <tr>
	  	   <td style="width: 126px; height: 27px;">Ticket No.</td>
	  	   <td style="width: 13px; height: 27px;">:</td>
	  	   <td style="width: 272px; height: 27px;">
			<input class="textnoentry1" name="ticketno" id="ticketnoid" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $ticketno; ?>"></td>
	  	<td>
			&nbsp;</td>
	  	   <td style="width: 126px">QC Date</td>
	  	   <td style="width: 9px">:</td>
	  	   <td style="width: 239px"><div id="msgcd0">
			<input class="textnoentry1" name="qcdate" id="productcodeid0" type="text" style="width: 200px; " readonly="readonly" value="<?php echo  $qcdate; ?>"></div></td>
	  	<td style="height: 31px">
	  	&nbsp;</td>

	  </tr>
	  <tr>
	  	   <td style="width: 126px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td></td></tr>
	  <tr>
	  	   <td style="width: 126px">Ticket Date</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
			<input class="textnoentry1" name="ticketdate" id="ticketdateid" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $ticketdate; ?>"></td>
	  	<td style="height: 31px">
			&nbsp;</td>
	  	   <td>Product Code</td>
	  	   <td style="width: 9px">:</td>
	  	   <td style="width: 239px">
			<input class="textnoentry1" name="productcode" id="productcodeid" type="text" style="width: 200px; " readonly="readonly" value="<?php echo  $productcode; ?>"></td>
	  	<td style="height: 31px">
	  	&nbsp;</td>
	  </tr>
	  <tr>
	  	   <td style="width: 126px">&nbsp;</td>
	  	   <td style="width: 13px">&nbsp;</td>
	  	   <td style="width: 239px">&nbsp;</td>
	  	   <td>
			&nbsp;</td>
		   <td style="width: 136px">&nbsp;</td>
		   <td style="width: 9px">&nbsp;</td>
		   <td>
		   &nbsp;</td>
		  	<td style="height: 31px">
		  	&nbsp;</td>

	  </tr>
	  <tr>
	  	   <td style="width: 126px">&nbsp;</td>
	  	   <td style="width: 13px">&nbsp;</td>
	  	   <td style="width: 239px">&nbsp;</td>
	  	<td>
			&nbsp;</td>
		   <td style="width: 136px">Product Qty</td>
		   <td style="width: 9px">&nbsp;</td>
		   <td>
			<input class="textnoentry1" name="productqty" id="productqtyid" type="text" style="width: 200px; " readonly="readonly" value="<?php echo  $productqty; ?>"></td>
	  	<td>
	  	&nbsp;</td>
	  </tr>
	  <tr>
	  	   <td style="width: 126px">Batch No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
			<input class="textnoentry1" name="batchno" id="batchnoid" type="text" style="width: 200px; height: 18px;" readonly="readonly" value="<?php echo  $batchno; ?>"></td>
	  	   <td>
			&nbsp;</td>
		   <td>OK Qty</td>
		   <td style="width: 9px">:</td>
		    <td>
		   <input class="textnoentry1" name="okqty" id ="okqtyid" type="text" style="width: 128px;" tabindex="4" onblur="showNumberOK(this.value)" value="<?php echo  $okqty; ?>">
		   </td>
	  		<td style="height: 31px">
	  		&nbsp;</td>

	     </tr>
	  <tr>
		   <td>Order Date</td>
		   <td>:</td>
		    <td>
			<input class="textnoentry1" name="orderdate" id="orderdateid" type="text" style="width: 200px; height: 18px;" readonly="readonly" value="<?php echo  $orderdate; ?>"></td>
	  	<td>
			&nbsp;</td>
		   <td>Defect Qty</td>
		   <td style="width: 9px">:</td>
		    <td>
		   <input class="textnoentry1" name="defectqty" id ="defectqtyid" type="text" style="width: 128px;" tabindex="4" onblur="showNumberDefect(this.value)" value="<?php echo  $defectqty; ?>">
		   </td>
	  	<td>
		&nbsp;</td>
	  </tr>
	  <tr>
	  	   <td style="width: 126px; height: 26px;">Buyer</td>
	  	   <td style="width: 13px; height: 26px;">:</td>
	  	   <td style="width: 239px; height: 26px;">
			<input class="textnoentry1" name="buyer" id="buyerid" type="text" style="width: 200px; " readonly="readonly" value="<?php echo  $buyer; ?>"></td>
	  	   <td>
			&nbsp;</td>
		   <td>New Product</td>
		   <td style="width: 9px">:</td>
		    <td>
			<input class="textnoentry1" name="newproduct" id="productqtyid0" type="text" style="width: 200px; " readonly="readonly" value="<?php echo  $newproduct; ?>"></td>
	  		<td style="height: 31px">
	  		&nbsp;</td>

	  </tr>
	  <tr>
		   <td>&nbsp;</td>
		   <td>&nbsp;</td>
	    <td></td>
	    <td>
			&nbsp;</td>
		   <td style="width: 136px">Missing Qty</td>
		   <td style="width: 9px">:</td>
		   <td>
		   <input class="textnoentry1" name="missingqty" id ="missingqtyid" type="text" style="width: 128px;" tabindex="4" value="<?php echo  $missingqty; ?>" onblur="showNumberMissing(this.value)" readonly="readonly" ></td>
	  	<td>
	  	&nbsp;</td>
	  </tr>
	   <tr>	
			<td colspan="6" align="center">
				<?php
				 $locatr = "m_sew_qc.php?menucd=".$var_menucode;			
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
