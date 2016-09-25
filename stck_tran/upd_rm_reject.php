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
      $rm_reject_id = $_GET['rm_reject_id'];
      include("../Setting/ChqAuth.php");
    }
    
    if ($_POST['Submit'] == "Update") {
		$rm_reject_id = $_POST['rm_reject_id'];
		//$refno = $_POST['refno'];
		$refno ='';
		$prorevdte= date('Y-m-d', strtotime($_POST['prorevdte']));
		$remark = $_POST['remark'];
		$sendto = $_POST['sendto'];
		$requestby = $_POST['requestby'];	            
		$var_menucode  = $_POST['menudcode'];
            
		if ($rm_reject_id<> "") {
			   	$vartoday = date("Y-m-d H:i:s");
				$sql = "update rawmat_reject set rejectdate = '$prorevdte', refno ='$refno', remark='$remark', ";
				$sql .= "                       upd_by = '$var_loginid', upd_on='$vartoday' ";
				$sql .= "  where rm_reject_id = '$rm_reject_id'";
				mysql_query($sql);
		
				// to delete from rawmat reject details table
				$sql =  "delete from rawmat_reject_tran";
				$sql .= "  where rm_reject_id ='$rm_reject_id'";
				
				mysql_query($sql);
				
				// to delete from rawmat history table
				$sql =  "delete from rawmat_tran";  
				$sql .= "  where rm_receive_id ='$rm_reject_id' and tran_type = 'REJ'";
				
				mysql_query($sql);
				//echo $sql;
				//break;
			
				if(!empty($_POST['procomat']) && is_array($_POST['procomat'])) 
				{	
					foreach($_POST['procomat'] as $row=>$matcd ) {
						$matcode    = $matcd;
						$seqno      = $_POST['seqno'][$row];
						$matdesc    = $_POST['procodesc'][$row];
						$matuom     = $_POST['procouom'][$row];
						$rejectqty   = $_POST['rejectqty'][$row];
						$onhandbal  = $_POST['procomark'][$row];
						$negrejectqty = 0 - $rejectqty;
					
						if ($matcode <> "")
						{
							if ($rejectqty== ""){ $rejectqty= 0;}
							if ($onhandbal== ""){ $onhandbal= 0;}
							
							$sql = "INSERT INTO rawmat_reject_tran values 
						    		('$rm_reject_id', '$seqno', '$matcode', '$matdesc', '$matuom','$rejectqty','$onhandbal')";
							mysql_query($sql);
							
							$sql = "INSERT INTO rawmat_tran values 
						    		('REJ', '$rm_reject_id', '$refno','$remark', '$prorevdte', '$matcode', '0', '$matdesc', '0', '$negrejectqty ','$var_loginid', '$vartoday','$var_loginid', '$vartoday')";
							mysql_query($sql);

           				}	
					}
				}
				$backloc = "../stck_tran/m_rm_reject.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 		
		}else{
			$backloc = "../stck_tran/m_rm_reject.php?stat=4&menucd=".$var_menucode;
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
<script  type="text/javascript" src="../stck_tran/jq-ac-script-reject.js"></script>


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
    	 var vrejectqty = "rejectqty"+i;
         var vprococost = "prococost"+i;

		 var colmatucost = document.getElementById(vprocostid).value;
		 var colmatucomp = document.getElementById(vrejectqty).value;						
		
		 if (!isNaN(colmatucost) && (colmatucost != "")){
				totmcost = parseFloat(totmcost) + (parseFloat(colmatucost) * parseFloat(colmatucomp));		
		 }
	}
	document.InpJobFMas.totalmatcid.value = parseFloat(totmcost).toFixed(4);
	caltotmscb();
}

function calcCost(vid)
{
    var vrejectqtyid = "openingqtyid"+vid;
    	
    var col1 = document.getElementById(vrejectqtyid).value;
 
	
	if (col1 != ""){
		if(isNaN(col1)) {
    	   alert('Please Enter a valid number for Reject Qty:' + col1);
    	   col1 = 0;
    	}
    	document.getElementById(vrejectqtyid).value = parseFloat(col1).toFixed(2);
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
	
    var x=document.forms["InpJobFMas"]["prorevdte"].value;
	if (x==null || x=="")
	{
	alert("Date Must Not Be Blank");
	document.InpJobFMas.prorevdte.focus;
	return false;
	}

	// to chk Reject qty is not more than onhand qty---------------------------------------------------
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
	var mylist = new Array();	 
	  

	for (var j = 1; j < rowCount; j++){
	    var idrowItem = "procomat"+j; // raw mat item
        var rowItem = document.getElementById(idrowItem).value;	 
        
        var idrowItem2 = "openingqtyid"+j; // reject qty
        var rowItem2 = document.getElementById(idrowItem2).value;	
        
        var idrowItem3 = "procomark"+j; //onhand qty
        var rowItem3 = document.getElementById(idrowItem3).value;

		if (parseFloat(rowItem3) < 0 ){			
			alert ("Onhand Balance For Item " + rowItem + " is Negative");		   
		    return false;
		}
		
		if (parseFloat(rowItem3) == 0 ){			
			alert ("Cannot Reject Item " +rowItem + ". Onhand Balance is ZERO ");		   
		    return false;
		}		
		
		if (parseFloat(rowItem2) == 0 ){			
			alert ("Reject Qty Cannot Be ZERO For Item : " +rowItem);		   
		    return false;
		}	       
       
		if (parseFloat(rowItem2) > parseFloat(rowItem3) ){			
			alert ("Reject Qty Cannot More Than Onhand Balance For Item : " + rowItem);		   
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

</script>
</head>
 
<body onload= "setup()">
	 <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>-->
  <?php
  	 $sql = "select * from rawmat_reject";
     $sql .= " where rm_reject_id ='".$rm_reject_id."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $rejectdate = date('d-m-Y', strtotime($row['rejectdate']));
     $refno = $row['refno'];
     $remark = $row['remark'];
     $itemcode = $row['item_code'];
	
  ?> 
  <div class="contentc">

	<fieldset name="Group1" style=" width: 899px;" class="style2">
	 <legend class="title">RAWMAT REJECT UPDATE :<?php echo $rm_reject_id;?></legend>
	  <br>	 
	  
	  <form name="InpJobFMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	    <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">

		<table style="width: 886px">
	   	   <tr>
	   	    <td style="height: 25px"></td>
	  	    <td style="width: 126px; height: 25px;">Reject No</td>
	  	    <td style="width: 13px; height: 25px;">:</td>
	  	    <td style="width: 239px; height: 25px;">
			<input class="textnoentry1" name="rm_reject_id" id="prorevid" type="text" style="width: 84px;" readonly="readonly" tabindex="0" value="<?php echo $rm_reject_id; ?>">
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
	  	    <td style="width: 126px">Reject Date</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 239px">
		   <input class="inputtxt" name="prorevdte" id ="prorevdte" type="text" style="width: 128px;" value="<?php  echo $rejectdate; ?>" tabindex="1" >
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('prorevdte','ddMMyyyy')" style="cursor:pointer"></td>
			<td style="width: 29px"></td>
			<td style="width: 144px">&nbsp;</td>
			<td style="width: 16px">&nbsp;</td>
			<td style="width: 270px">
		   &nbsp;
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
	  	   <td style="width: 126px">Remark</td>
	  	   <td style="width: 13px">:</td>
	  	   <td colspan="5">
		   <input name="remark" id ="remarkid" type="text" style="width: 556px; color:black" value="<?php echo $remark;?>" maxlength="100"></td>
		  </tr> 
		  	
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table" style="width: 888px">
          	<thead>
          	 <tr>
              <th class="tabheader" style="width: 27px; height: 57px;">#</th>
              <th class="tabheader">Raw Material Item</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Onhand Bal</th>
              <th class="tabheader">Reject Qty</th>
             </tr>
            </thead>
            <tbody>
              <?php
             	$sql = "SELECT * FROM rawmat_reject_tran";
             	$sql .= " Where rm_reject_id='".$rm_reject_id ."'"; 
	    		$sql .= " ORDER BY seqno";  
				$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){
					
					$currentbal = 0;
					$trx_onhand_bal = 0;
			        $sql = "select sum(totalqty) from rawmat_tran ";
        			$sql .= " where item_code ='".$rowq['item_code']."' and tran_type in ('OPB', 'REC', 'REJ', 'RTN', 'ISS', 'ADJ')";
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
					echo "<td><input name='procomat[]' value='".htmlentities($rowq["item_code"])."' id='procomat".$i."' class='autosearch' style='width: 161px'></td>";
                	echo '<td><input name="procodesc[]" value="'.$rowq['description'].'" id="procodesc'.$i.'" style="width: 303px; border-style: none;" readonly="readonly"></td>';
             		echo '<td><input name="procouom[]" id="procouom'.$i.'" readonly="readonly" value="'.$rowq['oum'].'" style="border-style: none; border-color: inherit; border-width: 0; width: 48px;"></td>';
                   	echo '<td><input name="procomark[]" tMark="1" id="procomark'.$i.'" readonly="readonly" style="width: 75px; border-width: 0;" value="'.$trx_onhand_bal.'"></td>';
                	echo '<td><input name="rejectqty[]" value="'.$rowq['totalqty'].'" id="openingqtyid'.$i.'" onBlur="calcCost('.$i.');" style="width: 75px; "></td>';              	
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
				<td style="width: 880px; height: 22px;" align="center">
				<?php
				 $locatr = "m_rm_reject.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
					include("../Setting/btnupdate.php");
				?>
				</td>
			</tr>
			<tr>
				<td style="width: 880px" colspan="5">
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
				<td style="width: 880px">&nbsp;</td>
			</tr>
	  	</table>
	   </form>	
	</fieldset>
	</div>
	<div class="spacer"></div>
</body>

</html>
