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
      $var_menucode = $_GET['menucd'];
      $rm_issue_id = $_GET['rm_issue_id'];
      include("../Setting/ChqAuth.php");
    }
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<style media="all" type="text/css">
@import "../css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css";
@import "../css/styles.css";

.general-table #procomat                        { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}
.general-table #procoucost                      { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}
.general-table #prococompt                      { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}

.style2 {
	margin-right: 0px;
}
</style>

<!-- jQuery libs -->
<script type="text/javascript" src="../js/multitable/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>

<script type="text/javascript"> 


function calsalrm(){
	var exftramt  = document.InpJobFMas.totalexft0.value;
	var persaltax = document.InpJobFMas.totaltaxd.value;
	var saltaxamt;
	var saltotamt;
	
	if (exftramt == ""){
	 	exftramt = 0;
	 	document.InpJobFMas.totalexft0.value = parseFloat(exftramt).toFixed(4);
	}
	if (persaltax == ""){
	 	exftramt = 0;
	 	document.InpJobFMas.totaltaxd.value = parseFloat(persaltax);
	}
	saltaxamt = parseFloat(exftramt) * (parseFloat(persaltax)/100);
	saltotamt = parseFloat(exftramt) + parseFloat(saltaxamt);
	document.InpJobFMas.totalsaltid.value = parseFloat(saltaxamt).toFixed(4);
	document.InpJobFMas.totalamtid.value = parseFloat(saltotamt).toFixed(4);
}

</script>
</head>
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->
<body>

  <?php
  	 $sql = "select * from rawmat_issue";
     $sql .= " where rm_issue_id ='".$rm_issue_id."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $issuedate = date('d-m-Y', strtotime($row['issuedate']));
     $refno = $row['refno'];
     $labelno = $row['label_number'];
     $itemcode = $row['item_code'];
     $issueqty = $row['totalqty'];
     $description = $row['description'];
     $requestby = $row['request_by'];
     $sendto = $row['send_to'];
     $create_by = $row['create_by'];
     $create_on = $row['create_on'];
     $modified_by= $row['upd_by'];
 	 $modified_on = $row['upd_on'];


  ?>
  <div class="contentc">

	<fieldset name="Group1" style=" width: 926px;" class="style2">
	 <legend class="title">RAW MAT ISSUE DETAILS&nbsp; :<?php echo $var_revno;?></legend>
	  <br>	 
	  
	  <form name="InpJobFMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="width: 911px">
	   
		<table style="width: 886px">
	   	   <tr>
	   	    <td></td>
	  	    <td style="width: 126px">Issue No</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 239px">
			<input name="issueno" id="issuenoid" type="text" maxlength="10" style="width: 191px;" readonly="readonly" value="<?php echo $rm_issue_id; ?>">
	  	  </tr>  
	   	   <tr>
	   	    <td></td>
			<td style="width: 136px">Issue Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 270px">
		    <input class="inputtxt" readonly="readonly" name="issuedate0" id ="issuedateid0" type="text" style="width: 106px;" value="<?php  echo $issuedate; ?>">
		    </td>
			<td style="width: 29px"></td>
			<td style="width: 136px">&nbsp;</td>
			<td style="width: 16px">&nbsp;</td>
			<td style="width: 270px">
		    &nbsp;</td>
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 126px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 239px"></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 126px">Label ID</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
		   <input name="labelno" id="labelnoid" type="text" style="width: 129px" readonly="readonly" value="<?php echo $labelno; ?>">
		   </td>
	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td style="width: 126px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 239px">&nbsp;</td>
	  	  </tr>
		  <tr>
	  	   <td></td>
	  	   <td style="width: 126px">Send To</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 239px">
		   <input readonly="readonly" name="sendto" id ="sendtoid" type="text" style="width: 276px;" value="<?php echo $sendto; ?>"></td>
		   <td></td>
		   <td style="width: 136px">Request By</td>
		   <td>:</td>
		   <td>
		   <input readonly="readonly" name="requestby" id ="requestbyid" type="text" style="width: 276px;" value="<?php echo $requestby; ?>"></td>
	  	  </tr>
		  	

		  	
	  	    <tr>
	  	   <td></td>
		   <td style="width: 745px">Create By</td>
           <td>:</td>
           <td style="width: 264px">
			<input class="textnoentry1" name="create_by" id ="create_byid" type="text" readonly="readonly" style="width: 150px" value="<?php echo $create_by;?>"></td>
		   <td></td>
           <td style="width: 290px">Create On</td>
           <td style="width: 109px">:</td>
           <td style="width: 468px">
		   <input class="textnoentry1" name="create_on" id ="create_onid" type="text" readonly="readonly" style="width: 160px" value="<?php echo $create_on;?>"></td>
		    </tr>
			<tr>
	  	    <td style="width: 2px"></td>
	  	    <td class="tdlabel" style="width: 745px">Modified By&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	  	    <td>:</td>
	  	    <td style="width: 264px">
	  	    <input class="textnoentry1" name="modified_by" id ="modified_byid" type="text" readonly="readonly" style="width: 150px" value="<?php echo $modified_by;?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  	    </td>
			<td></td>
			<td class="tdlabel" style="width: 290px">Modified On&nbsp;&nbsp;&nbsp; </td>
			<td style="width: 109px">:</td>
            <td style="width: 468px">
	  	     <input class="textnoentry1" name="modified_on" id ="suppstateid0" type="text" readonly="readonly" style="width: 160px" value="<?php echo $modified_on; ?>"></td>
		    </tr>
		  	

		  	
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table" style="width: 904px">
          	<thead>
          	 <tr>
              <th class="tabheader" style="width: 27px">#</th>
              <th class="tabheader">Raw Material Item</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Onhand Bal</th>
              <th class="tabheader">Issue Qty</th>
             </tr>
            </thead>
            <tbody>            
             <?php
             	$sql = "SELECT * FROM rawmat_issue_tran";
             	$sql .= " Where rm_issue_id='".$rm_issue_id ."'"; 
	    		$sql .= " ORDER BY seqno";  
				$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){
					echo '<tr class="item-row">';	
					echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>'; 
					echo '<td><input name="promat[]" value="'.htmlentities($rowq['item_code']).'" id="procomatid" style="width: 250px; border-style: none;" readonly="readonly"></td>';
                	echo '<td><input name="procodesc[]" value="'.htmlentities($rowq['description']).'" id="procomat1" style="width: 300px; border-style: none;" readonly="readonly"></td>';
             		echo '<td><input name="procoum[]" value="'.$rowq['oum'].'" id="procoum" style="width: 75px; border-style: none;" readonly="readonly"></td>';       	
                	echo '<td><input name="procomark[]" id="procomark" readonly="readonly" style="width: 75px; border:0;" value="'.$rowq['onhandbal'].'"></td>';
                	echo '<td><input name="prococost[]" value="'.$rowq['totalqty'].'" id="prococost1" readonly="readonly" style="width: 75px; border:0;"></td>';
                	
                	echo ' </tr>';
                	$i = $i + 1;
                }
             ?>          
            </tbody>
           </table>

      	
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_rm_issue.php?menucd=".$var_menucode;
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				?>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
	  	</table>
	   </form>	
	</fieldset>
	</div>
	<div class="spacer"></div>
</body>

</html>
