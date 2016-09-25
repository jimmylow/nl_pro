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
      $var_plandte = $_GET['pdte'];
      $var_planrmk = $_GET['prmk'];
      $var_planopt = $_GET['popt'];
      
      $arrord = unserialize(urldecode($_GET["ord"]));
      $arrbuy = unserialize(urldecode($_GET["ordbuy"]));
      include("../Setting/ChqAuth.php");
    }
    
    if ($_POST['Submit'] == "Save") {
    	$var_plandte   = $_POST['plandte'];
    	$var_planrmk   = $_POST['planrmk'];
    	$var_planopt   = $_POST['planopt'];
    	  
    	if(!empty($_POST['propl']) && is_array($_POST['propl'])){ 
    		foreach($_POST['propl'] as $value){
    			$sql  = "Update tmpplancol set planflg = 'Y'"; 
				$sql .=	" Where procdcol = '$value' and usernm = '$var_loginid'";
				mysql_query($sql) or die("Can't Update Temp Product Plan Table 1:".mysql_error());
				
				$sql  = "Update tmpplanpro01 set planflg = 'Y'"; 
				$sql .=	" Where procdcol = '$value' and usernm = '$var_loginid'";
				mysql_query($sql) or die("Can't Update Temp Product Plan Table 2:".mysql_error());	  
			}
		}
		
		$backloc = "../bom_tran/mat_plan_colsel.php?menucd=".$var_menucode."&pdte=".$var_plandte."&prmk=".$var_planrmk."&popt=".$var_planopt;;
        echo "<script>";
        echo 'location.replace("'.$backloc.'")';
        echo "</script>"; 		
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
function poptastic(url)
{
	var newwindow;
	newwindow=window.open(url,'name','height=200,width=500,left=50,top=200,,scrollbars=yes');
	if (window.focus) {newwindow.focus()}
}

function validateForm()
{
    var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length; 
	
	var cnt = 0;	
	for(var i = 1; i < rowCount; i++) { 
	  var vplantco = "propl"+i;
	  var colcops = document.getElementById(vplantco).checked;
			
	  if (colcops){
	  	cnt = cnt + 1;
	  }
	}
	if (cnt == 0){
		alert ('Cant Proceed With No Planning Color Code Selected');
		var vplantco = "propl1";
		document.getElementById(vplantco).focus();
		return false;
	}
}
</script>

</head>
<?php

   if($var_planopt == "P"){
   		$poptdesc = "By Product Code";
   }else{
   		$poptdesc = "By Color";
   }
   
   $orddesc = "";
   foreach($arrord as $row=>$ordno ) {
  					
		$pordno = $ordno;
		if ($pordno != ""){
			if ($orddesc == ""){
				$orddesc = $pordno;
			}else{	
				$orddesc = $orddesc.", ".$pordno;	
   			}
   		}
   }
?>
 
<body>
   <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>-->

  <div class ="contentc">
 
	<fieldset name="Group1" style=" width: 751px;" class="style2">
	 <legend class="title">PLANNING DETAIL - BY COLOR (2) <?php echo  $var_costinno; ?></legend>
	  <br>	 
	 
	  <form name="InpMDETMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()" style="width: 734px">
		
		<input name="plandte" type="hidden" value="<?php echo $var_plandte;?>">
		<input name="planrmk" type="hidden" value="<?php echo $var_planrmk;?>">
		<input name="planopt" type="hidden" value="<?php echo $var_planopt;?>">
		
		<table>
			<tr>
				<td></td>
				<td>Planning Option</td>
				<td>:</td>
				<td><?php echo $poptdesc; ?></td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td></td>
				<td>Sales Order No </td>
				<td>:</td>
				<td><?php echo $orddesc; ?></td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td></td>
				<td>Remark</td>
				<td>:</td>
				<td><?php echo $var_planrmk; ?></td>
			</tr>
			<tr><td></td></tr>
			<?php
				$txtnocost = "";
				foreach($arrord as $row=>$ordno ) {	
					$pordno = $ordno;
					$probuy = $arrbuy[$row];
					
					if ($pordno != ""){
						$sql  = "SELECT distinct y.sprocd ";
						$sql .= " From salesentry x, salesentrydet y";
             			$sql .= " Where x.sordno = y.sordno  and x.sbuycd = y.sbuycd"; 
             			$sql .= " And   x.sordno = '$pordno' and x.sbuycd='$probuy'";
	    				$sql .= " ORDER BY 1";
	    				$rs_result = mysql_query($sql) or die("Can't query Sales Table : ".mysql_error()); 
			      	
						while ($row = mysql_fetch_assoc($rs_result)){
							$prcode = htmlentities($row['sprocd']);
						
							$cnt = 0;
							$sql1  = "Select count(*) from prod_matmain";
							$sql1 .= " Where prod_code = '$prcode'";
							$sql_result = mysql_query($sql1) or die("Can't Query Costing Table :".mysql_error());
        					$rowcd = mysql_fetch_array($sql_result);
        					$cntcost = $rowcd[0];
        					if ($cntcost == "" or is_null($cntcost)){
        						$cntcost = 0;
        					}
        					
							if ($cntcost == 0){
								if ($txtnocost == ""){
									$txtnocost = $prcode;
								}else{
									$txtnocost = $txtnocost.", ".$prcode;
								}	
							}		
						}
					}
				}
				if ($txtnocost != ""){
			?>
				<tr>
					<td></td>
					<td>Note</td>
					<td>:</td>
					<td><?php echo $txtnocost;?></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td><span style="font-size:small; color:maroon;">* Note - Product Code Will Not In Planning Cause Material Costing Product Code Not Found.</span></td>	
				</tr>
			<?php
				}
			?>	
	    </table>
	    <br>
		  <table id="itemsTable" class="general-table" style="width: 723px">
          	<thead>
          	 <tr>
              <th class="tabheader" style="width: 41px">#</th>
              <th class="tabheader" style="width: 78px">Color Code</th>
              <th class="tabheader">Description</th>
              <th class="tabheader" style="width: 100px">Order Qty</th>
              <th class="tabheader" style="width: 100px">Planning</th>
             </tr>
            </thead>
            <tbody>
            
            <?php
  				if ($_POST['Submit'] == ""){	
					$sqld = "delete from tmpplancol where usernm = '$var_loginid'";
             		mysql_query($sqld) or die(" Unable Delete Temp Table 2 :".mysql_error());
             		$sqldk = "delete from tmpplanpro01 where usernm = '$var_loginid'";
             		mysql_query($sqldk) or die(" Unable Delete Temp Table 1:".mysql_error());
				}
					
  				foreach($arrord as $row=>$ordno ) {	
					$pordno = $ordno;
					$probuy = $arrbuy[$row];

					if ($pordno <> ""){	
						$sql1  = "SELECT x.sprocd, x.sproqty, y.prod_col, x.sprodesc, x.sprouom ";
						$sql1 .= " FROM salesentrydet x left join pro_cd_master y on (x.sprocd = y.prod_code)";
             			$sql1 .= " Where x.sordno='".$pordno."'"; 
             			$sql1 .= " And   x.sbuycd='".$probuy."'";
	    			    $sql1 .= " ORDER BY y.prod_col, x.sprocd";
	    			    $rs_result1 = mysql_query($sql1) or die("Can't query Sales Table : ".mysql_error()); 
			      	
						while ($row1 = mysql_fetch_assoc($rs_result1)){
						    $prodcol    = $row1['prod_col'];
						    $prodcdi    = htmlentities($row1['sprocd']);
						    $prodordqty = $row1['sproqty'];
						    $prdesc     = $row1['sprodesc'];
						    $pruom      = $row1['sprouom'];
						    
						    $sql5  = "INSERT INTO tmpplanpro01 ";
							$sql5 .= " (sordno, sbuycd, procd, procdcol, ordqty, procdde, procduom, ";
							$sql5 .= "  usernm) ";
							$sql5 .= "  values "; 
							$sql5 .= " ('$pordno', '$probuy', '$prodcdi', '$prodcol', '$prodordqty', '$prdesc', '$pruom', ";
							$sql5 .= "  '$var_loginid') ";
							mysql_query($sql5) or die("Cant Insert Temp Table 1 :".mysql_error());
						    
						    $cnt = 0;
							$sqlj  = "Select count(*) from costing_matord";
							$sqlj .= " Where procd = '$prodcdi'";
							$sqlj .= " And sordno = '$pordno' and sbuycd='$probuy'";
							$sql_resultj = mysql_query($sqlj) or die("Can't Query cost mat ord Table :".mysql_error());
        					$rowj = mysql_fetch_array($sql_resultj);
        					$cnt = $rowj[0];
        					if ($cnt == "" or is_null($cnt)){
        						$cnt = 0;
        					}

						    if ($cnt == 0){
						    	
								$sql2 = "SELECT rm_code, rm_comps, rm_desc, rm_uom, rm_seqno FROM prod_matlis";
             					$sql2 .= " Where prod_code = '".$row1['sprocd']."'"; 
             					$sql2 .= " ORDER BY rm_seqno";  
								$rs_result2 = mysql_query($sql2) or die("Can't Query Planning Detail Table :".mysql_error());

								while ($row2 = mysql_fetch_assoc($rs_result2)){
									$sumconp = $row1['sproqty'] * $row2['rm_comps'];		
									$rmcode = htmlentities($row2['rm_code']);
									$rmdesc = mysql_real_escape_string($row2['rm_desc']);
									$rmuom  = $row2['rm_uom'];
									$rmcomp = $row2['rm_comps'];
									$rmordqty  = $row1['sproqty'];
									$rmseq = $row2['rm_seqno'];
									
									if ($rmseq == ""){ $rmseq = 0; }				
									$sql  = "INSERT INTO tmpplancol"; 
									$sql .=	" (orderno, buycd, procd, procdcol, procddesc, procduom, ordqty, rmseqno, ";
									$sql .= "  rm_code, rm_cddesc, rm_comps, rm_uom, sumcomps, usernm, planflg) values"; 
				       				$sql .= " ('$pordno', '$probuy', '$prodcdi', '$prodcol', '$prdesc', '$pruom','$prodordqty', '$rmseq', ";
				       				$sql .= "  '$rmcode', '$rmdesc', '$rmcomp', '$rmuom', '$sumconp', '$var_loginid', '')";
							    	mysql_query($sql) or die("Can't Insert To Temp Table Color Plan :".mysql_error());
							    }			
						}
					}
				}	
       		  }
       		 
       		  $sql1 = "SELECT distinct procdcol FROM tmpplancol where usernm = '$var_loginid' order by 1";
              $rs_result1 = mysql_query($sql1) or die("Mysql Error =".mysql_error()); 
			  $i = 1;    	
			  while ($row1 = mysql_fetch_assoc($rs_result1)){
			  
			  	$procdcol = $row1['procdcol'];
			  	
			  	$sqlcd = "select clr_desc from pro_clr_master  ";
        		$sqlcd .= " where clr_code ='$procdcol'";
        		$sql_resultcd = mysql_query($sqlcd) or die("Can't query Product Color Table :".mysql_error());
        		$rowcd = mysql_fetch_array($sql_resultcd);
        		$coldesc = $rowcd[0];
        		
				$sqlcd = "select ordqty from tmpplanpro01 ";
        		$sqlcd .= " where procdcol ='$procdcol' and usernm = '$var_loginid'";
        		$sql_resultcd = mysql_query($sqlcd) or die("Can't query temp Table 1:".mysql_error());
        		$rowcd = mysql_fetch_array($sql_resultcd);
        		$ordqty = $rowcd[0];		

                $urlviwcol = "detplanord.php?col=".$row1['procdcol']."&opt=a";
				echo '<tr class="item-row">';
             	echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 41px; border:0;"></td>';
             	echo '<td><input name="plancol[]" value="'.$procdcol.'" id="plcolcode'.$i.'" readonly="readonly" style="width: 78px"></td>';
             	echo '<td><input name="plancdes[]" value="'.$coldesc.'" id="plcoldesc'.$i.'" readonly="readonly" style="border-style: none;"></td>';
             	echo '<td style="text-align:right"><a href=javascript:poptastic("'.$urlviwcol.'") title="Edit the Detail RM Plan For this Color">'.$ordqty.'</a></td>';
             	echo '<td><input name="propl[]" value='.$row1['procdcol'].' id="propl'.$i.'" type="checkbox" align="middle" style="width: 100px;"></td>';
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
					include("../Setting/btnsave.php");
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
