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
		$vmordno = mysql_real_escape_string($_POST['saordno']);
		$vmordnobuyer  = mysql_real_escape_string($_POST['saordnobuyer']);
		$vmexpdte = date('Y-m-d', strtotime($_POST['saexpddte']));
		$vmorddte = date('Y-m-d', strtotime($_POST['saorddte']));
		$vmcutsno = $_POST['sacutsno'];
		$vmbuycd  = $_POST['sabuycd'];
		$vmbuytyp  = $_POST['sabuytyp'];
		$vmremark = mysql_real_escape_string($_POST['saremark']);
		$vmremark2 = mysql_real_escape_string($_POST['saremark2']);
		$vmremark3 = mysql_real_escape_string($_POST['saremark3']);
		$vmremark4 = mysql_real_escape_string($_POST['saremark4']);
		$vmtotqty = $_POST['totqty'];
		$vmtotamt = $_POST['totamt'];
            
		if ($vmordnobuyer <> "") {
		
		  /*----------------------------- TO get auto number------------------------------------ */
	/*	BLOCK - requested by Costing - 28 Feb 2014 CEDRIC  
	//User need to segregate into different buyer order if have more than 1 costing.  
    //When MDF/FNL/Export dept. placed PO, NO MORE auto insert into buyer sales order module. 
    //NLG user need to key in buyer order once received PO from MDF/FNL/Export dept. 
	
			$sysno = '';
     		$sqlchk = " select sysno from system_number ";
     		$sqlchk.= " where type = '$vmbuytyp'";
     
     		$dumsysno= mysql_query($sqlchk) or die(mysql_error());
     		while($row = mysql_fetch_array($dumsysno))
     		{
     			$sysno = $row['sysno'];        
     		}
     		if ($sysno ==NULL)
     		{
     			$sysno = '0';
     					$sysno_sql = "INSERT INTO system_number values ('$vmbuytyp', '$sysno')";

     			mysql_query($sysno_sql);

     		}
     		$newsysno = $sysno + 1;
     		
     		$adj_sysno  = str_pad($newsysno , 5, '0', STR_PAD_LEFT);
     		$vmordno = $vmbuytyp. "-".$adj_sysno;
     		*/
            /*--------------------------- end get auto number  ---------------------------------- */

			
			$var_sql = " SELECT count(*) as cnt from salesentry";
	      	$var_sql .= " Where sordno = '$vmordno' And sbuycd = '$vmbuycd'";
	      	$query_id = mysql_query($var_sql) or die ("Cant Check Sales Entry Order No");
	      	$res_id = mysql_fetch_object($query_id);
             
	      	if ($res_id->cnt > 0 ){
				$backloc = "../sales_tran/sale_form.php?stat=5&menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
           		echo "</script>";  			
            }else{
            	if ($vmtotqty == ""){ $vmtotqty = 0;}
            	if ($vmtotamt == ""){ $vmtotamt = 0;}
            	
            	$vartoday = date("Y-m-d H:i:s");
				$sql = "INSERT INTO salesentry values 
						('$vmordno', '$vmordnobuyer', '$vmorddte', '$vmexpdte','$vmbuycd', '$vmbuytyp', '$vmremark','$vmremark2','$vmremark3','$vmremark4','$var_loginid','$vartoday', 
						 '$var_loginid','$vartoday','ACTIVE','$vmtotqty', '$vmtotamt')"; 
				//mysql_query($sql) or die(mysql_error());
				mysql_query($sql) or die("Error insert Sales Entry:".mysql_error(). ' Failed SQL is --> '. $sql);

				
				if(!empty($_POST['procd']) && is_array($_POST['procd'])) 
				{	
					foreach($_POST['procd'] as $row=>$procd ) {
						$procode  = $procd;
						$proseqno = $_POST['seqno'][$row];
						$prodesc  = $_POST['procdname'][$row];
						$proqty   = $_POST['proorqty'][$row];
						$prouom   = $_POST['prouom'][$row];
						$proupri  = $_POST['prooupri'][$row];
						$protamt  = $_POST['proouamt'][$row];
						$procdbuyer  = $_POST['procdbuyer'][$row];
						
											
						if ($procode <> "" or $procdbuyer <> "")
						{
							if ($proqty == ""){ $proqty= 0;}
							if ($proupri == ""){ $proupri = 0;}
							if ($protamt == ""){ $protamt = 0;}
							
							$sql = "INSERT INTO salesentrydet values 
						    		('$vmordno', '$vmbuycd', '$procode', '$procdbuyer', '$prodesc','$proqty','$prouom','$proupri','$protamt', '$proseqno')";
							//mysql_query($sql) or die(mysql_error());
							mysql_query($sql) or die("Error in Sales Entry Det:".mysql_error(). ' Failed SQL is --> '. $sql);
           				}	
					}
				}
				
				//$updsysno_sql = "UPDATE system_number SET sysno = '$newsysno' WHERE type = '$vmbuytyp'";

		     	//mysql_query($updsysno_sql) or die("Error Update Sys Number :".mysql_error(). ' Failed SQL is --> '. $updsysno_sql);

				
				$backloc = "../sales_tran/m_sale_form.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 	
			}		
		}else{
			$backloc = "../sales_tran/sale_form.php?stat=4&menucd=".$var_menucode;
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
<script  type="text/javascript" src="sale_procd.js"></script>


<script type="text/javascript">
function setup() {

		document.InpSalesF.saordno.focus();
				
 		//Set up the date parsers
        var dateParser = new DateParser("dd-MM-yyyy");
      
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("dd-MM-yyyy", "saexpddte");
		dateMask1.validationMessage = errorMessage;
		
		var dateMask2 = new DateMask("dd-MM-yyyy", "saorddte");
		dateMask2.validationMessage = errorMessage;  
} 

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}


function calcAmt(vid)
{
    var vproqty = "proordqty"+vid;
    var vproupri = "prooupri"+vid;
    var vproamt = "proouamt"+vid;
	
    var col1 = document.getElementById(vproqty).value;
	var col2 = document.getElementById(vproupri).value;		
	var totsumamt = 0;
	
	if (col1 != ""){
		if(isNaN(col1)) {
    	   alert('Please Enter a valid number for Quantity :' + col1);
    	   col1 = 0;
    	}
    	document.getElementById(vproqty).value = parseFloat(col1).toFixed(0);
    }
    if (col2 != ""){	
		if(isNaN(col2)) {
    	   alert('Please Enter a valid number for Unit Price :' + col2);
    	   col2 = 0;
    	}
    	document.getElementById(vproupri).value = parseFloat(col2).toFixed(4);
    }	
	
	if ((!isNaN(col1) && (col1 != "")) && (!isNaN(col2) && (col2 != ""))){
		totsumamt = parseFloat(col1) * parseFloat(col2);
		document.getElementById(vproamt).value = parseFloat(totsumamt).toFixed(2);		
     }	
     caltotqty();
	 caltotamt();
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

    var x=document.forms["InpSalesF"]["saordnoid"].value;
	if (x==null || x=="")
	{
		alert("Buyer Order No Cannot Be Blank");
		document.InpSalesF.saordnoid.focus();
		return false;
	}

    var x=document.forms["InpSalesF"]["saexpddte"].value;
	if (x==null || x=="")
	{
		alert("Exp Delivery Date Must Not Be Blank");
		document.InpSalesF.saexpddte.focus();
		return false;
	}
	
	var x=document.forms["InpSalesF"]["sabuycd"].value;
	if (x==null || x=="")
	{
		alert("Buyer Code Must Not Be Blank");
		document.InpSalesF.sabuycd.focus();
		return false;
	}

	
	var x=document.forms["InpSalesF"]["sabuytyp"].value;
	if (x==null || x=="")
	{
		alert("Sales Type Must Not Be Blank");
		document.InpSalesF.sabuytyp.focus();
		return false;
	}

	
	

	var x=document.forms["InpSalesF"]["saorddte"].value;
	if (x==null || x=="")
	{
		alert("Order Date Must Not Be Blank");
		document.InpSalesF.saorddte.focus();
		return false;
	}
	
	
	var x=document.forms["InpSalesF"]["saorddte"].value;
	var y=document.forms["InpSalesF"]["saexpddte"].value;
	if (y < x)
	{
		//alert("Exp Delivery Date Must Larger Or Equal To Sales Order Date");
		//document.InpSalesF.saorddte.focus();
		//return false;
	}

	
	//Check the sales order number Valid--------------------------------------------------------
	var flgchk = 1;
	var x = document.forms["InpSalesF"]["saordnoid"].value;
	var y = document.forms["InpSalesF"]["sabuycd"].value;
	var strURL="aja_chk_ordernocnt.php?sordno="+x+"&buyercd="+y;
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
					  document.InpSalesF.saordnoid.focus;
					  alert ('This Buyer Purchase Order No Is Use For This Buyer Code :'+x);
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
	
	//Check the list of mat item no is Valid-------------------------------------------------------------
	var flgchk = 1;	
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
         
    for (var j = 1; j < rowCount; j++){
       var idrowItem = "procd"+j;
       var rowItem = document.getElementById(idrowItem).value;	 
              
       if (rowItem != ""){
       	var strURL="aja_chk_procdCount.php?procd="+rowItem;
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
						   alert ('Invalid Raw Mat Item Product Code : '+ rowItem + ' At Row '+j);
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

	    var idrowItem = "procd"+j;
        var rowItem = document.getElementById(idrowItem).value;	 
        if (rowItem != ""){ 
        	mylist.push(rowItem);   
	    }		
    }		
	
	mylist.sort();
	var last = mylist[0];
	
	for (var i=1; i < mylist.length; i++) {
		if (mylist[i] == last){ 
			alert ("Duplicate Product Code Found; " + last);
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
	caltotqty();
	caltotamt();

}

function caltotamt(){
    var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length; 
		
	var totmat = 0;
		
	for(var i = 1; i < rowCount; i++) { 
	  var vprouamt = "proouamt"+i;
	  var colamt = document.getElementById(vprouamt).value;					
		
	  if (!isNaN(colamt) && (colamt != "")){
				totmat = parseFloat(totmat) + parseFloat(colamt);		
	  }
	}
	document.InpSalesF.totamtid.value = parseFloat(totmat).toFixed(2);	     
}

function caltotqty(){
    var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length; 
		
	var totqty = 0;
		
	for(var i = 1; i < rowCount; i++) { 
	  var vprouqty = "proordqty"+i;
	  var colqty = document.getElementById(vprouqty).value;					
		
	  if (!isNaN(colqty) && (colqty != "")){
				totqty = parseFloat(totqty ) + parseFloat(colqty);		
	  }
	}
	document.InpSalesF.totqtyid.value = parseFloat(totqty).toFixed(0);	     
}


function AjaxOrdNo(buyerc)
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
	var orderno = document.InpSalesF.saordnoid.value;
	var url="aja_chk_orderno.php";
	
	url=url+"?sordno="+orderno+"&buyercd="+buyerc;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",encodeURI(url),true);
	httpxml.send(null);
}


function chksalesord(buyerc)
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
			document.getElementById("msgcd1").innerHTML=httpxml.responseText;
		}
	}

	var ordernos = document.InpSalesF.saordno.value;
	var orderno = document.InpSalesF.saordnoid.value;
	var url="aja_chk_saleord.php";
	

	url=url+"?sordno="+ordernos;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	//alert(url);
	httpxml.open("GET",encodeURI(url),true);
	httpxml.send(null);
}


function get_desc(prodcode, vid)
{
    var idproname = "proconame"+vid;
    var idprouom  = "prouom"+vid;
    var idbuycd = "procdbuyer"+vid;
	var strURL="aja_pro_desc.php?procode="+prodcode;
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
						document.getElementById(idproname).value = obj.desc;
						document.getElementById(idprouom).value = obj.uom;
						document.getElementById(idbuycd).value = obj.procdbuy;
					}else{
						document.getElementById(idproname).value = "";
						document.getElementById(idprouom).value = "";
						document.getElementById(idbuycd).value = "";
					}	
				} 
			}
		}	 
	}
	req.open("GET", strURL, true);
	req.send(null);

}

</script>
</head>
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc">

	<fieldset name="Group1" style=" width: 973px;" class="style2">
	 <legend class="title">BUYER PURCHASE ORDER ENTRY</legend>
	  <br>	 
	  
	  <form name="InpSalesF" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()" style="width: 1200px">
	   
		<table style="width: 993px; height: 220px;">
	   	   <tr>
	   	    <td style="width: 13px"></td>
	  	    <td style="width: 122px">Order No</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 201px">
			<input class="inputtxt" name="saordno" id="saordno" type="text" maxlength="30" style="width: 204px;" onchange="chksalesord(this.value);">
			</td>
			<td style="width: 10px"></td>
			<td style="width: 204px">&nbsp;</td>
			<td style="width: 16px">&nbsp;</td>
			<td style="width: 284px">
		    &nbsp;</td>
	  	  </tr>  
	  	  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 201px"><div id="msgcd1" style="width: 272px"></div></td>
	  	  </tr>

	   	   <tr>
	   	    <td style="width: 13px"></td>
	  	    <td style="width: 122px">Buyer Order No</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 201px">
			<input class="inputtxt" name="saordnobuyer" id="saordnoid" type="text" maxlength="30" style="width: 204px;" onchange ="upperCase(this.id)">
			</td>
			<td style="width: 10px"></td>
			<td style="width: 204px">Expected Delivery Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		   <input class="inputtxt" name="saexpddte" id ="saexpddte" type="text" style="width: 128px;" value="<?php  echo date("d-m-Y"); ?>">
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('saexpddte','ddMMyyyy')" style="cursor:pointer">
		   </td>
	  	  </tr>  
	  	  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 201px"><div id="msgcd" style="width: 271px"></div></td>
	  	  </tr>
	  	  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Buyer </td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
		   	<select name="sabuycd" id="sabuycd" style="width: 268px" >
			 <?php
              $sql = "select customer_code, customer_desc from customer_master ORDER BY customer_code ASC";
              $sql_result = mysql_query($sql);
              echo "<option size =30 selected></option>";
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['customer_code'].'">'.$row['customer_code']." | ".$row['customer_desc'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>

		   </td>
		   <td></td>
	  	   <td style="width: 122px">Sales Type</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
		   	<select name="sabuytyp" id="sabuytyp" style="width: 268px" onchange="AjaxOrdNo(this.value);">
			 <?php
              $sql = "select pro_buy_code, pro_buy_desc from pro_buy_master ORDER BY pro_buy_code ASC";
              $sql_result = mysql_query($sql);
              echo "<option size =30 selected></option>";
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['pro_buy_code'].'">'.$row['pro_buy_code']." | ".$row['pro_buy_desc'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>

		   </td>
	  	  </tr>
	  	   <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px"></td>
	  	   <td style="width: 13px"></td>
	  	  </tr>
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Order Date</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
		   <input class="inputtxt" name="saorddte" id ="saorddte" type="text" style="width: 128px;" value="<?php  echo date("d-m-Y"); ?>">
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('saorddte','ddMMyyyy')" style="cursor:pointer"></td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td style="width: 284px">
		   &nbsp;</td>
		  </tr> 
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">&nbsp;</td>
	  	   <td style="width: 13px">&nbsp;</td>
	  	   <td style="width: 201px">
		   &nbsp;</td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td style="width: 284px">
		   &nbsp;</td>
	  	  </tr>
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Remark</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="saremark" id="sasewgrpid" type="text" maxlength="60" style="width: 263px;" onchange ="upperCase(this.id)"></td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">Total Quantity</td>
		   <td>:</td>
		   <td style="width: 284px">
		   <input readonly="readonly" name="totqty" id ="totqtyid" type="text" style="width: 156px;" class="textnoentry1"></td>
	  	  </tr>
	  	    <tr>
	  	   <td style="width: 13px; height: 22px;"></td>
	  	   <td style="width: 122px; height: 22px;">&nbsp;</td>
	  	   <td style="width: 13px; height: 22px;">&nbsp;</td>
	  	   <td style="width: 201px; height: 22px;">
			<input class="inputtxt" name="saremark2" id="sasewgrpid0" type="text" maxlength="60" style="width: 263px;" onchange ="upperCase(this.id)"></td>
		   <td style="width: 10px; height: 22px;"></td>
		   <td style="width: 204px; height: 22px;">Total Amount</td>
		   <td style="height: 22px">:</td>
		   <td style="width: 284px; height: 22px;">
		   <input readonly="readonly" name="totamt" id ="totamtid" type="text" style="width: 156px;" class="textnoentry1"></td>
	  	    </tr>
			<tr>
	  	   <td style="width: 13px; height: 22px;"></td>
	  	   <td style="width: 122px; height: 22px;">&nbsp;</td>
	  	   <td style="width: 13px; height: 22px;">&nbsp;</td>
	  	   <td style="width: 201px; height: 22px;">
			<input class="inputtxt" name="saremark3" id="sasewgrpid1" type="text" maxlength="60" style="width: 263px;" onchange ="upperCase(this.id)"></td>
		   <td style="width: 10px; height: 22px;"></td>
		   <td style="width: 204px; height: 22px;">&nbsp;</td>
		   <td style="height: 22px">&nbsp;</td>
		   <td style="width: 284px; height: 22px;">
		   &nbsp;</td>
	  	    </tr>
	  	   <tr>
	  	   <td style="width: 13px; height: 22px;"></td>
	  	   <td style="width: 122px; height: 22px;">&nbsp;</td>
	  	   <td style="width: 13px; height: 22px;">&nbsp;</td>
	  	   <td style="width: 201px; height: 22px;">
			<input class="inputtxt" name="saremark4" id="sasewgrpid2" type="text" maxlength="60" style="width: 263px;" onchange ="upperCase(this.id)"></td>
		   <td style="width: 10px; height: 22px;"></td>
		   <td style="width: 204px; height: 22px;">&nbsp;</td>
		   <td style="height: 22px">&nbsp;</td>
		   <td style="width: 284px; height: 22px;">
		   &nbsp;</td>
	  	  </tr>
	  	  
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table" style="width: 958px">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader" style="width: 178px">Product Code</th>
              <th class="tabheader" style="width: 178px">Buyer Article Number</th>
              <th class="tabheader">Description</th>
              <th class="tabheader" style="width: 100px">Quantity</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader" style="width: 137px">Unit <br>Price(RM)</th>
              <th class="tabheader" style="width: 242px">Amount</th>
             </tr>
            </thead>
            <tbody>
             <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="1" readonly="readonly" style="width: 27px; border:0;"></td>
                <td style="width: 178px">
				<input name="procd[]" value="" tProCd1=1 id="procd1" class="autosearch" style="width: 175px" onchange ="upperCase(this.id)" onblur="get_desc(this.value, '1')"></td>
                <td style="width: 178px">
				<input name="procdbuyer[]" value="" tProCd1=1 id="procdbuyer1" class="autosearch" style="width: 175px" onchange ="upperCase(this.id)" onblur="get_desc(this.value, '1')"></td>
                <td>
				<input name="procdname[]" value="" id="proconame1" style="width: 303px;"></td>
                <td style="width: 100px">
				<input name="proorqty[]" id="proordqty1" onBlur="calcAmt('1');" style="width: 97px; text-align:center;">
				</td>
                <td>
				<input name="prouom[]" id="prouom1" style="width: 75px;  ">
				</td>
                <td style="width: 137px">
				<input name="prooupri[]" id="prooupri1" onBlur="calcAmt('1');" style="width: 89px; text-align:right;">
				</td>
				<td style="width: 242px">
				<input name="proouamt[]" id="proouamt1" readonly="readonly" style="width: 116px; border-style: none; border-color: inherit; border-width: 0; text-align:right;">
				</td>
             </tr>
            </tbody>
           </table>
           
         <a href="#" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Item</span></a>
         <a href="javascript:deleteRow('itemsTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>

	
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_sale_form.php?menucd=".$var_menucode;
			
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
						case 4:
							echo("<span>Please Fill In The Sales Order No; Process Fail</span>");
							break;
						case 5:
							echo("<span>This Sales Order No Is Use For This Buyer Code; Process Fail</span>");
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
