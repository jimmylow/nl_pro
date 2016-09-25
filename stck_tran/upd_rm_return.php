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
      $rm_return_id = $_GET['rm_return_id'];
      include("../Setting/ChqAuth.php");
    }

    if ($_POST['Submit'] == "Update") {

		$rm_return_id = $_POST['rm_return_id'];
		//$refno = $_POST['refno'];
		$refno = '';
		$prorevdte= date('Y-m-d', strtotime($_POST['prorevdte']));
		$labelno = mysql_real_escape_string($_POST['labelno']);
		$returnto= mysql_real_escape_string($_POST['returnto']);
		$returnby = mysql_real_escape_string($_POST['returnby']);	            
		$var_menucode  = $_POST['menudcode'];
		if ($rm_return_id<> "") {
			   	$vartoday = date("Y-m-d H:i:s");
				$sql = "update rawmat_return set returndate = '$prorevdte', refno='$labelno', ";
				$sql .= "                       return_to = '$returnto', return_by ='$returnby', ";
				$sql .= "                       upd_by = '$var_loginid', upd_on='$vartoday' ";
				$sql .= "  where rm_return_id = '$rm_return_id'";
			
				mysql_query($sql) or die ("Cant Update Raw Mat Return Master : ".mysql_error());	
			//echo $sql; break;
				// to delete from rawmat return details table
				$sql =  "delete from rawmat_return_tran";
				$sql .= "  where rm_return_id ='$rm_return_id'";
				
				mysql_query($sql) or die ("Cant Delete Raw Mat Return Trans Table : ".mysql_error());
				
				// to delete from rawmat history table
				$sql =  "delete from rawmat_tran";  
				$sql .= "  where rm_receive_id ='$rm_return_id' and tran_type = 'RTN'";
				
				mysql_query($sql) or die ("Cant Delete Raw Mat History Table : ".mysql_error());
			
				if(!empty($_POST['procomat']) && is_array($_POST['procomat'])) 
				{	
					foreach($_POST['procomat'] as $row=>$matcd ) {
						$matcode    = $matcd;
						$seqno      = $_POST['seqno'][$row];
						$matdesc    = mysql_real_escape_string($_POST['procodesc'][$row]);
						$matuom     = mysql_real_escape_string($_POST['procouom'][$row]);
						$returnqty   = $_POST['returnqty'][$row];
						$onhandbal  = $_POST['procomark'][$row];
						$negreturnqty = 0 - $returnqty;
					
						if ($matcode <> "")
						{
							if ($returnqty== ""){ $returnqty= 0;}
							if ($onhandbal== ""){ $onhandbal= 0;}
							
							$sql = "INSERT INTO rawmat_return_tran values 
						    		('$rm_return_id', '$seqno','$labelno', '$matcode', '$matdesc', '$matuom','$returnqty','$onhandbal')";
							//mysql_query($sql);
							mysql_query($sql) or die ("Cant Insert into Raw Mat Return Trans Table : ".mysql_error());
							
							$sql = "INSERT INTO rawmat_tran values 
						    		('RTN', '$rm_return_id', '$refno','$labelno', '$prorevdte', '$matcode', '0', '$matdesc', '0', '$returnqty','$var_loginid', '$vartoday','$var_loginid', '$vartoday')";
							mysql_query($sql) or die ("Cant Insert into Raw Mat Return Master Table : ".mysql_error());

           				}	
					}
				}
				$backloc = "../stck_tran/m_rm_return.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 		
		}else{
			$backloc = "../stck_tran/m_rm_return.php?stat=4&menucd=".$var_menucode;
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
    	 var vreturnqty = "returnqty"+i;
         var vprococost = "prococost"+i;

		 var colmatucost = document.getElementById(vprocostid).value;
		 var colmatucomp = document.getElementById(vreturnqty).value;						
		
		 if (!isNaN(colmatucost) && (colmatucost != "")){
				totmcost = parseFloat(totmcost) + (parseFloat(colmatucost) * parseFloat(colmatucomp));		
		 }
	}
	document.InpJobFMas.totalmatcid.value = parseFloat(totmcost).toFixed(4);
	caltotmscb();
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


	var x=document.forms["InpJobFMas"]["returnto"].value;
	if (x==null || x=="")
	{
	alert("Return To Must Not Be Blank");
	document.InpJobFMas.returnto.focus();
	return false;
	}
	
	var x=document.forms["InpJobFMas"]["returnby"].value;
	if (x==null || x=="")
	{
	alert("Return By Must Not Be Blank");
	document.InpJobFMas.returnby.focus();
	return false;
	}

	// to chk return qty is not more than onhand qty---------------------------------------------------
	var table = document.getElementById('itemsTable');
			
	var rowCount = table.rows.length;  

	var mylist = new Array();	 

	for (var j = 1; j < rowCount; j++){
	    var idrowItem = "procomat"+j; // raw mat item
        var rowItem = document.getElementById(idrowItem).value;	 
        
        var idrowItem2 = "returnqtyid"+j; // return qty
        var rowItem2 = document.getElementById(idrowItem2).value;	
        
        var idrowItem3 = "procomark"+j; //return qty
        var rowItem3 = document.getElementById(idrowItem3).value;
        //alert('One - ' + rowItem + ' two - ' + rowItem2 + ' three - '+ rowItem3);

		if (parseFloat(rowItem2) < 0 ){			
			alert ("Return Qty For Item " + rowItem + " is Negative");		   
		    return false;
		}	
		
		if (parseFloat(rowItem2) == 0 ){			
			alert ("Cannot Update Return Qty To ZERO For Item " + rowItem +".");		   
		    return false;
		}       
       
		if (parseFloat(rowItem2) > parseFloat(rowItem3) ){			
			alert ("Return Qty Cannot More Than Issue Qty For Item : " + rowItem);		   
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
  	 $sql = "select * from rawmat_return";
     $sql .= " where rm_return_id ='".$rm_return_id."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $returndate = date('d-m-Y', strtotime($row['returndate']));
     $refno = $row['refno'];
     $labelno = $row['issue_id'];
     $itemcode = $row['item_code'];
     $returnqty = $row['totalqty'];
     $description = $row['description'];
     $returnby = $row['return_by'];
     $returnto = $row['return_to'];
	
  ?> 
  <div class="contentc">

	<fieldset name="Group1" style=" width: 910px;" class="style2">
	 <legend class="title">RAWMAT ISSUE UPDATE :<?php echo $rm_return_id;?></legend>
	  <br>	 
	  
	  <form name="InpJobFMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()" style="width: 900px">
	    <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">

		<table style="width: 886px">
	   	   <tr>
	   	    <td style="height: 25px"></td>
	  	    <td style="width: 126px; height: 25px;">Return No.</td>
	  	    <td style="width: 13px; height: 25px;">:</td>
	  	    <td style="width: 239px; height: 25px;">
			<input class="textnoentry1" name="rm_return_id" id="prorevid" type="text" style="width: 84px;" readonly="readonly" tabindex="0" value="<?php echo $rm_return_id; ?>">
			</td>
	  	  </tr> 
	   	   <tr>
	   	    <td></td>
			<td style="width: 136px">Return Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 270px">
		   <input class="inputtxt" name="prorevdte" id ="prorevdte" type="text" style="width: 128px;" value="<?php  echo $returndate; ?>" tabindex="1" >
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
	  	   <td style="width: 126px">Label ID </td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
		   <input name="labelno" id="labelnoid" type="text" style="width: 129px; height: 21px;" value="<?php echo $labelno;?>">
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
	  	   <td style="width: 126px">Return To</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
		   <input name="returnto" id ="returntoid" type="text" style="width: 156px; color:black" value="<?php echo $returnto;?>"></td>
		   <td></td>
		   <td style="width: 136px">Return By</td>
		   <td>:</td>
		   <td>
		   <input name="returnby" id ="returnbyid" type="text" style="width: 156px; text-align:center;" tabindex="6" value="<?php echo $returnby; ?>"></td>
		  </tr> 
		  	
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table" style="width: 890px">
          	<thead>
          	 <tr>
              <th class="tabheader" style="width: 27px; height: 57px;">#</th>
              <th class="tabheader">Raw Material Item</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Onhand Bal</th>
              <th class="tabheader">Return Qty</th>
             </tr>
            </thead>
            <tbody>
              <?php
             	$sql = "SELECT * FROM rawmat_return_tran";
             	$sql .= " Where rm_return_id='".$rm_return_id ."'"; 
	    		$sql .= " ORDER BY seqno";  
				$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){
					$currentbal = 0;
					$trx_onhand_bal = 0;
			        $sql = "select sum(totalqty) from rawmat_tran ";
        			$sql .= " where item_code ='".$rowq['item_code']."' and tran_type in ('REC', 'ADJ', 'RTN', 'ISS', 'OPB', 'REJ')";
        			$sql_result = mysql_query($sql);
			        $row3= mysql_fetch_array($sql_result); // current onhand balance //

			        if ($row3[0] == "" or $row3[0] == null){ 
			        	$row3[0]  = 0;
        			}
        			
			        $currentbal= htmlentities($row3[0]); //current onhand bal as to date
			        $currentbal = floatval($currentbal);
        			
			        $return_no_qty= htmlentities($rowq['totalqty']); //return qty from this Return No...
			        $return_no_qty = floatval($return_no_qty);
			        
			        $trx_onhand_bal=  $return_no_qty + $currentbal; // need to add back the Return qty from this return_no
     			
					echo '<tr class="item-row">';	
					echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>'; 
					echo '<td><input name="procomat[]" value="'.htmlentities($rowq['item_code']).'" id="procomat'.$i.'" class="autosearch" style="width: 161px"></td>';
                	echo '<td><input name="procodesc[]" value="'.$rowq['description'].'" id="procodesc" style="width: 303px; border-style: none;" readonly="readonly"></td>';
             		echo '<td><input name="procouom[]" value="'.$rowq['oum'].'" id="procouom" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 48px;"></td>';
                   	echo '<td><input name="procomark[]" tMark="1" id="procomark'.$i.'" readonly="readonly" style="width: 75px; border:0;" value="'.$trx_onhand_bal.'"></td>';
                	echo '<td><input name="returnqty[]" value="'.$rowq['totalqty'].'" id="returnqtyid'.$i.'" onBlur="calcCost('.$i.');" style="width: 75px; "></td>';              	
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
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_rm_return.php?menucd=".$var_menucode;
			
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
