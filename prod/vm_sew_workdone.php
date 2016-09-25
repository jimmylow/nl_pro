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
      $barcodeno= $_GET['barcodeno'];
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
	$sql = "select * from sew_barcode";
    $sql .= " where barcodeno ='$barcodeno' ";        
    $sql_result = mysql_query($sql);
    $row = mysql_fetch_array($sql_result);
    //echo $sql; break;

    $ticketno = $row['ticketno'];
	$prod_code= $row['prod_code'];
	$prod_jobsec= $row['prod_jobsec'];
	$prod_jobid= $row['prod_jobid'];
	$prod_jobrate= $row['prod_jobrate'];
	$barcodeno= $row['barcodeno'];
	$workid= $row['workid'];
	$qtydoz = $row['qtydoz'];
	$qtypcs= $row['qtypcs'];
	$sewdate = date('d-m-Y', strtotime($row['sewdate']));

//echo $ticketno; break;

    $sql = "select workname from wor_detmas ";
    $sql .= " where workid ='$workid'";
    $sql_result = mysql_query($sql);
    $row = mysql_fetch_array($sql_result);
    $widname = $row[0];
    
    $sql = "select productqty from sew_entry ";
    $sql .= " where ticketno = '$ticketno'";
    $sql_result = mysql_query($sql);
    $row = mysql_fetch_array($sql_result);
    $productqty = $row[0];

?>
<body>
	<?php include("../topbarm.php"); ?> 
<!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc" style="width: 926px;">
    <form name="InpCutDone" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	<fieldset name="Group1" style=" width: 901px; height: 320px;">
	 <legend class="title">SEWING WORK DONE - <?php $barcodeno = $_GET['barcodeno']; echo $barcodeno; ?></legend>
	 <table style="width: 878px">
	  <tr>
	  	   <td style="width: 126px">Barcode No. </td>
	  	   <td style="width: 13px">:</td>
	  	<td>
			<input class="inputtxt" name="barcodeno" id="sewtypeid" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $barcodeno; ?>"></td>
	  	<td>
			&nbsp;</td>
	  	   <td style="width: 126px">Product No</td>
	  	   <td style="width: 9px">:</td>
	  	   <td style="width: 13px">
			<input class="inputtxt" name="prod_code" id="sewtypeid3" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $prod_code; ?>"></td>
	  	<td style="height: 31px">
	  	&nbsp;</td>

	  </tr>
	  <tr>
	  	   <td style="width: 126px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td></td></tr>
	  <tr>
	  	   <td style="width: 126px; height: 27px;">Ticket No.</td>
	  	   <td style="width: 13px; height: 27px;">:</td>
	  	   <td style="width: 272px; height: 27px;">
			<input class="inputtxt" name="ticketno" id="ticketnoid" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $ticketno; ?>"></td>
	  	<td style="height: 31px">
			&nbsp;</td>
		   <td style="height: 27px">Job Code</td>
		   <td style="height: 27px">:</td>
		   <td>
			<input class="inputtxt" name="prod_jobid" id="sewtypeid4" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $prod_jobid; ?>"></td>
	  	<td style="height: 31px">
	  	&nbsp;</td>
	  </tr>
	  <tr>
	  	   <td style="width: 126px">&nbsp;</td>
	  	   <td style="width: 13px">&nbsp;</td>
	  	   <td></td>
	  	   <td>
			&nbsp;</td>
		   <td style="width: 136px">Job Rate</td>
		   <td>:</td>
		   <td>
			<input class="inputtxt" name="prod_jobrate" id="sewtypeid5" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $prod_jobrate; ?>"></td>
		  	<td style="height: 31px">
		  	&nbsp;</td>

	  </tr>
	  <tr>
	  	   <td style="width: 126px">Worker ID/Name</td>
	  	   <td style="width: 13px">:</td>
	  	<td>
			<input class="inputtxt" name="workid" id="sewtypeid1" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $workid . ' - '. $widname; ?>"></td>
	  	<td>
			&nbsp;</td>
		   <td>Total In Pcs</td>
		   <td>:</td>
		   <td>
			<input class="inputtxt" name="productqty" id="productqtyid" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $productqty; ?>"></td>
	  	<td>
	  	&nbsp;</td>
	  </tr>
	  <tr>
	  	   <td style="width: 126px; height: 26px;">&nbsp;</td>
	  	   <td style="width: 13px; height: 26px;">&nbsp;</td>
	  	   <td></td>
	  	   <td>
			&nbsp;</td>
		   <td>Qty In Doz.</td>
		   <td>:</td>
		   <td>
			<input class="inputtxt" name="qtydoz" id="sewtypeid6" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $qtydoz; ?>"></td>
	  		<td style="height: 31px">
	  		&nbsp;</td>

	     </tr>
	  <tr>
	  	   <td style="width: 126px; height: 26px;">Sewing Date</td>
	  	   <td style="width: 13px; height: 26px;">:</td>
	  	<td>
			<input class="inputtxt" name="sewdate" id="sewtypeid2" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $sewdate; ?>"></td>
	  	<td>
			&nbsp;</td>
		   <td>Qty In Pcs.</td>
		   <td>:</td>
		   <td>
			<input class="inputtxt" name="qtypcs" id="sewtypeid7" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $qtypcs; ?>"></td>
	  	<td>
		&nbsp;</td>
	  </tr>
	  <tr>
	  	<td></td>
	  	<td>&nbsp;</td>
	  	<td>&nbsp;</td>
	  	<td>
	  	&nbsp;</td>
	  </tr>
	   <tr>	
			<td colspan="6" align="center">
				<?php
				 $locatr = "sew_workdone_query.php?menucd=".$var_menucode;			
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
