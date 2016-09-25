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
    
      $var_menucode = $_GET['menucd'];
      $var_po = $_GET['po'];
      include("../Setting/ChqAuth.php");
    }
    
    
    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
        $pdponum = $_POST['pponum'];
        
        $fname = "po_mas2.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&ponum=".$pdponum."&menuc=".$var_menucode."&dbsel=".$varrpturldb."&__pageFooterFloatFlag=False";
        $dest .= urlencode(realpath($fname));

        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        $backloc = "../pur_ord/vm_po.php?po=".$pdponum."&menucd=".$var_menucode;
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

.general-table #prococode                        { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}
.general-table #procoucost                      { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}
.general-table #prococompt                      { border: 1px solid #ccc; font-size: 12px; font-weight: bold;}

.style2 {
	margin-right: 0px;
}
</style>

<!-- jQuery libs -->
<script type="text/javascript" src="../js/multitable/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>


<script type="text/javascript"> 

</script>
</head>
 <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->
<body>
 
  <?php
  	 $sql = "select * from po_master";
     $sql .= " where po_no ='".$var_po."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);

     $supplier = htmlentities($row['supplier']);
     $po_date = date('d-m-Y', strtotime($row['po_date']));
     $terms = htmlentities($row['terms']);
     $order_no = htmlentities($row['order_no']);
     $style_no = htmlentities($row['style_no']);
     $del_date = date('d-m-Y', strtotime($row['del_date']));
     $remark   = htmlentities($row['remark']);
     $cstno    = htmlentities($row['costingno']);
     
     $creby   = htmlentities($row['created_by']);
     $creon   = date('d-m-Y', strtotime($row['creation_time']));
     $updby   = htmlentities($row['modified_by']);
     $updon   = date('d-m-Y', strtotime($row['modified_on']));
     
     $sql="SELECT address_1_1, address_2_1, telephone_1, telephone_2, country_1, city_1, postal_code_1, state_1, fax1, fax2  FROM supplier_master ";
     $sql .= " where supplier_code = '".$supplier."'";

     $result = mysql_query($sql) or die ("Error : ".mysql_error());

     $data = mysql_fetch_object($result);

     $var_add = "";
     
     if (!empty($data->address_1_1)) { $var_add .= $data->address_1_1.", \n"; }
     if (!empty($data->address_2_1)) { $var_add .= $data->address_2_1.", \n"; }
     if (!empty($data->postal_code_1)) { $var_add .= $data->postal_code_1.","; }      
     if (!empty($data->city_1)) { $var_add .= $data->city_1.", \n"; } 
     if (!empty($data->state_1)) { $var_add .= $data->state_1." \n"; } 
     $var_add .= "\nTel : "; 
     if (!empty($data->telephone_1)) { $var_add .= $data->telephone_1.","; }  
     if (!empty($data->telephone_2)) { $var_add .= $data->telephone_2; }   
     $var_add .= "\nFax : "; 
     if (!empty($data->fax1)) { $var_add .= $data->fax1.","; }  
     if (!empty($data->fax2)) { $var_add .= $data->fax2; }
     
	 $var_sql = " SELECT term_desc from term_master";
     $var_sql .= " WHERE term_code = '$terms'";
     $result=mysql_query($var_sql);
     $row = mysql_fetch_array($result);
     $termde = $row[0];
    
     
  ?> 
   
  <div class="contentc">
     <form name="InpPO" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">

	<fieldset name="Group1" style=" width: 821px;" class="style2">
	 <legend class="title">PURCHASE ORDER</legend>
	  <br>	 
	  	   
		<table style="width: 803px"; border="0">
	   	   <tr>
	  	    <td style="width: 110px">Supplier</td>
	  	    <td style="width: 13px">:</td>
	  	    <td style="width: 300px"><input readonly="readonly" type="text" style="width: 156px;"  class="inputtxt"  value="<?php echo $supplier; ?>">
			</td> 
      <td></td>
			<td style="width: 47px"></td>
			<td style="width: 113px">P.O. #</td>
			<td style="width: 12px">:</td>
			<td style="width: 270px">
		   <input class="inputtxt" name="pponum" type="text" style="width: 128px;" value="<?php  echo $var_po; ?>"  >
		   </td>                 
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	   <td ></td>
	  	   <td ></td>
	  	   <td ></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td rowspan="6"></td>
         <td colspan="2" rowspan="8">
          <textarea class="inputtxt" name="suppadd" id="suppadd" COLS=35 ROWS=8><?php echo $var_add; ?></textarea>
         </td>
			<td style="width: 47px"></td>
			<td style="width: 113px">P.O Date</td>
			<td style="width: 12px">:</td>
			<td style="width: 270px">
		   <input class="inputtxt" readonly="readonly" type="text" style="width: 128px;" value="<?php  echo $po_date; ?>"  >
		   </td>       
	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td style="width: 47px" ></td>
	  	   <td style="width: 113px" ></td>
	  	   <td style="width: 12px" ></td>
	  	  </tr>
		  <tr>
		   <td></td>
       <td style="width: 47px"></td>
		   <td style="width: 113px" >Terms</td>
		   <td style="width: 12px">:</td>
		   <td>
		   <input  readonly="readonly" type="text" style="width: 83px;" value ="<?php echo $terms; ?>">
		   <label><?php echo $termde?></label>
		   </td>
		  </tr> 
		  <tr>
	  	   <td></td>
		   <td style="width: 47px"></td>
		   <td style="width: 113px" >Order No</td>
		   <td style="width: 12px">:</td>
		   <td>
		   <input  readonly="readonly" type="text" style="width: 156px; "  class="inputtxt" value="<?php echo $order_no; ?>" ></td>
	  	  </tr>
		  <tr>
	  	   <td></td>
		   <td style="width: 47px"></td>
		   <td style="width: 113px" >Style No</td>
		   <td style="width: 12px">:</td>
		   <td>
		   <input  readonly="readonly" type="text" style="width: 161px;"  class="inputtxt" value="<?php echo $style_no; ?>" ></td>
	  	  </tr>
	  	   <tr>
	  	   	<td></td>
	  	   	<td></td>
	  	   	<td><span>Planning</span> No</td>
	  	   	<td>:</td>
	  	   	<td><input  readonly="readonly" type="text" style="width: 161px;"  class="inputtxt" value="<?php echo $cstno; ?>" ></td>
	  	   </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td></td>
		   <td style="width: 47px"></td>
			<td style="width: 113px" >Delivery Date</td>
			<td style="width: 12px" >:</td>
			<td >
		   <input class="inputtxt"  readonly="readonly" type="text" style="width: 128px;" value="<?php  echo $del_date; ?>" >
		   </td>       
	  	  </tr>
	  	  <tr><td></td></tr>
	  	   <tr><td></td></tr>
	  	   
	  	  <tr>
	  	  	<td>Create By</td>
	  	  	<td>:</td>
	  	  	<td>
			<input class="textnoentry1" readonly="readonly" type="text" style="width: 128px;" value="<?php  echo $creby; ?>" name="creby" ></td>
	  	  	<td></td>
	  	  	<td></td>
	  	  	<td>Create On</td>
	  	  	<td>:</td>
	  	  	<td>
			<input class="textnoentry1" readonly="readonly" type="text" style="width: 128px;" value="<?php  echo $creon; ?>" name="creon" ></td>
	  	  </tr>
		   <tr><td></td></tr>
	  	  <tr>
	  	  	<td>Last Update By</td>
	  	  	<td>:</td>
	  	  	<td>
			<input class="textnoentry1" readonly="readonly" type="text" style="width: 128px;" value="<?php  echo $updby; ?>" name="updby" ></td>
	  	  	<td></td>
	  	  	<td></td>
	  	  	<td>Updated On</td>
	  	  	<td>:</td>
	  	  	<td>
			<input class="textnoentry1" readonly="readonly" type="text" style="width: 128px;" value="<?php  echo $updon; ?>" name="updon" ></td>
	  	  </tr>	
	  	  </table>
		 
		  <br><br>
		  <table id="itemsTable" class="general-table">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader">Item Code</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">Quantity</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Unit Price</th>
              <th class="tabheader">Amount</th>
             </tr>
            </thead>
            <tbody>
             <?php
             	$sql = "SELECT * FROM po_trans";
             	$sql .= " Where po_no='".$var_po."'"; 
	    		$sql .= " ORDER BY seqno";  
				$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){ 
        
             $var_amt = $rowq['qty'] * $rowq['uprice']; 
             
        
                                          
             ?>
             
             <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" readonly="readonly" style="width: 27px; border:0;" value ="<?php echo $rowq['seqno']; ?>"></td>
                <td>
				<input name="prococode[]" readonly="readonly" tProItem1=1 id="prococode<?php echo $i; ?>" tabindex="0" style="width: 161px" value ="<?php echo htmlentities($rowq['itemcode']); ?>" ></td>
                <td>
				<input name="procodesc[]" class="tInput" id="procodesc" readonly="readonly" value ="<?php echo $rowq['itmdesc']; ?>" style="border-style: none; border-color: inherit; border-width: 0; width: 303px;"></td>
                <td>
				<input name="procoqty[]" readonly="readonly" id="procoqty<?php echo $i; ?>" style="width: 48px; text-align : right" value ="<?php echo $rowq['qty']; ?>"></td>
                <td>
				<input name="procouom[]" id="procouom" class="tInput" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 75px" value ="<?php echo $rowq['itmuom']; ?>"></td>
                <td>
				<input name="procoprice[]" class="tInput" id="procoprice<?php echo $i; ?>" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 75px; text-align : right" value ="<?php echo $rowq['uprice']; ?>"></td>
                <td>
				<input name="procoamount[]" class="tInput" id="procoamount<?php echo $i; ?>" readonly="readonly" style="width: 75px; border:0; text-align : right" value ="<?php echo number_format($var_amt,2,'.',' '); ?>"></td>
             </tr>
             
          <?php 
          
                	$i = $i + 1;          
             } // while
          ?>   
            </tbody>
           </table>
           
     <br /><br />
		 <table>
			<tr>
				<td valign="top">Remarks :</td>
        <td><textarea class="inputtxt" name="remark" id="remark1" COLS=60 ROWS=5><?php echo $remark; ?></textarea></td>
			</tr>
    </table>  

     <br /><br />
     
		 <table>
		  	<tr>
				<td style="width: 1150px; height: 22px;" align="center">
				<?php
				 $locatr = "m_po.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				 include("../Setting/btnprint.php");

				?>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
	  	</table>	
	</fieldset>
	</form>
	</div>
	<div class="spacer"></div>
</body>

</html>
