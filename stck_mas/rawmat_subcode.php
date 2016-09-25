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
     
     	$rawmat_maincode = htmlentities(mysql_real_escape_string($_POST['selmaincode']));   
        
     	if ($rawmat_maincode <> "") {
 
      		$var_sql = " SELECT count(*) as cnt from rawmat_subcode ";
      		$var_sql .= " WHERE rm_code = '$rawmatcd1'";
      		$query_id = mysql_query($var_sql) or die ("Cant Check Raw Material Code; ".mysql_error());
      		$res_id = mysql_fetch_object($query_id);

      		if ($res_id->cnt > 0 ) {
	  		   $backloc = "../stck_mas/rawmat_subcode.php?stat=3&menucd=".$var_menucode;
      		   echo "<script>";
      		   echo 'location.replace("'.$backloc.'")';
      		   echo "</script>";      
      		}else {
      
      			if(!empty($_POST['scol']) && is_array($_POST['scol'])) 
				{	
					foreach($_POST['scol'] as $row=>$sicol ) {
						if ($sicol <> ""){
							$siwitdh    = mysql_real_escape_string($_POST['swidth'][$row]);
							$sidensity  = mysql_real_escape_string($_POST['sdensity'][$row]);
							$simoq      = $_POST['smoq'][$row];
							$simcq      = $_POST['smcq'][$row];
							$sminqty      = $_POST['sminqty'][$row];
							$smaxqty      = $_POST['smaxqty'][$row];

							$sissupplt  = $_POST['ssupplt'][$row];
							$sdesc      = $_POST['sdesc'][$row];
							$slocation  = $_POST['slocation'][$row];
							$scost_price= $_POST['scost_price'][$row];


						
							$rawmatcd   =  $rawmat_maincode.$siwitdh.$sicol;
         	   				$vartoday = date("Y-m-d H:i:s");
         	   				if ($simoq == ""){$simoq = 0;}
         	   				if ($simcq == ""){$simcq = 0;}
         	   				if ($sminqty == ""){$sminqty = 0;}
         	   				if ($smaxqty == ""){$smaxqty = 0;}

         	   				if ($sissupplt == ""){$sissupplt = 0;}
         	   				if ($scost_price == ""){$scost_price= 0;}
         	   				$sql = "INSERT INTO rawmat_subcode values 
               						 ('$rawmatcd', '$rawmat_maincode', '$sidensity', '$simoq', '$simcq', '$sminqty', '$smaxqty','$sissupplt', 
               						  '$sicol', '$siwitdh', '$sdesc', '$slocation','$scost_price','$var_loginid', '$vartoday','$var_loginid', '$vartoday','ACTIVE')";	  		  
     	 	  			 	mysql_query($sql) or die(mysql_error());
     	 	  			}
     	 	  		}
     	 	  	}	         
     	 	  $backloc = "../stck_mas/m_rm_subcode.php?stat=1&menucd=".$var_menucode;
         	  echo "<script>";
         	  echo 'location.replace("'.$backloc.'")';
         	  echo "</script>";      
            } 
     	}else{
     	  $backloc = "../stck_mas/rawmat_subcode.php?stat=3&menucd=".$var_menucode;
     	  echo "<script>";
     	  echo 'location.replace("'.$backloc.'")';
     	  echo "</script>";     
     	}
    }
    
   
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<style media="all" type="text/css">
@import "../css/demo_table.css";
@import "../css/styles.css";

thead th input { width: 90% }

.style2 {
	margin-right: 0px;
}
.auto-style1 {
	color: #3366CC;
}
</style>

<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>

<script type="text/javascript" charset="utf-8"> 
$(document).ready(function() {
	$('#example').dataTable( {
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50,"All"]],
		"bStateSave": true,
		"bFilter": true,
		"sPaginationType": "full_numbers",
		"bAutoWidth":false
	})
	
	.columnFilter({sPlaceHolder: "head:after",

		aoColumns: [ 
					 null,	
					 { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     null,
				     null
				   ]
		});	
} );

jQuery(function($) {
  
    $("tr :checkbox").live("click", function() {
        $(this).closest("tr").css("background-color", this.checked ? "#FFCC33" : "");
    });
  
});

function AjaxFunctioncd(suppcd)
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
	
	var url="aja_chk_supp.php";
	
	url=url+"?suppcdg="+suppcd;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",url,true);
	httpxml.send(null);
}	

function AjaxFunction(email)
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
		document.getElementById("msg").innerHTML=httpxml.responseText;
	  }
    }
	
	var url="email-ajax.php";
	
	url=url+"?email="+email;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",url,true);
	httpxml.send(null);
}	
	
	function showUser(str)
	{
	if (str=="")
  	{
 	 document.getElementById("txtHint").innerHTML="";
 	 return;
 	 }
	if (window.XMLHttpRequest)
 	 {// code for IE7+, Firefox, Chrome, Opera, Safari
  	httpxml=new XMLHttpRequest();
  	}
	else
  	{// code for IE6, IE5
  	httpxml=new ActiveXObject("Microsoft.XMLHTTP");
  	}
	httpxml.onreadystatechange=function()
  	{
  	if (httpxml.readyState==4 && httpxml.status==200)
    	{
    	document.getElementById("txtHint").innerHTML=httpxml.responseText;
    	}
  	}
	httpxml.open("GET","aja_get_det.php?q="+str,true);
	httpxml.send();
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

function addRow(tableID) {

	var table = document.getElementById(tableID);
	var rowCount = table.rows.length; 
	var row = table.insertRow(rowCount);             
	var colCount = table.rows[0].cells.length;             

	for(var i = 0; i < colCount; i++) {                 
		var newcell = row.insertCell(i);                 
		newcell.innerHTML = table.rows[rowCount-1].cells[i].innerHTML;
		
		switch(newcell.childNodes[1].type) {
		case "text":  
		    switch(i){
		    case 1:
		        newcell.childNodes[1].value = "";
                newcell.childNodes[1].id = 'switdh'+rowCount;	
               	break;
		    case 2: 
		      	newcell.childNodes[1].value = "";
                newcell.childNodes[1].id = 'sdesc'+rowCount;	
                break;
            case 3:
		      	newcell.childNodes[1].value = "";
                newcell.childNodes[1].id = 'sdensity'+rowCount;	
                break;
			case 4:
		      	newcell.childNodes[1].value = "";
                newcell.childNodes[1].id = 'smoq'+rowCount;	
                break;
			case 5:
		      	newcell.childNodes[1].value = "";
                newcell.childNodes[1].id = 'smcq'+rowCount;	
                break;
            
			case 6:
		      	newcell.childNodes[1].value = "";
                newcell.childNodes[1].id = 'sminqty'+rowCount;	
                break;
			case 7:
		      	newcell.childNodes[1].value = "";
                newcell.childNodes[1].id = 'smaxqty'+rowCount;	
                break; 
                        
            case 8:
		      	newcell.childNodes[1].value = "";
                newcell.childNodes[1].id = 'ssupplt'+rowCount;	
                break; 
            case 9:
		      	newcell.childNodes[1].value = "";
                newcell.childNodes[1].id = 'slocation'+rowCount;	
                break; 
            case 10:
		      	newcell.childNodes[1].value = "";
                newcell.childNodes[1].id = 'scost_price'+rowCount;	
                break; 

		    }    	             
		case "checkbox":                            
			newcell.childNodes[1].checked = false; 
            //newcell.childNodes[0].id = rowCount;			
			break;                    
		case "select-one":                            
			newcell.childNodes[1].selectedIndex = 0; 
            newcell.childNodes[1].id = 'scol'+rowCount;			
			break; 
       	}            
	}
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

function validateForm()
{
	var x=document.forms["InpSuppMas"]["selrmcode"].value;
	if (x==null || x=="")
	{
		alert("Please Select Item Category!");
		document.InpSuppMas.selrmcode.focus();
		return false;
	}
	
	var x=document.forms["InpSuppMas"]["selmaincode"].value;
	if (x==null || x=="")
	{
		alert("Main Code Cannot Be Blank!");
		document.InpSuppMas.selmaincode.focus();
		return false;
	}
	
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length; 
		
	var mylist = new Array();	
	for(var i = 1; i < rowCount; i++) { 
	         
		var idcol = "scol"+i;       
		var e = document.getElementById(idcol);
        var vprocdbuy = e.options[e.selectedIndex].value;
       
		if (vprocdbuy != ""){
			 mylist.push(vprocdbuy); 
		}   
	}
	
	if (mylist.length == 0) {
  		alert("Can't Proceed Without Empty Row Color And Width.");
		document.getElementById("scol1").focus();
		return false;
    }
    
    var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length; 
	
	for(var i = 1; i < rowCount; i++) {
		 var idcol   = "scol"+i; 
		 var idwidth = "switdh"+i;
		 
		 var e = document.getElementById(idcol);
         var vprocol = e.options[e.selectedIndex].value;

         var vprowidth = document.getElementById(idwidth).value;
         
         if (vprocol !== ""){
         	if (vprowidth == ""){
         		alert ("Can't Proceed With Empty Value Of Color Or Width");
         		document.getElementById(idcol).focus();
         		return false
         	}
         }
         
          if (vprowidth !== ""){
         	if (vprocol == ""){
         		alert ("Can't Proceed With Empty Value Of Color Or Width");
         		document.getElementById(idcol).focus();
         		return false
         	}
         } 	   
	}
	
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length; 
		
	var mylist = new Array();	
	for(var i = 1; i < rowCount; i++) { 
	                
		var idcol   = "scol"+i; 
		var idwidth = "switdh"+i;
		 
		var e = document.getElementById(idcol);
        var vprocol = e.options[e.selectedIndex].value;

        var vprowidth = document.getElementById(idwidth).value;
       
		if (vprocol != ""){
			    mylist.push(vprocol+vprowidth); 
		}   
	}
	
	mylist.sort();
	var last = mylist[0];
	
	for (var i=1; i < mylist.length; i++) {
		if (mylist[i] == last){ 
			alert ("Duplicate Color And Width Found; Color " + last);
			return false;
		}	
		last = mylist[i];
	}
	
	//Check the main code, color, width is exist in table rawmat_subcode----------------------------------------------------
	var flgchk = 1;
	var x = document.forms["InpSuppMas"]["selmaincode"].value;

	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length; 
		
	for(var i = 1; i < rowCount; i++) { 
	                
		var idcol   = "scol"+i; 
		var idwidth = "switdh"+i;
		 
		var e = document.getElementById(idcol);
        var vprocol = e.options[e.selectedIndex].value;

        var vprowidth = document.getElementById(idwidth).value;
        var expmaincode = x+vprowidth+vprocol;
        
        if (vprocol !== ""){
			var strURL="aja_chk_subcodeCnt.php?rawmatcdg="+expmaincode;
			
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
							if (req.responseText == 1)
							{
							  flgchk = 0;
							  document.getElementById(idcol).focus();
							  alert ('This Main Code For This Color And Witdh Already Exist : Main Code '+x+ ', Color '+vprocol+', Size'+vprowidth);
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
		}
	}		
	//---------------------------------------------------------------------------------------------------
}

			
function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

function chkValue(vvalue, vid)
{
    var col2 = document.getElementById(vid).value;
	
   	if (col2 != ""){
		if(isNaN(col2)) {
    	   alert('Please Enter a valid number:' + vvalue);
    	   col2 = 0;
    	}
    	document.getElementById(vid).value = col2;
    }
}
	
</script>
</head>
<body OnLoad="document.InpSuppMas.selrmcode.focus();">
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc" style="height: 515px">
	<fieldset name="Group1" style=" width: 1300px; height: 566px;" class="style2">
	 <legend class="title" style="width: 161px">RAW MATERIAL SUB CODE</legend>
	  <form name="InpSuppMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="width: 970px;" onsubmit="return validateForm()">
		<table style="width: 970px;">
	  	  <tr>
	  	    <td style="width: 2px;"></td>
	  	    <td style="width: 175px;">Item Category</td>
	  	    <td style="width: 4px;">:</td>
	  	    <td style="width: 375px;">

			<select name="selrmcode" style="width: 168px" onchange="getState(this.value)">

			    <?php
                   $sql = "select cat_code, cat_desc from cat_master ORDER BY cat_desc ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['cat_code'].'">'.$row['cat_desc'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select>

			</td>
			<td></td>
		    <td style="width: 106px;"></td>
	  	    <td style="width: 6px;"></td>
	  	    <td style="width: 108px;"></td>
	  	  </tr>
	  	  <tr>
	  	    <td style="width: 2px;">
	  	    </td>
	  	    <td style="width: 175px;">Main Code</td>
	  	    <td style="width: 4px;">:</td>
	  	    <td style="width: 375px;">
			 <p id="statedivx" style="width: 249px;"></p>
			</td>
			<td></td>
		    <td style="width: 106px;"></td>
	  	    <td style="width: 6px;"></td>
	  	    <td style="width: 108px;"></td>
	  	  </tr>

	   	   <tr>
	  	    <td colspan="11" rowspan="2">
				<div id="txtHint" align="center" style="height: 85px; width: 800px;"></div>
			</td>
	  	  </tr>
	  	  </table>
	  	  <br><br>
	  	  
		  <table border="0" style="width: 100%;">
			<tr>
				<td class="auto-style1"><strong>Add Colour/Width :</strong></td>
			</tr>
		  </table>
				
		  <table id="itemsTable" class="general-table" style = "width: 100%;">
		  	<thead>
				<tr>
					<th class="tabheader" >Colour</th>
					<th class="tabheader" style="width: 1%" >Width</th>
					<th class="tabheader" style="width: 4%">Description</th>
					<th class="tabheader" style="width: 142px" >Density</th>
					<th class="tabheader" >MOQ</th>
					<th class="tabheader" >MCQ</th>
					<th class="tabheader" >Min Qty</th>
					<th class="tabheader" >Max Qty</th>
					<th class="tabheader" >Supplier Lead<br>&nbsp;Time (Days)</th>
					<th class="tabheader" >Location</th>
					<th class="tabheader" >Cost Price</th>					
				</tr>
			</thead>
			<?php 
				$i = 1;	
				do { ?>
				<tr class="item-row">
                	<td width="50">
                	  <select name="scol[]" id="<?php echo 'scol'.$i;?>" style="width: 180px">	
					  <?php
					  		$sql = "select colour_code, colour_desc from colour_master ORDER BY colour_code ASC";
                   			$sql_result = mysql_query($sql);
                   			echo "<option size =30 selected></option>";
      
				   			if(mysql_num_rows($sql_result)) 
				   			{
				   	 			while($row = mysql_fetch_assoc($sql_result)) 
				     			{ 
					 			 echo '<option value="'.$row['colour_code'].'">'.$row['colour_code']." | ".$row['colour_desc'].'</option>';
				 	 			} 
				   			} 
	            	?>								
	            	</select>
	                </td>
	                
					<td >
					<input name="swidth[]" type="text" id="<?php echo 'switdh'.$i;?>" maxlength="15" style="width: 80px" onchange ="upperCase(this.id)"></td>
					<td style="width: 4%" >
					<input name="sdesc[]" type="text" id="<?php echo 'sdesc'.$i;?>"  maxlength="50" onchange="upperCase(this.id)" style="width: 150px"></td>

                	<td style="width: 142px">
					<input name="sdensity[]" type="text" id="<?php echo 'sdensity'.$i;?>"  maxlength="50" onchange="upperCase(this.id)" style="width: 150px"></td>
					
                	<td >
					<input name="smoq[]" type="text" id="<?php echo 'smoq'.$i;?>" maxlength="10" onchange="upperCase(this.id)" onBlur="chkValue(this.value, this.id);" style="width: 70px"></td>
                	<td >
                	<input name="smcq[]" type="text" id="<?php echo 'smcq'.$i;?>" maxlength="10" onchange="upperCase(this.id)" onBlur="chkValue(this.value, this.id);" style="width: 70px"></td>
					
					<td >
					<input name="sminqty[]" type="text" id="<?php echo 'sminqty'.$i;?>" maxlength="10" onchange="upperCase(this.id)" onBlur="chkValue(this.value, this.id);" style="width: 70px"></td>
                	<td >
                	<input name="smaxqty[]" type="text" id="<?php echo 'smaxqty'.$i;?>" maxlength="10" onchange="upperCase(this.id)" onBlur="chkValue(this.value, this.id);" style="width: 70px"></td>
					
					
					<td >
					<input name="ssupplt[]" type="text" id="<?php echo 'ssupplt'.$i;?>" maxlength="10" onchange="upperCase(this.id)" onBlur="chkValue(this.value, this.id);" style="width: 87px"></td>  
					<td width="50">
                	  <select name="slocation[]" id="<?php echo 'slocation'.$i;?>" style="width: 180px">	
					  <?php
					  		$sql = "select loca_code, loca_desc from stk_location ORDER BY loca_desc ASC";
                   			$sql_result = mysql_query($sql);
                   			echo "<option size =20 selected></option>";
      
				   			if(mysql_num_rows($sql_result)) 
				   			{
				   	 			while($row = mysql_fetch_assoc($sql_result)) 
				     			{ 
					 			 echo '<option value="'.$row['loca_code'].'">'.$row['loca_desc'].'</option>';
				 	 			} 
				   			} 
	            	?>								
	            	</select>
	                </td>

					
					<td>
					<input name="scost_price[]" type="text" id="<?php echo 'scost_price'.$i;?>" maxlength="10" onchange="upperCase(this.id)" onBlur="chkValue(this.value, this.id);" style="width: 87px"></td>
              
         </tr>
               <?php 
               		$i = $i + 1;
               }while($i <= 5);?>

	  	 </table>
	  	 
	  	  <a href="javascript:addRow('itemsTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Row</span></a>
         <a href="javascript:deleteRow('itemsTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>

  		 <table style="width:1276px">	
          <tr>
          	<td style="height: 38px;" align="center">
	  	   	<?php
	  	   		 $locatr = "m_rm_subcode.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
	  	   		include("../Setting/btnsave.php");
	  	    ?>
	  	   	</td>
	  	  </tr>
	  	 </table>
	  	 
		<table> 
		  <tr>
	  	     <td style="width: 505px" colspan="7"><span style="color:#FF0000">Message :</span>
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
				    		echo("<span>Fail! Duplicated Found</span>");
  							break;
  						case 4:
				    		echo("<span>Please Fill In The Data To Save</span>");
  							break;
						default:
  							echo "";
						}
			  		}	
				?>
           </td>
	  	  </tr>
	  	</table>
	   </form>	
  
	</fieldset>
    </div>
    <div class="spacer"></div>
</body>

</html>
