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
 
 	 if ($_GET['p'] == "Print"){
        $bno = $_GET['bkno'];
        
        $fname = "book_slip.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&bk=".$bno."&dbsel=".$varrpturldb;
        $dest .= urlencode(realpath($fname));
        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        $backloc = "../book_tran/m_booksys.php?menucd=".$var_menucode;
       	echo "<script>";
       	echo 'location.replace("'.$backloc.'")';
        echo "</script>"; 
    }
    
	if ($_GET['p'] == "Label"){
        $bno = $_GET['bkno'];      
        
        $backloc = "../book_tran/bookslip.php?menucd=".$var_menucode."&bk=".$bno;
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
				     { type: "text" },
				     { type: "text" },
				     null,
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
			
</script>
</head>
<body>
   <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc">


	<fieldset name="Group1" style=" width: 910px;" class="style2">
	 <legend class="title">BOOKING LISTING</legend>
	  <br>
	 
        <form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table>
		 <tr>
		  
           <td style="width: 1131px; height: 38px;" align="left">
           <?php
                $locatr = "booktranm.php?menucd=".$var_menucode;
                if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Manual" class="butsub" style="width: 70px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Create" class="butsub" style="width: 70px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}
  				
  				//$locatr = "booktranp.php?menucd=".$var_menucode;
                //if ($var_accadd == 0){
   				//	echo '<input disabled="disabled" type=button name = "Submit" value="Planning" class="butsub" style="width: 70px; height: 32px">';
  				//}else{
   				//	echo '<input type="button" value="Planning" class="butsub" style="width: 70px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				//}
    	      ?>
    	    </td>
		 </tr>
		 </table>
		 <br>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         <thead>
           <tr>
          	<th></th>
          	<th style="width: 89px">Booking No</th>
          	<th style="width: 130px">Booking Date</th>
          	<th style="width: 3px">Type</th>
          	<th style="width: 131px">Ref No</th>
          	<th>Book By</th>
          	<th style="width: 149px">Tran Date</th>
          	<th></th>
          	<th></th>
          	<th></th>
          	<th></th>
           </tr>
           <tr>
          	<th class="tabheader" style="width: 12px">#</th>
          	<th class="tabheader" style="width: 89px">Booking No</th>
          	<th class="tabheader" style="width: 130px">Booking Date</th>
          	<th class="tabheader" style="width: 3px">Booking Type</th>
          	<th class="tabheader" style="width: 131px">Ref No</th>
          	<th class="tabheader" style="width: 100px">Book By</th>
          	<th class="tabheader" style="width: 149px">Tran Date</th>
          	<th class="tabheader" style="width: 20px">Detail</th>
          	<th class="tabheader" style="width: 20px">Print</th>
          	<th class="tabheader" style="width: 20px">Slip</th>
          	<th class="tabheader" style="width: 20px">Update</th>
         </tr>
         </thead>
		 <tbody>
		 <?php 
		    $sql  = "SELECT distinct x.bookno, x.bookdte, x.booktyp, x.byrefno, x.modified_by, x.modified_on ";
		    $sql .= " FROM booktab01 x, booktab02 y";
		    $sql .= " Where x.bookno = y.bookno";
		    $sql .= " And y.compflg = 'N'";
    		$sql .= " ORDER BY x.bookno";  
			$rs_result = mysql_query($sql); 
	
		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) {
			
				$bookdte = date('d-m-Y', strtotime($rowq['bookdte']));
				$trxdte  = date('d-m-Y', strtotime($rowq['modified_on']));
			        
				$urlpop = 'upd_bookitm.php?bno='.$rowq['bookno'].'&menucd='.$var_menucode;
				$urlvie = 'vm_bookitm.php?bno='.$rowq['bookno'].'&menucd='.$var_menucode;
				
				echo '<tr>';
            	echo '<td>'.$numi.'</td>';
           		echo '<td>'.$rowq['bookno'].'</td>';
            	echo '<td>'.$bookdte.'</td>';
            	echo '<td>'.$rowq['booktyp'].'</td>';
            	echo '<td>'.$rowq['byrefno'].'</td>';
            	echo '<td>'.$rowq['modified_by'].'</td>';
            	echo '<td>'.$trxdte.'</td>';
            
            	if ($var_accvie == 0){
            		echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlvie.'" title="View Detail Booking">[VIEW]</a>';'</td>';
            	}
            	
            	echo '<td align="center"><a href="m_booksys.php?p=Print&bkno='.$rowq['bookno'].'&menucd='.$var_menucode.'" title="Print Booking List"><img src="../images/b_print.png" border="0" width="16" height="16" hspace="2" alt="Duplicate Booking List" /></a></td>';
            	echo '<td align="center"><a href="m_booksys.php?p=Label&bkno='.$rowq['bookno'].'&menucd='.$var_menucode.'" title="Print Booking Slip"><img src="../images/icolabel.png" border="0" width="16" height="16" hspace="2" alt="Duplicate Booking Slip" /></a></td>';

	            if ($var_accupd == 0){
		            echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
	            }else{
		            echo '<td align="center"><a href="'.$urlpop.'" title="Update Detail Booking">[EDIT]</a>';'</td>';
	            }
	           
           		echo '</tr>';
            	$numi = $numi + 1;
			}
      		mysql_close ($db_link);
		 ?>
		 </tbody>
		 </table>
		</form>
	   </fieldset>
	  </div>	
	  <div class="spacer"></div>
	
</body>

</html>
