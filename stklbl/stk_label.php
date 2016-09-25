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
    
    	 $varopt = $_POST['refnosel'];
    	 $varref = mysql_real_escape_string($_POST['reflblno']);
    	 
		 if ($varref != ""){	
         	$backloc = "../stklbl/stk_labelitm.php?&menucd=".$var_menucode."&opt=".$varopt."&ref=".$varref;
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

function chkvalid(valrefno){

	var e = document.getElementById("refnosel");
    var seloptv = e.options[e.selectedIndex].value;
	
	if (valrefno != ""){
		var strURL="aja_get_chklblref.php?opt="+seloptv+"&ref="+valrefno;
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
					    if (req.responseText == 1){
					    	document.getElementById("msgcd").innerHTML = "<font color=green>This Ref No Is Valid.</font>";
					    }else{
					    	document.getElementById("msgcd").innerHTML = "<font color=red>Invalid Ref No.</font>";
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

function displblsel(idselopt){

	var y = document.getElementById("seloptlbl");
	var x = document.getElementById("seloptcol");
	var z = document.getElementById("selinput");

	switch(idselopt)
	{
		case "0":
    		y.innerHTML = "Opening No";
    		x.innerHTML = ":";
    		z.innerHTML = '<input class="inputtxt" name="reflblno" id ="reflblno" type="text" maxlength="30" onchange ="upperCase(this.id)" onblur="chkvalid(this.value)" style="width: 200px">';
  			break;
		case "1":
    		y.innerHTML = "Receive No";
    		x.innerHTML = ":";
    		z.innerHTML = '<input class="inputtxt" name="reflblno" id ="reflblno" type="text" maxlength="30" onchange ="upperCase(this.id)" onblur="chkvalid(this.value)" style="width: 200px">';
  			break;
  		case "2":
    		y.innerHTML = "Supplier Inv No";
    		x.innerHTML = ":";
    		z.innerHTML = '<input class="inputtxt" name="reflblno" id ="reflblno" type="text" maxlength="30" onchange ="upperCase(this.id)" onblur="chkvalid(this.value)" style="width: 200px">';
  			break;
  		case "3":
    		y.innerHTML = "Receive Ref No";
    		x.innerHTML = ":";
    		z.innerHTML = '<input class="inputtxt" name="reflblno" id ="reflblno" type="text" maxlength="30" onchange ="upperCase(this.id)" onblur="chkvalid(this.value)" style="width: 200px">';
  			break;		
		default:
  			y.innerHTML = "";
    		x.innerHTML = "";
    		z.innerHTML = "";
	}
}

function validateForm()
{
    var x=document.forms["InpColMas"]["refnosel"].value;
	if (x==null || x=="")
	{
		alert("Select Option Ref No To Print Label");
		document.InpColMas.refnosel.focus();
		return false;
	}
	
	var x=document.forms["InpColMas"]["reflblno"].value;
	if (x==null || x=="")
	{
		alert("Put In The Ref No");
		document.InpColMas.reflblno.focus();
		return false;
	}
	
	//------------------Check The Ref No ----------------------------------------------
	var chkflg = "1";
	var e = document.getElementById("refnosel");
    var seloptv = e.options[e.selectedIndex].value;
	  
	var strURL="aja_get_chklblref.php?opt="+seloptv+"&ref="+x;
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
				    	chkflg = "0";
				    	alert("Invalid Ref No");
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
		document.InpColMas.reflblno.focus();
		return false;
	}	
}	
</script>
</head>
<body onload="document.InpColMas.refnosel.focus()">
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc">
	<fieldset name="Group1" style="width: 650px; height: 181px;" class="style2">
	 <legend class="title">CREATE STOCK LABEL</legend>
	  <form name="InpColMas" method="POST" onsubmit="return validateForm()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 632px;">
		<table>
		  <tr>
		  	<td></td>
		  	<td style="width: 144px">Option</td>
		  	<td>:</td>
		  	<td>
		  		<select name="refnosel" id="refnosel" onchange="displblsel(this.value)" style="width:150px;">
		  			<option></option>
		  			<option value="0">Opening No</option>
		  			<option value="1">Receive No</option>
		  			<option value="2">Supplier Inv No</option>
		  			<option value="3">Receive Ref No</option>
		  		</select>
		  	</td>
		  </tr>
		  <tr><td></td></tr>	
	  	  <tr>
	  	    <td></td>
	  	    <td style="width: 144px" class="tdlabel" id="seloptlbl"></td>
	  	    <td id="seloptcol"></td>
	  	    <td id="selinput"></td>
			<td></td>
	  	  </tr>
	  	  <tr>
	  	    	<td></td> 
	  	    	<td style="width: 144px" class="tdlabel"></td>
	  	     	<td></td> 
	  	     	<td><div id="msgcd"></div></td> 
	   	  </tr> 
	   
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

