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
      $wrkid = $_GET['w'];
	  $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");
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
</script>
</head>

<?php
 	$sql = "select *";
   	$sql .= " from wor_detmas";
   	$sql .= " where workid ='$wrkid'";   
   	$sql_result = mysql_query($sql) or die(mysql_error());
   	$row = mysql_fetch_array($sql_result);

	$wrkname   = htmlentities($row['workname']);
	$status    = $row['status'];
	$wrkdept   = $row['deptcd'];
	$wrkdeptde = htmlentities($row['deptde']);
	$wrktype   = htmlentities($row['worktyp']);
	$worfnl    = $row['worfnl'];
	$wrkprate  = $row['payratecd'];
	$wrkgrp    = $row['grpcd'];
	$wrkgrpde  = htmlentities($row['grpde']);
	$prodimg   = htmlentities($row['imgname']);
	$create_by = $row['create_by'];
    $create_on = date("d-m-Y", strtotime($row['create_on']));
    $modified_by= $row['modified_by'];
 	$modified_on = date("d-m-Y", strtotime($row['modified_on']));
 	$country    = $row['country'];
	
	$sql  = "select paydesc";
  	$sql .= " from wor_payrate";
   	$sql .= " where paycode ='$wrkprate'";   
   	$sql_result = mysql_query($sql) or die(mysql_error());
   	$row = mysql_fetch_array($sql_result);
	$payde = htmlentities($row[0]);

	$sql  = "select countrydesc";
  	$sql .= " from wor_country";
   	$sql .= " where countrycode ='$country'";   
   	$sql_result = mysql_query($sql) or die(mysql_error());
   	$row = mysql_fetch_array($sql_result);
	$worctry = htmlentities($row[0]);
	
	
	switch ($status){
	case "A":
		$statde = "ACTIVE";
		break;
	case "Z":
		$statde = "DEACTIVE";
		break;
	}
			
 	
 	if ($prodimg == "" ){
 		$imgname = "../images/ImageHandler.gif";
 	}else{
 		$dirimg = "../workefile/wrkimg/";
    	$imgname = $dirimg.$prodimg;
	}
?>
 
  <!--<?php include("../sidebarm.php"); ?>--> 
<?php
	 $sql2 = "select * from wor_jobrate";
	 $sql2 .= " where workerid ='".$wrkid."'";
	 $sql2 .= " order by seqno ";
	 $sql2_result = mysql_query($sql2);
	 $row2 = mysql_fetch_array($sql_result);
	
	 $jobid = $row2['jobid'];
	 $rate = $row2['rate'];
	 
	 
	 $sql3 = "select jobfile_desc from jobfile_master";
	 $sql3 .= " where jobfile_id ='".$jobid."'";
	 $sql3_result = mysql_query($sql3);
	 $row3 = mysql_fetch_array($sql_result);
	
	 $jobfile_desc = $row3['jobfile_desc'];
?>

<body>
  <?php include("../topbarm.php"); ?> 
  <div class="contentc">

	<fieldset name="Group1" style="width: 900px; height: 800px;" class="style2">
	 <legend class="title">WORKER DETAIL</legend>
	  <br>
	
	  <form name="InpWrkMas" method="POST" enctype="multipart/form-data" style="height: 134px; width: 815px;">
	   <table>
	    <tr>
	      <td></td>
	      <td class="tdlabel" style="width: 111px">Worker ID<span class="style4"> </span></td>
	      <td>:</td>
	  	  <td style="width: 306px">
		  <input class="inputtxt" name="wrkid" id ="wrkid" type="text" style="width: 94px" readonly="readonly" value="<?php echo $wrkid; ?>">
		  </td>
		  <td style="width: 30px"></td>
		  <td class="tdlabel" style="width: 84px">Status</td>
	  	  <td>:</td>
	  	  <td>
	  	  	<input class="inputtxt" name="selactive" id ="selactive" type="text" style="width: 165px" readonly="readonly" value="<?php echo $statde; ?>">
		  </td>
	  	 </tr>
	  	 <tr>
	  	  <td></td> 
	  	  <td style="width: 111px"></td>
	  	  <td></td> 
          <td style="width: 306px"><div id="msgcd"></div></td>
	   	 </tr> 
	   	 <tr>
	   	  <td></td>
	  	  <td class="tdlabel" style="width: 111px">Worker Name
		  </td>
	  	  <td>:</td>
	  	  <td colspan="5">
		  <input class="inputtxt" name="wrkname" id ="wrkname" type="text" style="width: 459px" value="<?php echo $wrkname; ?>" readonly="readonly">
		  </td>
	  	 </tr>
	  	 <tr><td></td></tr>
         <tr>
          <td></td>
          <td style="width: 111px">Department</td>
          <td>:</td>
          <td style="width: 306px">
          	 <input class="inputtxt" name="seldept" id ="seldept" type="text" style="width: 94px" value="<?php echo $wrkdept; ?>" readonly="readonly">
          	 <label><?php echo $wrkdeptde; ?></label>
		  </td>
		  <td style="width: 30px"></td>
		  <td style="width: 84px">Type</td>
		  <td>:</td>
		  <td>
			<input class="inputtxt" name="wrktype" id ="wrktype" type="text" readonly="readonly" style="width: 225px" value="<?php echo $wrktype; ?>">
		  </td> 	
         </tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td style="width: 111px">Local/Foreign</td>
          <td>:</td>
          <td style="width: 306px">
          	 <input class="inputtxt" name="selfnl" id ="selfnl" type="text" readonly="readonly" style="width: 125px" value="<?php echo $worfnl; ?>">
		  </td>
          <td style="width: 30px"></td>
          <td style="width: 84px">Payrate</td>
          <td>:</td>
          <td>
          	<input class="inputtxt" name="selpayrate" id ="selpayrate" type="text" style="width: 94px" value="<?php echo $wrkprate ?>" readonly="readonly">
        	<label><?php echo $payde; ?></label>
          </td>
         </tr>
           <tr>
			   <td></td>
		   </tr>
		   <tr>
          <td></td>
          <td style="width: 111px">Country</td>
          <td>:</td>
          <td style="width: 306px">
          	 <input class="inputtxt" name="selcountry" id ="selfnl0" type="text" readonly="readonly" style="width: 125px" value="<?php echo $worctry; ?>">
		  </td>
           </tr>
         <tr><td></td></tr>
         <tr>
          <td></td>
          <td style="width: 111px">Group</td>
          <td>:</td>
          <td style="width: 306px">
          	 <input class="inputtxt" name="selgrp" id ="selgrp" type="text" style="width: 94px" value="<?php echo $wrkgrp; ?>" readonly="readonly">
          	 <label><?php echo $wrkgrpde; ?></label>
          </td>
          <td style="width: 30px"></td>
          <td style="width: 84px" colspan="3" rowspan="5">
          	<div id="empphoto">
     			<img src="<?php echo $imgname; ?>"  style="width: 130px;  height:120px;">
			</div>

          </td>
          <td></td>
         </tr>
         <tr><td></td></tr>
         <tr>
         	<td></td>
         	<td>Create By</td>
         	<td>:</td>
         	<td style="width: 306px">
         		<input class="textnoentry1" name="create_by" id ="create_byid" type="text" readonly="readonly" style="width: 152px" value="<?php echo $create_by;?>">
         	</td>
         </tr>
         <tr>
         	<td></td>
         	<td>Create On</td>
         	<td>:</td>
         	<td><input class="textnoentry1" name="create_on" id ="create_onid" type="text" readonly="readonly" style="width: 152px" value="<?php echo $create_on;?>"></td>
         </tr>
         <tr>
         	<td></td>
         	<td>Modified By</td>
         	<td>:</td>
         	<td><input class="textnoentry1" name="modified_by" id ="modified_byid" type="text" readonly="readonly" style="width: 152px" value="<?php echo $modified_by;?>"></td>
         </tr>
         <tr>
         	<td></td>
         	<td>Modified On</td>
         	<td>:</td>
         	<td>
	  	     <input class="textnoentry1" name="modified_on" id ="suppstateid0" type="text" readonly="readonly" style="width: 152px" value="<?php echo $modified_on; ?>"></td>
         </tr>
         <tr><td></td></tr>
         <tr>
         	<td colspan="8" align="left">
         		<?php
         			$locatr = "wo_m_wrkdet.php?menucd=".$var_menucode;
				 	echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
	  	   		?>
         	</td>
         </tr>
        </table>
        
        <table id="itemsTable" class="general-table" style = "width: 900px;">
          	<thead>
          	 <tr>
              <th class="tabheader" style="width: 27px">#</th>
              <th class="tabheader">Job ID</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">Job Rate</th>

             </tr>
            </thead>
            <tbody>            
             <?php
             	 $sql2 = "select * from wor_jobrate";
				 $sql2 .= " where workerid ='".$wrkid."'";
				 $sql2 .= " order by seqno ";
				 //$sql2_result = mysql_query($sql2);
				 //$row2 = mysql_fetch_array($sql_result);
				
				 //$jobid = $row2['jobid'];
				 //$rate = $row2['rate'];
				 $rs_result = mysql_query($sql2); 	 
				 
			   
			    $i = 1;
				while ($row2 = mysql_fetch_assoc($rs_result)){
				
					$sql3 = "select jobfile_desc from jobfile_master ";
					$sql3 .= " where jobfile_id ='".$row2["jobid"]."'";
					//echo $sql3;
					$sql3_result = mysql_query($sql3);
					$row3 = mysql_fetch_array($sql3_result);
					
					$jobfile_desc = $row3['jobfile_desc'];


					echo '<tr class="item-row">';	
					echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>'; 
					echo "<td><input name='promat[]' value='".$row2["jobid"]."' id='procomatid' style='width: 250px; border-style: none;' readonly='readonly'></td>";
                	echo '<td><input name="procodesc[]" value="'.$jobfile_desc.'" id="procomat1" style="width: 300px; border-style: none;" readonly="readonly"></td>';
             		echo '<td><input name="prococost[]" value="'.$row2['rate'].'" id="opening_cost1" readonly="readonly" style="width: 75px; border:0;"></td>';
                	
                	echo ' </tr>';
                	$i = $i + 1;
                }
             ?>          
            </tbody>
           </table>

	   </form>	
	   </fieldset>

	</div>
	 <div class="spacer"></div>
</body>

</html>
