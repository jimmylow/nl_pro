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
     if(!empty($_POST['cutsheid']) && is_array($_POST['cutsheid'])) 
     {
           $custmoby= $var_loginid;
           $custmoon= date("Y-m-d");
           foreach($_POST['cutsheid'] as $value ) {
		    $sql = "Delete from prodcutmas ";
            $sql .= " WHERE cutno ='".$value."'";
 		 	mysql_query($sql) or die(mysql_error()); 
 		 	
 		 	$sql2 = "Delete from prodcutdet ";
            $sql2 .= " WHERE cutno ='".$value."'";
 		 	mysql_query($sql2) or die(mysql_error()); 

		   }

		   $backloc = "../prod/cut_sheet.php?menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";
      }      
    }
    
     if ($_POST['Submit'] == "Active") {
     if(!empty($_POST['cutsheid']) && is_array($_POST['cutsheid'])) 
     {
           $custmoby= $var_loginid;
           $custmoon= date("Y-m-d");
           foreach($_POST['cutsheid'] as $value ) {
		    $sql = "Update prodcutmas set status ='A',";
            $sql .= " modified_by='$custmoby',";
            $sql .= " modified_on='$custmoon' WHERE cutno ='".$value."'";
 
		 	mysql_query($sql) or die(mysql_error()); 
		   }
		   
		   $backloc = "../prod/cut_sheet.php?menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";
       }      
    }

    if ($_GET['p'] == "Print") {
 		$chno = htmlentities($_GET['c']);	    

       // Redirect browser
        $fname = "cutting_sheet.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&dbsel=".$varrpturldb."&usernm=".$var_loginid."&cu=".$chno;
        $dest .= urlencode(realpath($fname));
        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=800,width=1000,left=200,top=200');</script>";
        
        $backloc = "../prod/cut_sheet.php?menucd=".$var_menucode;
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
    			{ "sType": "uk_date" },
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
	 <legend class="title">CUTTING SHEET</legend>
	  <br>
	   
		 <form name="LstWrkMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
         <table>
		 <tr>
		      
           <td style="width: 931px; height: 38px;" align="left">
			   <?php
			    $locatr = "prod_cutsheet.php?menucd=".$var_menucode;
                if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Create" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Create" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}
			   $msgdel = "Are You Sure Delete Selected Cutting Sheet No?";
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
         	 <th style="width: 72px">Cutting Sheet No</th>
         	 <th style="width: 94px">Cutting Date</th>
         	 <th>Buyer</th>
         	 <th>Order No</th>
         	 <th></th>
         	 <th></th>
         	 <th></th>
         	 <th></th>
         	 <th></th>
         	</tr>

         	<tr>
         	 <th class="tabheader" style="width: 4px;">#</th>
         	 <th class="tabheader" style="width: 72px">Cutting Sheet No</th>
         	 <th class="tabheader" style="width: 94px">Cutting Date</th>
         	 <th class="tabheader" style="width: 70px">Buyer</th>
         	 <th class="tabheader" style="width: 70px">Order No</th>
         	 <th class="tabheader" style="width: 70px">Status</th>
         	 <th class="tabheader" style="width: 7px">Detail</th>
         	 <th class="tabheader" style="width: 7px">Print</th>
         	 <th class="tabheader" style="width: 7px">Update</th>
         	 <th class="tabheader" style="width: 7px">Delete</th>
         	</tr>
         </thead>
		 <tbody>
		 <?php 
		 	$sql = "SELECT distinct cutno, cutdte, buyno, ordno, grpno, status, prodcnum, colno ";
			$sql .= " FROM prodcutmas ";
   		    $sql .= " ORDER BY cutno";  
		    $rs_result = mysql_query($sql); 
		    //echo $sql;
		   			
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) {
				$cutsheno  = $row['cutno'];
				$cutshedte =  date('d-m-Y', strtotime($row['cutdte']));
				$buyerno   = $row['buyno'];
				$orderno   = htmlentities($row['ordno']);
				$grpno     = $row['grpno'];
				$stat      = $row['status'];
				$prodcnum  = $row['prodcnum'];
				$colno     = $row['colno'];
				$sizeno    = $row['sizeno'];
				
				if ($cutsheno=='1113PE033')
				{
				//echo $prodcnum . "</br>";
				//echo $colno     . "</br>";
				//echo $sizeno . "</br>";
				}
				
				$productcode = $prodcnum. "-". $colno. "-".$sizeno ;
				
				$sql = "select pro_buy_desc from pro_buy_master ";
        		$sql .= " where pro_buy_code ='$buyerno'";
        		$sql_result = mysql_query($sql);
        		$row2 = mysql_fetch_array($sql_result);
        		$buyerde = htmlentities($row2[0]);
        		
				$sql = "select grpde from wor_grpmas ";
        		$sql .= " where grpcd ='$grpno'";
        		$sql_result = mysql_query($sql);
        		$row2 = mysql_fetch_array($sql_result);
        		$grpnode = htmlentities($row2[0]);
        		
        		$chkact = 0;
				$query  = "SELECT count(*) FROM prodcutdone ";
  				$query .= " WHERE cutno = '$cutsheno'";
  				$result = mysql_query($query) or die(mysql_error());
  				$row2 = mysql_fetch_array($result);
  				$cnt = $row2[0];  
  				if ($cnt > 0){
  				  $chkact = 1;
  				}		  
			
				$urlpop = 'upd_cutsheet.php?c='.$cutsheno."&menucd=".$var_menucode;
				$urlprt = 'cut_sheet.php?p=Print&c='.$cutsheno."&menucd=".$var_menucode;
				$urlvm = 'vm_cutsheet.php?c='.$cutsheno."&menucd=".$var_menucode;
				echo '<tr>';
            	echo '<td>'.$numi.'</td>';
            	echo '<td>'.$cutsheno.'</td>';
            	echo '<td>'.$cutshedte.'</td>';
            	echo '<td title="'.$buyerde.'">'.$buyerno.'</td>';
            	echo '<td>'.$orderno.'</td>';
            	//echo '<td title="'.$grpnode.'">'.$grpno.'</td>';
				echo '<td>'.$stat.'</td>';
				
				if ($var_accvie == 0){
            		echo '<td align="center" title="You Are Not Authorice View The Detail"><a href="#">[VIEW]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlvm.'">[VIEW]</a>';'</td>';
            	}
            	
            	if ($var_accvie == 0){
            		echo '<td align="center" title="You Are Not Authorice Print The Detail"><a href="#"><img src="../images/b_print.png" border="0" width="16" height="16" hspace="2" alt="Print Cutting Sheet" /></a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlprt.'" title="Print Cutting Sheet"> <img src="../images/b_print.png" border="0" width="16" height="16" hspace="2" alt="Print Cutting Sheet P/O" /></a>';'</td>';
            	}
			
				if ($var_accupd == 0){
           		    echo '<td align="center" title="You Are Not Authorice Update The Detail"><a href="#">[EDIT]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlpop.'">[EDIT]</a>';'</td>';
            	}
				
				if ($var_accupd == 0){
			 		echo '<td align="center"><input type="checkbox" DISABLED name="cutsheid[]" value="'.$cutsheno.'" />'.'</td>';
				}else{
					if ($chkact == 1){
					echo '<td align="center"><input type="checkbox" DISABLED name="cutsheid[]" value="'.$cutsheno.'" title="This Cutting No Has Been Update In Cutting Job Done"/>'.'</td>'; 
             		}else{
             		echo '<td align="center"><input type="checkbox" name="cutsheid[]" value="'.$cutsheno.'" />'.'</td>';
					}
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
