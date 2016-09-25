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
      include("../Setting/ChqAuth.php");
    }
    
    if ($_POST['Submit'] == "Save") {
    	$var_plandte   = $_POST['plandte'];
    	$var_planrmk   = $_POST['planrmk'];
    	$var_planopt   = $_POST['planopt'];
 
		$backloc = "../bom_tran/mat_plan_det.php?menucd=".$var_menucode."&prmk=".$var_planrmk."&pdte=".$var_plandte."&popt=".$var_planopt;
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
var newwindow;
function poptastic(url)
{
	newwindow=window.open(url,'name','height=700,width=1000,left=30,top=50,scrollbars=yes');
	if (window.focus) {newwindow.focus()}
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
   $sql  = "select distinct orderno ";
   $sql .= " from tmpplancol";
   $sql .= " Where usernm = '$var_loginid'";
   $sql_result = mysql_query($sql) or die("Can't query Temp Table : ".mysql_error());
                	
   while ($row = mysql_fetch_assoc($sql_result)){
		$pordno = $row['orderno'];
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
	 <legend class="title">PLANNING DETAIL - BY PRODUCT (3)</legend>
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
	    </table>
	    <br>

		  <table id="itemsTable" class="general-table" style="width: 723px">
          	<thead>
          	 <tr>
              <th class="tabheader" style="width: 41px">#</th>
              <th class="tabheader" style="width: 121px">Product Code</th>
              <th class="tabheader">Description</th>
              <th class="tabheader" style="width: 39px">UOM</th>
              <th class="tabheader">Order Qty</th> 
              <th class="tabheader" style="width: 100px">Plan Detail</th>
             </tr>
            </thead>
            <tbody>
       		<?php 
       		  $sql1  = "SELECT procd, procdde, procduom, sum(ordqty) ";
       		  $sql1 .= " FROM tmpplanpro01 ";
       		  $sql1 .= " where planflg = 'Y' and usernm = '$var_loginid'";
       		  $sql1 .= " group by 1, 2, 3 ";
       		  $sql1 .= " order by 1";
              $rs_result1 = mysql_query($sql1) or die("Mysql Error =".mysql_error()); 
			  
			  $i = 1;    	
			  while ($row1 = mysql_fetch_assoc($rs_result1)){
			  	$prodcd = htmlentities($row1['procd']);
                $prodde = htmlentities($row1['procdde']);
                $produo = $row1['procduom'];
                $prodqt = number_format($row1['sum(ordqty)'], 0,".",",");

                $urlviwcol = "planprod_upd.php?prod=".$prodcd;
				echo '<tr class="item-row">';
             	echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 41px; border:0;"></td>';
             	echo '<td><input name="plancol[]" value="'.$prodcd.'" id="plcolcode'.$i.'" readonly="readonly" style="width: 100px"></td>';
             	echo '<td><input name="plandes[]" value="'.$prodde.'" id="plcoldesc'.$i.'" readonly="readonly" style="border-style: none;"></td>';
             	echo '<td><input name="planuom[]" value="'.$produo.'" id="plcoluom'.$i.'" readonly="readonly" style="border-style: none;"></td>';
             	echo '<td><input name="planqty[]" value="'.$prodqt.'" id="plcolqty'.$i.'" readonly="readonly" style="border-style: none; text-align:right"></td>';
             	echo '<td style="text-align:center"><a href=javascript:poptastic("'.$urlviwcol.'") title="Edit the Detail RM Plan For this Product Code">[EDIT]</a></td>';
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
