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
		$vmrevdte = date('Y-m-d', strtotime($_POST['prorevdte']));
		$vmprodcd = $_POST['prod_code'];
		$vmmatcst = $_POST['totalmatc'];
		$vmlabcst = $_POST['totallabc'];
		$vmovecst = $_POST['totaloveh'];
		$vmmixcst = $_POST['totalmix'];
		$vmtotcst = $_POST['totalcos'];
		$vmextamt = $_POST['totalexft'];
		$vmsalper = $_POST['totaltaxc'];
		$vmsalamt = $_POST['totalsalt'];
		$vmtotamt = $_POST['totalamt'];		
		$sewtype = $_POST['seltype'];
            
		if ($vmprodcd <> "") {
			
			$var_sql = " SELECT count(*) as cnt from prod_matmain";
	      	$var_sql .= " Where prod_code = '$vmprodcd'";

	      	$query_id = mysql_query($var_sql) or die ("Cant Check Product Costing");
	      	$res_id = mysql_fetch_object($query_id);
             
	      	if ($res_id->cnt > 0 ){
				$backloc = "../bom_tran/pro_cost.php?stat=5&menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
           		echo "</script>";  			
            }else{
            	if ($vmmatcst == ""){ $vmmatcst = 0;}
            	if ($vmlabcst == ""){ $vmlabcst = 0;}
            	if ($vmovecst == ""){ $vmovecst = 0;}
            	if ($vmmixcst == ""){ $vmmixcst = 0;}
            	if ($vmtotcst == ""){ $vmtotcst = 0;}
            	if ($vmextamt == ""){ $vmextamt = 0;}
            	if ($vmsalper == ""){ $vmsalper = 0;}
            	if ($vmsalamt == ""){ $vmsalamt = 0;}
            	if ($vmtotamt == ""){ $vmtotamt = 0;}
            	$vartoday = date("Y-m-d H:i:s");
				$sql = "INSERT INTO prod_matmain values 
						('$vmprodcd', '$vmrevdte','$vmmatcst','$vmlabcst','$vmextamt','$vmovecst','$vmmixcst ','$vmsalper', 
						 '$vmsalamt','$vmtotcst','$vmtotamt','$var_loginid', '$vartoday','$var_loginid', '$vartoday')";
				mysql_query($sql) or die("Query 1 prod_mat:".mysql_error());
				
				if(!empty($_POST['procomat']) && is_array($_POST['procomat'])) 
				{	
					foreach($_POST['procomat'] as $row=>$matcd ) {
						$matcode    = $matcd;
						$matseqno   = $_POST['seqno'][$row];
						$matdesc    = mysql_real_escape_string($_POST['procodesc'][$row]);
						$matuom     = $_POST['procouom'][$row];
						$matcoucost = $_POST['procoucost'][$row];
						$matcocompt = $_POST['prococompt'][$row];
						$matcocost  = $_POST['prococost'][$row];
						$matmark    = $_POST['procomark'][$row];
						$matspre    = $_POST['procospre'][$row];
						$matcut     = $_POST['prococut'][$row];
						$matbund    = $_POST['procobund'][$row];
					
						if ($matcode <> "")
						{
							if ($matcoucost == "" or empty($matcoucost)){ $matcoucost = 0;}
							if ($matcocompt == "" or empty($matcocompt)){ $matcocompt = 0;}
							$matcocost = $matcoucost * $matcocompt;
							if ($matcocost == "" or empty($matcocost)){ $matcocost =0;}
							if ($matmark == ""){ $matmark = 0;}
							if ($matspre == ""){ $matspre = 0;}
							if ($matcut == ""){ $matcut = 0;}
							if ($matbund == ""){ $matbund = 0;}
							$sql = "INSERT INTO prod_matlis values 
						    		('$vmprodcd', '$matcode', '$matdesc', '$matuom','$matcoucost','$matcocompt','$matmark','$matspre',
						    		 '$matcut','$matbund', '', '$matseqno','$matcocost')";
							mysql_query($sql) or die("Query 2 :".mysql_error());
           				}	
					}
				}
				
				$backloc = "../bom_tran/m_pro_cost.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 	
			}		
		}else{
			$backloc = "../bom_tran/pro_cost.php?stat=4&menucd=".$var_menucode;
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

    var x=document.forms["InpJobFMas"]["prorevdte"].value;
	if (x==null || x=="")
	{
	alert("Date Must Not Be Blank");
	document.InpJobFMas.prorevdte.focus();
	return false;
	}

	var x=document.forms["InpJobFMas"]["prod_code"].value;
	if (x==null || x=="")
	{
	alert("Product Code Must Not Be Blank");
	document.InpJobFMas.prod_code.focus();
	return false;
	}
	
	//Check the product Code Valid--------------------------------------------------------
	var flgchk = 1;
	var strURL="../bom_master/aja_chk_procode.php?procode="+x;
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
					   document.InpJobFMas.prod_code.focus();
					  alert ('This Product Code Not Found :'+x);
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
    if (flgchk == 0){
	   return false;
	}
	//---------------------------------------------------------------------------------------------------
	
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
    	  // alert('Please Enter a number for Ex-Factory :' + exftrecst);
    	  // document.InpJobFMas.totalexft0.focus();
    	  // return false;
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
	xmlhttp.open("GET","getzone.php?q="+str+"&m="+rand,true);
	xmlhttp.send();
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
	getState(x);

	req.open("GET", strURL, true);
	req.send(null);
	 //------------------Get Description & Labour Rate----------------- 
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
 
<body onload="setup()">
 <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>--> 
  <div class ="contentc">

	<fieldset name="Group1" style="width: 1150px;" class="style2">
	 <legend class="title">CUTTING TICKET ENTRY</legend>
	  <br>	 
	  
	  <form name="InpJobFMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 886px">
	   	  <tr>
	  	   <td></td>
	  	   <td style="width: 126px">Sew Type</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
		   	<select name="sewtypecd" id="sewtypecdid" style="width: 268px" onchange="getzone(this.value)">
			 <?php
              $sql = "select sew_type_code, type_desc from protype_master ORDER BY type_desc ASC ";
              $sql_result = mysql_query($sql);
              echo "<option size =30 selected></option>";
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['sew_type_code'].'"';;
        if ($vmcustcd == $row['sew_type_code']) { echo "selected"; }
        echo '>'.$row['sew_type_code']." | ".$row['type_desc'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select></td>
		   <td></td>
		   <td>PO. No.</td>
		   <td>:</td>
		    <td>
		   <input class="autosearch" name="prod_code" id="prod_code" type="text" maxlength="15" style="width: 129px" onchange ="upperCase(this.id)" tabindex="1" onblur="get_labrate(this.value)"></td>
	  	  </tr>
	  	    <tr>
	  	   <td></td>
	  	   <td style="width: 126px">Ticket No.</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
		   <input name="ticketno" id ="ticketnoid" type="text" style="width: 156px; text-align:center;" tabindex="2" ></td>
		   <td></td>
		   <td>Order Date</td>
		   <td>:</td>
		    <td>
		   <input class="inputtxt" name="sorddte" id ="sorddteid" type="text" style="width: 128px;" tabindex="4" readonly="readonly" >
		   </td>
	  	    </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td style="width: 126px">Production Date</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px"><div id="msgcd">
		   <input class="inputtxt" name="prorevdte" id ="prorevdte" type="text" style="width: 128px;" value="<?php  echo date("d-m-Y"); ?>" tabindex="4" >
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('prorevdte','ddMMyyyy')" style="cursor:pointer"></div></td>
	  	  </tr>
		  <tr>
	  	   <td></td>
	  	   <td style="width: 126px">Batch No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
		   <input name="batchno" id ="batchnoid" type="text" style="width: 156px; text-align:center;" tabindex="2" onblur="showlabDecimal(this.value)"></td>
		   <td></td>
		   <td>Delivery Date</td>
		   <td>:</td>
		    <td>
		   <input class="inputtxt" name="sexpddte" id ="sexpddteid" type="text" style="width: 128px;" tabindex="4" readonly="readonly" >
		   </td>
		  </tr> 
		  <tr>
	  	   <td style="height: 26px"></td>
	  	   <td style="width: 126px; height: 26px;">Area</td>
	  	   <td style="width: 13px; height: 26px;">:</td>
	  	   <td style="width: 239px; height: 26px;">
		   	<select name="areacd" id="areacdid" style="width: 268px" >
			 <?php
              $sql = "select area_code, area_desc from area_master ORDER BY area_desc ASC ";
              $sql_result = mysql_query($sql);
              echo "<option size =30 selected></option>";
                       
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
		    <input class="inputtxt" name="qcdte" id ="qcdteid" type="text" style="width: 128px;" tabindex="4" >
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
              echo "<option size =30 selected></option>";
                       
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
		   <input class="inputtxt" name="qcqty" id ="qcqtyid" type="text" style="width: 128px;" tabindex="4" >
		   </td>
	  	    </tr>
			<tr>
	  	   <td></td>
	  	   <td>&nbsp;</td>
	  	   <td style="width: 13px"><br></td>
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
	  	   <td>&nbsp;</td>
	  	   <td style="width: 13px">&nbsp;</td>
	  	   <td style="width: 239px">
		   &nbsp;</td>
		   <td></td>
		   <td style="width: 136px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td>
		   &nbsp;</td>
	  	    </tr>
		  <tr>
	  	   <td></td>
	  	   <td>&nbsp;</td>
	  	   <td style="width: 13px">&nbsp;</td>
	  	   <td style="width: 239px">
		   &nbsp;</td>
		   <td></td>
		   <td style="width: 136px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td>
		   &nbsp;</td>
	  	  </tr>
	  	   <tr>
	  	   <td style="height: 26px"></td>
	  	   <td style="width: 126px; height: 26px;">Product Code</td>
	  	   <td style="width: 13px; height: 26px;">&nbsp;</td>
	  	   <td style="width: 239px; height: 26px;">
		   	&nbsp;</td>
		   <td></td>
		   <td style="width: 136px"></td>
		   <td></td>
		   <td></td>
	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td style="width: 126px; height: 26px;">Buyer</td>
	  	   <td style="width: 13px; height: 26px;">:</td>
	  	   <td style="width: 239px; height: 26px;">
		   <input name="sbuycd" id ="sbuycdid" type="text" style="width: 156px; text-align:center;" tabindex="2" readonly="readonly" ></td>
		   <td></td>
		   <td style="width: 136px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td>
		   &nbsp;</td>
	  	  </tr>
	
	  	  </table>
		 
		  <br><br>
		  &nbsp;

	
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_pro_cost.php?menucd=".$var_menucode;
			
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
