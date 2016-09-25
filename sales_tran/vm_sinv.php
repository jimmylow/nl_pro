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
      $sinv = $_GET['i'];    
      include("../Setting/ChqAuth.php");

    }
 	
 	if (isset($_POST['Submit'])){   	 
     if ($_POST['Submit'] == "Print") {

   	 	$var_inv  = $_POST['sinvno'];
   	 	
   	 	$var_sql  = " SELECT sum(amts) from ship_invdet";
		$var_sql .= " Where invno = '$var_inv'";
		$rs_result = mysql_query($var_sql); 
    	$row2 = mysql_fetch_array($rs_result);
		$samt   = htmlentities($row2['sum(amts)']);
		if (empty($samt)){$samt = 0;}
		
		$var_sql  = " SELECT taxper from ship_invmas";
		$var_sql .= " Where invno = '$var_inv'";
		$rs_result = mysql_query($var_sql); 
    	$row2 = mysql_fetch_array($rs_result);
		$tper   = htmlentities($row2['taxper']);
		if (empty($tper)){$tper = 0;}
		$gamt = 0;
		$gamt = ($samt * $tper) / 100;
		$samt = $samt + $gamt;
		
		$samt = number_format($samt, "2", ".", "");	
        $wamt = convert_number_to_words($samt);
        $wamt = trim($wamt)." Only";

        
        $fname = "shipinv_rpt.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&si=".$var_inv."&w=".$wamt."&dbsel=".$varrpturldb."&menuc=".$var_menucode;
        $dest .= urlencode(realpath($fname));

        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        $backloc = "../sales_tran/vm_sinv.php?menucd=".$var_menucode."&i=".$var_inv;

       	echo "<script>";
       	echo 'location.replace("'.$backloc.'")';
        echo "</script>"; 

     }
    } 
    
    function convert_number_to_words($number){ 
 $hyphen      = '-';
    $conjunction = '  ';
    $separator   = ' ';
    $negative    = 'negative ';
    $decimal     = ' and ';
    $dictionary  = array(
        0                   => 'Zero',
        1                   => 'One',
        2                   => 'Two',
        3                   => 'Three',
        4                   => 'Four',
        5                   => 'Five',
        6                   => 'Six',
        7                   => 'Seven',
        8                   => 'Eight',
        9                   => 'Nine',
        10                  => 'Ten',
        11                  => 'Eleven',
        12                  => 'Twelve',
        13                  => 'Thirteen',
        14                  => 'Fourteen',
        15                  => 'Fifteen',
        16                  => 'Sixteen',
        17                  => 'Seventeen',
        18                  => 'Eighteen',
        19                  => 'Nineteen',
        20                  => 'Twenty',
        30                  => 'Thirty',
        40                  => 'Fourty',
        50                  => 'Fifty',
        60                  => 'Sixty',
        70                  => 'Seventy',
        80                  => 'Eighty',
        90                  => 'Ninety',
        100                 => 'Hundred',
        1000                => 'Thousand',
        1000000             => 'Million',
        1000000000          => 'Billion',
        1000000000000       => 'Trillion',
        1000000000000000    => 'Quadrillion',
        1000000000000000000 => 'Quintillion'
    );
    
    $decones = array( 
            '01' => "One", 
            '02' => "Two", 
            '03' => "Three", 
            '04' => "Four", 
            '05' => "Five", 
            '06' => "Six", 
            '07' => "Seven", 
            '08' => "Eight", 
            '09' => "Nine", 
            10 => "Ten", 
            11 => "Eleven", 
            12 => "Twelve", 
            13 => "Thirteen", 
            14 => "Fourteen", 
            15 => "Fifteen", 
            16 => "Sixteen", 
            17 => "Seventeen", 
            18 => "Eighteen", 
            19 => "Nineteen" 
            );
            
    $ones = array( 
            0 => " ",
            1 => "One",     
            2 => "Two", 
            3 => "Three", 
            4 => "Four", 
            5 => "Five", 
            6 => "Six", 
            7 => "Seven", 
            8 => "Eight", 
            9 => "Nine", 
            10 => "Ten", 
            11 => "Eleven", 
            12 => "Twelve", 
            13 => "Thirteen", 
            14 => "Fourteen", 
            15 => "Fifteen", 
            16 => "Sixteen", 
            17 => "Seventeen", 
            18 => "Eighteen", 
            19 => "Nineteen" 
            ); 
$tens = array( 
            0 => "",
            2 => "Twenty", 
            3 => "Thirty", 
            4 => "Forty", 
            5 => "Fifty", 
            6 => "Sixty", 
            7 => "Seventy", 
            8 => "Eighty", 
            9 => "Ninety" 
            ); 
   
    if (!is_numeric($number)) {
        return false;
    }
   
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }
   
    $string = $fraction = null;
   
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
   
    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction) && $fraction <> "00") {

    $string .= " and Cents "; 
       if($fraction < 20){ 
        $string .= $decones[$fraction]; 
    }
    elseif($fraction < 100){ 
        $string .= $tens[substr($fraction,0,1)]; 
        $string .= " ".$ones[substr($fraction,1,1)]; 
    }        
    }
   
    return $string;
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

  <?php
  	 $sql = "select * from ship_invmas";
     $sql .= " where invno ='".$sinv."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $sinvdte   = date('d-m-Y', strtotime($row['invdte']));
     $setadte   = date('d-m-Y', strtotime($row['etadte']));
     $setddte   = date('d-m-Y', strtotime($row['etddte']));
     $sbuycd    = $row['buycd'];
     $scutsno   = $row['scutsno'];
     $sordno    = $row['buyorderno'];
     $svesdet   = $row['vesdet'];
     $scountori = $row['couori'];
     $scondi    = $row['salcondi'];
     $sbuyerde  = $row['buynm'];
     $gstper    = $row['taxper'];
     $sbuytel   = $row['buytel']; 
     $srmk1     = $row['rmk1'];
     $srmk2     = $row['rmk2'];
     $srmk3     = $row['rmk3'];
     $fabcont   = $row['fabcont'];
 
     $sql = "select sum(qtys), sum(amts) from ship_invdet ";
     $sql .= " where invno ='$sinv'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);
     $tqty = $row[0];
     $tamt = $row[1];	
     $tqty  = number_format($tqty, 0, '', '');
	 $tamt = number_format($tamt, 2, '.', ',');	
?>


<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc">

	<fieldset name="Group1" style=" width: 1020px;" class="style2">
	 <legend class="title">SHIPPING INVOICE</legend>
	  <br>	 
	  
	  <form name="InpSalesF" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>"  style="width: 1020px">
	   
		<table style="width: 993px; height: 220px;">
	   	   <tr>
	   	    <td style="width: 13px"></td>
	  	    <td style="width: 122px">Invoice No</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 201px">
			<input class="inputtxt" name="sinvno" id="sinvno" type="text"  style="width: 160px;" readonly="readonly" value="<?php echo $sinv; ?>">
			</td>
			<td style="width: 10px"></td>
			<td style="width: 204px">Invoice Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		    <input class="inputtxt" name="sinvdte" id ="sinvdte" type="text" style="width: 128px;" value="<?php  echo $sinvdte; ?>" readonly="readonly">
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
	  	    	<select name="sbuycd" id="sbuycd" style="width: 268px">
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
		   <input class="inputtxt" name="setddte" id ="setddte" type="text" style="width: 128px;" value="<?php  echo $setddte; ?>" readonly="readonly">
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
	  	   <td style="width: 201px">
	  	   <input class="inputtxt" name="sordno" id ="sordno" type="text" style="width: 128px;" value="<?php  echo $sordno; ?>" readonly="readonly">
		   </td>
		   <td></td>
	  	   <td style="width: 122px">ETA Date</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 201px">
		   	<input class="inputtxt" name="setadte" id ="setadte" type="text" style="width: 128px;" value="<?php  echo $setadte; ?>"  readonly="readonly">
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
			<input class="inputtxt" name="scondi" id="scondi" type="text" maxlength="60" style="width: 263px;" readonly="readonly" value="<?php echo $scondi; ?>"></td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">
		   </td>
		   <td></td>
		   <td style="width: 284px">
		   		<?php $viel = "vie_sinv_sbank.php?i=".$sinv; ?>
		   		<input type="button" value="Shipper Bank" class="butsub" style="width: 100px; height: 20px" onclick="javascript:poptastic('<?php echo $viel; ?>')">
		  </td>
	  	  </tr>
	  	    <tr>
	  	   <td style="width: 13px;"></td>
	  	   <td style="width: 122px;">Buyer</td>
	  	   <td style="width: 13px;">:</td>
	  	   <td style="width: 201px;">
			<input class="inputtxt" name="sbuyerde" id="sbuyerde" type="text" maxlength="60" style="width: 263px;" readonly="readonly" value="<?php echo $sbuyerde; ?>"></td>
		   <td style="width: 10px;"></td>
		   <td style="width: 204px;">Tax (%)</td>
		   <td>:</td>
		   <td style="width: 284px;">
		    <input class="inputtxt" name="gstper" id="gstper" type="text" style="width: 60px; text-align:right" value="<?php echo $gstper; ?>" readonly="readonly"></td>
	  	    </tr>
			<tr>
	  	   <td style="width: 13px;"></td>
	  	   <td style="width: 122px;">Tel</td>
	  	   <td style="width: 13px;">:</td>
	  	   <td style="width: 201px;">
			<input class="inputtxt" name="sbuytel" id="sbuytel" type="text" maxlength="60" style="width: 263px;" value="<?php echo $sbuytel; ?>" readonly="readonly"></td>
		   <td style="width: 10px;"></td>
		   <td style="width: 204px;">Total Quantity</td>
		   <td>:</td>
		   <td style="width: 284px;">
		   <input readonly="readonly" name="totqty" id ="totqtyid" type="text" style="width: 156px;" class="textnoentry1" value="<?php echo $tqty; ?>">	  	    
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
		   <input readonly="readonly" name="totamt" id ="totamtid" type="text" style="width: 156px;" class="textnoentry1" value="<?php echo $tamt; ?>"></td>
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
              	$sql = " select sysprocd, buyprocd, descript, qtys, uoms, unitpri, amts from ship_invdet";
             	$sql .= " where invno = '$sinv'";
             	$sql .= " order by seqno";
              	$rs_result = mysql_query($sql);
              	$i = 1;
               if (mysql_numrows($rs_result) > 0) {
                	while ($rowq = mysql_fetch_assoc($rs_result)){ 

        				$lprocd      = $rowq['sysprocd']; 
             			$lprocdbuyer = $rowq['buyprocd']; 
                		$lprodesc    = $rowq['descript']; 
  						$lproqty     = $rowq['qtys']; 	
						$lprouom     = $rowq['uoms']; 
						$lprounipri  = $rowq['unitpri']; 
						$oamt        = $rowq['amts']; 
						$lproqty = number_format($lproqty, 0,".", "");
						$oamt = number_format($oamt, 2,".", "");
                                          
             ?>   
             <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="<?php echo $i; ?>" readonly="readonly" style="width: 27px; border:0;"></td>
                <td style="width: 100px">
				<input name="procd[]" value="<?php echo $lprocd; ?>" id="procd<?php echo $i; ?>" class="autosearch" style="width: 110px" readonly="readonly"></td>
                <td style="width: 100px">
				<input name="procdbuyer[]" value="<?php echo $lprocdbuyer; ?>" tProCd1=1 id="procdbuyer<?php echo $i; ?>" style="width: 110px" readonly="readonly"></td>
                <td>
				<input name="procdname[]" value="<?php echo $lprodesc; ?>" id="proconame<?php echo $i; ?>" style="width: 303px;" readonly="readonly"></td>
                <td style="width: 100px">
				<input name="proorqty[]" id="proordqty<?php echo $i; ?>" readonly="readonly" style="width: 97px; text-align:center;" value="<?php echo $lproqty; ?>">
				</td>
                <td>
				<input name="prouom[]" id="prouom<?php echo $i; ?>" style="width: 75px;" value="<?php echo $lprouom;?>">
				</td>
                <td style="width: 137px">
				<input name="prooupri[]" id="prooupri<?php echo $i; ?>" readonly="readonly" style="width: 89px; text-align:right;" value="<?php echo $lprounipri;?>">
				</td>
				<td style="width: 242px">
				<input name="proouamt[]" id="proouamt<?php echo $i; ?>" readonly="readonly" style="width: 116px; border-style: none; border-color: inherit; border-width: 0; text-align:right;" value="<?php echo $oamt; ?>">
				</td>
             </tr>
             <?php
             	$i = $i + 1;
             	}
             }
             ?>
            </tbody>
           </table>
		 <table>
		  	<tr>
				<td style="width: 1150px;" align="center">
				<?php
				 $locatr = "m_ship_inv.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				 echo '<input type=submit name = "Submit" value="Print" class="butsub" style="width: 60px; height: 32px" >';
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
