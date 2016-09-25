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
      $ticketno = $_GET['ticketno'];
      include("../Setting/ChqAuth.php");

    }
    
    if ($_POST['Submit'] == "Save") {
    
    	$sewtype = htmlentities(mysql_real_escape_string($_POST['sewtypecd']));
		$sorddte = date('Y-m-d', strtotime($_POST['sorddte']));
		$prorevdte= date('Y-m-d', strtotime($_POST['prorevdte']));
		$sexpddte= date('Y-m-d', strtotime($_POST['sexpddte']));
		//echo 'exp :'. $sexpddte= date('Y-m-d', strtotime($_POST['sexpddte']));
		//break;
		
		if ($_POST['qcdte']=NULL || $_POST['qcdte']= '' | $_POST['qcdte']= ' ')
		{
			$qcdte= '';
		}else{
			$qcdte= date('Y-m-d', strtotime($_POST['qcdte']));
		}
		
		
		$buyerord = htmlentities(mysql_real_escape_string($_POST['buyerord']));
		$ticketno= $_POST['ticketno'];
		$batchno= htmlentities(mysql_real_escape_string($_POST['batchno']));
		$areacd= $_POST['areacd'];
		$qcqty= $_POST['qcqty'];
		$grpcd= $_POST['grpcd'];
		$selmaincode= htmlentities(mysql_real_escape_string($_POST['selmaincode'])); // is product code
		$buyerord= htmlentities(mysql_real_escape_string($_POST['prod_code']));
		$productqty= $_POST['productqty'];
		$sbuycd= htmlentities(mysql_real_escape_string($_POST['sbuycd']));
            
		if ($ticketno<> "") {
			
			$var_sql = " SELECT count(*) as cnt from sew_entry";
	      	$var_sql .= " Where ticketno = '$ticketno'";

	      	$query_id = mysql_query($var_sql) or die ("Cant Check Product Costing");
	      	$res_id = mysql_fetch_object($query_id);
             
	      	if ($res_id->cnt > 0 ){
				$backloc = "../prod/sew_entry.php?stat=5&menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
           		echo "</script>";  			
            }else{
            	if ($productqty == ""){ $productqty = 0;}
            	if ($qcqty == ""){ $qcqty = 0;}

            	$vartoday = date("Y-m-d H:i:s");
				$sql = "INSERT INTO sew_entry values 
						('$ticketno','$sewtype', '$buyerord', '$sbuycd', '$sorddte','$prorevdte','$sexpddte',
						'$batchno', '$selmaincode','$productqty','$areacd',
						'$grpcd','$qcdte','$qcqty',
						'$var_loginid', '$vartoday','$var_loginid', '$vartoday' , 'ACTIVE')";
				mysql_query($sql) or die("Error in Sew Entry:".mysql_error(). ' Failed SQL is -->'. $sql);
				
				
				//---------To get details from job rate (pro_jobmodel) and insert into ticket barcode -----//
				
				$sql = "SELECT prod_jobseq, prod_jobid, prod_jobrate ";
				$sql .=" FROM pro_jobmodel x, sew_entry y WHERE y.productcode = x.prod_code ";
				$sql .=" AND x.prod_code = '$selmaincode' ";
				$sql .=" ORDER BY prod_jobseq DESC ";
				
//				echo $sql;
				$barcode = '';
				$query = mysql_query("$sql");
				$results = array();
				while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
			   		$results[] = $row;
			   	}				foreach ($results as $jobrate) 
				{
				   $prod_jobseq = $jobrate['prod_jobseq'];
				   $prod_jobid= $jobrate['prod_jobid'];
				   $prod_jobrate = $jobrate['prod_jobrate'];
				   
				   $barcode = $prod_jobseq.$prod_jobid.$ticketno;
				   
				   $sql = " INSERT INTO sew_barcode values ";
				   $sql.= " ( '$ticketno', '$selmaincode', '$prod_jobseq', '$prod_jobid', ";
				   $sql.= " '$prod_jobrate', '$barcode' )";
				   mysql_query($sql) or die("Error in Sew Ticket Barcode :".mysql_error());
				}
				//break;
				//------------end of get details and insert --------------------------//
								
				//-------------To Update System Number for ticket printing ---//
				$result = mysql_query("select distinct MONTH(CURRENT_DATE) ")or die ("Error 2 : " .mysql_error());
				$row = mysql_fetch_row($result);
				$mth = $row[0];
				
				$result = mysql_query("select distinct YEAR(CURRENT_DATE) ")or die ("Error 3 : " .mysql_error());
				$row = mysql_fetch_row($result);
				$yr = $row[0];
								
				$sql = "UPDATE sew_sys_no SET sysno = sysno+1 WHERE type = '$sewtype' AND month = '$mth' AND yr = '$yr'";
				//echo $sql;
				//break;
		        mysql_query($sql) or die("Error UPDATE into sew_sys_no :".mysql_error());
		        //-------------END OF UPDATE -------------------//
				
    			$backloc = "../prod/m_sew_entry.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 	
			}		
		}else{
			$backloc = "../prod/sew_entry.php?stat=4&menucd=".$var_menucode;
           	echo "<script>";
           	echo 'location.replace("'.$backloc.'")';
            echo "</script>"; 
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
<script type="text/javascript" src="../js/InputMask.js"></script>

<!-- Our jQuery Script to make everything work -->
<script  type="text/javascript" src="jq-ac-script.js"></script>


<script type="text/javascript"> 
$(document).ready(function(){
	var ac_config = {
		source: "autocom_salesord.php",
		select: function(event, ui){
			$("#prod_code").val(ui.item.prod_code);
			$("#sbuycdid").val(ui.item.sbuycd);
			//$("#totallabcid").val(ui.item.prod_labcst);
			$("#sorddteid").val(ui.item.sorddte);
			$("#sexpddteid").val(ui.item.sexpddte);
			
			
		},
		minLength:1
		
	};
	$("#prod_code").autocomplete(ac_config);
});

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();

getState(x);
}

function test()
{
	alert('test alert');
}
function getState(countryId)
{
   var strURL="aja_get_maincode.php?country="+countryId;


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
alert("Sewing Type Must Not Be Blank");
break;	

    var x=document.forms["InpJobFMas"]["sewtypecd"].value;
	if (x==null || x=="")
	{
	alert("Sewing Type Must Not Be Blank");
	document.InpJobFMas.sewtypecd.focus();
	return false;
	}
	
	var x=document.forms["InpJobFMas"]["prod_code"].value;
	
	if (x==null || x=="")
	{
	alert("Buyer Order Must Not Be Blank");
	document.InpJobFMas.prod_code.focus();
	return false;
	}
	

	
	var x=document.forms["InpJobFMas"]["batchno"].value;
	if (x==null || x=="")
	{
	alert("Batch No Must Not Be Blank");
	document.InpJobFMas.batchno.focus();
	return false;
	}

	var x=document.forms["InpJobFMas"]["areacd"].value;
	if (x==null || x=="")
	{
	alert("Area Code Must Not Be Blank");
	document.InpJobFMas.areacd.focus();
	return false;
	}
	
	var x=document.forms["InpJobFMas"]["grpcd"].value;
	if (x==null || x=="")
	{
	alert("Group Code Must Not Be Blank");
	document.InpJobFMas.grpcd.focus();
	return false;
	}

	var x=document.forms["InpJobFMas"]["selmaincode"].value;
	if (x==null || x=="")
	{
	alert("Product Code Must Not Be Blank");
	document.InpJobFMas.selmaincode.focus();
	return false;
	}
alert("asd", x);
break;	
	
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

function getzone(str)
{

	var rand = Math.floor(Math.random() * 101);
	
	if (str=="s")
	  {
	  alert ("Please choose a customer to continue");
	  return;
	  } 
	  
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    {
	    //alert (xmlhttp.responseText);
	    document.getElementById("ticketnoid").value=xmlhttp.responseText;  
	    }
	  }
	xmlhttp.open("GET","get_ticket_no.php?q="+str+"&m="+rand,true);
	xmlhttp.send();
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
	$sql = "select * from sew_entry";
    $sql .= " where ticketno ='$ticketno' ";        
    $sql_result = mysql_query($sql);
    $row = mysql_fetch_array($sql_result);

    $ticketno = $row['ticketno'];
    $buyerorder = $row['buyerorder'];
    $buyerord = $row['buyerord'];
	$ticketno= $row['ticketno'];
	$batchno= $row['batchno'];
	$area= $row['area'];
	$qcqty= $row['qcqty'];
	$grpcd= $row['grpcd'];
	$productqty= $row['productqty'];
	$buyer= $row['buyer'];
	$sewtype = $row['sewtype'];
	$qcqty = $row['productqty'];


	$productcode = $row['productcode'];
    $sorddte = date('d-m-Y', strtotime($row['sorddte']));
    $sexpddte= date('d-m-Y', strtotime($row['sexpddte']));
    $prorevdte= date('d-m-Y', strtotime($row['prorevdte']));
    $qcdte= date('d-m-Y', strtotime($row['qcdte']));
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

<body onload="setup()">
 <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>--> 
  <div class ="contentc">

	<fieldset name="Group1" style="width: 1150px;" class="style2">
	 <legend class="title">UPDATE SEWING TICKET ENTRY - <?php $ticketno = $_GET['ticketno']; echo $ticketno; ?></legend>
	  <br>	 
	  
	  <form name="InpJobFMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 886px">
	   	  <tr>
	  	   <td></td>
	  	   <td style="width: 126px">Sew Type</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
			<input class="textnoentry1" name="sewtype" id="sewtypeid" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $sewtype; ?>"></td>
		   <td></td>
	  	   <td style="width: 126px">Buyer Order </td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
		   <input class="autosearch" name="prod_code" id="prod_code" type="text" maxlength="15" style="width: 129px" onblur="getState(this.value)" tabindex="1" value="<?php  echo $buyerorder; ?>" >
		   </td>
	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td style="width: 126px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 239px"><div id="msgcd"></div></td>
	  	  </tr>
		  <tr>
	  	   <td></td>
	  	   <td style="width: 126px">Ticket No.</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
			<input class="textnoentry1" name="ticketno" id="sewtypeid0" type="text" style="width: 200px;" readonly="readonly" value="<?php echo  $ticketno; ?>"></td>
		   <td></td>
		   <td>Order Date</td>
		   <td>:</td>
		    <td>
		   <input class="inputtxt" name="sorddte" id ="sorddteid" type="text" style="width: 128px;" tabindex="4" readonly="readonly" value="<?php  echo $sorddte; ?>" >
		   </td>
		  </tr> 
		  <tr>
	  	   <td></td>
	  	   <td style="width: 126px">Production Date</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px"><div id="msgcd0">
		   <input class="inputtxt" name="prorevdte" id ="prorevdte" type="text" style="width: 128px;" value="<?php  echo date("d-m-Y"); ?>" tabindex="4" >
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('prorevdte','ddMMyyyy')" style="cursor:pointer"></div></td>
		   <td></td>
		   <td style="width: 136px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td>
		   &nbsp;</td>
	  	  </tr>
		  <tr>
	  	   <td></td>
	  	   <td style="width: 126px">Batch No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
		   <input name="batchno" id ="batchnoid" type="text" style="width: 156px; text-align:center;" tabindex="2" value="<?php echo  $batchno; ?>" ></td>
		   <td></td>
		   <td>Delivery Date</td>
		   <td>:</td>
		    <td>
		   <input class="inputtxt" name="sexpddte" id ="sexpddteid" type="text" style="width: 128px;" tabindex="4" readonly="readonly" value="<?php  echo $sexpddte; ?>" >
		   </td>
	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td style="width: 126px; height: 26px;">Area</td>
	  	   <td style="width: 13px; height: 26px;">:</td>
	  	   <td style="width: 239px; height: 26px;">
		   	<select name="areacd" id="areacdid" style="width: 268px" >
			 <?php
              $sql = "select area_code, area_desc from area_master ORDER BY area_desc ASC ";
              $sql_result = mysql_query($sql);
              echo "<option size =30 selected>".$area."</option>";
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['area_code'].'"';;
		        if ($vmcustcd == $row['area_code']) { echo "selected"; }
     	        echo '>'.$row['area_code']." | ".$row['area_desc'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select></td>
		   <td style="height: 26px"></td>
		   <td>QC Date</td>
		   <td>:</td>
		    <td>
		    <input class="inputtxt" name="qcdte" id ="qcdteid" type="text" style="width: 128px;" tabindex="4" value="<?php  echo $qcdte; ?>" >
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('prorevdte','ddMMyyyy')" style="cursor:pointer"></td>
	  	  </tr>
	  	    <tr>
	  	   <td></td>
	  	   <td style="width: 126px; height: 26px;">Group Code</td>
	  	   <td style="width: 13px; height: 26px;">:</td>
	  	   <td style="width: 239px; height: 26px;">
		   	<select name="grpcd" id="grpcdid" style="width: 268px" >
			 <?php
              $sql = "select grpcd, grpde from wor_grpmas ORDER BY grpde ASC ";
              $sql_result = mysql_query($sql);
              echo "<option size =30 selected>".$grpcd."</option>";
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['grpcd'].'"';;
        if ($vmcustcd == $row['grpcd']) { echo "selected"; }
        echo '>'.$row['grpcd']." | ".$row['grpde'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select></td>
		   <td></td>
		   <td>QC Qty</td>
		   <td>:</td>
		    <td>
		   <input class="inputtxt" name="qcqty" id ="qcqtyid" type="text" style="width: 128px;" tabindex="4" value="<?php  echo $qcqty; ?>" >
		   </td>
	  	    </tr>
			<tr>
	  	   <td></td>
	  	   <td>Product Code</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
			 <p id="statedivx" style="width: 249px;"></p>
				</td>
		   <td></td>
		   <td style="width: 136px"></td>
		   <td></td>
		   <td></td>
	  	    </tr>
			<tr>
	  	   <td></td>
		   <td>Product Qty</td>
		   <td>:</td>
		    <td>
		   <input class="inputtxt" name="productqty" id ="productqtyid" type="text" style="width: 128px;" tabindex="4" value="<?php echo  $productqty; ?>" >
		   </td>
		   <td></td>
		   <td style="width: 136px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td>
		   &nbsp;</td>
	  	    </tr>
			<tr>
	  	   <td></td>
	  	   <td style="width: 126px; height: 26px;">Buyer</td>
	  	   <td style="width: 13px; height: 26px;">:</td>
	  	   <td style="width: 239px; height: 26px;">
		   <input name="sbuycd" id ="sbuycdid" type="text" style="width: 156px; text-align:center;" tabindex="2" readonly="readonly" value="<?php echo  $buyer; ?>" ></td>
		   <td></td>
		   <td style="width: 136px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td>
		   &nbsp;</td>
	  	    </tr>
	
	  	  </table>
		 
		  <br><br>
		  <div style="display:none">
		  <table id="itemsTable" class="general-table" visible="true">
          	<thead>
          	 <tr>
              <th class="tabheader">Raw Material Item</th>
             </tr>
            </thead>
            <tbody>
             <tr class="item-row">
                <td>
				<input name="procomat[]" value="" tProItem1=1 id="procomat1" tabindex="0" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)" onblur="get_desc(this.value, '1')"></td>
             </tr>
            </tbody>
           </table>
           </div>
           
         &nbsp;

	
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_sew_entry.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
					include("../Setting/btnsave.php");
				?>
				</td>
			</tr>
			<tr>
				<td style="width: 1150px" colspan="5">
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
							echo("<span>Duplicated Found Or Code Number Fall In Same Range</span>");
							break;
						case 4:
							echo("<span>Please Fill In The Data To Save</span>");
							break;
						case 5:
							echo("<span>This Product Code Has A Record</span>");
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
				<td>&nbsp;</td>
			</tr>
	  	</table>
	   </form>	
	</fieldset>
	</div>
	<div class="spacer"></div>
</body>

</html>
