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
		$var_bkno     = $_GET['bk'];	
      	$var_menucode = $_GET['menucd'];
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Preview") {
    	$reff   = $_POST['bookno'];
    	$itemc  = $_POST['selitm'];
    	$itmqty = $_POST['itmqty'];
    	$copyse = $_POST['vcopy'];
    
        $dest = "../book_tran/booklbltab.php?itmc=".$itemc."&ref=".$reff."&itmqt=".$itmqty."&secp=".$copyse;
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=600,width=400,left=100,top=100,scrollbars=yes');</script>";	
	
        $backloc = "../book_tran/bookslip.php?menucd=".$var_menucode."&bk=".$reff;
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

function get_itmdesc(itemcode)
{
    var refno   = document.getElementById("bookno").value;
   
	var strURL="aja_get_bkde.php?itmcod="+itemcode+"&ref="+refno;
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
	document.InpItmSel.vcopy.focus()
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
}		
</script>
</head>

<?php
?>

<body onload="document.InpItmSel.selitm.focus()">
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc">
	<fieldset name="Group1" style="width: 783px; height: 303px;" class="style2">
	 <legend class="title">STOCK LABEL / ITEM SELECT FOR <?php echo $voptdesc. " : ".$vref; ?></legend>
	  <form name="InpItmSel" method="POST" onsubmit="return validateForm()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 784px;">
		<table>
		   <tr>
		   		<td></td>
		   		<td>Booking No</td>
		   		<td>:</td>
		   		<td>
				<input class="inputtxt"  readonly="readonly" name="bookno" id ="bookno" type="text" style="width: 100px" value="<?php echo $var_bkno; ?>">
				</td>
		   </tr>
		   <tr><td></td></tr>	
	  	   <tr>
	   	  		<td></td>
	   	  		<td style="width: 153px">Item Code (<span class="style3">*</span>)</td>
	   	  		<td>:</td>
	   	  		<td>
	   	  			<select name="selitm" style="width: 208px" onchange="get_itmdesc(this.value)">
						<option></option>
			    		<?php
			    			$var_sql  = "Select distinct bookitm from booktab02 ";
			    			$var_sql .= " Where bookno = '$var_bkno'";
			    			$sql_result = mysql_query($var_sql) or die("Cant Get Item Code From Transaction Table".mysql_error()." ".$var_sql);       
				   			if(mysql_num_rows($sql_result)) 
				   			{
				   			 	while($row = mysql_fetch_assoc($sql_result)) 
				   				{ 
							  		echo '<option value="'.$row['bookitm'].'">'.$row['bookitm'].'</option>';
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
				 		$locatr = "m_booksys.php?menucd=".$var_menucode;
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

