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
   
    if ($_POST['Submit'] == "Approve") {
	 
     	if(!empty($_POST['salorno']) && is_array($_POST['salorno'])) 
     	{
           foreach($_POST['salorno'] as $key) {
             $defarr = explode(",", $key);
             $var_sale = $defarr[0];
             $var_buyer = $defarr[3];
             
			 $var_sql = " SELECT count(*) as cnt from salesappr";
	      	 $var_sql .= " Where sordno = '$var_sale' And sbuycd = '$var_buyer'";
	      	 $query_id = mysql_query($var_sql) or die ("Cant Check Sales Entry Approval Order No");
	      	 $res_id = mysql_fetch_object($query_id);
             
             $vartoday = date("Y-m-d H:i:s");
	      	 if ($res_id->cnt > 0 ){    			
			 	$sql  = "Update salesappr Set app_stat = 'APPROVE', modified_by = '$var_loginid', modified_on = '$vartoday',  sbuyrmk = 'RELEASED BY USER'";
             	$sql .=	" Where sordno ='".$var_sale."' And sbuycd='".$var_buyer."'";
                mysql_query($sql) or die(mysql_error()." 1");		
                
 	 
		   	 }else{
		   	 	$sql  = "Insert Into salesappr values";
             	$sql .=	" ('$var_sale','$vartoday','$var_loginid','APPROVE','$var_buyer', 'APPROVED BY USER')";
                mysql_query($sql) or die(mysql_error()." 2");	
		   	 }
		   	 
	   	 	//---- here to connect to mdf database -----//
			$var_server = '192.168.0.142:9909';
	        $var_userid = 'root';
	        $var_password = 'admin9002';
	        $var_db_name='mdf_fgood'; 
     
	 		$db_link2  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
  	 		mysql_select_db("$var_db_name")or die("cannot select DB ".$var_db_name.mysql_error());
  	 		
  	 		
  	 		$sql = "UPDATE po_master SET approved = 'Y' WHERE po_no = '$var_sale'"; 
			mysql_query($sql) or die ("Cant Update MDF PO : ".mysql_error());


	 		mysql_query("SET NAMES 'utf8'", $db_link2)or die(mysql_error()); 	//this will take effect both retrieve, update or insert data of utf-8 
	 		mysql_close ($db_link2); 
	 		//---- END connect to nl_db database -----//
		 		
		 		

		   }	 
		   $backloc = "../sales_tran/sales_appr.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";   
       }      
    }
    
   if ($_POST['Submit'] == "Release") {
     	if(!empty($_POST['salorno']) && is_array($_POST['salorno'])) 
     	{
           foreach($_POST['salorno'] as $key) {
             $defarr = explode(",", $key);
             $var_sale = $defarr[0];
             $var_buyer = $defarr[3];
             
			 $var_sql = " SELECT count(*) as cnt from salesappr";
	      	 $var_sql .= " Where sordno = '$var_sale' And sbuycd = '$var_buyer'";
	      	 $query_id = mysql_query($var_sql) or die ("Cant Check Sales Entry Approval Order No");
	      	 $res_id = mysql_fetch_object($query_id);
             
             $vartoday = date("Y-m-d H:i:s");
	      	 if ($res_id->cnt > 0 ){    			
			 	$sql  = "Update salesappr Set app_stat = 'PENDING', modified_by = '$var_loginid', modified_on = '$vartoday' ";
             	$sql .=	" Where sordno ='".$var_sale."' And sbuycd='".$var_buyer."'";
                mysql_query($sql) or die(mysql_error()." 1");		 	 
		   	 }else{
		   	 	$sql  = "Insert Into salesappr values";
             	$sql .=	" ('$var_sale','$vartoday','$var_loginid','PENDING','$var_buyer', 'RELEASED BY USER')";
                mysql_query($sql) or die(mysql_error()." 1");	
		   	 }
		   }	 
		   $backloc = "../sales_tran/sales_appr.php?stat=1&menucd=".$var_menucode;
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
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>

<script type="text/javascript"> 
$(document).ready(function() {
	$('#example').dataTable( {
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50,"All"]],
		"bStateSave": true,
		"bFilter": true,
		"sPaginationType": "full_numbers",
		"bAutoWidth":false
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
				     null
				   ]
		});	
} );
			
jQuery(function($) {
  
    $("tr :checkbox").live("click", function() {
        $(this).closest("tr").css("background-color", this.checked ? "#FFCC33" : "");
    });
  
});

var newwindow;
function poptastic(url)
{
	newwindow=window.open(url,'name','height=200,width=600,left=200,top=250');
	if (window.focus) {newwindow.focus()}
}
			
</script>
</head>
    <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>--> 

<body>
  <div class="contentc">


	<fieldset name="Group1" style=" width: 1143px;" class="style2">
	 <legend class="title">SALES FORM STATUS (APPROVE/RELEASE/REJECT) LISTING</legend>
	  <br>
	 
        <form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table>
		 <tr>
		  
           <td style="width: 1131px; height: 38px;" align="left">
           <?php
           		
                $msgdel = "Are You Sure Approve Selected Sales Entry?";
    	  	    if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Approve" class="butsub" style="width: 75px; height: 32px">';
  				}else{
   					echo '<input type=submit name = "Submit" value="Approve" class="butsub" style="width: 75px; height: 32px" onclick="return confirm(\''.$msgdel.'\')">';
  				}
  				
  				$msgdel = "Are You Sure Release Selected Sales Entry?";
    	  	    if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Release" class="butsub" style="width: 75px; height: 32px">';
  				}else{
   					echo '<input type=submit name = "Submit" value="Release" class="butsub" style="width: 75px; height: 32px" onclick="return confirm(\''.$msgdel.'\')">';
  				}
  			
    	      ?></td>
		 </tr>
		 </table>
		 <br>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         <thead>
           <tr>
          <th></th>
          <th style="width: 160px">Order No</th>
          <th style="width: 105px">Order Date</th>
          <th style="width: 110px">Exp Del Date</th>
          <th style="width: 72px">Buyer</th>
          <th style="width: 98px">Approve</th>
          <th></th>
          <th></th>
		  <th></th>
		  <th></th>
         </tr>

         <tr>
          <th class="tabheader" style="width: 12px">#</th>
          <th class="tabheader" style="width: 160px">Order No.</th>
          <th class="tabheader" style="width: 105px">Order Date</th>
          <th class="tabheader" style="width: 110px">Exp Delivery Date</th>
          <th class="tabheader" style="width: 72px">Buyer</th>
          <th class="tabheader" style="width: 98px">Approve</th>
          <th class="tabheader" style="width: 12px">Detail</th>
          <th class="tabheader" style="width: 12px">Approve</th>
		  <th class="tabheader" style="width: 12px">Release</th>
		  <th class="tabheader" style="width: 12px">Reject</th>
         </tr>
         </thead>
		 <tbody>
		 <?php 
		    $sql = "SELECT sordno, sorddte, sexpddte, sbuycd";
		    $sql .= " FROM salesentry";
    		$sql .= " ORDER BY modified_on desc";  
			$rs_result = mysql_query($sql); 
	
		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) { 
			
				$salorno = htmlentities($rowq['sordno']);
				$orddte = date('d-m-Y', strtotime($rowq['sorddte']));
				$expddte = date('d-m-Y', strtotime($rowq['sexpddte']));
				
				$sql1 = "select app_stat, modified_on, modified_by from salesappr ";
     			$sql1 .= " where sordno ='".$salorno."' And sbuycd='".$rowq['sbuycd']."'";
     			$sql_result1 = mysql_query($sql1);
     			$row1 = mysql_fetch_array($sql_result1);
     			$appstat  = $row1[0];
     			$applston = $row1[1];
     			$applstby = $row1[2];
     			if ($applston == ""){
     				$applston = "";
     			}else{	
     				$applston = date('d-m-Y', strtotime($applston));
				}
				
				$urlvie = 'vm_app_sales.php';
				$urlrejapp = 'rej_entry.php?sal='.$salorno."&bcd=".$rowq['sbuycd'].'&menucd='.$var_menucode;
				echo '<tr>';
            	echo '<td>'.$numi.'</td>';
           		echo '<td>'.$salorno.'</td>';
            	echo '<td>'.$orddte.'</td>';
            	echo '<td>'.$expddte.'</td>';
            	echo '<td>'.$rowq['sbuycd'].'</td>';
            	echo '<td>'.$appstat.'</td>';
            
            	if ($var_accvie == 0){
            		echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlvie.'?sorno='.$salorno.'&buycd='.$rowq['sbuycd'].'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
            	}
	            if ($var_accadd == 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="salorno[]" value="'.$values.'" />'.'</td>';
	            }else{
	              $values = implode(',', $rowq);	
	              echo '<td align="center"><input type="checkbox" name="salorno[]" value="'.$values.'" />'.'</td>';
    	        }
	            if ($var_accadd == 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="salorno[]" value="'.$values.'" />'.'</td>';
	            }else{
	              $values = implode(',', $rowq);	
	              echo '<td align="center"><input type="checkbox" name="salorno[]" value="'.$values.'" />'.'</td>';
    	        }
    	        
				if ($var_accadd == 0){
	              echo '<td align="center"><input type="checkbox" DISABLED  name="salorno[]" value="'.$values.'" />'.'</td>';
	            }else{
	              echo '<td align="center"><a href=javascript:poptastic("'.$urlrejapp.'")><img src="../images/deleterow.png" title="Reject This Sales Entry"></a>';'</td>';
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
