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
		$invdate= date('Y-m-d', strtotime($_POST['frdate2']));
    	$buyer = $_POST['sel_suppno'];
    	$terms = $_POST['terms'];
    	$tax = $_POST['tax'];
    	$discount = $_POST['discount'];
    	$smthyr = $_POST['samthyr'];

    	if ($totproduct == ""){ $totproduct = 0;};
    	if ($totdefect == ""){ $totdefect = 0;};
    	  	
    	
	     $sysno = '';
	     $sqlchk = " select sysno from sew_inv_sysnumber ";
	     $sqlchk.= " where buyer  = '$buyer'";
	     
	     $dumsysno= mysql_query($sqlchk) or die(mysql_error());
	     while($row = mysql_fetch_array($dumsysno))
	     {
	     	$sysno = $row['sysno'];        
	     }
	     if ($sysno ==NULL)
	     {
	     	$sysno = '0';
	     	
	     	$sysno_sql = "INSERT INTO sew_inv_sysnumber values ('".$buyer."', '$sysno')";
	
	     	mysql_query($sysno_sql);
	
	     }
	     $newsysno = $sysno + 1;
	     
	     $invsysno= str_pad($newsysno , 4, '0', STR_PAD_LEFT);
	     $invsysno = $buyer. "IV-".$invsysno;

        //here----------------------------------------------					 
		  $var_sql = " SELECT count(*) as cnt from sew_inv ";
		  $var_sql .= " WHERE sew_invid = '$invsysno'";
		  
		  

		  $query_id = mysql_query($var_sql) or die ("Cant Check Sewing Invoice");
		  $res_id = mysql_fetch_object($query_id);

		  if ($res_id->cnt > 0 ) {
			  $backloc = "../prod/sew_invoice.php?stat=3&menucd=".$var_menucode;
			  echo "<script>";
			  echo 'location.replace("'.$backloc.'")';
			  echo "</script>";
		  }else{
		   
			 $vartoday = date("Y-m-d H:i:s");
			 $sql = "INSERT INTO sew_inv values 
					('$invsysno', '$buyer', '$invdate',  
					 '$discount', '$tax', '$terms', 'N',
					 '$var_loginid', '$vartoday','$var_loginid', '$vartoday', '$smthyr')";

			 mysql_query($sql) or die("Error Enter Sew Invoice :".mysql_error(). ' Failed SQL is --> '. $sql);
				if(!empty($_POST['procd']) && is_array($_POST['procd'])) 
				{
					foreach($_POST['procd'] as $value ) 
					{	
					
						$sql = "SELECT  sew_doid, ticketno, productcode, issueqty, uprice, amount, seqno ";
						$sql.= " FROM sew_do_tran ";
						$sql.= " WHERE sew_doid = '". $value."'";
						$rs_result = mysql_query($sql); 
					   
						$i = 1;
						while ($rowq = mysql_fetch_assoc($rs_result))
						{
							$sew_doid = $rowq['sew_doid'];
							$ticketno = $rowq['ticketno'];
							$productcode = $rowq['productcode'];
							$issueqty = $rowq['issueqty'];
							$uprice = $rowq['uprice'];
							$amount = $rowq['amount'];
							$seqno = $rowq['seqno'];
							
							$sql = "INSERT INTO sew_inv_tran values ";
							$sql.= " ('$invsysno', '$sew_doid', '$ticketno', '$productcode',"; 
							$sql.= " '$issueqty', '$uprice', '$amount', '$seqno' )"; 
							mysql_query($sql) or die("Error INSERT Sewing Invoice Trans :".mysql_error(). ' Failed SQL is -->'. $sql);	
							//echo $sql; break;
						}
						
						$sql = "UPDATE sew_do";
						$sql .= " SET invflg = 'Y'";
						$sql .= "  WHERE sew_doid ='$value'";
 						mysql_query($sql) or die("Error update DO invoice flag :".mysql_error(). ' Failed SQL is --> '. $sql);       	
					

					}				
				 }       

			

			 $updsysno_sql = "UPDATE sew_inv_sysnumber SET sysno = '$newsysno' WHERE buyer = '".$buyer."'";
			 mysql_query($updsysno_sql) or die("Error UPDATE Sys :".mysql_error(). ' Failed SQL is -->'. $sql);	

					 
			 $backloc = "../prod/m_sew_invoice.php?stat=1&menucd=".$var_menucode;
			 echo "<script>";
			 echo 'location.replace("'.$backloc.'")';
			 echo "</script>";
		  } 

		   //-------------END OF UPDATE -------------------//

    }
 


    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
    
   		$here = getcwd();
       // Redirect browser
        $fname = "clr_rpt.rptdesign&__title=myReport"; 
        $dest = "http://".$var_server.":8080/birt-viewer/frameset?__report=clr_rpt.rptdesign";
        $dest .= urlencode(realpath($fname));
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
     }
    } 
    
    if ($vmmthyr == "") { $vmmthyr = date("m/Y"); }  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />

<style media="all" type="text/css">
@import "../css/styles.css";
@import "../css/demo_table.css";
thead th input { width: 90% }

.style2 {
	margin-right: 0px;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>
<script type="text/javascript" language="javascript" src="../js/datetimepicker_css.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>
<script type="text/javascript" src="../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../js/Parsers.js"></script>
<script type="text/javascript" src="../js/InputMask.js"></script>


<script type="text/javascript" charset="utf-8"> 

function setup() {

		document.InpRawmatReceive.sel_suppnoid.focus();
						
 		//Set up the date parsers
        var dateParser = new DateParser("dd-MM-yyyy");
      
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("dd-MM-yyyy", "frdate2");
		dateMask1.validationMessage = errorMessage;		
}


function setup2() {

 		//Set up the date parsers
        var dateParser = new DateParser("MM/yyyy");
      
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("MM/yyyy", "samthyr");
		dateMask1.validationMessage = errorMessage;
 
}


$(document).ready(function() {
		$('#example').dataTable( {
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50,"All"]],
			"bStateSave": true,
			"bFilter": true,
			"sPaginationType": "full_numbers"
			
		} )
		.columnFilter({sPlaceHolder: "head:after",
		aoColumns: [ 
					 null,	
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
			
var newwindow;
function poptastic(url)
{
	newwindow=window.open(url,'name','height=600,width=1011,left=100,top=100');
	if (window.focus) {newwindow.focus()}
}

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

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

function AjaxFunction(supp_code, rm_code)
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
	
	var url="aja_chk_price.php";
	
	url=url+"?rm_code="+rm_code+"&supp_code="+supp_code;
	
    //alert("Image's Source is: " + URL);

	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",encodeURI(url),true);
	httpxml.send(null);
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
    
function getItem(supp_code, main_code)
{
//   var strURL="aja_get_itemno.php?main_code="+main_code;
   var strURL="aja_get_itemno.php?supp_code="+supp_code+"&main_code="+main_code;
   //alert("Image's Source is: " + strURL);

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


function getPrice(supp_code, main_code)
{
   var strURL="aja_chk_price.php?supp_code="+supp_code+"&main_code="+main_code;
 	//alert("AJAX chk price is: " + strURL);

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
	var url="aja_chk_price.php?supp_code="+supp_code+"&rm_code="+main_code;
	
    //alert("second is: " + url);

	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",encodeURI(url),true);
	httpxml.send(null);

}


function getBuyer()
{
   
   //var frdate = document.InpRawmatReceive.frdate.value;
   //var todate = document.InpRawmatReceive.frdate2.value;
   var supp_code = document.InpRawmatReceive.sel_suppno.value;
   
   
   var strURL="aja_get_invoice.php?supp_code="+supp_code;

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
	    document.getElementById('statedivx0').innerHTML=req.responseText;
	 } else {
   	   alert("There was a problem while using XMLHTTP:\n" + req.statusText);
	 }
       }
      }
   req.open("GET", strURL, true);
   req.send(null);
   }
   
   showTerm();
   
}

function showTerm()
{
    var supp_code = document.InpRawmatReceive.sel_suppno.value;
   
    var strURL="getTerm.php?supp_code="+supp_code;

//alert(strURL);

	var rand = Math.floor(Math.random() * 101);

	  
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
//		alert (xmlhttp.responseText);
		//document.getElementById("suppadd").value=xmlhttp.responseText; 
		
		var txtrst = xmlhttp.responseText;
//alert(xmlhttp.responseText);		
		var result = txtrst.split("^");
		//alert (result[0]+" : "+result[1]+" : "+result[2]);
		
		var x = result[0];

		document.getElementById("terms").value=result[0];   
		//document.getElementById("totdefectid").value=result[1]; 
		//document.getElementById("totgrandid").value=result[2]; 
		//document.InpRawmatReceive.totproductid.value = parseFloat(x).toFixed(2);;		
		}
	  }
	xmlhttp.open("GET",strURL,true);
	xmlhttp.send();
}


   function isNumberKey(evt)
   {
   	var charCode = (evt.which) ? evt.which : event.keyCode
   	 if (charCode != 46 && charCode > 31 
   	&& (charCode < 48 || charCode > 57))
	   return false;
    return true;
   }

function DecimalValidate(control)
        {
            // regular expression
            var rgexp = new RegExp("^\d*([.]\d{2})?$");
            var input = document.getElementById(control).value;

            if (input.match(rgexp))
                alert("ok");
            else
                alert("no");
        }
        
function validateForm()
{
	var x=document.forms["InpRawmatReceive"]["sel_suppnoid"].value;
	if (x==null || x=="")
	{
	alert("Buyer Must Not Be Blank");
	return false;
	}
	
	var x=document.forms["InpRawmatReceive"]["frdate2"].value;
	if (x==null || x=="")
	{
	alert("Invoice Date Must Not Be Blank");
	document.InpRawmatReceive.frdate2.focus();
	return false;
	}	
	
		var x=document.forms["InpRawmatReceive"]["samthyr"].value;
	if (x==null || x=="")
	{
	alert("Period Cannot Be Blank");
	return false;
	}	

	
	var x=document.forms["InpRawmatReceive"]["totgrand"].value;
	if (x==0)
	{
	alert("Grand Total Must Not Be ZERO");
	return false;
	}	

	
	var x=document.forms["InpRawmatReceive"]["totproductid"].value;
	if (x==null || x=="")
	{
	alert("Product Total Must Not Be Blank");
	return false;
	}	


	var x=document.forms["InpRawmatReceive"]["totdefectid"].value;
	if (x==null || x=="")
	{
	alert("Defect Total Must Not Be Blank");
	return false;
	}	



}

function calcCost(vid)
{
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  

	var vamount= "amount"+vid; 
	
	var iqty = "issueqtyid"+vid;
    var qty = document.getElementById(iqty).value;	 
  
    var iduprice = "upriceid"+vid;
    var uprice = document.getElementById(iduprice).value;	

	
 	amount = 0;
	amount = qty*uprice;
	document.getElementById(vamount).value = parseFloat(amount).toFixed(2);


	
	var totproduct = 0; 
	var totdefect = 0; 
	var totgrand = 0; 
	var amount = 0;
	for (var j = 1; j < rowCount; j++)
	{
       var iqty = "issueqtyid"+j;
       var qty = document.getElementById(iqty).value;	 
  
       var iduprice = "upriceid"+j;
       var uprice = document.getElementById(iduprice).value;	
       
       var iqseqno = "seqnoid"+j;
       var seqno = document.getElementById(iqseqno).value;	
       
     
   	
 		if (qty != ""){
			if(isNaN(qty )) {
	    	   alert('Please Enter a valid number for DO Qty:' + qty);
	    	   qty= 0;
	    	   return false;
	    	}else{
		    	//here//
		    	if (seqno == 1)
		    	{	
				    totproduct = totproduct + (qty*uprice);	
				    //totgrand = +totdefect+ + totproduct;
				}
				if (seqno == 2)
				{
					totdefect = totdefect + (qty*uprice);	
					//totgrand = +totproduct+ + totdefect;
				}	
				totgrand = +totdefect+ + totproduct;			
		    	// end here//
		    	//alert('J - '+j+' totgrand '+totgrand+ ' totpro - '+totproduct+ ' defec - '+totdefect);
	    	}

	    }	   
	} 

	document.InpRawmatReceive.totproductid.value = parseFloat(totproduct).toFixed(2);;
	document.InpRawmatReceive.totdefectid.value = parseFloat(totdefect).toFixed(2);;
	document.InpRawmatReceive.totgrandid.value = parseFloat(totgrand).toFixed(2);;
	
	
	//alert(<?php $totproduct?>);
}


</script>
</head>
<body onload="setup(); setup2();">
	<?php include("../topbarm.php"); ?> 
<!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc" style="width: 978px; height: 559px;">

	<fieldset name="Group1" style=" width: 955px; height: 546px;" class="style2">
	 <legend class="title">SEWING INVOICE</legend>
	 
	  <form name="InpRawmatReceive" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()" style="height: 497px; width: 929px;">
		<table style="width: 950px; height: 432px;">
		 <tr>
	  	    <td style="width: 5px"></td>
	  	    <td style="width: 50px;">Buyer</td>
	  	    <td style="width: 4px;">:</td>
	  	    <td style="width: 212px;">
				<select name="sel_suppno" style="width: 300px"  id="sel_suppnoid" tabindex="0" onchange="getBuyer()">
			    <?php
                   $sql = "select supplier_code, supplier_desc from supplier_master WHERE active_flag = 'ACTIVE' ORDER BY supplier_desc ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['supplier_code'].'">'.$row['supplier_desc'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select>
			</td>
			<td style="width: 9px"></td>
		    <td style="width: 50px;" class="tdlabel">Invoice Date</td>
	  	    <td style="width: 5px;">:</td>
	  	    <td style="width: 69px;">
		   		<input class="inputtxt" name="frdate2" id ="frdate2" type="text" style="width: 128px;" value="<?php  echo date("d-m-Y"); ?>"><img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('frdate2','ddMMyyyy')" style="cursor:pointer"></td>
	  	  </tr>
		  <tr><td style="width: 5px"></td></tr>	
	  	  <tr>
	  	    <td style="width: 5px"></td>
		   <td>Tax</td>
		   <td>:</td>
		    <td>
		   <input class="inputtxt" name="tax" id ="tax" type="text" style="width: 128px;" tabindex="7" value="6" >
		    %</td>
			<td style="width: 9px"></td>
		   <td>For Period (MM/YYYY)</td>
		   <td>:</td>
		    <td>
			<input class="inputtxt" name="samthyr" id="samthyr" type="text" maxlength="45" style="width: 100px;" value="<?php echo $vmmthyr; ?>">
		    </td>
	  	  </tr>

	  	    <tr>
	  	    <td style="width: 5px"></td>
		   <td>Discount</td>
		   <td>:</td>
		    <td>
		   <input class="inputtxt" name="discount" id ="discount" type="text" style="width: 128px;" tabindex="7" value="0" >
		    %</td>
			<td style="width: 9px"></td>
		   <td>&nbsp;</td>
		   <td>&nbsp;</td>
		    <td>
		    &nbsp;</td>
	  	    </tr>
			<tr>
	  	    <td style="width: 5px; height: 28px;"></td>
		   <td style="height: 28px">Terms</td>
		   <td style="height: 28px">:</td>
		    <td style="height: 28px">
		   <input class="textnoentry1" name="terms" id ="terms" type="text" style="width: 128px;" tabindex="7" > 
			DAYS</td>
			<td style="width: 9px; height: 28px;"></td>
		   <td style="height: 28px"></td>
		   <td style="height: 28px"></td>
		    <td style="height: 28px">
		    	</td>
	  	    </tr>
			<tr>
	  	    <td style="width: 5px"></td>
	  	   <td>&nbsp;</td>
	  	   <td style="width: 13px">&nbsp;</td>
	  	   <td style="width: 212px">
		   &nbsp;</td>
			<td style="width: 9px"></td>
		    <td style="width: 50px;" class="tdlabel">&nbsp;</td>
	  	    <td style="width: 5px;">&nbsp;</td>
	  	    <td style="width: 69px;">
		   		&nbsp;</td>
	  	    </tr>

	  	    <tr>
   	       <td style="height: 38px;" colspan="8"><span style="color:#FF0000">Message :</span>
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
  				case 5:
				    echo("<span>Ref No, PO No, Receive Date must not Be EMPTY</span>");
  					break;

				default:
  					echo "";
				}
			  }	
			?>
		   </td>
	  	    </tr>
			<tr>
			<td colspan="8" align="center">
				<?php
				 $locatr = "m_sew_invoice.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				 include("../Setting/btnsave.php");
				?>
	  	    </td>
	  	    </tr>

	  	  <tr>
	  	    <td colspan="8">
			<p id="statedivx0" style="width: 925px; height: 600px"></p>
			</td>
		  </tr>
		  <tr>	
			<td colspan="8" align="center">
				&nbsp;</td>
	  	  </tr>
	  	  <tr>
   	       <td style="height: 38px;" colspan="8">&nbsp;</td>
	  	  </tr>
	  	</table>
	   </form>	
	</fieldset>
    </div>
    <div class="spacer"></div>
</body>

</html>
