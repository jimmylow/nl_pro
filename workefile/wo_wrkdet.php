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
     $wrkid     = $_POST['wrkid'];
     $status    = $_POST['selactive'];
     $wrkname   = mysql_real_escape_string($_POST['wrkname']);
     $wrkdept   = $_POST['seldept'];
     $wrktype   = mysql_real_escape_string($_POST['wrktype']);
     $wrkfnl    = $_POST['selfnl'];
     $wrkprate  = $_POST['selpayrate'];
     $wrkgrp    = $_POST['selgrp'];
     $wrkctry  = $_POST['selcountry'];

     
     $sql  = "select deptdesc";
   	 $sql .= " from wor_deptmas";
   	 $sql .= " where deptcode ='$wrkdept'";   
   	 $sql_result = mysql_query($sql) or die(mysql_error());
   	 $row = mysql_fetch_array($sql_result);
	 $deptde = mysql_real_escape_string($row[0]);
	 
	 $sql  = "select grpde";
   	 $sql .= " from wor_grpmas";
   	 $sql .= " where grpcd ='$wrkgrp'";   
   	 $sql_result = mysql_query($sql) or die(mysql_error());
   	 $row = mysql_fetch_array($sql_result);
	 $grpde = mysql_real_escape_string($row[0]);
	 
 	 #----------------------Exe Picture Product Code-----------------------------------------------
     if ($_FILES['uploadfile']['name'] <> "") {  
	 	$dir = '../workefile/wrkimg';
	 	$imgnm = htmlentities($wrkid);
	 	include("../Setting/uploadFuc.php");
	 }
	 #---------------------------------------------------------------------------------------------  

     $vartoday = date("Y-m-d");
     $sql = "INSERT INTO wor_detmas "; 
     $sql .= " (workid, workname, deptcd, deptde, worktyp, payratecd, ";
     $sql .= "  worfnl, status, grpcd, grpde, create_by, create_on, ";
     $sql .= "  modified_by, modified_on, imgname, country) values ";
     $sql .= " ('$wrkid ', '$wrkname', '$wrkdept','$deptde', ";
     $sql .= "  '$wrktype','$wrkprate','$wrkfnl','$status','$wrkgrp', ";
     $sql .= "  '$grpde', '$var_loginid', '$vartoday', ";
     $sql .= "   '$var_loginid','$vartoday', '$imagename', '$wrkctry') ";
     mysql_query($sql) or die("error :".mysql_error());
     
	if(!empty($_POST['procomat']) && is_array($_POST['procomat'])) 
	{	
		foreach($_POST['procomat'] as $row=>$matcd ) {
			$jobid    = $matcd;
			$seqno    = $_POST['seqno'][$row];
			$jobrate  = $_POST['openingcost'][$row];
		
			if ($jobid  <> "")
			{
				if ($openingcost== ""){ $openingcost= 0;}
				$sql = "INSERT INTO wor_jobrate values 
			    		('$wrkid', '$jobid', '$jobrate','$seqno')";
				mysql_query($sql) or die("query 2 :".mysql_error());
				
			}	
		}
	}

           
     $backloc = "../workefile/wo_m_wrkdet.php?menucd=".$var_menucode;
     echo "<script>";
     echo 'location.replace("'.$backloc.'")';
     echo "</script>";      
   }   
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<style media="all" type="text/css">
@import "../css/styles.css";
@import "../css/demo_table.css";

.style2 {
	margin-right: 0px;
}
.style4 {
	color: #FF0000;
	font-weight:bold;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>

<style media="all" type="text/css">
@import url('../css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css');
@import url('../css/styles.css');

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
<script  type="text/javascript" src="../workefile/jq-ac-script-wpay.js"></script>



<script type="text/javascript" charset="utf-8"> 
function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
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

function AjaxFunctioncd(x){

   if (x != ""){	
   	var strURL="aja_chk_wrkid.php?de="+x;
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
   	        	if (req.responseText == 1){    	
					document.getElementById("msgcd").innerHTML = "<font color='red'>This Worker ID Has Been Create</font>";
		    	}else{
		    		document.getElementById("msgcd").innerHTML = "";
		    	}
		    }else{
   	      		alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
	     	}
      }
     } 
   	}
   	req.open("GET", strURL, true);
   	req.send(null);
   }	
}

function deleteRow(tableID) {
	try {
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;
         
        if (rowCount > 2){
             table.deleteRow(rowCount - 1);
        }else{
             alert ("No More Row To Delete");
        }
	}catch(e) {
		alert(e);
	}
}

	
function validateForm()
{
    var x=document.forms["InpWrkMas"]["wrkid"].value;
	if (x==null || x==""){
		alert("Worker ID Cannot Be Blank");
		document.InpWrkMas.wrkid.focus();
		return false;
	}
	
	var x=document.forms["InpWrkMas"]["wrkname"].value;
	if (x==null || x==""){
		alert("Worker Name Cannot Be Blank");
		document.InpWrkMas.wrkname.focus();
		return false;
	}
	
	var x=document.forms["InpWrkMas"]["seldept"].value;
	if (x==null || x==""){
		alert("Department Cannot Be Blank");
		document.InpWrkMas.seldept.focus();
		return false;
	}
	
	var x=document.forms["InpWrkMas"]["selpayrate"].value;
	if (x==null || x==""){
		alert("Payrate Cannot Be Blank");
		document.InpWrkMas.selpayrate.focus();
		return false;
	}
	
	var x=document.forms["InpWrkMas"]["selgrp"].value;
	if (x==null || x==""){
		alert("Group Cannot Be Blank");
		document.InpWrkMas.selgrp.focus();
		return false;
	}
	
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

	
	var flgchk = 1;
	var x=document.forms["InpWrkMas"]["wrkid"].value;
	var strURL="aja_chk_wrkid.php?de="+x;
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
					  alert ('This Worker ID Has Been Created');
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
	   document.InpWrkMas.wrkid.focus();	
	   return false;
	}
}

function readURL(input) {

	if (input.files && input.files[0]) {
       var reader = new FileReader();

       reader.onload = function (e) {
       $('#proimgpre')
          .attr('src', e.target.result)
          .width(130)
          .height(120);
       };
       reader.readAsDataURL(input.files[0]);
       
    }
}	
</script>
</head>
 
  <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="document.InpWrkMas.wrkid.focus();">
  <?php include("../topbarm.php"); ?> 
  <div class="contentc">

	<fieldset name="Group1" style="width: 900px; height: 800px;" class="style2">
	 <legend class="title">WORKER DETAIL MASTER</legend>
	  <br>
	
	  <form name="InpWrkMas" method="POST" onsubmit="return validateForm()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" enctype="multipart/form-data"  style="height: 134px; width: 815px;">
	   <table>
	    <tr>
	      <td></td>
	      <td class="tdlabel" style="width: 111px">Worker ID<span class="style4"> *</span></td>
	      <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="wrkid" id ="wrkid" type="text" maxlength="10" onchange ="upperCase(this.id)" style="width: 94px" onBlur="AjaxFunctioncd(this.value);">
		  </td>
		  <td style="width: 30px"></td>
		  <td class="tdlabel" style="width: 84px">Status</td>
	  	  <td>:</td>
	  	  <td>
		   <select name="selactive" style="width: 165px">
		    <option value="A">ACTIVE</option>
		    <option value="Z">DEACTIVATE</option>
		   </select>
		  </td>
	  	 </tr>
	  	 <tr>
	  	  <td></td> 
	  	  <td style="width: 111px"></td>
	  	  <td></td> 
          <td><div id="msgcd"></div></td>
	   	 </tr> 
	   	 <tr>
	   	  <td></td>
	  	  <td class="tdlabel" style="width: 111px">Worker Name
		  <span class="style4">*</span></td>
	  	  <td>:</td>
	  	  <td colspan="5">
		  <input class="inputtxt" name="wrkname" id ="wrkname" type="text" maxlength="100" onchange ="upperCase(this.id)" style="width: 459px">
		  </td>
	  	 </tr>
	  	 <tr><td></td></tr>
         <tr>
          <td></td>
          <td style="width: 111px">Department</td>
          <td>:</td>
          <td>
			  <select name="seldept" style="width: 289px">
			    <?php
                   $sql = "select deptcode, deptdesc from wor_deptmas ORDER BY deptcode";
                   $sql_result = mysql_query($sql);
                   echo "<option selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['deptcode'].'">'.$row['deptcode']." - ".htmlentities($row['deptdesc']).'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select></td>
		  <td style="width: 30px"></td>
		  <td style="width: 84px">Type</td>
		  <td>:</td>
		  <td>
			<input class="inputtxt" name="wrktype" id ="wrktype" type="text" maxlength="50" style="width: 225px"></td> 	
         </tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td style="width: 111px">Local/Foreign</td>
          <td>:</td>
          <td>
			 <select name="selfnl" style="width: 125px">
			    <option>LOCAL</option>
			    <option>FOREIGN</option>
			</select></td>
          <td style="width: 30px"></td>
          <td style="width: 84px">Payrate</td>
          <td>:</td>
          <td>
			<select name="selpayrate" style="width: 165px">
			    <?php
                   $sql = "select paycode, paydesc from wor_payrate ORDER BY paycode";
                   $sql_result = mysql_query($sql);
                   echo "<option selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['paycode'].'">'.$row['paycode']." - ".$row['paydesc'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select></td>
         </tr>
           <tr>
			   <td></td>
		   </tr>
		   <tr>
          <td></td>
          <td style="width: 111px">Country</td>
          <td>:</td>
          <td>
			<select name="selcountry" style="width: 165px">
			    <?php
                   $sql = "select countrycode, countrydesc from wor_country ORDER BY countrycode";
                   $sql_result = mysql_query($sql);
                   echo "<option selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['countrycode'].'">'.$row['countrycode']." - ".$row['countrydesc'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select></td>
          <td style="width: 30px"></td>
          <td style="width: 84px">&nbsp;</td>
          <td>&nbsp;</td>
          <td>
			&nbsp;</td>
           </tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td style="width: 111px">Group</td>
          <td>:</td>
          <td>
			  <select name="selgrp" style="width: 289px">
			    <?php
                   $sql = "select grpcd, grpde from wor_grpmas ORDER BY grpcd";
                   $sql_result = mysql_query($sql);
                   echo "<option selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['grpcd'].'">'.$row['grpcd']." - ".$row['grpde'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select></td>
          <td style="width: 30px"></td>
          <td style="width: 84px" colspan="3" rowspan="5">
	  	   <img id="proimgpre" height="120" width="130" src="#"></td>
          <td></td>
         </tr>
         <tr><td></td></tr>
         <tr>
         	<td></td>
         	<td>Worker Picture</td>
         	<td>:</td>
         	<td>
		   <input name="uploadfile" style="width: 315px" type="file" onchange="readURL(this);"></td>
         </tr>
         <tr><td></td></tr>
         <tr><td></td></tr>
         <tr><td></td></tr>
         <tr>
         	<td colspan="8" align="left">
         		<?php
         			$locatr = "wo_m_wrkdet.php?menucd=".$var_menucode;
				 	echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
	  	   			include("../Setting/btnsave.php");
	  	   		?>
         	</td>
         </tr>
        </table>
        
        <table id="itemsTable" class="general-table" style = "width: 900px;">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader">Job ID</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">Rate</th>

             </tr>
            </thead>
            <tbody>
            
             <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="1" readonly="readonly" style="width: 27px; border:0;"></td>
                <td>
				<input name="procomat[]" id="procomat1" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)" onblur="get_desc(this.value, '1')"></td>
                <td>
				<input name="procodesc[]" id="procodesc1" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 303px;"></td>
				<td>
				<input name="openingcost[]" id="openingcostid1" onBlur="calcCost('1');" style="width: 75px"></td> 
             </tr>
            </tbody>
           </table>
           
         <a href="#" id="addRow" class="button-clean large"><span>
		  <img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Item</span></a>
         <a href="javascript:deleteRow('itemsTable')" id="addRow" class="button-clean large"><span>
		  <img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>

	   </form>	
	   </fieldset>

	</div>
	 <div class="spacer"></div>
</body>

</html>
