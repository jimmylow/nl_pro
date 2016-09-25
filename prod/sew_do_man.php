<?php

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	$var_loginid = $_SESSION['sid'];
	date_default_timezone_set('Asia/Kuala_Lumpur');
    
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
    
    if ($_POST['Submit'] == "Save") 
    {
      $frdate= date('Y-m-d', strtotime($_POST['frdate']));
      $todate= date('Y-m-d', strtotime($_POST['frdate2']));
      $dodate= date('Y-m-d', strtotime($_POST['dodate']));
    	$buyer = $_POST['sel_suppno'];
    	$remark = $_POST['remark'];
    	$totproduct = number_format($_POST['totproduct'],2);
    	$totdefect = number_format($_POST['totdefect'],2);
    	$totgrand = number_format($_POST['totgrand'],2);
    	$qtydoz = 0;

    	
    	if ($totproduct == ""){ $totproduct = 0;};
    	if ($totdefect == ""){ $totdefect = 0;};
    	if ($totgrand == ""){ $totgrand = 0;};
    	$totproduct= str_replace(",", "", $totproduct);
    	$totdefect= str_replace(",", "", $totdefect);
    	$totgrand= str_replace(",", "", $totgrand);
    	  	
	     $sysno = '';
	     $sqlchk = " select sysno from sew_do_man_sysnumber ";
	     $sqlchk.= " where buyer  = '$buyer'";
	     
	     $dumsysno= mysql_query($sqlchk) or die(mysql_error());
	     while($row = mysql_fetch_array($dumsysno))
	     {
	     	$sysno = $row['sysno'];        
	     }
	     if ($sysno ==NULL)
	     {
	     	$sysno = '0';
	     	
	     	$sysno_sql = "INSERT INTO sew_do_man_sysnumber values ('".$buyer."', '$sysno')";
	
	     	mysql_query($sysno_sql);
	
	     }
	     $newsysno = $sysno + 1;
	     
	     $dosysno  = str_pad($newsysno , 4, '0', STR_PAD_LEFT);
	     $dosysno   = "M".$buyer. "-".$dosysno  ;

    //echo $dosysno   ; break;

     if ($buyer!=NULL)
   	 {
 
      $var_sql = " SELECT count(*) as cnt from sew_do_man ";
      $var_sql .= " WHERE sew_do_manid = '$dosysno'";
      
      
      

      $query_id = mysql_query($var_sql) or die ("Cant Check Sewing D/O");
      $res_id = mysql_fetch_object($query_id);

      if ($res_id->cnt > 0 ) {
	      $backloc = "../prod/sew_do_man.php?stat=3&menucd=".$var_menucode;
          echo "<script>";
          echo 'location.replace("'.$backloc.'")';
          echo "</script>";
      }else{
       
         $vartoday = date("Y-m-d H:i:s");
         $sql = "INSERT INTO sew_do_man values 
                ('$dosysno', '$buyer', '$frdate', '$todate', '$dodate', 
                 '$totproduct', '$totdefect', '$totgrand', 'N', 'N',
                 '$var_loginid', '$vartoday','$var_loginid', '$vartoday', '$remark')";

     	 mysql_query($sql) or die("Error Enter Sew D/O :".mysql_error(). ' Failed SQL is --> '. $sql);
   	 
     	 
     	 //----------------------------------------------------------------
		if(!empty($_POST['prococode']) && is_array($_POST['prococode'])) 
		
		{	
			foreach($_POST['prococode'] as $row=>$matcd ) 
			{				
			    
				$uprice = 0;
				$matcode   = $matcd;
				$matseqno  = $_POST['seqno'][$row];
				$matdesc   = mysql_real_escape_string($_POST['procodesc'][$row]);
				$matuom    = $_POST['procouom'][$row];
				$matqty    = $_POST['procoqty'][$row];
				$matuprice = $_POST['procoprice'][$row];
				$amount    = $_POST['procoamount'][$row];
				$amount = str_replace(",", "", $amount);
			
			    //echo  'kkk - '. $matcode ; break;
				if ($matcode <> "")
				{					
					$sql2 = "INSERT INTO sew_do_man_tran values 
				    		('$dosysno', '$matseqno', '$ticketno', '$matcode', '$matuom', '$matqty', '$matuprice', '$amount', '$matseqno')";
					//echo $sql2; break;

					mysql_query($sql2) or die("Error Enter Sew D/O Tran :".mysql_error(). ' Failed SQL is --> '. $sql2);					
					
				    //---------------------end of insert ----------------------------//				


	
				}
			}				
		 }

     	 //----------------------------------------------------------------
     	 $updsysno_sql = "UPDATE sew_do_man_sysnumber SET sysno = '$newsysno' WHERE buyer = '".$buyer."'";

     	 mysql_query($updsysno_sql);
//     	 echo $updsysno_sql; break;

   	  	     	 
     	 $backloc = "../prod/m_sew_do_man.php?stat=1&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
      } 
     }else{
       $backloc = "../prod/m_sew_do_man.php?stat=4&menucd=".$var_menucode;
       echo "<script>";
       echo 'location.replace("'.$backloc.'")';
       echo "</script>";
     }
    }?>


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
<script  type="text/javascript" src="jq-ac-script-do-man.js"></script>


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

 var price = document.getElementById("procoprice"+str).value; 
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
   // alert(priamt);
    var result = priamt.split("k");
    //document.getElementById("procoprice"+str).value=result[0]; 
    document.getElementById("procoamount"+str).value=result[1];   
    }
  }
xmlhttp.open("GET","getpoprice.php?p="+price+"&i="+iteminfo+"&q="+qty+"&m="+rand,true); 
xmlhttp.send();
}

</script>
</head>
<body onload="setup()">
  <?php include("../topbarm.php"); ?> 
 <!-- <?php include("../sidebarm.php"); ?> -->

  <div class="contentc">

	<fieldset name="Group1" style=" width: 862px;" class="style2">
	 <legend class="title">SEWING DELIVERY ORDER - MANUAL</legend>
	  <br>	 
	  
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 886px"; border="0">
	   	   <tr>
	   	    <td style="width: 20px"></td>
	  	    <td style="width: 114px;">Customer</td>
	  	    <td style="width: 11px;">:</td>
	  	    <td style="width: 212px;">
				<select name="sel_suppno" style="width: 300px"  id="sel_suppnoid" tabindex="0" onchange="getTicket(this.value)">
			    <?php
                   $sql = "select customer_code, customer_desc from customer_master WHERE active_flag = 'ACTIVE' ORDER BY customer_desc ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['customer_code'].'">'.$row['customer_desc'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select>
			</td>
	  	  </tr>  
	  	  <tr>
	  	   <td style="width: 20px"></td>
			<td style="width: 114px" >Delivery Date</td>
			<td style="width: 11px" >:</td>
			<td >
		   <input class="inputtxt" name="dodate" id ="dodate" type="text" style="width: 128px;" value="<?php  echo date("d-m-Y"); ?>" >
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('deldte','ddMMyyyy')" style="cursor:pointer">
		   </td>       
	  	  </tr>
	  	  <tr>
	  	   <td style="width: 20px"></td>
			<td style="width: 114px">Remarks</td>
			<td style="width: 11px">:</td>
			<td style="width: 16px">
		   <input class="inputtxt" name="remark" id ="remark" type="text" style="width: 445px;" maxlength="80" ></td>
			<td style="width: 270px">
		    &nbsp;</td>       
	  	  </tr>
	  	   	 		
	  	  </table>
		 
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
             <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="1" readonly="readonly" style="width: 27px; border:0;"></td>
                <td>
				<input name="prococode[]" value="" tProItem1=1 id="prococode1" tabindex="0" class="autosearch" style="width: 161px" onchange ="upperCase(this.id)"></td>
                <td>
				<input name="procodesc[]" value="" class="tInput" id="procodesc" style="width: 300px; text-align: left;"></td>
                <td>
				<input name="procoqty[]" value="" id="procoqty1" style="width: 48px; text-align : right" onBlur="getUprice(1);" ></td>
                <td>
				<input name="procouom[]" id="procouom" class="tInput" style="width: 65px; text-align: right;"></td>
                <td>
				<input name="procoprice[]" value="" class="tInput" id="procoprice1" style="width: 92px; text-align : right" onBlur="getUprice(1);"></td>
                <td>
				<input name="procoamount[]" value="" class="tInput" id="procoamount1" readonly="readonly" style="width: 75px; border:0; text-align : right"></td>
             </tr>
            </tbody>
           </table>
           
         <a href="#" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Item</span></a>
         <a href="javascript:deleteRow('itemsTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>

     <br /><br />

     <br /><br />
     
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_sew_do_man.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
					include("../Setting/btnsave.php");
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
