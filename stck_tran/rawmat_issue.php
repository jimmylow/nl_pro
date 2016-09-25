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

		$refno ='';
		$prorevdte= date('Y-m-d', strtotime($_POST['prorevdte']));
		$labelno = $_POST['labelno'];
		$sendto = mysql_real_escape_string($_POST['sendto']);
		$requestby = mysql_real_escape_string($_POST['requestby']);	            
            
		if ($sendto <> "") {
		
			$sysno = '';
     		$sqlchk = " select sysno from system_number ";
     		$sqlchk.= " where type = 'ISSUE'";
     
     		$dumsysno= mysql_query($sqlchk) or die(mysql_error());
     		while($row = mysql_fetch_array($dumsysno))
     		{
     			$sysno = $row['sysno'];        
     		}
     		if ($sysno ==NULL)
     		{
     			$sysno = '0';
     					$sysno_sql = "INSERT INTO system_number values ('ISSUE', '$sysno')";

     			mysql_query($sysno_sql) or die ("Query 1 :".mysql_error());

     		}
     		$newsysno = $sysno + 1;
     		
     		$issue_sysno  = str_pad($newsysno , 6, '0', STR_PAD_LEFT);
     		$issue_sysno = "ISS".$issue_sysno;


         	$vartoday = date("Y-m-d H:i:s");
				$sql = "INSERT INTO rawmat_issue values 
						('$issue_sysno', '$refno', '$labelno','$prorevdte','$requestby','$sendto', 
						 '$var_loginid', '$vartoday','$var_loginid', '$vartoday')";
				mysql_query($sql) or die ("Query 2 :".mysql_error());
				
				if(!empty($_POST['procomat']) && is_array($_POST['procomat'])) 
				{	
					foreach($_POST['procomat'] as $row=>$matcd ) {
						$matcode    = $matcd;
						$seqno      = $_POST['seqno'][$row];
						$matdesc    = mysql_real_escape_string($_POST['procodesc'][$row]);
						$matuom     = mysql_real_escape_string($_POST['procouom'][$row]);
						$issueqty   = $_POST['issueqty'][$row];
						$onhandbal  = $_POST['procomark'][$row];
						$negissueqty = 0 - $issueqty;
					
						if ($matcode <> "")
						{
							if ($issueqty== ""){ $issueqty= 0;}
							if ($onhandbal== ""){ $onhandbal= 0;}
							echo $sql;
							$sql = "INSERT INTO rawmat_issue_tran values 
						    		('$issue_sysno', '$seqno','$labelno', '$matcode', '$matdesc', '$matuom','$issueqty','$onhandbal')";
							mysql_query($sql) or die ("Query 3 :".mysql_error());
							
							$sql = "INSERT INTO rawmat_tran values 
						    		('ISS', '$issue_sysno', '$refno','$labelno', '$prorevdte', '$matcode', '0', '$matdesc', '0', '$negissueqty','$var_loginid', '$vartoday','$var_loginid', '$vartoday')";
							mysql_query($sql) or die ("Query 4 :".mysql_error());

           				}	
					}
				}
				
				$updsysno_sql = "UPDATE system_number SET sysno = '$newsysno' WHERE type = 'ISSUE'";

		     	 mysql_query($updsysno_sql) or die ("Query 5 :".mysql_error());
				
				$backloc = "../stck_tran/m_rm_issue.php?menucd=".$var_menucode;
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
<script  type="text/javascript" src="jq-ac-script-issue.js"></script>


<script type="text/javascript"> 
function setup() {

		document.InpJobFMas.prorevdte.focus();
						
 		//Set up the date parsers
        var dateParser = new DateParser("dd-MM-yyyy");
      
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("dd-MM-yyyy", "prorevdte");
		dateMask1.validationMessage = errorMessage;		
}

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

function calcCost(vid)
{
	
    var vissueqtyid = "issueqtyid"+vid;
    var col1 = document.getElementById(vissueqtyid).value;
	
	var totjob = 0;
	
	if (col1 != ""){
		if(isNaN(col1)) {
    	   alert('Please Enter a valid number for Issue Qty :' + col1);
    	   col1 = 0;
    	}
    	document.getElementById(vissueqtyid).value = parseFloat(col1).toFixed(2);
    }
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

	var x=document.forms["InpJobFMas"]["labelno"].value;
	if (x==null || x=="")
	{
	alert("Lable No Must Not Be Blank");
	document.InpJobFMas.labelno.focus();
	return false;
	}
	
	var x=document.forms["InpJobFMas"]["sendto"].value;
	if (x==null || x=="")
	{
	alert("Send To Must Not Be Blank");
	document.InpJobFMas.sendto.focus();
	return false;
	}
	
	var x=document.forms["InpJobFMas"]["requestby"].value;
	if (x==null || x=="")
	{
	alert("Request By Must Not Be Blank");
	document.InpJobFMas.requestby.focus();
	return false;
	}
	
	// to chk issue qty is not more than onhand qty---------------------------------------------------
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
	var mylist = new Array();	    

	for (var j = 1; j < rowCount; j++){
	    var idrowItem = "procomat"+j; // raw mat item
        var rowItem = document.getElementById(idrowItem).value;	 
        
        var idrowItem2 = "issueqtyid"+j; // issue qty
        var rowItem2 = document.getElementById(idrowItem2).value;	
        
        var idrowItem3 = "procomark"+j; //onhand qty
        var rowItem3 = document.getElementById(idrowItem3).value;

		if (parseFloat(rowItem3) < 0 ){			
			alert ("Onhand Balance For Item " + rowItem + " is Negative");		   
		    return false;
		}
		
		if (parseFloat(rowItem3) == 0 ){			
			alert ("Cannot Issue Item " +rowItem + ". Onhand Balance is ZERO ");		   
		    return false;
		}		
		
		if (parseFloat(rowItem2) == 0 ){			
			alert ("Issue Qty Cannot Be ZERO For Item : " +rowItem);		   
		    return false;
		}	       
       
		if (parseFloat(rowItem2) > parseFloat(rowItem3) ){			
			alert ("Issue Qty Cannot More Than Onhand Balance For Item : " + rowItem);		   
		    return false;
		}	
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

function get_desc(itemcode, vid)
{
    var iditmdesc = "procodesc"+vid;
    var iditmuom  = "procouom"+vid;
    var iditmbal  = "procomark"+vid;
    
	var strURL="aja_get_itmadj.php?itmcod="+itemcode;
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
						document.getElementById(iditmbal).value = obj.bal;
					}else{
						document.getElementById(iditmdesc).value = '';
						document.getElementById(iditmuom).value = '';
						document.getElementById(iditmbal).value = '';
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

	<fieldset name="Group1" style=" width: 857px;" class="style2">
	 <legend class="title">RAWMAT ISSUE</legend>
	  <br>	 
	  
	  <form name="InpJobFMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 833px; height: 122px;">
	   	   <tr>
	   	    <td></td>
			<td style="width: 136px">Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 270px">
		   <input class="inputtxt" name="prorevdte" id ="prorevdte" type="text" style="width: 128px;" value="<?php  echo date("d-m-Y"); ?>" tabindex="0" >
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('prorevdte','ddMMyyyy')" style="cursor:pointer">
		   </td>
			<td style="width: 29px"></td>
			<td style="width: 136px">&nbsp;</td>
			<td style="width: 16px">&nbsp;</td>
			<td style="width: 270px">
		    &nbsp;</td>
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 126px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 239px"></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 126px">Label ID</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
		   <input class="autosearch" name="labelno" id="labelnoid" type="text" maxlength="15" style="width: 129px" onchange ="upperCase(this.id)" tabindex="0" onblur="get_labrate(this.value)">
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
	  	   <td style="width: 126px">Send To</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
		   <input name="sendto" id ="sendtoid" type="text" style="width: 156px; text-align:center;" tabindex="0" ></td>
		   <td></td>
		   <td style="width: 136px">Request By</td>
		   <td>:</td>
		   <td>
		   <input name="requestby" id ="requestbyid" type="text" style="width: 156px; text-align:center;" tabindex="0" ></td>
		  </tr> 
		  	
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table" style="width: 841px">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader">Raw Material Item</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Onhand Bal</th>
              <th class="tabheader" style="width: 72px">Issue Qty</th>
             </tr>
            </thead>
            <tbody>
            
             <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="1" readonly="readonly" style="width: 27px; border:0;"></td>
                <td>
				<input name="procomat[]" id="procomat1" tabindex="0" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)" onblur="get_desc(this.value, '1')"></td>
                <td>
				<input name="procodesc[]" id="procodesc1" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 303px;"></td>
                <td>
				<input name="procouom[]" id="procouom1" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 48px;"></td>
                <td>
                <input name="procomark[]" id="procomark1" readonly="readonly" style="width: 75px; border:0; text-align:right;"> </td>
				<td style="width: 72px">
				<input name="issueqty[]" class="tInput" id="issueqtyid1" onBlur="calcCost('1');" style="width: 75px; text-align:right;"></td>  
             </tr>
            </tbody>
           </table>
           
         <a href="#" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Item</span></a>
         <a href="javascript:deleteRow('itemsTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>

	
		 <table>
		  	<tr>
				<td style="width: 950px; height: 22px;" align="center">
				<?php
				 $locatr = "m_rm_issue.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
					include("../Setting/btnsave.php");
				?>
				</td>
			</tr>
			<tr>
				<td style="width: 1045px" colspan="5">
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
				<td style="width: 950px">&nbsp;</td>
			</tr>
	  	</table>
	   </form>	
	</fieldset>
	</div>
	<div class="spacer"></div>
</body>

</html>
