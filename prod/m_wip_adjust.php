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
                        
		     $sql = "DELETE FROM wip_adjust "; 
		     $sql .= "WHERE wip_adjust_id ='".$var_proccd."'";  
		 	 mysql_query($sql); 
		 	 
		 	 $sql = "DELETE FROM wip_adjust_tran "; 
		     $sql .= "WHERE wip_adjust_id ='".$var_proccd."'";  
		 	 mysql_query($sql); 
		 	 
		 	 $sql = "DELETE FROM wip_tran "; 
		     $sql .= "WHERE rm_receive_id  ='".$var_proccd."' and tran_type = 'ADJ'";  
		 	 mysql_query($sql); 
		 	 echo $sql;
		 	 break;
		 

 

		   }
		   $backloc = "../prod/m_wip_adjust.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";   
       }      
    }
	
	if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
     
     	 $backloc = "../stck_tran/inq_wip_open.php?menucd=".$var_menucode;
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
.auto-style1 {
	text-align: right;
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

  <div class="auto-style1">


	<fieldset name="Group1" style=" width: 800px;" class="style2">
	 <legend class="title">WIP ADJUSTMENT LISTING xxx--TESTING---xxx</legend>
	  <br>
	 
        <form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table>
		 <tr>
		  
           <td style="width: 1131px; height: 38px;" align="left">
           <?php
                $locatr = "wip_adjust.php?menucd=".$var_menucode;
                if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Create" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Create" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}
				include("../Setting/btnprint.php");
				
				//$locatr = "upd_rawopen_cost.php?menucd=".$var_menucode;
				//if ($var_accupd == 0){
				//	echo '<input type="button" value="Cost" class="butsub" style="width: 60px; height: 32px" disabled="disabled">';
				//}else{	
				//	echo '<input type="button" value="Cost" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				//}
				
    	  	   $msgdel = "Are You Sure Delete WIP adjust List?";
    	  	   include("../Setting/btndelete.php");
    	      ?>
    	    </td>
		 </tr>
		 </table>
		 <br>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         <thead>
           <tr>
          <th></th>
          <th>Adjust No.</th>
          <th style="width: 86px">Ref No.</th>
          <th>Adjust Date</th>
          
         </tr>

         <tr>
          <th class="tabheader" style="width: 12px">#</th>
          <th class="tabheader" style="width: 139px">Adjust No.</th>
           <th class="tabheader" style="width: 86px">Ref. No.</th>
          <th class="tabheader" style="width: 106px">Adjust Date</th>
          <th class="tabheader" style="width: 12px">Detail</th>
          <th class="tabheader" style="width: 12px">Update</th>
		  <th class="tabheader" style="width: 12px">Delete</th>
         </tr>
         </thead>
		 <tbody>
		 <?php 
		    $sql = "SELECT distinct wip_adjust_id, refno, adjustdate, upd_by, upd_on ";
		    $sql .= " FROM wip_adjust ";
    		$sql .= " ORDER BY wip_adjust_id";   
			$rs_result = mysql_query($sql); 
			//echo $sql;

		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) { 
			
				$sql = "select prod_desc from pro_cd_master ";
        		$sql .= " where prod_code ='".$rowq['prod_code']."'";
        		$sql_result = mysql_query($sql);
        		$row2 = mysql_fetch_array($sql_result);
			
				$refno = htmlentities($rowq['refno']);
				$wip_adjust_id = htmlentities($rowq['wip_adjust_id']);
				//$label_number = htmlentities($rowq['label_number']);

				$showdte = date('d-m-Y', strtotime($rowq['upd_on']));
				$adjustdate = date('d-m-Y', strtotime($rowq['adjustdate']));
				$urlpop = 'upd_wip_adjust.php';
				$urlvie = 'vm_wip_adjust.php';
				echo '<tr bgcolor='.$defaultcolor.'>';
				
            	echo '<td>'.$numi.'</td>';
           		echo '<td>'.$wip_adjust_id.'</td>';
           		echo '<td>'.$refno.'</td>';
            	//echo '<td align="center">'.$label_number.'</td>';
            	//echo '<td>'.$adjustdate.'</td>';
            	//echo '<td>'.$rowq['upd_by'].'</td>';
            	echo '<td>'.$showdte.'</td>';
            
            	if ($var_accvie == 0){
            		echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlvie.'?wip_adjust_id='.$wip_adjust_id.'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
            	}
	            if ($var_accupd == 0){
		            echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
	            }else{
		            echo '<td align="center"><a href="'.$urlpop.'?wip_adjust_id='.$wip_adjust_id.'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
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
