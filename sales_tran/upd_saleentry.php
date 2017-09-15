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
      $var_sor = htmlentities($_GET['sorno']);
      $var_buy = $_GET['buycd'];
      $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");
    }
    
    if ($_POST['Submit'] == "Update") {
		$vmordno  = $_POST['saordno'];
		$sordnobuyer = $_POST['sordnobuyer'];
		$vmexpdte = date('Y-m-d', strtotime($_POST['saexpddte']));
		$vmorddte = date('Y-m-d', strtotime($_POST['saorddte']));
	    $vmbuycd  = mysql_real_escape_string($_POST['sabuycd']);
	    $vmbuytyp  = $_POST['sabuytyp'];
		$vmremark = mysql_real_escape_string($_POST['saremark']);
		$vmremark2 = mysql_real_escape_string($_POST['saremark2']);
		$vmremark3 = mysql_real_escape_string($_POST['saremark3']);
		$vmremark4 = mysql_real_escape_string($_POST['saremark4']);
		$vmtotqty = $_POST['totqty'];
		$vmtotamt = $_POST['totamt'];
		$var_menucode  = $_POST['menudcode'];
            
		if ($vmordno <> "") {
			
			if ($vmtotqty == ""){ $vmtotqty = 0;}
            if ($vmtotamt == ""){ $vmtotamt = 0;}
			
			$tq = 0;
			$ta = 0;
			$sql  = " Delete From salesentrydet ";
			$sql .=	" Where sordno ='".$vmordno."' And sbuycd='".$vmbuycd."'";
			mysql_query($sql) or die(mysql_error()." 2");

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
											
						if ($procode <> "" || $procdbuyer <> "" )
						{
							if ($proqty == ""){ $proqty= 0;}
							if ($proupri == ""){ $proupri = 0;}
							if ($protamt == ""){ $protamt = 0;}
							$tq = $tq + $proqty;
							$ta = $ta + $protamt;
							$sql = "INSERT INTO salesentrydet values 
						   		('$vmordno', '$vmbuycd', '$procode', '$procdbuyer', '$prodesc','$proqty','$prouom','$proupri','$protamt', '$proseqno')";
							mysql_query($sql) or die(mysql_error()." 3");

           				}	
					}
				}
			            	
            $vartoday = date("Y-m-d H:i:s");
			$sql  = "Update salesentry Set sorddte = '$vmorddte', sexpddte = '$vmexpdte', ";
			$sql .=	" sordnobuyer = '$sordnobuyer', ";
			$sql .=	" sbuycd = '$vmbuycd', sremark = '$vmremark', sremark2 = '$vmremark2',";
			$sql .=	"		               sremark3 = '$vmremark3', sremark4 = '$vmremark4',";
			$sql .=	"		               modified_by = '$var_loginid', modified_on = '$vartoday', toqty = '$tq', toamt = '$ta' ";
			$sql .=	" Where sordno ='".$vmordno."'";			
			mysql_query($sql) or die(mysql_error()." 1");
			//echo $sql;
				
			$backloc = "../sales_tran/m_sale_form.php?menucd=".$var_menucode;
           	echo "<script>";
           	echo 'location.replace("'.$backloc.'")';
            echo "</script>"; 	
					
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
		alert("Order No Cannot Be Blank");
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

	var x=document.forms["InpSalesF"]["saorddte"].value;
	if (x==null || x=="")
	{
		alert("Order Date Must Not Be Blank");
		document.InpSalesF.saorddte.focus();
		return false;
	}
	
	var x=document.forms["InpSalesF"]["sabuycd"].value;
	if (x==null || x=="")
	{
		alert("Buyer Code Must Not Be Blank");
		document.InpSalesF.sabuycd.focus();
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
/*	var flgchk = 1;
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
				
					if (req.responseText == 0)
					{
					  flgchk = 0;
					  document.InpSalesF.saordnoid.focus;
					  alert ('This Sales Order No Is Use For This Buyer Code :'+x);
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
*/	
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


function get_desc(prodcode, vid)
{
    var idproname = "proconame"+vid;
    var idprouom  = "prouom"+vid;
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
					}else{
						document.getElementById(idproname).value = "";
						document.getElementById(idprouom).value = "";
					}	
				} 
			}
		}	 
	}
	req.open("GET", strURL, true);
	req.send(null);
}

function setup() {

		document.InpSalesF.saexpddte.focus();
						
 		//Set up the date parsers
        var dateParser = new DateParser("dd-MM-yyyy");
      
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("dd-MM-yyyy", "saexpddte");
		dateMask1.validationMessage = errorMessage;
		
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("dd-MM-yyyy", "saorddte");
		dateMask1.validationMessage = errorMessage;			
}

</script>

</head>
  
  <?php
  	 $sql = "select * from salesentry";
     $sql .= " where sordno ='".$var_sor."' And sbuycd ='".$var_buy."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $expddte = date('d-m-Y', strtotime($row['sexpddte']));
     $orddte = date('d-m-Y', strtotime($row['sorddte']));
     $scutsno = $row['scutsno'];
     $sordnobuyer= $row['sordnobuyer'];
     $sbuycd = $row['sbuycd'];
     $salestye = $row['salestype'];
     $sremark = $row['sremark'];
     $sremark2 = $row['sremark2'];
     $sremark3 = $row['sremark3'];
     $sremark4 = $row['sremark4'];
     $tqty = $row['toqty'];
     $tamt = $row['toamt'];  
	 $tqty  = number_format($tqty, 0, '', '');

	 $sql = "select customer_desc from customer_master ";
     $sql .= " where customer_code ='".$sbuycd."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);
     $customer_desc = $row[0];
     
     $sql = "select pro_buy_desc from pro_buy_master ";
     $sql .= " where pro_buy_code ='".$salestye."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);
     $salestyedesc= $row[0];

     		
  ?>
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc">

	<fieldset name="Group1" style=" width: 960px;" class="style2">
	 <legend class="title">BUYER PURCHASE ORDER UPDATE : <?php echo $var_sor; ?></legend>
	  <br>	 
	  
	  <form name="InpSalesF" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	    <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table style="width: 1014px">
	   	   <tr>
	   	    <td style="width: 13px"></td>
	  	    <td style="width: 122px">Order No.</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 201px">
			<input class="textnoentry1" name="saordno" id="saordnoid" readonly="readonly" type="text" style="width: 204px;" value="<?php echo $var_sor; ?>">
			</td>
			<td style="width: 10px"></td>
			<td style="width: 204px">Expected Delivery Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		   <input class="inputtxt" name="saexpddte" id ="saexpddte" type="text" style="width: 128px;" value="<?php  echo $expddte; ?>">
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('saexpddte','ddMMyyyy')" style="cursor:pointer">
		   </td>
	  	  </tr>  
	  	  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 201px"><div id="msgcd"></div></td>
	  	  </tr>
	  	    <tr>
	   	    <td style="width: 13px"></td>
	  	    <td style="width: 122px">Buyer Order No</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 201px">
		   <input class="inputtxt" name="sordnobuyer" id="sordnobuyer" type="text" maxlength="30" style="width: 204px;" value="<?php echo $sordnobuyer; ?>">
			</td>
			<td style="width: 10px"></td>
			<td style="width: 204px">&nbsp;</td>
			<td style="width: 16px">&nbsp;</td>
			<td style="width: 284px">
		    &nbsp;</td>
	  	    </tr>
	  	    <tr>
	   	    <td style="width: 13px"></td>
	  	    <td style="width: 122px">Buyer </td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 201px">
           		<select class= "inputtxt" name="sabuycd"  id = "sabuycd" onchange="showsupplier(this.value)" >
          		

          		 <?php 
                  $sql="select customer_code, customer_desc from customer_master ORDER BY customer_code ASC ";

                  $result = mysql_query($sql);
                  echo "<option value=".$sbuycd .">".$sbuycd ." - ".$customer_desc."</option>";

                  while($row = mysql_fetch_array($result))
                     {
                       echo "<option value= '".$row['customer_code']."'";
                       if ($supplier == $row['customer_code']) { echo "selected"; }
                       echo ">".$row['customer_desc']." - ".$row['customer_code'];
                       echo "</option>";
                     }                  
          		 ?> 
          		 </select>
			</td>
			<td style="width: 10px"></td>
	  	   <td style="width: 122px">Sales Type</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
		   	<input class="textnoentry1" name="saordno0" id="saordnoid0" readonly="readonly" type="text" style="width: 204px;" value="<?php echo $salestye; ?>"></td>
	  	    </tr>
			<tr>
	   	    <td style="width: 13px"></td>
	  	    <td style="width: 122px">&nbsp;</td>
	  	    <td style="width: 13px">&nbsp;</td>
	  	    <td style="width: 201px">
			&nbsp;</td>
			<td style="width: 10px"></td>
			<td style="width: 204px">&nbsp;</td>
			<td style="width: 16px">&nbsp;</td>
			<td style="width: 284px">
		    &nbsp;</td>
	  	    </tr>
	  	  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Order Date</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
		   <input class="inputtxt" name="saorddte" id ="saorddte" type="text" style="width: 128px;" value="<?php  echo $orddte; ?>">
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('saorddte','yyyyMMdd')" style="cursor:pointer"></td>
		   <td></td>
		   <td>&nbsp;</td>
		   <td>&nbsp;</td>
		   <td style="width: 284px">
			&nbsp;</td>
	  	  </tr>
	  	   <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 201px"></td>
	  	  </tr>
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Remark</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="saremark" id="saremarkid" type="text" maxlength="60" style="width: 263px;" onchange ="upperCase(this.id)" value="<?php echo  $sremark; ?>"></td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">Total Quantity</td>
		   <td>:</td>
		   <td style="width: 284px">
		   <input readonly="readonly" name="totqty" id ="totqtyid" type="text" style="width: 156px;" class="textnoentry1" value="<?php echo $tqty; ?>"></td>
		  </tr> 
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">&nbsp;</td>
	  	   <td style="width: 13px">&nbsp;</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="saremark2" id="saremarkid0" type="text" maxlength="60" style="width: 263px;" onchange ="upperCase(this.id)" value="<?php echo  $sremark2; ?>"></td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px; height: 22px;">Total Amount</td>
		   <td style="height: 22px">:</td>
		   <td style="width: 284px; height: 22px;">
		   <input readonly="readonly" name="totamt" id ="totamtid" type="text" style="width: 156px;" class="textnoentry1" value="<?php echo $tamt; ?>"></td>
	  	  </tr>
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">&nbsp;</td>
	  	   <td style="width: 13px">&nbsp;</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="saremark3" id="saremarkid1" type="text" maxlength="60" style="width: 263px;" onchange ="upperCase(this.id)" value="<?php echo  $sremark3; ?>"></td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td style="width: 284px">
		   &nbsp;</td>
	  	  </tr>
	  	   <tr>
	  	   <td style="width: 13px; height: 22px;"></td>
	  	   <td style="width: 122px; height: 22px;">&nbsp;</td>
	  	   <td style="width: 13px; height: 22px;">&nbsp;</td>
	  	   <td style="width: 201px; height: 22px;">
			<input class="inputtxt" name="saremark4" id="saremarkid2" type="text" maxlength="60" style="width: 263px;" onchange ="upperCase(this.id)" value="<?php echo  $sremark4; ?>"></td>
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
              <th class="tabheader" style="width: 178px">Buyer Article</th>
              <th class="tabheader">Description</th>
              <th class="tabheader" style="width: 100px">Quantity</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader" style="width: 137px">Unit <br>Price(RM)</th>
              <th class="tabheader" style="width: 242px">Amount</th>
             </tr>
            </thead>
            <tbody>
             <?php
            	$sql = "SELECT * FROM salesentrydet";
             	$sql .= " where sordno ='".$var_sor."' And sbuycd ='".$var_buy."'";
	    		$sql .= " ORDER BY sproseq";  
				$rs_result = mysql_query($sql); 
			   
			   	$i = 1;
			    while ($rowq = mysql_fetch_assoc($rs_result)){
            		$rowq['sproqty']  = number_format($rowq['sproqty'], 0, '', '');

             		echo '<tr class="item-row">';
                	echo '<td style="width: 30px"><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>';
                	echo '<td style="width: 178px"><input name="procd[]" value="'.$rowq['sprocd'].'" tProCd1='.$i.' id="procd'.$i.'" class="autosearch" style="width: 175px" onchange ="upperCase(this.id)" onblur="get_desc(this.value, '.$i.')"></td>';
                	echo '<td style="width: 178px"><input name="procdbuyer[]" value="'.$rowq['sprocdbuyer'].'" tProCd1='.$i.' id="procdbuyer'.$i.'" class="autosearch" style="width: 175px" onchange ="upperCase(this.id)" ></td>';
                	echo '<td><input name="procdname[]" value="'.$rowq['sprodesc'].'" id="proconame'.$i.'" style="width: 303px;"></td>';
                    echo '<td style="width: 100px"><input name="proorqty[]" value="'.$rowq['sproqty'].'" id="proordqty'.$i.'" onBlur="calcAmt('.$i.');" style="width: 97px; text-align:center;"></td>';
                	echo '<td><input name="prouom[]" id="prouom'.$i.'" value="'.$rowq['sprouom'].'"  onchange ="upperCase(this.id)" style="width: 75px"></td>';
                	echo '<td style="width: 137px"><input name="prooupri[]" id="prooupri'.$i.'" value="'.$rowq['sprounipri'].'" onBlur="calcAmt('.$i.');" style="width: 89px; text-align:right;"></td>';
					echo '<td style="width: 242px"><input name="proouamt[]" id="proouamt'.$i.'" value="'.$rowq['sproamt'].'" readonly="readonly" style="width: 116px; border-style: none; border-color: inherit; border-width: 0; text-align:right;"></td>';
             		echo '</tr>';
                    $i = $i + 1;
                }
               
                if ($i == 1){
                	$rowq['sproqty']  = number_format($rowq['sproqty'], 0, '', '');

                	echo '<tr class="item-row">';
                	echo '<td style="width: 30px"><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>';
                	echo '<td style="width: 178px"><input name="procd[]" value="'.$rowq['sprocd'].'" tProCd1='.$i.' id="procd'.$i.'" class="autosearch" style="width: 175px" onchange ="upperCase(this.id)" onblur="get_desc(this.value, '.$i.')"></td>';
                	echo '<td style="width: 178px"><input name="procdbuyer[]" value="'.$rowq['sprocdbuyer'].'" tProCd1='.$i.' id="procdbuyer'.$i.'" class="autosearch" style="width: 175px" onchange ="upperCase(this.id)" ></td>';
                	echo '<td><input name="procdname[]" value="'.$rowq['sprodesc'].'" id="proconame'.$i.'" style="width: 303px;"></td>';
                    echo '<td style="width: 100px"><input name="proorqty[]" value="'.$rowq['sproqty'].'" id="proordqty'.$i.'" onBlur="calcAmt('.$i.');" style="width: 97px; text-align:center;"></td>';
                	echo '<td><input name="prouom[]" id="prouom'.$i.'" value="'.$rowq['sprouom'].'"  onchange ="upperCase(this.id)" style="width: 75px"></td>';
                	echo '<td style="width: 137px"><input name="prooupri[]" id="prooupri'.$i.'" value="'.$rowq['sprounipri'].'" onBlur="calcAmt('.$i.');" style="width: 89px; text-align:right;"></td>';
					echo '<td style="width: 242px"><input name="proouamt[]" id="proouamt'.$i.'" value="'.$rowq['sproamt'].'" readonly="readonly" style="width: 116px; border-style: none; border-color: inherit; border-width: 0; text-align:right;"></td>';
             		echo '</tr>';
                    $i = $i + 1;

                }
             ?>       
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
					include("../Setting/btnupdate.php");
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
							echo("<span>Please Fill In The Buyer Purchase Order No; Process Fail</span>");
							break;
						case 5:
							echo("<span>This Buyer Purchase Order No Is Use For This Buyer Code; Process Fail</span>");
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
