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
    
   
    if ($_POST['Submit'] == "Search") {
    
    	 $varbuy = $_POST['sabuycd'];
    	 $varsal = mysql_real_escape_string($_POST['slordno']);
    	 
		 if ($varsal != ""){	
         	$backloc = "v_bom_print.php?&menucd=".$var_menucode."&b=".$varbuy."&s=".$varsal;
         	echo "<script>";
         	echo 'location.replace("'.$backloc.'")';
         	echo "</script>";
         }	     
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<style media="all" type="text/css">
@import "../css/styles.css";
@import "../css/demo_table.css";
thead th input { width: 90% }


.style2 {
	margin-right: 0px;
}
</style>

<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.nightly.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>

<script type="text/javascript" charset="utf-8"> 
			
function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
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

function checkValidSa(valSalNo){

	var e = document.getElementById("sabuycd");
    var selbuycd = e.options[e.selectedIndex].value;
	
	if (valSalNo != ""){
		var strURL="../sales_tran/aja_chk_ordernocnt.php?buyercd="+selbuycd+"&sordno="+valSalNo;
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
					    if (req.responseText != 0){
					    	document.getElementById("msgcd").innerHTML = "<font color=green>This Buyer & Sales Order No Is Valid.</font>";
					    }else{
					    	document.getElementById("msgcd").innerHTML = "<font color=red>Invalid Buyer & Sales Order No.</font>";
						}
					//} else {
					//	alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
					}
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
	}		
}


function validateForm()
{
    var x=document.forms["InpColMas"]["sabuycd"].value;
	if (x==null || x=="")
	{
		alert("Buyer Name Cannot Be Blank");
		document.InpColMas.sabuycd.focus();
		return false;
	}
	
	var x=document.forms["InpColMas"]["slordno"].value;
	if (x==null || x=="")
	{
		alert("Sales Order No Cannot Be Blank");
		document.InpColMas.slordno.focus();
		return false;
	}
	
	//------------------Check The Ref No ----------------------------------------------
	var chkflg = "1";
	var e = document.getElementById("sabuycd");
    var selbuycd = e.options[e.selectedIndex].value;
	  
	var strURL="../sales_tran/aja_chk_ordernocnt.php?buyercd="+selbuycd+"&sordno="+x;

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
				    	chkflg = 0;
				    	alert("Invalid Buyer & Sales Order No.");
					}
				} else {
					alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
				}
			}
		}
		req.open("GET", strURL, false);
		req.send(null);
	}
	
	if (chkflg == "0"){
		document.InpColMas.slordno.focus();
		return false;
	}
	//------------------Check The Ref No ----------------------------------------------
	
		//------------------Check The Order Is Cancel----------------------------------------------
	var chkflg = "1";
	var e = document.getElementById("sabuycd");
    var selbuycd = e.options[e.selectedIndex].value;
	  
	var strURL="../bom_tran/aja_chk_ordcan.php?buyercd="+selbuycd+"&sordno="+x;

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
				    if (req.responseText != 0){
				    	chkflg = 0;
				    	alert("This Sales Order Is Cancel");
					}
				} else {
					alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
				}
			}
		}
		req.open("GET", strURL, false);
		req.send(null);
	}
	
	if (chkflg == "0"){
		document.InpColMas.slordno.focus();
		return false;
	}
	//----------------------------------------------------------------

	
}	
</script>
</head>
<body onload="document.InpColMas.sabuycd.focus()">
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc">
	<fieldset name="Group1" style="width: 650px; height: 181px;" class="style2">
	 <legend class="title">BOM PRINTING</legend>
	  <form name="InpColMas" method="POST" onsubmit="return validateForm()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 632px;">
		<table>
		  <tr>
		  	<td></td>
		  	<td style="width: 144px">Buyer Name</td>
		  	<td>:</td>
		  	<td>
		  		<select name="sabuycd" id="sabuycd" style="width: 268px" onchange="AjaxOrdNo(this.value);">
			 <?php
              $sql = "select pro_buy_code, pro_buy_desc from pro_buy_master ORDER BY pro_buy_code ASC";
              $sql_result = mysql_query($sql);
              echo "<option size =30 selected></option>";
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['pro_buy_code'].'">'.$row['pro_buy_code']." | ".$row['pro_buy_desc'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>

		  	</td>
		  </tr>
	
		  <tr>
		  	<td></td>
		  </tr>	
		  <tr>
		  	<td></td>
		  	<td style="width: 144px">Sales Order No.</td>
		  	<td>:</td>
		  	<td><input class="inputtxt" name="slordno" id ="slordno" type="text" style="width: 268px" onblur="checkValidSa(this.value)" onchange ="upperCase(this.id)">
		  	</td>
		  </tr>	
	  	  <tr>
	  	    	<td></td> 
	  	    	<td style="width: 144px" class="tdlabel"></td>
	  	     	<td></td> 
	  	     	<td><div id="msgcd"></div></td> 
	   	  </tr>
	   	  <tr><td></td></tr> 
	  	  <tr> 
	  	   		<td></td>
	  	   		<td style="width: 144px"></td>
	  	   		<td></td>
	  	   		<td>
	  	    		<input type=submit name = "Submit" value="Search" class="butsub" style="width: 70px; height: 32px">
	  	   		</td>
	  	  </tr>
	  	  <tr>
	  	   		<td></td>
	  	   		<td style="width: 144px"></td>
	  	   		<td></td>
	  	   		<td style="width: 505px" colspan="7">
           		</td>
	  	  </tr>
	  	</table>
	   </form>	   
	</fieldset>
</div>
</body>

</html>

