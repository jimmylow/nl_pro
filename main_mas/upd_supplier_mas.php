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
      $var_supp_cd = $_GET['suppcd'];
	  $var_menucode = $_GET['menucd'];
    }
    
    if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menucd'];
         $backloc = "../main_mas/m_supp_mas.php?menucd=".$var_menucode;
	
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }
    
     if ($_POST['Submit'] == "Update") {
       $var_supp_cd = $_POST['suppcd'];
       if ($var_supp_cd <> "") {

         $suppname1 = $_POST['suppname'];
         $suppstat = $_POST['selactive'];
         $suppcurr = $_POST['selcurr'];
         $suppadd1 = $_POST['suppadd1'];
         $suppadd2 = $_POST['suppadd2'];
         $suppcity = $_POST['suppcity'];
         $supppostcd = $_POST['supppostcd'];
         $suppstate = $_POST['suppstate'];
         $suppcount = $_POST['selcount'];
         $supptel1  = $_POST['supptel1'];
         $supptel2  = $_POST['supptel2'];
         $suppfax1  = $_POST['suppfax1'];
         $suppfax2  = $_POST['suppfax2'];
         $suppweb  = $_POST['suppweb'];
         $suppconppl1 = $_POST['suppconppl1'];
         $suppconppl2 = $_POST['suppconppl2'];
         $suppeml1    = $_POST['suppeml1'];
         $suppeml2    = $_POST['suppeml2'];
         $supploovs   = $_POST['selloovl'];
         $suppterm   = $_POST['selterm'];
         $supppmode   = $_POST['selpmode']; 
         $suppitms  = $_POST['suppitms'];
         $supprmk  = $_POST['supprmk'];
         $suppmoby= $var_loginid;
         $suppmoon= date("Y-m-d H:i:s");
         $suppadd1_2 = $_POST['suppadd1_2'];
         $suppadd2_2 = $_POST['suppadd2_2'];
         $suppcity_2 = $_POST['suppcity_2'];
         $supppostcd_2 = $_POST['supppostcd_2'];
         $suppstate_2 = $_POST['suppstate_2'];
         $suppcount_2 = $_POST['selcount_2'];
		 $var_menucode  = $_POST['menucd'];
		 $suppmobile1  = $_POST['suppmobile1'];
		 $suppmobile2  = $_POST['suppmobile2'];
		 $gstno = $_POST['gstno'];
               
         $sql = "Update supplier_master set supplier_desc ='$suppname1', ";
         $sql .= " active_flag = '$suppstat', currency_code = '$suppcurr', address_1_1 = '$suppadd1', ";
         $sql .= " address_2_1 = '$suppadd2', city_1= '$suppcity', postal_code_1='$supppostcd', country_1='$suppcount',";
         $sql .= " state_1 = '$suppstate', telephone_1 = '$supptel1', telephone_2 ='$supptel2', fax1 = '$suppfax1', ";
         $sql .= " fax2 = '$suppfax2', website = '$suppweb', conppl1 = '$suppconppl1', conppl2 = '$suppconppl2',";
         $sql .= " email1 = '$suppeml1', email2 = '$suppeml2', loovs = '$supploovs', terms = '$suppterm',";
         $sql .= " paymode = '$supppmode', itemsupp = '$suppitms', remark = '$supprmk', ";
         $sql .= " address_1_2 = '$suppadd1_2', address_2_2 = '$suppadd2_2', city_2= '$suppcity_2',";
         $sql .= " state_2 ='$suppstate_2', postal_code_2='$supppostcd_2', country_2='$suppcount_2',";
         $sql .= " mobile_phone_1 ='$suppmobile1', mobile_phone_2 ='$suppmobile2',";
         $sql .= " modified_by='$suppmoby',";
         $sql .= " modified_on='$suppmoon', gstno='$gstno' WHERE supplier_code = '$var_supp_cd'";
         
         mysql_query($sql);
         $backloc = "../main_mas/m_supp_mas.php?menucd=".$var_menucode;
	
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

<style media="all" type="text/css">@import "../css/styles.css";
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>

<script type="text/javascript" charset="utf-8"> 
function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
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

$(function(){
  $('legend').click(function(){
   $(this).siblings().slideToggle("slow");
  });
});

</script>
</head>
 
<body OnLoad="document.UpdSuppMas.selactive.focus();">
<?php include("../topbarm.php"); ?> 
    <!--<?php include("../sidebarm.php"); ?>--> 
 <?php
        $sql = "select supplier_desc, active_flag, currency_code, address_1_1, ";
        $sql .= " address_2_1, website, telephone_1, telephone_2, country_1, city_1, ";
        $sql .= " postal_code_1, state_1, conppl1, conppl2, email1, email2, loovs, ";
        $sql .= " terms, paymode, itemsupp, remark, fax1, fax2, ";
        $sql .= " modified_by, modified_on, address_1_2, address_2_2,city_2,postal_code_2, state_2,country_2, mobile_phone_1, mobile_phone_2,";
        $sql .= " gstno";
        $sql .= " from supplier_master";
        $sql .= " where supplier_code ='".$var_supp_cd."'";
        
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);

        $suppde = $row[0];
        $suppstat = $row[1];
        $suppcurr = $row[2];
        $suppadd1 = $row[3];
        $suppadd2 = $row[4];
        $suppweb  = $row[5];
        $supptel1  = $row[6];
        $supptel2  = $row[7];
        $suppcount = $row[8];
        $suppcity = $row[9];
        $supppostcd = $row[10];
        $suppstate = $row[11];
        $suppconppl1 = $row[12];
        $suppconppl2 = $row[13];
        $suppeml1 = $row[14];
        $suppeml2 = $row[15];
        $supploovs = $row[16];
        $suppterm = $row[17];
        $supppmode = $row[18]; 
        $suppitms = $row[19];
        $supprmk = $row[20];
        $suppfax1 = $row[21];
        $suppfax2 = $row[22];
        $suppadd1_2 = $row[25];
        $suppadd2_2 = $row[26];
        $suppcity_2 = $row[27];
        $supppostcd_2 = $row[28];
        $suppstate_2 = $row[29];
        $suppcount_2 = $row[30];
        $suppmobile1 = $row[31];
        $suppmobile2 = $row[32];
		$gstno = $row[33];
        
        $sql = "select country_desc from country_master  ";
        $sql .= " where country_code ='".$suppcount."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $suppcountde = $row[0];
        
        $sql = "select country_desc from country_master  ";
        $sql .= " where country_code ='".$suppcount_2."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $suppcountde_2 = $row[0];
        
        $sql = "select term_desc from term_master  ";
        $sql .= " where term_code ='".$suppterm."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $supptermde = $row[0];
        
        $sql = "select pmdesc from pay_m_master  ";
        $sql .= " where pmcode ='".$supppmode."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $supppmde = $row[0];

    ?>		
   
    <div class="contentc">

	  <fieldset name="Group1" style="width: 993px; height: 850px">
	  <legend class="title">EDIT SUPPLIER MASTER - <?php echo $var_supp_cd; php?></legend>

	  <form name="InpSuppMas" onsubmit="return validateForm()" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px; width: 970px;">
	    <input name="menucd" type="hidden" value="<?php echo $var_menucode;?>">
	   <table>
	    <tr>
	      <td></td>
	      <td class="tdlabel">Supplier Code</td>
	      <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="suppcd" id ="suppcdid" readonly="readonly" type="text" style="width: 161px" value="<?php echo $var_supp_cd; ?>">
		  </td>
		  <td></td>
		  <td class="tdlabel">Status</td>
	  	  <td>:</td>
	  	  <td>
		   <select name="selactive" style="width: 125px">
		    <option><?php echo $suppstat;?></option>
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
	  	  <td class="tdlabel">Supplier Name</td>
	  	  <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="suppname" id ="suppnmid" type="text" maxlength="50" style="width: 396px" value="<?php echo $suppde; ?>">
		  </td>
		  <td></td>
		  <td class="tdlabel">Currency Code</td>
	  	  <td>:</td>
	  	  <td>
		   <select name="selcurr" style="width: 68px">
		   <option><?php echo $suppcurr;?></option>   
			 <?php
                   $sql = "select currency_code, currency_desc from currency_master ORDER BY currency_code ASC";
                   $sql_result = mysql_query($sql);
                                       
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
			    <option><?php echo $supploovs; ?></option>
			    <option>LOCAL</option>
			    <option>OVERSEA</option>
			</select></td>
		  <td></td>
		  <td>Terms</td>
		  <td>:</td>
		  <td>
			<select name="selterm" style="width: 165px">
			<option value="<?php echo $suppterm; ?>"><?php echo $supptermde; ?></option>
			    <?php
                   $sql = "select term_code, term_desc from term_master ORDER BY term_desc ASC";
                   $sql_result = mysql_query($sql);
                                         
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
			    <option  value="<?php echo $supppmode; ?>"><?php echo $supppmde; ?></option>
			    <?php
                   $sql = "select pmcode, pmdesc from pay_m_master ORDER BY pmdesc ASC";
                   $sql_result = mysql_query($sql);
                  
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
			<input class="inputtxt" name="suppweb" id ="suppwebid" type="text" maxlength="50" style="width: 345px" value="<?php echo $suppweb; ?>"></td>
         </tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td>Item Supplied</td>
          <td>:</td>
          <td>
		    <input class="inputtxt" name="suppitms" id ="suppitmsid" type="text" maxlength="80" style="width: 396px" value="<?php echo $suppitms; ?>">
		  </td>
          <td></td>
          <td>GST ID No</td>
          <td></td>
          <td>
          <input class="inputtxt" name="gstno" id ="gstno" type="text" value="<?php echo $gstno; ?>" maxlength="80" style="width: 100px"></td>
          </td>
         </tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td>Remark</td>
          <td>:</td>
          <td>
		   <input class="inputtxt" name="supprmk" id ="supprmkid" type="text" maxlength="80" style="width: 396px" value="<?php echo $supprmk; ?>"></td>
          <td></td>
          <td></td>
          <td></td>
         </tr>
        </table>
        <br>
        <fieldset name="Group1" class="style2" style="width: 971px">
	     <legend class="style3"><strong>Contact Information 1</strong></legend>
	      <table>
	  	  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 1</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="suppadd1" id ="suppadd1id" type="text" maxlength="100" style="width: 396px" value="<?php echo $suppadd1; ?>">
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
			<input class="inputtxt" name="suppadd2" id ="suppadd2id" type="text" maxlength="50" style="width: 396px" value="<?php echo $suppadd2; ?>">
			</td>
			<td></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">City</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="suppcity" id ="suppcityid" type="text" maxlength="50" style="width: 282px" value="<?php echo $suppcity; ?>">
			</td>
			<td></td>
			<td style="width: 81px">Postal Code</td>
			<td>:</td>
            <td>
			<input class="inputtxt" name="supppostcd" id ="supppostcdid" type="text" maxlength="50" style="width: 151px" value="<?php echo $supppostcd; ?>">
			</td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">State</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
	  	    <input class="inputtxt" name="suppstate" id ="suppstateid" type="text" maxlength="50" style="width: 151px" value="<?php echo $suppstate; ?>"></td>
			<td></td>
			<td class="tdlabel" style="width: 81px">Country</td>
            <td>:</td>
            <td>
	  	     <select name="selcount" style="width: 279px">
	  	        <option value="<?php echo $suppcount; ?>"><?php echo $suppcountde; ?></option>
			    <?php
                   $sql = "select country_code, country_desc from country_master ORDER BY country_desc ASC";
                   $sql_result = mysql_query($sql);
                   
                       
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
			<input class="inputtxt" name="supptel1" id ="supptel1id" type="text" maxlength="50" style="width: 161px" value="<?php echo $supptel1;?>">
		   </td>
		   <td></td>
           <td style="width: 81px">Fax </td>
           <td>:</td>
           <td>
			<input class="inputtxt" name="suppfax1" id ="suppfax1id" type="text" maxlength="50" style="width: 294px" value="<?php echo $suppfax1;?>"></td>
		  </tr>
		  	<tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Mobile Phone</td>
           <td style="width: 8px">:</td>
           <td>
			<input class="inputtxt" name="suppmobile1" id ="suppmobile1id" type="text" maxlength="50" style="width: 161px" value="<?php echo $suppmobile1;?>">
		   </td>
		   <td></td>
           <td style="width: 81px">Fax </td>
           <td>:</td>
           <td>
			<input class="inputtxt" name="suppfax3" id ="suppfax1id0" type="text" maxlength="50" style="width: 294px" value="<?php echo $suppfax1;?>"></td>
		  	</tr>
		  <tr>
	  	    <td style="height: 30px"></td>
	  	    <td class="tdlabel" style="width: 83px; height: 30px;">Contact<br>Person</td>
	  	    <td style="width: 8px; height: 30px;">:</td>
	  	    <td style="height: 30px">
			<input class="inputtxt" name="suppconppl1" id ="suppconppl1id" type="text" maxlength="50" style="width: 345px" value="<?php echo $suppconppl1;?>">
			</td>
			<td style="height: 30px"></td>
			<td style="width: 81px; height: 30px;">Email</td>
			<td style="height: 30px">:</td>
			<td style="height: 30px">
			<input class="inputtxt" name="suppeml1" id ="suppeml1id" type="text" maxlength="50" style="width: 345px" onBlur="AjaxFunction(this.value);" value="<?php echo $suppeml1;?>"></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td></td><td></td><td></td><td></td><td></td><td></td><td><div id="msg"></div></td>
		  </tr>

		  </table>
		  </fieldset>
		  <br>
		  <fieldset name="Group1" style="width: 968px">
	      <legend class="style3"><strong>Contact Information 2</strong></legend>
          <table style="display:none">
		  <tr>
		   <td style="width: 2px"></td>
		   <td class="tdlabel" style="width: 525px">Address 1</td>
		   <td>:</td>
		   <td>
			<input class="inputtxt" name="suppadd1_2" id ="suppadd1_2id" type="text" maxlength="100" style="width: 396px" value="<?php echo $suppadd1_2;?>">
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
			<input class="inputtxt" name="suppadd2_2" id ="suppadd2_2id" type="text" maxlength="100" style="width: 396px" value="<?php echo $suppadd2_2;?>"></td>
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
			<input class="inputtxt" name="suppcity_2" id ="suppcity_2id" type="text" maxlength="50" style="width: 282px" value="<?php echo $suppcity_2;?>"></td>
           <td></td>
           <td style="width: 290px">Postal Code</td>
           <td style="width: 109px">:</td>
           <td style="width: 468px">
		   <input class="inputtxt" name="supppostcd_2" id ="supppostcd_2id" type="text" maxlength="50" style="width: 151px" value="<?php echo $supppostcd_2;?>">
		   </td>
		  </tr>
		  <tr>
	  	    <td style="width: 2px"></td>
	  	    <td class="tdlabel" style="width: 525px">State&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	  	    <td>:</td>
	  	    <td>
	  	    <input class="inputtxt" name="suppstate_2" id ="suppstate_2id" type="text" maxlength="50" style="width: 151px" value="<?php echo $suppstate_2;?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  	    </td>
			<td></td>
			<td class="tdlabel" style="width: 290px">Country&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
			<td style="width: 109px">:</td>
            <td style="width: 468px">
	  	     <select name="selcount_2" style="width: 279px">
	  	        <option value="<?php echo $suppcount_2; ?>"><?php echo $suppcountde_2; ?></option>
			    <?php
                   $sql = "select country_code, country_desc from country_master ORDER BY country_desc ASC";
                   $sql_result = mysql_query($sql);
                  
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
			<input class="inputtxt" name="supptel2" id ="supptel2id" type="text" maxlength="50" style="width: 240px" value="<?php echo $supptel2;?>"></td>
			<td></td>
			<td style="width: 290px">Fax</td>
			<td style="width: 109px">:</td>
			<td style="width: 468px">
			<input class="inputtxt" name="suppfax2" id ="suppfax2id" type="text" maxlength="50" style="width: 294px" value="<?php echo $suppfax2;?>"></td>
		  </tr>
		  	<tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 525px">Mobile Phone </td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="suppmobile2" id ="suppmobile2id" type="text" maxlength="50" style="width: 240px" value="<?php echo $suppmobile2;?>"></td>
			<td></td>
			<td style="width: 290px">&nbsp;</td>
			<td style="width: 109px">&nbsp;</td>
			<td style="width: 468px">
			&nbsp;</td>
		  	</tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 525px">Contact Person </td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="suppconppl2" id ="suppconppl2id" type="text" maxlength="50" style="width: 359px" value="<?php echo $suppconppl2;?>"></td>
			<td></td>
			<td style="width: 290px">Email </td>
			<td style="width: 109px">:</td>
			<td style="width: 468px">
			<input class="inputtxt" name="suppeml2" id ="suppeml2id" type="text" maxlength="50" style="width: 359px" onBlur="AjaxFunction(this.value);" value="<?php echo $suppeml2;?>">
			</td>
		  </tr>
	  	 </table>
	  	 </fieldset>
	  	 <table>
	  	 <tr><td style="width: 1198px"></td></tr>
	  	 <tr>
	  	   <td align="center" style="width: 1198px">
	  	   <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px" >
	  	   <input type=submit name = "Submit" value="Update" class="butsub" style="width: 60px; height: 32px" >
	  	   </td>
	  	  </tr>
	  	 </table>
	   </form>	
	   </fieldset>
	   </div>
</body>
</html>
