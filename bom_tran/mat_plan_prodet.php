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
    
    if ($_POST['Submit'] == "Save"){

    	$var_plandte   = $_POST['plandte'];
    	$var_planrmk   = $_POST['planrmk'];
    	$var_planopt   = $_POST['planopt'];
    	
    	if(!empty($_POST['propl']) && is_array($_POST['propl'])){ 
    		foreach($_POST['propl'] as $value){
    			$sql  = "Update tmpplancol set planflg = 'Y'"; 
				$sql .=	" Where procd = '$value' and usernm = '$var_loginid'";
				mysql_query($sql) or die("Can't Update Product Plan Table 1:".mysql_error());
				
				$sql  = "Update tmpplanpro01 set planflg = 'Y'"; 
				$sql .=	" Where procd = '$value' and usernm = '$var_loginid'";
				mysql_query($sql) or die("Can't Update Product Plan Table 2:".mysql_error());	  
			}
		}
		$backloc = "../bom_tran/mat_plan_prodsel.php?menucd=".$var_menucode."&pdte=".$var_plandte."&prmk=".$var_planrmk."&popt=".$var_planopt;
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
	newwindow=window.open(url,'name','height=200,width=400,left=50,top=200, scrollbars=yes');
	if (window.focus) {newwindow.focus()}
}

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}


function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}		 	
		return xmlhttp;
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
		alert ('Cant Proceed With No Planning Product Code Selected');
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
 
	<fieldset name="Group1" style=" width: 912px;" class="style2">
	 <legend class="title">PLANNING DETAIL - BY PRODUCT CODE (2)<?php echo  $var_costinno; ?></legend>
	  <br>	 
	 
	  <form name="InpMDETMas" method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF'].'?menucd='.$var_menucode); ?>" onsubmit="return validateForm()" style="width: 917px">
		
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
			<tr>
				<td></td>
				<td>Remark</td>
				<td>:</td>
				<td><?php echo $var_planrmk;?></td>
			</tr>
	    </table>
	    <br>
		<?php
		
			foreach($arrord as $row=>$ordno ) {
  					
						$pordno = $ordno;
						$probuy = $arrbuy[$row];

			echo '<input name="arrord[]" type="hidden" value="'.$pordno.'">';
			echo '<input name="$arrbuy[]" type="hidden" value="'.$probuy.'">';

			}
		?>
		
		  <table id="itemsTable" class="general-table" style="width: 100%">
          	<thead>
          	 <tr>
              <th class="tabheader" style="width: 20px">#</th>
              <th class="tabheader" style="width: 150px">Product Code</th>
              <th class="tabheader" style="width: 331px">Description</th>
              <th class="tabheader" style="width: 42px">UOM</th>
              <th class="tabheader" style="width: 80px">Order Qty</th>
              <th class="tabheader" style="width: 50px">Costing Create</th> 
              <th class="tabheader" style="width: 50px">Planning</th>
             </tr>
            </thead>
            <tbody>
            <?php
            
            	if ($_POST['Submit'] == ""){
            		$sqldk = "delete from tmpplancol where usernm = '$var_loginid'";
             		mysql_query($sqldk) or die(" Unable Delete Temp Table 2:".mysql_error());
             		$sqldk = "delete from tmpplanpro01 where usernm = '$var_loginid'";
             		mysql_query($sqldk) or die(" Unable Delete Temp Table 1:".mysql_error());
             	}
             	
             	foreach($arrord as $row=>$ordno ) {
  					
				    $pordno = $ordno;
					$probuy = $arrbuy[$row];

            		$sql  = "select sprocd, sprodesc, sprouom, sproqty ";
            		$sql .= " from salesentrydet";
					$sql .= " Where sordno = '$pordno'  and sbuycd = '$probuy'";
                	$sql_result = mysql_query($sql) or die("Can't query Sales Table : ".mysql_error());
                	
                	while ($row = mysql_fetch_assoc($sql_result)){
                	
						$prodcd     = mysql_real_escape_string($row['sprocd']);
						$prodde     = mysql_real_escape_string($row['sprodesc']);
						$produom    = $row['sprouom'];
						$prodordqty = $row['sproqty'];
						if ($prodordqty == ""){$prodordqty = 0;}
						
						$sql3  = "select prod_col from pro_cd_master ";
        				$sql3 .= " where prod_code ='$prodcd'";
        				$sql_result3 = mysql_query($sql3) or die('Cant query product master '.mysql_error());
        				$row3 = mysql_fetch_array($sql_result3);
        				$prodcol = $row3[0];
        				
        				$sql5  = "INSERT INTO tmpplanpro01 ";
						$sql5 .= " (sordno, sbuycd, procd, procdcol, ordqty, procdde, procduom, ";
						$sql5 .= "  usernm) ";
						$sql5 .= "  values "; 
						$sql5 .= " ('$pordno', '$probuy', '$prodcd', '$prodcol', '$prodordqty', '$prodde', '$produom', ";
						$sql5 .= "  '$var_loginid') ";
						mysql_query($sql5) or die("Cant Insert Temp Table 1 :".mysql_error());
						
						$sql2  = "select count(*) from costing_matord ";
        				$sql2 .= " where sordno ='$pordno' And sbuycd='$probuy'";
        				$sql2 .= " And procd = '$prodcd'";
        				$sql_result2 = mysql_query($sql2) or die('5 '.mysql_error());
        				$row2 = mysql_fetch_array($sql_result2);
        				$cntc = $row2[0];
						
						if ($cntc == 0){
						    
						    $sql4 = "SELECT rm_code, rm_comps, rm_desc, rm_uom, rm_seqno FROM prod_matlis";
             				$sql4 .= " Where prod_code = '$prodcd'"; 
             				$sql4 .= " ORDER BY rm_seqno";  
							$rs_result4 = mysql_query($sql4) or die("Cant Query Planning Detail Table :".mysql_error());

							while ($row4 = mysql_fetch_assoc($rs_result4)){
								$sumconp = $prodordqty * $row4['rm_comps'];		
								$rmcode  = stripslashes(mysql_real_escape_string($row4['rm_code']));
								$rmdesc  = mysql_real_escape_string($row4['rm_desc']);
								$rmuom   = $row4['rm_uom'];
								$rmcomp  = $row4['rm_comps'];
								$rmseq   = $row4['rm_seqno'];					
						
								$sql1  = "INSERT INTO tmpplancol ";
								$sql1 .= " (orderno, buycd, procd, procdcol, procddesc, procduom, ordqty, ";
								$sql1 .= "  rmseqno, rm_code, rm_cddesc, rm_comps, rm_uom, sumcomps, usernm) ";
								$sql1 .= "  values "; 
								$sql1 .= " ('$pordno', '$probuy', '$prodcd', '$prodcol', '$prodde', '$produom', '$prodordqty', ";
								$sql1 .= "  '$rmseq', '$rmcode', '".$rmdesc."', '$rmcomp', '$rmuom', '$sumconp', '$var_loginid') ";
								mysql_query($sql1) or die("Cant Insert Temp Table 2 :".mysql_error());
							}	
                		}
                	}
                }
                
				$sql  = "select distinct procd, procdde, procduom, sum(ordqty) ";
            	$sql .= " from tmpplanpro01 ";
				$sql .= " Where usernm = '$var_loginid'";
				$sql .= " group by 1, 2, 3";
				$sql .= " order by procd";
                $sql_result = mysql_query($sql) or die("Cant query Temp Table : ".mysql_error());
                
				$i = 1;	
                while ($row = mysql_fetch_assoc($sql_result)){
                	$prodcd = htmlentities($row['procd']);
                	$prodde = htmlentities($row['procdde']);
                	$produo = $row['procduom'];
                	$prodqt = number_format($row['sum(ordqty)'], 0,".",",");
                	
                	$sql1 = "SELECT count(*)";
					$sql1 .= " from prod_matmain"; 
             		$sql1 .= " Where  prod_code = '$prodcd'";
					$sql_result2 = mysql_query($sql1);
        			$row2 = mysql_fetch_array($sql_result2);        
        			$cntcost = $row2[0];
        			if ($cntcost != 0){
        				$flgcst = "YES";
        				$plhtml = '<input name="propl[]" value='.$prodcd.' id="propl'.$i.'" type="checkbox" align="middle" style="width: 50px;">';
        			}else{
        				$flgcst = "NO";
        				$plhtml = '<input name="propl[]" value='.$prodcd.' id="propl'.$i.'" type="checkbox" align="middle" disabled style="width: 50px;" title="Create Costing For This Product In Order To Do Planning">';
        			}
        			
					$urlviwcol = "detplanord.php?pr=".$prodcd;	
				?>
					<tr>
						<td><input name="seqno[]" id="seqno" value="<?php echo $i; ?>" readonly="readonly" style="width: 20px; border:0;"></td>
						<td><input name="procd[]" id="procd" value="<?php echo $prodcd; ?>" readonly="readonly" style="width: 150px; border:0;"></td>
						<td><input name="prode[]" id="prode" value="<?php echo $prodde; ?>" readonly="readonly" style="width: 331px; border:0;"></td>
						<td><input name="prouo[]" id="prouo" value="<?php echo $produo; ?>" readonly="readonly" style="width: 42px; border:0;"></td>
						<td style="text-align:right;"><a href="javascript:poptastic('<?php echo $urlviwcol; ?>')" title="Sales Order Detail"><?php echo $prodqt; ?></a></td>
						<td><input name="procs[]" id="procs" value="<?php echo $flgcst; ?>" readonly="readonly" style="text-align:right; width: 50px; border:0;"></td>
						<td><?php echo $plhtml; ?></td>
					</tr>
				<?php
					$i = $i + 1;
				}
            ?>
         	</tbody>
        </table>
	   <table>
	  	<tr>
			<td style="width: 1165px; height: 22px;" align="center">
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
