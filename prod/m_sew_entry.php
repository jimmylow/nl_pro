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
                         
		     $sql = "DELETE FROM sew_entry "; 
		     $sql .= "WHERE ticketno ='".$var_proccd."'";  
		     mysql_query($sql) or die("Error deleting in Sew Entry:".mysql_error(). ' Failed SQL is -->'. $sql);
		 	 
		 	 $sql = "DELETE FROM sew_barcode "; 
		     $sql .= "WHERE ticketno ='".$var_proccd."'";  
		 	 mysql_query($sql) or die("Error deleting in Sew Barcode:".mysql_error(). ' Failed SQL is -->'. $sql);
		 	 
		 	 $sql = "DELETE FROM wip_tran "; 
		     $sql .= "WHERE rm_receive_id ='".$var_proccd."' AND tran_type = 'REC'";  
		 	 mysql_query($sql) or die("Error deleting in WIP tran :".mysql_error(). ' Failed SQL is -->'. $sql);
		 	

		   }
		   $backloc = "../prod/m_sew_entry.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";   
       }      
    }
    //print_r($_GET);
    if ($_GET['p'] == "Print"){
        $prcode = $_GET['prod_code'];
        $ticketno = $_GET['tic'];
        
        //$fname = "prcost_rpt.rptdesign&__title=myReport"; 
        //$dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&prc=".$prcode."&dbsel=".$varrpturldb."&usernm=".$var_loginid;
        $fname = "sew_barentry.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&tic=".$ticketno."&menuc=".$var_menucode."&dbsel=".$varrpturldb;
        $dest .= urlencode(realpath($fname));
        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        $backloc = "../prod/m_sew_entry.php?menucd=".$var_menucode;
       	echo "<script>";
       	//echo 'location.replace("'.$backloc.'")';
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
		"bProcessing": true,
		"bServerSide": true,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50,"All"]],
		"bStateSave": true,
		"bFilter": true,
		"sDom": "Rlfrtip",
		"sAjaxSource": "m_prosentry.php",
		"sPaginationType": "full_numbers",
		"bAutoWidth":false,
		"aoColumns": [
    					null,
    					{ "sType": "uk_date" },
    					null,
    					null,
    					null,
    					null,
    					null,
    					{ "sType": "uk_date" },
    					null,
    					null,
    					null,
    					null,
    					null
    				]

	})
	
	.columnFilter({sPlaceHolder: "head:after",

		aoColumns: [ 
					 { type: "text" },
					 { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     { type: "text" },
				     { type: "text" },
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
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>--> 
<body>
  
  <div class ="contentc">


	<fieldset name="Group1" style=" width: 1200px;" class="style2">
	 <legend class="title">SEWING TICKET ENTRY LISTING</legend>
&nbsp;<form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table>
		 <tr>
		  
           <td style="width: 1011px; height: 38px;" align="left">
           <?php
                $locatr = "sew_entry.php?menucd=".$var_menucode;
                if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Create" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Create" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}
  				$msgdel = "Are You Sure Delete Selected Sewing Ticket Entry List?";
    	  	   include("../Setting/btndelete.php");
    	      ?></td>
		 </tr>
		 </table>
		 <br>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" style="width: 99%">
         <thead>
           <tr>
          <th style="width: 9px">Ticket No</th>
          <th>Prod Date</th>
          <th>Product Code</th>
          <th style="width: 64px">Pro. Qty</th>
          <th style="width: 64px">Buyer</th>
          <th style="width: 144px">Delivery Date</th>
          <th style="width: 64px">Batch No.</th>
          <th style="width: 93px">QC Date</th>
          <th style="width: 107px">Buyer Order</th>
          <th></th>
          <th></th>
		  <th></th>
         </tr>

         <tr>
          <th class="tabheader" style="width: 9px">Ticket No</th>
          <th class="tabheader" style="width: 106px">Prod Date</th>
          <th class="tabheader" style="width: 106px">Product Code</th>
          <th class="tabheader" style="width: 64px">Prod. Qty</th>
          <th class="tabheader" style="width: 64px">Buyer</th>
          <th class="tabheader" style="width: 144px">Delivery Date</th>
          <th class="tabheader" style="width: 64px">Batch No.</th>
          <th class="tabheader" style="width: 93px">QC Date</th>
          <th class="tabheader" style="width: 107px">Buyer Order</th>
          <th class="tabheader" style="width: 12px">Detail</th>
          <th class="tabheader" style="width: 12px">Print</th>
          <th class="tabheader" style="width: 12px">Update</th>
		  <th class="tabheader" style="width: 12px">Delete</th>
         </tr>
         </thead>
		 <tbody>
			<tr>
				<td colspan="13" class="dataTables_empty">Loading data from server</td>
			</tr>
		 </tbody>
		 </table>
		</form>
	   </fieldset>
	  </div>	
	  <div class="spacer"></div>
	
</body>

</html>
