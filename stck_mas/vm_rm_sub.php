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
      $var_rm_cd = $_GET['rawmatcd'];
      //$var_rm_cd = str_replace("\\","\\\\",stripslashes(str_replace("'","''",$_GET['rawmatcd'])));
      $var_menucode = $_GET['menucd'];
    }
    
    if ($_POST['Submit'] == "Back") {
         $backloc = "../stck_mas/m_rm_subcode.php?menucd=".$var_menucode;
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
        
        $backloc = "../stck_mas/vm_rm_sub.php?menucd=".$var_menucode."&rawmatcd=".$rcd;
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
	<?php
		$var_rm_cd= str_replace( array('~'), '', $var_rm_cd);
		$sql = "select active_flag, density,  moq, mcq, lead_time, ";
        $sql .= " colour, size, main_code, modified_by, modified_on, description, location, cost_price, minqty, maxqty, ";
        $sql .= " create_by, creation_time ";
        
        
        $sql .= " from rawmat_subcode";
        $sql .= " where rm_code ='".mysql_real_escape_string($var_rm_cd)."'";
        $sql_result = mysql_query($sql);
        
        $row = mysql_fetch_array($sql_result);

        $active_flag = $row[0];
        $density = $row[1];
        $moq = $row[2];
        $mcq = $row[3];
        $lead_time = $row[4];
        $colourcd = $row[5];
        $size = $row[6];
        $main_code = $row[7];
        $description = $row[10];
        $location = $row[11];        
        $cost_price = $row[12];
        $minqty = $row[13];
        $maxqty = $row[14];
        
        $create_by = $row[15];
	    $create_on = $row[16];
	    $modified_by= $row[8];
	 	$modified_on = $row[9];

        $sql = "select loca_desc from stk_location";
        $sql .= " where loca_code ='".$location."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $loca_desc = $row[0];
echo $sql;
       
       
        $sql = "select colour_desc from colour_master";
        $sql .= " where colour_code ='".$colourcd."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $colour = $row[0];
        
        $sql = "select category, uom, currency_code, remark from rawmat_master";
        $sql .= " where rm_code ='".$main_code."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $category = $row[0];
        $uom = $row[1];
        $currency_code = $row[2];
        $remark= $row[3];

        $sql = "select cat_desc from cat_master";
        $sql .= " where cat_code ='".$category."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $category_desc = $row[0];
        
        $sql = "select sum(totalqty) from rawmat_tran ";
        $sql .= " where item_code ='".mysql_real_escape_string($var_rm_cd)."' ";
        $sql .= " and tran_type in ('REC', 'ADJ', 'RTN', 'ISS', 'OPB', 'REJ')";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);        
        if ($row[0] == "" or $row[0] == null){ 
          $row[0]  = 0.00;
        }
        $onhandbal = $row[0];
        
        
        $sql = "select sum(bookqty - sumrelqty - sendqty) from booktab02 ";
        $sql .= " where bookitm ='".mysql_real_escape_string($var_rm_cd)."' ";
        $sql .= " and compflg = 'N'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);        
        if ($row[0] == "" or $row[0] == null){ 
          $row[0]  = 0.00;
        }
        $bookqty = $row[0];
        
        $availbal = 0;
        $availbal = $onhandbal - $bookqty;


        $sql = "select sum(totalqty) from rawmat_tran ";
        $sql .= " where item_code ='".mysql_real_escape_string($var_rm_cd)."' ";
        $sql .= " and tran_type in ('REC', 'ADJ', 'RTN', 'ISS', 'OPB', 'REJ')";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);        
        if ($row[0] == "" or $row[0] == null){ 
          $row[0]  = 0.00;
        }
        $onhandbal = $row[0];
        
        $sql = "select sum(totalqty * myr_unit_cost) / sum(totalqty) from rawmat_receive_tran ";
        $sql .= " where item_code ='".mysql_real_escape_string($var_rm_cd)."' ";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);        
        if ($row[0] == "" or $row[0] == null){ 
          $row[0]  = 0.00;
        }

        $avg_cost = round($row[0], 3);
        //$avg_cost = number_format( $avg_cost,3);

        if ($avg_cost==0)
        {
        	$avg_cost = round($cost_price,3);
        }
        $avg_cost = number_format( $avg_cost,3);
 /*       
        $vartoday = date("Y-m-d H:i:s");
	 	$exhmth = date("n",strtotime($vartoday)); 
	 	$exhyr  = date("Y",strtotime($vartoday));
	 	
	 	if ($currency_code== "MYR"){
	 		$brate = 1;
	 	}else{	
	 		$sql4 = "select buyrate from curr_xrate ";
			$sql4 .= " where xmth ='$exhmth' and xyr ='$exhyr' ";
			$sql4 .= " and curr_code = '$currency_code'";
			$sql_result4 = mysql_query($sql4) or die("Cant Echange Rate Table ".mysql_error());;
			$row4 = mysql_fetch_array($sql_result4);
			$brate = $row4[0];
		}	
		$avg_cost= number_format($avg_cost* $brate,3);
 */		
 
 		$var_sql = "select photo from tblphoto";
    	$var_sql .= " where typ = 'R'";
    	$var_sql .= " and itemno ='$var_rm_cd'";
    	$que_photo = mysql_query($var_sql) or die (mysql_error());
    	$res_photo = mysql_fetch_object($que_photo);

    	$var_photo = $res_photo->photo;
		$picsour = "../stck_mas/itmpic/".$var_photo;
?>
  
  <div class="contentc">


	<fieldset name="Group1" style=" width: 1143px;" class="style2">
	 <legend class="title">RAW MAT SUBCODE</legend>
	  <br>
	 
        <form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 
		<table style="width: 1080px; height: 334px;">
	  	  <tr>
	  	    <td style="width: 2px">
	  	    </td>
	  	    <td style="width: 86px" class="tdlabel">Sub Code Number</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 19px">
			<input class="inputtxt" name="rm_code" id ="suppcdid" type="text" style="width: 161px" readonly="readonly" value="<?php echo htmlentities($var_rm_cd); ?>">
			</td>
			<td style="width: 6px">
			</td>
		    <td style="width: 56px" class="tdlabel">Status</td>
	  	    <td style="width: 7px">:</td>
	  	    <td style="width: 108px">
			<input class="inputtxt" name="active_flag" id ="suppactid" type="text" style="width: 125px" readonly="readonly" value="<?php echo $active_flag; ?>">
			</td>
	  	  </tr>
	   	   <tr>
	   	    <td style="width: 2px">
	  	    </td>
	  	    <td style="width: 86px" class="tdlabel">Colour</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 19px">
			<input class="inputtxt" name="colour" id ="suppnmid" type="text" style="width: 396px" readonly="readonly" value="<?php echo $colour; ?>">
			</td>
			<td style="width: 6px">
			</td>
		    <td style="width: 56px" class="tdlabel">Width</td>
	  	    <td style="width: 7px">:</td>
	  	    <td style="width: 108px">
			<input class="inputtxt" name="size" id ="suppcurrid" type="text" style="width: 68px" readonly="readonly" value="<?php echo $size; ?>">
			</td>
	  	  </tr>
	  	  <tr>
	   	    <td style="width: 2px">
	  	    </td>
	  	    <td style="width: 86px" class="tdlabel">Density</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 19px">
			<input class="inputtxt" name="density" id ="suppnmid1" type="text" style="width: 396px" readonly="readonly" value="<?php echo $density; ?>">
			</td>
			<td style="width: 6px">
			</td>
		    <td style="width: 56px" class="tdlabel">MOQ</td>
	  	    <td style="width: 7px">:</td>
	  	    <td style="width: 108px">
			<input class="inputtxt" name="moq" id ="suppcurrid0" type="text" style="width: 68px" readonly="readonly" value="<?php echo $moq; ?>">
			</td>
	  	  </tr>
	  	  <tr>
	   	    <td style="width: 2px">
	  	    </td>
	  	    <td style="width: 86px" class="tdlabel">Supplier Lead Time (Days)</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 19px">
			<input class="inputtxt" name="lead_time" id ="suppnmid2" type="text" style="width: 396px" readonly="readonly" value="<?php echo $lead_time; ?>">
			</td>
			<td style="width: 6px">
			</td>
		    <td style="width: 56px" class="tdlabel">MCQ</td>
	  	    <td style="width: 7px">:</td>
	  	    <td style="width: 108px">
			<input class="inputtxt" name="mcq" id ="suppcurrid1" type="text" style="width: 68px" readonly="readonly" value="<?php echo $mcq; ?>">
			</td>
	  	  </tr>
	  	    <tr>
	   	    <td style="width: 2px">
	  	    </td>
	  	    <td style="width: 86px" class="tdlabel">&nbsp;</td>
	  	    <td style="width: 4px">&nbsp;</td>
	  	    <td style="width: 19px">
			&nbsp;</td>
			<td style="width: 6px">
			</td>
		    <td style="width: 56px" class="tdlabel">Max Qty</td>
	  	    <td style="width: 7px">:</td>
	  	    <td style="width: 108px">
			<input class="inputtxt" name="maxqty" id ="suppcurrid4" type="text" style="width: 68px" readonly="readonly" value="<?php echo $maxqty; ?>">
			</td>
	  	    </tr>
	  	    <tr>
	   	    <td style="width: 2px">
	  	    </td>
	  	    <td style="width: 86px" class="tdlabel">&nbsp;</td>
	  	    <td style="width: 4px">&nbsp;</td>
	  	    <td style="width: 19px">
			&nbsp;</td>
			<td style="width: 6px">
			</td>
		    <td style="width: 56px" class="tdlabel">Min Qty</td>
	  	    <td style="width: 7px">:</td>
	  	    <td style="width: 108px">
			<input class="inputtxt" name="minqty" id ="suppnmid4" type="text" style="width: 68px" readonly="readonly" value="<?php echo $minqty; ?>"></td>
	  	    </tr>
	  	  <tr>
	   	    <td style="width: 2px">
	  	    </td>
	  	    <td style="width: 86px" class="tdlabel">Category</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 19px">
			<input class="inputtxt" name="category" id ="suppnmid3" type="text" style="width: 34px" readonly="readonly" value="<?php echo $category; ?>">
			<input class="inputtxt" name="category_desc" id ="suppnmid0" type="text" style="width: 351px" readonly="readonly" value="<?php echo $category_desc; ?>"></td>
			<td style="width: 6px">
			</td>
		    <td style="width: 56px" class="tdlabel">Currency Code</td>
	  	    <td style="width: 7px">:</td>
	  	    <td style="width: 108px">
			<input class="inputtxt" name="currency_code" id ="suppcurrid2" type="text" style="width: 68px" readonly="readonly" value="<?php echo $currency_code; ?>">
			</td>
	  	  </tr>

		  <tr>
	  	    <td style="width: 2px">
	  	    </td>
	  	    <td style="width: 86px" class="tdlabel">Unit of Measurement</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 19px">
			<input class="inputtxt" name="uom" id ="suppcityid" type="text" style="width: 282px" readonly="readonly" value="<?php echo $uom; ?>">
			</td>
			<td style="width: 6px">
			</td>
			<td style="width: 56px">
			Location</td>
			<td>
			:</td>
			<td>
			<input class="inputtxt" name="location" id ="suppcurrid3" type="text" style="width: 34px" readonly="readonly" value="<?php echo $location; ?>"><input class="inputtxt" name="loca_desc" id ="suppcurrid5" type="text" style="width: 125px" readonly="readonly" value="<?php echo $loca_desc; ?>"></td>	
		  </tr>
		  <tr>
	  	    <td style="width: 2px">
	  	    </td>
	  	    <td style="width: 86px" class="tdlabel">Description</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 19px">
			<input class="inputtxt" name="description" id ="suppwebid1" type="text" style="width: 345px" readonly="readonly" value="<?php echo $description; ?>"></td>
			<td style="width: 6px">
			</td>
			<td style="width: 56px">
			Cost Price (MYR)</td>
			<td>
			:</td>
			<td>
			<input class="inputtxt" name="cost_price" id ="suppcurrid3" type="text" style="width: 125px" readonly="readonly" value="<?php echo $cost_price; ?>"></td>	
		  </tr>
		    <tr>
	  	    <td style="width: 2px">
	  	    </td>
	  	    <td style="width: 86px" class="tdlabel">Remark</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 19px">
			<input class="inputtxt" name="remark" id ="suppwebid" type="text" style="width: 345px" readonly="readonly" value="<?php echo $remark; ?>"></td>
			<td style="width: 6px">
			</td>
			<td style="width: 56px">
			Avg Cost (MYR)</td>
			<td>
			:</td>
			<td>
			<input class="inputtxt" name="avg_cost" id ="suppcurrid3" type="text" style="width: 125px" readonly="readonly" value="<?php echo $avg_cost; ?>"></td>		  
		    </tr>
		  <tr>
	  	    <td style="width: 2px">
	  	    </td>
	  	    <td style="width: 86px" class="tdlabel">Onhand Balance</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 19px">
			<input class="inputtxt" name="onhandbal" id ="suppwebid0" type="text" style="width: 345px" readonly="readonly" value="<?php echo $onhandbal; ?>">
			</td>
			<td style="width: 6px">
			</td>
		  </tr>
		    <tr>
	  	    <td style="width: 2px">
	  	    </td>
	  	    <td style="width: 86px" class="tdlabel">Booked Qty</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 19px">
			<input class="inputtxt" name="bookqty" id ="suppwebid2" type="text" style="width: 345px" readonly="readonly" value="<?php echo $bookqty; ?>">
			</td>
			<td style="width: 6px"></td>
			<td  colspan="3" rowspan="5">
				<img src = "<?php echo $picsour; ?>" border="0" id="proimgpre" width="346px" height="210px">
			</td>
		    </tr>
			<tr>
	  	    <td style="width: 2px">
	  	    </td>
	  	    <td style="width: 86px" class="tdlabel">Available Balance</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 19px">
			<input class="inputtxt" name="availbal" id ="suppwebid3" type="text" style="width: 345px" readonly="readonly" value="<?php echo $availbal; ?>">
			</td>
			<td style="width: 6px">
			</td>
		    </tr>
		  <tr>
	  	    	<td style="height: 22px"></td>
		   <td style="width: 86px; height: 22px;">Create By</td>
           <td style="height: 22px">:</td>
           <td style="width: 19px; height: 22px;">
			<input class="textnoentry1" name="create_by" id ="create_byid" type="text" readonly="readonly" style="width: 152px" value="<?php echo $create_by;?>"></td>
	  	    </tr>
			<tr>
	  	    	<td style="height: 24px"></td>
		   <td style="width: 86px; height: 24px;">Create On</td>
           <td style="height: 24px">:</td>
           <td style="width: 19px; height: 24px;">
		   <input class="textnoentry1" name="create_on" id ="create_onid" type="text" readonly="readonly" style="width: 152px" value="<?php echo $create_on;?>"></td>
	  	    </tr>
			<tr>
	  	    	<td>&nbsp;</td>
		   <td style="width: 86px">Modified By</td>
           <td>:</td>
           <td style="width: 19px">
	  	    <input class="textnoentry1" name="modified_by" id ="modified_byid" type="text" readonly="readonly" style="width: 152px" value="<?php echo $modified_by;?>"></td>
	  	    </tr>
			<tr>
	  	    	<td>&nbsp;</td>
		   <td style="width: 86px">Modified On</td>
           <td>:</td>
           <td style="width: 19px">
	  	     <input class="textnoentry1" name="modified_on" id ="suppstateid0" type="text" readonly="readonly" style="width: 152px" value="<?php echo $modified_on; ?>"></td>
	  	    </tr>


		  </table>
         <table>
            <tr>
		     	<td style="width: 998px; height: 38px;" align="center">
             	 <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px">
             	 <input type=submit name = "Submit" value="Print" class="butsub" style="width: 60px; height: 32px">

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
		    $sql = "SELECT tran_type, rm_receive_id, refno, grndate, totalqty, create_by, create_on, unit_cost ";
		    $sql .= " FROM rawmat_tran where item_code = '".mysql_real_escape_string($var_rm_cd)."'";
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
				$trxdate = date('Y-m-d', strtotime($rowq['grndate']));
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
