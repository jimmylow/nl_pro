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
    
    //phpinfo();
		$vmsuppid  = $_POST['supp'];
		$vmpodte   = date('Y-m-d', strtotime($_POST['podte']));
		$vmterms   = $_POST['terms'];
		$vmorderno = $_POST['orderno'];
		$vmstyleno = $_POST['styleno'];
		$vmdeldte  = date('Y-m-d', strtotime($_POST['deldte']));
		$vmremark  = $_POST['remark']; 
		$vmcstno   = $_POST['cstno'];   
            
		if ($vmsuppid <> "") {
    
            /*----------------------------- Cash Bill details ------------------------------------ */
              $chk_invno_query = mysql_query("select count(*) from `ctrl_sysno` where `descrip` = 'PUR_ORD'; ", $db_link);

              $chk_invno_res = mysql_fetch_array($chk_invno_query) or die("cant Get Cash Bill No Info".mysql_error());
              
              if ($chk_invno_res[0] > 0 ) {
                  $get_invno_query = mysql_query("select noctrl from `ctrl_sysno` where `descrip` = 'PUR_ORD'", $db_link);
                  
                  $get_invno_res = mysql_fetch_object($get_invno_query) or die("Cant Get Cash Bill No ".mysql_error()); 

                  $var_invno = vsprintf("%06d",$get_invno_res->noctrl+1); 
                  $var_invno = "NLGP-".$var_invno; 
                  
 		  mysql_query("update `ctrl_sysno` set `noctrl` = `noctrl` + 1
                           where `descrip` = 'PUR_ORD';", $db_link) 
                           or die("Cant Update Cash Bill Auto No ".mysql_error());              
               
                }  else { 

		   mysql_query("insert into `ctrl_sysno` 
                          (`descrip`, `noctrl`)
                   values ('PUR_ORD', 1);",$db_link) or die("Cant Insert Into Cash Bill Auto No");

                   $var_invno = "NLGP-000001";

                }  

            /*--------------------------- end Inv no details ---------------------------------- */
    
			
        $vartoday = date("Y-m-d H:i:s"); 
        
				$sql = "INSERT INTO po_master values 
						('$var_invno', '$vmsuppid', '$vmpodte','$vmterms','$vmorderno','$vmstyleno','$vmdeldte','$var_loginid','$vartoday', 
						 '$var_loginid', '$vartoday', 'ACTIVE', '$vmremark', '$vmcstno')";
				mysql_query($sql) or die ("Cant insert : ".mysql_error());
				
				if(!empty($_POST['prococode']) && is_array($_POST['prococode'])) 
				{	
					foreach($_POST['prococode'] as $row=>$matcd ) {
						$matcode   = $matcd;
						$matseqno  = $_POST['seqno'][$row];
						$matdesc   = mysql_real_escape_string($_POST['procodesc'][$row]);
						$matuom    = $_POST['procouom'][$row];
						$matqty    = $_POST['procoqty'][$row];
						$matuprice = $_POST['procoprice'][$row];

					
						if ($matcode <> "")
						{
							if(empty($matqty)){$matqty = 0;}
							if(empty($matuprice)){$matuprice = 0;}
							$sql = "INSERT INTO po_trans values 
						    		('$var_invno', '$matcode', '$matqty', '$matuprice','$matseqno', '$matdesc', '$matuom')";
                    
							mysql_query($sql) or die ("Cant insert : ".mysql_error());
							
							
							if (!empty($vmcstno)){
								$sql  = "INSERT INTO costing_itmpurbok "; 
								$sql .=	" (costing_no, itmcd, pono, poqty)";
								$sql .= " values"; 
						    	$sql .= "  ('$vmcstno', '$matcode', '$var_invno', '$matqty')";
								mysql_query($sql) or die ("Cant insert : ".mysql_error());
							}
           				}	
					}
				}
				echo "<script language=\"javascript\">"; 
				echo "if(confirm('Print This P/O?'))";
				echo "{";
			
				$fname = "po_mas2.rptdesign&__title=myReport"; 
        		$dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&ponum=".$var_invno."&menuc=".$var_menucode."&dbsel=".$varrpturldb."&__pageFooterFloatFlag=False";
        		$dest .= urlencode(realpath($fname));

        
        		//header("Location: $dest" );
        		echo "window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');";

				echo "}";
				echo "</script>";

				
				
				$backloc = "../pur_ord/m_po.php?menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
            	echo "</script>";
            	
				 	
					
		}else{
			$backloc = "../pur_ord/po.php?stat=4&menucd=".$var_menucode;
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
<script  type="text/javascript" src="jq-ac-script.js"></script>


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
 var suppinfo = document.getElementById("supp").value;
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
    
    var result = priamt.split("k");
    document.getElementById("procoprice"+str).value=result[0]; 
    document.getElementById("procoamount"+str).value=result[1];   
    }
  }
xmlhttp.open("GET","getpoprice.php?s="+suppinfo+"&i="+iteminfo+"&q="+qty+"&m="+rand,true);
xmlhttp.send();
}

</script>
</head>
<body onload="setup()">
  <?php include("../topbarm.php"); ?> 
 <!-- <?php include("../sidebarm.php"); ?> -->

  <div class="contentc">

	<fieldset name="Group1" style=" width: 862px;" class="style2">
	 <legend class="title">PURCHASE ORDER</legend>
	  <br>	 
	  
	  <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	   
		<table style="width: 886px"; border="0">
	   	   <tr>
	   	    <td></td>
	  	    <td style="width: 110px">Supplier</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 300px">
           <select class= "texta" name="supp"  id = "supp" onchange="showsupplier(this.value)" >
           <option value ="s">-SELECT-</option>

           <?php 
                  $sql="SELECT supplier_code, supplier_desc FROM supplier_master ";
                  $sql .= " where active_flag = 'ACTIVE'"; 
                  $sql .= " order by supplier_desc";

                  $result = mysql_query($sql);

                  while($row = mysql_fetch_array($result))
                     {
                       echo "<option value= '".$row['supplier_code']."'";
                       echo ">".$row['supplier_desc']." - ".$row['supplier_code'];
                       echo "</option>";
                     }                  
           ?> 
           	</select>
			</td>     
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	   <td ></td>
	  	   <td ></td>
	  	   <td ></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td rowspan="7"></td>
         <td colspan="2" rowspan="6">
          <textarea class="inputtxt" name="suppadd" id="suppadd" COLS=35 ROWS=8></textarea>
         </td>
			<td style="width: 29px"></td>
			<td style="width: 136px">P.O Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 270px">
		   <input class="inputtxt" name="podte" id ="podte" type="text" style="width: 128px;" value="<?php  echo date("d-m-Y"); ?>"  >
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('podte','ddMMyyyy')" style="cursor:pointer">
		   </td>       
	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td ></td>
	  	   <td ></td>
	  	   <td ></td>
	  	  </tr>
		  <tr>
		   <td></td>
       <td></td>
		   <td >Terms</td>
		   <td>:</td>
		   <td>
		   <input name="terms" id ="terms" type="text" style="width: 83px;" readonly="readonly">
		   <label id="termdesc"></label>
		   </td>
		  </tr> 
		  <tr>
	  	   <td></td>
		   <td></td>
		   <td >Order No</td>
		   <td>:</td>
		   <td>
		   <input name="orderno" id ="orderno" type="text" style="width: 156px; "  class="inputtxt" value="" onchange ="upperCase(this.id)" ></td>
	  	  </tr>
		  <tr>
	  	   <td></td>
		   <td></td>
		   <td >Style No</td>
		   <td>:</td>
		   <td>
		   <input name="styleno" id ="styleno" type="text" style="width: 156px;"  class="inputtxt" onchange ="upperCase(this.id)"></td>
	  	  </tr>
	  	  <tr>
			<td></td>
			<td></td>
			<td>Planning No</td>
			<td>:</td>
			<td>
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
	  	   <tr>
	  	   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
			<td >Delivery Date</td>
			<td >:</td>
			<td >
		   <input class="inputtxt" name="deldte" id ="deldte" type="text" style="width: 128px;" value="<?php  echo date("d-m-Y"); ?>" >
		   <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('deldte','ddMMyyyy')" style="cursor:pointer">
		   </td>       
	  	  </tr>
	 		
	  	  </table>
		 
		  <br><br>
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
				<input name="procouom[]" id="procouom" class="tInput" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 75px"></td>
                <td>
				<input name="procoprice[]" value="" class="tInput" id="procoprice1" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 75px; text-align : right"></td>
                <td>
				<input name="procoamount[]" value="" class="tInput" id="procoamount1" readonly="readonly" style="width: 75px; border:0; text-align : right"></td>
             </tr>
            </tbody>
           </table>
           
         <a href="#" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Item</span></a>
         <a href="javascript:deleteRow('itemsTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>

     <br /><br />
		 <table>
			<tr>
				<td valign="top">Remarks :</td>
        <td><textarea class="inputtxt" name="remark" id="remark1" COLS=60 ROWS=5></textarea></td>
			</tr>
    </table>  

     <br /><br />
     
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_po.php?menucd=".$var_menucode;
			
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
