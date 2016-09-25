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
      $price_id = $_GET['price_id'];
      $price_id = $_GET['price_id'];
      include("../Setting/ChqAuth.php");
    }
    
    if ($_POST['Submit'] == "Update") {
		$price_id     = $_POST['price_id'];
		$rm_code      = mysql_real_escape_string($_POST['rm_code']);
		$prorevdte    = date('Y-m-d', strtotime($_POST['dteResult']));
		$uom          = $_POST['seluom'];
		$currency_code= $_POST['selcurr'];
		$supplier     = $_POST['supplier'];
		$shipping_term= $_POST['selship_term'];
	            
		$var_menucode  = $_POST['menudcode'];
            
		if ($price_id <> "") {
			   	$vartoday = date("Y-m-d H:i:s");
				$sql = "update rawmat_price_ctrl set effective_date = '$prorevdte',  uom='$uom', ";
				$sql .= "                       currency_code = '$currency_code', shipping_term ='$shipping_term', ";
				$sql .= "                       modify_by = '$var_loginid', modified_on='$vartoday' ";
				$sql .= "  where price_id = '$price_id'";
				mysql_query($sql);
			
				// to delete from rawmat price ctrl details table
				$sql =  "delete from rawmat_price_trans";
				$sql .= "  where price_id ='$price_id'";
				
				mysql_query($sql);
							
				if(!empty($_POST['fromqty']) && is_array($_POST['fromqty'])) 
				{	
					foreach($_POST['fromqty'] as $row=>$fromqty) {
						$fromqty = $fromqty;
						$seqno      = $_POST['seqno'][$row];
						$price   = $_POST['price'][$row];
						$toqty  = $_POST['toqty'][$row];
					
						if ($fromqty != NULL && $toqty != NULL && $price != NULL)
						{
							if ($price== ""){ $price= 0;}
							if ($toqty== ""){ $toqty= 0;}
							
							$sql = "INSERT INTO rawmat_price_trans values 
						    		('$price_id', '$rm_code',  '$fromqty','$toqty','$price', '$seqno', '$supplier')";
							mysql_query($sql);
							//echo $sql;
							//break;
						}	
					}
				}
				$backloc = "../stck_mas/m_rawmat_price_ctrl.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 		
		}else{
			$backloc = "../stck_mas/m_rawmat_price_ctrl.php?stat=4&menucd=".$var_menucode;
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

	var x=document.forms["InpJobFMas"]["remark"].value;
	if (x==null || x=="")
	{
	alert("Lable No Must Not Be Blank");
	return false;
	}
	
	var x=document.forms["InpJobFMas"]["requestby"].value;
	if (x==null || x=="")
	{
	alert("Request By Must Not Be Blank");
	return false;
	}
	
	// to chk reject qty is not more than onhand qty---------------------------------------------------
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
	var mylist = new Array();	    

	for (var j = 1; j < rowCount; j++){
	    var idrowItem = "fromqty"+j; // raw mat item
        var rowItem = document.getElementById(idrowItem).value;	 
        
        var idrowItem2 = "priceid"+j; // reject qty
        var rowItem2 = document.getElementById(idrowItem2).value;	
        
        var idrowItem3 = "toqty"+j; //onhand qty
        var rowItem3 = document.getElementById(idrowItem3).value;

		if (parseFloat(rowItem3) < 0 ){			
			alert ("Onhand Balance For Item " + rowItem + " is NEGATIVE");		   
		    return false;
		}	       
		
		if (parseFloat(rowItem3) == 0 ){			
			alert ("Cannot reject Item " +rowItem + ". Onhand Balance is ZERO ");		   
		    return false;
		}	
		
		if (parseFloat(rowItem2) == 0 ){			
			alert ("reject Qty Cannot Be ZERO For Item : " +rowItem);		   
		    return false;
		}		       
       
		if (parseFloat(rowItem2) > parseFloat(rowItem3) ){			
			alert ("reject Qty Cannot More Than Onhand Balance For Item : " + rowItem);		   
		    return false;
		}	
    }
    //---------------------------------------------------------------------------------------------------

			
	//Check the list of mat item no is Valid-------------------------------------------------------------
	var flgchk = 1;	
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
         
    for (var j = 1; j < rowCount; j++){
       var idrowItem = "fromqty"+j;
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

function setup() {

		document.InpJobFMas.dteResult.focus();
						
 		//Set up the date parsers
        var dateParser = new DateParser("dd-MM-yyyy");
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		
		var dateMask1 = new DateMask("dd-MM-yyyy", "dteResult");
		dateMask1.validationMessage = errorMessage;	
		
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
  <!--<?php include("../sidebarm.php"); ?>-->

  <?php
  	 $sql = "select * from rawmat_price_ctrl";
     $sql .= " where price_id ='".$price_id."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $effective_date = date('d-m-Y', strtotime($row['effective_date']));
     $main_code      = $row['main_code'];
     $remark         = $row['remark'];
     $rm_code        = $row['rm_code'];
     $supplier       = $row['supplier'];
     $uom            = $row['uom'];
     $currency_code  = $row['currency_code'];
     $shipping_term  = $row['shipping_term'];
	
  ?> 
  <div class="contentc">

	<fieldset name="Group1" style=" width: 747px;" class="style2">
	 <legend class="title">RAWMAT PRICE CONTROL UPDATE :<?php echo $price_id;?></legend>
	  <br>	 
	  
	  <form name="InpJobFMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	    <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">

		<table style="width: 734px">
	   	   <tr>
	   	    <td style="height: 25px"></td>
	  	    <td style="width: 126px; height: 25px;">Price ID</td>
	  	    <td style="width: 13px; height: 25px;">:</td>
	  	    <td style="width: 192px; height: 25px;">
			<input class="textnoentry1" name="price_id" id="prorevid" type="text" style="width: 84px; height: 16px;" readonly="readonly" tabindex="0" value="<?php echo $price_id; ?>">
			</td>
	  	  </tr> 
	  	  <tr>
	  	    <td style="width: 2px; height: 44px;">
	  	    </td>
	  	    <td style="width: 114px; height: 44px;" class="tdlabel">Master Code</td>
	  	    <td style="width: 4px; height: 44px;">:</td>
	  	    <td style="width: 192px; height: 44px;">
			<input class="textnoentry1" name="main_code" id ="rawmatcdid" readonly="readonly" type="text" style="width: 161px" value="<?php echo $main_code; ?>">
			</td>
			<td style="height: 44px; width: 4px;">
			</td>
		    <td style="width: 106px; height: 44px;" class="tdlabel"></td>
	  	    <td style="width: 6px; height: 44px;"></td>
	  	    <td style="width: 108px; height: 44px;">
			   </td>
	  	  </tr> 
	   	   <tr>
	   	    <td style="width: 2px; height: 28px;">
	  	    </td>
	  	    <td style="width: 114px; height: 28px;" class="tdlabel">Sub Code No</td>
	  	    <td style="width: 4px; height: 28px;">:</td>
	  	    <td style="width: 192px; height: 28px;">
			<input class="textnoentry1" name="rm_code" id ="rawmatcdid0" readonly="readonly" type="text" style="width: 161px" value="<?php echo $rm_code; ?>"></td>
			<td style="height: 28px; width: 4px;">
			</td>
		    <td style="width: 96px" class="tdlabel">Currency Code</td>
	  	    <td style="width: 7px">:</td>
	  	    <td style="width: 108px">
			   <select name="selcurr" style="width: 68px">
			    <?php
                   $sql = "select currency_code, currency_desc from currency_master ORDER BY currency_code ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected value = '$currency_code'>$currency_code</option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['currency_code'].'">'.$row['currency_code'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select>
			</td>
	  	  </tr>
	  	  <tr>
	   	    <td style="width: 2px; height: 28px;">
	  	    </td>
	  	    <td style="width: 114px; height: 28px;" class="tdlabel">Supplier</td>
	  	    <td style="width: 4px; height: 28px;">:</td>
	  	    <td style="width: 192px; height: 28px;">
			<input class="textnoentry1" name="supplier" id ="rawmatcdid1" readonly="readonly" type="text" style="width: 161px" value="<?php echo $supplier; ?>"></td>
			<td style="height: 28px; width: 4px;">
			</td>
			<td>
			UOM</td>
			<td>
			:</td>
			<td>
		    <select name="seluom" style="width: 68px">
			    <?php
                   $sql = "select uom_code, uom_desc from uom_master ORDER BY uom_code ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected value = '$uom'>$uom</option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['uom_code'].'">'.$row['uom_code'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select></td>

			
	  	  </tr>
		  <tr>
	   	    <td style="width: 2px; height: 29px;">
	  	    </td>
	  	    <td style="width: 114px; height: 29px;" class="tdlabel">Effective 
			Date</td>
	  	    <td style="width: 4px; height: 29px;">:</td>
	  	    <td style="width: 192px; height: 29px;">
			<input class="inputtxt" name="dteResult" id ="dteResult" type="text" maxlength="50" onchange ="upperCase(this.id)"</td value="<?php echo $effective_date; ?>">
			<img src="../images/cal.gif" onclick="javascript:NewCssCal('dteResult','ddMMyyyy')" style="cursor:pointer"></td>
			<td style="height: 29px; width: 4px;">
			</td>
			<td style="width: 96px; height: 29px;">Shipping Term</td>
            <td style="width: 7px; height: 29px;">:</td>
            <td style="width: 108px; height: 29px;">
			   <select name="selship_term" style="width: 116px">
			    <?php
                   $sql = "select shiptecd, shiptede from ship_term_master ORDER BY shiptecd ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected value = '$shipping_term'>$shipping_term</option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['shiptecd'].'">'.$row['shiptede'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select></td>
	  	  </tr>
		  
	
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table">
          	<thead>
          	 <tr>
              <th class="tabheader" style="width: 27px; height: 57px;">#</th>
              <th class="tabheader">From Qty</th>
              <th class="tabheader">To Qty</th>
              <th class="tabheader">Price</th>
             </tr>
            </thead>
            <tbody>
              <?php
             	$sql = "SELECT * FROM rawmat_price_trans";
             	$sql .= " Where price_id='".$price_id ."'"; 
	    		$sql .= " ORDER BY id";  
				$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){
					
					$currentbal = 0;
					$trx_onhand_bal = 0;
			        $sql = "select sum(totalqty) from rawmat_tran ";
        			$sql .= " where item_code ='".$rowq['item_code']."' and tran_type in ('OPB', 'REC', 'REJ', 'RTN', 'ISS')";
        			$sql_result = mysql_query($sql);
			        $row3= mysql_fetch_array($sql_result); // current onhand balance //

			        if ($row3[0] == "" or $row3[0] == null){ 
			        	$row3[0]  = 0;
        			}
        			
			        $currentbal= htmlentities($row3[0]); //current onhand bal as to date
			        $currentbal = floatval($currentbal);
        			
			        $reject_no_qty= htmlentities($rowq['totalqty']); //reject qty from this reject No...
			        $reject_no_qty = floatval($reject_no_qty);
			        
			        $trx_onhand_bal=  $currentbal + $reject_no_qty; // need to add back the reject qty from this reject_no
     			
					echo '<tr class="item-row">';	
					echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>'; 
					echo "<td><input name='fromqty[]' value='".htmlentities($rowq["from_qty"])."' id='fromqty".$i."' class='tInput' style='width: 75px'></td>";
                   	echo "<td><input name='toqty[]' value='".htmlentities($rowq["to_qty"])."' id='toqty".$i."' class='tInput' style='width: 75px'></td>";
                	echo "<td><input name='price[]' value='".htmlentities($rowq["price"])."' id='priceid".$i."' class='tInput' style='width: 75px' onBlur='disp_dec(".$i.")';></td>";

                	//echo '<td><input name="price[]" value="'.$rowq['totalqty'].'" id="priceid'.$i.'"  class='tInput' style="width: 75px; "></td>';              	
                	echo ' </tr>';
                	
                	$i = $i + 1;
                }
              ?>
            </tbody>
           </table>
           
         <a href="#" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Item</span></a>
         <a href="javascript:deleteRow('itemsTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>

	
		 <table>
		  	<tr>
				<td style="width: 727px; height: 22px;" align="center">
				<?php
				 $locatr = "m_rawmat_price_ctrl.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
					include("../Setting/btnupdate.php");
				?>
				</td>
			</tr>
			<tr>
				<td style="width: 727px" colspan="5">
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
				<td style="width: 727px">&nbsp;</td>
			</tr>
	  	</table>
	   </form>	
	</fieldset>
	</div>
	<div class="spacer"></div>
</body>

</html>
