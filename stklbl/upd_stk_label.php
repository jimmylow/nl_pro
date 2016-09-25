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
    }else{
      	$var_menucode = $_GET['menucd'];
      	include("../Setting/ChqAuth.php");
    }

    if (isset($_POST['Submit'])){ 
    	if ($_POST['Submit'] == "Cancel"){
			$vrefno = $_POST['refno'];
			
			if ($vrefno != ""){
				foreach($_POST['sitmcd'] as $row=>$itmcd ) {
					$mitmcd = $itmcd;
									   	  
					$sqli  = "Delete From stck_lbl";
					$sqli .= " Where refno = '$vrefno '";
				 	$sqli .= " And sub_code = '$mitmcd'";
					mysql_query($sqli) or die("Can't Delete From Label Table :".mysql_error());;
				}  
           	}
           	echo "<script>";   
      		echo "alert('Cancel Stock Label Success');"; 
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

function chkrefcre(refnop, refid){

	if (refnop != ""){
		var krefid = 1;
		var strURL="aja_get_chkref.php?ref="+refnop+"&pid="+krefid;
		var req = getXMLHTTP();

		var chkflg = 0;
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
					 		 alert("This Reference No. Not Create Label Yet : "+refnop);
					 		 document.getElementById('statsel').innerHTML = "";
							 chkflg = 1;
						}else{
							  document.getElementById('statsel').innerHTML=req.responseText;	 					
						}
					} 
				}
			}	 
		}
		req.open("GET", strURL, false);
		req.send(null);
		if (chkflg == 1){
			 document.getElementById(refid).focus();	
		}
	}	
}

function getnooflbl(itmcd, itmcdid, rowid){
	if (itmcd != ""){

		var refnop  = document.getElementById('refno').value;		
		var strURL  ="aja_get_lblno.php?ref="+refnop+"&titm="+itmcd;
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
						document.getElementById('vcopy').value = req.responseText;
						get_itmdesc(itmcd);
					} 
				}
			}	 
		}
		req.open("GET", strURL, true);
		req.send(null);
			
	}
}

function validateForm()
{
    var x=document.forms["InpItmSel"]["refno"].value;
	if (x==null || x=="")
	{
		alert("Ref No Cannot Be Blank");
		document.InpItmSel.refno.focus();
		return false;
	}
	
	var x=document.forms["InpItmSel"]["sitmcd1"].value;
	if (x==null || x=="")
	{
		alert("Item No Cannot Be Blank");
		document.InpItmSel.sitmcd1.focus();
		return false;
	}
	    
    var krefid = 1;
    var refnop = document.forms["InpItmSel"]["refno"].value;
	var strURL ="aja_get_chkref.php?ref="+refnop+"&pid="+krefid;
	var req = getXMLHTTP();

	var chkflg = 0;
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
				 		 alert("This Reference No. Not Create Label Yet : "+refnop);
							 chkflg = 1;
					} 
				}
			}	 
		}
	}	
	req.open("GET", strURL, false);
	req.send(null);
	if (chkflg == 1){
		 document.getElementById("refno").focus();
		 return false	
	}
}	

</script>
</head>

<body onload="document.InpItmSel.refno.focus();">
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc">
	<fieldset name="Group1" style="width: 600px; height: 250px;">
	 <legend class="title">CANCEL STOCK LABEL</legend>
	 
	  <form name="InpItmSel" method="POST" onsubmit="return validateForm()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 784px;">

		<table>
		   <tr>
		   		<td></td>
		   		<td>Ref No</td>
		   		<td>:</td>
		   		<td>
				<input class="inputtxt" name="refno" id ="refno" type="text" style="width: 200px" maxlength="50" onchange="upperCase(this.id)" onblur="chkrefcre(this.value, this.id)">
				</td>
		   </tr>
		   <tr><td></td></tr>	
	  	   <tr>
	   	  		<td></td>
	   	  		<td style="width: 122px">Item Code</td>
	   	  		<td>:</td>
	   	  		<td><div id="statsel"></div>
	   	  		</td>
	   	  </tr>
	   	
	  	  <tr>
	   	    	<td></td>
	  	    	<td style="width: 122px" class="tdlabel"></td>
	  	    	<td></td>
	  	    	<td></td>
	  	  </tr> 
	  	  <tr>
	   	    	<td></td>
	  	    	<td style="width: 122px" class="tdlabel">Copies</td>
	  	    	<td>:</td>
	  	    	<td>
				<input class="inputtxt" name="vcopy" id ="vcopy" type="text" maxlength="10" readonly="readonly"></td>
	  	  </tr>
	  	  <tr><td></td></tr>
	  	  <tr> 
	  	   		<td></td>
	  	   		<td style="width: 122px;"></td>
	  	   		<td></td>
	  	   		<td>
	   	    		<input type=submit name = "Submit" value="Cancel" class="butsub" style="width: 70px; height: 32px">
	  	  		</td>
	  	  </tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td style="width: 122px"></td>
	  	  	<td></td>
	  	  	<td></td>
	  	  </tr>
	  	</table>
	   </form>	  
	</fieldset>
</div>
</body>

</html>

