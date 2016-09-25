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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>SUPPLIER DETAIL <?php echo $var_supp_cd ?></title>
<style media="all" type="text/css">@import "../css/styles.css";
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>

<script type="text/javascript" charset="utf-8"> 
$(function(){
  $('legend').click(function(){
   $(this).siblings().slideToggle("slow");
  });
});

</script>
</head>

<body>
<?php
        $sql = "select supplier_desc, active_flag, currency_code, address_1_1, ";
        $sql .= " address_2_1, website, telephone_1, telephone_2, country_1, city_1, ";
        $sql .= " postal_code_1, state_1, conppl1, conppl2, email1, email2, loovs, ";
        $sql .= " terms, paymode, itemsupp, remark, fax1, fax2, ";
        $sql .= " modified_by, modified_on, address_1_2, address_2_2,city_2,postal_code_2, state_2,country_2, mobile_phone_1, mobile_phone_2, create_by,  creation_time, ";
        $sql .= " gstno";
        $sql .= " from supplier_master";
        $sql .= " where supplier_code ='".$var_supp_cd."'";
        
//echo $sql;
//break;
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
        $modified_by = $row[23];
        $modified_on = $row[24];       
        $suppadd1_2 = $row[25];
        $suppadd2_2 = $row[26];
        $suppcity_2 = $row[27];
        $supppostcd_2 = $row[28];
        $suppstate_2 = $row[29];
        $suppcount_2 = $row[30];
        
        $suppmobile1 = $row[31];
        $suppmobile2 = $row[32];
        
        $create_by = $row[33];
        $creation_time = $row[34];
		$gstno = $row[35];
        
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

	<?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>--> 
  <div class="contentc">

	  <fieldset name="Group1" style="width: 1005px; height: 950px">
	  <legend class="title">SUPPLIER MASTER - <?php echo $var_supp_cd; php?></legend>
       <form name="VmUserMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 1130px">
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
	  	   <input class="inputtxt" name="suppact" id ="suppactid" readonly="readonly" type="text" style="width: 125px" value="<?php echo $suppstat; ?>">
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
		  <input class="inputtxt" name="suppname" id ="suppnmid" type="text" readonly="readonly" style="width: 396px" value="<?php echo $suppde; ?>">
		  </td>
		  <td></td>
		  <td class="tdlabel">Currency Code</td>
	  	  <td>:</td>
	  	  <td>
	  	   <input class="inputtxt" name="suppcurr" id ="suppcurrid" readonly="readonly" type="text" style="width: 68px" value="<?php echo $suppcurr; ?>">
		  </td>
	  	 </tr>
	  	 <tr><td></td></tr>
         <tr>
          <td></td>
          <td>Local/Oversea</td>
          <td>:</td>
          <td>
           <input class="inputtxt" name="supploovl" id ="supploovlid" readonly="readonly" type="text" style="width: 125px" value="<?php echo $supploovs; ?>">
		  <td></td>
		  <td>Terms</td>
		  <td>:</td>
		  <td>
		   <input class="inputtxt" name="suppterm" id ="supptermid" readonly="readonly" type="text" style="width: 165px" value="<?php echo $supptermde; ?>">
          </td> 	
         </tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td>Pay Mode</td>
          <td>:</td>
          <td>
          <input class="inputtxt" name="suppmode" id ="suppmodeid" readonly="readonly" type="text" style="width: 221px" value="<?php echo $supppmde; ?>">
		  </td>
          <td></td>
          <td>Website</td>
          <td>:</td>
          <td>
			<input class="inputtxt" name="suppweb" id ="suppwebid" type="text" readonly="readonly" style="width: 345px" value="<?php echo $suppweb; ?>"></td>
         </tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td>Item Supplied</td>
          <td>:</td>
          <td>
		    <input class="inputtxt" name="suppitms" id ="suppitmsid" type="text" readonly="readonly" style="width: 396px" value="<?php echo $suppitms; ?>">
		  </td>
          <td></td>
          <td>GST ID No</td>
          <td></td>
          <td>
          <input class="inputtxt" name="gstno" id ="gstno" type="text" readonly="readonly" value="<?php echo $gstno; ?>" style="width: 100px"></td>
          </td>
         </tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td>Remark</td>
          <td>:</td>
          <td>
		   <input class="inputtxt" name="supprmk" id ="supprmkid" type="text" readonly="readonly" style="width: 396px" value="<?php echo $supprmk; ?>"></td>
          <td></td>
          <td></td>
          <td></td>
         </tr>
        </table>
        <br>
        <fieldset name="Group1" class="style2" style="width: 980px">
	     <legend class="style3"><strong>Contact Information 1</strong></legend>
	      <table>
	  	  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">Address 1</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="suppadd1" id ="suppadd1id" type="text" readonly="readonly" style="width: 396px" value="<?php echo $suppadd1; ?>">
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
			<input class="inputtxt" name="suppadd2" id ="suppadd2id" type="text" readonly="readonly" style="width: 396px" value="<?php echo $suppadd2; ?>">
			</td>
			<td></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">City</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
			<input class="inputtxt" name="suppcity" id ="suppcityid" type="text" readonly="readonly" style="width: 282px" value="<?php echo $suppcity; ?>">
			</td>
			<td></td>
			<td style="width: 81px">Postal Code</td>
			<td>:</td>
            <td>
			<input class="inputtxt" name="supppostcd" id ="supppostcdid" type="text" readonly="readonly" style="width: 151px" value="<?php echo $supppostcd; ?>">
			</td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 83px">State</td>
	  	    <td style="width: 8px">:</td>
	  	    <td>
	  	    <input class="inputtxt" name="suppstate" id ="suppstateid" type="text" readonly="readonly" style="width: 151px" value="<?php echo $suppstate; ?>"></td>
			<td></td>
			<td class="tdlabel" style="width: 81px">Country</td>
            <td>:</td>
            <td>
            <input class="inputtxt" name="suppstate" id ="suppstateid" type="text" readonly="readonly" style="width: 279px" value="<?php echo $suppcountde; ?>">
            </td>
		  </tr>
		  <tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Telephone</td>
           <td style="width: 8px">:</td>
           <td>
			<input class="inputtxt" name="supptel1" id ="supptel1id" type="text" readonly="readonly" style="width: 161px" value="<?php echo $supptel1;?>">
		   </td>
		   <td></td>
           <td style="width: 81px">Fax </td>
           <td>:</td>
           <td>
			<input class="inputtxt" name="suppfax1" id ="suppfax1id" type="text" readonly="readonly" style="width: 294px" value="<?php echo $suppfax1;?>"></td>
		  </tr>
		  	<tr>
		   <td></td>
		   <td class="tdlabel" style="width: 83px">Mobile Phone</td>
           <td style="width: 8px">:</td>
           <td>
			<input class="inputtxt" name="suppmobile1" id ="suppmobile1id" type="text" readonly="readonly" style="width: 161px" value="<?php echo $suppmobile1;?>">
		   </td>
		   <td></td>
           <td style="width: 81px">&nbsp;</td>
           <td>&nbsp;</td>
           <td>
			&nbsp;</td>
		  	</tr>
		  <tr>
	  	    <td style="height: 30px"></td>
	  	    <td class="tdlabel" style="width: 83px; height: 30px;">Contact<br>Person</td>
	  	    <td style="width: 8px; height: 30px;">:</td>
	  	    <td style="height: 30px">
			<input class="inputtxt" name="suppconppl1" id ="suppconppl1id" type="text" readonly="readonly" style="width: 345px" value="<?php echo $suppconppl1;?>">
			</td>
			<td style="height: 30px"></td>
			<td style="width: 81px; height: 30px;">Email</td>
			<td style="height: 30px">:</td>
			<td style="height: 30px">
			<input class="inputtxt" name="suppeml1" id ="suppeml1id" type="text" readonly="readonly" style="width: 345px" value="<?php echo $suppeml1;?>"></td>
		  </tr>
		  <tr>
	  	    <td></td>
		  </tr>
		  </table>
		  </fieldset>
		  <br><br>
		  <fieldset name="Group1" style="width: 977px">
	      <legend class="style3"><strong>Contact Information 2</strong></legend>
          <table style="display:none">
		  <tr>
		   <td style="width: 2px"></td>
		   <td class="tdlabel" style="width: 525px">Address 1</td>
		   <td>:</td>
		   <td>
			<input class="inputtxt" name="suppadd1_2" id ="suppadd1_2id" type="text" readonly="readonly" style="width: 396px" value="<?php echo $suppadd1_2;?>">
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
			<input class="inputtxt" name="suppadd2_2" id ="suppadd2_2id" type="text" readonly="readonly" style="width: 396px" value="<?php echo $suppadd2_2;?>"></td>
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
			<input class="inputtxt" name="suppcity_2" id ="suppcity_2id" type="text" readonly="readonly" style="width: 282px" value="<?php echo $suppcity_2;?>"></td>
           <td></td>
           <td style="width: 290px">Postal Code</td>
           <td style="width: 109px">:</td>
           <td style="width: 468px">
		   <input class="inputtxt" name="supppostcd_2" id ="supppostcd_2id" type="text" readonly="readonly" style="width: 151px" value="<?php echo $supppostcd_2;?>">
		   </td>
		  </tr>
		  <tr>
	  	    <td style="width: 2px"></td>
	  	    <td class="tdlabel" style="width: 525px">State&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	  	    <td>:</td>
	  	    <td>
	  	    <input class="inputtxt" name="suppstate_2" id ="suppstate_2id" type="text" readonly="readonly" style="width: 151px" value="<?php echo $suppstate_2;?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  	    </td>
			<td></td>
			<td class="tdlabel" style="width: 290px">Country&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
			<td style="width: 109px">:</td>
            <td style="width: 468px">
	  	     <input class="inputtxt" name="suppstate" id ="suppstateid" type="text" readonly="readonly" style="width: 279px" value="<?php echo $suppcountde_2; ?>"></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 525px">Telephone </td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="supptel2" id ="supptel2id" type="text" readonly="readonly" style="width: 240px" value="<?php echo $supptel2;?>"></td>
			<td></td>
			<td style="width: 290px">Fax</td>
			<td style="width: 109px">:</td>
			<td style="width: 468px">
			<input class="inputtxt" name="suppfax2" id ="suppfax2id" type="text" readonly="readonly" style="width: 294px" value="<?php echo $suppfax2;?>"></td>
		  </tr>
		  	<tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 525px">Mobile Phone </td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="suppmobile2" id ="suppmobile2id" type="text" readonly="readonly" style="width: 240px" value="<?php echo $suppmobile2;?>"></td>
			<td></td>
			<td style="width: 290px">Fax</td>
			<td style="width: 109px">:</td>
			<td style="width: 468px">
			<input class="inputtxt" name="suppfax3" id ="suppfax2id0" type="text" readonly="readonly" style="width: 294px" value="<?php echo $suppfax2;?>"></td>
		  	</tr>
		  <tr>
	  	    <td></td>
	  	    <td class="tdlabel" style="width: 525px">Contact Person </td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="suppconppl2" id ="suppconppl2id" type="text" readonly="readonly" style="width: 359px" value="<?php echo $suppconppl2;?>"></td>
			<td></td>
			<td style="width: 290px">Email </td>
			<td style="width: 109px">:</td>
			<td style="width: 468px">
			<input class="inputtxt" name="suppeml2" id ="suppeml2id" type="text" readonly="readonly" style="width: 359px" onBlur="AjaxFunction(this.value);" value="<?php echo $suppeml2;?>">
			</td>
		  </tr>
	  	 </table>
	  	 </fieldset>
          <table>
		  <tr>
		   <td style="width: 2px"></td>
		   <td style="width: 745px">Create By</td>
           <td>:</td>
           <td style="width: 264px">
			<input class="inputtxt" name="create_by" id ="create_byid" type="text" readonly="readonly" style="width: 150px" value="<?php echo $create_by;?>"></td>
           <td></td>
           <td style="width: 290px">Create On</td>
           <td style="width: 109px">:</td>
           <td style="width: 468px">
		   <input class="inputtxt" name="creation_time" id ="creation_timeid" type="text" readonly="readonly" style="width: 150px" value="<?php echo $creation_time;?>"></td>
		  </tr>
		  <tr>
	  	    <td style="width: 2px"></td>
	  	    <td class="tdlabel" style="width: 745px">Modified By&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	  	    <td>:</td>
	  	    <td style="width: 264px">
	  	    <input class="inputtxt" name="modified_by" id ="modified_byid" type="text" readonly="readonly" style="width: 150px" value="<?php echo $modified_by;?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  	    </td>
			<td></td>
			<td class="tdlabel" style="width: 290px">Modified On&nbsp;&nbsp;&nbsp; </td>
			<td style="width: 109px">:</td>
            <td style="width: 468px">
	  	     <input class="inputtxt" name="modified_on" id ="suppstateid0" type="text" readonly="readonly" style="width: 150px" value="<?php echo $modified_on; ?>"></td>
		  </tr>
		  <tr>
	  	    <td align ="center" colspan="8">
	  	 	  <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px" align="middle"></td>
		  </tr>

		  </table>
	  	 	    </td>
		 </form>
	   </fieldset>
	   </div>
</body>
</html>
