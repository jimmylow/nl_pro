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
    	if(!empty($_POST['barno']) && is_array($_POST['barno'])) 
     	{ 
           foreach($_POST['barno'] as $value ) {
		    	$sql = "DELETE FROM prodcutdone WHERE barcodeno ='".$value."'"; 
		 		mysql_query($sql); 
		   }
		   $backloc = "../prod/cut_jobdone.php?menucd=".$var_menucode;
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
    			{ "sType": "uk_date" },
    			null,
    			null,
    			{ "sType": "uk_date" },
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
  
  <div class="contentc">

	<fieldset name="Group1" style=" width: 951px;" class="style2">
	 <legend class="title">CUTTING JOB DONE</legend>
	  <br>
	   
		 <form name="LstWrkMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
         <table>
		 <tr>
		      
           <td style="width: 931px; height: 38px;" align="left">
			   <?php
			    $locatr = "prod_cutdone.php?menucd=".$var_menucode;
                if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Create" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Create" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}
    	  	   $msgdel = "Are You Sure Delete Selected Cutting Sheet No From Completed?";
    	  	   include("../Setting/btndelete.php");  	       
		       ?>
            </td>
		 </tr>
		 </table>
		 <br>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" style="width: 99%">
         <thead>
         	<tr>
         	 <th></th>
         	 <th>Barcode</th>
         	 <th>Cutting Sheet No</th>
         	 <th>Cutting Date</th>
         	 <th>Buyer</th>
         	 <th>Order No</th>
         	 <th>Complete Date</th>
         	 <th></th>
         	 <th></th>
         	 <th></th>
         	</tr>

         	<tr>
         	 <th class="tabheader" style="width: 4px;">#</th>
         	 <th class="tabheader" style="width: 72px">Barcode</th>
         	 <th class="tabheader" style="width: 72px">Cutting Sheet No</th>
         	 <th class="tabheader" style="width: 94px">Cutting Date</th>
         	 <th class="tabheader" style="width: 70px">Buyer</th>
         	 <th class="tabheader" style="width: 70px">Order No</th>
         	 <th class="tabheader" style="width: 70px">Complete Date</th>
         	 <th class="tabheader" style="width: 7px">Detail</th>
         	 <th class="tabheader" style="width: 7px">Update</th>
         	 <th class="tabheader" style="width: 7px">Delete</th>
         	</tr>
         </thead>
		 <tbody>
		 <?php 
		 	$sql = "SELECT distinct barcodeno, cutno, cutdate, buyno, orderno, donedte ";
			$sql .= " FROM prodcutdone";
   		    $sql .= " ORDER BY cutno"; 
		    $rs_result = mysql_query($sql); 
		   			
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) {
				$barno     = $row['barcodeno'];
				$cutsheno  = $row['cutno'];
				$cutshedte =  date('d-m-Y', strtotime($row['cutdate']));
				$buyerno   = $row['buyno'];
				$orderno   = htmlentities($row['orderno']);
				$donedte   =  date('d-m-Y', strtotime($row['donedte']));
				
				$sql = "select pro_buy_desc from pro_buy_master ";
        		$sql .= " where pro_buy_code ='$buyerno'";
        		$sql_result = mysql_query($sql);
        		$row2 = mysql_fetch_array($sql_result);
        		$buyerde = htmlentities($row2[0]);  
			
				$urlpop = 'upd_cutdone.php?c='.$cutsheno."&b=".$barno."&menucd=".$var_menucode;
				$urlvm = 'vm_cutdone.php?c='.$cutsheno."&b=".$barno."&menucd=".$var_menucode;
				echo '<tr>';
            	echo '<td>'.$numi.'</td>';
            	echo '<td>'.$barno.'</td>';
            	echo '<td>'.$cutsheno.'</td>';
            	echo '<td>'.$cutshedte.'</td>';
            	echo '<td title="'.$buyerde.'">'.$buyerno.'</td>';
            	echo '<td>'.$orderno.'</td>';
            	echo '<td>'.$donedte.'</td>';
				
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
						
				if ($var_accdel == 0){
            		echo '<td align="center"><input type="checkbox" DISABLED name="barno[]" value="'.$barno.'" />'.'</td>';
            	}else{
            		echo '<td align="center"><input type="checkbox" name="barno[]" value="'.$barno.'" />'.'</td>';
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
