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
                        
		     $sql = "DELETE FROM rawmat_opening "; 
		     $sql .= "WHERE rm_opening_id ='".$var_proccd."'";  
		 	 mysql_query($sql); 
		 	 
		 	 $sql = "DELETE FROM rawmat_opening_tran "; 
		     $sql .= "WHERE rm_opening_id ='".$var_proccd."'";  
		 	 mysql_query($sql); 
		 	 
		 	 $sql = "DELETE FROM rawmat_tran "; 
		     $sql .= "WHERE rm_receive_id  ='".$var_proccd."' and tran_type = 'OPB'";  
		 	 mysql_query($sql); 
		 	 echo $sql;
		 	 break;
		 

 

		   }
		   $backloc = "../stck_tran/m_rm_opening.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";   
       }      
    }
	
	if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
     
     	 $backloc = "../stck_tran/inq_raw_open.php?menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";   
     }
    } 
    
    if(isset($_POST['importSubmit'])){
    
    	//validate whether uploaded file is a csv file
    	$csvMimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
    	if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'],$csvMimes)){
    		if(is_uploaded_file($_FILES['file']['tmp_name'])){
    
    			//open uploaded csv file with read only mode
    			$csvFile = fopen($_FILES['file']['tmp_name'], 'r');
    
    			//skip first line
    			//fgetcsv($csvFile);
    
    			//parse data from csv file line by line
    			while(($line = fgetcsv($csvFile)) !== FALSE){    			
    				$refno = $line[0];
    				$matcode = $line[1];
    				$matdesc = $line[2];
    				$matuom = $line[3];
    				$openingqty = $line[4];
    				$openingcost = $line[5];
    				$qtyperpack = "0";
    				$remark = "";
    				$vartoday = date("Y-m-d H:i:s");
    				
    				if (!empty($matcode)) {   					
    					//check whether opening balance already exists in database with same code and ref
    					$prevQuery = "SELECT item_code FROM rawmat_tran WHERE tran_type = 'OPB' AND item_code ='".$matcode."' AND refno='".$refno."'";    					
    					$prevResult = mysql_query($prevQuery);
    					$num=mysql_numrows($prevResult);
    					if ($num>0) {    						
    						//update data
    						$upd_sql1 = "UPDATE rawmat_subcode SET cost_price = '$openingcost', modified_by = '$var_loginid', modified_on = '$vartoday' WHERE rm_code = '$matcode'";
							mysql_query($upd_sql1); 							
    					}else{
    						   						
    					$sqlcode = " select rm_code, description from rawmat_subcode where rm_code ='".mysql_real_escape_string($matcode)."'";
    					$sql_result = mysql_query($sqlcode);   					
    					while($row = mysql_fetch_array($sql_result))
    					{
    						$rmcode = $row['rm_code'];
    						$matdesc = mysql_real_escape_string($row['description']);
    					}
    					if ($rmcode != NULL) {
		    				$sqlchk = " select sysno from system_number ";
		    				$sqlchk.= " where type = 'opening'";   				 
		    				$dumsysno= mysql_query($sqlchk) or die(mysql_error());
		    				while($row = mysql_fetch_array($dumsysno))
		    				{
		    					$sysno = $row['sysno'];
		    				}
		    				if ($sysno ==NULL)
		    				{
		    					$sysno = '0';
		    					$sysno_sql = "INSERT INTO system_number values ('OPENING', '$sysno')";
		    				
		    					mysql_query($sysno_sql);
		    				
		    				}
		    				$newsysno = $sysno + 1;
		    						    				
		    				$opening_sysno  = str_pad($newsysno , 6, '0', STR_PAD_LEFT);
		    				$opening_sysno = "OPB".$opening_sysno;
		    				$remark = "";    				
		    				
		    				$prorevdte = $vartoday;
		    				
		    				$updsysno_sql = "UPDATE system_number SET sysno = '$newsysno' WHERE type = 'OPENING'";
		    				mysql_query($updsysno_sql);
		    				
		    				$sql = "INSERT INTO rawmat_opening values
		    					('$opening_sysno', '$refno','$prorevdte', '$remark', '$var_loginid', '$vartoday','$var_loginid', '$vartoday')";
		    				mysql_query($sql) or die("query 1 :".mysql_error());
	    				   	
							$sql = "INSERT INTO rawmat_opening_tran values
	    					('$opening_sysno', '1', '$matcode', '$matdesc', '$matuom','$openingqty','$qtyperpack', '$openingcost', '0')";
	    					mysql_query($sql) or die("query 2 :".mysql_error());
	    														
	    					$sql = "INSERT INTO rawmat_tran values
	    					('OPB', '$opening_sysno', '$refno','$remark', '$prorevdte', '$matcode', '$openingcost', '$matdesc', '$qtyperpack', '$openingqty','$var_loginid', '$vartoday','$var_loginid', '$vartoday')";
	    					mysql_query($sql) or die("query 3 :".mysql_error());
	    				    				
							$upd_sql = "UPDATE rawmat_subcode SET cost_price = '$openingcost', modified_by = '$var_loginid', modified_on = '$vartoday' WHERE rm_code = '$matcode'";
							mysql_query($upd_sql); 
	
    					}
    					}
    				}
    			}
    
    			//close opened csv file
    			fclose($csvFile);
    
    			$statusMsgClass = 'alert-success';
    			$statusMsg = 'Opening Balance has been inserted successfully.';
    		}else{
    			$statusMsgClass = 'alert-danger';
    			$statusMsg = 'Some problem occurred, please try again.';
    		}
    	}else{
    		$statusMsgClass = 'alert-danger';
    		$statusMsg = 'Please upload a valid CSV file.';
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

  <div class="contentc">


	<fieldset name="Group1" style=" width: 800px;" class="style2">
	 <legend class="title">RAW MAT OPENING LISTING</legend>
	  <br>
	 	<div class="container">
    <?php if(!empty($statusMsg)){
        echo '<div class="alert '.$statusMsgClass.'">'.$statusMsg.'</div>';
    } ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            Import Opening Balance
        </div>
        <div class="panel-body">
        	<table>
        		<tr>
        			<td width="30%">
            	<form action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>" method="post" enctype="multipart/form-data" id="importFrm">
                <input type="file" name="file" />
                <input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORT">
                </form>
                	</td>
                <td>
                <b>
                	Refno,Item Code,Description,UOM,On Hand Bal,Cost Price
            		<br>2016-01,AA001600101,J2299-SATIN PRINTING 60-BLACK,MTR,337.50,15.992</br>
            	</b>
            	</td>
            
            </tr>            
        </div>
    </div>
</div>

        <form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table>
		 <tr>
		  
           <td style="width: 1131px; height: 38px;" align="left">
           <?php
                $locatr = "rawmat_opening.php?menucd=".$var_menucode;
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
				
    	  	   $msgdel = "Are You Sure Delete Raw Mat opening List?";
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
          <th>Opening No.</th>
          <th style="width: 86px">Ref No.</th>
          <th>Opening Date</th>
          
         </tr>

         <tr>
          <th class="tabheader" style="width: 12px">#</th>
          <th class="tabheader" style="width: 139px">Opening No.</th>
           <th class="tabheader" style="width: 86px">Ref. No.</th>
          <th class="tabheader" style="width: 106px">Opening Date</th>
          <th class="tabheader" style="width: 12px">Detail</th>
          <th class="tabheader" style="width: 12px">Update</th>
		  <th class="tabheader" style="width: 12px">Delete</th>
         </tr>
         </thead>
		 <tbody>
		 <?php 
		    $sql = "SELECT distinct rm_opening_id, refno, openingdate, upd_by, upd_on ";
		    $sql .= " FROM rawmat_opening ";
    		$sql .= " ORDER BY rm_opening_id";   
			$rs_result = mysql_query($sql); 
			//echo $sql;

		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) { 
			
				$sql = "select prod_desc from pro_cd_master ";
        		$sql .= " where prod_code ='".$rowq['prod_code']."'";
        		$sql_result = mysql_query($sql);
        		$row2 = mysql_fetch_array($sql_result);
			
				$refno = htmlentities($rowq['refno']);
				$rm_opening_id = htmlentities($rowq['rm_opening_id']);
				//$label_number = htmlentities($rowq['label_number']);

				$showdte = date('d-m-Y', strtotime($rowq['upd_on']));
				$openingdate = date('d-m-Y', strtotime($rowq['openingdate']));
				$urlpop = 'upd_rm_opening.php';
				$urlvie = 'vm_rm_opening.php';
				echo '<tr bgcolor='.$defaultcolor.'>';
				
            	echo '<td>'.$numi.'</td>';
           		echo '<td>'.$rm_opening_id.'</td>';
           		echo '<td>'.$refno.'</td>';
            	//echo '<td align="center">'.$label_number.'</td>';
            	//echo '<td>'.$openingdate.'</td>';
            	//echo '<td>'.$rowq['upd_by'].'</td>';
            	echo '<td>'.$showdte.'</td>';
            
            	if ($var_accvie == 0){
            		echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlvie.'?rm_opening_id='.$rm_opening_id.'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
            	}
	            if ($var_accupd == 0){
		            echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
	            }else{
		            echo '<td align="center"><a href="'.$urlpop.'?rm_opening_id='.$rm_opening_id.'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
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
