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
		$rawmat_maincode   = mysql_real_escape_string($_POST['selmain_code']);
	    //$rawmat_subcode    = mysql_real_escape_string($_POST['selrmcode']);
     	$rawmat_supplierid = $_POST['selsupplier'];
     	$rawmat_curr       = $_POST['selcurr'];
     	$rawmat_uom        = $_POST['seluom'];
     	$rawmat_shipterm   = $_POST['selship_term'];
     	$rawmat_effective  = date('Y-m-d', strtotime($_POST['dteResult']));
     	
     	$rawmat_supplier = '';
     	$sql = " SELECT supplier_code from supplier_master";
     	$sql .= " WHERE supp_id = '$rawmat_supplierid'";

     	$dumsql= mysql_query($sql) or die(mysql_error());
     	while($row = mysql_fetch_array($dumsql))
     	{
     		$rawmat_supplier = $row['supplier_code'];        
     	}
		
     	foreach ($_POST['selrmcode'] as $rawmat_subcode)
     	{
    	     	
		if ($rawmat_subcode <> "") {
			 
			$sysno = '';
     		$sqlchk = " select sysno from system_number ";
     		$sqlchk.= " where type = 'PRICE'";
     
     		$dumsysno= mysql_query($sqlchk) or die(mysql_error());
     		while($row = mysql_fetch_array($dumsysno))
     		{
     			$sysno = $row['sysno'];        
     		}
     		if ($sysno ==NULL)
     		{
     			$sysno = '0';
     					$sysno_sql = "INSERT INTO system_number values ('PRICE', '$sysno')";

     			mysql_query($sysno_sql);

     		}
     		$newsysno = $sysno + 1;
     		
    		
     		$var_sql = " SELECT count(*) as cnt from rawmat_price_ctrl ";
        	$var_sql .= " WHERE rm_code = '$rawmat_subcode'";
        	$var_sql .= " AND supplier = '$rawmat_supplier'";


        	$query_id = mysql_query($var_sql) or die ("Cant Check Raw Material Price Control");
        	$res_id = mysql_fetch_object($query_id);

        	if ($res_id->cnt > 0 ) {
          		$backloc = "../stck_mas/rawmat_price_ctrl.php?stat=3&menucd=".$var_menucode;
          		echo "<script>";
          		echo 'location.replace("'.$backloc.'")';
          		echo "</script>";
        	}else{
				
         		$vartoday = date("Y-m-d H:i:s");
				$sql = "INSERT INTO rawmat_price_ctrl values 
                ('$newsysno', '$rawmat_maincode', '$rawmat_subcode', '$rawmat_supplier', '$rawmat_effective',
                 '$rawmat_uom','$rawmat_curr','$rawmat_shipterm',
                 '$var_loginid', '$vartoday','$var_loginid', '$vartoday')";
				mysql_query($sql);
				
				if(!empty($_POST['fromqty']) && is_array($_POST['fromqty'])) 
				{	
					foreach($_POST['fromqty'] as $row=>$fromqty ) {
						$fromqty    = $fromqty;
						$seqno      = $_POST['seqno'][$row];
						$price   = $_POST['price'][$row];
						$toqty  = $_POST['toqty'][$row];
					
						if ($fromqty != NULL && $toqty != NULL && $price != NULL)
						{
							if ($price== ""){ $price= 0;}
							if ($toqty== ""){ $toqty= 0;}
							
							$sql = "INSERT INTO rawmat_price_trans values 
                			('$newsysno', '$rawmat_subcode', '$fromqty', '$toqty', '$price', '$seqno', '$rawmat_supplier')";
     	 					mysql_query($sql) or die(mysql_error());  					

           				}	
					}
				}
				
				$updsysno_sql = "UPDATE system_number SET sysno = '$newsysno' WHERE type = 'PRICE'";

		     	 mysql_query($updsysno_sql);
				
				$backloc = "../stck_mas/m_rawmat_price_ctrl.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 	
			}		
		}
     	}
	}
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">


<style media="all" type="text/css">
@import "../css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css";
@import "../css/styles.css";

.general-table #fromqty                        { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}
.general-table #procoucost                      { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}
.general-table #prococompt                      { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}

.style2 {
	margin-right: 0px;
}
</style>

<!-- jQuery libs -->
<script type="text/javascript" src="../js/datetimepicker_css.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>
<script type="text/javascript" src="../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../js/Parsers.js"></script>
<script type="text/javascript" src="../js/InputMask.js"></script>


<!-- Our jQuery Script to make everything work -->
<script  type="text/javascript" src="jq-ac-script-price.js"></script>


<script type="text/javascript"> 

function setup() {

		document.InpJobFMas.supplierid.focus();
						
 		//Set up the date parsers
        var dateParser = new DateParser("dd-MM-yyyy");
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		
		var dateMask1 = new DateMask("dd-MM-yyyy", "prorevdte");
		dateMask1.validationMessage = errorMessage;	
		
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask2 = new DateMask("dd-MM-yyyy", "dteResult");
		dateMask1.validationMessage = errorMessage;		
}

$(document).ready(function(){
	var ac_config = {
		source: "autocomscrpro1.php",
		select: function(event, ui){
			$("#prod_code").val(ui.item.prod_code);
			$("#promodesc").val(ui.item.prod_desc);
			$("#totallabcid").val(ui.item.prod_labcst);
	
		},
		minLength:1
		
	};
	$("#prod_code").autocomplete(ac_config);

	$('#select_all').click( function() {
		$('#selrmcode option').prop('selected', true);
	});
	
	$('#unselect_all').click( function() {
		$('#selrmcode option').prop('selected', false);
	});
});

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

function calccompNmix()
{
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length; 
	
	var totmcost = 0;
	for(var i = 1; i < rowCount; i++) { 

		 var vprocostid = "procoucost"+i;
    	 var vprice = "price"+i;
         var vprococost = "prococost"+i;

		 var colmatucost = document.getElementById(vprocostid).value;
		 var colmatucomp = document.getElementById(vprice).value;						
		
		 if (!isNaN(colmatucost) && (colmatucost != "")){
				totmcost = parseFloat(totmcost) + (parseFloat(colmatucost) * parseFloat(colmatucomp));		
		 }
	}
	document.InpJobFMas.totalmatcid.value = parseFloat(totmcost).toFixed(4);
	caltotmscb();
}

function calcCost(vid)
{
    var vprocostid = "procoucost"+vid;
    var vprice = "price"+vid;
    var vprococost = "prococost"+vid;
	
    var col1 = document.getElementById(vprocostid).value;
	var col2 = document.getElementById(vprice).value;		
	var totjob = 0;
	
	if (col1 != ""){
		if(isNaN(col1)) {
    	   alert('Please Enter a valid number for Unit Cost :' + col1);
    	   col1 = 0;
    	}
    	document.getElementById(vprocostid).value = parseFloat(col1).toFixed(4);
    }
    if (col2 != ""){	
		if(isNaN(col2)) {
    	   alert('Please Enter a valid number for Unit Consumption :' + col2);
    	   col2 = 0;
    	}
    	document.getElementById(vprice).value = parseFloat(col2).toFixed(4);
    }	
	
	if ((!isNaN(col1) && (col1 != "")) && (!isNaN(col2) && (col2 != ""))){
		totjob = parseFloat(col1) * parseFloat(col2);
		document.getElementById(vprococost).value = parseFloat(totjob).toFixed(4);
				
		var table = document.getElementById('itemsTable');
		var rowCount = table.rows.length; 
		
		var totmat = 0;
		for(var i = 1; i < rowCount; i++) { 
			var vprococost = "prococost"+i;
			var colmatcost = document.getElementById(vprococost).value;					
		
			if (!isNaN(colmatcost) && (colmatcost != "")){
				totmat = parseFloat(totmat) + parseFloat(colmatcost);		
			}
		}
		document.InpJobFMas.totalmatc.value = parseFloat(totmat).toFixed(4);	
     }	
     caltotmscb();
	 calctotcost();	
}

function caltotmscb()
{
	    var totmscb = 0;
		var form=document.getElementById('itemsTable');
		if(!form) return;
		
        var fields=form.getElementsByTagName('input');        
        var totmark = 0;
        for (var i=0;i<fields.length;i++){
              if( fields[i].getAttribute('tMark') != "1" ) continue;//reject any field not carring the "sumMe" attribute
     		  var txt = fields[i].value;
			
			  if (!isNaN(txt) && (txt != "")){
			   	totmark = parseFloat(totmark) + parseFloat(txt);
		      }
		 }
		
         var totSpre = 0;
         for (var i=0;i<fields.length;i++){
              if( fields[i].getAttribute('tSpre') != "1" ) continue;//reject any field not carring the "sumMe" attribute
			  var txt = fields[i].value;
			
			  if (!isNaN(txt) && (txt != "")){
			   	totSpre = parseFloat(totSpre) + parseFloat(txt);
		      }
		  }
		  var totCut = 0;
          for (var i=0;i<fields.length;i++){
              if( fields[i].getAttribute('tCut') != "1" ) continue;//reject any field not carring the "sumMe" attribute
			  var txt = fields[i].value;
			
			  if (!isNaN(txt) && (txt != "")){
			   	totCut = parseFloat(totCut) + parseFloat(txt);
		      }
		  }
		  var totBund = 0;
          for (var i=0;i<fields.length;i++){
              if( fields[i].getAttribute('tBund') != "1" ) continue;//reject any field not carring the "sumMe" attribute
			  var txt = fields[i].value;
			
			  if (!isNaN(txt) && (txt != "")){
			   	totBund = parseFloat(totBund) + parseFloat(txt);
		      }
		  }
          totmscb = parseFloat(totmark) + parseFloat(totSpre) + parseFloat(totCut) + parseFloat(totBund);
          document.getElementById('totalmixid').value = parseFloat(totmscb).toFixed(4);
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

function validateForm()
{

    var x=document.forms["InpJobFMas"]["refno"].value;
	if (x==null || x=="")
	{
	alert("Ref No Must Not Be Blank");
	document.InpJobFMas.procorev.focus;
	return false;
	}

    var x=document.forms["InpJobFMas"]["prorevdte"].value;
	if (x==null || x=="")
	{
	alert("Date Must Not Be Blank");
	document.InpJobFMas.prorevdte.focus;
	return false;
	}
	
			
	//Check the list of mat item no is Valid-------------------------------------------------------------
	var flgchk = 1;	
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
         
    for (var j = 1; j < rowCount; j++){
       var idrowItem = "fromqty"+j;
       var rowItem = document.getElementById(idrowItem).value;	 
              
       if (rowItem != ""){
       	var strURL="aja_chk_subCodeCount.php?rawmat_subcodeg="+rowItem;
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
						if (req.responseText == 0)
						{
						   flgchk = 0;
						   alert ('Invalid Raw Mat Item Sub Code : '+ rowItem + ' At Row '+j);
						   return false;
						}
					} else {
						//alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
						return false;
					}
				}
			}	 
		  }
		
		  req.open("GET", strURL, false);
		  req.send(null);
	    }	  
    }
     if (flgchk == 0){
	   return false;
	}
    //---------------------------------------------------------------------------------------------------

	//Check the list of mat item no got duplicate item no------------------------------------------------
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
	var mylist = new Array();	    

	for (var j = 1; j < rowCount; j++){

	    var idrowItem = "fromqty"+j;
        var rowItem = document.getElementById(idrowItem).value;	 
        if (rowItem != ""){ 
        	mylist.push(rowItem);   
	    }		
    }		
	
	mylist.sort();
	var last = mylist[0];
	
	for (var i=1; i < mylist.length; i++) {
		if (mylist[i] == last){ 
			alert ("Duplicate Item Found; " + last);
			 return false;
		}	
		last = mylist[i];
	}
	//---------------------------------------------------------------------------------------------------
}

function deleteRow(tableID) {
	try {
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;
         
        if (rowCount > 2){
             table.deleteRow(rowCount - 1);
        }else{
             alert ("No More Row To Remove");
        }
	}catch(e) {
		alert(e);
	}
	calccompNmix();
	calctotcost();
}

function showoveDecimal(ovecst){
 	if (ovecst != ""){
		if(isNaN(ovecst)) {
    	   alert('Please Enter a number for Overhead :' + ovecst);
    	   document.InpJobFMas.totalovehid.focus();
    	   return false;
    	}
    	document.InpJobFMas.totalovehid.value = parseFloat(ovecst).toFixed(4);
    	calctotcost();
    }
}

function showexftrDecimal(exftrecst)
{
	if (exftrecst != ""){
		if(isNaN(exftrecst)) {
    	   alert('Please Enter a number for Ex-Factory :' + exftrecst);
    	   document.InpJobFMas.totalexft0.focus();
    	   return false;
    	}
    	document.InpJobFMas.totalexft0.value = parseFloat(exftrecst).toFixed(4);
    	calsalrm();
    }
}

function calctotcost(){
     var rawmatcst = document.InpJobFMas.totalmatcid.value;
     var labcst    = document.InpJobFMas.totallabcid.value;
     var ovecst    = document.InpJobFMas.totalovehid.value;
     var omixcst   = document.InpJobFMas.totalmixid.value;
     var totcstall;
     
     if (rawmatcst == ""){
      	rawmatcst = 0;
      	document.InpJobFMas.totalmatcid.value = parseFloat(rawmatcst).toFixed(4);
     }
     if (labcst == ""){
      	labcst = 0;
      	//document.InpJobFMas.totallabcid.value = parseFloat(labcst).toFixed(4);
     }
     if (ovecst == ""){
      	ovecst = 0;
      	//document.InpJobFMas.totalovehid.value = parseFloat(ovecst).toFixed(4);
     }
	 if (omixcst == ""){
      	omixcst = 0;
      	document.InpJobFMas.totalmixid.value = parseFloat(omixcst).toFixed(4);
     }
     
	 totcstall = parseFloat(rawmatcst) + parseFloat(labcst) + parseFloat(ovecst) + parseFloat(omixcst); 
     document.InpJobFMas.totalcosid.value = parseFloat(totcstall).toFixed(4);
}

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

function showtaxperDec(taxper){
	if (taxper != ""){
		if(isNaN(taxper)) {
    	   alert('Please Enter a number for Salex Tax :' + taxper);
    	   document.InpJobFMas.totalsaltid.focus();
    	   return false;
    	}
    	document.InpJobFMas.totalsaltid.value = parseInt(taxper);
    	calsalrm();
    }
}

function get_labrate(itemcode)
{
	var strURL="aja_procd_labrate.php?procode="+itemcode;
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
					   document.InpJobFMas.totallabcid.value = req.responseText;
				} 
			}
		}	 
	}
	req.open("GET", strURL, true);
	req.send(null);

}

function getItem(supp_code, main_code)
{
//   var strURL="aja_get_itemno.php?main_code="+main_code;
   var strURL="aja_get_itemno_multi.php?supp_code="+supp_code+"&main_code="+main_code;
   //alert("Image's Source is: " + strURL);

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
	    document.getElementById('statedivx').innerHTML=req.responseText;
	 } else {
   	   alert("There was a problem while using XMLHTTP:\n" + req.statusText);
	 }
       }
      }
   req.open("GET", strURL, true);
   req.send(null);
   }
}


function getPrice(supp_code, main_code)
{
   var strURL="aja_chk_price.php?supp_code="+supp_code+"&main_code="+main_code;
 	//alert("AJAX chk price is: " + strURL);

		var httpxml;
		try	{
			// Firefox, Opera 8.0+, Safari
			httpxml=new XMLHttpRequest();
		}catch (e){
		  // Internet Explorer
		  try{
			  httpxml=new ActiveXObject("Msxml2.XMLHTTP");
		  }catch (e){
		    try{
			   httpxml=new ActiveXObject("Microsoft.XMLHTTP");
		    }catch (e){
			   alert("Your browser does not support AJAX!");
			   return false;
		    }
		}
		
	}

	function stateck()
	{
		if(httpxml.readyState==4)
		{
			document.getElementById("msgcd").innerHTML=httpxml.responseText;
		}
	}
	var url="aja_chk_price.php?supp_code="+supp_code+"&rm_code="+main_code;
	
    //alert("second is: " + url);

	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",encodeURI(url),true);
	httpxml.send(null);

}


function getSupp(supp_code)
{
   var strURL="aja_get_supp.php?supp_code="+supp_code;

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
	    document.getElementById('statedivx0').innerHTML=req.responseText;
	 } else {
   	   alert("There was a problem while using XMLHTTP:\n" + req.statusText);
	 }
       }
      }
   req.open("GET", strURL, true);
   req.send(null);
   }
}

function disp_dec(vid)
{
    var vprice = "priceid"+vid;
    var col1 = document.getElementById(vprice).value;

	if (col1 != ""){
		if(isNaN(col1)) {
    	   alert('Please Enter a valid number for Quantity :' + col1);
    	   col1 = 0;
    	}
    	document.getElementById(vprice).value = parseFloat(col1).toFixed(3);	
    }
}

</script>
</head>
<body onload="setup()">
	 <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->

 
  <div class="contentc">

	<fieldset name="Group1" style=" width: 924px;" class="style2">
	 <legend class="title">RAWMAT PRICE CONTROL</legend>
	  <br>	 
	  
	  <form name="InpJobFMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 866px">
	   	   <tr>
	   	    <td></td>
	  	    <td style="width: 418px">Supplier</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 231px">
			<select name="selsupplier" style="width: 200px" onchange="getSupp(this.value)" id="supplierid">
			    <?php
                   $sql = "select supp_id, supplier_desc from supplier_master ORDER BY supplier_desc ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['supp_id'].'">'.$row['supplier_desc'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select>
			</td>
			<td style="width: 199px"></td>
			<td style="width: 136px">Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 270px">
		   <input class="inputtxt" name="prorevdte" id ="prorevdte" type="text" style="width: 128px;" value="<?php  echo date("d-m-Y"); ?>" tabindex="0" >
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('prorevdte','ddMMyyyy')" style="cursor:pointer">
		   </td>
	  	  </tr>  
	  	  <tr>
	  	    <td style="width: 2px;">
	  	    </td>
	  	    <td style="width: 418px;" class="tdlabel">Master Code</td>
	  	    <td style="width: 4px;">:</td>
	  	    <td style="width: 231px;">
			<p id="statedivx0" style="width: 210px;"></p>
			
			  </td>
			<td style="width: 199px"></td>
		    <td style="width: 105px;" class="tdlabel">Currency Code</td>
	  	    <td style="width: 5px;">:</td>
	  	    <td style="width: 108px;">
			   <select name="selcurr" style="width: 68px">
			    <?php
                   $sql = "select currency_code, currency_desc from currency_master ORDER BY currency_code ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['currency_code'].'">'.$row['currency_code'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select></td>
	  	  </tr>
	   	   <tr>
	   	    <td style="width: 2px;">
	  	    </td>
	  	    <td style="width: 418px;" class="tdlabel">Sub Code No.</td>
	  	    <td style="width: 4px;">:</td>
	  	    <td style="width: 231px;">
	  	    <a href="#" id="select_all">Select All</a>&nbsp;|&nbsp;<a href="#" id="unselect_all">Unselect All</a>
			<br>(CTRL + Left Click for Multiple)	  	    
			<p id="statedivx" style="width: 210px;"></p>
			
			</td>
			<td style="width: 199px">
  				<p id="citydiv"></p>
   			</td>
		    <td style="width: 105px;" class="tdlabel">UOM</td>
	  	    <td style="width: 5px;">:</td>
	  	    <td style="width: 108px;">
			   <select name="seluom" style="width: 68px">
			    <?php
                   $sql = "select uom_code, uom_desc from uom_master ORDER BY uom_code ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['uom_code'].'">'.$row['uom_desc'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select></td>
	  	  </tr>
	  	  <tr>
	   	    <td></td>
	  	    <td style="width: 418px"></td>
	  	    <td style="width: 13px"></td>
	  	    <td colspan="5"></td>
	  	  </tr> 
		  <tr>
	  	    <td style="width: 2px;">
	  	    </td>
	  	    <td style="width: 418px;" class="tdlabel">Effective 
			Date</td>
	  	    <td style="width: 4px;">:</td>
	  	    <td style="width: 231px;">
			<input class="inputtxt" name="dteResult" id ="dteResult" type="text" maxlength="50" onchange ="upperCase(this.id)" value="<?php  echo date("d-m-Y"); ?>"/>

		 	<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('dteResult','ddMMyyyy')" style="cursor:pointer">
			<td style="width: 199px">&nbsp;<td style="width: 227px">
			  Shipping Term</td>
			<td style="width: 5px;">:</td>
            <td style="width: 6px;">
			   <select name="selship_term" style="width: 116px">
			    <?php
                   $sql = "select shiptecd, shiptede from ship_term_master ORDER BY shiptecd ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['shiptecd'].'">'.$row['shiptede'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select></td>
		  
		  <tr>
	   	    <td></td>
	  	    <td style="width: 418px">Remark</td>
	  	    <td style="width: 13px">:</td>
	  	    <td colspan="5">
			<input class="inputtxt" name="remark" id="remark" type="text" maxlength="100" style="width: 634px;" onchange ="upperCase(this.id)" tabindex="0">
			</td>
	  	  </tr> 

	  	  		  	
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader">From Qty</th>
              <th class="tabheader">To Qty</th>
              <th class="tabheader">Price</th>
             </tr>
            </thead>
            <tbody>
            
             <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="1" readonly="readonly" style="width: 27px;"></td>
                <td>
				<input name="fromqty[]" value="0" tProItem1=1 id="fromqty1" tabindex="0" class="tInput" style="width: 75px" onchange ="upperCase(this.id)"></td>
                <td>
                <input name="toqty[]" value="0" class="tInput" tMark="1" id="toqty1"  style="width: 75px; "> </td>
				<td>
				<input name="price[]" value="0" class="tInput" id="priceid1" style="width: 75px" onBlur="disp_dec('1');"></td>  
             </tr>
            </tbody>
           </table>
           
         <a href="#" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Item</span></a>
         <a href="javascript:deleteRow('itemsTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>

	
		 <table>
		  	<tr>
				<td style="width: 777px; height: 22px;" align="center">
				<?php
				 $locatr = "m_rawmat_price_ctrl.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
					include("../Setting/btnsave.php");
				?>
				</td>
			</tr>
			<tr>
				<td style="width: 777px" colspan="5">
				<span style="color:#FF0000">Message :</span>
				<?php
					if (isset($var_stat)){
						switch ($var_stat)
						{
						case 1:
							echo("<span>Success Process</span>");
							break;
						case 0:
							echo("<span>Process Fail</span>");
							break;
						case 3:
							echo("<span>Duplicated Found For Same Supplier and Sub-Code/span>");
							break;
						case 4:
							echo("<span>Please Fill In The Data To Save</span>");
							break;
						case 5:
							echo("<span>This Product Code And Rev No Has A Record</span>");
							break;
						case 6:
							echo("<span>Duplicate Job File ID Found; Process Fail.</span>");
							break;
						case 7:
							echo("<span>This Product Code Dost Not Exits</span>");
							break;			
						default:
							echo "";
						}
					}	
				?>
				</td>
			</tr>
			<tr>
				<td style="width: 777px">&nbsp;</td>
			</tr>
	  	</table>
	   </form>	
	</fieldset>
	</div>
	<div class="spacer"></div>
</body>

</html>
