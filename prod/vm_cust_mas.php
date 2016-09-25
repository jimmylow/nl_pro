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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<style media="all" type="text/css">
@import "../css/styles.css";

.auto-style1 {
	margin-top: 22px;
}

</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" charset="utf-8">
$(function(){
  $('legend').click(function(){
   $(this).siblings().slideToggle("slow");
  });
});
 
</script>
</head>
<?php
 	$sql = "select create_by, 	creation_time, 	modified_by, 	modified_on ";
   	$sql .= " from customer_master";
   	$sql .= " where customer_code ='".$var_custcd."'";   

   	$sql_result = mysql_query($sql) or die(mysql_error());
   	 
   	$row = mysql_fetch_array($sql_result);

	$create_by = $row[0];
    $create_on = $row[1];
    $modified_by= $row[2];
 	$modified_on = $row[3];
?>
  <?php include("../topbarm.php"); ?> 
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

<body>
 
  <div class="contentc">

	<fieldset name="Group1" style=" width: 1045px; height: 840px;" class="style2">
	 <legend class="title">CUSTOMER MASTER DETAIL - <?php echo $var_custcd;?> (<?php echo $custname;?>)</legend>
	  <br>
	
	  <form name="InpCustMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 887px; width: 970px;">
	   <table>
	    <tr>
	      <td></td>
	      <td class="tdlabel" style="width: 218px">Customer Code</td>
	      <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="custcd" id ="custcdid" type="text" readonly="readonly" style="width: 94px" value="<?php echo $var_custcd;?>">
		  </td>
		  <td></td>
		  <td class="tdlabel" style="width: 84px">Status</td>
	  	  <td>:</td>
	  	  <td>
	  	   <input class="inputtxt" name="selactive" id ="selactive" type="text" readonly="readonly" style="width: 125px" value="<?php echo $custstat;?>">	
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
	  	  <td class="tdlabel" style="width: 218px">Customer Name</td>
	  	  <td>:</td>
	  	  <td>
		  <input class="inputtxt" name="custname" id ="custnmid" type="text" readonly="readonly" style="width: 396px" value="<?php echo $custname; ?>">
		  </td>
		  <td></td>
		  <td class="tdlabel" style="width: 84px">Currency Code</td>
	  	  <td>:</td>
	  	  <td>
	  	   <input class="inputtxt" name="selcurr" id ="selcurr" type="text" readonly="readonly" style="width: 68px" value="<?php echo $custcurr; ?>">
		  </td>
	  	 </tr>
	  	 <tr><td></td></tr>
         <tr>
          <td></td>
          <td style="width: 218px">Local/Oversea</td>
          <td>:</td>
          <td>
          	<input class="inputtxt" name="selloovl" id ="selloovl" type="text" readonly="readonly" style="width: 125px" value="<?php echo $custloovl; ?>">
          </td>
		  <td></td>
		  <td style="width: 84px">Terms</td>
		  <td>:</td>
		  <td>
		  	<input class="inputtxt" name="selterm" id ="selterm" type="text" readonly="readonly" style="width: 165px" value="<?php echo $custtermde; ?>">
		  </td> 	
         </tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td style="width: 218px">Pay Mode</td>
          <td>:</td>
          <td>
          	<input class="inputtxt" name="selpmode" id ="selpmode" type="text" readonly="readonly" style="width: 221px" value="<?php echo $custpde; ?>">
		  </td>
          <td></td>
          <td style="width: 84px">Website</td>
          <td>:</td>
          <td>
			<input class="inputtxt" name="custweb" id ="custwebid" type="text" readonly="readonly" style="width: 345px" value="<?php echo $custweb; ?>"></td>
         </tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td style="width: 218px">Remark</td>
          <td>:</td>
          <td>
		  <input class="inputtxt" name="custrmk" id ="custrmkid" type="text" readonly="readonly" style="width: 396px" value="<?php echo $custrmk; ?>"></td>
          <td></td>
          <td style="width: 84px"></td>
          <td></td>
         </tr>
         	<tr>
	      	<td style="width: 14px"></td>
	      	<td class="tdlabel" style="width: 80px">Create By</td>
	      	<td style="width: 10px">:</td>
	  	  	<td style="width: 195px">
		  	<input class="textnoentry1" name="create_by" id ="create_byid" type="text" readonly="readonly" style="width: 152px" value="<?php echo $create_by;?>">
		  	</td>
		  	<td style="width: 13px"></td>
		  	<td class="tdlabel" style="width: 117px">Create On</td>
	  	  	<td style="width: 1px">:</td>
	  	  	<td style="width: 158px">
	  	   	<input class="textnoentry1" name="create_on" id ="create_onid" type="text" readonly="readonly" style="width: 152px" value="<?php echo $create_on;?>">
		  	</td>
	  		</tr>
	  	 	<tr>
	  	 	<td style="width: 14px"></td> 
	  	  	<td style="width: 80px"></td>
	  	  	<td style="width: 10px"></td> 
          	<td style="width: 195px"></td>
	   	 	</tr> 
	   	 	<tr>
	   	 	<td style="width: 14px"></td>
	      	<td class="tdlabel" style="width: 80px">Modified_by</td>
	      	<td style="width: 10px">:</td>
	  	  	<td style="width: 195px">
		  	<input class="textnoentry1" name="modified_by0" id ="modified_byid0" type="text" readonly="readonly" style="width: 152px" value="<?php echo $modified_by;?>">
		  	</td>
		  	<td style="width: 13px"></td>
		  	<td class="tdlabel" style="width: 117px">Modified On</td>
	  	  	<td style="width: 1px">:</td>
	  	  	<td style="width: 158px">
	  	   	<input class="textnoentry1" name="modified_on0" id ="suppstateid1" type="text" readonly="readonly" style="width: 152px" value="<?php echo $modified_on; ?>">
		  	</td>
	  		</tr>
	  	 	<tr>
        </table>
        <br>
        <fieldset name="Group1" class="style2" style="width: 1020px">
	     <legend class="style3"><strong>Contact Information</strong></legend>
	      <table>
	  	  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 1</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="custadd1" id ="custadd1id" type="text" readonly="readonly" style="width: 396px" value="<?php echo $custadd1; ?>">
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
			<input class="inputtxt" name="custadd2" id ="custadd2id" type="text" readonly="readonly" style="width: 396px" value="<?php echo $custadd2; ?>">
			</td>
			<td></td>
			<td>Mobile No</td>
			<td>:</td>
			<td>
			<input class="inputtxt" name="mobilen" id ="mobilen" type="text" value="<?php echo $custmob; ?>"style="width: 200px;" readonly="readonly"></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">City</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="custcity" id ="custcityid" type="text" readonly="readonly" style="width: 282px" value="<?php echo $custcity; ?>">
			</td>
			<td></td>
			<td style="width: 131px">Postal Code</td>
			<td>:</td>
            <td>
			<input class="inputtxt" name="custpostcd" id ="custpostcdid" type="text" readonly="readonly" style="width: 151px" value="<?php echo $custpostcd; ?>">
			</td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">State</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
	  	    <input class="inputtxt" name="custstate" id ="custstateid" type="text" readonly="readonly" style="width: 151px" value="<?php echo $custstate; ?>"></td>
			<td></td>
			<td class="tdlabel" style="width: 131px">Country</td>
            <td>:</td>
            <td>
            <input class="inputtxt" name="selcount" id ="selcount" type="text" readonly="readonly" style="width: 151px" value="<?php echo $custcountde; ?>">	 
		   </td>
		  </tr>
		  <tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Telephone</td>
           <td style="width: 8px">:</td>
           <td>
			<input class="inputtxt" name="custtel" id ="custtelid" type="text" readonly="readonly" style="width: 200px;" value="<?php echo $custtel; ?>">
		   </td>
		   <td></td>
           <td style="width: 131px">Fax</td>
           <td>:</td>
           <td>
			<input name="custfax" id ="custfaxid" type="text" readonly="readonly" style="width: 200px" value="<?php echo $custfax; ?>"></td>
		  </tr>
		  <tr>
	  	    <td style="height: 30px"></td>
	  	    <td class="tdlabel" style="width: 83px; height: 30px;">Contact<br>Person</td>
	  	    <td style="width: 8px; height: 30px;">:</td>
	  	    <td style="height: 30px">
			<input class="inputtxt" name="custconppl" id ="custconpplid" type="text" readonly="readonly" style="width: 345px" value="<?php echo $custppl; ?>">
			</td>
			<td style="height: 30px"></td>
			<td style="width: 131px; height: 30px;">Email</td>
			<td style="height: 30px">:</td>
			<td style="height: 30px">
			<input class="inputtxt" name="custeml" id ="custemlid" type="text" readonly="readonly" style="width: 345px" value="<?php echo $custeml; ?>"></td>
		  </tr>
		  <tr>    
	  	    <td></td><td></td><td></td><td></td><td></td><td style="width: 131px"></td><td></td><td></td>
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
			<input class="inputtxt" name="ageadd1" id ="ageadd1" type="text" style="width: 396px" value="<?php echo $ageadd1; ?>" readonly="readonly">
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
			<input class="inputtxt" name="ageadd2" id ="ageadd2" type="text" style="width: 396px" value="<?php echo $ageadd2; ?>" readonly="readonly">
			</td>
			<td></td>
			<td>Mobile No</td>
			<td>:</td>
			<td>
			<input class="inputtxt" name="agemob" id ="agemob" type="text" readonly="readonly" value="<?php echo $agemob; ?>" style="width: 200px;"></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">City</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="agecity" id ="agecity" type="text" style="width: 282px" value="<?php echo $agecity; ?>" readonly="readonly">
			</td>
			<td></td>
			<td style="width: 131px">Postal Code</td>
			<td>:</td>
            <td>
			<input class="inputtxt" name="agepost" id ="agepost" type="text" style="width: 151px" value="<?php echo $agepostcd; ?>" readonly="readonly">
			</td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">State</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
	  	    <input class="inputtxt" name="agestate" id ="agestate" type="text" style="width: 151px" value="<?php echo $agestate; ?>" readonly="readonly"></td>
			<td></td>
			<td class="tdlabel" style="width: 131px">Country</td>
            <td>:</td>
            <td>
	  	     <input class="inputtxt" name="agecountry" id ="agecountry" type="text" style="width: 279px" value="<?php echo $agecountde; ?>" readonly="readonly">		   
	  	     </td>
		  </tr>
		  <tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Telephone</td>
           <td style="width: 8px">:</td>
           <td>
			<input class="inputtxt" name="agetel" id ="agetel" type="text" style="width: 200px;" value="<?php echo $agetel; ?>" readonly="readonly">
		   </td>
		   <td></td>
           <td style="width: 131px">Fax</td>
           <td>:</td>
           <td>
			<input name="agefax" id ="agefax" type="text" style="width: 200px" value="<?php echo $agefax; ?>" readonly="readonly">
			</td>
		  </tr>
		  <tr>
	  	    <td style="height: 30px"></td>
	  	    <td class="tdlabel" style="width: 83px; height: 30px;">Contact<br>Person</td>
	  	    <td style="width: 8px; height: 30px;">:</td>
	  	    <td style="height: 30px">
			<input class="inputtxt" name="ageconppl" id ="ageconppl" type="text" style="width: 345px" value="<?php echo $ageppl;?>" readonly="readonly">
			</td>
			<td style="height: 30px"></td>
			<td style="width: 131px; height: 30px;">Email</td>
			<td style="height: 30px">:</td>
			<td style="height: 30px">
			<input class="inputtxt" name="ageeml" id ="ageeml" type="text"style="width: 345px" value="<?php echo  $ageeml;?>" readonly="readonly">
			</td>
		  </tr>
		  <tr>    
	  	    <td></td><td></td><td></td><td></td><td></td><td style="width: 131px"></td><td></td><td><div id="msg"></div></td>
		  </tr>

		  </table>
		  </fieldset>
		  <table style="width: 1014px">
	  	 	<tr>
	  	 		<td align="center" style="width: 14px">
	  	 		  <?php
	  	 		  $locatr = "m_cust_mas.php?menucd=".$var_menucode;			
		 		  echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
	  	 		  ?>
	  	 		 </td>
	  	  	</tr>
		  
	  	 </table>
	   </form>	
	   </fieldset>

	</div>
	 <div class="auto-style1"></div>
</body>

</html>
