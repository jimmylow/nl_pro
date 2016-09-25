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
      $wip_opening_id = $_GET['wip_opening_id'];
      include("../Setting/ChqAuth.php");
    }
    
    if ($_POST['Submit'] == "Update") {
		$wip_opening_id = $_POST['wip_opening_id'];
		$refno 		   = htmlentities(mysql_real_escape_string($_POST['refno']));
		$prorevdte     = date("Y-m-d", strtotime($_POST['prorevdte']));
		$remark        = htmlentities(mysql_real_escape_string($_POST['remark']));
		$sendto        = htmlentities(mysql_real_escape_string($_POST['sendto']));
		$requestby     = $_POST['requestby'];	            
		$var_menucode  = $_POST['menudcode'];
            
		if ($refno <> "") {
			   	$vartoday = date("Y-m-d H:i:s");
				$vartoday = date("Y-m-d H:i:s");
				$sql = "update wip_opening set openingdate = '$prorevdte', refno ='$refno', remark='$remark', ";
				$sql .= "                       upd_by = '$var_loginid', upd_on='$vartoday' ";
				$sql .= "  where wip_opening_id = '$wip_opening_id'";
				mysql_query($sql) or die("Query 1: ".mysql_error());
			
				// to delete from wip opening details table
				$sql =  "delete from wip_opening_tran";
				$sql .= "  where wip_opening_id ='$wip_opening_id'";
				mysql_query($sql) or die("Query 2: ".mysql_error());
				
				// to delete from wip history table
				$sql =  "delete from wip_tran";  
				$sql .= "  where rm_receive_id ='$wip_opening_id' and tran_type = 'OPB'";	
				mysql_query($sql) or die("Query 3: ".mysql_error());
				
				
		
				if(!empty($_POST['procomat']) && is_array($_POST['procomat'])) 
				{	
					foreach($_POST['procomat'] as $row=>$matcd ) {
						$matcode    = $matcd;
						$seqno      = $_POST['seqno'][$row];
						$matdesc    = $_POST['procodesc'][$row];
						$matuom     = $_POST['procouom'][$row];
						$openingqty   = $_POST['openingqty'][$row];
						$ticketno  = $_POST['procomark'][$row];
						$openingcost   = $_POST['openingcost'][$row];
						
						if ($matcode <> "")
						{
							if ($openingqty== ""){ $openingqty= 0;}
							if ($qtyperpack== ""){ $qtyperpack= 0;}
							if ($openingcost== ""){ $openingcost= 0;}

							
							$sql = "INSERT INTO wip_opening_tran values 
						    		('$wip_opening_id', '$seqno', '$matcode', '$matdesc', '$matuom','$openingqty','0','$openingcost','0', '$ticketno')";
							mysql_query($sql) or die("Query 4: ".mysql_error());
							
							$sql = "INSERT INTO wip_tran values 
						    		('OPB', '$ticketno', '$wip_opening_id','$remark', '$prorevdte', '$matcode', '$openingcost', '$matdesc', '0', '$openingqty','$var_loginid', '$vartoday','$var_loginid', '$vartoday')";
							mysql_query($sql) or die("Query 5: ".mysql_error());
							
							
							$varchar = $matcode[0];
							switch ($varchar)
							{
							case 'E':
								$buyer = 'EXP';
								break;
							case 'M':
								$buyer = 'MDF';
								break;
							case 'W':
								$buyer = 'FNL';
								break;
							case 'G':
								$buyer = 'FNL';
								break;
		
							default:
								echo "";
							}
							
							$cnt = 0;
							$sql1 = "select count(*) from sew_entry ";
		        			$sql1 .= " where ticketno ='$ticketno' ";
		        			$sql_result1 = mysql_query($sql1);
		        			$row1 = mysql_fetch_array($sql_result1);
		        			$cnt = $row1[0];
		        			
		        			if ($cnt ==0 or $cnt == NULL)
		        			{							
								$sql = "INSERT INTO sew_entry (ticketno, buyer, batchno, productcode, productqty, ";
								$sql.= " create_by, creation_time, modified_by, modified_on, stat )values ";
							    $sql.= "		('$ticketno', '$buyer','WIPSTKCHK','$matcode', '$openingqty', '$var_loginid', '$vartoday','$var_loginid', '$vartoday', 'ACTIVE')";
								mysql_query($sql) or die("query 99-1 :".mysql_error());
							}else{
								$sql = " UPDATE sew_entry ";
								$sql.= " set productqty = '$openingqty',  modified_by = '$var_loginid', modified_on = '$vartoday', productcode = '$matcode'";
								$sql.= " WHERE ticketno = '$ticketno' ";
								mysql_query($sql) or die("query 99-2 :".mysql_error());

							}


           				}	
					}
				}
				$backloc = "../prod/m_wip_opening_bar.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>"; 		
		}else{
			$backloc = "../prod/m_wip_opening_bar.php?stat=4&menucd=".$var_menucode;
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
<script  type="text/javascript" src="../prod/jq-ac-script-opening-ticket.js"></script>


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

function upperCase(x){
	var y=document.getElementById(x).value;
	document.getElementById(x).value=y.toUpperCase();
}


function calcCost(vid)
{
    var vprocomark = "procomark"+vid;
    var vopeningqtyid = "openingqtyid"+vid;
    
    var col2 = document.getElementById(vopeningqtyid).value;
	
   	if (col2 != ""){
		if(isNaN(col2)) {
    	   alert('Please Enter a valid number for Opening Qty :' + col2);
    	   col1 = 0;
    	}
    	document.getElementById(vopeningqtyid).value = parseFloat(col2).toFixed(2);
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

    var x=document.forms["InpJobFMas"]["refno"].value;
	if (x==null || x=="")
	{
	alert("Ref No Must Not Be Blank");
	document.InpJobFMas.refno.focus();
	return false;
	}

    var x=document.forms["InpJobFMas"]["prorevdte"].value;
	if (x==null || x=="")
	{
	alert("Date Must Not Be Blank");
	document.InpJobFMas.prorevdte.focus();
	return false;
	}
	
			
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

	    var idrowItem = "procomark"+j;
        var rowItem = document.getElementById(idrowItem).value;	 
        if (rowItem != ""){ 
        	mylist.push(rowItem);   
	    }		
    }		
	
	mylist.sort();
	var last = mylist[0];
	
	for (var i=1; i < mylist.length; i++) {
		if (mylist[i] == last){ 
			alert ("Duplicate Ticket Found; " + last);
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
    var iditmmark = "procomark"+vid;
    
	var strURL="aja_get_itmdesc.php?itmcod="+itemcode;
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
						//document.getElementById(iditmdesc).value = obj.desc;
						//document.getElementById(iditmuom).value = obj.uom;
					}else{
						document.getElementById(iditmdesc).value = "";
						document.getElementById(iditmuom).value = "";
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
  <!--<?php include("../sidebarm.php"); ?>-->
  <?php
  	 $sql = "select * from wip_opening";
     $sql .= " where wip_opening_id ='".$wip_opening_id."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $openingdate = date('d-m-Y', strtotime($row['openingdate']));
     $refno = $row['refno'];
     $remark = $row['remark'];
     $itemcode = $row['item_code'];
	
  ?> 
  <div class="contentc">

	<fieldset name="Group1" style=" width: 950px;" class="style2">
	 <legend class="title">WIP TICKET OPENING UPDATE :<?php echo $wip_opening_id;?></legend>
	  <br>	 
	  
	  <form name="InpJobFMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	    <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">

		<table style="width: 886px">
	   	   <tr>
	   	    <td style="height: 25px"></td>
	  	    <td style="width: 126px; height: 25px;">Opening No</td>
	  	    <td style="width: 13px; height: 25px;">:</td>
	  	    <td style="width: 239px; height: 25px;">
			<input class="textnoentry1" name="wip_opening_id" id="prorevid" type="text" style="width: 84px;" readonly="readonly" tabindex="0" value="<?php echo $wip_opening_id; ?>">
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
	  	    <td style="width: 126px">Ref No</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 239px">
			<input class="inputtxt" name="refno" id="refnoid" type="text" style="width: 84px;" tabindex="0" value="<?php echo $refno; ?>">
			</td>
			<td style="width: 29px"></td>
			<td style="width: 144px">Opening Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 270px">
		   <input class="inputtxt" name="prorevdte" id ="prorevdte" type="text" style="width: 128px;" value="<?php  echo $openingdate; ?>" tabindex="1" >
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('prorevdte','ddMMyyyy')" style="cursor:pointer">
		  
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
		   <input name="remark" id ="openingid" type="text" style="width: 556px; color:black" value="<?php echo $remark;?>" maxlength="100"></td>
		  </tr> 
		  	
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table" style = "width: 900px;">
          	<thead>
          	 <tr>
              <th class="tabheader" style="width: 27px; height: 57px;">#</th>
              <th class="tabheader">Product Code</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">Colour</th>
              <th class="tabheader">Ticket No.</th>
              <th class="tabheader">Opening Qty</th>
              <th class="tabheader">Opening Cost</th>

             </tr>
            </thead>
            <tbody>
              <?php
             	$sql = "SELECT * FROM wip_opening_tran";
             	$sql .= " Where wip_opening_id='".$wip_opening_id ."'"; 
             	$sql .= " and ticketno <> '' "; 
	    		$sql .= " ORDER BY seqno";  
				$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){
     			?>
                
               	<tr class="item-row">
               	 	<td style="width: 30px">
					<input name="seqno[]" id="seqno" readonly="readonly" style="width: 27px; border:0;" value="<?php echo $rowq["seqno"]; ?>" >
					</td>
                	<td>
					<input name="procomat[]" id="procomat<?php echo $i;?>" value="<?php echo htmlentities($rowq["item_code"]);?>" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)" onblur="get_desc(this.value, '<?php echo $i; ?>')"></td>
                	<td>
					<input name="procodesc[]" id="procodesc<?php echo $i;?>" value="<?php echo $rowq["description"]; ?>" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 303px;"></td>
                	<td>
					<input name="procouom[]" id="procouom<?php echo $i;?>" value="<?php echo $rowq["oum"]; ?>" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 48px;"></td>
                	<td>
                	<input name="procomark[]" id="procomark<?php echo $i;?>" value="<?php echo $rowq["ticketno"]; ?>"  style="width: 85px; "> </td>
					<td>
					<input name="openingqty[]" id="openingqtyid<?php echo $i;?>" value="<?php echo $rowq["totalqty"]; ?>" onBlur="calcCost('<?php echo $i;?>');" style="width: 75px"></td>  
					<td>
					<input name="openingcost[]" id="openingcostid<?php echo $i;?>" value="<?php echo $rowq["opening_cost"]; ?>" onBlur="calcCost('<?php echo $i;?>');" style="width: 75px"></td>  

                	</tr>
				<?php	
                	$i = $i + 1;
                }
                
                if ($i == 1){?>
                		<tr class="item-row">
                		<td style="width: 30px">
					<input name="seqno[]" id="seqno" readonly="readonly" style="width: 27px; border:0;" value="1" >
					</td>
                	<td>
					<input name="procomat[]" id="procomat<?php echo $i;?>" value="<?php echo htmlentities($rowq["item_code"]);?>" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)" onblur="get_desc(this.value, '<?php echo $i; ?>')"></td>
                	<td>
					<input name="procodesc[]" id="procodesc<?php echo $i;?>" value="<?php echo $rowq["description"]; ?>" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 303px;"></td>
                	<td>
					<input name="procouom[]" id="procouom<?php echo $i;?>" value="<?php echo $rowq["oum"]; ?>" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 48px;"></td>
                	<td>
                	<input name="procomark[]" id="procomark<?php echo $i;?>"  style="width: 85px; "> </td>
					<td>
					<input name="openingqty[]" id="openingqtyid<?php echo $i;?>" value="<?php echo $rowq["totalqty"]; ?>" onBlur="calcCost('<?php echo $i;?>');" style="width: 75px"></td>  
                	</tr>
				<?php
					$i = $i + 1;
                }
              ?>
            </tbody>
           </table>
           
         <a href="#" id="addRow" class="button-clean large"><span>
		  <img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Item</span></a>
         <a href="javascript:deleteRow('itemsTable')" id="addRow" class="button-clean large"><span>
		  <img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>

	
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_wip_opening_bar.php?menucd=".$var_menucode;
			
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
