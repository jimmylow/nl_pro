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
    }else{
      $var_rm_cd = $_GET['procd'];
      //$var_rm_cd = str_replace("\\","\\\\",stripslashes(str_replace("'","''",$_GET['procd'])));
      $var_menucode = $_GET['menucd'];
    }
    
    if ($_POST['Submit'] == "Back") {
         $backloc = "../prod/m_fg.php?menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }
    
    if ($_POST['Submit'] == "Print") {
    	$rcd = $_POST['rm_code'];
     
		// Redirect browser
        $fname = "sub_coderpt.rptdesign&__title=myReport&rmcd=".$rcd; 
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&dbsel=".$varrpturldb;
        $dest .= urlencode(realpath($fname));

        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        
        $backloc = "../stck_mas/vm_rm_sub.php?menucd=".$var_menucode."&procd=".$rcd;
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
@import "../css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css";
@import "../css/styles.css";
@import "../css/demo_table.css";
thead th input { width: 90% }

.style2 {
	margin-right: 0px;
}
</style>

<script type="text/javascript" language="javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>

<script type="text/javascript"> 
$(document).ready(function() {
	$('#example').dataTable( {
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50,"All"]],
		"bStateSave": true,
		"bFilter": true,
		"sPaginationType": "full_numbers",
		"bAutoWidth":false
	})
	
	.columnFilter({sPlaceHolder: "head:after",

		aoColumns: [ 
					 null,	
					 { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     { type: "text" }
				   ]
		});	
} );
			
jQuery(function($) {
  
    $("tr :checkbox").live("click", function() {
        $(this).closest("tr").css("background-color", this.checked ? "#FFCC33" : "");
    });
  
});
			
</script>
</head>
  <?php include("../topbarm.php"); ?> 
<!--  <?php include("../sidebarm.php"); ?> -->
<body>
  
  <div class="contentc">


	<fieldset name="Group1" style=" width: 1143px;" class="style2">
	 <legend class="title">Finish Goods - <?php  $rcd = $_GET['procd']; echo $rcd ?></legend>
	  <table style="width: 100%">
	   <?php  
	   	$onhandbal = $_GET['qty'];
	   	$uprice = $_GET['uprice'];
	   	$amt = 0;
	   	$amt = $onhandbal * $uprice;
	   ?>
		  <tr>
			  <td style="width: 244px">Onhand Balance</td>
			  <td style="width: 11px">:</td>
			  <td>
			<input name="openingno" id="openingnoid" type="text" maxlength="10" style="width: 191px;" readonly="readonly" value="<?php echo $onhandbal; ?>" class="textnoentry1"></td>
		  </tr>
		  <tr>
			  <td style="width: 244px">Unit Price</td>
			  <td style="width: 11px">:</td>
			  <td>
			<input name="uprice" id="openingnoid0" type="text" maxlength="10" style="width: 191px;" readonly="readonly" value="<?php echo $uprice; ?>" class="textnoentry1"></td>
		  </tr>
		  <tr>
			  <td style="width: 244px">Amount</td>
			  <td style="width: 11px">:</td>
			  <td>
			<input name="amt" id="openingnoid1" type="text" maxlength="10" style="width: 191px;" readonly="readonly" value="<?php echo $amt; ?>" class="textnoentry1"></td>
		  </tr>
	 </table>
	  <form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 
         <table>
            <tr>
		     	<td style="width: 998px; height: 38px;" align="left">
             	 <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px">&nbsp;

             	</td>
             </tr>
             <tr>
		     	<td style="width: 998px; height: 38px;" align="left" class="title">
				HISTORY :</td>
             </tr>

             
	 
	 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         <thead>
           <tr>
          <th></th>
          <th style="width: 15px">Trx Type</th>
          <th style="width: 30px">Trx No</th>
          <th style="width: 30px">Ref No</th>
          <th style="width: 15px">Unit Cost</th>
          <th style="width: 15px">Trx Qty</th>
          <th style="width: 15px">Trx Date</th>
          <th style="width: 30px">Modified By</th>
          <th style="width: 30px">Modified On</th>
         </tr>

         <tr>
          <th class="tabheader" style="width: 12px">#</th>
          <th class="tabheader" style="width: 15px">Trx Type</th>
           <th class="tabheader" style="width: 30px">Trx No</th>
          <th class="tabheader" style="width: 30px">Ref No.</th>
          <th class="tabheader" style="width: 15px">Unit Cost</th>
          <th class="tabheader" style="width: 15px">Trx Qty</th>
          <th class="tabheader" style="width: 15px">Trx Date</th>
          <th class="tabheader" style="width: 30px">Modified By</th>
          <th class="tabheader" style="width: 30px">Modified On</th>
         </tr>
         </thead>
		 <tbody>
		 <?php 
		    $sql = "SELECT tran_type, rm_receive_id, refno, trxdate, totalqty, create_by, create_on, unit_cost ";
		    $sql .= " FROM fg_tran where item_code = '".mysql_real_escape_string($rcd)."'";
		    $sql .= " ORDER BY create_on";

			$rs_result = mysql_query($sql); 
	
		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) { 
			
				$sql = "select prod_desc from pro_cd_master ";
        		$sql .= " where prod_code ='".$rowq['prod_code']."'";
        		$sql_result = mysql_query($sql);
        		$row2 = mysql_fetch_array($sql_result);
			
				$rm_receive_id = htmlentities($rowq['rm_receive_id']);
				$tran_type = htmlentities($rowq['tran_type']);
				$refno = htmlentities($rowq['refno']);
				$totalqty= htmlentities($rowq['totalqty']);
				$po_number = htmlentities($rowq['po_number']);
				$unit_cost = htmlentities($rowq['unit_cost']);

				$showdte = date('Y-m-d', strtotime($rowq['create_on']));

				$trxdate = date('Y-m-d', strtotime($rowq['trxdate']));
				$urlpop = 'upd_rm_receive.php';
				$urlvie = 'vm_rm_receive.php';
				echo '<tr bgcolor='.$defaultcolor.'>';
				
            	echo '<td>'.$numi.'</td>';
           		echo '<td align="center">'.$tran_type.'</td>';
           		echo '<td align="center">'.$rm_receive_id.'</td>';
            	echo '<td align="center">'.$refno.'</td>';
            	echo '<td align="center">'.$unit_cost.'</td>';
             	echo '<td align="center">'.$totalqty.'</td>';
             	echo '<td align="center">'.$trxdate.'</td>';
            	echo '<td>'.$rowq['create_by'].'</td>';
            	echo '<td>'.$showdte.'</td>';
            	echo '</tr>';
            $numi = $numi + 1;
			}
		 ?>
		 </tbody>
		 </table>

		 </table>
		</form>
	   </fieldset>
	  </div>	
	  <div class="spacer"></div>
	
</body>

</html>
