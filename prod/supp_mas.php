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
     $suppcd   = $_POST['suppcd'];
     $suppstat = $_POST['selactive'];
     $suppname =  mysql_real_escape_string($_POST['suppname']);
     $suppcurr = $_POST['selcurr'];
     $supploovs = $_POST['selloovl'];
     $suppterm = $_POST['selterm'];
     $supppmode = $_POST['selpmode'];
     $suppweb = $_POST['suppweb'];
     $suppitms = $_POST['suppitms'];
     $supprmk = $_POST['supprmk'];
     
     $suppadd1_1 = $_POST['suppadd1'];
     $suppadd2_1 = $_POST['suppadd2'];
     $suppcity_1 = $_POST['suppcity'];
     $supppostcd_1 = $_POST['supppostcd'];
     $suppstate_1 = $_POST['suppstate'];
     $suppcount_1 = $_POST['selcount'];
     $supptel1  = $_POST['supptel1'];
     $suppmobotel1  = $_POST['suppmobile1'];
     $suppfax1  = $_POST['suppfax1'];
     $suppconppl1 = $_POST['suppconppl1'];
     $suppeml1    = $_POST['suppeml1'];
     
     $suppadd1_2 = $_POST['suppadd1_2'];
     $suppadd2_2 = $_POST['suppadd2_2'];
     $suppcity_2 = $_POST['suppcity_2'];
     $supppostcd_2 = $_POST['supppostcd_2'];
     $suppstate_2 = $_POST['suppstate_2'];
     $suppcount_2 = $_POST['selcount_2'];
     $supptel2  = $_POST['supptel2'];
     $suppmobotel2  = $_POST['suppmobile2'];
     $suppfax1  = $_POST['suppfax1'];

     $suppfax2  = $_POST['suppfax2'];
     $suppconppl2 = $_POST['suppconppl2'];
     $suppeml2    = $_POST['suppeml2'];
     
     //echo 'kkk-'.$suppmobotel1. "</br>";
     
     if ($suppcd <> "") {
 
      $var_sql = " SELECT count(*) as cnt from supplier_master ";
      $var_sql .= " WHERE supplier_code = '$suppcd'";

      $query_id = mysql_query($var_sql) or die ("Cant Check Supplier Code");
      $res_id = mysql_fetch_object($query_id);

      if ($res_id->cnt > 0 ) {
	     $backloc = "../main_mas/supp_mas.php?stat=3&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
      }else {
         $vartoday = date("Y-m-d H:i:s");
         $sql = "INSERT INTO supplier_master "; 
         $sql .= " (supplier_code, supplier_desc, active_flag, currency_code, address_1_1, address_2_1, ";
         $sql .= "  website, telephone_1, telephone_2, mobile_phone_1, mobile_phone_2, create_by, creation_time, modified_by, modified_on, ";
         $sql .= "  country_1, city_1, postal_code_1, state_1, conppl1, conppl2, email1, email2, ";
         $sql .= "  loovs, terms, paymode, itemsupp, remark, fax1, fax2, address_1_2, address_2_2, ";
         $sql .= "  city_2, postal_code_2, state_2, country_2) values ";
         $sql .= "  ('$suppcd', '$suppname', '$suppstat','$suppcurr', ";
         $sql .= "   '$suppadd1_1','$suppadd2_1','$suppweb','$supptel1','$supptel2', '$suppmobotel1', '$suppmobotel2', ";
         $sql .= "   '$var_loginid', '$vartoday','$var_loginid', '$vartoday', ";
         $sql .= "   '$suppcount_1','$suppcity_1','$supppostcd_1','$suppstate_1','$suppconppl1','$suppconppl2', ";
         $sql .= "   '$suppeml1','$suppeml2','$supploovs','$suppterm ','$supppmode','$suppitms', ";
         $sql .= "   '$supprmk', '$suppfax1', '$suppfax2','$suppadd1_2','$suppadd2_2','$suppcity_2','$supppostcd_2', ";
         $sql .= "   '$suppstate_2','$suppcount_2')";
  //       echo $sql;
//         break;
         mysql_query($sql); 
              
     	 $backloc = "../main_mas/m_supp_mas.php?stat=1&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";      
       } 
     }else{
       $backloc = "../main_mas/supp_mas.php?stat=4&menucd=".$var_menucode;
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

.style2 {
	margin-right: 0px;
}
.style3 {
	font-size: x-small;
}
.style4 {
	color: #FF0000;
	font-weight:bold;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>


<script type="text/javascript" charset="utf-8"> 
function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
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
	
	var url="aja_chk_supp.php";
	
	url=url+"?suppcdg="+suppcd;
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
    var x=document.forms["InpSuppMas"]["suppcdid"].value;
	if (x==null || x=="")
	{
	alert("Supplier Code Cannot Be Blank");
	document.InpSuppMas.suppcdid.focus();
	return false;
	}
	
	var x=document.forms["InpSuppMas"]["suppnmid"].value;
	if (x==null || x=="")
	{
	alert("Supplier Name Cannot Be Blank");
	document.InpSuppMas.suppnmid.focus();
	return false;
	}
	
	var x=document.forms["InpSuppMas"]["suppadd1id"].value;
	if (x==null || x=="")
	{
	alert("Supplier Address1 Cannot Be Blank");
	document.InpSuppMas.suppadd1id.focus();
	return false;
	}
	
	var x=document.forms["InpSuppMas"]["suppcityid"].value;
	if (x==null || x=="")
	{
	alert("Supplier Address City Cannot Be Blank");
	document.InpSuppMas.suppcityid.focus();
	return false;
	}
	
	var x=document.forms["InpSuppMas"]["supppostcdid"].value;
	if (x==null || x=="")
	{
	alert("Supplier Address Postal Code Cannot Be Blank");
	document.InpSuppMas.supppostcdid.focus();
	return false;
	}
	
	var x=document.forms["InpSuppMas"]["suppstateid"].value;
	if (x==null || x=="")
	{
	alert("Supplier Address State Cannot Be Blank");
	document.InpSuppMas.suppstateid.focus();
	return false;
	}

	var x=document.forms["InpSuppMas"]["selcount"].value;
	if (x==null || x=="")
	{
	alert("Supplier Address Country Cannot Be Blank");
	document.InpSuppMas.selcount.focus();
	return false;
	}
	
	var x=document.forms["InpSuppMas"]["supptel1id"].value;
	if (x==null || x=="")
	{
	alert("Supplier Telephone Cannot Be Blank");
	document.InpSuppMas.supptel1id.focus();
	return false;
	}
	
	var x=document.forms["InpSuppMas"]["suppconppl1id"].value;
	if (x==null || x=="")
	{
	alert("Supplier Contact Person 1 Cannot Be Blank");
	document.InpSuppMas.suppconppl1id.focus();
	return false;
	}	
}	
</script>
</head>

<body onload="document.InpSuppMas.suppcdid.focus();">
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>-->
  <div class="contentc">

	<fieldset name="Group1" style=" width: 1065px;" class="style2">
	 <legend class="title">SUPPLIER MASTER</legend>
	  <br>
	  <fieldset name="Group1" style="width: 1043px; height: 750px">
	  <form name="InpSuppMas" method="POST" onsubmit="return validateForm()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 970px;">
	   <table>
	    <tr>
	      <td></td>
	      <td class="tdlabel" style="width: 200px">Supplier Code<span class="style4">*</span></td>
	      <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="suppcd" id ="suppcdid" type="text" maxlength="10" onchange ="upperCase(this.id)" style="width: 94px" onBlur="AjaxFunctioncd(this.value);">
		  </td>
		  <td></td>
		  <td class="tdlabel">Status</td>
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
	  	  <td></td>
	  	  <td></td> 
          <td><div id="msgcd"></div></td>
	   	 </tr> 
	   	 <tr>
	   	  <td></td>
	  	  <td class="tdlabel">Supplier Name<span class="style4">*</span></td>
	  	  <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="suppname" id ="suppnmid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 396px">
		  </td>
		  <td></td>
		  <td class="tdlabel">Currency Code</td>
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
          <td>Local/Oversea</td>
          <td>:</td>
          <td>
			<select name="selloovl" style="width: 125px">
			    <option>LOCAL</option>
			    <option>OVERSEA</option>
			</select></td>
		  <td></td>
		  <td>Terms</td>
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
          <td>Pay Mode</td>
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
          <td>Website</td>
          <td>:</td>
          <td>
			<input class="inputtxt" name="suppweb" id ="suppwebid" type="text" maxlength="50" style="width: 345px"></td>
         </tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td>Item Supplied</td>
          <td>:</td>
          <td>
		    <input class="inputtxt" name="suppitms" id ="suppitmsid" type="text" maxlength="80" style="width: 396px"></td>
          <td></td>
          <td></td>
          <td></td>
         </tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td>Remark</td>
          <td>:</td>
          <td>
		  <input class="inputtxt" name="supprmk" id ="supprmkid" type="text" maxlength="80" style="width: 396px"></td>
          <td></td>
          <td></td>
          <td></td>
         </tr>
        </table>
        <br>
        <fieldset name="Group1" class="style2" style="width: 975px">
	     <legend class="style3"><strong>Contact Information 1</strong></legend>
	      <table>
	  	  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 1<span class="style4">*</span></td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="suppadd1" id ="suppadd1id" type="text" maxlength="100" style="width: 396px">
			</td>
			<td></td>
			<td style="width: 81px"></td>
			<td></td>
            <td></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 2</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="suppadd2" id ="suppadd2id" type="text" maxlength="50" style="width: 396px">
			</td>
			<td></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">City<span class="style4">*</span></td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="suppcity" id ="suppcityid" type="text" maxlength="50" style="width: 282px">
			</td>
			<td></td>
			<td style="width: 81px">Postal Code<span class="style4">*</span></td>
			<td>:</td>
            <td>
			<input class="inputtxt" name="supppostcd" id ="supppostcdid" type="text" maxlength="50" style="width: 151px">
			</td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">State<span class="style4">*</span></td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
	  	    <input class="inputtxt" name="suppstate" id ="suppstateid" type="text" maxlength="50" style="width: 151px"></td>
			<td></td>
			<td class="tdlabel" style="width: 81px">Country<span class="style4">*</span></td>
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
			<input class="inputtxt" name="supptel1" id ="supptel1id" type="text" maxlength="50" style="width: 161px">
		   </td>
		   <td></td>
           <td style="width: 81px">Fax</td>
           <td>:</td>
           <td>
			<input class="inputtxt" name="suppfax1" id ="suppfax1id" type="text" maxlength="50" style="width: 294px"></td>
		  </tr>
		  	<tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Mobile Phone</td>
           <td style="width: 8px">:</td>
           <td>
			&nbsp;<input class="inputtxt" name="suppmobile1" id ="suppmobile1id" type="text" maxlength="50" style="width: 294px"></td>
		   <td></td>
           <td style="width: 81px">&nbsp;</td>
           <td>&nbsp;</td>
           <td>
			&nbsp;</td>
		  	</tr>
		  <tr>
	  	    <td style="height: 30px"></td>
	  	    <td class="tdlabel" style="width: 83px; height: 30px;">Contact<br>Person<span class="style4">*</span></td>
	  	    <td style="width: 8px; height: 30px;">:</td>
	  	    <td style="height: 30px">
			<input class="inputtxt" name="suppconppl1" id ="suppconppl1id" type="text" maxlength="50" style="width: 345px">
			</td>
			<td style="height: 30px"></td>
			<td style="width: 81px; height: 30px;">Email</td>
			<td style="height: 30px">:</td>
			<td style="height: 30px">
			<input class="inputtxt" name="suppeml1" id ="suppeml1id" type="text" maxlength="50" style="width: 345px" onBlur="AjaxFunction(this.value);"></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td></td><td></td><td></td><td></td><td></td><td></td><td><div id="msg"></div></td>
		  </tr>

		  </table>
		  </fieldset>
		  <br><br>
		  <fieldset name="Group1" style="width: 975px">
	      <legend class="style3"><strong>Contact Information 2</strong></legend>
          <table>
		  <tr>
		   <td style="width: 2px"></td>
		   <td class="tdlabel" style="width: 525px">Address 1</td>
		   <td>:</td>
		   <td>
			<input class="inputtxt" name="suppadd1_2" id ="suppadd1_2id" type="text" maxlength="100" style="width: 396px">
		   </td>
		   <td></td>
		   <td style="width: 290px"></td>
		   <td style="width: 109px"></td>
		  </tr>
		   <tr>
	  	    <td style="width: 2px"></td>
	  	    <td class="tdlabel" style="width: 525px">Address 2</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="suppadd2_2" id ="suppadd2_2id" type="text" maxlength="100" style="width: 396px"></td>
			<td></td>
		    <td class="tdlabel" style="width: 290px">&nbsp;</td>
	  	    <td style="width: 109px">&nbsp;</td>
	  	    <td style="width: 468px">
			&nbsp;</td>
		   </tr>
		  <tr>
		   <td style="width: 2px"></td>
		   <td style="width: 525px">City</td>
           <td>:</td>
           <td>
			<input class="inputtxt" name="suppcity_2" id ="suppcity_2id" type="text" maxlength="50" style="width: 282px"></td>
           <td></td>
           <td style="width: 290px">Postal Code</td>
           <td style="width: 109px">:</td>
           <td style="width: 468px">
		   <input class="inputtxt" name="supppostcd_2" id ="supppostcd_2id" type="text" maxlength="50" style="width: 151px">
		   </td>
		  </tr>
		  <tr>
	  	    <td style="width: 2px"></td>
	  	    <td class="tdlabel" style="width: 525px">State&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	  	    <td>:</td>
	  	    <td>
	  	    <input class="inputtxt" name="suppstate_2" id ="suppstate_2id" type="text" maxlength="50" style="width: 151px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  	    </td>
			<td></td>
			<td class="tdlabel" style="width: 290px">Country&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
			<td style="width: 109px">:</td>
            <td style="width: 468px">
	  	     <select name="selcount_2" style="width: 279px">
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
			 </select></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 525px">Telephone </td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="supptel2" id ="supptel2id" type="text" maxlength="50" style="width: 240px"></td>
			<td></td>
			<td style="width: 290px">Fax</td>
			<td style="width: 109px">:</td>
			<td style="width: 468px">
			<input class="inputtxt" name="suppfax2" id ="suppfax2id" type="text" maxlength="50" style="width: 294px"></td>
		  </tr>
		  	<tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 525px">Mobile Phone </td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="suppmobile2" id ="suppmobile2id" type="text" maxlength="50" style="width: 294px"></td>
			<td></td>
			<td style="width: 290px">Fax</td>
			<td style="width: 109px">:</td>
			<td style="width: 468px">
			<input class="inputtxt" name="suppfax3" id ="suppfax2id0" type="text" maxlength="50" style="width: 294px"></td>
		  	</tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 525px">Contact Person </td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="suppconppl2" id ="suppconppl2id" type="text" maxlength="50" style="width: 359px"></td>
			<td></td>
			<td style="width: 290px">Email </td>
			<td style="width: 109px">:</td>
			<td style="width: 468px">
			<input class="inputtxt" name="suppeml2" id ="suppeml2id" type="text" maxlength="50" style="width: 359px" onBlur="AjaxFunction(this.value);">
			</td>
		  </tr>
	  	 </table>
	  	 </fieldset>
	  	 <table>
	  	 <tr><td></td></tr>
	  	 <tr><td colspan="8" align="center">
	  	   <?php
	  	   $locatr = "m_supp_mas.php?menucd=".$var_menucode;			
		   echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
	  	   include("../Setting/btnsave.php");
	  	   ?>
	  	   </td>
	  	  </tr>
		   <tr>
	  	  <td></td>
	  	              <td style="width: 1160px" colspan="7"><span style="color:#FF0000">Message :</span>
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
				    echo("<span>Fail! Duplicated Supplier Code Found</span>");
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
	</fieldset>
	</div>
	 <div class="spacer"></div>
</body>

</html>
