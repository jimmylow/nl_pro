<?php

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	$var_loginid = $_SESSION['sid'];
	    
    if($var_loginid == "") { 
      echo "<script>";   
      echo "alert('Not Log In to the system');"; 
      echo "</script>"; 

      echo "<script>";
      echo 'top.location.href = "../login.php"';
      echo "</script>";
    } else {
      $var_selmth   = $_GET['m'];
      $var_selyr   = $_GET['y'];
      $var_menucode = $_GET['menucd'];
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
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.nightly.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>


</head>
<?php
 	$sql = "select distinct create_by,	create_on, 	modified_by, 	modified_on ";
   	$sql .= " from curr_xrate";  
	$sql .= " Where xmth= '$var_selmth' And xyr = '$var_selyr'";

   	$sql_result = mysql_query($sql) or die(mysql_error());
   	 
   	$row = mysql_fetch_array($sql_result);

	$create_by = $row[0];
    $create_on = $row[1];
    $modified_by= $row[2];
 	$modified_on = $row[3];

?>
<body >
  <?php include("../topbarm.php"); ?> 
   <!--<?php include("../sidebarm.php"); ?>-->
   
  	<div class="contentc" style="width: 621px">
	<fieldset name="Group1" style=" width: 354px; height: 515px;" class="style2">
	 <legend class="title">CURRENCY EXCHANGE RATE</legend>
	  <form name="InpCurrMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 641px;" onSubmit= "return chkSubmit()">
	  	<br>
		<table style="width: 301px">
	  	  <tr>
	  	    <td style="width: 133px" class="tdlabel">Exchange Month</td>
	  	    <td style="width: 15px">:</td>
	  	    <td>
	  	    <input class="inputtxt" name="selmth" id ="selmth" type="text" readonly="readonly" style="width: 71px;" value="<?php echo $var_selmth; ?>" >				
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td style="width: 133px" class="tdlabel"></td>
	  	    <td style="width: 15px;"></td>
	  	    <td></td>
	   	  </tr> 
	   	   <tr>
	  	    <td style="width: 133px" class="tdlabel">Exchange Year</td>
	  	    <td style="width: 15px">:</td>
	  	    <td>
			<input class="inputtxt" name="selyr" id ="selyr" type="text" readonly="readonly" style="width: 71px;" value="<?php echo $var_selyr; ?>" >	
			</td>
	  	  </tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td></td>
	  	  	<td><div id="msgcd"></div></td>
	  	  </tr>  
	  	  </table>
	  	  <br>
	  	  <table id="dataTable" style="width: 316px" border="1px solid black;" class="general-table">
	  	     <thead>
	  	     	<tr>
	  	        	<th class="tabheader" style="width: 164px;">Currency Code </th>
          			<th class="tabheader" style="width: 83px;">Sell Rate</th>
          			<th class="tabheader" style="width: 83px;">Buy Rate</th>
			 	</tr>
         	 </thead>
         	 <?php
         	 	
         	 	$sql = "SELECT curr_code, sellrate, buyrate ";
				$sql .= " FROM curr_xrate";
				$sql .= " Where xmth= '$var_selmth' And xyr = '$var_selyr'";
   		    	$sql .= " ORDER BY curr_code";  
		    	$rs_result = mysql_query($sql) or die(mysql_error()); 
		   			
		    	$numi = 1;
				while ($row = mysql_fetch_assoc($rs_result)) { 
			 ?>	
         	 	
         	  	<tr>
         	  		<td align="center">
         	  		<input class="inputtxt" name="currcd[]" id ="<?php $numi; ?>" type="text" style="width: 120px;" readonly="readonly" value="<?php echo $row['curr_code'];?>">
               		</td>
         	               
              		<td style="width: 69px" align="center">
              		<input class="inputtxt" name="xrate[]" id ="<?php echo "xrateid".$numi; ?>" type="text" style="width: 83px;" readonly="readonly" value="<?php echo $row['sellrate'];?>">
              		</td>
              		
              		<td style="width: 69px" align="center">
              		<input class="inputtxt" name="brate[]" id ="<?php echo "brateid".$numi; ?>" type="text" style="width: 83px;" readonly="readonly" value="<?php echo $row['buyrate'];?>">
              		</td>

             		</tr>
             <?php		
             		$i++;
             }
             ?>
           </table>
			
		  <table>
		  	  <tr>
		   <td style="width: 111px; height: 22px;">&nbsp;</td>
           <td style="height: 22px">&nbsp;</td>
           <td style="width: 250px; height: 22px;">
			&nbsp;</td>
		   <td style="width: 122px">&nbsp;</td>
           <td>&nbsp;</td>
           <td style="width: 264px">
	  	    &nbsp;</td>

		      </tr>
			  <tr>
		   <td style="width: 111px; height: 22px;">Create By</td>
           <td style="height: 22px">:</td>
           <td style="width: 250px; height: 22px;">
			<input class="textnoentry1" name="create_by0" id ="create_byid0" type="text" readonly="readonly" style="width: 152px" value="<?php echo $create_by;?>"></td>
		   <td style="width: 122px">Modified By</td>
           <td>:</td>
           <td style="width: 264px">
	  	    <input class="textnoentry1" name="modified_by1" id ="modified_byid1" type="text" readonly="readonly" style="width: 152px" value="<?php echo $modified_by;?>"></td>

		      </tr>
			  <tr>
		   <td style="width: 111px; height: 24px;">Create On</td>
           <td style="height: 24px">:</td>
           <td style="width: 250px; height: 24px;">
		   <input class="textnoentry1" name="create_on" id ="create_onid" type="text" readonly="readonly" style="width: 152px" value="<?php echo $create_on;?>"></td>
		   <td style="width: 122px">Modified On</td>
           <td>:</td>
           <td style="width: 264px">
	  	     <input class="textnoentry1" name="modified_on0" id ="suppstateid1" type="text" readonly="readonly" style="width: 152px" value="<?php echo $modified_on; ?>"></td>

		      </tr>

	  	 </table>
			
			<br>
	  	  	&nbsp;<table style="width: 314px">
	  	  		<tr>
	  	   			<td align="center" style="width: 318px">
	  	   		
	  	   			<?php
	  	   				$locatr = "m_xrate_mas.php?menucd=".$var_menucode;
				 		echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
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
