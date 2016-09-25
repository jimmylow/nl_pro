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
      $var_rm_cd = mysql_real_escape_string($_GET['rawmatcd']);	
      //$var_rm_cd = $_GET['rawmatcd'];
	  $var_menucode = $_GET['menucd'];
    }
    
    if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menudcode'];

         $backloc = "../stck_mas/rawmat_mas.php?menucd=".$var_menucode;
    
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
.style2 {
	margin-right: 0px;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>

<script type="text/javascript" charset="utf-8"> 

</script>
</head>
 <?php include("../topbarm.php"); ?> 
 <!-- <?php include("../sidebarm.php"); ?> -->
<body>
 
  <div class="contentc">
	<fieldset name="Group1" style=" width: 1011px; height: 380px;">
	 <legend class="title">RAW MATERIAL MAIN CODE DETAILS : <?php echo $var_rm_cd; ?></legend>
	 <?php
	    $sql = "select  active_flag, currency_code, cat_desc, ";
        $sql .= " uom, description, itm_grp_cd, content, ";
        $sql .= " remark, ";
        $sql .= " rawmat_master.create_by, rawmat_master.creation_time,";
        $sql .= " rawmat_master.modified_by, rawmat_master.modified_on";
        $sql .= " from rawmat_master, cat_master";
        $sql .= " where rm_code ='".$var_rm_cd."' and category = cat_code";
        
        //echo $sql;
        $sql_result = mysql_query($sql);
        
        $row = mysql_fetch_array($sql_result);

        $active_flag = $row[0];
        $currency_code = $row[1];
        $category = $row[2];
        $uom  = $row[3];
        $description  = $row[4];
        $stock_group = $row[5];
        $content = $row[6];
        $remark = $row[7];
        $create_by = $row[8];
        $creation_time = date('d-m-Y', strtotime($row[9]));
        $modified_by = $row[10];
        $modified_on = date('d-m-Y', strtotime($row[11]));

    ?>		
	  <br>
	  <form name="InpSuppMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
	    <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">

		<table style="width: 980px; height: 256px;">
	  	  <tr>
	  	    <td style="width: 2px"></td>
	  	    <td style="width: 114px">Raw Mat Code</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 375px">
			<input class="inputtxt" name="rm_code" id ="suppcdid" type="text" style="width: 161px" readonly="readonly" value="<?php echo $var_rm_cd; ?>">
			</td>
			<td style="width: 120px"></td>
		    <td style="width: 96px">Status</td>
	  	    <td style="width: 7px">:</td>
	  	    <td style="width: 108px">
			<input class="inputtxt" name="active_flag" id ="suppactid" type="text" style="width: 125px" readonly="readonly" value="<?php echo $active_flag; ?>">
			</td>
	  	  </tr>
		  <tr>
	  	    <td></td> 
	  	    <td style="width: 138px"></td>
	   	  </tr> 
	   	   <tr>
	   	    <td style="width: 2px"></td>
	  	    <td style="width: 114px">Category</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 375px">
			<input class="inputtxt" name="category" id ="suppnmid" type="text" style="width: 396px" readonly="readonly" value="<?php echo $category; ?>">
			</td>
			<td style="width: 120px"></td>
		    <td style="width: 96px">Currency Code</td>
	  	    <td style="width: 7px">:</td>
	  	    <td style="width: 108px">
			<input class="inputtxt" name="currency_code" id ="suppcurrid" type="text" style="width: 68px" readonly="readonly" value="<?php echo $currency_code; ?>">
			</td>
	  	  </tr>
		   <tr>
		     <td></td> 
	  	     <td style="width: 138px" class="tdlabel"></td>
	   	  </tr> 
		  <tr>
	  	    <td style="width: 2px"></td>
	  	    <td style="width: 114px">UOM</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 375px">
			<input class="inputtxt" name="uom" id ="suppcityid" type="text" style="width: 282px" readonly="readonly" value="<?php echo $uom; ?>">
			</td>
			<td style="width: 120px"></td>
			<td></td>
			<td></td>
			<td></td>
		  </tr>
		  <tr>
	  	    <td style="width: 2px"></td>
	  	    <td style="width: 114px">Description</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 375px">
			<input class="inputtxt" name="description" id ="supppostcdid" type="text" style="width: 390px" readonly="readonly" value="<?php echo $description; ?>">
			</td>
			<td style="width: 120px"></td>
			<td style="width: 96px">&nbsp;</td>
            <td style="width: 7px">&nbsp;</td>
            <td style="width: 108px">
            &nbsp;</td>
		  </tr>
		  <tr>
	  	    <td style="width: 2px"></td>
	  	    <td style="width: 114px">Content</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 375px">
	  	    <input class="inputtxt" name="content" id ="contentid" type="text" style="width: 279px" readonly="readonly" value="<?php echo $content; ?>">
	  	    </td>
			<td style="width: 120px"></td>
		  </tr>
		  <tr>
	  	    <td style="width: 2px"></td>
	  	    <td style="width: 114px">Remark</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 375px">
			<input class="inputtxt" name="remark" id ="suppwebid" type="text" style="width: 345px" readonly="readonly" value="<?php echo $remark; ?>">
			</td>
			<td style="width: 120px"></td>
		  </tr>
		  <tr>
	  	    <td style="width: 2px"></td>
	  	    <td style="width: 114px">Create By</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 375px">
			<input class="inputtxt" name="create_by" id ="supppostcdid" type="text" style="width: 150px" readonly="readonly" value="<?php echo $create_by; ?>">
			</td>
			<td style="width: 120px"></td>
			<td style="width: 96px">Creation Time</td>
            <td style="width: 7px">:</td>
            <td style="width: 108px">
            <input class="inputtxt" name="creation_time" id ="suppstateid" type="text" style="width: 151px" readonly="readonly" value="<?php echo $creation_time; ?>">
			</td>
		  </tr>
  		  <tr>
	  	    <td style="width: 2px"></td>
	  	    <td style="width: 114px">Last Update By</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 375px">
			<input class="inputtxt" name="modified_by" id ="supppostcdid" type="text" style="width: 150px" readonly="readonly" value="<?php echo $modified_by; ?>">
			</td>
			<td style="width: 120px"></td>
			<td style="width: 96px">Updated On</td>
            <td style="width: 7px">:</td>
            <td style="width: 108px">
            <input class="inputtxt" name="modified_on" id ="suppstateid" type="text" style="width: 151px" readonly="readonly" value="<?php echo $modified_on; ?>">
			</td>
		  </tr>
          <tr>
		   <td colspan="8" align="center">
              <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px">
	          <input type=submit name = "Submit" value="Print" class="butsub" style="width: 60px; height: 32px">
		   </td>
		 </tr>
		 </table>
        </form>
	 </fieldset>
	 </div>
	 <div class="spacer" style="height: 1px"></div>
</body>
</html>

