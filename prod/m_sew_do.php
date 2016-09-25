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
            foreach($_POST['procd'] as $value ) {
		
			$sql = " select distinct ticketno from sew_do_tran ";
     		$sql.= " WHERE sew_doid = '$value'";
     		$sql_result = mysql_query($sql);
            //echo $sql . "</br>";   
	 		while ($row = mysql_fetch_assoc($sql_result)) 
	 		{ 	 		
	 			$sqlw = "DELETE FROM fg_tran "; 
		     	$sqlw .= "WHERE rm_receive_id ='".$row['ticketno']."' AND tran_type = 'ISS'";  
		 	 	mysql_query($sqlw) or die("Error deleting in FG tran :".mysql_error(). ' Failed SQL is -->'. $sqlw);
		 	 	//echo $sqlw; //break;
		   }
		   
		   	$sql = "DELETE FROM sew_do ";
			$sql.= " WHERE sew_doid = '$value'";
			mysql_query($sql) or die("Error DELETE Sewing DO :".mysql_error(). ' Failed SQL is -->'. $sql);	
			//echo $sql; break;
			
			$sql = "DELETE FROM sew_do_tran ";
			$sql.= " WHERE sew_doid = '$value'";
			mysql_query($sql) or die("Error DELETE Sewing DO Trans:".mysql_error(). ' Failed SQL is -->'. $sql);	
			

	   }
		   $backloc = "../prod/m_sew_do.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";   
       }      
    }
    
    if ($_GET['p'] == "PrintDO"){
   	 	$var_menucode = $_GET['menucd'];
   	 	$dono = $_GET['donum'];
        
        $fname = "sew_doform2.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&do=".$dono."&menuc=".$var_menucode."&dbsel=".$varrpturldb;
        $dest .= urlencode(realpath($fname));
        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        $backloc = "../prod/m_sew_do.php?&menucd=".$var_menucode;
       	echo "<script>";
       	echo 'location.replace("'.$backloc.'")';
        echo "</script>"; 
   	}
   	
	if ($_GET['p'] == "PrintGIN"){
   	 	$var_menucode = $_GET['menucd'];
   	 	$dono = $_GET['donum'];
        
        $fname = "sew_ginform.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&do=".$dono."&menuc=".$var_menucode."&dbsel=".$varrpturldb;
        $dest .= urlencode(realpath($fname));
        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
         $backloc = "../prod/m_sew_do.php?&menucd=".$var_menucode;
       	echo "<script>";
       	echo 'location.replace("'.$backloc.'")';
        echo "</script>"; 

   	 }		?>
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
    					{ "sType": "uk_date" },
    					{ "sType": "uk_date" },
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
			
</script>
</head>
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>--> 
<body>
  
  <div class ="contentc">


	<fieldset name="Group1" style=" width: 1000px;" class="style2">
	 <legend class="title">SEWING DELIVERY ORDER&nbsp; </legend>
&nbsp;<form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table>
		 <tr>
		  
           <td style="width: 1011px; height: 38px;" align="left">
           <?php
                $locatr = "sew_do.php?menucd=".$var_menucode;
                if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Create" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Create" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}
  				$msgdel = "Are You Sure Want To Delete Selected Sewing DO?";
    	  	   include("../Setting/btndelete.php");
    	  	   
    	  	    $locatr = "m_sew_do_man.php?menucd=".$var_menucode;
                if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="ManualDO" class="butsub" style="width: 90px; height: 32px">';
  				}else{
   					echo '<input type="button" value="ManualDO	" class="butsub" style="width: 90px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}

    	      ?></td>
		 </tr>
		 </table>
		 <br>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" style="width: 99%; margin-top: 2;">
         <thead align="center">
           <tr>
          <th style="width: 6px"></th>
          <th style="width: 25px">DO Number</th>
          <th style="width: 9px">Buyer</th>
          <th>DO Date</th>
          <th>From Date</th>
          <th style="width: 64px">To Date</th>
          <th style="width: 64px">Posted</th>
          <th></th>
		  <th></th>
          <th></th>
		  <th></th>
         </tr>

         <tr>
          <th class="tabheader" style="width: 6px">#</th>
          <th class="tabheader" style="width: 25px">DO Number</th>
          <th class="tabheader" style="width: 9px">Buyer</th>
          <th class="tabheader" style="width: 106px">DO Date</th>
          <th class="tabheader" style="width: 106px">From Date</th>
          <th class="tabheader" style="width: 64px">To Date</th>
          <th class="tabheader" style="width: 64px">Posted</th>
          <th class="tabheader" style="width: 12px">Print D/O</th>
          <th class="tabheader" style="width: 12px">Print GIN</th>
          <th class="tabheader" style="width: 12px">Detail</th>
          <th class="tabheader" style="width: 12px">Update</th>
		  <th class="tabheader" style="width: 12px">Delete</th>
         </tr>
         </thead>
		 <tbody>
		 <?php 
		    $sql = "SELECT *  ";
			$sql.= " FROM sew_do"; 

			$rs_result = mysql_query($sql); 
			//echo $sql;
	
		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) { 
			
				$sql = "select prod_desc from pro_cd_master ";
        		$sql .= " where prod_code ='".$rowq['prod_code']."'";
        		$sql_result = mysql_query($sql);
        		$row2 = mysql_fetch_array($sql_result);
        		
        		$sql = "select customer_desc from customer_master ";
        		$sql .= " where customer_code ='".$rowq['buyer']."'";
        		$sql_result = mysql_query($sql);
        		$row2 = mysql_fetch_array($sql_result);
				$buyer_desc= $row2[0];


			
				$sew_doid = htmlentities($rowq['sew_doid']);
				//$dodate= htmlentities($rowq['dodate']);
				//$frdate= htmlentities($rowq['frdate']);
				//$todate= htmlentities($rowq['todate']);
				$posted= htmlentities($rowq['posted']);
				
				$frdate= date('d-m-Y', strtotime($rowq['frdate']));
				$todate= date('d-m-Y', strtotime($rowq['todate']));
				$dodate= date('d-m-Y', strtotime($rowq['dodate']));

//				$newproduct= htmlentities($rowq['newproduct']);
//				$creation_time = date('d-m-Y', strtotime($rowq['creation_time']));
//				$qcdate = date('d-m-Y', strtotime($rowq['qcdate']));

				$urlpop = 'upd_sew_do.php';
				$urlvie = 'vm_sew_do.php';
				echo '<tr bgcolor='.$defaultcolor.'>';
            	echo '<td>'.$numi.'</td>';
           		echo '<td align="left">'.$sew_doid.'</td>';
           		echo '<td align="left">'.$buyer_desc.'</td>';
            	echo '<td>'.$dodate.'</td>';
            	echo '<td>'.$frdate.'</td>';
            	echo '<td>'.$todate.'</td>';   
            	echo '<td>'.$posted.'</td>';   
          	
            	
            	// to check the sewing QC, if have record, cannot delete
            	$sql1 = "select count(*) from sew_do";
        		$sql1 .= " where sew_doid='".$sew_doid."' ";
        		$sql1 .= " and posted='Y' ";
        		$sql_result1 = mysql_query($sql1) or die("error query sew qc :".mysql_error());
        		$row2 = mysql_fetch_array($sql_result1);
				$cnt = $row2[0];
				//echo $sql1;  "<br/>";
				//echo $cnt; "<br/>";
				
				echo '<td align="center"><a href="m_sew_do.php?p=PrintDO&donum='.$sew_doid.'&menucd='.$var_menucode.'" title="Print Sewing D/O"><img src="../images/b_print.png" border="0" width="16" height="16" hspace="2" alt="Duplicate Sewing D/O" /></a></td>'; 
				echo '<td align="center"><a href="m_sew_do.php?p=PrintGIN&donum='.$sew_doid.'&menucd='.$var_menucode.'" title="Print GIN"><img src="../images/b_print.png" border="0" width="16" height="16" hspace="2" alt="Duplicate GIN" /></a></td>'; 

				           
            	if ($var_accvie == 0){
            		echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlvie.'?donum='.$sew_doid.'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
            	}
            	
            	//echo '<td align="center"><a href="m_sew_do.php?p=Print&prod_code='.$ticketno.'&menucd='.$var_menucode.'" title="Print Product Costing"><img src="../images/b_print.png" border="0" width="16" height="16" hspace="2" alt="Duplicate Product Costing" /></a></td>'; 




	            if ($var_accupd == 0 ){
		            echo '<td align="center"><a href="#" title="You Are Not Authorice To Update Product Costing">[EDIT]</a>';'</td>';
	            }else{
	            	if ($cnt <> 0 ){
	            		echo '<td align="center"><a href="#" title="This Record is Posted. Not Allowed To Edit">[EDIT]</a>';'</td>';
	            	}else{
		            	echo '<td align="center"><a href="'.$urlpop.'?donum='.$sew_doid.'&menucd='.$var_menucode.'" title="'.$apstat.'">[EDIT]</a>';'</td>';
	            	}
	            }
	            if ($var_accdel == 0 or $cnt <> 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" title="This record is Posted. Not Allowed To Delete" value="'.$sew_doid.'" />'.'</td>';
	            }else{
	             // $values = implode(',', $rowq);	
	              echo '<td align="center"><input type="checkbox" name="procd[]" value="'.$sew_doid.'" />'.'</td>';
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
