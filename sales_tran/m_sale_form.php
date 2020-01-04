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
   	 	$var_menucode = $_GET['menucd'];
   	 	$var_sor  = $_GET['sno'];
   	 	$var_buy  = $_GET['buycd'];
        
        $fname = "sales_f_rpt.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&sno=".$var_sor."&buyc=".$var_buy."&dbsel=".$varrpturldb;
        $dest .= urlencode(realpath($fname));

        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        $backloc = "../sales_tran/m_sale_form.php?menucd=".$var_menucode;

       	echo "<script>";
       	echo 'location.replace("'.$backloc.'")';
        echo "</script>"; 
   	 }	
   
 
	 if ($_POST['Submit'] == "Cancel") {
     	if(!empty($_POST['salorno']) && is_array($_POST['salorno'])) 
     	{
           foreach($_POST['salorno'] as $key) {
             $defarr = explode(",", $key);
             $var_sale = $defarr[0];
             $var_buyer = $defarr[3];
                        
		     $vartoday = date("Y-m-d H:i:s");
			 $sql  = "Update salesentry Set stat = 'CANCEL', modified_by = '$var_loginid', modified_on = '$vartoday' ";
             $sql .=	" Where sordno ='".$var_sale."' And sbuycd='".$var_buyer."'";
             
             mysql_query($sql) or die(mysql_error()." 1");		 	 
		   }
		   $backloc = "../sales_tran/m_sale_form.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";   
       }      
    }
    
   if ($_POST['Submit'] == "Active") {
     	if(!empty($_POST['salorno']) && is_array($_POST['salorno'])) 
     	{
           foreach($_POST['salorno'] as $key) {
             $defarr = explode(",", $key);
             $var_sale = $defarr[0];
             $var_buyer = $defarr[3];
                        
		     $vartoday = date("Y-m-d H:i:s");
			 $sql  = "Update salesentry Set stat = 'ACTIVE', modified_by = '$var_loginid', modified_on = '$vartoday' ";
             $sql .=	" Where sordno ='".$var_sale."' And sbuycd='".$var_buyer."'";
             
             mysql_query($sql) or die(mysql_error()." 1");		 	 
		   }
		   $backloc = "../sales_tran/m_sale_form.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";   
       }      
    }
    
    if ($_POST['Submit'] == "Delete") {
     	if(!empty($_POST['salorno']) && is_array($_POST['salorno'])) 
     	{
           foreach($_POST['salorno'] as $key) {
             $defarr = explode(",", $key);
             $var_sale = $defarr[0];
             $var_buyer = $defarr[3];
                        
		     $vartoday = date("Y-m-d H:i:s");
			 $sql  = "DELETE FROM salesentry ";
             $sql .=	" WHERE sordno ='".$var_sale."' And sbuycd='".$var_buyer."'";
             
             mysql_query($sql) or die(mysql_error()." 1");		 	 
		   }
		   $backloc = "../sales_tran/m_sale_form.php?stat=1&menucd=".$var_menucode;
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
    					{ "sType": "uk_date" },
    					{ "sType": "uk_date" },
    					null,
    					null,
    					null,
    					null,
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
    <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>--> 

<body>
  <div class="contentc">


	<fieldset name="Group1" style=" width: 1143px;" class="style2">
	 <legend class="title">BUYER PURCHASE ORDER LISTING</legend>
	  <br>
	 
        <form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table>
		 <tr>
		  
           <td style="width: 1131px; height: 38px;" align="left">
           <?php
                $locatr = "sale_form.php?menucd=".$var_menucode;
                if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Create" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Create" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}
  				
    	  	   $msgdel = "Are You Sure Want to Cancel Selected Sales Entry?";
    	  	   if ($var_accdel == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Cancel" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type=submit name = "Submit" value="Cancel" class="butsub" style="width: 60px; height: 32px" onclick="return confirm(\''.$msgdel.'\')">';
  				}
  				
			   $msgdel = "Are You Sure Want to Active Selected Sales Entry?";
    	  	   if ($var_accdel == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Cancel" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type=submit name = "Submit" value="Active" class="butsub" style="width: 60px; height: 32px" onclick="return confirm(\''.$msgdel.'\')">';
  				}
  				
  				$msgdel = "Are You Sure Want to Delete Selected Sales Entry?";
    	  	   if ($var_accdel == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Delete" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type=submit name = "Submit" value="Delete" class="butsub" style="width: 60px; height: 32px" onclick="return confirm(\''.$msgdel.'\')">';
  				}


    	      ?></td>
		 </tr>
		 </table>
		 <br>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         <thead>
           <tr>
          <th></th>
          <th style="width: 234px">Order No</th>
          <th style="width: 129px">Order Date</th>
          <th style="width: 128px">Exp Del Date</th>
          <th style="width: 124px">Buyer</th>
          <th>Status</th>
          <th></th>
          <th></th>
          <th></th>
		  <th></th>
		  <th></th>
		  <th></th>
         </tr>

         <tr>
          <th class="tabheader" style="width: 12px">#</th>
          <th class="tabheader" style="width: 234px">Order No.</th>
          <th class="tabheader" style="width: 129px">Order Date</th>
          <th class="tabheader" style="width: 128px">Exp Delivery Date</th>
          <th class="tabheader" style="width: 124px">Buyer</th>
          <th class="tabheader" style="width: 124px">Status</th>
          <th class="tabheader" style="width: 12px">Detail</th>
          <th class="tabheader" style="width: 12px">Print</th>
          <th class="tabheader" style="width: 12px">Update</th>
		  <th class="tabheader" style="width: 12px">Cancel</th>
		  <th class="tabheader" style="width: 12px">Active</th>
		  <th class="tabheader" style="width: 12px">Delete</th>
         </tr>
         </thead>
		 <tbody>
		 <?php 
		    $sql = "SELECT sordno, sorddte, sexpddte, sbuycd, modified_by, modified_on, stat ";
		    $sql .= " FROM salesentry";
    		$sql .= " ORDER BY modified_on desc limit 10";  
			$rs_result = mysql_query($sql); 
	
		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) { 
			
				$salorno = htmlentities($rowq['sordno']);
				$orddte = date('d-m-Y', strtotime($rowq['sorddte']));
				$expddte = date('d-m-Y', strtotime($rowq['sexpddte']));
				$showdte = date('d-m-Y', strtotime($rowq['modified_on']));
				$postat = date('d-m-Y', strtotime($rowq['stat']));
				
				//$sql1 = "select app_stat from salesappr";
        		//$sql1 .= " where sordno ='".$salorno."' ";
        		//$sql1 .= " and sbuycd ='".$rowq['sbuycd']."' ";
        		//$sql_result1 = mysql_query($sql1) or die("error query sales order status :".mysql_error());
        		//$row2 = mysql_fetch_array($sql_result1);
				//$sstat = $row2[0];
				$sstat = "1";
				
				$urlpop = 'upd_saleentry.php';
				$urlvie = 'vm_saleentry.php';
				echo '<tr>';
            	echo '<td>'.$numi.'</td>';
           		echo '<td>'.$salorno.'</td>';
            	echo '<td>'.$orddte.'</td>';
            	echo '<td>'.$expddte.'</td>';
            	echo '<td>'.$rowq['sbuycd'].'</td>';
            	echo '<td>'.$rowq['stat'].'</td>';
            
            	if ($var_accvie == 0){
            		echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlvie.'?sorno='.$salorno.'&buycd='.$rowq['sbuycd'].'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
            	}
            	
            	echo '<td align="center"><a href="m_sale_form.php?p=Print&sno='.$salorno.'&buycd='.$rowq['sbuycd'].'&menucd='.$var_menucode.'" title="Print Sales Order"><img src="../images/b_print.png" border="0" width="16" height="16" hspace="2" alt="Duplicate Sales Order" /></a></td>'; 

	            if ($var_accupd == 0){
		            echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
	            }else{
	            	if ($sstat == "APPROVE"){
	            		echo '<td align="center"><a href="#" title="This Sales Order Is Approved; Edit Is Not Allow">[EDIT]</a>';'</td>';
	            	}else{ 
		            	echo '<td align="center"><a href="'.$urlpop.'?sorno='.$salorno.'&buycd='.$rowq['sbuycd'].'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
	            	}
	            }
	            if ($var_accdel == 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
	            }else{
	              if ($sstat == "APPROVE"){
					echo '<td align="center"><input type="checkbox" title="This Sales Order Is Approved; Edit Is Not Allow" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
				  }else{	
	              	$values = implode(',', $rowq);	
	              	echo '<td align="center"><input type="checkbox" name="salorno[]" value="'.$values.'" />'.'</td>';
    	          }	
    	        }
           		
           		 if ($var_accdel == 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
	            }else{
	              if ($sstat == "APPROVE"){
					echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
				  }else{	
	              	$values = implode(',', $rowq);	
	              	echo '<td align="center"><input type="checkbox" name="salorno[]" value="'.$values.'" />'.'</td>';
    	          }	
    	        }
    	        
    	        if ($var_accdel == 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
	            }else{
	              if ($sstat == "APPROVE"){
					echo '<td align="center"><input type="checkbox" title="This Sales Order Is Approved; Cannot Delete" DISABLED  name="procd[]" value="'.$values.'" />'.'</td>';
				  }else{	
				  	$values = implode(',', $rowq);	
				  	if ($postat <> "CANCEL"){       		
	              		echo '<td align="center"><input type="checkbox" name="salorno[]" value="'.$values.'" />'.'</td>';
	              	}else{
	              		echo '<td align="center"><input type="checkbox" DISABLED  name="salorno[]" value="'.$values.'" />'.'</td>';
	              	}
    	          }	
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
