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
    
    if ($_POST['Submit'] == "Update") {
    	$var_costinno = $_POST['costingno'];
    	$var_planrmk  = $_POST['mplanrmk'];
    	$var_planopt  = $_POST['var_planopt'];

		if ($var_costinno != ""){
			$vartoday = date("Y-m-d");
			$sql = "Update costing_mat Set remark = '$var_planrmk ', modified_by ='$var_loginid', modified_on ='$vartoday' ";
			$sql .= "  Where costingno ='$var_costinno'";
			mysql_query($sql) or die("Unable Update Planning Master Table ;".mysql_error());
			
			$sql = "Delete from costing_matdet";
			$sql .= "  Where costingno ='$var_costinno'";
			mysql_query($sql) or die("Unable Redudent Planning Detail Table ;".mysql_error());
			
			$sql1  = "SELECT distinct procdcol, rm_code, rm_cddesc, rm_uom, sum(sumcomps), sum(bokitmqty)";
		    $sql1 .= " from tmpplancol"; 
            $sql1 .= " Where usernm ='".$var_loginid."'"; 
            $sql1 .= " group by 1, 2, 3, 4";
	    	$sql1 .= " ORDER BY rm_code, rmseqno";
			$rs_result1 = mysql_query($sql1) or die("Can't Temporaly Planning Table : ".mysql_error()); 
			      	
			$i = 1; 
			$bookflg = 0;    #detect goot booking Item 	
			while ($row1 = mysql_fetch_assoc($rs_result1)){
					
					$prodclr = $row1['procdcol'];
					$itmcode = $row1['rm_code'];
				    $itmdesc = htmlentities($row1['rm_cddesc']);
				    $itmuom = $row1['rm_uom'];
				    $itmscomp = $row1['sum(sumcomps)'];
			        $itmsbk   = $row1['sum(bokitmqty)'];
				    if ($itmsbk == ""){ $itmsbk = 0;}
				    if ($itmsbk > 0){ $bookflg = 1;} #Should Insert Into Booking Table
				    
				    $sqlcd = "select puritm from tmpplancol ";
 					$sqlcd .= " where procdcol ='".$prodclr."'";
 					$sqlcd .= " and   rm_code  ='".mysql_real_escape_string($itmcode)."'";
 					$sqlcd .= " and   puritm   ='Y'"; 
    				$sql_resultcd = mysql_query($sqlcd) or die("Can't query Purchase Item :".mysql_error());
    				$rowcd = mysql_fetch_array($sql_resultcd);
    				$itmpur = $rowcd['puritm'];
																		    
					$sql = "INSERT INTO costing_matdet values 
						('$var_costinno', '$itmcode', '$itmdesc', '$itmuom','0','0','$itmscomp','$i','$prodclr', '$itmpur', '$itmsbk', '$var_planopt')";		
					mysql_query($sql) or die("Can't Insert Into Detail Table :".mysql_error());
					$i = $i + 1;
			}
			
			#--------------------------Searching Booking Detail & Update Booking Detail-----------------------------------
			if ($bookflg > 0){
      			
				$sql  = "Select bookno from booktab01 ";
		 		$sql .= " Where byrefno = '".$var_costinno."'";
        		$sql_result = mysql_query($sql);
        		$row = mysql_fetch_array($sql_result);
        		$bookno = $row[0];
        	 
        		if ($bookno != ""){
        				#--------------Insert Into Booking Detail Table------------------------------------------
						$sql1 = "SELECT distinct procdcol, rm_code, rm_cddesc, rm_uom, sum(sumcomps), sum(bokitmqty), itmavail";
						$sql1 .= " from tmpplancol"; 
             			$sql1 .= " Where usernm ='".$var_loginid."'"; 
             			$sql1 .= " And  bokitmqty >0";
             			$sql1 .= " group by 1, 2, 3, 4, 7";
	    				$sql1 .= " ORDER BY rm_code";
						$rs_result1 = mysql_query($sql1) or die("Can't query Sales Table : ".mysql_error()); 
			      	
			    		$i = 0;  	
						while ($row1 = mysql_fetch_assoc($rs_result1)){
							$macode    = $row1['rm_code'];
							$madesc    = $row1['rm_cddesc'];
							$mauom     = $row1['rm_uom'];
							$bkitmqty  = $row1['sum(bokitmqty)'];
							$bkitmavai = $row1['itmavail'];
	
							if ($bkitmqty > 0){
								if ($bkitmavai == ""){ $bkitmavai = 0;}
								if ($bkitmqty == ""){ $bkitmqty = 0;}
								
								$sql  = "Select bookitm from booktab02 ";
		 	 					$sql .= " Where bookno  = '".$bookno."'";
		 	 					$sql .= " and   bookitm = '".$macode."'";
        	 					$sql_result = mysql_query($sql);
        	 					$row = mysql_fetch_array($sql_result);
        	 					$bookitm = $row[0];
								
								if ($bookitm == ""){
									$sql = "INSERT INTO booktab02 values 
											('$bookno', '$macode', '$madesc', '$bkitmavai','$mauom',
					       					'$bkitmqty', 'N', '0', '0')";
					       			echo $sql;		
									mysql_query($sql) or die("Query 2 :".mysql_error());
								}
							}		
							$i = $i + 1;
						}
						#----------------------------------------------------------------------------------------	
			 	}else{ 	
			 			#------------Getting Booking No-------------------------------
						$chk_invno_query = mysql_query("select count(*) from `ctrl_sysno` where `descrip` = 'BOOKNO'; ", $db_link);
              			$chk_invno_res = mysql_fetch_array($chk_invno_query) or die("Cant Get Book No Info".mysql_error());
              
						if ($chk_invno_res[0] > 0 ) {
                  			$get_invno_query = mysql_query("select noctrl from `ctrl_sysno` where `descrip` = 'BOOKNO'", $db_link);                 
                  			$get_invno_res = mysql_fetch_object($get_invno_query) or die("Cant Get Book No ".mysql_error()); 

                  			$var_invno1 = vsprintf("%06d",$get_invno_res->noctrl+1); 
                  			$varbookno = "BK".$var_invno1; 
                  
 		  		  			mysql_query("update `ctrl_sysno` set `noctrl` = `noctrl` + 1
                           				where `descrip` = 'BOOKNO';", $db_link) 
                           	or die("Cant Update Book No Auto No ".mysql_error());              
               			}else{ 
				  			$sql  = "insert into ctrl_sysno (descrip, noctrl) values ";
				  			$sql .= "	('BOOKNO', 1)";
		   		  			mysql_query($sql, $db_link) or die("Cant Insert Into Book No Auto No".mysql_error());
                  			$varbookno = "BK000001";
               			}  
						#-------------------------------------------------------------

						$vartoday = date("Y-m-d");
						$sql  = "INSERT INTO booktab01 (bookno, bookdte, booktyp, byrefno, create_by, create_on, ";
						$sql .= "                       modified_by, modified_on, buycd) values ";
						$sql .=	" ('$varbookno', '$vartoday', 'M', '$var_costinno','$var_loginid','$vartoday', "; 
						$sql .= "  '$var_loginid','$vartoday', '')";
						mysql_query($sql) or die("Query 1 Booking Table ;".mysql_error());
				
						#--------------Insert Into Booking Detail Table------------------------------------------
						if ($varbookno != ""){	
							$sql1 = "SELECT distinct procdcol, rm_code, rm_cddesc, rm_uom, sum(sumcomps), sum(bokitmqty), itmavail";
							$sql1 .= " from tmpplancol"; 
             				$sql1 .= " Where usernm ='".$var_loginid."'"; 
             				$sql1 .= " And  bokitmqty >0";
             				$sql1 .= " group by 1, 2, 3, 4, 7";
	    					$sql1 .= " ORDER BY rm_code";
							$rs_result1 = mysql_query($sql1) or die("Can't query Sales Table : ".mysql_error()); 
			      	
			    			$i = 0;  	
							while ($row1 = mysql_fetch_assoc($rs_result1)){
								$macode    = $row1['rm_code'];
								$madesc    = $row1['rm_cddesc'];
								$mauom     = $row1['rm_uom'];
								$bkitmqty  = $row1['sum(bokitmqty)'];
								$bkitmavai = $row1['itmavail'];
	
								if ($bkitmqty > 0){
									if ($bkitmavai == ""){ $bkitmavai = 0;}
									if ($bkitmqty == ""){ $bkitmqty = 0;}
									$sql = "INSERT INTO booktab02 values 
						   					('$varbookno', '$macode', '$madesc', '$bkitmavai','$mauom',
						       				'$bkitmqty', 'N', '0', '0')";
									mysql_query($sql) or die("Query 2 :".mysql_error());
								}		
								$i = $i + 1;
							}
						}	
						#----------------------------------------------------------------------------------------
				}
			}
			#-----------------------------------------------------------------------------------------------------
			
			$backloc = "../bom_tran/m_mat_plan.php?menucd=".$var_menucode;
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
	margin-right: 0px;
}
</style>

<!-- jQuery libs -->
<script type="text/javascript" src="../js/multitable/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>

<script type="text/javascript">
var newwindow;
function poptastic(url)
{
	newwindow=window.open(url,'name','height=1000,width=1000,left=100,top=100');
	if (window.focus) {newwindow.focus()}
}

</script>

</head>
<?php
  	 $sql = "select remark, docdate, planopt from costing_mat ";
     $sql .= " where costingno ='".$var_costinno."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);
     $planrmk = $row['remark'];
     $plandte = $row['docdate'];
     $plandte = date('d-m-Y', strtotime($plandte));
     $planopt = $row['planopt'];
	
     $sql = "select sordno from costing_matord ";
     $sql .= " where costingno ='".$var_costinno."'";   
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
 
<body onload="document.InpMDETMas.mplanrmk.focus()">
   <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>-->

  <div class ="contentc">
 
	<fieldset name="Group1" style=" width: 796px;" class="style2">
	 <legend class="title">PLANNING DETAIL - BY COLOR <?php echo  $var_costinno; ?></legend>
	  <br>	 
	 
	  <form name="InpMDETMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()" style="width: 780px">
		
		<input name="costingno" type="hidden" value="<?php echo $var_costinno;?>">
		<input name="var_planopt" type="hidden" value="<?php echo $planopt;?>">
		
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
				<input class="inputtxt" name="mplanrmk" id ="mplanrmk" type="text" style="width: 400px;" value="<?php  echo $planrmk; ?>"></td>
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
	  	  </table>	
	    <br>
		<?php
			$sql1 = "SELECT sordno, sbuycd from costing_matord";
            $sql1 .= " Where costingno ='".$var_costinno."'"; 
			$rs_result1 = mysql_query($sql1) or die("Can't query Sales Table : ".mysql_error()); 
			 
            $i = 1;     	
			while ($row1 = mysql_fetch_assoc($rs_result1)){  					
						$pordno[i] = $row1['sordno'];
						$probuy[i] = $row1['sbuycd'];
			}			

			echo '<input name="arrord[]" type="hidden" value="'.$pordno.'">';
			echo '<input name="$arrbuy[]" type="hidden" value="'.$probuy.'">';
		?>
		
		  <table id="itemsTable" class="general-table" style="width: 723px">
          	<thead>
          	 <tr>
              <th class="tabheader" style="width: 41px">#</th>
              <th class="tabheader" style="width: 113px">Color Code</th>
              <th class="tabheader">Description</th>
              <th class="tabheader" style="width: 100px">View Detail</th>
             </tr>
            </thead>
            <tbody>
            
            <?php
  					
				$sqld = "delete from tmpplancol where usernm = '$var_loginid'";
             	mysql_query($sqld) or die(" Unable Delete Temp Table tmpplancol :".mysql_error());
             	
             	$sql1 = "SELECT * from costing_matdet";
                $sql1 .= " Where costingno ='".$var_costinno."'"; 
				$rs_result1 = mysql_query($sql1) or die("Can't query Planning Detail Table : ".mysql_error()); 
			      	
				while ($row1 = mysql_fetch_assoc($rs_result1)){  	
						
					$prodcol   = $row1['prod_colcd'];
					$itmcode   = $row1['rm_code'];
					$itmdesc   = $row1['rm_desc'];
					$itmuom    = $row1['rm_uom'];
					$itmscomps = $row1['sum_comp'];
					$itmseqno  = $row1['seqno'];
					$itmpur    = $row1['puritm'];
					$itmbok    = $row1['bokitmqty'];
					
					$sqlcd = "select clr_desc from pro_clr_master  ";
 					$sqlcd .= " where clr_code ='".$prodcol."'";
    				$sql_resultcd = mysql_query($sqlcd) or die("Can't query Product Color Table :".mysql_error());
    				$rowcd = mysql_fetch_array($sql_resultcd);
    				$coldesc = $rowcd[0];
    				
    				$sql2  = "Select sum(y.bookavail)";
    				$sql2 .= " from booktab01 x, booktab02 y";
    				$sql2 .= " where x.bookno  = y.bookno";
    				$sql2 .= " and   x.byrefno = '$var_costinno'";
    				$sql2 .= " and   y.bookitm  = '$itmcode'";
    				$sql_resultcd = mysql_query($sql2) or die("Can't query Booking Table :".mysql_error());
    				$row2 = mysql_fetch_array($sql_resultcd);
    				$availqty = $row2[0];
								
					if ($rmseq == ""){ $rmseq = 0; }
					if ($itmbok == ""){ $itmbok= 0; }
					if ($availqty == ""){ $availqty= 0; }				
					$sql  = "INSERT INTO tmpplancol"; 
					$sql .=	" (orderno, buycd, procd, procdcol, procddesc, rm_code, rm_cddesc, rm_comps, rm_uom, ";
					$sql .= "  ordqty, sumcomps, usernm, rmseqno, puritm, bokitmqty, itmavail) values"; 
				    $sql .= " ('', '', '', '$prodcol', '$coldesc','$itmcode', ";
				    $sql .= "  '$itmdesc', '0', '$itmuom', '0', '$itmscomps', '$var_loginid', '$itmseqno', '$itmpur', '$itmbok', '$availqty')";
				    mysql_query($sql) or die("Can't Insert To Temp Table Color Plan :".mysql_error());		
				}	
       		 
       		  $sql1 = "SELECT distinct procdcol, procddesc FROM tmpplancol where usernm = '$var_loginid' order by 1";
              $rs_result1 = mysql_query($sql1) or die("Mysql Error =".mysql_error()); 
			  $i = 1;    	
			  while ($row1 = mysql_fetch_assoc($rs_result1)){

                $urlviwcol = "plancol_upd.php?col=".$row1['procdcol']."&opt=u";
				echo '<tr class="item-row">';
             	echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>';
             	echo '<td><input name="plancol[]" value="'.$row1['procdcol'].'" id="plcolcode'.$i.'" readonly="readonly" style="width: 161px; border:0;"></td>';
             	echo '<td><input name="plancdes[]" value="'.$row1['procddesc'].'" id="plcoldesc'.$i.'" readonly="readonly" style="border-style: none; width: 400px;"></td>';
             	echo '<td style="text-align:center"><a href=javascript:poptastic("'.$urlviwcol.'") title="Edit the Detail RM Plan For this Color">[EDIT]</a></td>';
             	echo '</tr>';
             	$i = $i + 1;						
	 		 }
         ?>
         </tbody>
        </table>
     
	   <table>
	  	<tr>
			<td style="width: 1150px; height: 22px;" align="center">
			<?php
				 $locatr = "m_mat_plan.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
					include("../Setting/btnupdate.php");
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
