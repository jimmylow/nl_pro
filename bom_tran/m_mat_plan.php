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
     	if(!empty($_POST['costn']) && is_array($_POST['costn'])) 
     	{
           foreach($_POST['costn'] as $key) {
             $defarr = explode(",", $key);
             $var_costn = $defarr[0];
                                    
		     $sql = "DELETE FROM costing_mat "; 
		     $sql .= "WHERE costingno = '".$var_costn."' ";  
		 	 mysql_query($sql); 
		 	 
		 	 $sql = "DELETE FROM costing_matord "; 
		     $sql .= "WHERE costingno = '".$var_costn."' ";  
		 	 mysql_query($sql); 
		 	 
	 	     $sql = "DELETE FROM costing_matdet "; 
		     $sql .= "WHERE costingno = '".$var_costn."' ";  
		 	 mysql_query($sql); 
		 	 
		 	 $sql = "DELETE FROM costing_purbook "; 
		     $sql .= "WHERE costing_no = '".$var_costn."' ";  
		 	 mysql_query($sql); 

		 	 
		 	 //$sql  = "Select bookno from booktab01 ";
		 	 //$sql .= " Where byrefno = '".$var_costn."'";
        	 //$sql_result = mysql_query($sql);
        	 //$row = mysql_fetch_array($sql_result);
        	 //$bookno = $row[0];
        	 
        	 //if ($bookno != ""){
        	 //		$sql1  = "Update booktab02 set sumrelqty = (bookqty - sendqty), ";
        	 //		$sql1 .= "                      compflg = 'Y'";
        	 //		$sql1 .= " Where bookno = '$bookno'";
        	 //		mysql_query($sql1) or die("Cannot Release Booking Quantity".mysql_error());
        	 		
        	 //		$vartoday = date("Y-m-d");
        	 //		$sql1  = "Update booktab01 set modified_by = '$var_loginid', ";
        	 //		$sql1 .= "                     modified_on = '$vartoday'";
        	 //		$sql1 .= " Where bookno = '$bookno'";
        	 //		mysql_query($sql1) or die("Cannot Update Book Table 1".mysql_error());
			// } 	
		   }
		   $backloc = "../bom_tran/m_mat_plan.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";   
       }      
    }
    
    /*
     if ($_GET['p'] == "Print"){
        $var_costinno = $_GET['costinno'];
        
        $fname = "planrpt.rptdesign&__title=myReport"; 
       	$dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&costn=".$var_costinno."&usernm=".$var_loginid."&dbsel=".$varrpturldb;
        $dest .= urlencode(realpath($fname));
        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        $backloc = "../bom_tran/m_mat_plan.php?menucd=".$var_menucode;
       	echo "<script>";
       	echo 'location.replace("'.$backloc.'")';
        echo "</script>"; 
    }
    */
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
    					{ "sType": "uk_date" },
    					null,
    					null,
    					{ "bSortable": false },
    					{ "bSortable": false },
    					{ "bSortable": false },
    					{ "bSortable": false }
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
				     null,
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

function newWindow(file,window) {
    msgWindow=open(file,window,'resizable=yes,scrollbars=yes,width=1250,height=700');
    if (msgWindow.opener == null) msgWindow.opener = self;
}
		
</script>
</head>
<body>
    <?php include("../topbarm.php"); ?> 
 
  <div class ="contentc">


	<fieldset name="Group1" style=" width: 992px;" class="style2">
	 <legend class="title">MATERIAL PLANNING LISTING</legend>
	  <br>
	 
        <form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table>
		 <tr>
		  
           <td style="width: 980px; height: 38px;" align="left">
           <?php
                $locatr = "mat_plan.php?menucd=".$var_menucode;
                if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Create" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Create" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}
  				
    	  	   $msgdel = "Are You Sure Delete Selected Costing No From Material Planning List?";
    	  	   include("../Setting/btndelete.php");
    	      ?></td>
		 </tr>
		 </table>
		 <br>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" style="width: 100%">
         <thead>
           <tr>
          <th></th>
          <th style="width: 125px">Planning No</th>
          <th style="width: 107px">Doc. Date</th>
          <th style="width: 70px">Plan By</th>
          <th style="width: 396px">Remark</th>
          <th></th>
          <th></th>
          <th></th>
		  <th></th>
         </tr>

         <tr>
          <th class="tabheader" style="width: 12px">#</th>
          <th class="tabheader" style="width: 125px">Planning No</th>
          <th class="tabheader" style="width: 90px">Doc. Date</th>
          <th class="tabheader" style="width: 90px">Plan By</th>
          <th class="tabheader" style="width: 384px">Remark</th>
          <th class="tabheader" style="width: 12px">Print</th>
          <th class="tabheader" style="width: 12px">Detail</th>
          <th class="tabheader" style="width: 12px">Update</th>
		  <th class="tabheader" style="width: 12px">Delete</th>
         </tr>
         </thead>
		 <tbody>
		 <?php 
		    $sql = "SELECT costingno, docdate, remark, modified_by, modified_on, planopt ";
		    $sql .= " FROM costing_mat";
    		$sql .= " ORDER BY costingno";  
			$rs_result = mysql_query($sql); 
	
		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) { 

				$docdte = date('d-m-Y', strtotime($rowq['docdate']));
				$showdte = date('d-m-Y', strtotime($rowq['modified_on']));
				
				if($rowq['planopt'] == "C"){
					$optplan = "Color";
					$urlpop = 'upd_matcost.php';
				    $urlvie = 'vm_matcost_col.php';
				}else{
					$optplan = "Product Code";
					$urlpop = 'upd_matcost.php';
				    $urlvie = 'vm_matcost.php';
				}
				
				$prtplan = "matplanrpt.php?opt=".$rowq['costingno'];

				echo '<tr bgcolor='.$defaultcolor.'>';
            	echo '<td>'.$numi.'</td>';
           		echo '<td>'.$rowq['costingno'].'</td>';
            	echo '<td>'.$docdte.'</td>';
            	echo '<td>'.$optplan.'</td>';
            	echo '<td align="left">'.$rowq['remark'].'</td>';
            	?>
            	<td align="center"><a href=javascript:newWindow("<?php echo $prtplan; ?>") title="Print BOM For All Color"><img src="../images/b_print.png" border="0" width="16" height="16" hspace="2" alt="Duplicate BOM Planning" /></a></td> 
            	<?php
            	if ($var_accvie == 0){
            		echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlvie.'?costn='.$rowq['costingno'].'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
            	}
	            if ($var_accupd == 0){
		            echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
	            }else{
		            echo '<td align="center"><a href="'.$urlpop.'?costn='.$rowq['costingno'].'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
	            }
	            if ($var_accdel == 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="costn[]" value="'.$values.'" />'.'</td>';
	            }else{
	              $values = implode(',', $rowq);
	              echo '<td align="center"><input type="checkbox" name="costn[]" value="'.$values.'" />'.'</td>';
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
