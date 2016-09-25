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
      $cutno        = $_GET['c'];
      include("../Setting/ChqAuth.php");
    }
    
    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
        $cno = $_POST['cutsheno'];
        
        $fname = "cutting_sheet.rptdesign&__title=myReport";
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&cu=".$cno."&dbsel=".$varrpturldb."&usernm=".$var_loginid;
        $dest .= urlencode(realpath($fname));
        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        $backloc = "../prod/vm_cutsheet.php?c=".$cno."&menucd=".$var_menucode;
       	echo "<script>";
       	echo 'location.replace("'.$backloc.'")';
        echo "</script>"; 

     }
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

</style>

<!-- jQuery libs -->
<script type="text/javascript" src="../js/datetimepicker_css.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>
 
</head>
<?php
		$sql = "select * from prodcutmas";
        $sql .= " where cutno ='$cutno'";        
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);

        $sordno = $row['byrefno'];
        $cutdte = date('d-m-Y', strtotime($row['cutdte']));
        $ordno  = $row['ordno'];
        $buyno  = $row['buyno'];
        $orddte = date('d-m-Y', strtotime($row['orddte']));
        $deldte = date('d-m-Y', strtotime($row['deldte']));
        $grpno  = $row['grpno'];
        $prodcat= $row['prodcat'];
        $prodcnum= $row['prodcnum'];
        $prodno = $prodcat.$prodcnum;
        $colno  = $row['colno'];
        
        $sql = "select pro_buy_desc from pro_buy_master ";
        $sql .= " where pro_buy_code ='".$buyno."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $sbuyde = $row[0];
        
        $sql = "select grpde from wor_grpmas ";
        $sql .= " where grpcd ='$grpno'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $sgrpde = $row[0];
        
        $sql = "select clr_desc from pro_clr_master ";
        $sql .= " where clr_code ='$colno'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $colde = $row[0];

?>
<body>
  <?php include("../topbarm.php"); ?> 
 <!-- <?php include("../sidebarm.php"); ?> -->

  <div class="contentc">
	<form name="InpVmCut" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
	<fieldset name="Group1" style=" width: 873px;" class="style2">
	 <legend class="title">VIEW CUTTING SHEET -&nbsp; <?php echo $cutno;?></legend>
	 <table style="width: 851px">
	 <tr>
	  	 <td style="width: 5px"></td>
	  	 <td style="width: 107px;" class="tdlabel">Cutting Sheet No</td>
	  	 <td style="width: 4px;">:</td>
	  	 <td style="width: 16px;">
			<input class="inputtxt" name="cutsheno" id="cutsheno" type="text" style="width: 196px;" readonly="readonly" value="<?php echo $cutno; ?>"></td>
		 <td style="width: 9px"></td>
		 <td style="width: 50px;" class="tdlabel">Cut&nbsp; Date</td>
	  	 <td style="width: 5px;">:</td>
	  	 <td style="width: 69px;">
		  <input class="inputtxt" name="cutdte" id ="cutdte" readonly="readonly" type="text" style="width: 128px;" value="<?php  echo $cutdte; ?>">
		 </td>
	 </tr>
	 <tr><td></td></tr>
	 <tr>
	  	 <td style="width: 5px"></td>
	  	 <td style="width: 107px;">Order No</td>
	  	 <td style="width: 4px;">:</td>
	  	 <td style="width: 16px;">
			<input class="inputtxt" name="ordno" id ="ordno" readonly="readonly" type="text" style="width: 128px;" value="<?php  echo $ordno; ?>">
		 </td>
		 <td style="width: 9px"></td>
		 <td style="width: 50px;">Buyer</td>
	  	 <td style="width: 5px;">:</td>
	  	 <td style="width: 69px;">
			<input class="inputtxt" name="buyerno" id="buyerno" type="text" style="width: 125px;" value="<?php echo $buyno;?>" readonly="readonly">
			<label><?php echo $sbuyde; ?></label>
		</td>
	 </tr>
	 <tr><td></td></tr>
	 <tr>
	  	<td style="height: 26px"></td>
	   	<td style="height: 26px">Order Date</td>
	   	<td style="height: 26px">:</td>
	   	<td style="height: 26px">
	  		<input class="inputtxt" name="salorddte" id ="salorddte" type="text" style="width: 128px;" value="<?php echo  $orddte; ?>"></td>
	 </tr>
	 <tr><td></td></tr>
		 <tr>
		  	  <td></td>
		  	  <td>Delivery Date</td>
		  	  <td>:</td>
		  	  <td>
		   		<input class="inputtxt" name="saldelidte" id ="saldelidte" type="text" style="width: 128px;" value="<?php echo $deldte; ?>" readonly="readonly">
		   	  </td>
		   	  <td></td>
		   	  <td>Group</td>
		   	  <td>:</td>
		   	  <td>
		   	    <input class="inputtxt" name="cutgrpno" id ="cutgrpno" type="text" style="width: 126px;" value="<?php echo $grpno; ?>">
		   	    <label><?php echo $sgrpde; ?></label>
			  </td>
		  </tr>
	 <tr><td></td></tr>
	 <tr>
	  	<td></td>
	  	<td>Product Code</td>
	  	<td>:</td>
	  	<td>
	  		<input class="inputtxt" name="prodno" id ="prodno" type="text" style="width: 128px;" value="<?php echo $prodno; ?>" readonly="readonly">
	  	</td>
		<td></td>
		<td>Color</td>
		<td>:</td>
		<td><input class="inputtxt" name="colno" id ="colno" type="text" style="width: 80px;" value="<?php echo $colno; ?>" readonly="readonly">
		<label><?php echo $colde; ?></label>
		</td>
		</tr>
	 <tr><td></td></tr>
	 </table>
	 
	 <table id="itemsTable" class="general-table" align="center">
         <thead>
        	 <tr>
              <th class="tabheader" style="width: 27px;">#</th>
              <th class="tabheader">Size</th>
              <th class="tabheader">Order Quantity</th>
              <th class="tabheader">UOM</th>
             </tr>
            </thead>
            <tbody>
			<?php 
            	$query  = "SELECT distinct sizeno, sum(ordqty), prodnouom  FROM prodcutmas ";
  				$query .= " WHERE cutno = '$cutno'";
  				$query .= " group by 1, 3";
  				$query .= " order by 1";	
				$rs_result = mysql_query($query) or die(mysql_error()); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){
            		$prsize = htmlentities($rowq['sizeno']);
            		$ordqty = $rowq['sum(ordqty)'];
					$pruom  = $rowq['prodnouom '];
            		
			  		echo '<tr class="item-row">';	
			        echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 30px; border:0;" readonly="readonly"></td>'; 
			        echo '<td><input name="prsize[]" value="'.$prsize.'" id="prsize'.$i.'" style="width: 100px; border-style: none;"  readonly="readonly"></td>';
          	  		echo '<td><input name="ordqty[]" value="'.$ordqty.'" id="ordqty'.$i.'" style="width: 100px; text-align: right" readonly="readonly"></td>';
          	        echo '<td><input name="pruom[]" value="'.$pruom.'" id="pruom'.$i.'" readonly="readonly" style="border-style: none; width: 50px;"></td>';
          	        echo '</tr>';
          	    	$i = $i + 1;
          	    }
          	?>             	
          	</tbody>
     </table>
     <br>  
     <table>
	 <tr>
		<td style="height: 22px; width: 853px;" align="center">
		<?php
			$locatr = "cut_sheet.php?menucd=".$var_menucode;
			echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
			$locatr = "cut_sheetvmdet.php?menucd=".$var_menucode."&c=".$cutno;
			echo '<input type="button" value="Detail" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
 
			include("../Setting/btnprint.php");
		?>
		</td>
	</tr>
     </table>	
	</fieldset>
	</form>
	</div>
	<div class="spacer"></div>
</body>

</html>
