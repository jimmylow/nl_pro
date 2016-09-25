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
    
      $var_po = $_GET['po'];
      $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");

    }
    
    if ($_POST['Submit'] == "Update") {
    
   		$vmpo      = $_POST['po'];
		$vmsuppid  = $_POST['supp'];
		$vmpodte   = date('Y-m-d', strtotime($_POST['podte']));
		$vmterms   = $_POST['terms'];
		$vmorderno = $_POST['orderno'];
		$vmstyleno = $_POST['styleno'];
		$vmdeldte  = date('Y-m-d', strtotime($_POST['deldte']));
		$vmremark  = $_POST['remark'];
		$vmcstno   = $_POST['cstno'];      
            
		if ($vmpo <> "") {
    			
        $vartoday = date("Y-m-d H:i:s"); 
        
				$sql = "Update po_master Set supplier = '$vmsuppid', po_date ='$vmpodte', terms='$vmterms', ";
				$sql .= "                    order_no = '$vmorderno', style_no ='$vmstyleno', del_date='$vmdeldte', ";
				$sql .= "                    remark = '$vmremark', modified_by = '$var_loginid', modified_on='$vartoday', costingno= '$vmcstno' ";
				$sql .= "  Where po_no ='$vmpo'";
        
				mysql_query($sql) or die ("Cant update : ".mysql_error());
        
				$sql =  "Delete From po_trans";
				$sql .= "  Where po_no ='$vmpo'";
				mysql_query($sql) or die ("Cant delete details : ".mysql_error());
				
				$sql =  "Delete From costing_itmpurbok";
				$sql .= "  Where pono ='$vmpo'";
				mysql_query($sql) or die ("Cant BOM Tables : ".mysql_error());         
				
				if(!empty($_POST['prococode']) && is_array($_POST['prococode'])) 
				{	
					foreach($_POST['prococode'] as $row=>$matcd ) {
						$matcode   = $matcd;
						$matseqno  = $_POST['seqno'][$row];
						$matdesc   = mysql_real_escape_string($_POST['procodesc'][$row]);
						$matuom    = $_POST['procouom'][$row];
						$matqty    = $_POST['procoqty'][$row];
						$matuprice = $_POST['procoprice'][$row];

					
						if ($matcode <> "")
						{
							if(empty($matqty)){$matqty = 0;}
							if(empty($matuprice)){$matuprice = 0;}
							$sql = "INSERT INTO po_trans values 
						    		('$vmpo', '$matcode', '$matqty', '$matuprice','$matseqno', '$matdesc', '$matuom')";
							mysql_query($sql) or die ("Cant insert : ".mysql_error());
							
							if (!empty($vmcstno)){
								$sql  = "INSERT INTO costing_itmpurbok "; 
								$sql .=	" (costing_no, itmcd, pono, poqty)";
								$sql .= " values"; 
						    	$sql .= "  ('$vmcstno', '$matcode', '$vmpo', '$matqty')";
								mysql_query($sql) or die ("Cant insert : ".mysql_error());
							}

           				}	
					}
				}
				
				$backloc = "../pur_ord/m_po.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 	
					
		}else{
			$backloc = "../pur_ord/po.php?stat=4&menucd=".$var_menucode;
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

.general-table #prococode                        { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}
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

function setup() {

		document.InpPO.supp.focus();
				
 		//Set up the date parsers
        var dateParser = new DateParser("dd-MM-yyyy");
        
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("dd-MM-yyyy", "podte");
		dateMask1.validationMessage = errorMessage;
		
		
		var dateMask2 = new DateMask("dd-MM-yyyy", "deldte");
		dateMask2.validationMessage = errorMessage;  
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

    var x=document.forms["InpPO"]["supp"].value;
	if (x==null || x=="s")
	{
	alert("Supplier Must Not Be Blank");
	document.InpPO.supp.focus;
	return false;
	}

   var x=document.forms["InpPO"]["podte"].value;
	if (x==null || x=="")
	{
	alert("Date Must Not Be Blank");
	document.InpPO.podte.focus;
	return false;
	}

	var x=document.forms["InpPO"]["deldte"].value;
	if (x==null || x=="")
	{
	alert("Delivery Date Must Not Be Blank");
	document.InpPO.deldte.focus;  
	return false;
	}

  
	//Check the list of mat item no is Valid-------------------------------------------------------------
	var flgchk = 1;	
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
         
    for (var j = 1; j < rowCount; j++){
       var idrowItem = "prococode"+j;
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

	    var idrowItem = "prococode"+j;
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
  
}


function showoveDecimal(vterms){
 	if (vterms != ""){
		if(isNaN(vterms)) {
    	   alert('Please Enter a number for Terms :' + vterms);
    	   document.InpPO.terms.focus();
    	   return false;
    	}
    }
}

function showsupplier(str)
{

var rand = Math.floor(Math.random() * 101);

if (str=="s")
  {
  alert ("Please choose a supplier to continue");
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
    document.getElementById("suppadd").value=xmlhttp.responseText; 
    
    }
  }
xmlhttp.open("GET","getsupplier.php?q="+str+"&m="+rand,true);
xmlhttp.send();
get_term(str);
}

function get_term(supp)
{
   var strURL="aja_get_suppterm.php?s="+supp;

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
        	var json = req.responseText
            obj = JSON.parse(json);

	    	document.getElementById("terms").value = obj.a; 
	    	document.getElementById("termdesc").innerHTML = obj.b; 
	    		
	 	}else{
   	   		alert("There was a problem while using XMLHTTP:\n" + req.statusText);
	 	}
      }
     }
   	req.open("GET", strURL, true);
   	req.send(null);
   }

}



function getUprice(str)
{

 var rand = Math.floor(Math.random() * 101);
 var suppinfo = document.getElementById("supp").value;
 var iteminfo = document.getElementById("prococode"+str).value;
 var qty      = document.getElementById("procoqty"+str).value; 

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
    var priamt = xmlhttp.responseText;
    
    var result = priamt.split("k");
    document.getElementById("procoprice"+str).value=result[0]; 
    document.getElementById("procoamount"+str).value=result[1];   
    }
  }
xmlhttp.open("GET","getpoprice.php?s="+suppinfo+"&i="+iteminfo+"&q="+qty+"&m="+rand,true);
xmlhttp.send();
}

</script>
</head>

<body onload="setup()">
	  <?php include("../topbarm.php"); ?> 
<!--  <?php include("../sidebarm.php"); ?> -->

  <?php
  	 $sql = "select * from po_master";
     $sql .= " where po_no ='".$var_po."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $supplier = $row['supplier'];
     $po_date = date('d-m-Y', strtotime($row['po_date']));
     $terms = $row['terms'];
     $order_no = htmlentities($row['order_no']);
     $style_no = htmlentities($row['style_no']);
     $del_date = date('d-m-Y', strtotime($row['del_date']));
     $remark   = htmlentities($row['remark']);
     $cstno    = htmlentities($row['costingno']);
     
     $sql="SELECT address_1_1, address_2_1, telephone_1, telephone_2, country_1, city_1, postal_code_1, state_1, fax1, fax2  FROM supplier_master ";
     $sql .= " where supplier_code = '".$supplier."'";

     $result = mysql_query($sql) or die ("Error : ".mysql_error());

     $data = mysql_fetch_object($result);

     $var_add = "";
     
     if (!empty($data->address_1_1)) { $var_add .= $data->address_1_1.", \n"; }
     if (!empty($data->address_2_1)) { $var_add .= $data->address_2_1.", \n"; }
     if (!empty($data->postal_code_1)) { $var_add .= $data->postal_code_1.","; }      
     if (!empty($data->city_1)) { $var_add .= $data->city_1.", \n"; } 
     if (!empty($data->state_1)) { $var_add .= $data->state_1." \n"; } 
     $var_add .= "\nTel : "; 
     if (!empty($data->telephone_1)) { $var_add .= $data->telephone_1.","; }  
     if (!empty($data->telephone_2)) { $var_add .= $data->telephone_2; }   
     $var_add .= "\nFax : "; 
     if (!empty($data->fax1)) { $var_add .= $data->fax1.","; }  
     if (!empty($data->fax2)) { $var_add .= $data->fax2; }

	 if ($terms == ""){
	
		$sql1  = "select terms from supplier_master where supplier_code = '$supplier'";
		$sql_result1 = mysql_query($sql1) or die(mysql_error());
		$row1 = mysql_fetch_array($sql_result1);
		$terms = $row1['terms'];
	 }
	$var_sql = " SELECT term_desc from term_master";
     $var_sql .= " WHERE term_code = '$terms'";
     $result=mysql_query($var_sql);
     $row = mysql_fetch_array($result);
     $termde = $row[0];
    
     
  ?> 
  
  <div class="contentc">

	<fieldset name="Group1" style=" width: 849px;" class="style2">
	 <legend class="title">PURCHASE ORDER</legend>
	  <br>	 
	  
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 838px"; border="0">
	   	   <tr>
	   	    <td></td>
	  	    <td style="width: 110px">Supplier</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 300px">
           		<select class= "inputtxt" name="supp"  id = "supp" onchange="showsupplier(this.value)" >
          		 <option value ="s">-SELECT-</option>

          		 <?php 
                  $sql="SELECT supplier_code, supplier_desc FROM supplier_master ";
                  $sql .= " where active_flag = 'ACTIVE'"; 
                  $sql .= " order by supplier_code";

                  $result = mysql_query($sql);

                  while($row = mysql_fetch_array($result))
                     {
                       echo "<option value= '".$row['supplier_code']."'";
                       if ($supplier == $row['supplier_code']) { echo "selected"; }
                       echo ">".$row['supplier_desc']." - ".$row['supplier_code'];
                       echo "</option>";
                     }                  
          		 ?> 
          		 </select>
			</td>    
			<td style="width: 29px"></td>
			<td style="width: 243px">P.O. #</td>
			<td style="width: 16px">:</td>
			<td style="width: 270px">
		   <input class="inputtxt" readonly="readonly" type="text" name="po"  id = "po" style="width: 128px;" value="<?php  echo $var_po; ?>"  >
		   </td>        
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	   <td ></td>
	  	   <td ></td>
	  	   <td ></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td rowspan="8"></td>
         <td colspan="2" rowspan="6">
          <textarea class="inputtxt" name="suppadd" id="suppadd" COLS=35 ROWS=8><?php echo $var_add; ?></textarea>
         </td>
			<td style="width: 29px"></td>
			<td style="width: 243px">P.O Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 270px">
		   <input class="inputtxt" name="podte" id ="podte" type="text" style="width: 128px;" value="<?php  echo $po_date; ?>"  >
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('podte','ddMMyyyy')" style="cursor:pointer">
		   </td>       
	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td ></td>
	  	   <td style="width: 243px" ></td>
	  	   <td ></td>
	  	  </tr>
		  <tr>
		   <td></td>
       <td></td>
		   <td style="width: 243px" >Terms</td>
		   <td>:</td>
		   <td>
		   <input name="terms" id ="terms" type="text" style="width: 83px;" value = "<?php echo $terms; ?>" >
		   <label id="termdesc"><?php echo $termde?></label>
		   </td>
		  </tr> 
		  <tr>
	  	   <td></td>
		   <td></td>
		   <td style="width: 243px" >Order No</td>
		   <td>:</td>
		   <td>
		   <input name="orderno" id ="orderno" type="text" style="width: 156px; "  class="inputtxt" onchange ="upperCase(this.id)"  value="<?php echo $order_no; ?>"></td>
	  	  </tr>
		  <tr>
	  	   <td></td>
		   <td></td>
		   <td style="width: 243px" >Style No</td>
		   <td>:</td>
		   <td>
		   <input name="styleno" id ="styleno" type="text" style="width: 156px;"  class="inputtxt" onchange ="upperCase(this.id)" value="<?php echo $style_no; ?>"></td>
	  	  </tr>
  	  	  <tr>
  	  	  	<td></td>
  	  	  	<td></td>
  	  	  	<td style="width: 243px"><span>Planning</span> No</td>
  	  	  	<td>:</td>
  	  	  	<td>
  	  	  		<select class= "texta" name="cstno"  id = "cstno" style="width: 140px">
           			<option value ="<?php echo $cstno; ?>" selected="selected"><?php echo $cstno; ?></option>
           			<option></option>
           			<?php 
                  		$sql="SELECT costingno FROM costing_mat ";
                  		$sql .= " where actvty = 'ACTIVE'"; 
                  		$sql .= " order by costingno";
                  		$result = mysql_query($sql);

                  		while($row = mysql_fetch_array($result))
                     	{
                       		echo "<option value= '".$row['costingno']."'";
                       echo ">".$row['costingno'];
                       echo "</option>";
                     }                  
           ?> 
           	</select>

  	  	  	</td>
  	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td></td>
		   <td></td>
		   <td></td>
			<td style="width: 243px" >Delivery Date</td>
			<td >:</td>
			<td >
		   <input class="inputtxt" name="deldte" id ="deldte" type="text" style="width: 128px;" value="<?php  echo $del_date; ?>" >
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('deldte','ddMMyyyy')" style="cursor:pointer">
		   </td>       
	  	  </tr>
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader">Item Code</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">Quantity</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Unit Price</th>
              <th class="tabheader">Amount</th>
             </tr>
            </thead>
            <tbody>
             <?php
             	$sql = "SELECT * FROM po_trans";
             	$sql .= " Where po_no='".$var_po."'"; 
	    		$sql .= " ORDER BY seqno";  
				$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){ 
        
             $var_amt = $rowq['qty'] * $rowq['uprice']; 
                   
                                          
             ?>            
             <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="<?php echo $i; ?>" readonly="readonly" style="width: 27px; border:0;" value ="<?php echo $i; ?>"></td>
                <td>
				<input name="prococode[]" tProItem1=1 id="prococode<?php echo $i; ?>" tabindex="0" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)" value ="<?php echo htmlentities($rowq['itemcode']); ?>"></td>
                <td>
				<input name="procodesc[]" class="tInput" id="procodesc" style="width: 303px;" value ="<?php echo $rowq['itmdesc']; ?>"></td>
                <td>
				<input name="procoqty[]" id="procoqty1" style="width: 48px; text-align : right" onBlur="getUprice(<?php echo $i; ?>);" value ="<?php echo $rowq['qty']; ?>"></td>
                <td>
				<input name="procouom[]" id="procouom" class="tInput" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 75px" value ="<?php echo $rowq['itmuom']; ?>"></td>
                <td>
				<input name="procoprice[]" class="tInput" id="procoprice<?php echo $i; ?>" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 75px; text-align : right" value ="<?php echo $rowq['uprice']; ?>"></td>
                <td>
				<input name="procoamount[]" class="tInput" id="procoamount<?php echo $i; ?>" readonly="readonly" style="width: 75px; border:0; text-align : right" value ="<?php echo number_format($var_amt,2,'.',' '); ?>"></td>
             </tr>
             
          <?php 
          
                	$i = $i + 1;          
          
             } // while
          ?>     
          <?php
            if ($i == 1){ ?>
            	 <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="<?php echo $i; ?>" readonly="readonly" style="width: 27px; border:0;" value ="<?php echo $i; ?>"></td>
                <td>
				<input name="prococode[]" tProItem1=1 id="prococode<?php echo $i; ?>" tabindex="0" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)" value ="<?php echo htmlentities($rowq['itemcode']); ?>"></td>
                <td>
				<input name="procodesc[]" class="tInput" id="procodesc" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 303px;" value ="<?php echo $row1['description']; ?>"></td>
                <td>
				<input name="procoqty[]" id="procoqty1" style="width: 48px; text-align : right" onBlur="getUprice(<?php echo $i; ?>);" value ="<?php echo $rowq['qty']; ?>"></td>
                <td>
				<input name="procouom[]" id="procouom" class="tInput" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 75px" value ="<?php echo $rowq['uom']; ?>"></td>
                <td>
				<input name="procoprice[]" class="tInput" id="procoprice<?php echo $i; ?>" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 75px; text-align : right" value ="<?php echo $rowq['uprice']; ?>"></td>
                <td>
				<input name="procoamount[]" class="tInput" id="procoamount<?php echo $i; ?>" readonly="readonly" style="width: 75px; border:0; text-align : right" value ="<?php echo number_format($var_amt,2,'.',' '); ?>"></td>
             </tr>
		  <?php
            }
          ?>         
            </tbody>
           </table>
           
         <a href="#" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Item</span></a>
         <a href="javascript:deleteRow('itemsTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>

     <br /><br />
		 <table>
			<tr>
				<td valign="top">Remarks :</td>
        <td><textarea class="inputtxt" name="remark" id="remark1" COLS=60 ROWS=5><?php echo $remark; ?></textarea></td>
			</tr>
    </table>  

     <br /><br />
     
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_po.php?menucd=".$var_menucode;
			
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
          
         mysql_close ($db_link); 
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
