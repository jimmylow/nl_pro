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
    	$vmfprodcd = $_POST['fselprcode'];
		$vmtprodcd = $_POST['prod_code'];
		$var_menucode  = $_POST['menudcode'];
    	
    	if ($vmtprodcd <> "") {
    	
    		 $sql = "select prod_desc from pro_cd_master";
     		 $sql .= " where prod_code ='".$vmtprodcd."'";
     		 $sql_result = mysql_query($sql);
     		 $row = mysql_fetch_array($sql_result);
     		 $tprocddesc = $row['prod_desc'];
    		
    		 $sql = "SELECT * FROM pro_jobmodel";
             $sql .= " Where prod_code='".$vmfprodcd."'"; 
	    	 $sql .= " ORDER BY prod_jobid";  
			 $rs_result = mysql_query($sql); 
			   
			 $vartoday = date("Y-m-d");  
			 while ($rowq = mysql_fetch_assoc($rs_result)){
			 		$jobid     = $rowq['prod_jobid'];
					$jobdesc   = $rowq['prod_jobdesc'];
					$jobrate    = $rowq['prod_jobrate'];
					$jobseq    = $rowq['prod_jobseq'];
					$jobsec    = $rowq['prod_jobsec'];
					$jobame    = $rowq['prod_jobdame'];

					if (empty($jobame)){
						$sql  = "INSERT INTO pro_jobmodel";
							$sql .=	" (prod_code, prod_desc, prod_jobseq, prod_jobid, prod_jobdesc, ";
							$sql .= "  prod_jobrate, prod_jobsec, modified_by, modified_on)";
							$sql .= "  values "; 
							$sql .= " ('$vmtprodcd', '$tprocddesc', '$jobseq', '$jobid', '$jobdesc', '$jobrate', '$jobsec',";
							$sql .= "  '$var_loginid', '$vartoday')";	
					}else{
						$sql = "INSERT INTO pro_jobmodel values 
							('$vmtprodcd', '$tprocddesc', '$jobseq','$jobid', '$jobdesc', '$jobrate', '$jobsec',
							'$jobame', '$var_loginid', CURDATE())";
					}
					mysql_query($sql) or die(mysql_error());
             }
             
             $backloc = "../bom_master/projob_rate1.php?menucd=".$var_menucode;
           	 echo "<script>";
           	 echo 'location.replace("'.$backloc.'")';
             echo "</script>"; 

		}else{
			$backloc = "../bom_master/projob_rate1.php?menucd=".$var_menucode;
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
<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>

<script type="text/javascript" charset="utf-8"> 
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
    var x=document.forms["InpColMas"]["fselprcode"].value;
	if (x==null || x=="")
	{
	alert("From Product Code Cannot Be Blank");
	document.InpColMas.fselprcode.focus();
	return false;
	}

 	var x=document.forms["InpColMas"]["prod_code"].value;
	if (x==null || x=="")
	{
	alert("Copy To Product Code Cannot Be Blank");
	document.InpColMas.prod_code.focus();
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
					  alert ('This To Product Code Not Found :'+x);
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

	//Check the from product code and rev no is same as to prod code and rev no--------------------------
	var fprodcode = document.forms["InpColMas"]["fselprcode"].value;
	var tprodcode = document.forms["InpColMas"]["prod_code"].value;
	
	var n = fprodcode.match(tprodcode);
	
	if (fprodcode == tprodcode){
			alert("Copy To Product Code Same As From Product Code.");
			document.InpColMas.prod_code.focus();
			return false;
	}
	//---------------------------------------------------------------------------------------------------

	//Check the duplicate of prod code and rev no--------------------------------------------------------
	var x      = document.forms["InpColMas"]["prod_code"].value;
	var flgchk = 1;
	var strURL="../bom_master/aja_chk_procdrate.php?procode="+x;
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
					  document.InpColMas.prod_code.focus();
					  alert ('This To Product Code Already Have a Job Rate Model :'+x);
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
<body onload="document.InpColMas.fselprcode.focus();">
 	
	
	<div class ="contentc">
	<fieldset name="Group1" style=" width: 583px; height: 332px;" class="style2">
	 <legend class="title">COPY PRODUCT JOB PAY RATE</legend>
	  <br>
	
	   <form name="InpColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 567px;" onsubmit="return validateForm()">
	  	<input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 165px" class="tdlabel">From Product Code<br>Job 
			Pay Rate</td>
	  	    <td>:</td> 
	  	    <td>
				<select name="fselprcode" style="width: 140px">
			 	<?php
                   $sql = "select distinct prod_code from pro_jobmodel ORDER BY prod_code";
                   $sql_result = mysql_query($sql);
                   echo "<option size =140 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['prod_code'].'">'.$row['prod_code'].'</option>';
				 	 } 
				   } 
	        	 ?>				   
	       	</select>
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td style="width: 165px" class="tdlabel"></td>
	  	    <td></td> 
            <td><div id="msgcd"></div></td> 
	   	  <tr>
	  	  	<td></td>
	  	  	<td></td>
	  	  	<td></td>
	  	  </tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td>To Product Code <br>Job Pay Rate</td>
	  	  	<td>:</td>
	  	  	<td><input class="autosearch" name="prod_code" id="prod_code" type="text" maxlength="15" style="width: 129px" onchange ="upperCase(this.id)">
	  	  	</td>
	  	  </tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td></td>
	  	  	<td></td>
	  	  </tr>
		  <tr>
	  	   <td></td>
	  	   <td style="width: 165px"></td>
	  	   <td></td>
	  	   <td>
	  	   <?php
				 $locatr = "projob_rate1.php?menucd=".$var_menucode;
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
	  	   ?>
	  	   <input type=submit name = "Submit" value="Copy" class="butsub" style="width: 60px; height: 32px" >
	  	   </td>
	  	  </tr>
	  	   <tr><td></td></tr>
	  	   <tr>
	  	     <td></td>
	  	     <td style="width: 165px"></td>
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
