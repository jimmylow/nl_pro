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
        
    if ($_POST['Submit'] == "Deactive") {
     if(!empty($_POST['wrkid']) && is_array($_POST['wrkid'])) 
     {
           $custmoby= $var_loginid;
           $custmoon= date("Y-m-d");
           foreach($_POST['wrkid'] as $value ) {
		    $sql = "Update wor_detmas set status ='D',";
            $sql .= " modified_by='$custmoby',";
            $sql .= " modified_on='$custmoon' WHERE workid ='".$value."'";
 		 	mysql_query($sql); 
		   }

		   $backloc = "../workefile/wo_m_wrkdet.php?menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";
      }      
    }
    
     if ($_POST['Submit'] == "Active") {
     if(!empty($_POST['wrkid']) && is_array($_POST['wrkid'])) 
     {
           $custmoby= $var_loginid;
           $custmoon= date("Y-m-d");
           foreach($_POST['wrkid'] as $value ) {
		    $sql = "Update wor_detmas set status ='A',";
            $sql .= " modified_by='$custmoby',";
            $sql .= " modified_on='$custmoon' WHERE workid ='".$value."'";
 
		 	mysql_query($sql); 
		   }
		   $backloc = "../workefile/wo_m_wrkdet.php?menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";
       }      
    }

	if ($_POST['Submit'] == "Delete") {
     if(!empty($_POST['wrkid']) && is_array($_POST['wrkid'])) 
     {
           foreach($_POST['wrkid'] as $value ) {
		    $sql = "Delete From wor_detmas WHERE workid ='".$value."'";
 
		 	mysql_query($sql); 
		   }
		   $backloc = "../workefile/wo_m_wrkdet.php?menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";
       }      
    }


    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {

       // Redirect browser
        $fname = "wrkid_rpt.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&dbsel=".$varrpturldb."&usernm=".$var_loginid;
        $dest .= urlencode(realpath($fname));
        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=800,width=1000,left=200,top=200');</script>";
     }
    } 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<style media="all" type="text/css">
@import "../css/styles.css";
@import "../css/demo_table.css";
thead th input { width: 90% }

.style2 {
	margin-right: 0px;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>

<script type="text/javascript" charset="utf-8"> 

$(document).ready(function() {
		$('#example').dataTable( {
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50,"All"]],
			"bStateSave": true,
			"bFilter": true,
			"bAutoWitdh": false,
			"sPaginationType": "full_numbers",
				"aoColumns": [
    			null,
    			null,
    			null,
    			null,
    			null,
    			null,
    			{ "bSortable": false },
    			{ "bSortable": false },
    			{ "bSortable": false },
    			{ "bSortable": false }
    			]
		} )
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

	<fieldset name="Group1" style=" width: 951px;" class="style2">
	 <legend class="title">WORKER MASTER</legend>
	  <br>
	   
		 <form name="LstWrkMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
         <table>
		 <tr>
		      
           <td style="width: 931px; height: 38px;" align="left">
			   <?php
			    $locatr = "wo_wrkdet.php?menucd=".$var_menucode;
                if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Create" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Create" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}
    	  	   $msgdel = "Are You Sure Active Selected Worker ID?";
    	  	   include("../Setting/btnactive.php"); 
			   $msgdel = "Are You Sure Deactive Selected Worker ID?";
    	  	   include("../Setting/btndeactive.php"); 
    	  	   $msgdel = "Are You Sure Delete Selected Worker ID?";
    	  	   include("../Setting/btndelete.php"); 
    	  	   include("../Setting/btnprint.php");   	  	       
		       ?>
            </td>
		 </tr>
		 </table>
		 <br>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" style="width: 99%">
         <thead>
         	<tr>
         	 <th></th>
         	 <th>Worker ID</th>
         	 <th>Worker Name</th>
         	 <th>Dept</th>
         	 <th>Group</th>
         	 <th>Status</th>
         	 <th></th>
         	 <th></th>
         	 <th></th>
         	 <th></th>
         	</tr>

         	<tr>
         	 <th class="tabheader" style="width: 4px;">#</th>
         	 <th class="tabheader" style="width: 30px">Worker ID</th>
         	 <th class="tabheader" style="width: 200px">Worker Name</th>
         	 <th class="tabheader" style="width: 80px">Dept</th>
         	 <th class="tabheader" style="width: 80px">Group</th>
         	 <th class="tabheader" style="width: 80px">Status</th>
         	 <th class="tabheader" style="width: 8px">Detail</th>
         	 <th class="tabheader" style="width: 8px">Update</th>
         	 <th class="tabheader" style="width: 8px">Status</th>
         	 <th class="tabheader" style="width: 8px">Delete</th>
         	</tr>
         </thead>
		 <tbody>
		 <?php 
		 	$sql = "SELECT * ";
			$sql .= " FROM wor_detmas";
   		    $sql .= " ORDER BY workid";  
		    $rs_result = mysql_query($sql); 
		   			
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) {
				$wrkid     = $row['workid'];
				$wrknm     = htmlentities($row['workname']);
				$wrkdept   = $row['deptcd'];
				$wrkdeptde = htmlentities($row['deptde']);
				$wrkgrp    = $row['grpcd'];
				$wrkgrpde  = htmlentities($row['grpde']);
				$status    = $row['status'];
				
				switch ($status){
				case "A":
					$statde = "ACTIVE";
					break;
				case "Z":
					$statde = "DEACTIVE";
					break;
				}		  
			
				$urlpop = 'upd_wrkdet.php?w='.$wrkid."&menucd=".$var_menucode;
				$urlvm = 'vm_wrkdet.php?w='.$wrkid."&menucd=".$var_menucode;
				echo '<tr>';
            	echo '<td>'.$numi.'</td>';
            	echo '<td>'.$wrkid.'</td>';
            	echo '<td>'.$wrknm.'</td>';
            	echo '<td title="'.$wrkdeptde.'">'.$wrkdept.'</td>';
            	echo '<td title="'.$wrkgrpde.'">'.$wrkgrp.'</td>';
            	echo '<td title="'.$statde.'">'.$status.'</td>';
			
				if ($var_accvie == 0){
            		echo '<td align="center" title="You Are Not Authorice View The Detail"><a href="#">[VIEW]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlvm.'">[VIEW]</a>';'</td>';
            	}
			
				if ($var_accupd == 0){
           		    echo '<td align="center" title="You Are Not Authorice Update The Detail"><a href="#">[EDIT]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlpop.'">[EDIT]</a>';'</td>';
            	}
			
				if ($var_accupd == 0){
			   		echo '<td align="center"><input type="checkbox" DISABLED name="wrkid[]" value="'.$wrkid.'" />'.'</td>';	
            	}else{
               		echo '<td align="center"><input type="checkbox" name="wrkid[]" value="'.$wrkid.'" />'.'</td>';
            	}  
            
				if ($var_accdel == 0){
			 		echo '<td align="center"><input type="checkbox" DISABLED name="wrkid[]" value="'.$wrkid.'" />'.'</td>';
				}else{ 
             		echo '<td align="center"><input type="checkbox" name="wrkid[]" value="'.$wrkid.'" />'.'</td>';
				}       
            
            	echo '</tr>';
            	$numi = $numi + 1;
			}
		 ?>
		 </tbody>
		 </table>
		 
		 <div class="spacer" style="width: 937px"></div>
         </form>
	 
	</fieldset>
	</div>
</body>

</html>
