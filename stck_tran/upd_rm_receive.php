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
      $var_menucode = $_GET['menucd'];
      $rm_receive_id = $_GET['rm_receive_id'];
      include("../Setting/ChqAuth.php");
    }
    
    if ($_POST['Submit'] == "Update") {
		$rm_receive_id = $_POST['rm_receive_id'];
		$refno         = htmlentities(mysql_real_escape_string($_POST['refno']));
		$prorevdte     = date('Y-m-d', strtotime($_POST['prorevdte']));    
		$var_menucode  = $_POST['menudcode'];
		$po_no         = htmlentities(mysql_real_escape_string($_POST['pono']));
		$invno         = htmlentities(mysql_real_escape_string($_POST['invno']));
		$remarks       = htmlentities(mysql_real_escape_string($_POST['remarks']));
            
		if ($refno <> "") {
			   	$vartoday = date("Y-m-d H:i:s");
				$sql = "update rawmat_receive set grndate = '$prorevdte', refno ='$refno', invno ='$invno',";
				$sql .= "      remarks = '$remarks',  upd_by = '$var_loginid', upd_on='$vartoday' ";
				$sql .= "  where rm_receive_id = '$rm_receive_id'";
				mysql_query($sql);
			
				// to delete from rawmat receive details table
				$sql =  "delete from rawmat_receive_tran";
				$sql .= "  where rm_receive_id ='$rm_receive_id'";
				
				mysql_query($sql);
				
				// to delete from rawmat history table
				$sql =  "delete from rawmat_tran";  
				$sql .= "  where rm_receive_id ='$rm_receive_id' and tran_type = 'REC'";
				
				mysql_query($sql);
				
				// to delete from rawmat over 10% table
				$sql =  "delete from rawmat_receive_over ";  
				$sql .= "  where rm_receive_id ='$rm_receive_id' ";
				
				mysql_query($sql);
				
				$sql =  "delete from po_over ";  
				$sql .= "  where trx_no ='$rm_receive_id' ";
				
				mysql_query($sql);
		 
				if(!empty($_POST['procomat']) && is_array($_POST['procomat'])) 
				{	
					foreach($_POST['procomat'] as $row=>$matcd ) {
						$matcode    = $matcd;
						$seqno      = $_POST['seqno'][$row];
						$matdesc    = htmlentities(mysql_real_escape_string($_POST['procodesc'][$row]));
						$matuom     = $_POST['procoum'][$row];
						$receiveqty = $_POST['recqty'][$row];
						$orderqty   = $_POST['procomark'][$row];
						$qtyperpack = $_POST['qtyperpack'][$row];
						$uprice = $_POST['uprice'][$row];
						//$totreceived = $_POST['receivedqty'][$row]; // total received qty - from other receivings
						
						//echo '</br>'. $matcode. " - ". $receiveqty. " - ". $totreceived;
						
						$sql2 = " select sum(totalqty) as receivedqty  ";
						$sql2.= " FROM rawmat_tran ";
						$sql2.= " WHERE rm_receive_id =  '". $rm_receive_id. "'";
						$sql2.= " AND item_code =  '". $matcode."'";
						
						$totreceived = 0;
						$sql_result2 = mysql_query($sql2) or die("Cant Get Total Received Qty ".mysql_error());;
						$row2 = mysql_fetch_array($sql_result2);
						$totreceived = $row2[0];
						if ($totreceived=='' or $totreceived== ' ' or $totreceived==NULL){ $totreceived=0; }


						
						
						
						// to select supplier based on PO, then only can find our what currency used //
				
						$sql_po = "select supplier from po_master x ";
		    			$sql_po .= "where x.po_no = '$po_no' ";
		    			//echo $sql_po;
		
		    			$sql_result_po = mysql_query($sql_po) or die("Cant Query Supplier From PO Master ".mysql_error());;
		    			$row_po = mysql_fetch_array($sql_result_po);
		    			$supplier = $row_po[0];
		    			
		    			
		    			$sql2 = "SELECT currency_code FROM rawmat_price_ctrl ";
		    			$sql2 .= "where supplier  = '$supplier' ";
		    			$sql2 .= "and  rm_code = '$matcode'";

						
						//$sql2 = "select currency_code from rawmat_master x , rawmat_subcode y ";
		    			//$sql2 .= "where x.rm_code = main_code ";
		    			//$sql2 .= "and  y.rm_code = '$matcode'";
		    			//echo $sql2;
		
		    			$sql_result2 = mysql_query($sql2) or die("Cant Query Curr From Main Code Table ".mysql_error());;
		    			$row2 = mysql_fetch_array($sql_result2);
		    			$curr       = $row2[0];
		
						#-------------Begin convert price to based currency buy rate--------------------------------------
					 	$exhmth = date("n",strtotime($prorevdte)); 
					 	$exhyr  = date("Y",strtotime($prorevdte));
					 	
					 	if ($curr == "MYR"){
					 		$brate = 1;
					 	}else{	
					 		$sql4 = "select buyrate from curr_xrate ";
		   					$sql4 .= " where xmth ='$exhmth' and xyr ='$exhyr' ";
		   					$sql4 .= " and curr_code = '$curr'";
		   					$sql_result4 = mysql_query($sql4) or die("Cant Echange Rate Table ".mysql_error());;
		   					$row4 = mysql_fetch_array($sql_result4);
		   					$brate = $row4[0];
		   				}	
		
						$basecst = $uprice * $brate;

					
						if ($matcode <> "")
						{
							if ($qtyperpack == ""){ $qtyperpack = 0;}
							$sql = "INSERT INTO rawmat_receive_tran values 
				    				('$rm_receive_id', '$seqno', '$refno', '$po_no', '$matcode', '$uprice', '$matdesc', '$matuom','$orderqty', '$qtyperpack', '$receiveqty', '$basecst')";
							mysql_query($sql);
							
							
							$marginqty =0;
						    $marginqty = $orderqty * 1.1;
						    
						    $sum_received = 0;
						    $sum_received = $totreceived + $receiveqty;

						    if ($sum_received < $marginqty)	
						    {
						    	//echo 'ONE '; '</br>';
								$sql = "INSERT INTO rawmat_tran values 
					   			('REC', '$rm_receive_id', '$refno','$po_no', '$prorevdte', '$matcode', '$uprice', '$matdesc', '$qtyperpack', '$receiveqty','$var_loginid', '$vartoday','$var_loginid', '$vartoday')";
								mysql_query($sql);
							}else{
								$sql2 = "INSERT INTO rawmat_receive_over values 
					    		('$rm_receive_id', '$seqno', '$refno', '$po_no', '$matcode', '$uprice', '$matdesc', '$matuom','$orderqty', '$qtyperpack', '$receiveqty', '$basecst')";
								mysql_query($sql2) or die("Error Insert into RAW MAT Extra : ".mysql_error(). ' Failed SQL is --> '. $sql2);
	
								$cnt = 0;
								$sqlc = "select count(*) from po_over where po_no = '$po_no' and trx_no = '$rm_receive_id' and stat = 'KIV'";
								$sqc = mysql_query($sqlc) or die("Error : ".mysql_error());;
		   						$rc = mysql_fetch_array($sqc);
		   						$cnt = $rc[0];
		   						if (empty($cnt)){$cnt = 0;}
								
								if ($cnt == 0){
									$sqlins = "INSERT INTO po_over values ('$po_no', 'KIV', '$rm_receive_id', '$prorevdte', '', '0000-00-00 00:00:00', '')";
									mysql_query($sqlins) or die("Error Insert into Approval Table : ".mysql_error(). ' Failed SQL is --> '. $sqlins);	 
								}
							}

           				}	
					}
				}

				$backloc = "../stck_tran/m_rm_receive.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 		
		}else{
			$backloc = "../stck_tran/m_rm_receive.php?stat=4&menucd=".$var_menucode;
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
<script  type="text/javascript" src="../stck_tran/jq-ac-script_rec.js"></script>


<script type="text/javascript"> 
function setup() {

		document.InpJobFMas.refnoid.focus();
						
 		//Set up the date parsers
        var dateParser = new DateParser("dd-MM-yyyy");
      
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("dd-MM-yyyy", "prorevdte");
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
});

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

function calcCost(vid)
{
    var vissueqtyid = "issueqtyid"+vid;    	
   	var vqtyperpack = "qtyperpack"+vid;

    var col1 = document.getElementById(vissueqtyid).value;
    var col2 = document.getElementById(vqtyperpack).value;
 
	if (col1 != ""){
		if(isNaN(col1)) {
    	   alert('Please Enter a valid number for Received Qty : ' + col1);
    	   col1 = 0;
    	}
       	document.getElementById(vissueqtyid).value = parseFloat(col1).toFixed(2);

    }	
    
   	if (col2 != ""){
		if(isNaN(col2)) {
    	   alert('Please Enter a valid number for Qty / Pack : ' + col2);
    	   col1 = 0;
    	}
    	document.getElementById(vqtyperpack).value = parseFloat(col2).toFixed(2);
    }
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
	document.InpJobFMas.refno.focus;
	return false;
	}

    var x=document.forms["InpJobFMas"]["prorevdte"].value;
	if (x==null || x=="")
	{
	alert("Date Must Not Be Blank");
	document.InpJobFMas.prorevdte.focus;
	return false;
	}

	var x=document.forms["InpJobFMas"]["pono"].value;
	if (x==null || x=="")
	{
	alert("PO No Must Not Be Blank");
	document.InpJobFMas.pono.focus;
	return false;
	}
	
	
	// to chk receive qty is not more than order qty---------------------------------------------------
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
	var mylist = new Array();	    
	
	var yesflg = 'N';

	for (var j = 1; j < rowCount; j++){
	    var idrowItem = "procomat"+j; // raw mat item
        var rowItem = document.getElementById(idrowItem).value;	 
        
        var idrowItem2 = "issueqtyid"+j; // rec qty
        var rowItem2 = document.getElementById(idrowItem2).value;	
        
        var idrowItem3 = "procomark"+j; //order qty
        var rowItem3 = document.getElementById(idrowItem3).value;
        
        var idrowItem4 = "receivedqty"+j; //total received qty
        var rowItem4 = document.getElementById(idrowItem4).value;
        
        var totqty = parseInt(rowItem4) + parseInt(rowItem2);

        
        var recqty_var = rowItem3 * 1.10; // received qty cannot more +10% from order qty//
        
        //alert (totqty); return false;
         
		if (parseFloat(totqty) > parseFloat(recqty_var) ){			
			//alert ("Receive Qty Is More +10% Than Order Qty For Item : " + rowItem);		   
		    //return false;
		    yesflg = 'Y';
		}	
		//return false;

    }
    if (yesflg != 'N'){
		alert ("Got Item(s) Receive Qty Is More Than 10% Than Order Qty...Please Get Approval From FM");
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

	for (var j = 1; j < rowCount; j++){

	    var idrowItem = "procomat"+j;
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
	
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx------------------------------------------------
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
	var mylist = new Array();	    

	for (var j = 1; j < rowCount; j++){

	    var idrowItem = "procomat"+j;
        var rowItem = document.getElementById(idrowItem).value;	 
        
        var idrowItem2 = "recqty"+j;
        var rowItem2 = document.getElementById(idrowItem2).value;	
        
        var idrowItem3 = "procomark"+j;
        var rowItem3 = document.getElementById(idrowItem3).value;
        
		if (rowItem2 > rowItem3 ){
			mylist.push(rowItem);  
			
		}	
    }		
	
	mylist.sort();
	var last = mylist[0];
	
	for (var i=1; i < mylist.length; i++) {
		
			alert ("QTY MORE Item Found; " + last);
			 return false;
			
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

</script>
</head>
<body onload= "setup()">
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>-->

  <?php
  	 $sql = "select * from rawmat_receive";
     $sql .= " where rm_receive_id ='".$rm_receive_id."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $grndate = date('d-m-Y', strtotime($row['grndate']));
     $refno = $row['refno'];
     $pono = $row['po_number'];
     $itemcode = $row['item_code'];
     $invno = $row['invno'];
     $remarks = $row['remarks'];

	
  ?> 
  <div class="contentc">

	<fieldset name="Group1" style=" width: 991px;" class="style2">
	 <legend class="title">RAWMAT RECEIVE UPDATE :<?php echo $rm_receive_id;?></legend>
	  <br>	 
	  
	  <form name="InpJobFMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	    <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">

		<table style="width: 886px">
	   	   <tr>
	   	    <td style="height: 25px"></td>
	  	    <td style="width: 126px; height: 25px;">Receive No.</td>
	  	    <td style="width: 13px; height: 25px;">:</td>
	  	    <td style="width: 239px; height: 25px;">
			<input class="textnoentry1" name="rm_receive_id" id="prorevid" type="text" style="width: 84px;" readonly="readonly" tabindex="0" value="<?php echo $rm_receive_id; ?>">
			</td>
	  	  </tr> 
	   	   <tr>
	   	    <td></td>
	  	    <td style="width: 126px">Ref No.</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 239px">
			<input class="inputtxt" name="refno" id="refnoid" type="text" style="width: 84px;" tabindex="0" value="<?php echo $refno; ?>">
			</td>
			<td style="width: 29px"></td>
			<td style="width: 136px">Receive Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 270px">
		   <input class="inputtxt" name="prorevdte" id ="prorevdte" type="text" style="width: 128px;" value="<?php  echo $grndate; ?>">
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('prorevdte','yyyyMMdd')" style="cursor:pointer">
		   </td>
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 126px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 239px"></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 126px">PO No.</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
		   <input name="pono" id="ponoid" type="text" style="width: 129px; height: 21px;" value="<?php echo $pono;?>" class="textnoentry1" readonly="readonly">
		   </td>
		   <td style="width: 29px"></td>
			<td style="width: 136px">Invoice No.</td>
			<td style="width: 16px">:</td>
			<td style="width: 270px">
		   <input class="inputtxt" name="invno" id ="invnoid" type="text" style="width: 128px;" value="<?php  echo $invno; ?>">&nbsp;
		   </td>

	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td style="width: 126px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 239px"></td>
	  	  </tr>
		  		  	
	  	    <tr>
	  	   <td></td>
	  	   <td style="width: 126px">Remarks</td>
	  	   <td style="width: 13px">:</td>
	  	   <td colspan="5">
		   <input class="inputtxt" name="remarks" id ="invnoid0" type="text" style="width: 563px;" value="<?php  echo $remarks; ?>" maxlength="100">&nbsp;
		   </td>
		    </tr>
		  		  	
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table">
          	<thead>
          	 <tr>
              <th class="tabheader" style="width: 27px; height: 57px;">#</th>
              <th class="tabheader">Raw Material Item</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Unit Cost</th>
              <th class="tabheader" style="width: 78px">Order Qty</th>
              <th class="tabheader">Total Received</th>
              <th class="tabheader">Qty/Pack</th>
              <th class="tabheader">Receive Qty</th>
             </tr>
            </thead>
            <tbody>
              <?php
             	$sql = "SELECT * FROM rawmat_receive_tran";
             	$sql .= " Where rm_receive_id='".$rm_receive_id ."'"; 
	    		$sql .= " ORDER BY seqno";  
				$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){
				
					$sql2 = " select sum(totalqty) as receivedqty  ";
					$sql2.= " FROM rawmat_receive_tran ";
					$sql2.= " WHERE po_number =  '". $pono. "'";
					$sql2.= " AND item_code =  '". $rowq['item_code']."'";
					//echo $sql2;
					$receivedqty = 0;
					$sql_result2 = mysql_query($sql2) or die("Cant Get Total Received Qty ".mysql_error());;
					$row2 = mysql_fetch_array($sql_result2);
					$receivedqty = $row2[0];
					if ($receivedqty=='' or $receivedqty== ' ' or $receivedqty ==NULL){ $receivedqty=0; }
					
					echo '<tr class="item-row">';	
					echo '<td><input name="seqno[]" id="seqno" value="'.$i.'"   readonly="readonly" style="width: 27px; border:0;"></td>'; 
					echo '<td><input name="procomat[]" value="'.$rowq['item_code'].'" id="procomat'.$i.'" readonly="readonly" style="width: 250px; border-style: none;"  ></td>';
                	echo '<td><input name="procodesc[]" value="'.$rowq['description'].'" id="procomat1" readonly="readonly" style="width: 300px; border-style: none;"  ></td>';
             		echo '<td><input name="procoum[]" value="'.$rowq['uom'].'" id="procoum" readonly="readonly" style="width: 75px; border-style: none;"  ></td>';       	
             		echo '<td><input name="uprice[]" tMark="1" id="uprice"   readonly="readonly" style="width: 75px; border:0;" value="'.$rowq['unit_cost'].'"></td>';

                	echo '<td><input name="procomark[]" id="procomark'.$i.'"   readonly="readonly" style="width: 75px; border:0;" value="'.$rowq['orderqty'].'"></td>';
                	echo '<td><input name="receivedqty[]" tMark="1" id="receivedqty'.$i.'" readonly="readonly" style="width: 75px; border:0;" value="'.$receivedqty.'"></td>';
                	echo '<td><input name="qtyperpack[]" value="'.$rowq['qtyperpack'].'" id="qtyperpack'.$i.'" onBlur="calcCost('.$i.');" style="width: 75px; "></td>'; 
                	echo '<td><input name="recqty[]" value="'.$rowq['totalqty'].'" id="issueqtyid'.$i.'"    onBlur="calcCost('.$i.');" style="width: 75px;"></td>';
                	
                	echo ' </tr>';
            	
                	$i = $i + 1;
                }
              ?>
            </tbody>
           </table>
           
         &nbsp;

	
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_rm_receive.php?menucd=".$var_menucode;
			
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
						case 3:
							echo("<span>Duplicated Found Or Code Number Fall In Same Range</span>");
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
				<td>&nbsp;</td>
			</tr>
	  	</table>
	   </form>	
	</fieldset>
	</div>
	<div class="spacer"></div>
</body>

</html>
