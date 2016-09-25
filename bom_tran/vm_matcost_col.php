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
      $var_costinno = $_GET['costn'];
      include("../Setting/ChqAuth.php");

    }
    
     if (isset($_POST['Submit'])){ 
     	if ($_POST['Submit'] == "Print") {
           $var_costinno = $_POST['costinno'];
            
   		   // Redirect browser
       		$fname = "planrpt.rptdesign&__title=myReport"; 
       		$dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&costn=".$var_costinno."&usernm=".$var_loginid."&dbsel=".$varrpturldb;
        	$dest .= urlencode(realpath($fname));
        
        	//header("Location: $dest" );
        	echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        	$backloc = "../bom_tran/vm_matcost.php?costn=".$var_costinno."&menucd=".$var_menucode;
        	
      	 	echo "<script>";
      	 	echo 'location.replace("'.$backloc.'")';
      	  echo "</script>"; 

     }
    } 

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">


<style media="all" type="text/css">
@import "../css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css";
@import "../css/styles.css";

.style2 {
	margin-right: 53px;
}
</style>

<!-- jQuery libs -->
<script type="text/javascript" src="../js/multitable/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>

<!-- Our jQuery Script to make everything work -->

<script type="text/javascript"> 
function poptastic(url)
{
	var newwindow;
	newwindow=window.open(url,'name','height=800,width=800,left=30,top=200, scrollbars=yes');
	if (window.focus) {newwindow.focus()}
}

</script>
</head>
<?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>-->
   
<?php
  	 $sql = "select remark, docdate, planopt, create_by, create_on, modified_by, modified_on  from costing_mat ";
     $sql .= " where costingno ='".$var_costinno."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);
     $planrmk = $row['remark'];
     $plandte = $row['docdate'];
     $plandte = date('d-m-Y', strtotime($plandte));
     $planopt = $row['planopt'];
     $modified_by = $row['modified_by'];
     $modified_on = date("d-m-Y", strtotime($row['create_on']));              
     $create_by = $row['create_by'];
     $creation_time = date("d-m-Y", strtotime($row['modified_on']));
	
     $sql  = "select distinct sordno from costing_matord ";
     $sql .= " where costingno ='".$var_costinno."'";
     $sql .= " order by sordno";  
     $sql_result = mysql_query($sql) or die(mysql_error);
     
     $lblorder = "";
     while($row = mysql_fetch_assoc($sql_result)){
       if ($row['sordno'] != ""){ 
        if ($lblorder == ""){
    	    $lblorder = $row['sordno'];
        }else{  
			$lblorder = $lblorder.", ".$row['sordno'];		
		}
	   }			 
	 } 
	 
	 if($planopt == "C"){
		$ploptdesc = "Color";
	}else{
		$ploptdesc = "Product Code";
	}	
?>
<body>
 
  <div class ="contentc">
     <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
	<fieldset name="Group1" style=" width: 970px;" class="style2">
	 <legend class="title">PLANNING DETAIL <?php echo  $var_costinno; ?></legend>
	 
	  <br>	 
	  	  <table>
	  	  	<tr>
	  	  		<td></td>
	  	  		<td>Costing No</td>
	  	  		<td>:</td>
	  	  		<td><input type="text" name="costinno" id="costinno" value="<?php echo $var_costinno; ?>" readonly="readonly"></td>
	  	  	</tr>
	  	  	<tr>
	  	  		<td></td>
	  	  	</tr>
	  	    <tr>
	  	    	<td></td>
	  	    	<td style="width: 154px">Remark</td>
	  	    	<td>:</td>
	  	    	<td style="width: 311px">
				<input class="inputtxt" name="mplandte" id ="mplandte" type="text" readonly="readonly" style="width: 400px;" value="<?php  echo $planrmk; ?>"></td>
	  	    	<td style="width: 30px"></td>
	  	    	<td style="width: 84px">Doc. Date</td>
	  	    	<td style="width: 20px">:</td>
	  	    	<td style="width: 100px">
				<input name="mplandte" id ="mplandte" type="text" readonly="readonly" value="<?php  echo $plandte; ?>"></td>
	  	    </tr>
	  	    <tr>
	  	    	<td></td>
	  	    	<td style="width: 154px">Order No Include</td>
	  	    	<td>:</td>
	  	    	<td colspan="5">
	  	    	<label><?php echo $lblorder;?></label>
	  	    	</td>
	  	    </tr>
	  	    <tr>
	  	    	<td></td>
	  	    	<td>Planning By</td>
	  	    	<td>:</td>
	  	    	<td><?php echo $ploptdesc; ?></td>
	  	    </tr>
	  	    <tr><td></td></tr>
	  	    <tr>
	  	    	<td></td>
	  	    	<td>Create By</td>
	  	    	<td>:</td>
	  	    	<td><input class="textnoentry1" name="create_by" id ="create_byid" type="text" readonly="readonly" style="width: 150px" value="<?php echo $create_by;?>"></td>
	  	    	<td></td>
	  	    	<td>Create On</td>
	  	    	<td>:</td>
	  	    	<td><input class="textnoentry1" name="creation_time" id ="creation_timeid" type="text" readonly="readonly" style="width: 160px" value="<?php echo $creation_time;?>"></td>
	  	    </tr>
	  	    <tr><td></td></tr>
	  	    <tr>
	  	    	<td></td>
	  	    	<td>Modified By</td>
	  	    	<td>:</td>
	  	    	<td><input class="textnoentry1" name="modified_by" id ="modified_byid" type="text" readonly="readonly" style="width: 150px" value="<?php echo $modified_by;?>"></td>
	  	    	<td></td>
	  	    	<td>Modified On</td>
	  	    	<td>:</td>
	  	    	<td><input class="textnoentry1" name="modified_on" id ="suppstateid0" type="text" readonly="readonly" style="width: 160px" value="<?php echo $modified_on; ?>"></td>
	  	    </tr>
	  	  </table>	
	  	  <br>
		  <table id="itemsTable" class="general-table" style="width: 967px; height: 46px;">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader">Raw Material</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Consumption</th>
              <th class="tabheader">Purchased</th>
              <th class="tabheader">Booking Qty</th>
              <th class="tabheader">Detail</th>  
             </tr>
            </thead>
            <tbody>
            
            <?php
          		$var_sql  = " SELECT rm_code, rm_desc, rm_uom, sum(sum_comp)";
          		$var_sql .= " from costing_matdet";
	      		$var_sql .= " Where costingno = '$var_costinno'";
	      		$var_sql .= " group by 1, 2, 3 ";
	      		$var_sql .= " order by seqno";
	      		$rs_result = mysql_query($var_sql); 
	      		
	      	    $totcomps = 0;
	      	    $i = 1;
	      		while($row = mysql_fetch_assoc($rs_result)){
	      		    $itmcode  = htmlentities($row['rm_code']);
	      		    $itmdesc  = htmlentities($row['rm_desc']);
	      		    $itmuom   = $row['rm_uom'];
	      		    $itmscomp = $row['sum(sum_comp)'];
	      		    
	      		    $sql = "select sum(plpurqty), sum(plbkqty) from costing_purbook ";
     				$sql .= " where costing_no ='".$var_costinno."' And itmcode = '$itmcode'";
     				$sql_result = mysql_query($sql);
     				$row = mysql_fetch_array($sql_result);
     				$itmspur = number_format($row[0], 2, ".",",");
     				$itmsbok = number_format($row[1], 2, ".",",");
	      		    
	      		    $urlviwcol = "detplanpro.php?pr=".$itmcode."&cs=".$var_costinno;
	      		    	      		    
	      			echo '<tr class="item-row">';
             		echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 15px; border:0;"></td>';
             		echo '<td><input name="planmat[]" value="'.$itmcode.'"  id="planmat'.$i.'" readonly="readonly" style="width: 80px; border:0;"></td>';
             		echo '<td><input name="plandes[]" value="'.$itmdesc.'"  id="plandes'.$i.'" readonly="readonly" style="width: 200px; border:0;"></td>';
             		echo '<td><input name="planuom[]" value="'.$itmuom.'"   id="planuom'.$i.'" readonly="readonly" style="border: 0; width: 30px;"></td>';
             		echo '<td><input name="plantco[]" value="'.$itmscomp.'" id="plantco'.$i.'" readonly="readonly" style="width: 80px; border:0; text-align:right;"></td>';
             		echo '<td><input name="planpur[]" value="'.$itmspur.'"  id="planpur'.$i.'" readonly="readonly" style="width: 80px; border:0; text-align:right;"></td>';
             	    echo '<td><input name="planbok[]" value="'.$itmsbok.'"  id="planbok'.$i.'" readonly="readonly" style="width: 80px; border:0; text-align:right;""></td>';
             	    echo '<td style="text-align:center;"><a href="javascript:poptastic(\''.$urlviwcol.'\')" title="RM Planning Detail">[DETAIL]</a></td>';
             		echo '</tr>';
             		$totcomps = $totcomps + $itmscomp;
             		$i = $i + 1;	
				}      
				$totcomps = number_format($totcomps, 4, '.', '');      
         	?>
         </tbody>
        </table>
           <table style="width: 967px">
         	<tr>
         		<td style="width: 24px; height: 25px;"></td>
         		<td style="width: 227px; height: 25px;"></td>
         		<td style="width: 330px; height: 25px;"></td>
         		<td align="left" style="width: 71px; height: 25px;">Total</td>
         		<td style="height: 25px">
				<input readonly="readonly" class="textnoentry1" type="text" id="totcompid" value="<?php echo $totcomps;?>" style="width: 100px; text-align:right">
				</td>
         	</tr>
		   </table>	
    
	 
	   <table>
	  	<tr>
			<td style="width: 1150px; height: 22px;" align="center">
			<?php
				 $locatr = "m_mat_plan.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				 include("../Setting/btnprint.php"); 
				?>
				</td>
			</tr>
	  </table>
	  
  </fieldset>
  </form>
  </div>
  <div class="spacer"></div>
</body>
</html>
