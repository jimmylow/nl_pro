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
      $var_rm_cd = $_GET['rawmatcd'];
      $var_menucode = $_GET['menucd'];
      $var_supplier = $_GET['suppliercd'];
      $price_id = $_GET['price_id'];
      
    }
    
    if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menudcode'];
         $backloc = "../stck_mas/m_rawmat_price_ctrl.php?menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
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
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>

<script type="text/javascript" charset="utf-8"> 
function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

</script>
</head>
  <?php include("../topbarm.php"); ?> 
 	<!--<?php include("../sidebarm.php"); ?> -->
<body>
  
	<div class="contentc">

	<fieldset name="Group1" style=" width: 1011px; height: 633px;" class="style2">
	 <legend class="title">RAW MATERIAL PRICE : <?php echo $var_rm_cd; ?></legend>
	 <?php
	    $sql = "select main_code, rm_code, supplier, effective_date, ";
        $sql .= " currency_code, uom, shipping_term, ";
        $sql .= " create_by, creation_time, modify_by, modified_on ";
        $sql .= " from rawmat_price_ctrl";
        $sql .= " where price_id='".$price_id."'";

        
        $sql_result = mysql_query($sql);
        //echo $sql;
        $row = mysql_fetch_array($sql_result);

        $main_code = htmlentities($row[0]);
        $rm_code = htmlentities($row[1]);
        $supplier = $row[2];
        $effective_date  = date('d-m-Y', strtotime($row[3]));
        $currency_code  = $row[4];
        $uom= $row[5];
        $shipping_term = $row[6];
        $create_by = $row[7];
        $creation_time = date('d-m-Y', strtotime($row[8]));
        $modify_by = $row[9];
        $modified_on = date('d-m-Y', strtotime($row[10]));

    ?>		
	  <br>
	  <form name="InpColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px">
	  <fieldset name="Group1" style="width: 993px; height: 268px">
	    <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table style="width: 980px; height: 256px;">
	  	  <tr>
	  	    <td style="width: 2px">
	  	    </td>
	  	    <td style="width: 114px" class="tdlabel">Master Code</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 375px">
			<input class="inputtxt" name="main_code" id ="suppcdid" type="text" style="width: 161px" readonly="readonly" value="<?php echo $main_code; ?>"/>
			</td>
			<td style="width: 120px">
			</td>
		    <td style="width: 96px" class="tdlabel">&nbsp;</td>
	  	    <td style="width: 7px">&nbsp;</td>
	  	    <td style="width: 108px">
			&nbsp;</td>
	  	  </tr>
	   	   <tr>
	   	    <td style="width: 2px">
	  	    </td>
	  	    <td style="width: 114px" class="tdlabel">Sub Code No</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 375px">
			<input class="inputtxt" name="rm_code" id ="suppnmid" type="text" style="width: 396px" readonly="readonly" value="<?php echo $rm_code; ?>">
			</td>
			<td style="width: 120px">
			</td>
		    <td style="width: 96px" class="tdlabel">Currency Code</td>
	  	    <td style="width: 7px">:</td>
	  	    <td style="width: 108px">
			<input class="inputtxt" name="currency_code" id ="suppcurrid" type="text" style="width: 68px" readonly="readonly" value="<?php echo $currency_code; ?>"/>
			</td>
	  	  </tr>
		  <tr>
	  	    <td style="width: 2px">
	  	    </td>
	  	    <td style="width: 114px" class="tdlabel">Supplier </td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 375px">
			<input class="inputtxt" name="supplier" id ="suppcityid" type="text" style="width: 282px" readonly="readonly" value="<?php echo $supplier; ?>">
			</td>
			<td style="width: 120px">
			</td>
			<td>
			UOM</td>
			<td>
			:</td>
			<td>
            <input class="inputtxt" name="uom0" id ="suppstateid0" type="text" style="width: 151px" readonly="readonly" value="<?php echo $uom; ?>"></td>
		  </tr>
		  <tr>
	  	    <td style="width: 2px">
	  	    </td>
	  	    <td style="width: 114px" class="tdlabel">Effective Date</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 375px">
			<input class="inputtxt" name="effective_date" id ="supppostcdid" type="text" style="width: 390px" readonly="readonly" value="<?php echo $effective_date; ?>"/>
			</td>
			<td style="width: 120px">
			</td>
			<td style="width: 96px">Shipping Term</td>
            <td style="width: 7px">:</td>
            <td style="width: 108px">
            <input class="inputtxt" name="shipping_term" id ="suppstateid" type="text" style="width: 151px" readonly="readonly" value="<?php echo $shipping_term; ?>"/></td>
		  </tr>
		  <tr>
	  	    <td style="width: 2px">
	  	    </td>
	  	    <td style="width: 114px" class="tdlabel">Create By</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 375px">
			<input class="inputtxt" name="create_by" id ="supppostcdid" type="text" style="width: 150px" readonly="readonly" value="<?php echo $create_by; ?>"/>
			</td>
			<td style="width: 120px">
			</td>
			<td style="width: 96px">Creation Time</td>
            <td style="width: 7px">:</td>
            <td style="width: 108px">
            <input class="inputtxt" name="creation_time" id ="suppstateid1" type="text" style="width: 151px" readonly="readonly" value="<?php echo $creation_time; ?>"/></td>
		  </tr>
		  <tr>
	  	    <td style="width: 2px">
	  	    </td>
	  	    <td style="width: 114px" class="tdlabel">Update By</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 375px">
			<input class="inputtxt" name="modify_by" id ="supppostcdid" type="text" style="width: 150px" readonly="readonly" value="<?php echo $modify_by; ?>"/>
			</td>
			<td style="width: 120px">
			</td>
			<td style="width: 96px">Update On</td>
            <td style="width: 7px">:</td>
            <td style="width: 108px">
            <input class="inputtxt" name="modified_on" id ="suppstateid2" type="text" style="width: 151px" readonly="readonly" value="<?php echo $modified_on; ?>"/></td>
		  </tr>


		  </table>
	   </fieldset>
	   
	   <?php
		    $sql = "SELECT from_qty, to_qty, price  ";
			$sql .= " FROM rawmat_price_trans where price_id= '".$price_id."'";
			//$sql .= " AND supplier = '".$var_supplier."'";  
   		    $sql .= " ORDER BY 1,2";  
   		    //echo $sql ;
		    $rs_result = mysql_query($sql); 
		    
		 ?>
        <table>
		 <tr>
           <td style="width: 982px; height: 38px;" align="center">
            <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px">
		   </td>
		 </tr>
		 </table>
		 
		 <table cellpadding="0" cellspacing="0" class="display" border id="example" style="width: 993px">
         <thead><tr>
          <th class="tabheader" style="width: 27px">#</th>
          <th class="tabheader" style="width: 75px">From Qty</th>
          <th class="tabheader" style="width: 75px">To Qty</th>
          <th class="tabheader" style="width: 75px">Price</th>
         </tr></thead>
		 <tbody>
		 <?php 
		   			
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) { 
			//echo $row['rm_code'];
			$urlpop = 'upd_rm_mas.php';
			$urlvm = 'vm_rm_mas.php';
			echo '<tr>';
            echo '<td>'.$numi.'</td>';
            echo '<td>'.$row['from_qty'].'</td>';
            echo '<td>'.$row['to_qty'].'</td>';
            echo '<td>'.$row['price'].'</td>';
            echo '</tr>';
            $numi = $numi + 1;
			}
		 ?>
		 </tbody>
		 </table>
         </form> 
	 </fieldset>
	 </div>
	 <div class="spacer" style="height: 1px"></div>
</body>
</html>
