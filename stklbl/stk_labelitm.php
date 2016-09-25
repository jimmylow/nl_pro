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
		$vopt = $_GET['opt'];	
		$vref = htmlentities($_GET['ref']);	
      	$var_menucode = $_GET['menucd'];
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Preview") {
    	$reff   = mysql_real_escape_string($_POST['refno']);
    	$refd   = $_POST['refopt'];
    	$itemc  = $_POST['selitm'];
    	$itmqty = $_POST['itmqty'];
    	$copyse = $_POST['vcopy'];
    
        //header("Location: $dest" );
        $dest = "../stklbl/stk_lbltab.php?itmc=".$itemc."&ref=".$reff."&refd=".$refd."&itmqt=".$itmqty."&secp=".$copyse;
       // echo "<script language=\"javascript\">";
		//echo " window.open('".$dest."','STOCK LABEL PREVIEW','height=800,width=600,left=100,top=100');";
		//echo "	if (window.focus) {newwindow.focus()}";
		//echo "</script>";
        //newwindow=window.open(url,'name','height=200,width=600,left=200,top=250');

        echo "<script language=\"javascript\">window.open('".$dest."','name','height=600,width=800,left=100,top=100,scrollbars=yes');</script>";	
	
	
        $backloc = "../stklbl/stk_labelitm.php?menucd=".$var_menucode."&ref=".$reff."&opt=".$refd;
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
.style3 {
	color: #FF0000;
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

function chk_creitm(itemcode)
{
	var refno   = document.getElementById("refno").value;
    var reffrom = document.getElementById("reffrom").innerHTML;
    
    switch(reffrom)
	{
		case "Opening No":
			refopt = 0;
  			break;
		case "Receive No":
  			refopt = 1;
  			break;
  		case "Supplier Inv No":
    		refopt = 2;
  			break;
  		case "Receive Ref No":
  		    refopt = 3;
  			break;		
	}

	var strURL="aja_chk_lblcre.php?itmcod="+itemcode+"&ref="+refno+"&refd="+refopt;
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
						document.getElementById("statcre").innerHTML = "<font color=red> This Item Have Been Create Label</font>";
					}else{
						get_itmdesc(itemcode, refopt);
						document.getElementById("statcre").innerHTML = "";
					}
				} 
			}
		}	 
	}
	req.open("GET", strURL, true);
	req.send(null);
}

function get_itmdesc(itemcode, refopt)
{
    var refno   = document.getElementById("refno").value;
   
	var strURL="aja_get_itmdescqty.php?itmcod="+itemcode+"&ref="+refno+"&refd="+refopt;
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
						document.getElementById("itmdesc").value = obj.desc;
						document.getElementById("itmqty").value = obj.qty;
						document.getElementById("vcopy").focus();
						
					}else{
						document.getElementById("itmdesc").value = "";
						document.getElementById("itmqty").value = "";
					}	
				} 
			}
		}	 
	}
	req.open("GET", strURL, true);
	req.send(null);
}

function chkvaluedec(vvar, vvid)
{	
	
	if (vvar != ""){
		if(isNaN(vvar)) {
    	   alert('Please Enter a valid number:' + vvar);
    	   vvar = 0;
    	}else{
    	    if(vvar < 0) {
    	   		alert('Not Accept Negative Value Or Zero:' + vvar);
    	   		vvar = 0;
    		}
    	}
    	
    	document.getElementById(vvid).value = vvar;
    	document.getElementById("vcopy").value = parseFloat(vvar).toFixed(0);
    }else{
    	vvar = 0;
    	document.getElementById("vcopy").value = parseFloat(vvar).toFixed(0);
    }

}

function validateForm()
{
    var x=document.forms["InpItmSel"]["selitm"].value;
	if (x==null || x=="")
	{
		alert("Select Item To Print Stock Label");
		document.InpItmSel.selitm.focus();
		return false;
	}
	
	var x=document.forms["InpItmSel"]["itmqty"].value;
	if (x==null || x=="")
	{
		alert("Total Qty Cannot Be Blank");
		document.InpItmSel.itmqty.focus();
		return false;
	}
	
	if(isNaN(x)){
    	alert('Please Enter a valid number for Total Qty:' + x);
    	document.InpItmSel.itmqty.focus();
    	return false;
    }
    if(x <= 0) {
    	alert('Not Accept Negative Value Or Zero:' + x);
    	document.InpItmSel.itmqty.focus();
    	return false;
    }

	var x=document.forms["InpItmSel"]["vcopy"].value;
	if (x==null || x=="")
	{
		alert("Copies Cannot Be Blank");
		document.InpItmSel.vcopy.focus();
		return false;
	}
	
	if(isNaN(x)){
    	alert('Please Enter a valid number for Copies:' + x);
    	document.InpItmSel.vcopy.focus();
    	return false;
    }
    if(x <= 0) {
    	alert('Not Accept Negative Value Or Zero:' + x);
    	document.InpItmSel.vcopy.focus();
    	return false;
    }
    
    var flgchk = 0;
    var refno = document.getElementById("refno").value;
    var itmcd = document.forms["InpItmSel"]["selitm"].value;    
	var reffrom = document.getElementById("reffrom").innerHTML;
    
    switch(reffrom)
	{
		case "Opening No":
			refopt = 0;
  			break;
		case "Receive No":
  			refopt = 1;
  			break;
  		case "Supplier Inv No":
    		refopt = 2;
  			break;
  		case "Receive Ref No":
  		    refopt = 3;
  			break;		
	}
	
    
	var strURL="aja_get_itmdescqty.php?itmcod="+itmcd+"&ref="+refno+"&refd="+refopt;
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
						alert ('This Item Have Been Create Label');
						flgchk = 1;
					}	
				} 
			}
		}	 
	}

	req.open("GET", strURL, false);
	req.send(null);
	if (flgchk == 1){
		document.forms["InpItmSel"]["selitm"].focus(); 
		return false;
	}
}		
</script>
</head>

<?php
	switch ($vopt)
	{
		case "0":
			$voptdesc = "Opening No";
			$var_sql = " SELECT item_code from rawmat_opening_tran ";
    		$var_sql .= " WHERE rm_opening_id = '$vref'";
			break;
		case "1":
			$voptdesc = "Receive No";
			$var_sql = " SELECT item_code from rawmat_receive_tran ";
    		$var_sql .= " WHERE rm_receive_id = '$vref'";
			break;
  		case "2":
  			$voptdesc = "Supplier Inv No";
  			$var_sql = " SELECT y.item_code from rawmat_receive_tran y, rawmat_receive x ";
    		$var_sql .= " WHERE x.rm_receive_id = y.rm_receive_id";
    		$var_sql .= " AND   x.invno = '$vref'";
  			break;	
		case "3":
			$voptdesc = "Receive Ref No";
			$var_sql = " SELECT item_code from rawmat_receive_tran ";
    		$var_sql .= " WHERE refno = '$vref'";
  			break;	
		default:
 			$voptdesc = "";
 			$var_sql  = "";
	} 	
?>

<body onload="document.InpItmSel.selitm.focus()">
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc">
	<fieldset name="Group1" style="width: 783px; height: 303px;" class="style2">
	 <legend class="title">STOCK LABEL / ITEM SELECT FOR <?php echo $voptdesc. " : ".$vref; ?></legend>
	  <form name="InpItmSel" method="POST" onsubmit="return validateForm()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 784px;">
		<input name="refno" type="hidden" value="<?php echo $vref;?>">
		<input name="refopt" type="hidden" value="<?php echo $vopt;?>">
		<table>
		   <tr>
		   		<td></td>
		   		<td id="reffrom"><?php echo $voptdesc; ?></td>
		   		<td>:</td>
		   		<td>
				<input class="inputtxt"  readonly="readonly" name="refno" id ="refno" type="text" style="width: 206px" value="<?php echo $vref; ?>"></td>
		   </tr>
		   <tr><td></td></tr>	
	  	   <tr>
	   	  		<td></td>
	   	  		<td style="width: 153px">Item Code (<span class="style3">*</span>)</td>
	   	  		<td>:</td>
	   	  		<td>
	   	  			<select name="selitm" style="width: 208px" onchange="chk_creitm(this.value)">
						<option></option>
			    		<?php 
			    			$sql_result = mysql_query($var_sql) or die("Cant Get Item Code From Transaction Table".mysql_error()." ".$var_sql);       
				   			if(mysql_num_rows($sql_result)) 
				   			{
				   			 	while($row = mysql_fetch_assoc($sql_result)) 
				   				{ 
							  		echo '<option value="'.$row['item_code'].'">'.$row['item_code'].'</option>';
				 				} 
				   			} 
	            		?>				   
			  		</select>
	   	  		</td>
	   	  </tr>
	   	  <tr>
	   	  	<td></td>
	   	  	<td></td>
	   	  	<td></td>
	   	  	<td><div id="statcre"></div></td>
	   	  </tr>  
	  	  <tr>
	   	    	<td></td>
	  	    	<td style="width: 153px" class="tdlabel">Item Description</td>
	  	    	<td>:</td>
	  	    	<td>
				<input class="inputtxt"  readonly="readonly" name="itmdesc" id ="itmdesc" type="text" style="width: 368px">
				</td>
	  	  </tr> 
	  	  <tr>
	   	    	<td></td>
	  	    	<td style="width: 153px" class="tdlabel"></td>
	  	    	<td></td>
	  	    	<td></td>
	  	  </tr> 
	  	  <tr>
	   	    	<td></td>
	  	    	<td style="width: 153px" class="tdlabel">Total Qty</td>
	  	    	<td>:</td>
	  	    	<td>
				<input class="inputtxt" name="itmqty" id ="itmqty" type="text" readonly="readonly" style="width: 131px" onblur="chkvaluedec(this.value, this.id)">
				</td>
	  	  </tr> 
	  	  <tr><td></td></tr>
	  	  <tr>
	   	    	<td></td>
	  	    	<td style="width: 153px" class="tdlabel">Copies</td>
	  	    	<td>:</td>
	  	    	<td>
				<input class="inputtxt" name="vcopy" id ="vcopy" type="text" maxlength="10" onblur="chkvaluedec(this.value, this.id)"></td>
	  	  </tr>
	  	  <tr><td></td></tr>
	  	  <tr> 
	  	   		<td style="height: 39px"></td>
	  	   		<td style="width: 153px; height: 39px;"></td>
	  	   		<td style="height: 39px"></td>
	  	   		<td style="height: 39px">
	  	   			<?php
				 		$locatr = "stk_label.php?menucd=".$var_menucode;
				 		echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				 	?>
	  	    		<input type=submit name = "Submit" value="Preview" class="butsub" style="width: 70px; height: 32px">
	  	  		</td>
	  	  </tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td></td>
	  	  	<td></td>
	  	  	<td>(<span class="style3">*</span>) - Select Item Generate From Ref No.</td>
	  	  </tr>
	  	</table>
	   </form>	  
	</fieldset>
</div>
</body>

</html>

