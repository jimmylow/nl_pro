<?php

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	$var_loginid = $_SESSION['sid'];
	    
    if($var_loginid == "") { 
      echo "<script>";   
      echo "alert('Not Log In to the system');"; 
      echo "</script>"; 

      echo "<script>";
      echo 'top.location.href = "../login.htm"';
      echo "</script>";
    } else {
      $var_stat = $_GET['stat'];
      $var_prodcode = htmlentities($_GET['procd']);
      $var_menucode = $_GET['menucd'];    
    }
    
   if ($_POST['Submit'] == "Get" && !empty($_POST['prod_code'])) {
    	$var_prodcode= $_POST['prod_code'];
    }
    
    if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menudcode'];
         $backloc = "../bom_master/projob_rate1.php?menucd=".$var_menucode;
        
 		 echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }
    
    if ($_POST['Submit'] == "Update to Costing") {
         $var_menucode  = $_POST['menudcode'];
         $prcode  = $_POST['prod_code'];
         $tlabcst = $_POST['totlab'];
         
         echo "<script>";
         echo "alert('$prcode');";
         echo "</script>"; 
         
         $query  = "SELECT count(*) FROM prod_matmain "; 
		 $query .= " WHERE prod_code='$prcode'";
		 $result=mysql_query($query);
		 $row = mysql_fetch_array($result);
		 $cnt = $row[0];
		 if ($cnt == ""){$cnt = 0;}
		 
		 if ($cnt == 0){
		 	echo "<script>";   
      		echo "alert('Product Costing For This Product Not Found; No Update.');"; 
      		echo "</script>";				 
	  	 }else{
	  	 	$query  = "SELECT rmcost, overcost, totmscb FROM prod_matmain "; 
		 	$query .= " WHERE prod_code='$prcode'";
		 	$result=mysql_query($query);
		 	$row = mysql_fetch_array($result);
		 	$rcst = $row[0];
		 	$ocst = $row[1];
		 	$mcst = $row[2];
		 	if ($rcst == "" or empty($rcst)){$rcst = 0;}
		 	if ($ocst == "" or empty($ocst)){$ocst = 0;}
			if ($mcst == "" or empty($mcst)){$mcst = 0;}
	  	 	
	  	 	if ($tlabcst == "" or empty($tlabcst)){$tlabcst = 0;}
	  	 	$tcst = $rcst + $ocst + $mcst + $tlabcst;
	  	 	$query  = "Update prod_matmain set labcost = '$tlabcst', ";
	  	 	$query .= "                   	   totcost = '$tcst'";      
		 	$query .= " WHERE prod_code='$prcode'";
		 	$result=mysql_query($query) or die(mysql_error());
	  	 
	  	 	echo "<script>";   
      		echo "alert('Update Success To Product Costing For This Product. ".$prcode."');"; 
      		echo "</script>";	  
		 }
     	 $backloc = "../bom_master/vm_projob_rate.php?menucd=".$var_menucode."&procd=".$prcode;
        
 		 echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
	
    }   
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<link rel="stylesheet" href="../css/lightbox.css" type="text/css" media="screen">	
<style media="all" type="text/css">
@import "../css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css";
@import "../css/styles.css";
.style2 {
	margin-right: 0px;
}
</style>

<!-- jQuery libs -->
<script type="text/javascript" src="../js/datetimepicker_css.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>
<script type="text/javascript" src="../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../js/Parsers.js"></script>
<script type="text/javascript" src="../js/InputMask.js"></script>


<?php
	include("../Setting/jquery_script.php");
?>

</head>
<?php include("../topbarm.php"); ?> 

<body>
	<?php
		if (!empty($_POST['prod_code'])) {
	        $sql = "select * ";
	        $sql .= " from pro_cd_master";
	        $sql .= " where prod_code ='".$var_prodcode."'";
	        $sql_result = mysql_query($sql);
	        $row = mysql_fetch_array($sql_result);
	        $num=mysql_numrows($sql_result);
	        if ($num==0) {
	        	echo "<script>";
	        	echo "alert('Product Code ".$var_prodcode. " not exist at Product Costing Details!')";
	        	echo "</script>";
	        }
		}
        $prodbuyer = $row[1];
        $prodtype = $row[2];
        $prodcat = $row[14];
        $prodsiz = $row[4];
        $prodcol = $row[5];
        $proddesc = $row[6];
        $produom = $row[7];
        $prodimg = $row[9];
        $create_by = $row[10];
        $creation_time = date("d-m-Y", strtotime($row[11]));
       
               
        $dirimg = "../bom_master/procdimg/";
        $imgname = $dirimg.$prodimg;
        
        $sqla  = " select distinct modified_by, modified_on from pro_jobmodel";
        $sqla .= "  where prod_code = '$var_prodcode'";
        $sql_resulta = mysql_query($sqla);
        $rowa = mysql_fetch_array($sql_resulta);
        $modified_by = $rowa[0];
        $modified_on = date("d-m-Y", strtotime($rowa[1]));
?>		
  
  <div class="contentc">
	<fieldset name="Group1" style="width: 708px; height: 655px">
	 <legend class="title">PRODUCT JOB PAY RATE - PRODUCT CODE <?php echo $var_prodcode;?></legend>
	  <br>
	 	<form name="InpProCDMasV" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" enctype="multipart/form-data" style="height: 230px; width: 697px;">
		<input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">

		<table style="width: 687px" >
	  	  <tr>
	  	    <td style="height: 28px; width: 7px;"></td>
	  	    <td style="width: 113px;">Product Code</td>
	  	    <td style="width: 10px">:</td>
	  	    <td style="width: 197px;">
				<input class="autosearch" name="prod_code" id="prod_code" type="text" style="width: 129px" value="<?php echo $var_prodcode;?>">
				<input type=submit name = "Submit" value="Get" class="butsub" style="width: 60px; height: 32px" >
			</td>
			<td></td>
			<td colspan="3" rowspan="6">
			<a href="<?php echo $imgname; ?>" rel="lightbox">
			<img id="proimgpre" height="120" width="150" src="<?php echo $imgname; ?>">
			</a>
			</td>
	  	  </tr>
	  	  <tr>
	  	   <td style="width: 7px"></td>
	  	   <td style="width: 113px"></td>
	  	   <td style="width: 10px"></td>
	  	   <td style="width: 197px"><div id="msgcd"></div></td>
	  	  </tr>
	  	   <tr>
	  	   <td style="width: 7px"></td>
	  	   <td style="width: 113px">Description</td>
	  	   <td style="width: 10px">:</td>
	  	   <td style="width: 197px">
   			<input class="inputtxt" readonly="readonly" name="procddesc" id ="procddescid" type="text" style="width: 363px;" value="<?php echo $proddesc; ?>"></td>
	  	   <td style="width: 8px"></td>
	  	  </tr>
	  	   <tr>
	  	   <td style="width: 7px"></td>
	  	   <td style="width: 113px">Create By</td>
	  	   <td style="width: 10px">:</td>
	  	   <td style="width: 197px">
			<input class="textnoentry1" name="create_by" id ="create_byid" type="text" readonly="readonly" style="width: 150px" value="<?php echo $create_by;?>"></td>
	  	   <td style="width: 8px"></td>
	  	  </tr>
	  	   <tr>
	  	   <td style="width: 7px"></td>
	  	   <td style="width: 113px">Create On</td>
	  	   <td style="width: 10px">:</td>
	  	   <td style="width: 197px">
		   <input class="textnoentry1" name="creation_time" id ="creation_timeid" type="text" readonly="readonly" style="width: 150px" value="<?php echo $creation_time;?>"></td>
	  	   <td style="width: 8px"></td>
	  	  </tr>
	  	  <tr>
	  	   <td style="width: 7px"></td>
	  	   <td style="width: 113px">Modified By&nbsp;&nbsp;</td>
	  	   <td style="width: 10px">:</td>
	  	   <td style="width: 197px">
	  	    <input class="textnoentry1" name="modified_by" id ="modified_byid" type="text" readonly="readonly" style="width: 150px" value="<?php echo $modified_by;?>"></td>
	  	   <td style="width: 8px"></td>
	  	  </tr>
	  	   <tr>
	  	   <td style="width: 7px"></td>
	  	   <td style="width: 113px">Modified On&nbsp;&nbsp;</td>
	  	   <td style="width: 10px">:</td>
	  	   <td style="width: 197px">
	  	     <input class="textnoentry1" name="modified_on" id ="suppstateid0" type="text" readonly="readonly" style="width: 150px" value="<?php echo $modified_on; ?>"></td>
	  	   <td style="width: 8px"></td>
	  	  </tr>

	  	</table>
		 <table cellpadding="0" cellspacing="0" border id="example" width="690px">
         <thead><tr>
          <th class="tabheader" style="width: 30px">Sec</th>
          <th class="tabheader" style="width: 50px;">Job ID</th>
          <th class="tabheader" style="width: 290px;">Job Desciption</th>
          <th class="tabheader" style="width: 91px;">Rate</th>
          <th class="tabheader" style="width: 80px">Date Modified</th>
         </tr></thead>
		 <tbody>
		 <?php 
		    $sql = "SELECT prod_jobid, prod_jobdesc, prod_jobrate, prod_jobsec, prod_jobdame ";
		    $sql .= " FROM pro_jobmodel Where prod_code ='".$var_prodcode."'";
    		$sql .= " ORDER BY prod_jobseq";  
			$rs_result = mysql_query($sql); 
		 
		    $numi = 1;
			$vartotal = 0;
			while ($rowq = mysql_fetch_assoc($rs_result)) {
				
				$dteame = "";
				if (empty($rowq['prod_jobdame'])){
					$dteame = "";
				}else{	
					$dteame = date('d-m-Y', strtotime($rowq['prod_jobdame'])); 
				}
	
				$var_jofdesc = str_replace("^", "'", $rowq['prod_jobdesc']);
				echo '<tr height="25"; bgcolor='.$defaultcolor.'>';
				echo '<td>'.$rowq['prod_jobsec'].'</td>';
            	echo '<td>'.$rowq['prod_jobid'].'</td>';						
            	echo '<td>'.$var_jofdesc.'</td>';
            	echo '<td>'.$rowq['prod_jobrate'].'</td>';
            	echo '<td>'.$dteame.'</td>';
            	echo '</tr>';
            	
            	$vartotal = $vartotal + $rowq['prod_jobrate'];         
            	$numi = $numi + 1;
			}
		 ?>
		 </tbody>
		 </table>
		<br/> 
		<span style="font-size: small;">Total Job Rate : <span style="color:#FF0000">
			<input id="totlab" name="totlab" type="text" value="<?php echo $vartotal; ?>" style="color:red" readonly="readonly">
			</span>
		</span>
		<br/><br/>
	    <table>
  	 	<tr>
		<td align="center" style="width: 700px">
	  		<input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px" title="Back To Previous Screen">
	  		<input type=submit name = "Submit" value="Update to Costing" class="butsub" style="width: 130px; height: 32px" title="Update Product Job Rate To Product Costin">
	  	</td>
        </tr>
	  	</table>
	   </form>	
	   </fieldset>
	   </div>
</body>
</html>
