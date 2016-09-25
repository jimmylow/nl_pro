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
   
 
	 if ($_POST['Submit'] == "Delete") {
     	if(!empty($_POST['procd']) && is_array($_POST['procd'])) 
     	{
           foreach($_POST['procd'] as $key) {
             $defarr = explode(",", $key);
             
             $var_proccd = $defarr[0];
             $var_prorev = $defarr[1];
                        
		     $sql = "DELETE FROM rawmat_issue "; 
		     $sql .= "WHERE rm_issue_id ='".$var_proccd."'";  
		 	 mysql_query($sql); 
		 	 
		 	// $sql = "DELETE FROM prod_matlis "; 
		    // $sql .= "WHERE rm_issue_id ='".$var_proccd."'";  
		 	// mysql_query($sql); 

		   }
		   $backloc = "../stock_tran/m_rm_issue.php?stat=1&menucd=".$var_menucode;
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
				     null,
				     null
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
  <!--<?php include("../sidebarm.php"); ?> -->
<body>
    
  <div class="contentc">


	<fieldset name="Group1" style=" width: 1143px;" class="style2">
	 <legend class="title">RAW MAT ISSUE LISTING</legend>
	  <br>
	 
        <form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table>
		 <tr>
		  
           <td style="width: 1131px; height: 38px;" align="left">
           <?php
                $locatr = "rawmat_issue.php?menucd=".$var_menucode;
                if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Create" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Create" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}
  				#$locatr = "copy_pro_cost.php?menucd=".$var_menucode;
  				#if ($var_accadd == 0){
   				#	echo '<input disabled="disabled" type=button name = "Submit" value="Copy" class="butsub" style="width: 60px; height: 32px">';
  				#}else{
   				#	echo '<input type="button" value="Copy" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				#}
    	  	   $msgdel = "Are You Sure Delete Selected Product Code From Material & Costing List?";
    	  	   include("../Setting/btndelete.php");
    	      ?></td>
		 </tr>
		 </table>
		 <br>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         <thead>
           <tr>
          <th></th>
          <th>Issue No.</th>
          <th>Ref No.</th>
          <th>Label ID</th>
          <th>Item Code</th>
          <th>Issue Date</th>
          <th>Modified By</th>
          <th>Modified On</th>
          <th></th>
          <th></th>
		  <th></th>
         </tr>

         <tr>
          <th class="tabheader" style="width: 12px">#</th>
          <th class="tabheader" style="width: 139px">Issue No.</th>
           <th class="tabheader" style="width: 139px">Ref. No.</th>
          <th class="tabheader" style="width: 283px">Label ID</th>
          <th class="tabheader" style="width: 89px">Item Code</th>
          <th class="tabheader" style="width: 106px">Issue Date</th>
          <th class="tabheader" style="width: 118px">Modified By</th>
          <th class="tabheader" style="width: 131px">Modified On</th>
          <th class="tabheader" style="width: 12px">Detail</th>
          <th class="tabheader" style="width: 12px">Update</th>
		  <th class="tabheader" style="width: 12px">Delete</th>
         </tr>
         </thead>
		 <tbody>
		 <?php 
		    $sql = "SELECT rm_issue_id, refno, po_number, grndate, item_code, description, upd_by, upd_on ";
		    $sql .= " FROM rawmat_issue ";
    		$sql .= " ORDER BY rm_issue_id";   
			$rs_result = mysql_query($sql); 

		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) { 
			
				$sql = "select prod_desc from pro_cd_master ";
        		$sql .= " where prod_code ='".$rowq['prod_code']."'";
        		$sql_result = mysql_query($sql);
        		$row2 = mysql_fetch_array($sql_result);
			
				$refno = htmlentities($rowq['refno']);
				$rm_issue_id = htmlentities($rowq['rm_issue_id']);
				$po_number = htmlentities($rowq['po_number']);
				$item_code = htmlentities($rowq['item_code']);

				$showdte = date('Y-m-d', strtotime($rowq['upd_on']));
				$grndate = date('Y-m-d', strtotime($rowq['grndate']));
				$urlpop = 'upd_rm_issue.php';
				$urlvie = 'vm_rm_issue.php';
				echo '<tr bgcolor='.$defaultcolor.'>';
				
            	echo '<td>'.$numi.'</td>';
           		echo '<td align="center">'.$rm_issue_id.'</td>';
           		echo '<td align="center">'.$refno.'</td>';
            	echo '<td align="center">'.$po_number.'</td>';
            	echo '<td align="center">'.$item_code.'</td>';
            	echo '<td align="center">'.$grndate.'</td>';
            	echo '<td>'.$rowq['upd_by'].'</td>';
            	echo '<td>'.$showdte.'</td>';
            
            	if ($var_accvie == 0){
            		echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlvie.'?rm_issue_id='.$rm_issue_id.'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
            	}
	            if ($var_accupd == 0){
		            echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
	            }else{
		            echo '<td align="center"><a href="'.$urlpop.'?rm_issue_id='.$rm_issue_id.'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
	            }
	            if ($var_accdel == 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
	            }else{
	              $values = implode(',', $rowq);	
	              echo '<td align="center"><input type="checkbox" name="procd[]" value="'.$values.'" />'.'</td>';
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
