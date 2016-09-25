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
   
      $var_sor = htmlentities($_GET['sorno']);
      $var_buy = $_GET['buycd'];
      $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");

    }
    
   if (isset($_POST['Submit'])){ 
   	 $var_menucode  = $_POST['menudcode'];
   	 $var_sor  = $_POST['sorno'];
   	 $var_buy  = $_POST['buycd'];
   	 
     if ($_POST['Submit'] == "Print") {
    
   		$here = getcwd();
       // Redirect browser
        $fname = "sales_f_rpt.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&sno=".$var_sor."&buyc=".$var_buy."&dbsel=".$varrpturldb;
        $dest .= urlencode(realpath($fname));
        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        
        $backloc = "../sales_tran/vm_saleentry.php?sorno=".$var_sor."&buycd=".$var_buy."&menucd=".$var_menucode;
        
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

.general-table #procomat                        { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}

.style2 {
	margin-right: 0px;
}
</style>

<!-- jQuery libs -->
<script type="text/javascript" src="../js/multitable/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>

<script type="text/javascript"> 

</script>
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>--> 

</head>
  
  <?php
  	 $sql = "select * from salesentry";
     $sql .= " where sordno ='".$var_sor."' And sbuycd ='".$var_buy."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $expddte = date('d-m-Y', strtotime($row['sexpddte']));
     $orddte = date('d-m-Y', strtotime($row['sorddte']));
     $cutdte = date('d-m-Y', strtotime($row['scutdte']));
     $sordnobuyer= $row['sordnobuyer'];
     $scutsno = $row['scutsno'];
     $salestype= $row['salestype'];
     $sremark = $row['sremark'];
     $sremark2 = $row['sremark2'];
     $sremark3 = $row['sremark3'];
     $sremark4 = $row['sremark4'];
     $tqty = $row['toqty'];
     $tamt = $row['toamt']; 
     $create_by = $row['create_by'];
     $create_on = $row['create_on'];
     $modified_by= $row['modified_by'];
 	 $modified_on = $row['modified_on'];
 
     
     $tqty  = number_format($tqty, 0, '', '');
	 
	 $sql = "select pro_buy_desc from pro_buy_master ";
     $sql .= " where pro_buy_code ='".$var_buy."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);
     $buycdde = $row[0];	
     
	 $sql = "select customer_desc from customer_master ";
     $sql .= " where customer_code ='".$var_buy."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);
     $customerdesc = $row[0];		
  ?>

<body>
 
  <div class="contentc">

	<fieldset name="Group1" style=" width: 960px;" class="style2">
	 <legend class="title">SALES ORDER VIEW :&nbsp; <?php echo $var_sor; ?></legend>
	  <br>	 
	  
	  <form name="InpSalesF" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
	   <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
	   <input name="sorno" type="hidden" value="<?php echo $var_sor;?>">
	   <input name="buycd" type="hidden" value="<?php echo $var_buy;?>">
	
		<table style="width: 934px; height: 278px;">
	   	   <tr>
	   	    <td style="width: 13px"></td>
	  	    <td style="width: 106px">Order No.</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 135px">
			<input class="inputtxt" readonly="readonly" name="saordno" id="saordnoid" type="text" maxlength="30" style="width: 204px;" value="<?php echo $var_sor; ?>">
			</td>
			<td style="width: 10px"></td>
			<td style="width: 106px">Expected Delivery Date</td>
			<td style="width: 16px">:</td>
			<td style="width: 284px">
		    <input class="inputtxt" name="saexpddte" readonly="readonly" id ="saexpddte" type="text" style="width: 128px;" value="<?php  echo $expddte; ?>">
		    </td>
	  	  </tr>  
	  	  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 106px"></td>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 135px"></td>
	  	  </tr>
	  	    <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 106px">Buyer Order No.</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 135px">
		   <input class="inputtxt" readonly="readonly" name="saordno" id="saordnoid0" type="text" maxlength="30" style="width: 204px;" value="<?php echo $sordnobuyer; ?>"></td>
		   <td></td>
		   <td style="width: 204px">Total Quantity</td>
		   <td>:</td>
		   <td style="width: 284px">
		   <input readonly="readonly" name="totqty" id ="totqtyid" type="text" style="width: 156px;" class="textnoentry1" value="<?php echo $tqty; ?>"></td>
	  	    </tr>
	  	  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 106px">Order Date</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 135px">
		   <input class="inputtxt" name="saorddte" readonly="readonly" id ="saorddte" type="text" style="width: 128px;" value="<?php  echo $orddte; ?>">
		   </td>
		   <td></td>
		   <td style="width: 204px;">Total Amount</td>
		   <td style="height: 22px">:</td>
		   <td style="width: 284px;">
		   <input readonly="readonly" name="totamt" id ="totamtid" type="text" style="width: 156px;" class="textnoentry1" value="<?php echo $tamt; ?>"></td>
	  	  </tr>
	  	   <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 106px">Buyer Name</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 135px">
	  	   	<input class="inputtxt" name="sabuycd" readonly="readonly" id ="sabuycd" type="text" style="width: 199px;" value="<?php  echo $customerdesc ; ?>"></td>
	  	  </tr>
		  <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 106px">Sales type</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 135px">
	  	   	<input class="inputtxt" name="sabuytyp" readonly="readonly" id ="sabuycd0" type="text" style="width: 195px;" value="<?php  echo $salestype; ?>"></td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td style="width: 284px">
		   &nbsp;</td>
		  </tr> 
		    <tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 106px">Remark</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 135px">
			<input class="inputtxt" name="saremark" readonly="readonly"  id="sasewgrpid" type="text" style="width: 263px;" value="<?php echo  $sremark; ?>"></td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td style="width: 284px">
		   &nbsp;</td>
	  	    </tr>
			<tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 106px">&nbsp;</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 135px">
			<input class="inputtxt" name="saremark1" readonly="readonly"  id="sasewgrpid1" type="text" style="width: 263px;" value="<?php echo  $sremark2; ?>"></td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td style="width: 284px">
		   &nbsp;</td>
	  	    </tr>
			<tr>
	  	   <td style="width: 13px"></td>
	  	   <td style="width: 106px">&nbsp;</td>
	  	   <td style="width: 13px">:</td>
	  	   <td style="width: 135px">
			<input class="inputtxt" name="saremark2" readonly="readonly"  id="sasewgrpid2" type="text" style="width: 263px;" value="<?php echo  $sremark3; ?>"></td>
		   <td style="width: 10px"></td>
		   <td style="width: 204px">&nbsp;</td>
		   <td>&nbsp;</td>
		   <td style="width: 284px">
		   &nbsp;</td>
	  	    </tr>
		  <tr>
	  	   <td style="width: 13px; height: 28px;"></td>
	  	   <td style="width: 106px; height: 28px;"></td>
	  	   <td style="width: 13px; height: 28px;">:</td>
	  	   <td style="width: 135px; height: 28px;">
			<input class="inputtxt" name="saremark0" readonly="readonly"  id="sasewgrpid0" type="text" style="width: 263px;" value="<?php echo  $sremark4; ?>"></td>
		   <td style="width: 10px; height: 28px;"></td>
		   <td style="width: 204px; height: 28px;"></td>
		   <td style="height: 28px"></td>
		   <td style="width: 284px; height: 28px;">
		      </td>
	  	  </tr>
	  	   <tr>
	  	   <td style="width: 13px;"></td>
	  	   <td style="width: 106px;">&nbsp;</td>
	  	   <td style="width: 13px;">&nbsp;</td>
	  	   <td style="width: 135px;">
		   	&nbsp;</td>
		   <td style="width: 10px;"></td>
		   <td style="width: 204px;">&nbsp;</td>
		   <td style="height: 22px">&nbsp;</td>
		   <td style="width: 284px;">
		   &nbsp;</td>
	  	  </tr>
	  	  
	  	    <tr>
	  	   <td></td>
		   <td style="width: 442px">Create By</td>
           <td>:</td>
           <td style="width: 135px">
			<input class="textnoentry1" name="create_by" id ="create_byid" type="text" readonly="readonly" style="width: 150px" value="<?php echo $create_by;?>"></td>
		   <td></td>
           <td style="width: 290px">Create On</td>
           <td style="width: 109px">:</td>
           <td style="width: 468px">
		   <input class="textnoentry1" name="create_on" id ="create_onid" type="text" readonly="readonly" style="width: 160px" value="<?php echo $create_on;?>"></td>
		    </tr>
			<tr>
	  	    <td style="width: 2px"></td>
	  	    <td class="tdlabel" style="width: 442px">Modified By&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	  	    <td>:</td>
	  	    <td style="width: 135px">
	  	    <input class="textnoentry1" name="modified_by" id ="modified_byid" type="text" readonly="readonly" style="width: 150px" value="<?php echo $modified_by;?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  	    </td>
			<td></td>
			<td class="tdlabel" style="width: 290px">Modified On&nbsp;&nbsp;&nbsp; </td>
			<td style="width: 109px">:</td>
            <td style="width: 468px">
	  	     <input class="textnoentry1" name="modified_on" id ="suppstateid0" type="text" readonly="readonly" style="width: 160px" value="<?php echo $modified_on; ?>"></td>
		    </tr>
	  	  
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table" style="width: 958px">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader" style="width: 178px">Product Code</th>
              <th class="tabheader" style="width: 178px">Buyer Article</th>
              <th class="tabheader">Description</th>
              <th class="tabheader" style="width: 100px">Quantity</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader" style="width: 137px">Unit <br>Price(RM)</th>
              <th class="tabheader" style="width: 242px">Amount</th>
             </tr>
            </thead>
            <tbody>
             <?php
            	$sql = "SELECT * FROM salesentrydet";
             	$sql .= " where sordno ='".$var_sor."' And sbuycd ='".$var_buy."'";
	    		$sql .= " ORDER BY sproseq";  
				$rs_result = mysql_query($sql); 
			   
			    while ($rowq = mysql_fetch_assoc($rs_result)){
            		$rowq['sproqty']  = number_format($rowq['sproqty'], 0, '', '');
            		
             		echo '<tr class="item-row">';
                	echo '<td><input name="seqno[]" value="'.$rowq['sproseq'].'" style="width: 27px; border:0;"></td>';
                    echo '<td><input name="procd[]" value="'.$rowq['sprocd'].'" tProCd1=1 id="procd1" style="width: 175px; border-style: none; border-color: inherit; border-width: 0;"></td>';
                    echo '<td><input name="procdbuyer[]" value="'.$rowq['sprocdbuyer'].'" tProCd1=1 id="procdbuyer1" style="width: 175px; border-style: none; border-color: inherit; border-width: 0;"></td>';
                    echo '<td><input name="procdname[]" value="'.$rowq['sprodesc'].'" id="proconame1" style="border-style: none; border-color: inherit; border-width: 0; width: 303px;"></td>';
                    echo '<td><input name="proorqty[]" style="width: 97px; text-align:center; border-style: none; border-color: inherit; border-width: 0;" value="'.$rowq['sproqty'].' "></td>';
                    echo '<td><input name="prouom[]" value="'.$rowq['sprouom'].'" style="width: 75px; border-style: none; border-color: inherit; border-width: 0;"></td>';
                    echo '<td><input name="prooupri[]" id="prooupri1" value="'.$rowq['sprounipri'].'" style="width: 89px; text-align:right; border-style: none; border-color: inherit; border-width: 0;"></td>';
					echo '<td><input name="proouamt[]" id="proouamt1" value="'.$rowq['sproamt'].'" style="width: 116px; border-style: none; border-color: inherit; border-width: 0; text-align:right;"></td>';
                    echo '</tr>';                }
             ?>       
            </tbody>
           </table>
           
	
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_sale_form.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				 echo '<input type=submit name = "Submit" value="Print" class="butsub" style="width: 60px; height: 32px" >';			
				?>
				</td>
			</tr>
			<tr>
				<td style="width: 1150px" colspan="5">
				
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
	  	</table>
	   </form>	
	</fieldset>
	</div>
	<div class="spacer"></div>
</body>

</html>
