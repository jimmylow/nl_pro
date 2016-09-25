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
		$cutno   = htmlentities(mysql_real_escape_string($_POST['cutsheno']));
		$cutdte  = date('Y-m-d', strtotime($_POST['cutdte']));
    	$ordnostr= $_POST['orderno'];
    	$sorddte = date('Y-m-d', strtotime($_POST['salorddte']));
		$sdeldte = date('Y-m-d', strtotime($_POST['saldelidte']));
 		$grpno   = $_POST['cutgrpno'];
 		$prdnostr= $_POST['cutprodcd'];
 		$prdcolno= $_POST['cutprodcol'];

 		$defarr = explode(",", $ordnostr);
        $ordno  = $defarr[0];
        $buyno  = $defarr[1];
        
       	$defarr = explode("^", $prdnostr);
        $prcat  = $defarr[0];
        $prcnum = $defarr[1];
     
        $sqlchk = " delete from tmpcut01 where usernm = '$var_loginid' ";     
        mysql_query($sqlchk) or die(mysql_error());
               	 
     	//----------------------------------------------------------------
     	if(!empty($_POST['prsize']) && is_array($_POST['prsize'])) 
		 {	
			foreach($_POST['prsize'] as $row=>$prsize ) {
				$orderqty = $_POST['ordqty'][$row];
				$pruom    = $_POST['pruom'][$row];
				$chk      = $_POST['chk'][$row];
				
				if (isset($_POST['chk'][$row])) {
				
					$sql2  = "INSERT INTO tmpcut01 values ";
					$sql2 .= " ('$cutno', '$cutdte', '$ordno', '$buyno', '$sorddte', ";
					$sql2 .= "  '$sdeldte', '$grpno', '$prcat', '$prcnum', '$prdcolno', '$prsize', ";
					$sql2 .= "  '$orderqty', '$pruom', '$var_loginid')";
					mysql_query($sql2) or die(mysql_error());					
				}
			}				
		 }
   	  	     	 
     	 $backloc = "prod_csrmcode.php?menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
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

		document.InpCutSheet.cutsheno.focus();
						
 		//Set up the date parsers
        var dateParser = new DateParser("dd-MM-yyyy");
      
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("dd-MM-yyyy", "cutdte");
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
    
function chk_cutno(cutno)
{
   var strURL="aja_chk_cutno.php?c="+cutno;

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
	    		document.getElementById('msgdivx').innerHTML="<font color=red>This Cutting Sheet No Has Been Create.</font>";
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

function getsaldet(ordno)
{
	if (ordno == ""){
		document.getElementById('buyerno').value = "";
   		document.getElementById('salorddte').value = "";
  		document.getElementById('saldelidte').value = "";
	}else{
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
   	var strURL="aja_get_saldet.php?o="+ord+"&b="+buy;

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
     	    	var str_array = req.responseText.split('^');
				for(var i = 0; i < str_array.length; i++)
				{
   					str_array[i] = str_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
   					// Add additional code here, such as:
   					if (i == 0){
   						var ordd = str_array[i];
   					}else{
   						if (i == 2){
   							var orddel = str_array[i];
   						}	
   					}	
				}	

	    		document.getElementById('buyerno').value = buy;
	    		document.getElementById('salorddte').value = ordd;
	    		document.getElementById('saldelidte').value = orddel;
	    		disp_procd(ord, buy);
	    		document.getElementById('cutgrpno').focus();
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

function disp_procd(ordno, buyno)
{
   	var strURL="aja_get_salprodcd.php?o="+ordno+"&b="+buyno;

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
	
	var strURL="aja_get_salprodcol.php?o="+ord+"&b="+buy+"&c="+prcat+"&cdn="+prnum;

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
	    		document.getElementById('colordiv').innerHTML = req.responseText;
	 		}else{
   	   			alert("There was a problem while using XMLHTTP:\n" + req.statusText);
	 		}
     	 }
        }
   	req.open("GET", strURL, true);
   	req.send(null);
   }	
}

function disprmcode(col)
{	
	var comprocd = document.getElementById('cutprodcd').value;
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
	
	var col    = document.getElementById('cutprodcol').value;	
	var strURL="aja_get_prodoqty.php?or="+ord+"&by="+buy+"&cat="+prcat+"&prnum="+prnum+"&cl="+col;

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
	    		document.getElementById('statedivx0').innerHTML = req.responseText;
	 		}else{
   	   			alert("There was a problem while using XMLHTTP:\n" + req.statusText);
	 		}
     	 }
        }
   	req.open("GET", strURL, true);
   	req.send(null);
   }	
}

function mycheckdec(valdecbk, valid){
	var puqtyid = "ordqty"+valid;

	if (valdecbk != ""){
		if(isNaN(valdecbk)) {
    	   alert('Please Enter a valid number for Order Quantity:' + valdecbk);
    	   document.getElementById(puqtyid).focus();
    	   valdecbk = 0;
    	}
    	document.getElementById(puqtyid).value = parseFloat(valdecbk).toFixed(2);
    }else{
    	valdecbk = 0;
    	document.getElementById(puqtyid).value = parseFloat(valdecbk).toFixed(2);
    }
    if (parseFloat(valdecbk) < 0){	
    	alert('Purchase Qunatity Cannot Negative Value:' + valdecbk);
		document.getElementById(puqtyid).focus();
    }
}
        
function validateForm()
{
    var x=document.forms["InpCutSheet"]["cutsheno"].value;
	if (x==null || x=="")
	{
		alert("Cutting Sheet No Must Not Be Blank");
		document.InpCutSheet.cutsheno.focus();
		return false;
	}
	
	var x=document.forms["InpCutSheet"]["cutdte"].value;
	if (x==null || x=="")
	{
		alert("Cutting Date Must Not Be Blank");
		document.InpCutSheet.cutdte.focus();
		return false;
	}
	
	var x=document.forms["InpCutSheet"]["orderno"].value;
	if (x==null || x=="")
	{
		alert("Order No Must Not Be Blank");
		document.InpCutSheet.orderno.focus();
		return false;
	}
	
	var x=document.forms["InpCutSheet"]["cutgrpno"].value;
	if (x==null || x=="")
	{
		alert("Group Must Not Be Blank");
		document.InpCutSheet.cutgrpno.focus();
		return false;
	}
	
	var x=document.forms["InpCutSheet"]["cutprodcd"].value;
	if (x==null || x=="")
	{
		alert("Product Code Must Not Be Blank");
		document.InpCutSheet.cutprodcd.focus();
		return false;
	}
	
	var x=document.forms["InpCutSheet"]["cutprodcol"].value;
	if (x==null || x=="")
	{
		alert("Color Code Must Not Be Blank");
		document.InpCutSheet.cutprodcol.focus();
		return false;
	}
	
	//Check input order quantity is empty-------------------------------------------------------
	  var table = document.getElementById('itemsTable');
	  var rowCount = table.rows.length;  
	
	  for (var j = 1; j < rowCount; j++){

	    var idrowbook = "ordqty"+j;
	    var idrowsize = "prsize"+j;
	    var rowItemz = document.getElementById(idrowsize).value;	
        var rowItemc = document.getElementById(idrowbook).value;	 
        
        if (rowItemz != ""){
        	if (rowItemc == ""){ 
    		  alert('Please Enter a valid number for Order Quantity :' + rowItemc + " line No :"+j);
    		  return false;
    		}      
    	}
       }		
    //---------------------------------------------------------------------------------------------------

	//Check input order quantity is Valid-------------------------------------------------------
	  var table = document.getElementById('itemsTable');
	  var rowCount = table.rows.length;  
	
	  for (var j = 1; j < rowCount; j++){

	    var idrowbook = "ordqty"+j;
        var rowItemc = document.getElementById(idrowbook).value;	 
        
        if (rowItemc != ""){ 
        	if(isNaN(rowItemc)) {
    	   		alert('Please Enter a valid number for Order Quantity :' + rowItemc + " line No :"+j);
    	        return false;
    	    }    
    	}
       }		
    //---------------------------------------------------------------------------------------------------
}

</script>
</head>
<body onload="setup()">
	<?php include("../topbarm.php"); ?> 
<!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc" style="width: 978px;">

	<fieldset name="Group1" style=" width: 953px; height: 580px;">
	 <legend class="title">CUTTING SHEET</legend>
	 
	  <form name="InpCutSheet" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()" style="height: 497px; width: 929px;">
		<table style="width: 938px; height: 432px;">
		 <tr>
	  	    <td style="width: 5px"></td>
	  	    <td style="width: 50px;" class="tdlabel">Cutting Sheet No</td>
	  	    <td style="width: 4px;">:</td>
	  	    <td style="width: 82px;">
				<input class="inputtxt" name="cutsheno" id="cutsheno" type="text" style="width: 196px;" onblur="upperCase(this.id)" onchange="chk_cutno(this.value)"></td>
			<td style="width: 9px"></td>
		    <td style="width: 50px;" class="tdlabel">Cut&nbsp; Date</td>
	  	    <td style="width: 5px;">:</td>
	  	    <td style="width: 69px;">
		   		<input class="inputtxt" name="cutdte" id ="cutdte" type="text" style="width: 128px;" value="<?php  echo date("d-m-Y"); ?>">
		   		<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('cutdte','ddMMyyyy')" style="cursor:pointer">
		   	</td>
	  	  </tr>
		  <tr>
		  	<td style="width: 5px"></td>
		  	<td></td>
		  	<td></td>
		  	<td><div id="msgdivx"></div></td>
		  </tr>	
	  	  <tr>
	  	    <td style="width: 5px"></td>
	  	    <td style="width: 50px;">Order No</td>
	  	    <td style="width: 4px;">:</td>
	  	    <td style="width: 82px;">
				<select name="orderno" style="width: 200px" onchange="getsaldet(this.value)" id="orderno">
			    <?php
                   $sql = "select sordno, sbuycd from salesentry WHERE stat = 'ACTIVE' ORDER BY sordno, sbuycd";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     {
				      $values = implode(',', $row);	 
					  echo '<option value="'.$values.'">'.$row['sordno']." - ".$row['sbuycd'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select>
			</td>
			<td style="width: 9px"></td>
		    <td style="width: 50px;">Buyer</td>
	  	    <td style="width: 5px;">:</td>
	  	    <td style="width: 69px;">
				<input class="inputtxt" name="buyerno" id="buyerno" type="text" style="width: 125px;">
			</td>
	  	  </tr>
	  	  <tr><td></td></tr>
	  	  <tr>
	  	  	<td style="height: 26px"></td>
	  	  	<td style="height: 26px">Order Date</td>
	  	  	<td style="height: 26px">:</td>
	  	  	<td style="height: 26px">
		   		<input class="inputtxt" name="salorddte" id ="salorddte" type="text" style="width: 128px;"></td>
	  	  </tr>
	  	  <tr><td></td></tr>
		  <tr>
		  	  <td></td>
		  	  <td>Delivery Date</td>
		  	  <td>:</td>
		  	  <td>
		   		<input class="inputtxt" name="saldelidte" id ="saldelidte" type="text" style="width: 128px;"></td>
		   	  <td></td>
		   	  <td>Group</td>
		   	  <td>:</td>
		   	  <td>
		   	  
				<select name="cutgrpno" id="cutgrpno" style="width: 200px">
			    <?php
                   $sql = "select grpcd, grpde from wor_grpmas ORDER BY grpcd";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['grpcd'].'">'.$row['grpcd'].' - '.$row['grpde'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select>
			  </td>
		  </tr>
		  <tr><td></td></tr>
		  <tr>
		  	<td></td>
		  	<td>Product Code</td>
		  	<td>:</td>
		  	<td><div id="proddiv"></div></td>
		  	<td></td>
		  	<td>Color</td>
		  	<td>:</td>
		  	<td><div id="colordiv"></div></td>
		  </tr>
		  <tr><td></td></tr>	
	  	    <tr>
			<td colspan="8" align="center">
				<?php
				 $locatr = "cut_sheet.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				 include("../Setting/btnsave.php");
				?>
	  	    </td>
	  	    </tr>
	  	  <tr>
	  	    <td colspan="8">
			<p id="statedivx0" style="width: 925px; height: 314px"></p>
			</td>
		  </tr>
	  	  <tr><td></td></tr>
	  	  <tr><td></td></tr>
	  	</table>
	   </form>	
	</fieldset>
    </div>
    <div class="spacer"></div>
</body>

</html>
