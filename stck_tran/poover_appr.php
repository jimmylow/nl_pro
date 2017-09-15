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
    
      $var_stat = $_GET['stat'];
      $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");
    }
   
     if ($_POST['Submit'] == "Approve") {
	 
     	if(!empty($_POST['trx_no']) && is_array($_POST['trx_no'])) 
     	{
           foreach($_POST['trx_no'] as $value) {
            
             $vartoday = date("Y-m-d H:i:s");
			
			 	$sql  = "Update po_over Set stat = 'APPROVED', remark ='', appr_by = '$var_loginid', appr_on = '$vartoday' ";
             	$sql .=	" Where trx_no ='".$value."'";
                mysql_query($sql) or die("Error Update Approval Table : ".mysql_error(). ' Failed SQL is --> '. $sql);	 	 	 	 
                
                
                $sql = "DELETE FROM rawmat_tran "; 
		        $sql .= "WHERE rm_receive_id ='".$value."' AND tran_type = 'REC'";  
		 	    mysql_query($sql) or die("Error Delete Raw Mat Trans  : ".mysql_error(). ' Failed SQL is --> '. $sql);	 	 	 	 
                
                
                $sql = "SELECT * FROM rawmat_receive_tran";
             	$sql .= " Where rm_receive_id='".$value ."'"; 
	    		$sql .= " ORDER BY seqno";  
				$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){
					$refno = $rowq['refno'];
					$po_number = $rowq['po_number'];
					
					$itemcode = $rowq['item_code'];
					$unit_cost = $rowq['unit_cost'];
					$description = $rowq['description'];
					$qtyperpack = $rowq['qtyperpack'];
					$totalqty = $rowq['totalqty'];
			
					$sql = "select * from rawmat_receive";
			        $sql .= " where rm_receive_id ='".$value."'";
			        $sql_result = mysql_query($sql);
			        $row = mysql_fetch_array($sql_result);
			
			     	$create_by = $row['create_by'];
			     	$create_on = $row['create_on'];
			     	$modified_by= $row['upd_by'];
			 	 	$modified_on = $row['upd_on'];
			 	 	$grndate = $row['grndate'];
			 	 	
			 	 	
				 	 // to select supplier based on PO, then only can find out what currency used //
					
					$sql_po = "select supplier from po_master x ";
	    			$sql_po .= "where x.po_no = '$po_number' ";
	    			//echo $sql_po; 
	
	    			$sql_result_po = mysql_query($sql_po) or die("Cant Query Supplier From PO Master ".mysql_error());;
	    			$row_po = mysql_fetch_array($sql_result_po);
	    			$supplier = $row_po[0];
	    			
	    			
	    			$sql2 = "SELECT currency_code FROM rawmat_price_ctrl ";
	    			$sql2 .= "where supplier  = '$supplier' ";
	    			$sql2 .= "and  rm_code = '$matcode'";
	    			
				
	    			$sql_result2 = mysql_query($sql2) or die("Cant Query Curr From Main Code Table ".mysql_error());;
	    			$row2 = mysql_fetch_array($sql_result2);
	    			$curr       = $row2[0];
	
					#-------------Begin convert price to based currency buy rate--------------------------------------
				 	$exhmth = date("n",strtotime($prorevdte)); 
				 	$exhyr  = date("Y",strtotime($prorevdte));
				 	
				 	if ($curr == "MYR"){
				 		$brate = 1;
				 	}else{	
				 		$sql4 = "select buyrate from curr_xrate ";
	   					$sql4 .= " where xmth ='$exhmth' and xyr ='$exhyr' ";
	   					$sql4 .= " and curr_code = '$curr'";
	   					$sql_result4 = mysql_query($sql4) or die("Cant Get Data From Exchange Rate Table ".mysql_error());;
	   					$row4 = mysql_fetch_array($sql_result4);
	   					$brate = $row4[0];
	   				}	
	
					$basecst = $uprice * $brate;
				                
	                //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	                $sql3 = "INSERT INTO rawmat_tran values ";
	                $sql3.= " ('REC', '$value', '$refno','$po_number', '$grndate', '$itemcode', '$unit_cost', ";
	                $sql3.= " '$description', '$qtyperpack', '$totalqty', ";
	                $sql3.= " '$create_by', '$create_on','$create_by', '$modified_on')";
							
	                mysql_query($sql3) or die("Error Insert into RAW MAT Tran : ".mysql_error(). ' Failed SQL is --> '. $sql3);
	                
	                //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	                }
              

		   }	 
		   $backloc = "../stck_tran/poover_appr.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";   
       }      
    }
    
   if ($_POST['Submit'] == "Release") {
     	if(!empty($_POST['trx_no']) && is_array($_POST['trx_no'])) 
     	{
           foreach($_POST['trx_no'] as $value) {
             
			 $var_sql = " SELECT count(*) as cnt from procos_appr";
	      	 $var_sql .= " Where pro_code = '$value'";
	      	 $query_id = mysql_query($var_sql) or die ("Cant Check Costing Approval Product Code");
	      	 $res_id = mysql_fetch_object($query_id);
             
             $vartoday = date("Y-m-d H:i:s");
	      	 if ($res_id->cnt > 0 ){    			
			 	$sql  = "Update procos_appr Set stat = 'PENDING', modified_by = '$var_loginid', modified_on = '$vartoday' ";
             	$sql .=	" Where pro_code ='".$value."'";
                mysql_query($sql) or die(mysql_error()." 1");		 	 
		   	 }else{
		   	 	$sql  = "Insert Into procos_appr values";
             	$sql .=	" ('$value', '$var_loginid', '$vartoday', 'PENDING','')";
                mysql_query($sql) or die(mysql_error()." 1");	
		   	 }
		   }	 
		    $backloc = "../bom_tran/procost_appr.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";   
       }      
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
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.nightly.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>

<script type="text/javascript"> 
$(document).ready(function() {
	$('#example').dataTable( {
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50,"All"]],
		"bStateSave": true,
		"bFilter": true,
		"sPaginationType": "full_numbers",
		"bAutoWidth":false,
		"aoColumns": [
    					null,
    					null,
    					null,
    					{ "sType": "uk_date" },
    					null,
    					null,
    					null,
    					null
    				]

	})
	
	.columnFilter({sPlaceHolder: "head:after",

		aoColumns: [ 
					 null,	
					 { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     null
				   ]
		});	
} );
			
jQuery(function($) {
  
    $("tr :checkbox").live("click", function() {
        $(this).closest("tr").css("background-color", this.checked ? "#FFCC33" : "");
    });
  
});
	
var newwindow;
function poptastic(url)
{
	newwindow=window.open(url,'name','height=200,width=600,left=200,top=250');
	if (window.focus) {newwindow.focus()}
}
		
</script>
</head>
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>--> 
<body>
  
  <div class ="contentc">


	<fieldset name="Group1" style=" width: 1004px;" class="style2">
	 <legend class="title">RAWMAT RECEIVED APPROVAL</legend>
	  <br>
	 
        <form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table>
		 <tr>
		  
           <td style="width: 990px; height: 38px;" align="left">
           <?php
                $msgdel = "Are You Sure Approve Selected Receiving?";
    	  	    if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Approve" class="butsub" style="width: 75px; height: 32px">';
  				}else{
   					echo '<input type=submit name = "Submit" value="Approve" class="butsub" style="width: 75px; height: 32px" onclick="return confirm(\''.$msgdel.'\')">';
  				}
  				
    	      ?></td>
		 </tr>
		 </table>
		 <br>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" style="width: 99%">
         <thead>
           <tr>
          <th></th>
          <th style="width: 97px">Received No.</th>
          <th style="width: 207px">PO No.</th>
          <th>Rec. Date</th>
          <th>Status</th>
          <th></th>
          <th></th>
		  <th></th>
         </tr>

         <tr>
          <th class="tabheader" style="width: 12px">#</th>
          <th class="tabheader" style="width: 97px">Received No.</th>
          <th class="tabheader" style="width: 207px">PO No.</th>
          <th class="tabheader" style="width: 106px">Rec. Date</th>
          <th class="tabheader" style="width: 106px">Status</th>
          <th class="tabheader" style="width: 12px">Detail</th>
          <th class="tabheader" style="width: 12px">Approve</th>
		  <th class="tabheader" style="width: 12px">Reject</th>
         </tr>
         </thead>
		 <tbody>
		 <?php 
		    $sql = "SELECT po_no, trx_no, recdate, stat ";
		    $sql .= " FROM po_over ";
    		$sql .= " ORDER BY po_no ";  
			$rs_result = mysql_query($sql); 
	
		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) { 
			
				/* $sql = "select prod_desc from pro_cd_master ";
        		$sql .= " where prod_code ='".$rowq['prod_code']."'";
        		$sql_result = mysql_query($sql);
        		$row2 = mysql_fetch_array($sql_result); */
			
				$po_no = htmlentities($rowq['po_no']);
				$trx_no = htmlentities($rowq['trx_no']);
				$recdate= date('d-m-Y', strtotime($rowq['recdate']));
				$appstat = htmlentities($rowq['stat']);

				/*
				$sql1 = "select stat, modified_by, modified_on from procos_appr ";
     			$sql1 .= " where pro_code ='".$trx_no."'";
     			$sql_result1 = mysql_query($sql1);
     			$row1 = mysql_fetch_array($sql_result1);
     			$appstat  = $row1[0];
     			$applston = $row1[2];
     			$applstby = $row1[1];
     			if ($applston == ""){
     				$applston = "";
     			}else{	
     				$applston = date('d-m-Y', strtotime($applston));
				}
				*/
				
				$urlvie = 'vm_poover.php';
				$urlrejapp = 'rej_entry_poover.php?trx_no='.$trx_no.'&menucd='.$var_menucode;
				
				echo '<tr bgcolor='.$defaultcolor.'>';
            	echo '<td>'.$numi.'</td>';
           		echo '<td>'.$trx_no.'</td>';
            	echo '<td>'.$po_no.'</td>';
            	echo '<td>'.$recdate.'</td>';
            	echo '<td>'.$appstat.'</td>';
            
            	if ($var_accvie == 0){
            		echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlvie.'?trx_no='.$trx_no.'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
            	}
            	
	            if ($var_accadd == 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="trx_no[]" value="'.$trx_no.'" />'.'</td>';
	            }else{
	              echo '<td align="center"><input type="checkbox" name="trx_no[]" value="'.$trx_no.'" />'.'</td>';
    	        }
	            
    	        if ($var_accadd == 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="salorno[]" value="'.$trx_no.'" />'.'</td>';
	            }else{
	              echo '<td align="center"><a href=javascript:poptastic("'.$urlrejapp.'")><img src="../images/deleterow.png" title="Reject This Receiving"></a>';'</td>';
    	        }      
           		 echo '</tr>';
            $numi = $numi + 1;
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
