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
      $workerid = $_GET['workerid'];
      $sewdate = $_GET['sewdate'];
      include("../Setting/ChqAuth.php");

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

.general-table #procomat                        { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}
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

<!-- Our jQuery Script to make everything work -->
<script  type="text/javascript" src="jq-ac-script.js"></script>


<script type="text/javascript"> 
$(document).ready(function(){
	var ac_config = {
		source: "autocom_bar.php",
		select: function(event, ui){
			$("#prod_code").val(ui.item.prod_code);
			$("#ticketnoid").val(ui.item.ticketno);
			$("#productnoid").val(ui.item.prod_code);
			$("#jobcodeid").val(ui.item.prod_jobid);
			$("#jobrateid").val(ui.item.prod_jobrate);
			$("#productqtyid").val(ui.item.productqty);
			
			
		},
		minLength:1
		
	};
	$("#prod_code").autocomplete(ac_config);
});

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();

}
function getState(areacd)
{
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
	
		//var workerid = document.InpJobFMas.workerid.value;
		var sewdate = document.InpJobFMas.sewdate.value;
		var url="aja_ins_qc.php";
		
		
		//url=url+"?barcode="+areacd+"&sewdate="+sewdate+"&workerid="+workerid;
		url=url+"?areacd="+areacd+"&sewdate="+sewdate;
		url=url+"&sid="+Math.random();

		document.InpJobFMas.prod_code.value = '';
		document.InpJobFMas.prod_code.focus();
		httpxml.onreadystatechange=stateck;
	
		httpxml.open("GET",encodeURI(url),true);
		httpxml.send(null);
	}	

function calccompNmix()
{
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length; 
	
	var totmcost = 0;
	for(var i = 1; i < rowCount; i++) { 

		 var vprocostid = "procoucost"+i;
    	 var vprococompt = "prococompt"+i;
         var vprococost = "prococost"+i;

		 var colmatucost = document.getElementById(vprocostid).value;
		 var colmatucomp = document.getElementById(vprococompt).value;						
		
		 if (!isNaN(colmatucost) && (colmatucost != "")){
				totmcost = parseFloat(totmcost) + (parseFloat(colmatucost) * parseFloat(colmatucomp));		
		 }
	}
	document.InpJobFMas.totalmatcid.value = parseFloat(totmcost).toFixed(4);
	caltotmscb();
}

function checkcompsum(vid)
{
	 var vprococompt = "prococompt"+vid;
	 var col2 = document.getElementById(vprococompt).value;	
	 
	 if (col2 != ""){	
		if(isNaN(col2)) {
    	   alert('Please Enter a valid number for Unit Consumption :' + col2);
    	   col2 = 0;
    	}
    	document.getElementById(vprococompt).value = parseFloat(col2).toFixed(4);
    }	

}

function calcCost(vid)
{
    var vprocostid = "procoucost"+vid;
    var vprococompt = "prococompt"+vid;
    var vprococost = "prococost"+vid;
	
    var col1 = document.getElementById(vprocostid).value;
	var col2 = document.getElementById(vprococompt).value;		
	var totjob = 0;
	
	if (col1 != ""){
		if(isNaN(col1)) {
    	   alert('Please Enter a valid number for Unit Cost :' + col1);
    	   col1 = 0;
    	}
    	document.getElementById(vprocostid).value = parseFloat(col1).toFixed(4);
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

	
	var x=document.forms["InpJobFMas"]["prod_code"].value;
	
	if (x==null || x=="")
	{
	alert("Barcode Number Must Not Be Blank");
	document.InpJobFMas.prod_code.focus();
	return false;
	}
	
	
	var x=document.forms["InpJobFMas"]["grpcd"].value;
	if (x==null || x=="")
	{
	alert("Worker Number Must Not Be Blank");
	document.InpJobFMas.grpcd.focus();
	return false;
	}

	var x=document.forms["InpJobFMas"]["qtydoz"].value;
	if (x==null || x=="")
	{
	alert("Product Code Must Not Be Blank");
	document.InpJobFMas.selmaincode.focus();
	return false;
	}
	
	
	var x=document.forms["InpJobFMas"]["productqty"].value;
	if (x==null || x=="")
	{
	alert("Product Qty Must Not Be Blank");
	document.InpJobFMas.productqty.focus();
	return false;
	}
	
	//Check the duplicate of prod code--------------------------------------------------------
	var x      = document.InpJobFMas.prod_code.value;
	var flgchk = 1;
	var strURL="../bom_tran/aja_chk_prodrev.php?procode="+x;
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
				
					if (req.responseText != 0)
					{
					  flgchk = 0;
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
    if (flgchk == 0){
       alert ('This Product Code Already Have a Record :'+x);
       document.InpJobFMas.prod_code.focus();
	   return false;
	}
	//---------------------------------------------------------------------------------------------------	
	
	//Check the list of mat item no is Valid-------------------------------------------------------------
	var flgchk = 1;	
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
         
    for (var j = 1; j < rowCount; j++){
       var idrowItem = "procomat"+j;
       var rowItem = document.getElementById(idrowItem).value;	 
              
       if (rowItem != ""){
       	var strURL="aja_chk_subCodeCount.php?rawmatcdg="+rowItem;
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
	var mylist2 = new Array();	    

	for (var j = 1; j < rowCount; j++){

	    var idrowItem = "procomat"+j;
	    var idrowItem2 = "procodesc"+j;
        var rowItem = document.getElementById(idrowItem).value;
        var rowItem2 = document.getElementById(idrowItem2).value;		 
        if (rowItem != ""){ 
        	mylist.push(rowItem);   
	    }
	    if (rowItem2 != ""){ 
        	mylist2.push(rowItem2);   
	    }		
    }		
	
	mylist.sort();
	mylist2.sort();
	var last = mylist[0];
	var last2 = mylist2[0];
	
	for (var i=1; i < mylist.length; i++) {
		if (mylist[i] == last){ 
			if (mylist2[i] == last2){ 
				alert ("Duplicate Item & Description Found; " + last + " "+ last2);
				return false;
			}	
		}	
		last = mylist[i];
		last2 = mylist2[i];
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
    	   //alert('Please Enter a number for Ex-Factory :' + exftrecst);
    	   //document.InpJobFMas.totalexft0.focus();
    	   //return false;
    	}
    	//document.InpJobFMas.totalexft0.value = parseFloat(exftrecst).toFixed(4);
    	//calsalrm();
    }
}

function showlabDecimal(labcst)
{
	if (labcst != ""){
		if(isNaN(labcst)) {
    	   alert('Please Enter a number for Ex-Factory :' + labcst);
    	   document.InpJobFMas.totallabcid.focus();
    	   return false;
    	}
    	document.InpJobFMas.totallabcid.value = parseFloat(labcst).toFixed(4);
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
    //------------------Check ALready Create Product costing For this Product Code---
    var x      = document.InpJobFMas.prod_code.value;
	var flgchk = 1;
	var strURL="../bom_tran/aja_chk_prodrev.php?procode="+x;
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
				
					if (req.responseText != 0)
					{
					  flgchk = 0;

					}
				} else {
					alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
					return false;
				}
			}
		}	 
	}
	req.open("GET", strURL, false);
	req.send(null);
    if (flgchk == 0){
       alert ('This Product Code Already Have a Record :'+x);	
       document.InpJobFMas.prod_code.focus();
	}
    //-------------------------------------------------------------------------------

	//------------------Get Description & Labour Rate----------------- 
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
	 //------------------Get Description & Labour Rate----------------- 
}

function get_desc(itemcode, vid)
{
    var iditmdesc = "procodesc"+vid;
    var iditmuom  = "procouom"+vid;
    var iditmmark = "procomark"+vid;
    var iditmspre = "procospre"+vid;
    var iditmcut  = "prococut"+vid;
    var iditmbund = "procobund"+vid;
    var iditmcost = "procoucost"+vid;
    
	var strURL="aja_get_itmdesc.php?itmcod="+itemcode;
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
					var obj = jQuery.parseJSON(req.responseText);
					
					if (obj != null){
						document.getElementById(iditmdesc).value = obj.desc.replace(/&quot;/g, "\"");
						document.getElementById(iditmuom).value = obj.uom;
						document.getElementById(iditmmark).value = obj.mark;
						document.getElementById(iditmspre).value = obj.spread;
						document.getElementById(iditmcut).value = obj.cut;
						document.getElementById(iditmbund).value = obj.bundle;
						document.getElementById(iditmcost).value = obj.cost;
					}else{
						document.getElementById(iditmdesc).value = '';
						document.getElementById(iditmuom).value = '';
						document.getElementById(iditmmark).value = '';
						document.getElementById(iditmspre).value = '';
						document.getElementById(iditmcut).value = '';
						document.getElementById(iditmbund).value = '';
						document.getElementById(iditmcost).value = '';
					}	
				} 
			}
		}	 
	}
	req.open("GET", strURL, true);
	req.send(null);
}

function setup() {

		document.InpJobFMas.prod_code.focus();
						
 		//Set up the date parsers
        var dateParser = new DateParser("dd-MM-yyyy");
      
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("dd-MM-yyyy", "prorevdte");
		dateMask1.validationMessage = errorMessage;		
}

</script>
</head>
<?php
	$sql = "select workname from wor_detmas ";
    $sql .= " where workid ='$workerid'";
    $sql_result = mysql_query($sql);
    $row = mysql_fetch_array($sql_result);
    $workername = $row[0];


?>
 
<body onload="setup()">
 <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>--> 
  <div class ="contentc">

	<fieldset name="Group1" style="width: 1150px;" class="style2">
	 <legend class="title">SEWING QC ENTRY</legend>
	  <br>	 
	  
	  <form name="InpJobFMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   

		<table style="width: 886px">
	   	    <tr>
	  	   <td></td>
		   <td style="width: 80px">QC Date</td>
		   <td>:</td>
		    <td style="width: 272px">
			<input class="textnoentry1" name="sewdate" id="sewdate" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $sewdate; ?>"></td>
		   <td></td>
		   <td style="width: 136px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td>
		   &nbsp;</td>
	  	    </tr>
	   	  <tr>
	  	   <td></td>
	  	   <td style="width: 80px">Ticket No.</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 272px">
		   <input class="autosearch" name="prod_code" id="prod_code" type="text" maxlength="14" style="width: 129px" onchange="getState(this.value)" tabindex="1" ></td>
		   <td></td>
	  	   <td style="width: 126px">&nbsp;</td>
	  	   <td style="width: 13px">&nbsp;</td>
	  	   <td style="width: 239px">
		   &nbsp;</td>
	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td colspan="3"><div id="msgcd" style="width: 630px"></div></td>
	  	  </tr>
		  <tr>
	  	   <td style="height: 27px"></td>
		   <td style="height: 27px; width: 80px;"></td>
		   <td style="height: 27px">&nbsp;</td>
		   <td style="height: 27px">&nbsp;</td>
		    <td style="height: 27px">
		    &nbsp;</td>
		  </tr> 
		  	
	  	  </table>
		 
	
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
					 $locatr = "sew_qc2.php?menucd=".$var_menucode;
					 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				?>
				</td>
			</tr>
			<tr>
				<td style="width: 1150px" colspan="5">
				&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
	  	</table>
	   </form>	
	</fieldset>
	</div>
	<div class="spacer"></div>
</body>

</html>
