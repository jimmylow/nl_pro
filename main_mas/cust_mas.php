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
     $agecount = $_POST['agecountry'];
     $agetel  = $_POST['agetel'];
     $agemob  = $_POST['agemob'];
     $agefax  = $_POST['agefax'];
     $ageconppl = $_POST['ageconppl'];
     $ageeml    = $_POST['ageeml'];
     
     $gstno = $_POST['gstno'];
     
     if ($custcd <> "") {
 
      $var_sql = " SELECT count(*) as cnt from customer_master ";
      $var_sql .= " WHERE customer_code = '$suppcd'";

      $query_id = mysql_query($var_sql) or die ("Cant Check Customer Code");
      $res_id = mysql_fetch_object($query_id);

      if ($res_id->cnt > 0 ) {
	     $backloc = "../main_mas/cust_mas.php?stat=3&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
      }else {
         $vartoday = date("Y-m-d H:i:s");
         $sql = "INSERT INTO customer_master "; 
         $sql .= " (customer_code, customer_desc, active_flag, currency_code, address, address_2, ";
         $sql .= "  website, telephone, mobileno, fax, contact_person, email, create_by, creation_time, ";
         $sql .= "  modified_by, modified_on, loovl, termcd, paymode, remark, custcity, custpostcd, custstate, custcountry,";
         $sql .= "  ageadd1, ageadd2, agecity, agepost, agestate, agecountry, agetel, agemobile, agefax, ageconppl, ageemail, gstno) values ";
         $sql .= "  ('$custcd', '$custname', '$custstat','$custcurr', ";
         $sql .= "   '$custadd1','$custadd2','$custweb','$custtel', '$custmob', '$custfax', ";
         $sql .= "   '$custconppl', '$custeml', '$var_loginid', '$vartoday', ";
         $sql .= "   '$var_loginid','$vartoday','$custloovs','$custterm','$custpmode','$custrmk', ";
         $sql .= "   '$custcity','$custpostcd','$custstate','$custcount', '$ageadd1', '$ageadd2', ";
         $sql .= "   '$agecity', '$agepostcd','$agestate', '$agecount', '$agetel', '$agemob', '$agefax', '$ageconppl', ";
         $sql .= "   '$ageeml', '$gstno')"; 
         mysql_query($sql) or die("error :".mysql_error()); 
              
     	 $backloc = "../main_mas/m_cust_mas.php?stat=1&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";      
       } 
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
@import "../css/demo_table.css";
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
	
	var url="aja_chk_cust.php";
	
	url=url+"?custcdg="+suppcd;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",url,true);
	httpxml.send(null);
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
    var x=document.forms["InpCustMas"]["custcdid"].value;
	if (x==null || x=="")
	{
	alert("Customer Code Cannot Be Blank");
	document.InpCustMas.custcdid.focus();
	return false;
	}
	
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

	flgchk = 1;
	var x=document.forms["InpCustMas"]["custcdid"].value;
	var strURL="aja_chk_dupcustcd.php?custcode="+x;
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
					  alert ('This Customer Code Is Assign To The Customer');
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
}

	
</script>
</head>
 
  <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="document.InpCustMas.custcdid.focus();">
  <?php include("../topbarm.php"); ?> 
  <div class="contentc">

	<fieldset name="Group1" style=" width: 1045px; height: 830px;" class="style2">
	 <legend class="title">CUSTOMER MASTER</legend>
	  <br>
	
	  <form name="InpCustMas" method="POST" onsubmit="return validateForm()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 970px;">
	   <table style="width: 1027px">
	    <tr>
	      <td></td>
	      <td class="tdlabel" style="width: 218px">Customer Code<span class="style4">*</span></td>
	      <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="custcd" id ="custcdid" type="text" maxlength="10" onchange ="upperCase(this.id)" style="width: 94px" onBlur="AjaxFunctioncd(this.value);">
		  </td>
		  <td></td>
		  <td class="tdlabel" style="width: 141px">Status</td>
	  	  <td>:</td>
	  	  <td>
		   <select name="selactive" style="width: 125px">
		    <option>ACTIVE</option>
		    <option>DEACTIVATE</option>
		   </select>
		  </td>
	  	 </tr>
	  	 <tr>
	  	  <td></td> 
	  	  <td style="width: 218px"></td>
	  	  <td></td> 
          <td><div id="msgcd"></div></td>
	   	 </tr> 
	   	 <tr>
	   	  <td></td>
	  	  <td class="tdlabel" style="width: 218px">Customer Name<span class="style4">*</span></td>
	  	  <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="custname" id ="custnmid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 396px">
		  </td>
		  <td></td>
		  <td class="tdlabel" style="width: 141px">Currency Code</td>
	  	  <td>:</td>
	  	  <td>
		   <select name="selcurr" style="width: 68px">
			 <?php
                   $sql = "select currency_code, currency_desc from currency_master ORDER BY currency_code ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
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
			    <option>LOCAL</option>
			    <option>OVERSEA</option>
			</select></td>
		  <td></td>
		  <td style="width: 141px">Terms</td>
		  <td>:</td>
		  <td>
			<select name="selterm" style="width: 165px">
			    <?php
                   $sql = "select term_code, term_desc from term_master ORDER BY term_desc ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =40 selected></option>";
                       
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
                   echo "<option size =40 selected></option>";
                       
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
          <td style="width: 141px">Website</td>
          <td>:</td>
          <td>
			<input class="inputtxt" name="custweb" id ="custwebid" type="text" maxlength="50" style="width: 345px"></td>
         </tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td style="width: 218px">Remark</td>
          <td>:</td>
          <td>
		  <input class="inputtxt" name="custrmk" id ="custrmkid" type="text" maxlength="80" style="width: 396px"></td>
          <td></td>
          <td style="width: 141px">GST ID No</td>
          <td></td>
          <td>
                    <input class="inputtxt" name="gstno" id ="gstno" type="text" maxlength="80" style="width: 150px">
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
			<input class="inputtxt" name="custadd1" id ="custadd1id" type="text" maxlength="100" style="width: 396px">
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
			<input class="inputtxt" name="custadd2" id ="custadd2id" type="text" maxlength="100" style="width: 396px">
			</td>
			<td></td>
			<td>Mobile No</td>
			<td>:</td>
			<td>
			<input class="inputtxt" name="mobilen" id ="mobilen" type="text" maxlength="50" style="width: 200px;"></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">City<span class="style4">*</span></td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="custcity" id ="custcityid" type="text" maxlength="50" style="width: 282px">
			</td>
			<td></td>
			<td style="width: 131px">Postal Code<span class="style4">*</span></td>
			<td>:</td>
            <td>
			<input class="inputtxt" name="custpostcd" id ="custpostcdid" type="text" maxlength="50" style="width: 151px">
			</td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">State<span class="style4">*</span></td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
	  	    <input class="inputtxt" name="custstate" id ="custstateid" type="text" maxlength="50" style="width: 151px"></td>
			<td></td>
			<td class="tdlabel" style="width: 131px">Country<span class="style4">*</span></td>
            <td>:</td>
            <td>
	  	     <select name="selcount" style="width: 279px">
			    <?php
                   $sql = "select country_code, country_desc from country_master ORDER BY country_desc ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =60 selected></option>";
                       
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
			<input class="inputtxt" name="custtel" id ="custtelid" type="text" maxlength="50" style="width: 200px;">
		   </td>
		   <td></td>
           <td style="width: 131px">Fax</td>
           <td>:</td>
           <td>
			<input name="custfax" id ="custfaxid" type="text" maxlength="50" style="width: 200px"></td>
		  </tr>
		  <tr>
	  	    <td style="height: 30px"></td>
	  	    <td class="tdlabel" style="width: 83px; height: 30px;">Contact<br>Person<span class="style4">*</span></td>
	  	    <td style="width: 8px; height: 30px;">:</td>
	  	    <td style="height: 30px">
			<input class="inputtxt" name="custconppl" id ="custconpplid" type="text" maxlength="50" style="width: 345px">
			</td>
			<td style="height: 30px"></td>
			<td style="width: 131px; height: 30px;">Email</td>
			<td style="height: 30px">:</td>
			<td style="height: 30px">
			<input class="inputtxt" name="custeml" id ="custemlid" type="text" maxlength="50" style="width: 345px" onBlur="AjaxFunction(this.value);"></td>
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
			<input class="inputtxt" name="ageadd1" id ="ageadd1" type="text" maxlength="100" style="width: 396px">
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
			<input class="inputtxt" name="ageadd2" id ="ageadd2" type="text" maxlength="100" style="width: 396px">
			</td>
			<td></td>
			<td>Mobile No</td>
			<td>:</td>
			<td>
			<input class="inputtxt" name="agemob" id ="agemob" type="text" maxlength="50" style="width: 200px;"></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">City</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="agecity" id ="agecity" type="text" maxlength="50" style="width: 282px">
			</td>
			<td></td>
			<td style="width: 131px">Postal Code</td>
			<td>:</td>
            <td>
			<input class="inputtxt" name="agepost" id ="agepost" type="text" maxlength="50" style="width: 151px">
			</td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">State</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
	  	    <input class="inputtxt" name="agestate" id ="agestate" type="text" maxlength="50" style="width: 151px"></td>
			<td></td>
			<td class="tdlabel" style="width: 131px">Country</td>
            <td>:</td>
            <td>
	  	     <select name="agecountry" style="width: 279px">
			    <?php
                   $sql = "select country_code, country_desc from country_master ORDER BY country_desc ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =60 selected></option>";
                       
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
			<input class="inputtxt" name="agetel" id ="agetel" type="text" maxlength="50" style="width: 200px;">
		   </td>
		   <td></td>
           <td style="width: 131px">Fax</td>
           <td>:</td>
           <td>
			<input name="agefax" id ="agefax" type="text" maxlength="50" style="width: 200px"></td>
		  </tr>
		  <tr>
	  	    <td style="height: 30px"></td>
	  	    <td class="tdlabel" style="width: 83px; height: 30px;">Contact<br>Person</td>
	  	    <td style="width: 8px; height: 30px;">:</td>
	  	    <td style="height: 30px">
			<input class="inputtxt" name="ageconppl" id ="ageconppl" type="text" maxlength="50" style="width: 345px">
			</td>
			<td style="height: 30px"></td>
			<td style="width: 131px; height: 30px;">Email</td>
			<td style="height: 30px">:</td>
			<td style="height: 30px">
			<input class="inputtxt" name="ageeml" id ="ageeml" type="text" maxlength="50" style="width: 345px" onBlur="AjaxFunction(this.value);"></td>
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
	  	 		  include("../Setting/btnsave.php");
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
