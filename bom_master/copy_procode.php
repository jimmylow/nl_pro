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
    
    if ($_POST['Submit'] == "Copy") {
    	$fprocd = mysql_real_escape_string($_POST['prod_code']);
		$tcolcd = mysql_real_escape_string($_POST['selproclr']);
		$tsizcd = mysql_real_escape_string($_POST['procdsiz']);
		$var_menucode  = $_POST['menudcode'];
    	
    	if ($tcolcd <> "") 
    	{
    		 $sql = "select * from pro_cd_master";
     		 $sql .= " where prod_code ='".$fprocd."'";
     		 $sql_result = mysql_query($sql) or die("Query 1 :".mysql_error());
     		 $row = mysql_fetch_array($sql_result);
     		 
     		 $tbuy = $row['prod_buyer'];
     		 $ttyp = $row['prod_type'];
     		 $tpre = $row['prod_catpre'];
     		 $tdes = $row['prod_desc'];
     		 $tuom = $row['prod_uom'];
     		 $trmk = $row['prod_rmk'];
     		 $tcat = $row['pro_cat'];
     		 $tnum = $row['pronumcode'];
     		 
     		 $exfacpcs= $row['xfac_pcsprice'];
     		 $exfacdoz= $row['xfac_dozprice'];
     		 $costpcs= $row['cost_pcsprice'];
     		 $costdoz= $row['cost_dozprice'];
     		 $tax= $row['tax'];    		
     		 $buyprocd = htmlentities($row['pro_buycd']); 
     		 
     		 $tdes = str_replace("'", '^', $tdes);
			 $trmk = str_replace("'", '^', $trmk);

     		 $fdesccd = strpos($fprocd, "-");
     		 $subtcd  = substr($fprocd, 0, $fdesccd);
     		 $tprocd  = $subtcd."-".$tcolcd."-".$tsizcd;
    		
			 $sql = "INSERT INTO pro_cd_master values 
					('$tprocd', '$tbuy', '$ttyp', '$tpre', '$tsizcd', '$tcolcd', '$tdes', '$tuom',
					 '$trmk', '', '$var_loginid', CURDATE(), '$var_loginid', CURDATE(), '$tcat',
					 'A', '$tnum', '$exfacpcs','$exfacdoz', '$costpcs','$costdoz', '$tax', '$buyprocd')";
					 
			 mysql_query($sql) or die(mysql_error());
			 
			 echo "<script>";   
      		 echo "alert('Product Code Created :".$tprocd."');"; 
      		 echo "</script>";
      		 
      		 $backloc = "../bom_master/pro_code_master.php?menucd=".$var_menucode;
           	echo "<script>";
           	echo 'location.replace("'.$backloc.'")';
            echo "</script>"; 

             
		}else{
			$backloc = "../bom_master/pro_code_master.php?menucd=".$var_menucode;
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
@import "../css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css";
@import "../css/styles.css";

.style2 {
	margin-right: 0px;
}
</style>

<script type="text/javascript" src="../js/datetimepicker_css.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>
<script type="text/javascript" src="../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../js/Parsers.js"></script>
<script type="text/javascript" src="../js/InputMask.js"></script>

<script type="text/javascript"> 
$(document).ready(function(){
	var ac_config = {
		source: "../bom_master/autocomscrpro1.php",
		select: function(event, ui){
			$("#prod_code").val(ui.item.prod_code);
		
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
    var x=document.forms["InpColMas"]["prod_code"].value;
	if (x==null || x=="")
	{
	alert("From Product Code Cannot Be Blank");
	document.InpColMas.prod_code.focus();
	return false;
	}

 	var x=document.forms["InpColMas"]["selproclr"].value;
	if (x==null || x=="")
	{
	alert("Copy To Color Product Code Cannot Be Blank");
	document.InpColMas.selproclr.focus();
	return false;
	}
	
	var x=document.forms["InpColMas"]["procdsizid"].value;
	if (x==null || x=="")
	{
		alert("Copy To Size Product Code Cannot Be Blank");
		document.InpColMas.procdsizid.focus();
		return false;
	}

	//Check the product Code Valid--------------------------------------------------------
	var flgchk = 1;
	var x=document.forms["InpColMas"]["prod_code"].value;
	var strURL="../bom_master/aja_chk_procode.php?procode="+x;
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
					  document.InpColMas.prod_code.focus();
					  alert ('Invalid From Product Code :'+x);
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
    if (flgchk == 0){
	   return false;
	}
	//---------------------------------------------------------------------------------------------------

	//Check Is Same From Product Code & To Color Code
	var x=document.forms["InpColMas"]["prod_code"].value;
	var y=document.forms["InpColMas"]["selproclr"].value;
	var z=document.forms["InpColMas"]["procdsizid"].value;
	var n = x.indexOf('-');
	var subpre = x.substring(0, n)
	var tprocd = subpre+"-"+z+"-"+y;
	
	if (tprocd == x){
		alert("Cannot Copy For From Product Code Same As To Product Code");
		document.InpColMas.selproclr.focus();
		return false;
	}
	//--------------------------------------------------------
	
	
	//Check the Product Code Is Exits--------------------------------------------------------
	var flgchk = 1;
    var strURL="aja_chk_procdcp.php?procd="+tprocd;
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
					if (req.responseText != 0)
					{
					  flgchk = 0;
					  document.InpProCDMas.procdsiz.focus();
					  alert ('This Color & Size Product Code Is Already Create');
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
    if (flgchk == 0){
	   return false;
	}
	//---------------------------------------------------------------------------------------------------
}		
</script>
</head>
<?php include("../topbarm.php"); ?> 
 	<!--<?php include("../sidebarm.php"); ?>-->
<body onload="document.InpColMas.prod_code.focus();">
 	
	
	<div class ="contentc">
	<fieldset name="Group1" style=" width: 623px;" class="style2">
	 <legend class="title">COPY PRODUCT CODE</legend>
	  <br>
	
	   <form name="InpColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 275px; width: 607px;" onsubmit="return validateForm()">
	  	<input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table>
	  	  <tr>
	  	  	<td></td>
	  	  	<td style="width: 192px">From Product Code <br></td>
	  	  	<td>:</td>
	  	  	<td>
			<input class="autosearch" type="text" name="prod_code" id="prod_code" style="width: 158px" onchange ="upperCase(this.id)">
	  	  	</td>
	  	  </tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td></td>
	  	  	<td></td>
	  	  </tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td>To Color Code</td>
	  	  	<td>:</td>
	  	  	<td style="width: 336px">
	  	  		<select name="selproclr" style="width: 206px">
			 		<?php
                   		$sql = "select clr_code, clr_desc from pro_clr_master ORDER BY clr_code";
                   		$sql_result = mysql_query($sql);
                   		echo "<option size =30 selected></option>";
                       
				   		if(mysql_num_rows($sql_result)) 
				   		{
				   	 		while($row = mysql_fetch_assoc($sql_result)) 
				     		{ 
					 		 echo '<option value="'.$row['clr_code'].'">'.$row['clr_code'].' | '.$row['clr_desc'].'</option>';
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
	  	  	<td>To Size</td>
	  	  	<td>:</td>
	  	  	<td>
			<input class="inputtxt" name="procdsiz" id ="procdsizid" type="text" maxlength="10" onchange ="upperCase(this.id)"></td>
	  	  </tr>
	  	  <tr>
	  	  	<td></td>
	  	  </tr>
		  <tr>
	  	   <td></td>
	  	   <td style="width: 192px"></td>
	  	   <td></td>
	  	   <td style="width: 336px">
	  	   <?php
				 $locatr = "pro_code_master.php?menucd=".$var_menucode;
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
	  	   ?>
	  	   <input type=submit name = "Submit" value="Copy" class="butsub" style="width: 60px; height: 32px" >
	  	   </td>
	  	  </tr>
	  	   <tr><td></td></tr>
	  	   <tr>
	  	     <td></td>
	  	     <td style="width: 192px"></td>
	  	     <td></td>
	  	  </tr>

	  	</table>
	   </form>	
	   <br>
	 </fieldset>
	 </div>
    <div class="spacer"></div>
</body>

</html>
