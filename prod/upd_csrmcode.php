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
    
    if ($_POST['Submit'] == "Update"){
    	$sql  = "select *";
        $sql .= " from tmpcut01";
		$sql .= " Where usernm = '$var_loginid'";
        $sql_result = mysql_query($sql) or die('2 '.mysql_error());
         
		$vartoday = date("Y-m-d");       	
        while ($row = mysql_fetch_assoc($sql_result)){
            $cutno   = $row['cutno'];
            $cutdte  = $row['cutdte'];
            $ordno   = $row['ordno'];
            $buyno   = $row['buyno'];
            $orddte  = $row['orddte'];
            $deldte  = $row['deldte'];
            $grpno   = $row['grpno'];
            $prodcat = $row['prodcat'];
            $prodcnum= $row['prodcnum'];
            $colno   = $row['colno'];
            $sizeno  = $row['sizeno'];
            $ordqty  = $row['ordqty'];
            $prodnouom  = $row['prodnouom'];
			
			$sql  = "Update prodcutmas set cutdte = '$cutdte', ";
			$sql .= "                      grpno = '$grpno', ";
			$sql .= "                      ordqty = '$ordqty', ";
			$sql .= "                      modified_by = '$var_loginid', ";
			$sql .= "                      modified_on = '$vartoday' ";
			$sql .= " where cutno = '$cutno' and sizeno = '$sizeno' ";
			mysql_query($sql) or die(mysql_error());	
		}
		
		//----------------------------------------------------------------
     	if(!empty($_POST['rmcode']) && is_array($_POST['rmcode'])) 
		 {	
			foreach($_POST['rmcode'] as $row=>$rmcode) {
				$rmdesc = $_POST['rmdesc'][$row];
				$rmuom  = $_POST['rmuom'][$row];
				$rmmark = $_POST['rmmark'][$row];
				$rmspre = $_POST['rmspre'][$row];
				$rmcut  = $_POST['rmcut'][$row];
				$rmbund = $_POST['rmbund'][$row];
				$rmleng = $_POST['rmleng'][$row];
				$rmply  = $_POST['rmply'][$row];
				
				if ($rmmark == ""){$rmmark = 0;}
				if ($rmspre == ""){$rmspre = 0;}
				if ($rmcut == ""){$rmcut = 0;}
				if ($rmbund == ""){$rmbund = 0;}
				if ($rmleng == ""){$rmleng = 0;}
				if ($rmply == ""){$rmply = 0;}
				
				$sql2  = "Update prodcutdet set mark = '$rmmark',";
				$sql2 .= "                      spread = '$rmspre', ";
				$sql2 .= "                      cut = '$rmcut', ";
				$sql2 .= "                      bundle = '$rmbund', ";
				$sql2 .= "                      length = '$rmleng', ";
				$sql2 .= "                      ply = '$rmply' ";
				$sql2 .= " where cutno = '$cutno' and rmcode =  '$rmcode'";
				mysql_query($sql2) or die(mysql_error());					
			}				
		 }
       
		$backloc = "../prod/cut_sheet.php?menucd=".$var_menucode;
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

function mycheckdec(valdecbk, valid){

	if (valdecbk != ""){
		if(isNaN(valdecbk)) {
    	   alert('Please Enter a valid number :' + valdecbk);
    	   document.getElementById(valid).focus();
    	   valdecbk = 0;
    	}
    	document.getElementById(valid).value = parseFloat(valdecbk).toFixed(3);
    }else{
    	valdecbk = 0;
    	document.getElementById(valid).value = parseFloat(valdecbk).toFixed(3);
    }
    if (parseFloat(valdecbk) < 0){	
    	alert('Cannot Negative Value:' + valdecbk);
		document.getElementById(valid).focus();
    }
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
            	$sql  = "select rmcode, rmdesc, rmuom, sum(mark), sum(spread), sum(cut), sum(bundle) ";
            	$sql .= " from prodcutdet";
				$sql .= " Where cutno = '$cutno'";
				$sql .= " group by 1, 2, 3";
                $sql_result = mysql_query($sql) or die(mysql_error());
                
				$i = 1;	
                while ($row = mysql_fetch_assoc($sql_result)){
                	
						$rmcode  = htmlentities($row['rmcode']);
						$rmdesc  = htmlentities($row['rmdesc']);
						$rmuom   = $row['rmuom'];
						$rmmark  = $row['mark'];
						$rmspre  = $row['spread'];
						$rmcut   = $row['cut'];
                	    $rmbundl = $row['bundle'];
                	    if ($rmmark == ""){$rmmark = 0;}
                	    if ($rmspre == ""){$rmspre = 0;}
                	    if ($rmcut == ""){$rmcut = 0;}
                	    if ($rmbundl == ""){$rmbundl = 0;}
                	    
                	    $sql1  = "select main_code ";
  						$sql1 .= " from rawmat_subcode";
    					$sql1 .= " where rm_code ='$rmcode'";
    					$sql_result1 = mysql_query($sql1);
    					$row1 = mysql_fetch_array($sql_result1);
    					$maincode  = $row1['main_code'];
    						
    					$sql1  = "select category ";
  						$sql1 .= " from rawmat_master";
    					$sql1 .= " where rm_code ='$maincode'";
    					$sql_result1 = mysql_query($sql1);
    					$row1 = mysql_fetch_array($sql_result1);
    					$catcd  = $row1['category'];
                	    
                	    $sql1  = "select mark, cut, spread, bundle ";
  						$sql1 .= " from cat_master";
    					$sql1 .= " where cat_code ='$catcd'";
    					$sql_result1 = mysql_query($sql1);
    					$row1 = mysql_fetch_array($sql_result1);
    					$tmark   = $row1['mark'];
    					$tcut    = $row1['cut'];
    					$tspread = $row1['spread'];
    					$tbundle  = $row1['bundle'];
    					
    					if($rmmark == 0){$rmmark = $tmark;}
                	    if($rmspre == 0){$rmspre = $tcut;}
                	    if($rmcut  == 0){$rmcut  = $tspread ;}
                	    if($rm_bundl == 0){$rm_bundl = $tbundle;}
                	    
                	    $rmmark  = number_format($rmmark,'3','.','');
						$rmspre  = number_format($rmspre,'3','.','');
						$rmcut   = number_format($rmcut,'3','.','');
                	    $rmbundl = number_format($rmbundl,'3','.','');
						        				
					echo '<tr>';
					echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 20px; border:0;"></td>';
					echo '<td><input name="rmcode[]" id="rmcode'.$i.'" value="'.$rmcode.'" readonly="readonly" style="width: 100px;"></td>';
					echo '<td><input name="rmdesc[]" id="rmdesc'.$i.'" value="'.$rmdesc.'" readonly="readonly" style="width: 210px; border:0;"></td>';
					echo '<td><input name="rmuom[]" id="rmuom'.$i.'" value="'.$rmuom.'" readonly="readonly" style="width: 30px; border:0;"></td>';
					echo '<td><input name="rmmark[]" id="rmmark'.$i.'" value="'.$rmmark.'" style="text-align:right; width: 50px;" onblur="mycheckdec(this.value, this.id)"></td>';
					echo '<td><input name="rmspre[]" id="rmspre'.$i.'" value="'.$rmspre.'" style="text-align:right; width: 50px;" onblur="mycheckdec(this.value, this.id)"></td>';
					echo '<td><input name="rmcut[]" id="rmcut'.$i.'" value="'.$rmcut.'" style="text-align:right; width: 50px;" onblur="mycheckdec(this.value, this.id)"></td>';
					echo '<td><input name="rmbund[]" id="rmbund'.$i.'" value="'.$rmbundl.'" style="text-align:right; width: 50px;" onblur="mycheckdec(this.value, this.id)"></td>';
					echo '<td><input name="rmleng[]" id="rmleng'.$i.'" style="text-align:right; width: 50px;" onblur="mycheckdec(this.value, this.id)"></td>';
					echo '<td><input name="rmply[]" id="rmply'.$i.'" style="text-align:right; width: 50px;" onblur="mycheckdec(this.value, this.id)"></td>';
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
				 $locatr = "cut_sheet.php?menucd=".$var_menucode;
			
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
