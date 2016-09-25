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
      $barno = $_GET['b'];
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
	$sql = "select * from prodcutdone";
    $sql .= " where cutno ='$cutno' and barcodeno = '$barno'";        
    $sql_result = mysql_query($sql);
    $row = mysql_fetch_array($sql_result);

    $barno = $row['barcodeno'];
    $cutid = $row['cutid'];
    $ordno   = $row['ordno'];
    $buyno   = $row['buyno'];
    $donedte = date('d-m-Y', strtotime($row['donedte']));
    $ordno   = $row['orderno'];
    $prodcat = $row['prodcat'];
    $prodcnum= $row['prodnum'];
	$prodno  = $prodcat.$prodcnum;
		
    $colno   = $row['prodcol'];
	$cutno   = $row['cutno'];
	$wid     = $row['workerid'];   
	$cdte    = date('d-m-Y', strtotime($row['cutdate'])); 
	$ratype  = $row['ratetype'];
	$rarate  = $row['rate'];
	$cutqty  = $row['totqty']; 
    
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
	 <legend class="title">CUTTING WORK DONE DETAIL - <?php echo $barno; ?></legend>
	 <table style="width: 878px">
	  <tr>
	  	<td></td>
	  	<td>Barcode</td>
	  	<td>:</td>
	  	<td style="width: 361px">
			<input class="inputtxt" name="cutbar" id="cutbar" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $barno; ?>">
		</td>
	  </tr>
	  <tr><td></td></tr>
	  <tr>
	  	<td></td>
	  	<td>ID</td>
	  	<td>:</td>
	  	<td style="width: 361px">
	  		<input class="inputtxt" name="barid" id="barid" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $cutid; ?>"></td>
	  	<td style="width: 18px"></td>
	  	<td>Date Work Done</td>
	  	<td>:</td>
	  	<td>
	  	<input class="inputtxt" name="donedte" id ="donedte" type="text" style="width: 128px;" readonly="readonly" value="<?php  echo $donedte; ?>">
	  	</td>
	  </tr>
	  <tr><td></td></tr>
	  <tr>
	  	<td></td>
	  	<td>Order No</td>
	  	<td>:</td>
	  	<td><input class="inputtxt" name="ordno" id ="ordno" type="text" style="width: 200px;" readonly="readonly" value="<?php  echo $ordno; ?>">
	  	</td>
	  	<td></td>
	  	<td>Buyer</td>
	  	<td>:</td>
	  	<td>
	  	<input class="inputtxt" name="buyno" id ="buyno" type="text" style="width: 50px;" readonly="readonly" value="<?php  echo $buyno; ?>">
	  	</td>
	  </tr>
	  <tr><td></td></tr>
	  <tr>
	  	<td></td>
	  	<td>Product</td>
	  	<td>:</td>
	  	<td>
		<input class="inputtxt" name="prodcd" id ="prodcd" type="text" style="width: 100px;" readonly="readonly" value="<?php  echo $prodno; ?>">
        </td>
	  	<td></td>
	  	<td>Color</td>
	  	<td>:</td>
	  	<td>
		<input class="inputtxt" name="colno" id ="colno" type="text" style="width: 60px;" readonly="readonly" value="<?php  echo $colno; ?>">
		<label><?php echo $colde; ?></label>
		</td>
	  </tr>
	  <tr><td></td></tr>
	  <tr>
	    <td></td>
	    <td>Cut No</td>
	    <td>:</td>
	    <td><input class="inputtxt" name="cutno" id ="cutno" type="text" style="width: 200px;" readonly="readonly" value="<?php  echo $cutno; ?>"></td>
	  	<td></td>
	  	<td>Worker ID</td>
	  	<td>:</td>
	  	<td>
	  	<input class="inputtxt" name="wrkid" id ="wrkid" type="text" style="width: 50px;" readonly="readonly" value="<?php  echo $wid; ?>">
	  	<label><?php echo $widname; ?></label>
	  	</td>
	  </tr>
	  <tr><td></td></tr>
	  <tr>
	  	<td></td>
	  	<td>Cut Date</td>
	  	<td>:</td>
	  	<td>
	  	<input class="inputtxt" name="cutdate" id="cutdate" type="text" style="width: 128px;" readonly="readonly" value="<?php echo $cdte; ?>">
	  	</td>
	  </tr>
	  <tr>
	  	<td></td>
	  	<td>Rate Type</td>
	  	<td>:</td>
	  	<td>
		<input class="inputtxt" name="ratetyp" id="ratetyp" type="text" style="width: 200px;" readonly="readonly" value="<?php echo $ratype; ?>">
	  	</td>
	  	<td></td>
	  	<td>Rate (RM)</td>
	  	<td>:</td>
	  	<td>
	  	<input class="inputtxt" name="ratecst" id="ratecst" type="text" style="width: 100px; text-align:right" readonly="readonly" value="<?php echo $rarate; ?>">
	  	</td>
	  </tr>
	  <tr><td></td></tr>
	  <tr>
	  	<td></td>
	  	<td>Quantity</td>
	  	<td>:</td>
	  	<td>
		<input class="inputtxt" name="cutqty" id="cutqty" type="text" style="width: 80px; text-align:right" readonly="readonly" value="<?php echo $cutqty; ?>"> 
		PCS</td>
	  </tr>
	  <tr><td></td></tr>
	   <tr>	
			<td colspan="8" align="center">
				<?php
				 $locatr = "cut_jobdone.php?menucd=".$var_menucode;			
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
