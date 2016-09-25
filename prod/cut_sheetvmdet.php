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
      $cutno = $_GET['c'];
      include("../Setting/ChqAuth.php");
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

</script>
</head>
<?php
	$sql = "select distinct cutno, prodcat, prodcnum, colno ";
  	$sql .= " from prodcutmas";
    $sql .= " where cutno ='$cutno'";
    $sql_result = mysql_query($sql);
    $row = mysql_fetch_array($sql_result);

    $cutno  = $row['cutno'];
    $prcat  = $row['prodcat'];
    $prcnum = $row['prodcnum'];
    $colno  = $row['colno'];
    
    $prcode = $prcat.$prcnum;
?>
 
<body>
   <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>-->

  <div class ="contentc">
 
	<fieldset name="Group1" style=" width: 912px;" class="style2">
	 <legend class="title">CUTTING SHEET - CUTTING RM CODE DETAIL<?php echo  $var_costinno; ?></legend>
	  <br>	 
	  <form name="InpCutDet" method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF'].'?menucd='.$var_menucode); ?>" style="width: 917px">		
		<table>
			<tr>
				<td></td>
				<td>Cutting No</td>
				<td>:</td>
				<td><?php echo $cutno; ?></td>
				
			</tr>
			<tr><td></td></tr>
			<tr>
				<td></td>
				<td>Product Code</td>
				<td>:</td>
				<td><?php echo $prcode;?></td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td></td>
				<td>Color Code</td>
				<td>:</td>
				<td><?php echo $colno;?></td>
			</tr>
	    </table>
	    <br>		
		  <table id="itemsTable" class="general-table" style="width: 100%">
          	<thead>
          	 <tr>
          	  <th colspan="8"></th>
          	  <th colspan="2">Measurement</th>	
          	 </tr>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader">Item Code</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Mark</th>
              <th class="tabheader">Spread</th>
              <th class="tabheader">Cut</th> 
              <th class="tabheader">Bundle</th>
              <th class="tabheader">Length</th>
              <th class="tabheader">Ply</th>
             </tr>
            </thead>
            <tbody>
            <?php
            	$sql  = "select rmcode, rmdesc, rmuom, sum(mark), sum(spread), sum(cut), sum(bundle), sum(length), sum(ply)";
            	$sql .= " from prodcutdet";
				$sql .= " Where cutno = '$cutno'";
				$sql .= " group by 1, 2, 3";
                $sql_result = mysql_query($sql) or die(mysql_error());
                
				$i = 1;	
                while ($row = mysql_fetch_assoc($sql_result)){
                	    
						$rmcode  = htmlentities($row['rmcode']);
						$rmdesc  = stripslashes(mysql_real_escape_string($row['rmdesc']));
						$rmuom   = $row['rmuom'];
						$rmmark  = $row['sum(mark)'];
						$rmspre  = $row['sum(spread)'];
						$rmcut   = $row['sum(cut)'];
                	    $rmbundl = $row['sum(bundle)'];
                	    $rmlength= $row['sum(length)'];
						$rmply   = $row['sum(ply)'];

                	    if ($rmmark == ""){$rmmark = 0;}
                	    if ($rmspre == ""){$rmspre = 0;}
                	    if ($rmcut == ""){$rmcut = 0;}
                	    if ($rmbundl == ""){$rmbundl = 0;}
                        if ($rmlength == ""){$rmlength = 0;}
                	    if ($rmply == ""){$rmply = 0;}

                	    $rmmark  = number_format($rmmark,'3','.','');
						$rmspre  = number_format($rmspre,'3','.','');
						$rmcut   = number_format($rmcut,'3','.','');
                	    $rmbundl = number_format($rmbundl,'3','.','');
                	    $rmlength = number_format($rmlength,'3','.','');
						$rmply = number_format($rmply,'3','.','');

						        				
					echo '<tr>';
					echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 20px; border:0;"></td>';
					echo '<td><input name="rmcode[]" id="rmcode'.$i.'" value="'.$rmcode.'" readonly="readonly" style="width: 100px;"></td>';
					echo '<td><input name="rmdesc[]" id="rmdesc'.$i.'" value="'.$rmdesc.'" readonly="readonly" style="width: 210px; border:0;"></td>';
					echo '<td><input name="rmuom[]" id="rmuom'.$i.'" value="'.$rmuom.'" readonly="readonly" style="width: 30px; border:0;"></td>';
					echo '<td><input name="rmmark[]" id="rmmark'.$i.'" value="'.$rmmark.'" readonly="readonly" style="text-align:right; width: 50px;"></td>';
					echo '<td><input name="rmspre[]" id="rmspre'.$i.'" value="'.$rmspre.'" readonly="readonly" style="text-align:right; width: 50px;"></td>';
					echo '<td><input name="rmcut[]" id="rmcut'.$i.'" value="'.$rmcut.'" readonly="readonly" style="text-align:right; width: 50px;"></td>';
					echo '<td><input name="rmbund[]" id="rmbund'.$i.'" value="'.$rmbundl.'" readonly="readonly" style="text-align:right; width: 50px;"></td>';
					echo '<td><input name="rmleng[]" id="rmleng'.$i.'" value="'.$rmlength.'" style="text-align:right; width: 50px;" readonly="readonly"></td>';
					echo '<td><input name="rmply[]" id="rmply'.$i.'" value="'.$rmply.'" style="text-align:right; width: 50px;" readonly="readonly"></td>';
					echo '</tr>';
					$i = $i + 1;
				}
            ?>
         	</tbody>
        </table>
	   <table>
	  	<tr>
			<td style="width: 1165px; height: 22px;" align="center">
			<?php
				 $locatr = "vm_cutsheet.php?menucd=".$var_menucode."&c=".$cutno;
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
