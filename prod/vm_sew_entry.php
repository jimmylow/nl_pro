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
      $ticketno = $_GET['ticketno'];
	  $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");
    }
    
     if (isset($_POST['Submit'])){ 
    	if ($_POST['Submit'] == "Barcode"){
       		$tick = $_POST['ticketno'];
        
        	$fname = "sew_barentry.rptdesign&__title=myReport"; 
        	$dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&tic=".$tick."&menuc=".$var_menucode."&dbsel=".$varrpturldb;
        	$dest .= urlencode(realpath($fname));
        
        	//header("Location: $dest" );
        	echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        	$backloc = "../prod/vm_sew_entry.php?ticketno=".$tick."&menucd=".$var_menucode;
       		echo "<script>";
       		echo 'location.replace("'.$backloc.'")';
        	echo "</script>"; 
     	}
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
<script type="text/javascript" src="../js/InputMask.js"></script>


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
	$sql = "select * from sew_entry";
    $sql .= " where ticketno ='$ticketno' ";        
    $sql_result = mysql_query($sql);
    $row = mysql_fetch_array($sql_result);
//echo 'kkk-'. $row['qcqty'];

    $ticketno = $row['ticketno'];
    $buyerorder = $row['buyerorder'];
    $buyerord = $row['buyerord'];
	$ticketno= $row['ticketno'];
	$batchno= $row['batchno'];
	$area= $row['areacd'];
	$qcqty= $row['qcqty'];
	$grpcd= $row['grpcd'];
	$productqty= $row['productqty'];
	$buyer= $row['buyer'];
	$sewtype = $row['sewtype'];
	//$qcqty = $row['productqty'];
//echo 'jjj-'.$qcqty;

	$productcode = $row['productcode'];
    $sorddte = date('d-m-Y', strtotime($row['orderdate']));
    $sexpddte= date('d-m-Y', strtotime($row['deliverydate']));
    $prorevdte= date('d-m-Y', strtotime($row['productiondate']));
    $qcdte= date('d-m-Y', strtotime($row['qcdte']));
    if ($qcdte <= '01-01-1970')
    {
    	$qcdte = '';
    }
    $productiondate= date('d-m-Y', strtotime($row['productiondate']));

    $sql = "select workname from wor_detmas ";
    $sql .= " where workid ='$wid'";
    $sql_result = mysql_query($sql);
    $row = mysql_fetch_array($sql_result);
    $widname = $row[0];
                
    $sql = "select clr_desc from pro_clr_master ";
    $sql .= " where clr_code ='$colno'";
    $sql_result = mysql_query($sql);
    $row = mysql_fetch_array($sql_result);
    $colde = $row[0];

?>
<body>
	<?php include("../topbarm.php"); ?> 
<!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc" style="width: 926px;">
    <form name="InpCutDone" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	<fieldset name="Group1" style=" width: 901px; height: 320px;">
	 <legend class="title">SEWING TICKET ENTRY - <?php $ticketno = $_GET['ticketno']; echo $ticketno; ?></legend>
	 <table style="width: 878px">
	  <tr>
	  	   <td style="width: 126px">Sew Type</td>
	  	   <td style="width: 13px">:</td>
	  	<td></td>
	  	<td>
			<input class="inputtxt" name="sewtype" id="sewtypeid" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $sewtype; ?>"></td>
	    <td style="width: 18px; height: 31px;"></td>
	  	   <td style="width: 151px">Buyer Order </td>
	  	   <td style="width: 13px">:</td>
	  	<td style="height: 31px">
	  	<input class="inputtxt" name="buyerorder" id ="buyerorderid\" type="text" style="width: 128px;" readonly="readonly" value="<?php  echo $buyerorder; ?>"></td>

	  </tr>
	  <tr>
	  	   <td style="width: 126px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td></td></tr>
	  <tr>
	  	   <td style="width: 126px; height: 31px;">Ticket No.</td>
	  	   <td style="width: 13px; height: 31px;">:</td>
	  	<td style="height: 31px"></td>
	  	<td style="height: 31px">
			<input class="inputtxt" name="ticketno" id="sewtypeid0" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $ticketno; ?>"></td>
	  	<td style="width: 18px; height: 31px;"></td>
		   <td style="width: 151px">Order Date</td>
		   <td>:</td>
	  	<td style="height: 31px">
	  	<input class="inputtxt" name="sorddte" id ="sorddteid" type="text" style="width: 128px;" readonly="readonly" value="<?php  echo $sorddte; ?>">
	  	</td>
	  </tr>
	  <tr>
	  	   <td style="width: 126px">Production Date</td>
	  	   <td style="width: 13px">:</td>
	  	   <td></td>
	  	   <td>
			<input class="inputtxt" name="productiondate" id="productiondateid" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $productiondate; ?>"></td>
		  	<td style="width: 18px; height: 31px;"></td>
		   <td style="width: 151px">&nbsp;</td>
		   <td>&nbsp;</td>
		  	<td style="height: 31px">
		  	&nbsp;</td>

	  </tr>
	  <tr>
	  	   <td style="width: 126px">Batch No</td>
	  	   <td style="width: 13px">:</td>
	  	<td></td>
	  	<td>
			<input class="inputtxt" name="batchno" id="batchnoid" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $batchno; ?>"></td>
	  	<td></td>
		   <td style="width: 151px">Delivery Date</td>
		   <td>:</td>
	  	<td>
	  	<input class="inputtxt" name="sexpddte" id ="sexpddteid" type="text" style="width: 126px;" readonly="readonly" value="<?php  echo $sexpddte; ?>">
	  	</td>
	  </tr>
	  <tr>
	  	   <td style="width: 126px; height: 26px;">Area</td>
	  	   <td style="width: 13px; height: 26px;">:</td>
	  	   <td></td>
	  	   <td>
			<input class="inputtxt" name="area" id="areaid" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $area; ?>"></td>
			<td style="width: 18px; height: 31px;"></td>
		   <td style="width: 151px">QC Date</td>
		   <td>:</td>
	  		<td style="height: 31px">
	  		<input class="inputtxt" name="qcdte" id ="qcdteid" type="text" style="width: 128px;" readonly="readonly" value="<?php  echo $qcdte; ?>">
	  		</td>

	     </tr>
	  <tr>
	  	   <td style="width: 126px; height: 26px;">Group Code</td>
	  	   <td style="width: 13px; height: 26px;">:</td>
	  	<td></td>
	  	<td>
			<input class="inputtxt" name="grpcd" id="grpcdid" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $grpcd; ?>"></td>
	  	<td></td>
		   <td style="width: 151px">QC Qty</td>
		   <td>:</td>
	  	<td>
		<input class="inputtxt" name="qcqty" id ="qcqtyid" type="text" style="width: 60px;" readonly="readonly" value="<?php  echo $qcqty; ?>">
		<label><?php echo $colde; ?></label>
		</td>
	  </tr>
	  <tr>
	  	   <td>Product Code</td>
	  	   <td style="width: 13px">:</td>
	  	   <td></td>
	  	   <td>
			<input class="inputtxt" name="productcode" id="productcodeid" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $productcode; ?>"></td>
			<td style="width: 18px; height: 31px;"></td>
	  		<td style="height: 31px; width: 151px;">&nbsp;</td>
	  		<td style="height: 31px">&nbsp;</td>
	  		<td style="height: 31px">
	  		&nbsp;</td>

	  </tr>
	  <tr>
		   <td>Product Qty</td>
		   <td>:</td>
	    <td></td>
	    <td>
			<input class="inputtxt" name="productqty" id="productqtyid" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $productqty; ?>"></td>
	  	<td></td>
	  	<td style="width: 151px">&nbsp;</td>
	  	<td>&nbsp;</td>
	  	<td>
	  	&nbsp;</td>
	  </tr>
	  <tr>
	  	   <td style="width: 126px; height: 26px;">Buyer</td>
	  	   <td style="width: 13px; height: 26px;">:</td>
	  	   <td></td>
	  	   <td>
			<input class="inputtxt" name="buyer" id="buyerid" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $buyer; ?>"></td>
	  </tr>
	  <tr>
	  	<td></td>
	  	<td>&nbsp;</td>
	  	<td>&nbsp;</td>
	  	<td>
	  	&nbsp;</td>
	  </tr>
	   <tr>	
			<td colspan="8" align="center">
				<?php
				 $locatr = "m_sew_entry.php?menucd=".$var_menucode;			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				 echo '<input type="submit" name = "Submit" value="Barcode" class="butsub" style="width: 80px; height: 32px">';	
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
