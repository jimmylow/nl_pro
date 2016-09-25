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
      $sinvdte = date("d-m-Y");
      $setadte = date("d-m-Y");
      $setddte = date("d-m-Y");
      $sinvd = $_GET['sinvno'];
      $sordno = "";
      $svesdet = "";
      $scountori = "";
      $scondi = "";
      $sbuyerde = "";
      $gstper = 6;
      $sbuytel= "";
      $totqty  =0;
      $totamt  =0;
      
      include("../Setting/ChqAuth.php");

    }
    
    if ($_POST['Submit'] == "Save") {
		$sbuycd    = mysql_real_escape_string($_POST['sbuycd']);
		$sordno    = mysql_real_escape_string($_POST['sordno']);
		$sinvdte   = date('Y-m-d', strtotime($_POST['sinvdte']));
		$setddte   = date('Y-m-d', strtotime($_POST['setddte']));
		$setadte   = date('Y-m-d', strtotime($_POST['setadte']));
		$svesdet   = mysql_real_escape_string($_POST['svesdet']);
		$scountori = mysql_real_escape_string($_POST['scountori']);
		$scondi    = mysql_real_escape_string($_POST['scondi']);
		$sbuyerde  = mysql_real_escape_string($_POST['sbuyerde']);
		$gstper    = $_POST['gstper'];
		$sbuytel   = mysql_real_escape_string($_POST['sbuytel']);
		$srmk1     = mysql_real_escape_string($_POST['srmk1']);
		$srmk2     = mysql_real_escape_string($_POST['srmk2']);
		$srmk3     = mysql_real_escape_string($_POST['srmk3']);
        $fabcont   = mysql_real_escape_string($_POST['fabcont']);
            
		if ($sbuycd <> "") {
		
		  /*----------------------------- TO get auto number------------------------------------ */
			$sysno = '';
			$yrinv = date('y', strtotime($_POST['sinvdte']));
     		$sqlchk  = "select sysno from ship_invsysno";
     		$sqlchk .= " where trxtype = 'SHIPINV'";
     		$dumsysno= mysql_query($sqlchk) or die(mysql_error());
     		while($row = mysql_fetch_array($dumsysno))
     		{
     			$sysno = $row['sysno'];        
     		}
     		if (empty($sysno))
     		{
     			$sysno = '2999';
     			$sysno_sql = "INSERT INTO ship_invsysno (trxtype, sysno)values ('SHIPINV', '$sysno')";
     			mysql_query($sysno_sql) or die("Error insert Sales Entry:".mysql_error(). ' Failed SQL is --> '. $sql);
     		}
     		$newsysno = $sysno + 1;
     		
     		$adj_sysno  = str_pad($newsysno , 4, '0', STR_PAD_LEFT);
     		$vmordno = $adj_sysno;
            /*--------------------------- end get auto number  ---------------------------------- */
            
            $sqlc  = "select * from tmpinvshipremark where usernm = '$var_loginid'";
     		$rwc = mysql_query($sqlc) or die(mysql_error());
     		$rw = mysql_fetch_array($rwc);
     		$rbanknm   = $rw['banknm']; 
     		$rbankadd1 = $rw['bankadd1'];  
     		$rbankadd2 = $rw['bankadd2'];  
     		$rbankadd3 = $rw['bankadd3'];  
     		$rtel      = $rw['tel'];  
     		$rfax      = $rw['fax'];  
     		$raccno    = $rw['accno'];  
     		$rswiftno  = $rw['swiftno'];        
          	
            $vartoday = date("Y-m-d");
			$sql = "INSERT INTO ship_invmas values 
					('$vmordno', '$sinvdte', '$sbuycd', '$setddte', '$setadte', '$sordno', '$svesdet',
					 '$scountori','$scondi','$gstper','$sbuyerde','$sbuytel', '$srmk1','$srmk2','$srmk3',
					 '$vartoday', '$var_loginid', '$var_loginid', '$vartoday', '$rbanknm', '$rbankadd1',
					 '$rbankadd2', '$rbankadd3', '$rtel', '$rfax', '$raccno', '$rswiftno', ' $fabcont')"; 
			mysql_query($sql) or die("Error insert Sales Entry:".mysql_error(). ' Failed SQL is --> '. $sql);


			if(!empty($_POST['procd']) && is_array($_POST['procd'])) 
				{	
					foreach($_POST['procd'] as $row=>$procd ) {
						$procode    = $procd;
						$proseqno   = $_POST['seqno'][$row];
						$procdbuyer = $_POST['procdbuyer'][$row];
						$prodesc    = $_POST['procdname'][$row];
						$proqty     = $_POST['proorqty'][$row];
						$prouom     = $_POST['prouom'][$row];
						$proupri    = $_POST['prooupri'][$row];
						$protamt    = $_POST['proouamt'][$row];
						if (empty($proqty)){ $proqty= 0;}
						if (empty($proupri)){ $proupri = 0;}
						if (empty($protamt)){ $protamt = 0;}
										
						if ($procode <> "" or $procdbuyer <> "")
						{
							$sql = "INSERT INTO ship_invdet values 
						    		('$vmordno', '$procode', '$procdbuyer', '$prodesc', '$proqty', '$prouom', '$proupri', '$protamt', '$proseqno')";
							mysql_query($sql) or die("Error in Sales Entry Det:".mysql_error(). ' Failed SQL is --> '. $sql);
           				}	
					}
				}
						
			$updsysno_sql  = "UPDATE ship_invsysno SET sysno = '$newsysno'";
			$updsysno_sql .= " WHERE  trxtype = 'SHIPINV'";
		   	mysql_query($updsysno_sql) or die("Error Update Sys Number :".mysql_error(). ' Failed SQL is --> '. $updsysno_sql);
		   	
		   	$sql = "delete from tmpinvshipremark where usernm = '$var_loginid'";
	 		mysql_query($sql) or die("Error SQL Delete:".mysql_error(). ' Failed SQL is --> '. $sql);
		
			$backloc = "../sales_tran/m_ship_inv.php?menucd=".$var_menucode;
           	echo "<script>";
           	echo 'location.replace("'.$backloc.'")';
            echo "</script>"; 		
		}
    }   
    
	 if ($_POST['Submit'] == "GetItem") {
    
		$sinvdte   = $_POST['sinvdte'];
		$setadte   = $_POST['setadte'];
		$setddte   = $_POST['setddte'];
		$sbuycd    = $_POST['sbuycd'];
		$sordno    = $_POST['sordno'];
   		$svesdet   = $_POST['svesdet'];
    	$scountori = $_POST['scountori'];
        $scondi    = $_POST['scondi'];
        $sbuyerde  = $_POST['sbuyerde'];
		$gstper    = $_POST['gstper'];
		$sbuytel   = $_POST['sbuytel'];
		$srmk1     = $_POST['srmk1'];
		$srmk2     = $_POST['srmk2'];
		$srmk3     = $_POST['srmk3'];
		$fabcont   = $_POST['fabcont'];
		
		if (empty($setddte)){
		$sql = " select sexpddte from salesentry";
        $sql .= " where sbuycd = '$sbuycd' and sordno = '$sordno'";
        $rs_result = mysql_query($sql);
        $rowq = mysql_fetch_assoc($rs_result);
        $setddte  = date('d-m-Y', strtotime($rowq['sexpddte'])); 
        }
		
		$sql = " select sum(sproqty), sum(sproqty*sprounipri) from salesentrydet";
        $sql .= " where sbuycd = '$sbuycd' and sordno = '$sordno'";
        $rs_result = mysql_query($sql);
        $rowq = mysql_fetch_assoc($rs_result);
        $totqty = $rowq['sum(sproqty)']; 
        $totamt = $rowq['sum(sproqty*sprounipri)']; 
        if (empty($totqty)){$totqty = 0;}
        if (empty($totamt)){$totamt = 0;}

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
<script  type="text/javascript" src="ship_inv_itm.js"></script>


<script type="text/javascript">
function setup() {

		document.InpSalesF.sinvdte.focus();
				
 		//Set up the date parsers
        var dateParser = new DateParser("dd-MM-yyyy");
      
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("dd-MM-yyyy", "sinvdte");
		dateMask1.validationMessage = errorMessage;
		
		var dateMask2 = new DateMask("dd-MM-yyyy", "setddte");
		dateMask2.validationMessage = errorMessage;  
		
		var dateMask3 = new DateMask("dd-MM-yyyy", "setadte");
		dateMask3.validationMessage = errorMessage;  
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

    var x=document.forms["InpSalesF"]["sbuycd"].value;
	if (x==null || x=="")
	{
		alert("Buyer Code Cannot Be Blank");
		document.InpSalesF.sbuycd.focus();
		return false;
	}

    //var x=document.forms["InpSalesF"]["totamt"].value;
	//if (x=="0")
	//{
	//	alert("Product To Invoice Empty");
	//	document.InpSalesF.sbuycd.focus();
	//	return false;
	//}
	
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
	var x = document.getElementById(idproname).value;
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
					if (x == ""){
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
	}
	req.open("GET", strURL, true);
	req.send(null);

}

function getBuyerOrder()
{
   var buyr = document.InpSalesF.sbuycd.value;
   var strURL="aja_get_salesorder.php?b="+buyr;

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
	    document.getElementById('buyord').innerHTML=req.responseText;
	 } else {
   	   alert("There was a problem while using XMLHTTP:\n" + req.statusText);
	 }
       }
      }
   req.open("GET", strURL, true);
   req.send(null);
   }
   showTerm();
}


function showTerm()
{
    var buyr = document.InpSalesF.sbuycd.value;
    var strURL="aja_get_valbuyer.php?b="+buyr;
 
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
//		alert (xmlhttp.responseText);
		//document.getElementById("suppadd").value=xmlhttp.responseText; 
		
		var txtrst = xmlhttp.responseText;
//alert(xmlhttp.responseText);		
		var result = txtrst.split("^");
		//alert (result[0]+" : "+result[1]+" : "+result[2]);
		
		var x = result[0];
		var y= result[1];

		document.getElementById("sbuyerde").value=result[0];   
		document.getElementById("sbuytel").value=result[1]; 
		//document.getElementById("totgrandid").value=result[2]; 
		//document.InpRawmatReceive.totproductid.value = parseFloat(x).toFixed(2);;		
		}
	  }
	xmlhttp.open("GET",strURL,true);
	xmlhttp.send();
}

function poptastic(url)
{
	var newwindow;
	newwindow=window.open(url,'name','height=300,width=900,left=100,top=200, scrollbars=yes');
	if (window.focus) {newwindow.focus()}
}

</script>
</head>
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc">

	<fieldset name="Group1" style=" width: 1020px;" class="style2">
	 <legend class="title">SHIPPING INVOICE</legend>
	  <br>	 
	  
	  <form name="InpSalesF" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()" style="width: 1020px">
	   
		<table style="width: 993px; height: 220px;">
	   	   <tr>
	   	    <td style="width: 13px"></td>
	  	    <td style="width: 122px">Invoice No</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 201px">
			<input class="inputtxt" name="sinvno" id="sinvno" type="text"  style="width: 100px;" readonly="readonly" value="<NEW>">
			</td>
			<td style="width: 10px"></td>
			<td style="width: 204px">Invoice Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		    <input class="inputtxt" name="sinvdte" id ="sinvdte" type="text" style="width: 128px;" value="<?php  echo $sinvdte; ?>">
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('sinvdte','ddMMyyyy')" style="cursor:pointer">
		    </td>
	  	  </tr>  
	  	  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 201px"></td>
	  	  </tr>

	   	   <tr>
	   	    <td style="width: 13px"></td>
	  	    <td style="width: 122px">Buyer</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 201px">
	  	    	<select name="sbuycd" id="sbuycd" style="width: 268px" onchange="getBuyerOrder()">
			 	<?php
             		 $sql = "select customer_code, customer_desc from customer_master ORDER BY customer_code ASC";
             		 $sql_result = mysql_query($sql);
             		 echo "<option size =30 selected></option>";
                       
			 		 if(mysql_num_rows($sql_result)) 
			 		 {
			 		  while($row = mysql_fetch_assoc($sql_result)) 
			 		  { 
			 		  	if ($sbuycd == $row['customer_code']){
							echo '<option value="'.$row['customer_code'].'" selected="selected" >'.$row['customer_code']." | ".$row['customer_desc'].'</option>';
						}else{
							echo '<option value="'.$row['customer_code'].'">'.$row['customer_code']." | ".$row['customer_desc'].'</option>';
						}			 		
					  } 
		     		 } 
	         	?>	
   
	       </select>
	       		
			</td>
			<td style="width: 10px"></td>
			<td style="width: 204px">ETD Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		   <input class="inputtxt" name="setddte" id ="setddte" type="text" style="width: 128px;" value="<?php  echo $setddte; ?>">
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('setddte','ddMMyyyy')" style="cursor:pointer">
		   </td>
	  	  </tr>  
	  	  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 201px"></td>
	  	  </tr>
	  	  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Buyer Order No</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px"> <div id="buyord">	  	   <?php
	  	   	if (!empty($sordno)){
	  	   		$sql = "SELECT sordno, sordnobuyer ";
				$sql.= " FROM salesentry ";
				$sql.= " where sbuycd = '$sbuycd'";
				$sql.= " and stat = 'ACTIVE'";
				$sql.= " ORDER BY sorddte desc ";
				$rs_result = mysql_query($sql); 
				if ($rs_result <> FALSE)
				{
					echo '<select name="sordno" id="sordno" style="width: 210px">';
        			echo "<option size =30 selected></option>";
             
					if(mysql_num_rows($rs_result)) 
					{
						 while($rowq = mysql_fetch_assoc($rs_result)) 
			 			{ 
			 				if ($sordno == $rowq['sordno']){
								echo '<option value="'.$rowq['sordno'].'"  selected="selected">'.$rowq['sordno']." | ".$rowq['sordnobuyer'].'</option>';
							}else{
								echo '<option value="'.$rowq['sordno'].'">'.$rowq['sordno']." | ".$rowq['sordnobuyer'].'</option>';
							}
			 			} 
					} 			   
					echo '</select>';
					echo '<input type=submit name = "Submit" value="GetItem" class="butsub" style="width: 70px; height: 20px" >';
				}
	  	   	}else{
	  	   	?>
	  	
	  	   <?php
	  	   	}
	  	   	?>
	  	   	   </div>
		   </td>
		   <td></td>
	  	   <td style="width: 122px">ETA Date</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
		   	<input class="inputtxt" name="setadte" id ="setadte" type="text" style="width: 128px;" value="<?php  echo $setadte; ?>">
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('setadte','ddMMyyyy')" style="cursor:pointer">
		   </td>
	  	  </tr>
	  	   <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px"></td>
	  	   <td style="width: 13px"></td>
	  	  </tr>
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 122px">Vessel's Detail</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
		   <input class="inputtxt" name="svesdet" id="svesdet" type="text"  style="width: 200px;" value="<?php echo $svesdet; ?>">
		   <td style="width: 10px"></td>
		   <td style="width: 204px">Country of Origin</td>
		   <td>:</td>
		   <td style="width: 284px">
		   	<select name="scountori" id="scountori" style="width: 200px" >
			 	<?php
             		 $sql = "select country_code, country_desc from country_master ORDER BY country_code ASC";
             		 $sql_result = mysql_query($sql);
             		 echo "<option size =30 selected></option>";
                       
			 		 if(mysql_num_rows($sql_result)) 
			 		 {
			 		  while($row = mysql_fetch_assoc($sql_result)) 
			 		  { 
			 		  	if ($scountori == $row['country_code']){
			 		  		echo '<option value="'.$row['country_code'].'" selected="selected">'.$row['country_code']." | ".$row['country_desc'].'</option>';
						}else{
							echo '<option value="'.$row['country_code'].'">'.$row['country_code']." | ".$row['country_desc'].'</option>';
			 		  	}
			 		  } 
		     		 } 
	         	?>				   
	       </select>

		   </td>
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
	  	   <td style="width: 122px">Sales Condition</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
			<input class="inputtxt" name="scondi" id="scondi" type="text" maxlength="60" style="width: 263px;" onchange ="upperCase(this.id)" value="<?php echo $scondi; ?>"></td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">
		   </td>
		   <td></td>
		   <td style="width: 284px">
		   		<input type="button" value="Shipper Bank" class="butsub" style="width: 100px; height: 20px" onclick="javascript:poptastic('upd_sinv_sbank.php')">
		  </td>
	  	  </tr>
	  	    <tr>
	  	   <td style="width: 13px;"></td>
	  	   <td style="width: 122px;">Buyer</td>
	  	   <td style="width: 13px;">:</td>
	  	   <td style="width: 201px;">
			<input class="inputtxt" name="sbuyerde" id="sbuyerde" type="text" maxlength="60" style="width: 263px;" value="<?php echo $sbuyerde; ?>"></td>
		   <td style="width: 10px;"></td>
		   <td style="width: 204px;">Tax (%)</td>
		   <td>:</td>
		   <td style="width: 284px;">
		    <input class="inputtxt" name="gstper" id="gstper" type="text" maxlength="60" style="width: 60px; text-align:right" value="<?php echo $gstper; ?>"></td>
	  	    </tr>
			<tr>
	  	   <td style="width: 13px;"></td>
	  	   <td style="width: 122px;">Tel</td>
	  	   <td style="width: 13px;">:</td>
	  	   <td style="width: 201px;">
			<input class="inputtxt" name="sbuytel" id="sbuytel" type="text" maxlength="30" style="width: 234px;" value="<?php echo $sbuytel; ?>"></td>
		   <td style="width: 10px;"></td>
		   <td style="width: 204px;">Total Quantity</td>
		   <td>:</td>
		   <td style="width: 284px;">
		   <input readonly="readonly" name="totqty" id ="totqtyid" type="text" style="width: 156px;" class="textnoentry1" value="<?php echo $totqty; ?>">	  	    
		   </tr>
  	  		<tr>
	  	   <td style="width: 13px;"></td>
	  	   <td style="width: 122px;"></td>
	  	   <td style="width: 13px;"></td>
	  	   <td style="width: 201px;">
		   <td style="width: 10px;"></td>
		   <td style="width: 204px;">Gross Total</td>
		   <td>:</td>
		   <td style="width: 284px;">
		   <input readonly="readonly" name="totamt" id ="totamtid" type="text" style="width: 156px;" class="textnoentry1" value="<?php echo $totamt; ?>"></td>
	  	    </tr>
	  	     <tr>
	  	   <td style="width: 13px;"></td>
	  	   <td style="width: 122px;">Fibre Content</td>
	  	   <td style="width: 13px;">:</td>
	  	   <td style="width: 201px;" colspan="4">
	  	   <input class="inputtxt" name="fabcont" id="fabcont" type="text" maxlength="80" style="width: 363px;" value="<?php echo $fabcont; ?>"></td>
	  	    </tr>
	  	    <tr>
	  	   <td style="width: 13px;"></td>
	  	   <td style="width: 122px;">Remark</td>
	  	   <td style="width: 13px;">:</td>
	  	   <td style="width: 201px;" colspan="4">
	  	   <input class="inputtxt" name="srmk1" id="srmk1" type="text" maxlength="80" style="width: 363px;" value="<?php echo $srmk1; ?>"></td>
	  	    </tr>
			<tr>
	  	   <td style="width: 13px;"></td>
	  	   <td style="width: 122px;"></td>
	  	   <td style="width: 13px;"></td>
	  	   <td style="width: 201px;" colspan="4">
	  	   <input class="inputtxt" name="srmk2" id="srmk2" type="text" maxlength="80" style="width: 363px;" value="<?php echo $srmk2; ?>"></td>
	  	    </tr>
			<tr>
	  	   <td style="width: 13px;"></td>
	  	   <td style="width: 122px;"></td>
	  	   <td style="width: 13px;"></td>
	  	   <td style="width: 201px;" colspan="4">
	  	   <input class="inputtxt" name="srmk3" id="srmk3" type="text" maxlength="80" style="width: 363px;" value="<?php echo $srmk3; ?>"></td>
	  	    </tr>

			
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table" style="width: 958px">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader" style="width: 110px">Product Code</th>
              <th class="tabheader" style="width: 110px">Buyer Code</th>
              <th class="tabheader">Description</th>
              <th class="tabheader" style="width: 100px">Quantity</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader" style="width: 137px">Unit <br>Price(RM)</th>
              <th class="tabheader" style="width: 242px">Amount</th>
             </tr>
            </thead>
            <tbody>
             <?php
              	$sql = " select sprocd, sprocdbuyer, sprodesc, sproqty, sprouom, sprounipri from salesentrydet";
             	$sql .= " where sbuycd = '$sbuycd' and sordno = '$sordno'";
             	$sql .= " order by sproseq";
              	$rs_result = mysql_query($sql);
              	$i = 1;
               if (mysql_numrows($rs_result) > 0) {
                	while ($rowq = mysql_fetch_assoc($rs_result)){ 

        				$lprocd      = $rowq['sprocd']; 
             			$lprocdbuyer = $rowq['sprocdbuyer']; 
                		$lprodesc    = $rowq['sprodesc']; 
  						$lproqty     = $rowq['sproqty']; 	
						$lprouom     = $rowq['sprouom']; 
						$lprounipri  = $rowq['sprounipri']; 
						$fprocd      = $lprocdbuyer;
						if (empty($lprocdbuyer)){
							$fprocd = $lprocd;
							if (empty($fprocd)){
								$sql1 = " select pro_buycd from pro_cd_master";
             					$sql1 .= " where prod_code = '$lprocd'";
				              	$rs_result1 = mysql_query($sql1);
                				$rowq1 = mysql_fetch_assoc($rs_result1); 
        						$fprocd = $rowq1['pro_buycd']; 
							} 
						}else{
							$fprocd = $lprocdbuyer;
						}
						
						$oamt = $lproqty * $lprounipri;
						$lproqty = number_format($lproqty, 0,".", "");
						$oamt = number_format($oamt, 2,".", "");
                                          
             ?>   
             <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="<?php echo $i; ?>" readonly="readonly" style="width: 27px; border:0;"></td>
                <td style="width: 100px">
				<input name="procd[]" value="<?php echo $lprocd; ?>" id="procd<?php echo $i; ?>" class="autosearch" style="width: 110px" onchange ="upperCase(this.id)" onblur="get_desc(this.value, '<?php echo $i; ?>')"></td>
                <td style="width: 100px">
				<input name="procdbuyer[]" value="<?php echo $fprocd; ?>" tProCd1=1 id="procdbuyer<?php echo $i; ?>" style="width: 110px"></td>
                <td>
				<input name="procdname[]" value="<?php echo $lprodesc; ?>" id="proconame<?php echo $i; ?>" style="width: 303px;"></td>
                <td style="width: 100px">
				<input name="proorqty[]" id="proordqty<?php echo $i; ?>" onBlur="calcAmt('<?php echo $i; ?>');" style="width: 97px; text-align:center;" value="<?php echo $lproqty; ?>">
				</td>
                <td>
				<input name="prouom[]" id="prouom<?php echo $i; ?>" style="width: 75px;" value="<?php echo $lprouom;?>">
				</td>
                <td style="width: 137px">
				<input name="prooupri[]" id="prooupri<?php echo $i; ?>" onBlur="calcAmt('<?php echo $i; ?>');" style="width: 89px; text-align:right;" value="<?php echo $lprounipri;?>">
				</td>
				<td style="width: 242px">
				<input name="proouamt[]" id="proouamt<?php echo $i; ?>" readonly="readonly" style="width: 116px; border-style: none; border-color: inherit; border-width: 0; text-align:right;" value="<?php echo $oamt; ?>">
				</td>
             </tr>
             <?php
             	$i = $i + 1;
             	}
             }
             

            if ($i == 1){ 
            ?>
             <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="1" readonly="readonly" style="width: 27px; border:0;"></td>
                <td style="width: 178px">
				<input name="procd[]" value="" tProCd1=1 id="procd1" class="autosearch" style="width: 110px" onchange ="upperCase(this.id)" onblur="get_desc(this.value, '1')"></td>
                <td style="width: 178px">
				<input name="procdbuyer[]" value="" tProCd1=1 id="procdbuyer1" style="width: 110px"></td>
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

            <?php
            }

             ?>
            </tbody>
           </table>
           
         <a href="#" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Item</span></a>
         <a href="javascript:deleteRow('itemsTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>

	
		 <table>
		  	<tr>
				<td style="width: 1150px;" align="center">
				<?php
				 $locatr = "m_ship_inv.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
					include("../Setting/btnsave.php");
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
