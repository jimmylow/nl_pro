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
    
    if ($_POST['Submit'] == "Save") 
    {
     	 if(!empty($_POST['prod_code']) && is_array($_POST['prod_code'])) 
		 {	
			foreach($_POST['prod_code'] as $row=>$matcd ) 
			{
				$prod_code= $matcd;
				$seqno      = $_POST['seqno'][$row];
				$prod_jobid = $_POST['prod_jobid'][$row]; 
				$prod_jobrate = $_POST['prod_jobrate'][$row]; 
				$ori_jobrate = $_POST['ori_jobrate'][$row]; 
				
				$vartoday = date("Y-m-d H:i:s");
				$var_loginid;
				
					if ($prod_jobrate <> $ori_jobrate)
					{
						$sql = "UPDATE pro_jobmodel ";
						$sql.= " SET prod_jobrate ='$prod_jobrate', modified_by = '$var_loginid', modified_on = '$vartoday' ";
						$sql.= " WHERE prod_code  = '$prod_code'";
						$sql.= " AND prod_jobid  = '$prod_jobid'";

						mysql_query($sql) or die("Error update Production Job Rate:".mysql_error(). ' Failed SQL is -->'. $sql);	
	    			}
    			
			
								
   			}	


   	  	 
     	 $backloc = "../bom_master/projob_rate1.php?stat=9&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
      } 
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

		document.InpRawmatReceive.refnoid.focus();
						
 		//Set up the date parsers
        var dateParser = new DateParser("dd-MM-yyyy");
      
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("dd-MM-yyyy", "prorevdte");
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

function reloadPage()
{
  location.reload();
}


function getSupp()
{
  //var productcode = document.InpRawmatReceive(productcode).value;
   var productcode= document.InpRawmatReceive.productcode.value;
   var supp_code= document.InpRawmatReceive.jobid.value;
   var strURL="aja_get_desc.php?supp_code="+supp_code+"&productcode="+productcode;
   //alert(strURL);


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

    var x=document.forms["InpRawmatReceive"]["refno"].value;
	if (x==null || x=="")
	{
	alert("Ref No Must Not Be Blank");
	document.InpRawmatReceive.refno.focus();
	return false;
	}

    var x=document.forms["InpRawmatReceive"]["prorevdte"].value;
	if (x==null || x=="")
	{
	alert("Date Must Not Be Blank");
	document.InpRawmatReceive.prorevdte.focus();
	return false;
	}

	var x=document.forms["InpRawmatReceive"]["selpo_noid"].value;
	if (x==null || x=="")
	{
	alert("PO No Must Not Be Blank");
	return false;
	}
	
	// to chk receive qty is not more than order qty---------------------------------------------------
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
	var mylist = new Array();	    

	for (var j = 1; j < rowCount; j++){
	    var idrowItem = "procomat"+j; // raw mat item
        var rowItem = document.getElementById(idrowItem).value;	 
        
        var idrowItem2 = "issueqtyid"+j; // rec qty
        var rowItem2 = document.getElementById(idrowItem2).value;	
        
        var idrowItem3 = "procomark"+j; //order qty
        var rowItem3 = document.getElementById(idrowItem3).value;
        
        var idrowItem4 = "receivedqty"+j; //total received qty
        var rowItem4 = document.getElementById(idrowItem4).value;
        
        var totqty = parseInt(rowItem4) + parseInt(rowItem2);

        
        var recqty_var = rowItem3 * 1.10; // received qty cannot more +10% from order qty//
        
        //alert (totqty); return false;
         
		if (parseFloat(totqty) > parseFloat(recqty_var) ){			
			alert ("Receive Qty Is More +10% Than Order Qty For Item : " + rowItem);		   
		    //return false;
		}	
		//return false;

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

function calcCost(vid)
{
    var vissueqtyid = "issueqtyid"+vid;    	
   	var vqtyperpack = "qtyperpack"+vid;

    var col1 = document.getElementById(vissueqtyid).value;
    var col2 = document.getElementById(vqtyperpack).value;
 
	if (col1 != ""){
		if(isNaN(col1)) {
    	   alert('Please Enter a valid number for Received Qty:' + col1);
    	   col1 = 0;
    	}
    	//document.getElementById(vissueqtyid).value = parseFloat(col1).toFixed(2);
    	//document.getElementById(vissueqtyid).value = parseFloat(col1).toFixed(2);

    }	
    
   	if (col2 != ""){
		if(isNaN(col2)) {
    	   alert('Please Enter a valid number for Qty / Pack:' + col2);
    	   col1 = 0;
    	}
    	//document.getElementById(vqtyperpack).value = parseFloat(col1).toFixed(2);
    }
}



</script>
</head>
<body onload="setup()">
	<?php include("../topbarm.php"); ?> 
<!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc" style="width: 978px; height: 559px;">

	<fieldset name="Group1" style=" width: 955px; height: 24546px;" class="style2">
	 <legend class="title">UPDATE JOB RATE BY PRODUCT CODE &amp; JOB ID</legend>
	 
	  <form name="InpRawmatReceive" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()" style="height: 497px; width: 1000px;">
		<table style="width: 938px; height: 432px;">
		  <tr><td style="width: 5px"></td></tr>	
	  	  <tr>
	  	    <td style="width: 5px"></td>
	  	    <td style="width: 86px;">Product Code</td>
	  	    <td style="width: 4px;">:</td>
	  	    <td style="width: 82px;">
				<input class="inputtxt" name="productcode" id="productcode" onchange ="upperCase(this.id)" type="text" style="width: 196px;"></td>
			<td style="width: 9px"></td>
		    <td style="width: 50px;">Job ID</td>
	  	    <td style="width: 5px;">:</td>
	  	    <td style="width: 69px;">
				<input class="inputtxt" name="jobid" id="jobid" type="text" style="width: 196px;" onchange="getSupp()" id="selpo_noid" tabindex="0"></td>
	  	  </tr>
	  	  <tr><td style="width: 5px"></td></tr>	
	  	  <tr>
	  	  	<td colspan="8" align="left">
				<?php

				 $locatr = "../bom_master/projob_rate1.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				 include("../Setting/btnsave.php");
				 include("../Setting/btnClear.php");
				?>
				
	  	    </td>
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
	  	    <td colspan="8">
			<p id="statedivx0" style="width: 925px; height: 314px"></p>
			</td>
		  </tr>
		  <tr>	
			<td colspan="8" align="center">
				&nbsp;</td>
	  	  </tr>
	  	</table>
	   </form>	
	</fieldset>
    </div>
    <div class="spacer"></div>
</body>

</html>
