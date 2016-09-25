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
      $cutno = $_GET['c'];
      $sew_invid= $_GET['invnum'];
	  $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");
    }
    
    if (isset($_POST['Submit'])){ 
    	if ($_POST['Submit'] == "Print"){
       		$tick = $_POST['sew_invid'];

        	$fname = "sew_taxinv2.rptdesign&__title=myReport"; 
        	$dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&tic=".$tick."&menuc=".$var_menucode."&dbsel=".$varrpturldb."&usernm=".$var_loginid;;
        	$dest .= urlencode(realpath($fname));
        
        	//header("Location: $dest" );
        	echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        	$backloc = "../prod/vm_sew_invoice.php?invnum=".$tick."&menucd=".$var_menucode;
       		echo "<script>";
       		echo 'location.replace("'.$backloc.'")';
        	echo "</script>"; 
     	}
    }

    
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

    var x=document.forms["InpRawmatReceive"]["frdate"].value;
	if (x==null || x=="")
	{
	alert("From Date Must Not Be Blank");
	document.InpRawmatReceive.frdate.focus();
	return false;
	}
	
	var x=document.forms["InpRawmatReceive"]["frdate2"].value;
	if (x==null || x=="")
	{
	alert("To Date Must Not Be Blank");
	document.InpRawmatReceive.frdate2.focus();
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


	var x=document.forms["InpRawmatReceive"]["totgrand"].value;
	if (x==0)
	{
	alert("Grand Total Must Not Be ZERO");
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
<?php
	$sew_invid= $_GET['invnum'];
	$sql = "select * from sew_inv";
    $sql .= " where sew_invid ='$sew_invid' ";        
    $sql_result = mysql_query($sql);
    $row = mysql_fetch_array($sql_result);
//    echo $sql; break;

    $sew_invid = $row['sew_invid'];
	$buyer= $row['buyer'];
	$tax= $row['tax'];
	$discount= $row['discount']; 
	$term= $row['term']; 

	$invdate= date('d-m-Y', strtotime($row['invdate']));
	$smthyr= $row['period'];
	
	$sql = "select supplier_desc from supplier_master ";
	$sql .= " where supplier_code ='".$row['buyer']."'";
	$sql_result = mysql_query($sql);
	$row = mysql_fetch_array($sql_result);
	if ($sql_result <> FALSE)
	{
		$supplier_desc = $row[0];
	}

   

?>

<body onload="setup()">
	<?php include("../topbarm.php"); ?> 
<!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc" style="width: 978px; height: 559px;">

	<fieldset name="Group1" style=" width: 955px; height: 546px;" class="style2">
	 <legend class="title">VIEW SEWING INVOICE</legend>
	 
	  <form name="InpRawmatReceive" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()" style="height: 497px; width: 929px;">
		<table style="width: 950px; height: 432px;">
		    <tr>
	  	    <td style="width: 5px"></td>
	  	    <td style="width: 50px;">Invoice Number</td>
	  	    <td style="width: 4px;">:</td>
	  	    <td style="width: 212px;">
		   <input class="textnoentry1" name="sew_invid" id ="sew_invid" type="text" style="width: 128px;" tabindex="7" value="<?php echo $sew_invid;?>" ></td>
			<td style="width: 9px"></td>
		    <td style="width: 50px;" class="tdlabel">&nbsp;</td>
	  	    <td style="width: 5px;">&nbsp;</td>
	  	    <td style="width: 69px;">
		   		&nbsp;</td>
	  	    </tr>
		 <tr>
	  	    <td style="width: 5px"></td>
	  	    <td style="width: 50px;">Buyer</td>
	  	    <td style="width: 4px;">:</td>
	  	    <td style="width: 212px;">
				<input class="textnoentry1" name="buyer" id ="sew_invid0" type="text" style="width: 128px;" tabindex="7" value="<?php echo $supplier_desc;?>" ></td>
			<td style="width: 9px"></td>
		    <td style="width: 50px;" class="tdlabel">Invoice Date</td>
	  	    <td style="width: 5px;">:</td>
	  	    <td style="width: 69px;">
		   <input class="textnoentry1" name="invdate" id ="sew_invid4" type="text" style="width: 128px;" tabindex="7" value="<?php echo $invdate;?>" ></td>
	  	  </tr>
		  <tr><td style="width: 5px"></td></tr>	
	  	  <tr>
	  	    <td style="width: 5px"></td>
		   <td>Tax</td>
		   <td>:</td>
		    <td>
		    <input class="textnoentry1" name="tax" id ="sew_invid1" type="text" style="width: 128px;" tabindex="7" value="<?php echo $tax;?>" >%</td>
			<td style="width: 9px"></td>
		   <td>For Period (MM/YYYY)</td>
		   <td>:</td>
		    <td>
			<input class="inputtxt" name="samthyr" id="samthyr" type="text"  readonly="readonly" style="width: 100px;" value="<?php echo $smthyr; ?>">
		    </td>
	  	  </tr>

	  	    <tr>
	  	    <td style="width: 5px"></td>
		   <td>Discount</td>
		   <td>:</td>
		    <td>
		   <input class="textnoentry1" name="discount" id ="sew_invid2" type="text" style="width: 128px;" tabindex="7" value="<?php echo $discount;?>" >%</td>
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
		   <input class="textnoentry1" name="terms" id ="sew_invid3" type="text" style="width: 128px;" tabindex="7" value="<?php echo $term;?>" >DAYS</td>
			<td style="width: 9px; height: 28px;"></td>
		   <td style="height: 28px"></td>
		   <td style="height: 28px"></td>
		    <td style="height: 28px">
		    	</td>
	  	    </tr>

	  	    <tr>
   	        </tr>
			<tr>
			<td colspan="8" align="center">
				<?php
				 $locatr = "m_sew_invoice.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				  echo '<input type="submit" name = "Submit" value="Print" class="butsub" style="width: 60px; height: 32px">';	

				?>
	  	    </td>
	  	    </tr>

	  	  <tr>
	  	    <td colspan="8">
			<?php 
			//kns
				$sql = "SELECT distinct x.sew_doid  ";
				$sql.= " FROM sew_inv_tran  x ";
				$sql.= " where  sew_invid = '$sew_invid' ";
				$sql.= " ORDER BY x.sew_doid ";
				//echo $sql;
			             	
				//$sql = "SELECT x.ticketno, x.productcode, x.seqno, qty FROM sew_qc_tran x, sew_entry y where x.ticketno = y.ticketno and buyer = 'MDF' and x.qcdate between '2013-10-01' and '2013-10-23' ORDER BY x.ticketno, x.seqno";
			
			
				$rs_result = mysql_query($sql); 
			   
				$i = 1;
				if ($rs_result <> FALSE)
				{
				//here
					echo '<table id="itemsTable" class="general-table">
					<thead>
						<tr>
							  <th class="tabheader" style="width: 27px; height: 57px;">#</th>
							  <th class="tabheader">Do No.</th>
							  <th class="tabheader">Buyer Name</th>
								<th class="tabheader">DO Date</th>
							  <th class="tabheader">DO Amount</th>
						 </tr>
					</thead>
					<tbody>';
				// end here
				
					while ($rowq = mysql_fetch_assoc($rs_result))
					{
						$sew_doid = $rowq['sew_doid'];
						//here//
						
						$sql = "select buyer, create_on, totgrand from sew_do";
						$sql .= " where sew_doid = '". $sew_doid."'";
						$sql_result = mysql_query($sql);
						if ($sql_result <> FALSE)
						{
							$row = mysql_fetch_array($sql_result);
							$supp_code= $row[0];
							$create_on= $row[1];
							$totgrand = $row[2];
						}
						$dodate= date('Y-m-d', strtotime($rowq['create_on']));

						$sql = "select supplier_desc from supplier_master ";
						$sql .= " where supplier_code = '". $supp_code."'";
						$sql_result = mysql_query($sql);
						if ($sql_result <> FALSE)
						{
							$row = mysql_fetch_array($sql_result);
							$supplier_desc= $row[0];
						}
						
						// end here//
								
						$dodate= date('Y-m-d', strtotime($create_on));
						
						$ticketno = $rowq['ticketno'];
						if ($rowq['seqno']==2)
						{
							$rowq['ticketno']= '';
						}
							  
						echo '<tr class="item-row">';	
						echo '<td><input name="sequence[]" id="sequence" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>'; 
						echo '<td><input name="donum[]" value="'.$sew_doid.'" id="sew_doid" readonly="readonly" style="border-style: none; border-color: none; border-width: 0; width: 60px;"></td>';
					  
						echo '<td><input name="supplier_desc[]" value="'.$supplier_desc.'" id="supplier_desc" style="width: 303px; border-style: none;" readonly="readonly"></td>'; 
						echo '<td><input name="dodate[]" class="tInput" value="'.$dodate.'" id="upriceid'.$i.'"  readonly="readonly" style="width: 75px; border:0;"></td>';   
						echo '<td ><input name="totgrand[]" tMark="1" id="totgrand'.$i.'" readonly="readonly" style="width: 75px; border:0; " value="'.$totgrand .'"></td>';         
			
						echo ' </tr>';
						
						$i = $i + 1;
					}
					echo '</tbody></table>';
				}else{
					echo 'No Invoice to display';
					
				}
			
			
			//end kns
			?>
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
