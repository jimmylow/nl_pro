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
	  $var_custcd = $_GET['custcd'];
      include("../Setting/ChqAuth.php");
    }
    
    if ($_POST['Submit'] == "Update") {
     $custcd   = $_POST['custcd'];
     $custstat = $_POST['selactive'];
     $custname = $_POST['custname'];
     $custcurr = $_POST['selcurr'];
     $custloovs = $_POST['selloovl'];
     $custterm = $_POST['selterm'];
     $custpmode = $_POST['selpmode'];
     $custweb = $_POST['custweb'];
     $custrmk = $_POST['custrmk'];
     
     $custadd1 = $_POST['custadd1'];
     $custadd2 = $_POST['custadd2'];
     $custcity = $_POST['custcity'];
     $custpostcd = $_POST['custpostcd'];
     $custstate = $_POST['custstate'];
     $custcount = $_POST['selcount'];
     $custtel  = $_POST['custtel'];
     $custmob  = $_POST['mobilen'];
     $custfax  = $_POST['custfax'];
     $custconppl = $_POST['custconppl'];
     $custeml    = $_POST['custeml'];
     
     $ageadd1 = $_POST['ageadd1'];
     $ageadd2 = $_POST['ageadd2'];
     $agecity = $_POST['agecity'];
     $agepostcd = $_POST['agepost'];
     $agestate = $_POST['agestate'];
     $agecount = $_POST['agecount'];
     $agetel  = $_POST['agetel'];
     $agemob  = $_POST['agemob'];
     $agefax  = $_POST['agefax'];
     $ageconppl = $_POST['ageconppl'];
     $ageeml    = $_POST['ageeml'];
	 $gstno     = $_POST['gstno'];
     
    	 if ($custcd <> "") {
 
    	     $vartoday = date("Y-m-d H:i:s");
    	     $sql = "Update customer_master Set customer_desc = '$custname', ";
    	     $sql .= "							active_flag = '$custstat', ";
    	     $sql .= "							currency_code = '$custcurr', ";
    	     $sql .= "							address = '$custadd1', ";
    	     $sql .= "							address_2 = '$custadd2', ";
    	     $sql .= "							website = '$custweb', ";
    	     $sql .= "							telephone = '$custtel', ";
    	     $sql .= "							mobileno = '$custmob', ";
    	     $sql .= "							fax = '$custfax', ";
    	     $sql .= "							contact_person = '$custconppl', ";
    	     $sql .= "							email = '$custeml',";
    	     $sql .= "							modified_by = '$var_loginid', ";
    	     $sql .= "							modified_on = '$vartoday', ";
    	     $sql .= "							loovl = '$custloovs', ";
    	     $sql .= "							termcd = '$custterm', ";
    	     $sql .= "							paymode = '$custpmode', ";
    	     $sql .= "							remark = '$custrmk', ";
    	     $sql .= "							custcity = '$custcity', ";
    	     $sql .= "							custpostcd = '$custpostcd', ";
    	     $sql .= "							custstate = '$custstate', ";
    	     $sql .= "							custcountry = '$custcount', ";
    	     $sql .= "							ageadd1 = '$ageadd1', ";
    	     $sql .= "							ageadd2 = '$ageadd2', ";
    	     $sql .= "							agecity = '$agecity', ";
    	     $sql .= "							agepost = '$agepostcd', ";
    	     $sql .= "							agestate = '$agestate', ";
    	     $sql .= "							agecountry = '$agecount', ";
    	     $sql .= "							agetel = '$agetel', ";
    	     $sql .= "							agemobile = '$agemob', ";
    	     $sql .= "							agefax = '$agefax', ";
    	     $sql .= "							ageconppl = '$ageconppl', ";
    	     $sql .= "							ageemail = '$ageeml', gstno = '$gstno' ";
    	     $sql .= " Where customer_code = '$custcd'";
    	    
    	     mysql_query($sql) or die(mysql_error()); 
              
    	 	 $backloc = "../main_mas/m_cust_mas.php?stat=1&menucd=".$var_menucode;
    	     echo "<script>";
    	     echo 'location.replace("'.$backloc.'")';
    	     echo "</script>";      
    	}else{
    	     $backloc = "../main_mas/cust_mas.php?stat=4&menucd=".$var_menucode;
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
.style4 {
	color: #FF0000;
	font-weight:bold;
}
.style5 {
	color: #FF0000;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>


<script type="text/javascript" charset="utf-8">
$(function(){
  $('legend').click(function(){
   $(this).siblings().slideToggle("slow");
  });
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

function AjaxFunction(email)
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
		document.getElementById("msg").innerHTML=httpxml.responseText;
	  }
    }
	
	var url="../Setting/email-ajax.php";
	
	url=url+"?email="+email;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",url,true);
	httpxml.send(null);
}	

function validateForm()
{
    var x=document.forms["InpCustMas"]["custnmid"].value;
	if (x==null || x=="")
	{
	alert("Customer Name Cannot Be Blank");
	document.InpCustMas.custnmid.focus();
	return false;
	}
	
	var x=document.forms["InpCustMas"]["custadd1id"].value;
	if (x==null || x=="")
	{
	alert("Customer Address Cannot Be Blank");
	document.InpCustMas.custadd1id.focus();
	return false;
	}
	
	var x=document.forms["InpCustMas"]["custcity"].value;
	if (x==null || x=="")
	{
	alert("Customer Address City Cannot Be Blank");
	document.InpCustMas.custcity.focus();
	return false;
	}
	
	var x=document.forms["InpCustMas"]["custpostcd"].value;
	if (x==null || x=="")
	{
	alert("Customer Address Postal Code Cannot Be Blank");
	document.InpCustMas.custpostcd.focus();
	return false;
	}
	
	var x=document.forms["InpCustMas"]["custstateid"].value;
	if (x==null || x=="")
	{
	alert("Customer Address State Cannot Be Blank");
	document.InpCustMas.custstateid.focus();
	return false;
	}
	
	var x=document.forms["InpCustMas"]["selcount"].value;
	if (x==null || x=="")
	{
	alert("Customer Address Country Cannot Be Blank");
	document.InpCustMas.selcount.focus();
	return false;
	}

	var x=document.forms["InpCustMas"]["custtel"].value;
	if (x==null || x=="")
	{
	alert("Customer Contact Telephone Cannot Be Blank");
	document.InpCustMas.custtel.focus();
	return false;
	}
	
	var x=document.forms["InpCustMas"]["custconpplid"].value;
	if (x==null || x=="")
	{
	alert("Customer Contact Person Name Cannot Be Blank");
	document.InpCustMas.custconpplid.focus();
	return false;
	}
}	
</script>
</head>
  
  <!--<?php include("../sidebarm.php"); ?>-->
  
  <?php
        $sql = "select * ";
        $sql .= " from customer_master";
        $sql .= " where customer_code ='".$var_custcd."'";      
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);

        $custname = $row['customer_desc'];
        $custstat = $row['active_flag'];
        $custcurr = $row['currency_code'];
        $custloovl = $row['loovl'];
        $custterm = $row['termcd'];
        $custptecd = $row['paymode'];
        $custweb = $row['website'];
        $custrmk = $row['remark'];
        $custadd1 = $row['address'];
        $custadd2 = $row['address_2'];
        $custcity = $row['custcity'];
        $custpostcd = $row['custpostcd'];
        $custstate = $row['custstate'];
        $custcouncd = $row['custcountry'];
        $custtel= $row['telephone'];
        $custmob= $row['mobileno'];
        $custfax= $row['fax'];
        $custppl= $row['contact_person'];
        $custeml= $row['email'];
        
        $ageadd1 = $row['ageadd1'];
        $ageadd2 = $row['ageadd2'];
        $agecity = $row['agecity'];
        $agepostcd = $row['agepost'];
        $agestate = $row['agestate'];
        $agecouncd = $row['agecountry'];
        $agetel= $row['agetel'];
        $agemob= $row['agemobile'];
        $agefax= $row['agefax'];
        $ageppl= $row['ageconppl'];
        $ageeml= $row['ageemail'];
         $gstno= $row['gstno'];
           
        $sql = "select country_desc from country_master  ";
        $sql .= " where country_code ='".$custcouncd."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $custcountde = $row[0];
        
        $sql = "select term_desc from term_master  ";
        $sql .= " where term_code ='".$custterm."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $custtermde = $row[0];
        
        $sql = "select pmdesc from pay_m_master  ";
        $sql .= " where pmcode ='".$custptecd."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $custpde = $row[0];
		
		$sql = "select country_desc from country_master  ";
        $sql .= " where country_code ='".$agecouncd."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $agecountde = $row[0];
    ?>		

<body onload="document.InpCustMas.custnmid.focus();">
 <?php include("../topbarm.php"); ?> 
  <div class="contentc">

	<fieldset name="Group1" style=" width: 1045px; height: 810px;" class="style2">
	 <legend class="title">CUSTOMER MASTER - <?php echo $var_custcd;?> (<?php echo $custname;?>)</legend>
	  <br>
	
	  <form name="InpCustMas" method="POST" onsubmit="return validateForm()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 970px;">
	   <table style="width: 1022px">
	    <tr>
	      <td></td>
	      <td class="tdlabel" style="width: 218px">Customer Code</td>
	      <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="custcd" id ="custcdid" type="text" readonly="readonly" style="width: 94px" value="<?php echo $var_custcd;?>">
		  </td>
		  <td></td>
		  <td class="tdlabel" style="width: 167px">Status</td>
	  	  <td>:</td>
	  	  <td>
		   <select name="selactive" style="width: 125px">
		    <?php echo '<option>'.$custstat.'</option>';?>
		    <option>ACTIVE</option>
		    <option>DEACTIVATE</option>
		   </select>
		  </td>
	  	 </tr>
	  	 <tr>
	  	  <td></td> 
	  	  <td style="width: 218px"></td>
	  	  <td></td> 
          <td></td>
	   	 </tr> 
	   	 <tr>
	   	  <td></td>
	  	  <td class="tdlabel" style="width: 218px">Customer Name<span class="style4">*</span></td>
	  	  <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="custname" id ="custnmid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 396px" value="<?php echo $custname;?>">
		  </td>
		  <td></td>
		  <td class="tdlabel" style="width: 167px">Currency Code</td>
	  	  <td>:</td>
	  	  <td>
		   <select name="selcurr" style="width: 68px">
			 <?php
                   $sql = "select currency_code, currency_desc from currency_master ORDER BY currency_code ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected>".$grpcd."</option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['currency_code'].'">'.$row['currency_code'].'</option>';
				 	 } 
				   } 
	         ?>				   
	       </select>
		  </td>
	  	 </tr>
	  	 <tr><td></td></tr>
         <tr>
          <td></td>
          <td style="width: 218px">Local/Oversea</td>
          <td>:</td>
          <td>
			<select name="selloovl" style="width: 125px">
				<?php echo '<option>'.$custloovl.'</option>';?>
			    <option>LOCAL</option>
			    <option>OVERSEA</option>
			</select></td>
		  <td></td>
		  <td style="width: 167px">Terms</td>
		  <td>:</td>
		  <td>
			<select name="selterm" style="width: 165px">
			    <?php
                   $sql = "select term_code, term_desc from term_master ORDER BY term_desc ASC";
                   $sql_result = mysql_query($sql);
                   echo '<option size="40" value="'.$custterm.'">'.$custtermde.'</option>';
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['term_code'].'">'.$row['term_desc'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select></td> 	
         </tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td style="width: 218px">Pay Mode</td>
          <td>:</td>
          <td>
			  <select name="selpmode" style="width: 221px">
			    <?php
                   $sql = "select pmcode, pmdesc from pay_m_master ORDER BY pmdesc ASC";
                   $sql_result = mysql_query($sql);
                   echo '<option size="40" value="'.$custptecd.'">'.$custpde.'</option>';
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['pmcode'].'">'.$row['pmdesc'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select>
		  </td>
          <td></td>
          <td style="width: 167px">Website</td>
          <td>:</td>
          <td>
			<input class="inputtxt" name="custweb" id ="custwebid" type="text" maxlength="50" style="width: 345px" value="<?php echo $custweb; ?>"></td>
         </tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td style="width: 218px">Remark</td>
          <td>:</td>
          <td>
		  <input class="inputtxt" name="custrmk" id ="custrmkid" type="text" maxlength="80" style="width: 396px" value="<?php echo $custrmk; ?>"></td>
          <td></td>
           <td style="width: 167px">GST ID No</td>
          <td></td>
          <td>
                    <input class="inputtxt" name="gstno" id ="gstno" type="text" maxlength="80" style="width: 150px" value="<?php echo $gstno; ?>">
          </td>
         </tr>
        </table>
        <br>
        <fieldset name="Group1" class="style2" style="width: 1020px">
	     <legend class="style3"><strong>Contact Information</strong></legend>
	      <table>
	  	  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 1<span class="style4">*</span></td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="custadd1" id ="custadd1id" type="text" maxlength="100" style="width: 396px" value="<?php echo $custadd1; ?>">
			</td>
			<td></td>
			<td style="width: 131px"></td>
			<td></td>
            <td></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 2</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="custadd2" id ="custadd2id" type="text" maxlength="100" style="width: 396px" value="<?php echo $custadd2; ?>">
			</td>
			<td></td>
			<td>Mobile No</td>
			<td>:</td>
			<td>
			<input class="inputtxt" name="mobilen" id ="mobilen" type="text" value="<?php echo $custmob; ?>"style="width: 200px;" maxlength="50">
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">City<span class="style4">*</span></td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="custcity" id ="custcityid" type="text" maxlength="50" style="width: 282px" value="<?php echo $custcity; ?>">
			</td>
			<td></td>
			<td style="width: 131px">Postal Code<span class="style4">*</span></td>
			<td>:</td>
            <td>
			<input class="inputtxt" name="custpostcd" id ="custpostcdid" type="text" maxlength="50" style="width: 151px" value="<?php echo $custpostcd; ?>">
			</td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">State<span class="style4">*</span></td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
	  	    <input class="inputtxt" name="custstate" id ="custstateid" type="text" maxlength="50" style="width: 151px" value="<?php echo $custstate; ?>"></td>
			<td></td>
			<td class="tdlabel" style="width: 131px">Country<span class="style4">*</span></td>
            <td>:</td>
            <td>
	  	     <select name="agecountry" style="width: 279px">
			    <?php
                   $sql = "select country_code, country_desc from country_master ORDER BY country_desc ASC";
                   $sql_result = mysql_query($sql);
                   echo '<option size =60 value="'.$custcouncd.'">'.$custcountde.'</option>';
                    
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['country_code'].'">'.$row['country_desc'].'</option>';
				 	 } 
				   } 
	            ?>				   
			 </select>
		   </td>
		  </tr>
		  <tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Telephone<span class="style4">*</span></td>
           <td style="width: 8px">:</td>
           <td>
			<input class="inputtxt" name="custtel" id ="custtelid" type="text" maxlength="50" style="width: 200px;" value="<?php echo $custtel; ?>">
		   </td>
		   <td></td>
           <td style="width: 131px">Fax</td>
           <td>:</td>
           <td>
			<input name="custfax" id ="custfaxid" type="text" maxlength="50" style="width: 200px" value="<?php echo $custfax; ?>"></td>
		  </tr>
		  <tr>
	  	    <td style="height: 30px"></td>
	  	    <td class="tdlabel" style="width: 83px; height: 30px;">Contact<br>Person<span class="style4">*</span></td>
	  	    <td style="width: 8px; height: 30px;">:</td>
	  	    <td style="height: 30px">
			<input class="inputtxt" name="custconppl" id ="custconpplid" type="text" maxlength="50" style="width: 345px" value="<?php echo $custppl; ?>">
			</td>
			<td style="height: 30px"></td>
			<td style="width: 131px; height: 30px;">Email</td>
			<td style="height: 30px">:</td>
			<td style="height: 30px">
			<input class="inputtxt" name="custeml" id ="custemlid" type="text" maxlength="50" style="width: 345px" onBlur="AjaxFunction(this.value);" value="<?php echo $custeml; ?>"></td>
		  </tr>
		  <tr>    
	  	    <td></td><td></td><td></td><td></td><td></td><td style="width: 131px"></td><td></td><td><div id="msg"></div></td>
		  </tr>

		  </table>
		  </fieldset>
		  <br>
		  <fieldset name="Group1" class="style2" style="width: 1020px">
	     <legend class="style3"><strong>Agent Contact Information</strong></legend>
	      <table style="display:none">
	  	  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 1</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="ageadd1" id ="ageadd1" type="text" style="width: 396px" value="<?php echo $ageadd1; ?>" maxlength="100">
			</td>
			<td></td>
			<td style="width: 131px"></td>
			<td></td>
            <td></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 2</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="ageadd2" id ="ageadd2" type="text" style="width: 396px" value="<?php echo $ageadd2; ?>" maxlength="100">
			</td>
			<td></td>
			<td>Mobile No</td>
			<td>:</td>
			<td>
			<input class="inputtxt" name="agemob" id ="agemob" type="text" maxlength="50" value="<?php echo $agemob; ?>" style="width: 200px;">
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">City</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="agecity" id ="agecity" type="text" style="width: 282px" value="<?php echo $agecity; ?>" maxlength="50">
			</td>
			<td></td>
			<td style="width: 131px">Postal Code</td>
			<td>:</td>
            <td>
			<input class="inputtxt" name="agepost" id ="agepost" type="text" style="width: 151px" value="<?php echo $agepostcd; ?>" maxlength="50">
			</td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">State</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
	  	    <input class="inputtxt" name="agestate" id ="agestate" type="text" style="width: 151px" value="<?php echo $agestate; ?>" maxlength="50"></td>
			<td></td>
			<td class="tdlabel" style="width: 131px">Country</td>
            <td>:</td>
            <td>
	  	     <select name="agecount" style="width: 279px">
			    <?php
                   $sql = "select country_code, country_desc from country_master ORDER BY country_desc ASC";
                   $sql_result = mysql_query($sql);
                   echo '<option size =60 value="'.$agecouncd.'">'.$agecountde.'</option>';
                    
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['country_code'].'">'.$row['country_desc'].'</option>';
				 	 } 
				   } 
	            ?>				   
			 </select>
		   
	  	     </td>
		  </tr>
		  <tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Telephone</td>
           <td style="width: 8px">:</td>
           <td>
			<input class="inputtxt" name="agetel" id ="agetel" type="text" style="width: 200px;" value="<?php echo $agetel; ?>" maxlength="50">
		   </td>
		   <td></td>
           <td style="width: 131px">Fax</td>
           <td>:</td>
           <td>
			<input name="agefax" id ="agefax" type="text" style="width: 200px" value="<?php echo $agefax; ?>" maxlength="50">
			</td>
		  </tr>
		  <tr>
	  	    <td style="height: 30px"></td>
	  	    <td class="tdlabel" style="width: 83px; height: 30px;">Contact<br>Person</td>
	  	    <td style="width: 8px; height: 30px;">:</td>
	  	    <td style="height: 30px">
			<input class="inputtxt" name="ageconppl" id ="ageconppl" type="text" style="width: 345px" value="<?php echo $ageppl;?>" maxlength="50">
			</td>
			<td style="height: 30px"></td>
			<td style="width: 131px; height: 30px;">Email</td>
			<td style="height: 30px">:</td>
			<td style="height: 30px">
			<input class="inputtxt" name="ageeml" id ="ageeml" type="text"style="width: 345px" value="<?php echo  $ageeml;?>" maxlength="50">
			</td>
		  </tr>
		  <tr>    
	  	    <td></td><td></td><td></td><td></td><td></td><td style="width: 131px"></td><td></td><td><div id="msg"></div></td>
		  </tr>

		  </table>
		  </fieldset>


		  
		  <table>
	  	 	<tr>
	  	 		<td align="center" style="width: 1446px">
	  	 		  <?php
	  	 		  $locatr = "m_cust_mas.php?menucd=".$var_menucode;			
		 		  echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
	  	 		  include("../Setting/btnupdate.php");
	  	 		  ?>
	  	 		 </td>
	  	  	</tr>
		   <tr>
	  	     <td style="width: 1160px" align="left">
	  	     	<span style="color:#FF0000">Message :</span>Please Fill In Require Field (<span class="style5"><strong>*</strong></span>)
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
							    echo("<span>Fail! Duplicated Customer Code Found</span>");
  								break;
  							case 4:
							    echo("<span>Please Fill In The Data To Save</span>");
  								break;
							default:
  								echo "";
							}
			  			}	
					?>
           </td>
	  	  </tr>
	  	 </table>
	   </form>	
	   </fieldset>

	</div>
	 <div class="spacer"></div>
</body>

</html>
