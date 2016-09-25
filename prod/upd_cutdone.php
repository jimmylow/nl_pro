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
    
    if ($_POST['Submit'] == "Update") {
		$cutbar   = htmlentities(mysql_real_escape_string($_POST['cutbar']));
		$cutid    = htmlentities(mysql_real_escape_string($_POST['barid']));
		$cutno    = $_POST['cutno'];
    	$cdonedte = date('Y-m-d', strtotime($_POST['donedte']));
 		$wrkid    = $_POST['wrkid'];
		$ratetyp  = $_POST['ratetyp'];
		$ratecst  = $_POST['ratecst'];
		$doneqty  = $_POST['cutqty'];
		
		if ($ratecst == ""){$ratecst = 0;}
		if ($doneqty == ""){$doneqty = 0;}
		        			
		if ($cutbar != ""){
			$vartoday = date("Y-m-d"); 		
			$sql2  = " Update prodcutdone set cutid = '$cutid',";
			$sql2 .= "                        donedte = '$cdonedte',  ";
			$sql2 .= "                        workerid = '$wrkid', ";
			$sql2 .= "                        ratetype = '$ratetyp',";
			$sql2 .= "                        rate = '$ratecst', ";
			$sql2 .= "                        totqty = '$doneqty'," ;
			$sql2 .= "                        modified_by = '$var_loginid', ";
			$sql2 .= "                        modified_on = '$vartoday'";
			$sql2 .= " Where barcodeno = '$cutbar' and cutno = '$cutno'";
			mysql_query($sql2) or die(mysql_error());					
				     	 
     		$backloc = "cut_jobdone.php?menucd=".$var_menucode;
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
function setup() 
{

		document.InpCutDone.barid.focus();
						
 		//Set up the date parsers
        var dateParser = new DateParser("dd-MM-yyyy");
      
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("dd-MM-yyyy", "donedte");
		dateMask1.validationMessage = errorMessage;		
}
	
function poptastic(url)
{
	var newwindow;
	newwindow=window.open(url,'name','height=600,width=1011,left=100,top=100');
	if (window.focus) {newwindow.focus()}
}

function upperCase(x)
{
	if (x != ""){
		var y=document.getElementById(x).value;
		document.getElementById(x).value=y.toUpperCase();
	}
}

function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		 	
		return xmlhttp;
}

function chkcutqty(vid)
{
	var col1 = document.getElementById(vid).value;
	if (col1 != ""){
		if(isNaN(col1)) {
    	   alert('Please Enter a valid number for Quantity :' + col1);
    	   document.getElementById(vid).focus(); 
    	   col1 = 0;
    	}
    	document.getElementById(vid).value = parseFloat(col1).toFixed(0);
    }

	var col1 = document.getElementById(vid).value;
    if (col1 == 0){
    	alert("Cutting Job Done Quantity Cannot Be Zero.")
    	document.getElementById(vid).focus(); 
	}

}

function chkdec(vid)
{
	var col1 = document.getElementById(vid).value;
	if (col1 != ""){
		if(isNaN(col1)) {
    	   alert('Please Enter a valid number for Rate :' + col1);
    	   document.getElementById(vid).focus(); 
    	   col1 = 0;
    	}
    	document.getElementById(vid).value = parseFloat(col1).toFixed(3);
    }

}

function validateForm()
{
	
	
	var x=document.forms["InpCutDone"]["wrkid"].value;
	if (x==null || x=="")
	{
		alert("Worker ID Cannot Be Blank");
		document.InpCutDone.wrkid.focus();
		return false;
	}

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
<body onload="setup()">
	<?php include("../topbarm.php"); ?> 
<!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc" style="width: 926px;">
    <form name="InpCutDone" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	<fieldset name="Group1" style=" width: 901px; height: 350px;">
	 <legend class="title">CUTTING WORK DONE UPDATE - <?php echo $barno; ?></legend>
	 <table style="width: 878px">
	  <tr>
	  	<td style="height: 29px"></td>
	  	<td style="height: 29px">Barcode</td>
	  	<td style="height: 29px">:</td>
	  	<td style="width: 361px; height: 29px;">
			<input class="inputtxt" name="cutbar" id="cutbar" type="text" style="width: 200px;" readonly="readonly" value="<?php echo $barno; ?>">
		</td>
	  </tr>
	  <tr>
	  	<td></td>
	  	<td></td>
	  	<td></td>
	  	<td><div id="msgdivx"></div></td>
	  </tr>
	  <tr>
	  	<td></td>
	  	<td>ID</td>
	  	<td>:</td>
	  	<td style="width: 361px">
	  		<input class="inputtxt" name="barid" id="barid" type="text" style="width: 200px;" maxlength="30" value="<?php echo $cutid; ?>" onchange ="upperCase(this.id)"></td>
	  	<td style="width: 18px"></td>
	  	<td>Date Work Done</td>
	  	<td>:</td>
	  	<td>
	  	<input class="inputtxt" name="donedte" id ="donedte" type="text" style="width: 128px;" value="<?php  echo $donedte; ?>">
		<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('donedte','ddMMyyyy')" style="cursor:pointer">
	  	</td>
	  </tr>
	  <tr><td></td></tr>
	  <tr>
	  	<td></td>
	  	<td>Order No</td>
	  	<td>:</td>
	  	<td>
	  	<input class="inputtxt" name="ordno" id ="ordno" type="text" style="width: 200px;" readonly="readonly" value="<?php  echo $ordno; ?>"></td>
	  </tr>
	  <tr><td></td></tr>
	  <tr>
	  	<td></td>
	  	<td>Product</td>
	  	<td>:</td>
	  	<td><input class="inputtxt" name="prodcd" id ="prodcd" type="text" style="width: 100px;" readonly="readonly" value="<?php  echo $prodno; ?>"></td>
	  	<td></td>
	  	<td>Color</td>
	  	<td>:</td>
	  	<td><input class="inputtxt" name="colno" id ="colno" type="text" style="width: 60px;" readonly="readonly" value="<?php  echo $colno; ?>">
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
	  	<select name="wrkid" style="width: 200px" id="wrkid">
			 <?php
                $sql = "select workid, workname from wor_detmas WHERE status = 'A' ORDER BY workid";
                $sql_result = mysql_query($sql);
                echo "<option size =30 selected value='$wid'>".$wid." - ".$widname."</option>";
                echo "<option></option>";       
				if(mysql_num_rows($sql_result)) 
				{
				 while($row = mysql_fetch_assoc($sql_result)) 
				 {
				  echo '<option value="'.$row['workid'].'">'.$row['workid']." - ".htmlentities($row['workname']).'</option>';
				 } 
			    } 
	          ?>				   
			</select></td>
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
		<input class="inputtxt" name="ratetyp" id="ratetyp" type="text" style="width: 200px;" maxlength="30" onchange ="upperCase(this.id)" value="<?php echo $ratype; ?>">
	  	</td>
	  	<td></td>
	  	<td>Rate(RM)</td>
	  	<td>:</td>
	  	<td>
	  	<input class="inputtxt" name="ratecst" id="ratecst" type="text" style="width: 118px; text-align:right" maxlength="30" onchange="chkdec(this.id)" value="<?php echo $rarate; ?>">
	  	</td>
	  </tr>
	  <tr><td></td></tr>
	  <tr>
	  	<td></td>
	  	<td>Quantity</td>
	  	<td>:</td>
	  	<td>
		<input class="inputtxt" name="cutqty" id="cutqty" type="text" style="width: 80px; text-align:right" value="<?php echo $cutqty; ?>" onblur="chkcutqty(this.id)"> 
		PCS</td>
	  </tr>
	  <tr><td></td></tr>
	   <tr>	
			<td colspan="8" align="center">
				<?php
				 $locatr = "cut_jobdone.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				 include("../Setting/btnupdate.php");
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
