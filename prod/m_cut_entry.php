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
                         
		     $sql = "DELETE FROM prod_matmain "; 
		     $sql .= "WHERE prod_code ='".$var_proccd."'";  
		 	 mysql_query($sql); 
		 	 
		 	 $sql = "DELETE FROM prod_matlis "; 
		     $sql .= "WHERE prod_code ='".$var_proccd."'";  
		 	 mysql_query($sql);
		 	 
			 $sql = "DELETE FROM procos_appr "; 
		     $sql .= "WHERE pro_code ='".$var_proccd."'";  
		 	 mysql_query($sql);  

		   }
		   $backloc = "../bom_tran/m_pro_cost.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";   
       }      
    }
    
    if ($_GET['p'] == "Print"){
        $prcode = $_GET['prod_code'];
        
        $fname = "prcost_rpt.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&prc=".$prcode."&dbsel=".$varrpturldb."&usernm=".$var_loginid;
        $dest .= urlencode(realpath($fname));
        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        $backloc = "../bom_tran/m_pro_cost.php?menucd=".$var_menucode;
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


	<fieldset name="Group1" style=" width: 1024px;" class="style2">
	 <legend class="title">PRODUCT COST & MATERIAL LISTING</legend>
	  <br>
	 
        <form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table>
		 <tr>
		  
           <td style="width: 1011px; height: 38px;" align="left">
           <?php
                $locatr = "pro_cost.php?menucd=".$var_menucode;
                if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Create" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Create" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}
  				$locatr = "copy_pro_cost.php?menucd=".$var_menucode;
  				if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Copy" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Copy" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}
    	  	   $msgdel = "Are You Sure Delete Selected Product Code From Material & Costing List?";
    	  	   include("../Setting/btndelete.php");
    	      ?></td>
		 </tr>
		 </table>
		 <br>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" style="width: 99%">
         <thead>
           <tr>
          <th></th>
          <th style="width: 100px">Product Code</th>
          <th style="width: 173px">Prod. Description</th>
          <th>Costing Date</th>
          <th></th>
          <th></th>
		  <th></th>
         </tr>

         <tr>
          <th class="tabheader" style="width: 12px">#</th>
          <th class="tabheader" style="width: 100px">Product Code</th>
          <th class="tabheader" style="width: 173px">Prod. Description</th>
          <th class="tabheader" style="width: 106px">Costing Date</th>
          <th class="tabheader" style="width: 12px">Detail</th>
          <th class="tabheader" style="width: 12px">Print</th>
          <th class="tabheader" style="width: 12px">Update</th>
		  <th class="tabheader" style="width: 12px">Delete</th>
         </tr>
         </thead>
		 <tbody>
		 <?php 
		    $sql = "SELECT prod_code, docdate, modified_by, modified_on ";
		    $sql .= " FROM prod_matmain";
    		$sql .= " ORDER BY prod_code";  
			$rs_result = mysql_query($sql); 
	
		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) { 
			
				$sql = "select prod_desc from pro_cd_master ";
        		$sql .= " where prod_code ='".$rowq['prod_code']."'";
        		$sql_result = mysql_query($sql);
        		$row2 = mysql_fetch_array($sql_result);
			
				$prodcd = htmlentities($rowq['prod_code']);
				$revno = htmlentities($rowq['revno']);
				$showdte = date('d-m-Y', strtotime($rowq['modified_on']));
				$docdte = date('d-m-Y', strtotime($rowq['docdate']));
				$urlpop = 'upd_procost.php';
				$urlvie = 'vm_procost.php';
				echo '<tr bgcolor='.$defaultcolor.'>';
            	echo '<td>'.$numi.'</td>';
           		echo '<td align="left">'.$prodcd.'</td>';
            	echo '<td>'.$row2[0].'</td>';
            	echo '<td>'.$docdte.'</td>';            	
            	
            	// to check the product costing used in sales or not, if yes, cannot delete
            	$sql1 = "select count(*) from salesentrydet";
        		$sql1 .= " where sprocd='".$prodcd."' ";
        		$sql_result1 = mysql_query($sql1) or die("error query sales entry:".mysql_error());
        		$row2 = mysql_fetch_array($sql_result1);
				$cnt = $row2[0];
				
				// to check the approve product costing, control delete
            	$sqlc = "select stat from procos_appr";
        		$sqlc .= " where pro_code ='$prodcd' ";
        		$sql_resultc = mysql_query($sqlc) or die("error query approve table :".mysql_error());
        		$rowc = mysql_fetch_array($sql_resultc);
				$apstat = $rowc[0];

            
            	if ($var_accvie == 0){
            		echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlvie.'?procd='.$prodcd.'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
            	}
            	
            	echo '<td align="center"><a href="m_pro_cost.php?p=Print&prod_code='.$prodcd.'&menucd='.$var_menucode.'" title="Print Product Costing"><img src="../images/b_print.png" border="0" width="16" height="16" hspace="2" alt="Duplicate Product Costing" /></a></td>'; 

	            if ($var_accupd == 0){
		            echo '<td align="center"><a href="#" title="You Are Not Authorice To Update Product Costing">[EDIT]</a>';'</td>';
	            }else{
	            	if ($apstat == 'APPROVE'){
	            		echo '<td align="center"><a href="#" title="This Product Costing Has Been Approved Not Allowed To Edot">[EDIT]</a>';'</td>';
	            	}else{
		            	echo '<td align="center"><a href="'.$urlpop.'?procd='.$prodcd.'&menucd='.$var_menucode.'" title="'.$apstat.'">[EDIT]</a>';'</td>';
	            	}
	            }
	            if ($var_accdel == 0 or $cnt <> 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" title="This Product Already In Sales Entry Not Allowed To Delete" value="'.$values.'" />'.'</td>';
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
