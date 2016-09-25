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
      $jobfid  = $_GET['jobfid'];
	  $var_menucode = $_GET['menucd'];
	}
    
     if ($_POST['Submit'] == "Update") {
        $var_jonfid = $_POST['jobfid'];
 		$var_jofdesc = stripslashes(mysql_real_escape_string($_POST['jobfiddesc']));
        $var_jofrate = $_POST['jobfrate'];
        $var_jofstat = $_POST['jobfact'];
        $var_menucode  = $_POST['menudcode'];
        
        if ($var_jonfid <> "") {
         $var_jofdesc = str_replace("'", '^', $var_jofdesc);	
         $vartoday = date("Y-m-d H:i:s");
         $sql = "Update jobfile_master set jobfile_desc='$var_jofdesc',";
         $sql .= " jobfile_rate='$var_jofrate', ";
         $sql .= " actvty = '$var_jofstat', ";
         $sql .= " modified_by ='$var_loginid', ";
         $sql .= " modified_on ='$vartoday' WHERE jobfile_id = '$var_jonfid'";
        
       	 mysql_query($sql) or die(mysql_error()); 
        
         $backloc = "../bom_master/job_id_master.php?menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
        
      }      
    }
    
    if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menudcode'];
         $backloc = "../bom_master/job_id_master.php?menucd=".$var_menucode;
        
 		 echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<style media="all" type="text/css">@import "../css/styles.css";
</style>

<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript"> 

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

function CheckDecimal(chkval)  
{  
   if (chkval != ""){
    if (((chkval / chkval) != 1) && (chkval != 0)) {
		alert('Job Rate Only Can Accept Decimal')
		document.InpJobFMas.jobfrate.focus()
	}else{  
    	document.InpJobFMas.jobfrate.value = (parseFloat(chkval)).toFixed(4); 
	}
   }	
}
</script>
</head>
 
  <!--<?php include("../sidebarm.php"); ?> -->
<body OnLoad="document.InpJobFMas.jobfiddesc.focus()">
<?php include("../topbarm.php"); ?> 
<?php
        $sql = "select jobfile_desc, jobfile_rate, actvty, create_by, create_on, modified_by, modified_on";
        $sql .= " from jobfile_master";
        $sql .= " where jobfile_id ='".$jobfid."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);

        $jobfdesc = htmlentities($row[0]);
        $jobfdesc = str_replace("^", "'", $jobfdesc);
        $jobfrate = $row[1];
        $jobfactvty = $row[2];
        $create_by = $row[3];
    	$create_on = date("d-m-Y", strtotime($row[4]));
    	$modified_by= $row[5];
 		$modified_on = date("d-m-Y", strtotime($row[6]));
    
        switch ($jobfactvty)
        {
        case 'A':
          $jobadesc = "ACTIVE";
          break;
        case 'D':
          $jobadesc = "DEACTIVATE";
          break;
       
        } 
       
?>	
 
  <div class="contentc">
	
	<fieldset name="Group1" style="height: 350px; width: 724px;" class="style2">
	 <legend class="title">EDIT JOB ID MASTER MASTER : <?php echo $jobfid; ?></legend>
	  <br>
	    <form name="InpJobFMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px; width: 630px;">
	    <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">

		<table style="width: 699px; height: 118px">
		   <tr>
	   	    <td style="width: 5px"></td>
	  	    <td style="width: 150px">Job ID </td>
	  	    <td style="width: 12px">:</td>
	  	    <td>
			<input class="inputtxt" readonly="readonly" name="jobfid" id ="jobfdescid" type="text" style="width: 71px" value="<?php echo $jobfid; ?>"></td>
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	  </tr>

	   	   <tr>
	   	    <td style="width: 5px"></td>
	  	    <td style="width: 150px">Job Description </td>
	  	    <td style="width: 12px">:</td>
	  	    <td>
			<input class="inputtxt" name="jobfiddesc" id ="jobfdescid" type="text" maxlength="80" style="width: 458px" value="<?php echo $jobfdesc; ?>"></td>
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 150px;">Rate</td>
	  	   <td>:</td>
	  	   <td>
		   <input class="inputtxt" name="jobfrate" id ="jobfrateid" type="text" maxlength="12" style="width: 94px" onBlur="CheckDecimal(this.value);" value="<?php echo $jobfrate;?>">
		   </td>
	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td style="width: 150px"></td>
	  	   <td></td>
	  	   <td></td>
	  	  </tr>
	  	  <tr>
	  	   <td style="width: 5px"></td>
	  	   <td style="width: 150px">Status</td>
	  	   <td style="width: 12px">:</td>
	  	   <td>
	  	    <select name="jobfact" style="width: 125px">
		    <option value="<?php echo $jobfactvty;?>"><?php echo $jobadesc;?></option>
		    <option value="A">ACTIVE</option>
		    <option value="D">DEACTIVATE</option>
		   </select>
			</td>
	  	  </tr>
	  	   </tr> 
		    <tr>
	  	  	<td></td>
	  	  	<td>Created By</td>
	  	  	<td>:</td>
	  	  	<td>
			<input type="text" class="textnoentry1" readonly="readonly" value="<?php echo $create_by; ?>" name="createby"></td>
	  	  </tr>
	  	  <tr><td></td></tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td>Created On</td>
	  	  	<td>:</td>
	  	  	<td>
			<input type="text" class="textnoentry1" readonly="readonly" value="<?php echo $create_on; ?>" name="createon"></td>
		  </tr>	
	  	  <tr><td></td></tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td>Modified By</td>
	  	  	<td>:</td>
	  	  	<td>
			<input type="text" class="textnoentry1" readonly="readonly" value="<?php echo $modified_by; ?>" name="modby"></td>
	  	  </tr>
	  	  <tr><td></td></tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td>Modified On</td>
	  	  	<td>:</td>
	  	  	<td>
			<input type="text" class="textnoentry1" readonly="readonly" value="<?php echo $modified_on; ?>" name="modon"></td>
	  	  </tr>
	  	  <tr><td></td></tr>

	  	   <tr>
	  	    <td style="width: 5px"></td>
	  	    <td colspan="3">&nbsp;</td>
	  	  </tr>
	  	  <tr>
	  	   <td style="width: 5px"></td>
	  	   <td style="width: 150px">&nbsp;</td>
	  	   <td style="width: 12px">&nbsp;</td>
	  	   <td>
			<input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px" >
			<input type=submit name = "Submit" value="Update" class="butsub" style="width: 60px; height: 32px" >
		   </td>
	  	  </tr>
	  	   <tr>
	  	    <td style="width: 5px"></td>
	  	    <td colspan="3">&nbsp;</td>
	  	  </tr>
	  	</table>
	  	</form>
	</fieldset>
    </div>
</body>

</html>

