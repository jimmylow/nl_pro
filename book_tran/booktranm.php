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
      include("../Setting/ChqAuth.php");
    }
    
    if ($_POST['Submit'] == "Save") {
    	$vmbkdte = date('Y-m-d', strtotime($_POST['bookdte']));
		$vmbkord = mysql_real_escape_string($_POST['bkordno']);
		$vmbkbuy = $_POST['bkbuycd'];
		
		if ($vmbkord <> "") {
		
			#------------Getting Booking No-------------------------------
				$chk_invno_query = mysql_query("select count(*) from `ctrl_sysno` where `descrip` = 'BOOKNO'; ", $db_link);
              	$chk_invno_res = mysql_fetch_array($chk_invno_query) or die("Cant Get Book No Info".mysql_error());
              
              	if ($chk_invno_res[0] > 0 ) {
                  $get_invno_query = mysql_query("select noctrl from `ctrl_sysno` where `descrip` = 'BOOKNO'", $db_link);                 
                  $get_invno_res = mysql_fetch_object($get_invno_query) or die("Cant Get Book No ".mysql_error()); 

                  $var_invno = vsprintf("%06d",$get_invno_res->noctrl+1); 
                  $var_invno = "BK".$var_invno; 
                  
 		  		  mysql_query("update `ctrl_sysno` set `noctrl` = `noctrl` + 1
                           where `descrip` = 'BOOKNO';", $db_link) 
                           or die("Cant Update Book No Auto No ".mysql_error());              
                }else{ 
				  $sql  = "insert into ctrl_sysno (descrip, noctrl) values ";
				  $sql .= "	('BOOKNO', 1)";
		   		  mysql_query($sql, $db_link) or die("Cant Insert Into Book No Auto No".mysql_error());
                  $var_invno = "BK000001";
                }  
			#-------------------------------------------------------------
				
			$vartoday = date("Y-m-d");
			$sql  = "INSERT INTO booktab01 (bookno, bookdte, booktyp, byrefno, create_by, create_on, ";
			$sql .= "                       modified_by, modified_on, buycd) values ";
			$sql .=	" ('$var_invno', '$vmbkdte', 'M', '$vmbkord','$var_loginid','$vartoday', "; 
			$sql .= "  '$var_loginid','$vartoday', '$vmbkbuy')";
			mysql_query($sql) or die("Query 1 Booking Table ;".mysql_error());

			if(!empty($_POST['bkitmcd']) && is_array($_POST['bkitmcd'])) 
			{	
				foreach($_POST['bkitmcd'] as $row=>$matcd){
					$bkitmcode = mysql_real_escape_string($matcd);
					$seqno     = $_POST['seqno'][$row];
					$bkitmdesc = mysql_real_escape_string($_POST['bkitmdesc'][$row]);
					$bkitmavai = $_POST['itmavai'][$row];
					$bkitmuom  = mysql_real_escape_string($_POST['itmuom'][$row]);
					$bkitmqty  = $_POST['bkqty'][$row];
					
					if ($bkitmcode <> "")
					{
						if ($bkitmavai == ""){ $bkitmavai = 0;}
						if ($bkitmqty == ""){ $bkitmqty = 0;}
						$sql = "INSERT INTO booktab02 values 
						       ('$var_invno', '$bkitmcode', '$bkitmdesc', '$bkitmavai','$bkitmuom',
						        '$bkitmqty', 'N', '0', '0')";    
						mysql_query($sql) or die("Query 2 :".mysql_error());	
					}
				}
		    }
		    
      	    echo "<script>";   
      		echo "alert('Booking No :".$var_invno."');"; 
      		echo "</script>";

      		echo "<script>"; 
			echo "if(confirm('Continue With Print Booking Slip?'))";
			echo "{";
			
			$backloc = "../book_tran/bookslip.php?menucd=".$var_menucode."&bk=".$var_invno;
       		echo 'location.replace("'.$backloc.'")';

			echo "}else{";
				
			$backloc = "../book_tran/m_booksys.php?menucd=".$var_menucode;
           	echo 'location.replace("'.$backloc.'")';

			echo "}";
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
<script  type="text/javascript" src="jq_ac_bkjs.js"></script>

<script type="text/javascript">

$(document).ready(function(){
	var ac_config = {
	    //alert(document.getElementById(bkbuycd).value);
		source: "autogetordno.php",
		select: function(event, ui){
			$("#bkordno").val(ui.item.sordno);
			$("#bkbuycd").val(ui.item.sbuycd);
		},
		minLength:1
		
	};
	$("#bkordno").autocomplete(ac_config);
}); 

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

function setup() {

		document.InpBook.bkordno.focus();
				
 		//Set up the date parsers
        var dateParser = new DateParser("dd-MM-yyyy");
      
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("dd-MM-yyyy", "bookdte");
		dateMask1.validationMessage = errorMessage;
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
	var x=document.forms["InpBook"]["bkordno"].value;
	if (x==null || x=="")
	{
		alert("Sales Order No Must Not Be Blank");
		document.InpBook.bkordno.focus();
		return false;
	}

 	//Checking the list is empty////////////////////////////
 	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length; 
		
	var mylist = new Array();	
	for(var i = 1; i < rowCount; i++) { 
	    var iditem = "bkitmcd"+i;            
		var e = document.getElementById(iditem).value;
		if (e != ""){
			    mylist.push(e); 
		}   
	}
	if (mylist.length == 0){
		alert("Cant Book With No Item Code");
		return false;
	}
	
	/////////////////////////////////////////////////////////
	
	//Checking got Invalid Item Code/////////////////////////
	var flgchk = 1;	
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
      	   
    for (var j = 1; j < rowCount; j++){
       var idrowItem = "bkitmcd"+j;
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
						   document.getElementById(idrowItem).focus();
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
	/////////////////////////////////////////////////////////
	
	//Checking got Invalid Item Code/////////////////////////
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
	var mylist = new Array();	    

	for (var j = 1; j < rowCount; j++){

	    var idrowItem = "bkitmcd"+j;
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
	/////////////////////////////////////////////////////////
	
	//Checking got Booking Quantity Got Negative Or Zero Value/////////////////////////
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;     

	for (var j = 1; j < rowCount; j++){

	    var idrowItem = "bkqty"+j;
        var rowItem = document.getElementById(idrowItem).value;	 
        if (rowItem <= 0){ 
        	alert ("Booking Quantity Must Larger Then Zero; " + rowItem);
        	document.getElementById(idrowItem).focus();
			return false;
   
	    }		
    }			
	///////////////////////////////////////////////////////////////////////////////////

	//Checking Booking Quantity Got Larger Then Availabel Quantity/////////////////////
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;     

	for (var j = 1; j < rowCount; j++){

	    var idbookqty  = "bkqty"+j;
	    var idavailqty = "itmavai"+j;
	    
        var vbkq = document.getElementById(idbookqty).value;	
        var vavq = document.getElementById(idavailqty).value; 
        
        if (parseFloat(vbkq) > parseFloat(vavq)){ 
        	alert ("Booking Quantity Must Not Exceed Available Quantity; Row "+ j);
        	document.getElementById(idbookqty).focus();
			return false;
   
	    }		
    }			
	///////////////////////////////////////////////////////////////////////////////////

   
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

function getitmdesc(itmvalue, vid)
{
	if (itmvalue != ""){
		var flgchk = 1;
		var iditmcode = "bkitmcd"+vid;
		var iditmdesc = "bkitmdesc"+vid;
    	var iditmavil = "itmavai"+vid;
    	var iditmuom  = "itmuom"+vid;
    
		var strURL="aja_get_itmdescmbk.php?itm="+itmvalue;
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
							document.getElementById(iditmdesc).value = obj.desc;
							document.getElementById(iditmavil).value = obj.avail;
							document.getElementById(iditmuom).value = obj.uom;
						}else{
							document.getElementById(iditmdesc).value = "";
							document.getElementById(iditmavil).value = "";
							document.getElementById(iditmuom).value = "";
						}	
						if (obj.cdcnt == 0){
							flgchk = 0;
						}
					} else {
						//alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
						return false;
					}
				}
			}	 
		}
		req.open("GET", strURL, true);
		req.send(null);
		
	}	
    //-------------------------------------------------------------------------------
}

function chkord(ordval)
{	
	lg = ordval.length;
	if (ordval != "" && lg != 0){

		var buyval = document.getElementById("bkbuycd").value;
	
		var strURL="../sales_tran/aja_chk_ordernocnt.php?sordno="+ordval+"&buyercd="+buyval;
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
						if (req.responseText == 0){
							document.getElementById("msgcd").innerHTML = "<font color=red>Invalid Sales Order No</font>";
						}else{
							document.getElementById("msgcd").innerHTML = "";
						}	
					}else{
						alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
						return false;
					}
				}
			}	 
		}
		req.open("GET", strURL, true);
		req.send(null);
		
	}
}

function chkdecval(valdecbk, valid)
{
	var bkqtyid = "bkqty"+valid;
	var avqtyid = "itmavai"+valid;
	var itemid = "bkitmcd"+valid;
	
	var qtyav = document.getElementById(avqtyid).value;

	if (valdecbk != ""){
		if(isNaN(valdecbk)) {
    	   alert('Please Enter a valid number for Booking Quantity:' + valdecbk);
    	   document.getElementById(bkqtyid).focus();
    	   valdecbk = 0;
    	}
    	document.getElementById(bkqtyid).value = parseFloat(valdecbk).toFixed(2);
    }else{
    	valdecbk = 0;
    	document.getElementById(bkqtyid).value = parseFloat(valdecbk).toFixed(2);
    }
    if (parseFloat(valdecbk) < 0){	
    	alert('Booking Qunatity Cannot Negative Value:' + valdecbk);
		document.getElementById(bkqtyid).focus();
    }
    if (parseFloat(valdecbk) > parseFloat(qtyav)){
    	alert('Booking Qunatity Cannot More Than Available Quantity');
		document.getElementById(itemid).focus();
	}
	
}
</script>
</head>
<body onload="setup()">
  <?php include("../topbarm.php"); ?> 
 <!-- <?php include("../sidebarm.php"); ?> -->

  <div class="contentc">

	<fieldset name="Group1" style=" width: 821px;" class="style2">
	 <legend class="title">MANUAL BOOK</legend>
	  <br>	 
	  
	  <form name="InpBook" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 797px" border="0">
		   <tr>
		   		<td></td>
		   		<td style="width: 62px">Sales Order No</td>
		   		<td style="width: 8px">:</td>
		   		<td>
		   			<input name="bkordno" id="bkordno" class="autosearch" type="text" maxlength="30" style="width: 150px;" onchange ="upperCase(this.id)" onblur="chkord(this.value)">
		   		</td>
		   </tr>
		   <tr>
		   		<td></td>
		   		<td style="width: 62px"></td>
		   		<td style="width: 8px"></td>
		   		<td><div id="msgcd"></div></td>
		   </tr>	
		   <tr>
		   		<td></td>
		   		<td style="width: 62px">Buyer Name</td>
		   		<td style="width: 8px">:</td>
		   		<td>
		   			<input class="inputtxt" name="bkbuycd" readonly="readonly" id ="bkbuycd" type="text" style="width: 60px;">	
		   		</td>
		   </tr>
		   <tr><td></td></tr>
	   	   <tr>
	   	    <td style="width: 10px;"></td>
	  	    <td style="width: 62px;">Booking Date</td>
	  	    <td style="width: 8px;">:</td>
	  	    <td style="width: 300px;">
		   		<input class="inputtxt" name="bookdte" readonly="readonly" id ="bookdte" type="text" style="width: 80px;" value="<?php  echo date("d-m-Y"); ?>">	
		   	</td>     
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 62px"></td>
	  	   <td style="width: 8px"></td>
	  	   <td></td>
	  	  </tr>
	  	  <tr><td></td></tr>
	  	  <tr>
	  	   <td style="width: 10px;"></td>
	  	   <td style="width: 62px;"><span>Planning No</span></td>
	  	   <td style="width: 8px;">:</td>
	  	   <td style="width: 300px;">
		   		<select class= "texta" name="cstno"  id = "cstno" style="width: 140px">
           			<option value =""></option>
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
		  <tr><td>&nbsp;</td></tr> 	
	  	</table>
	  	
		  <table id="itemsTable" class="general-table">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader">Item Code</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">Availabel</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Book Qty</th>
             </tr>
            </thead>

            <tbody>
             <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="1" readonly="readonly" style="width: 27px; border:0;"></td>
                <td>
				<input name="bkitmcd[]" id="bkitmcd1" class="autosearch" style="width: 150px" onchange = "upperCase(this.id)" onblur="getitmdesc(this.value, 1)"></td>
                <td>
				<input name="bkitmdesc[]" id="bkitmdesc1" readonly="readonly" style="width: 300px; border:0;"></td>
                <td>
				<input name="itmavai[]" id="itmavai1" style="width: 100px; border:0; text-align:right" readonly="readonly"></td>
                <td>
				<input name="itmuom[]" id="itmuom1" readonly="readonly" style="width: 75px; border:0"></td>
                <td>
				<input name="bkqty[]" id="bkqty1" style="width: 100px; text-align:right" onblur="chkdecval(this.value, 1)"></td>
             </tr>
            </tbody>
           </table>
           
         <a href="#" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Item</span></a>
         <a href="javascript:deleteRow('itemsTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>

         <br><br>
     
		 <table style="width: 797px">
		  	<tr>
				<td style="width: 1059px; height: 22px;" align="center">
				<?php
				 $locatr = "m_booksys.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
					include("../Setting/btnsave.php");
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
