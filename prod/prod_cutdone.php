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
      $var_stat = $_GET['stat'];
	  $var_menucode = $_GET['menucd'];

      include("../Setting/ChqAuth.php");
    }
    
    if ($_POST['Submit'] == "Save") {
		$cutbar   = htmlentities(mysql_real_escape_string($_POST['cutbar']));
		$cutid    = htmlentities(mysql_real_escape_string($_POST['barid']));
    	$cdonedte = date('Y-m-d', strtotime($_POST['donedte']));
    	$ordno    = $_POST['ordno'];
		$prodcd   = $_POST['cutprodcd'];
 		$procol   = $_POST['cutprodcol'];
 		$cutno    = $_POST['cutno'];
 		$wrkid    = $_POST['wrkid'];
		$cutdate  = date('Y-m-d', strtotime($_POST['cutdate']));
		$ratetyp  = $_POST['ratetyp'];
		$ratecst  = $_POST['ratecst'];
		$doneqty  = $_POST['cutqty'];
		
		if ($ratecst == ""){$ratecst = 0;}
		if ($doneqty == ""){$doneqty = 0;}
		
 		$defarr = explode("^", $prodcd);
 		$prcat  = $defarr[0];
 		$prnum  = $defarr[1];
        $ordno  = $defarr[2];
        $buyno  = $defarr[3];
        			
		if ($cutno != ""){
			$vartoday = date("Y-m-d"); 		
			$sql2  = "INSERT INTO prodcutdone ";
			$sql2 .= " (cutno, cutdate, donedte, orderno, buyno, ";
			$sql2 .= "  barcodeno, prodcat, prodnum, prodcol, totqty, uom, ";
			$sql2 .= "  workerid, rate, ratetype, modified_by, modified_on, create_by, create_on, cutid)";
			$sql2 .= " values ";
			$sql2 .= " ('$cutno', '$cutdate','$cdonedte', '$ordno', '$buyno', '$cutbar', '$prcat', '$prnum'," ;
			$sql2 .= "   '$procol', '$doneqty', '', '$wrkid', '$ratecst', '$ratetyp', '$var_loginid', '$vartoday', '$var_loginid', '$vartoday', '$cutid')";
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

		document.InpCutDone.cutbar.focus();
						
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

function getsaldet(ordno)
{
	var str_array = ordno.split(',');

	for(var i = 0; i < str_array.length; i++)
	{
		str_array[i] = str_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
		// Add additional code here, such as:
		if (i == 0){
   			var ord = str_array[i];
   		}else{
   			var buy = str_array[i];
   		}	
	}	
	
   	var strURL="aja_get_donepdet.php?o="+ord+"&b="+buy;

    var req = getXMLHTTP();
    if (req)
    {
     	req.onreadystatechange = function()
     	{
     	 if (req.readyState == 4)
     	 {  
	 		// only if "OK"
	 		if (req.status == 200)
     	    {
     	    	document.getElementById('proddiv').innerHTML = req.responseText;
     	   	}else{
   	   			alert("There was a problem while using XMLHTTP:\n" + req.statusText);
	 		}
     	 }
        }
   req.open("GET", strURL, true);
   req.send(null);
   }	
}

function getprodcol(comprocd)
{
	var str_array = comprocd.split('^');

	for(var i = 0; i < str_array.length; i++)
	{
   		str_array[i] = str_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
   		// Add additional code here, such as:
   		switch(i){
		case 0:
  			var prcat = str_array[i];
  			break;
		case 1:
  			var prnum = str_array[i];
  			break;
  		case 2:
  			var ord   = str_array[i];
  			break;
  		case 3:
  			var buy   = str_array[i];
  			break;	
		}	
	}
	
	var strURL="aja_get_pprodcol.php?o="+ord+"&b="+buy+"&c="+prcat+"&cdn="+prnum;

    var req = getXMLHTTP();
    if (req)
    {
     	req.onreadystatechange = function()
     	{
     	 if (req.readyState == 4)
     	 {
	 		// only if "OK"
	 		if (req.status == 200)
     	    {  
	    		document.getElementById('coldiv').innerHTML = req.responseText;
	 		}else{
   	   			alert("There was a problem while using XMLHTTP:\n" + req.statusText);
	 		}
     	 }
        }
   	req.open("GET", strURL, true);
   	req.send(null);
   }
   
   
}

function disprmcode(colcd)
{
	var prd = document.getElementById('cutprodcd').value;
	
	var str_array = prd.split('^');

	for(var i = 0; i < str_array.length; i++)
	{
   		str_array[i] = str_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
   		// Add additional code here, such as:
   		switch(i){
		case 0:
  			var prcat = str_array[i];
  			break;
		case 1:
  			var prnum = str_array[i];
  			break;
  		case 2:
  			var ord   = str_array[i];
  			break;
  		case 3:
  			var buy   = str_array[i];
  			break;	
		}	
	}
	
	if (ord != "" && buy != "" && colcd != "" && prcat != "" && prnum != ""){
	
    	var strURL="aja_get_cutnodte.php?o="+ord+"&b="+buy+"&c="+prcat+"&cdn="+prnum+"&col="+colcd;

    	var req = getXMLHTTP();
    	if (req)
    	{
     		req.onreadystatechange = function()
     		{
     	 		if (req.readyState == 4)
     	 		{
	 				// only if "OK"
	 				if (req.status == 200)
     	    		{ 
	    				document.getElementById('cutnodiv').innerHTML = req.responseText;
	 				}else{
   	   					alert("There was a problem while using XMLHTTP:\n" + req.statusText);
	 				}
     	 		}
        	}
   		req.open("GET", strURL, true);
   		req.send(null);
   		}
	}
}

function dispcutdate(cutno)
{
	var idd = "cutdate";
    var idq = "cutqty";

	var strURL="aja_get_cutdate.php?c="+cutno;

    var req = getXMLHTTP();
    if (req)
    {
    	req.onreadystatechange = function()
    	{
     		if (req.readyState == 4)
     		{
				// only if "OK"
				if (req.status == 200)
        		{   
	   				var obj = jQuery.parseJSON(req.responseText.replace(/&quot;/g,'"'));
					
					if (obj != null){
						document.getElementById(idd).value = obj.cd;
						document.getElementById(idq).value = obj.cq;
					}else{
						document.getElementById(idd).value = '';
						document.getElementById(idq).value = '';
					}	
				}else{
   					alert("There was a problem while using XMLHTTP:\n" + req.statusText);
				}
     		}
      	}
   	req.open("GET", strURL, true);
   	req.send(null);
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

function validateForm()
{
    var x=document.forms["InpCutDone"]["cutbar"].value;
	if (x==null || x=="")
	{
		alert("Barcode Cannot Be Blank");
		document.InpCutDone.cutbar.focus();
		return false;
	}
	
	var x=document.forms["InpCutDone"]["donedte"].value;
	if (x==null || x=="")
	{
		alert("Job Done Date Cannot Be Blank");
		document.InpCutDone.donedte.focus();
		return false;
	}
	
	var x=document.forms["InpCutDone"]["ordno"].value;
	if (x==null || x=="")
	{
		alert("Order No Cannot Be Blank");
		document.InpCutDone.ordno.focus();
		return false;
	}
	
	var x=document.forms["InpCutDone"]["cutprodcd"].value;
	if (x==null || x=="")
	{
		alert("Product No Cannot Be Blank");
		document.InpCutDone.cutprodcd.focus();
		return false;
	}
	
	
	var x=document.forms["InpCutDone"]["cutprodcol"].value;
	if (x==null || x=="")
	{
		alert("Color No Cannot Be Blank");
		document.InpCutDone.cutprodcol.focus();
		return false;
	}
	
	var x=document.forms["InpCutDone"]["cutno"].value;
	if (x==null || x=="")
	{
		alert("Cutting No Cannot Be Blank");
		document.InpCutDone.cutno.focus();
		return false;
	}
	
	var x=document.forms["InpCutDone"]["wrkid"].value;
	if (x==null || x=="")
	{
		alert("Worker ID Cannot Be Blank");
		document.InpCutDone.wrkid.focus();
		return false;
	}

	var chkflg = 1;
	var x=document.forms["InpCutDone"]["cutbar"].value;
	var strURL="aja_chk_barno.php?b="+x;

   	var req = getXMLHTTP();
   	if (req)
   	{
     	req.onreadystatechange = function()
     	{
      		if (req.readyState == 4)
      		{
	 			// only if "OK"
	 			if (req.status == 200)
        		{	
        			if (req.responseText == 1){
	    				chkflg = 0;
	    			}	
	 			}else{
   	   				alert("There was a problem while using XMLHTTP:\n" + req.statusText);
	 			}
      		}
     	}
   	req.open("GET", strURL, false);
   	req.send(null);
   }

   if (chkflg == 0){
   	alert("This Barcode No Has Been Create.");
   	document.InpCutDone.cutbar.focus();
   	return false;
   }	
}

function chkbardup(barno)
{
   var strURL="aja_chk_barno.php?b="+barno;

   var req = getXMLHTTP();
   if (req)
   {
     req.onreadystatechange = function()
     {
      if (req.readyState == 4)
      {
	 	// only if "OK"
	 	if (req.status == 200)
        {	
        	if (req.responseText == 1){
	    		document.getElementById('msgdivx').innerHTML="<font color=red>This Barcode No Has Been Create.</font>";
	    	}else{
	    		document.getElementById('msgdivx').innerHTML="";
	    	}	
	 	}else{
   	   			alert("There was a problem while using XMLHTTP:\n" + req.statusText);
	 	}
      }
     }
   	req.open("GET", strURL, true);
   	req.send(null);
   }
}
</script>
</head>
<body onload="setup()">
	<?php include("../topbarm.php"); ?> 
<!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc" style="width: 926px;">
    <form name="InpCutDone" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	<fieldset name="Group1" style=" width: 901px; height: 350px;">
	 <legend class="title">CUTTING WORK DONE</legend>
	 <table style="width: 878px">
	  <tr>
	  	<td style="height: 29px"></td>
	  	<td style="height: 29px">Barcode</td>
	  	<td style="height: 29px">:</td>
	  	<td style="width: 361px; height: 29px;">
			<input class="inputtxt" name="cutbar" id="cutbar" type="text" style="width: 200px;" maxlength="30" onblur="chkbardup(this.value)" onchange ="upperCase(this.id)">
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
	  		<input class="inputtxt" name="barid" id="barid" type="text" style="width: 200px;" maxlength="30" onchange ="upperCase(this.id)"></td>
	  	<td style="width: 18px"></td>
	  	<td>Date Work Done</td>
	  	<td>:</td>
	  	<td>
	  	<input class="inputtxt" name="donedte" id ="donedte" type="text" style="width: 128px;" value="<?php  echo date("d-m-Y"); ?>">
		<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('donedte','ddMMyyyy')" style="cursor:pointer">
	  	</td>
	  </tr>
	  <tr><td></td></tr>
	  <tr>
	  	<td></td>
	  	<td>Order No</td>
	  	<td>:</td>
	  	<td>
	  		<select name="ordno" style="width: 200px" id="ordno" onchange="getsaldet(this.value)">
			 <?php
                $sql = "select distinct ordno, buyno from prodcutmas WHERE status = 'A' ORDER BY ordno";
                $sql_result = mysql_query($sql);
                echo "<option size =30 selected></option>";
                       
				if(mysql_num_rows($sql_result)) 
				{
				 while($row = mysql_fetch_assoc($sql_result)) 
				 {
				   $values = implode(',', $row);	 
				   echo '<option value="'.$values.'">'.$row['ordno']." - ".$row['buyno'].'</option>';
				 } 
			    } 
	          ?>				   
			</select>
	  	</td>
	  </tr>
	  <tr><td></td></tr>
	  <tr>
	  	<td></td>
	  	<td>Product</td>
	  	<td>:</td>
	  	<td><div id="proddiv"></div></td>
	  	<td></td>
	  	<td>Color</td>
	  	<td>:</td>
	  	<td><div id="coldiv"></div></td>
	  </tr>
	  <tr><td></td></tr>
	  <tr>
	    <td></td>
	    <td>Cut No</td>
	    <td>:</td>
	    <td><div id="cutnodiv"></div></td>
	  	<td></td>
	  	<td>Worker ID</td>
	  	<td>:</td>
	  	<td>
	  	<select name="wrkid" style="width: 200px" id="wrkid">
			 <?php
                $sql = "select workid, workname from wor_detmas WHERE status = 'A' ORDER BY workid";
                $sql_result = mysql_query($sql);
                echo "<option size =30 selected></option>";
                       
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
	  	<input class="inputtxt" name="cutdate" id="cutdate" type="text" style="width: 200px;" >
	  	</td>
	  </tr>
	  <tr>
	  	<td></td>
	  	<td>Rate Type</td>
	  	<td>:</td>
	  	<td><input class="inputtxt" name="ratetyp" id="ratetyp" type="text" style="width: 200px;" maxlength="30" onchange ="upperCase(this.id)">
	  	</td>
	  	<td></td>
	  	<td>Rate(RM)</td>
	  	<td>:</td>
	  	<td>
	  	<input class="inputtxt" name="ratecst" id="ratecst" type="text" style="width: 118px; text-align:right" maxlength="30" onchange="chkdec(this.id)">
	  	</td>
	  </tr>
	  <tr><td></td></tr>
	  <tr>
	  	<td></td>
	  	<td>Quantity</td>
	  	<td>:</td>
	  	<td>
		<input class="inputtxt" name="cutqty" id="cutqty" type="text" style="width: 80px; text-align:right" onblur="chkcutqty(this.id)"> 
		PCS</td>
	  </tr>
	  <tr><td></td></tr>
	   <tr>	
			<td colspan="8" align="center">
				<?php
				 $locatr = "cut_jobdone.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				 include("../Setting/btnsave.php");
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
