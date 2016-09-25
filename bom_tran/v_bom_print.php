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
      $varbuy = $_GET['b'];
      $varsal = $_GET['s'];
      $var_menucode = $_GET['menucd'];
    }
    
    if ($_POST['Submit'] == "Update") {
    	
	
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
	margin-right: 0px;
}
</style>

<!-- jQuery libs -->
<script type="text/javascript" src="../js/multitable/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>

<script type="text/javascript">
function newWindow(file,window) {
    msgWindow=open(file,window,'resizable=yes,scrollbars=yes,width=950,height=700');
    if (msgWindow.opener == null) msgWindow.opener = self;
}
</script>

</head>
<?php
	$var_sql = " SELECT * From salesentry";
    $var_sql .= " WHERE sbuycd = '$varbuy'";
    $var_sql .= " and   sordno = '$varsal'";
	$result=mysql_query($var_sql);
	$row = mysql_fetch_array($result);
	$sorddt = $row['sorddte'];
	$sdeldt = $row['sexpddte'];

	$var_sql = " SELECT pro_buy_desc from pro_buy_master ";
    $var_sql .= " WHERE pro_buy_code = '$varbuy'";
	$result=mysql_query($var_sql);
	$row = mysql_fetch_array($result);
	$buydesc = $row['pro_buy_desc'];
?>
<body>
   <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>-->

  <div class ="contentc">
 
	<fieldset name="Group1" style=" width: 834px;" class="style2">
	 <legend class="title">BOM PRINTING</legend>
	  <br>	 
	 
	  <form name="InpMDETMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()" style="width: 792px">
		<table>
	  	  	<tr>
	  	  		<td></td>
	  	  		<td>Buyer Name</td>
	  	  		<td>:</td>
	  	  		<td>
				<input type="text" name="costinno" id="costinno" value="<?php echo $varbuy; ?>" readonly="readonly" style="width: 80px;">
				<label><?php echo $buydesc; ?></label>
				</td>
	  	  	</tr>
	  	  	<tr>
	  	  		<td></td>
	  	  	</tr>
	  	    <tr>
	  	    	<td></td>
	  	    	<td style="width: 154px">Sales Order No</td>
	  	    	<td>:</td>
	  	    	<td style="width: 311px">
				<input class="inputtxt" name="mplanrmk" id ="mplanrmk" type="text" readonly="readonly" style="width: 200px;" value="<?php  echo $varsal; ?>"></td>
	  	    	<td style="width: 30px"></td>
	  	    </tr>
	  	  </table>	
	    <br>
		  <table id="itemsTable" class="general-table" style="width: 784px">
          	<thead>
          	 <tr>
              <th class="tabheader" style="width: 41px">#</th>
              <th class="tabheader" style="width: 132px">BOM No</th>
              <th class="tabheader" style="width: 94px">Product Code</th>
              <th class="tabheader" style="width: 94px">Color Code</th>
              <th class="tabheader" style="width: 276px">Color Description</th>
              <th class="tabheader" style="width: 100px">Order Quantity</th>
              <th class="tabheader" style="width: 100px">Print Detail</th>
             </tr>
            </thead>
            <tbody>
            
             <?php
  					
				$sqld = "delete from tmpbomprn where usernm = '$var_loginid'";
             	mysql_query($sqld) or die(" Unable Delete Temp Table tmpbomprn :".mysql_error());
					
				$sql1  = "SELECT x.sprocd, x.sproqty, y.prod_size, y.prod_col, y.prod_catpre, y.pronumcode ";
				$sql1 .= " FROM salesentrydet x left join pro_cd_master y on (x.sprocd = y.prod_code)";
             	$sql1 .= " Where x.sordno = '$varsal'"; 
             	$sql1 .= " And   x.sbuycd = '$varbuy'";
	    		$sql1 .= " ORDER BY y.prod_catpre, y.pronumcode, y.prod_col, y.prod_size";
				$rs_result1 = mysql_query($sql1) or die("Can't query Sales Table 2: ".mysql_error()); 
				//echo $sql1 . "</br>";
			      	
				while ($row1 = mysql_fetch_assoc($rs_result1)){

					$prodcdi = htmlentities($row1['sprocd']);
					$prodsiz = $row1['prod_size'];
					$prodordqty = $row1['sproqty'];
					$prodcol = $row1['prod_col'];
					$prodcat = $row1['prod_catpre'];
					$prodnum = $row1['pronumcode'];
					
					$prc = $prodcat.$prodnum; 
					
					$tmpitm = $prc.'-'.$prodcol.'%';
						    
					$sqlcd = "select clr_desc from pro_clr_master";
        			$sqlcd .= " where clr_code ='$prodcol'";
        			$sql_resultcd = mysql_query($sqlcd) or die("Can't query Product Color Table 3:".mysql_error());
        			$rowcd = mysql_fetch_array($sql_resultcd);
        			$coldesc = $rowcd[0];
							
					$sql2 = "SELECT rm_code, rm_comps, rm_desc, rm_uom, rm_seqno FROM prod_matlis";
             		//$sql2 .= " Where prod_code = '$prodcdi'"; //block to get costing item...costing only do 1 for all sizes
             	    $sql2 .= " Where prod_code like '$tmpitm'"; 
             		$sql2 .= " ORDER BY rm_seqno"; 
             		//echo 'kkk - '. $sql2 . "</br>";
					$rs_result2 = mysql_query($sql2) or die("Can't Query Planning Detail Table 4 :".mysql_error());

					while ($row2 = mysql_fetch_assoc($rs_result2)){
						
						$sumconp = $row1['sproqty'] * $row2['rm_comps'];		
						$rmcode  = $row2['rm_code'];
						$rmdesc  = $row2['rm_desc'];
						$rmuom   = $row2['rm_uom'];
						$rmcomp  = $row2['rm_comps'];
						$rmordqty  = $row1['sproqty'];
						$rmseq = $row2['rm_seqno'];
						
						$rmdesc = str_replace('"', '^', $rmdesc);
						$rmdesc = str_replace("'", '|', $rmdesc);
								
						$sql2 = "select sum(totalqty) from rawmat_tran ";
        				$sql2 .= " where item_code ='".$rmcode."' ";
        				$sql2 .= " and tran_type in ('REC', 'ADJ', 'RTN', 'ISS', 'OPB', 'REJ')";
        				$sql_result2 = mysql_query($sql2) or die("Unable Query Onhand Qty 5 :".mysql_error());
        				$row2 = mysql_fetch_array($sql_result2);        
        				if ($row2[0] == "" or $row2[0] == null){ 
        				  $row2[0]  = 0.00;
        				}
        				$onhnd = $row2[0];
			
						if ($prodcol != ""){
							$colcnt = 0;
							$sqld  = "SELECT count(*) ";
			  				$sqld .= " FROM tmpbomprn ";
				            $sqld .= " Where usernm='$var_loginid'";
				            $sqld .= " and procode = '$prc'";
				            $sqld .= " and proclocd = '$prodcol'";
				            $sqld .= " and itmcd = '$rmcode'";
				            $sql_resultd = mysql_query($sqld);
				            $rowd = mysql_fetch_array($sql_resultd); 
				            $colcnt = $rowd[0]; 
				            if ($colcnt==''){ $colcnt = 0;}
				            //echo $sqld . "</br>";

						
							if ($colcnt==0){
								$vartoday = date("Y-m-d");
								$sql  = "INSERT INTO tmpbomprn"; 
								$sql .=	" (bomno, sordno, buycd, sorddte, prodcd, proclocd, proclode, sdeldte, prosiz, ordqty, ";
								$sql .= "  itmcd, itmseq, itmdesc, itmuom, uniconsump, totconsump, onhandbal, auddte, usernm, procode, prcat, prnum) values"; 
					       		$sql .= " ('$bomcode', '$varsal', '$varbuy', '$sorddt', '$prodcdi', '$prodcol', ";
					       		$sql .= "  '$coldesc', '$sdeldt', '$prodsiz', '$prodordqty', '$rmcode', '$rmseq', ";
					       		$sql .= "  '$rmdesc', '$rmuom', '$rmcomp', '$sumconp', '$onhnd', '$vartoday', '$var_loginid', '$prc', '$prodcat', '$prodnum')";
					       		mysql_query($sql) or die("Can't Insert To Temp Table Color Plan :".mysql_error());			
				       		}
						}
					}
				}
			?>
            
            <?php
            
              $sqld  = "SELECT count(distinct procode, proclocd) ";
			  $sqld .= " FROM tmpbomprn ";
              $sqld .= " Where usernm='$var_loginid'";
              $sql_resultd = mysql_query($sqld);
              $rowd = mysql_fetch_array($sql_resultd); 
              $colcnt = $rowd[0]; 
       		 
       		  $sql1 = "SELECT distinct procode, proclocd, proclode, prcat, prnum FROM tmpbomprn where usernm = '$var_loginid' order by 1, 2";
              $rs_result1 = mysql_query($sql1) or die("Mysql Error =".mysql_error()); 
              
			  $i = 1;
			  $tordq = 0;
			  $cdcnt = 1;    	
			  while ($row1 = mysql_fetch_assoc($rs_result1)){
			  	$clrcd = $row1['proclocd'];
			  	$procd = $row1['procode'];
			  	$bomcode  = $varsal." ".$cdcnt."/".$colcnt;
			  
			  	$sql3 = "Update tmpbomprn set bomno = '$bomcode'";
        		$sql3 .= " where usernm ='$var_loginid'";
        		$sql3 .= " and procode ='".$row1['procode']."'";
        		$sql3 .= " and proclocd = '$clrcd'";
        		mysql_query($sql3) or die("Unable Update Detail Table :".mysql_error());     		
        		
        		$sql2  = "SELECT sum(x.sproqty)";
				$sql2 .= " FROM salesentrydet x left join pro_cd_master y on (x.sprocd = y.prod_code)";
             	$sql2 .= " Where x.sordno = '$varsal'"; 
             	$sql2 .= " And   x.sbuycd = '$varbuy'";
	    		$sql2 .= " And   y.prod_catpre = '".$row1['prcat']."'";
	    		$sql2 .= " And   y.pronumcode = '".$row1['prnum']."'";
	    		$sql2 .= " And   y.prod_col = '$clrcd'";
        		$sql_result2 = mysql_query($sql2) or die("Unable Query Order Qty 5 :".mysql_error());
        		$row2 = mysql_fetch_array($sql_result2);        
        		if ($row2[0] == "" or $row2[0] == null){ 
        		  $row2[0]  = 0.00;
        		}
        		$ocolqty = $row2[0];
				
				$prnpath = "bomrpt.php?colcd=".$clrcd . "&procd=". $procd;
				echo '<tr class="item-row">';
             	echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 41px; border:0;"></td>';
             	echo '<td><input name="bomno[]" id="bomno" value="'.$bomcode.'" readonly="readonly" style="width: 100px; border:0;"></td>';
             	echo '<td><input name="prc[]" id="prc" value="'.$row1['procode'].'" readonly="readonly" style="width: 100px; border:0;"></td>';
             	echo '<td><input name="plancol[]" value="'.$clrcd.'" id="plcolcode'.$i.'" readonly="readonly" style="width: 113px; border:0;"></td>';
             	echo '<td><input name="plancdes[]" value="'.$row1['proclode'].'" id="plcoldesc'.$i.'" readonly="readonly" style="width: 314px; border:0;"></td>';
             	echo '<td><input name="ordqty[]" value="'.$ocolqty.'" readonly="readonly" style="width: 100px; border:0; text-align:right;"></td>';
             	echo '<td style="text-align:center"><a href=javascript:newWindow("'.$prnpath.'") title="Print BOM For this Color"><img src="../images/b_print.png" border="0" width="16" height="16" hspace="2" alt="Duplicate BOM"></a></td>';
             	echo '</tr>';
             	$i = $i + 1;
             	$cdcnt = $cdcnt + 1;
             	$tordq = $tordq + $ocolqty; 						
	 		 }
         ?>
         </tbody>
          <tfoot>
          	<tr>
          		<td></td>
          		<td style="width: 132px"></td>
          		<td style="width: 94px"></td>
          		<td style="width: 94px"></td>
          		<td style="text-align:right">Total</td>
          		<td style="width: 276px"><input name="tqty" value="<?php echo number_format($tordq,'2','.',","); ?>" readonly="readonly" style="width: 100px; border:0; text-align:right;"></td>
          		<?php $prnpath = "bomrpt.php?opt=A";?>
          		<td style="text-align:center"><a href=javascript:newWindow("<?php echo $prnpath; ?>") title="Print BOM For All Color">
          		<img src="../images/b_print.png" border="0" width="16" height="16" hspace="2" alt="Duplicate BOM"></a></td>
          	</tr>
          </tfoot>
        </table>
       
     
	   <table>
	  	<tr>
			<td style="width: 1105px; height: 22px;" align="center">
				<?php		
				 $locatr = "bom_print.php?menucd=".$var_menucode;
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				?>
				</td>
			</tr>
	  </table>
   </form>	
  </fieldset>
  </div>
  <div class="spacer"></div>
</body>
</html>
