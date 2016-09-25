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
    
    if ($_POST['Submit'] == "Save"){
    	$sql  = "select *";
        $sql .= " from tmpcut01";
		$sql .= " Where usernm = '$var_loginid'";
        $sql_result = mysql_query($sql) or die('2 '.mysql_error());
         
		//$vartoday = date("Y-m-d"); 
		$vartoday = date("Y-m-d H:i:s");      	
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
			
			$sql  = "insert into prodcutmas values ";
			$sql .= " ('$cutno', '$cutdte', '$ordno', '$buyno','$orddte', '$deldte','$grpno', ";
			$sql .= "  '$prodcat', '$prodcnum', '$colno', '$sizeno', '$ordqty','$prodnouom', ";
			$sql .= "  '$var_loginid', '$vartoday', '$var_loginid', '$vartoday', 'A')";
			//mysql_query($sql) or die(mysql_error());	
			mysql_query($sql) or die("Error INSERT prodcutmas :".mysql_error(). ' Failed SQL is --> '. $sql); 
		}
		
		//----------------------------------------------------------------
     	if(!empty($_POST['rmcode']) && is_array($_POST['rmcode'])) 
		 {	
			foreach($_POST['rmcode'] as $row=>$rmcode) {
				//$rmdesc = $_POST['rmdesc'][$row];
				$rmdesc = mysql_real_escape_string($_POST['rmdesc'][$row]);
				
				
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
				
				$sql2  = "INSERT INTO prodcutdet values ";
				$sql2 .= " ('$cutno', '$rmcode', '$rmuom', '$rmdesc', '0', ";
				$sql2 .= "  '$rmmark', '$rmspre', '$rmcut', '$rmbund', '$rmleng', '$rmply')";
				//mysql_query($sql2) or die(mysql_error());	
				mysql_query($sql2) or die("Error INSERT prodcutdet :".mysql_error(). ' Failed SQL is --> '. $sql2); 
								
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
  	$sql .= " from tmpcut01";
    $sql .= " where usernm ='$var_loginid'";
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
            	$sql = "delete from tmpcut02 where usernm = '$var_loginid'";
            	mysql_query($sql) or die(mysql_error());
            	            	
            	$sql  = "select distinct prodcat, prodcnum, colno, sizeno, sum(ordqty) ";
            	$sql .= " from tmpcut01";
				$sql .= " Where usernm = '$var_loginid'";
				$sql .= " group by 1,2,3,4";
                $sql_result = mysql_query($sql) or die('2 '.mysql_error());
                	
                while ($row = mysql_fetch_assoc($sql_result)){
                	$prcat = $row['prodcat'];
                	$prnum = $row['prodcnum'];
                	$prcol = $row['colno'];
                	$prsiz = $row['sizeno'];
                	$prord = $row['ordqty'];
                	//$prcode = $prcat.$prnum."-".$prcol."-".$prsiz;
                	$prcode = $prcat.$prnum."-".$prcol."%"; // to get the costing regardless watever the size is 
                	
                	// distinct - to overcome the costing which is more than 1 sizes
                	$sql1  = "select distinct rm_code, rm_desc, rm_ucost, rm_mark, rm_spre, rm_cut, rm_bundl, rm_uom, rm_seqno ";
            		$sql1 .= " from prod_matlis";
					$sql1 .= " Where prod_code LIKE '$prcode' ";
					$sql1 .= " order by rm_seqno";
                	$sql_result1 = mysql_query($sql1) or die('3 '.mysql_error());
                	//echo $sql1;
                	
                	while ($row1 = mysql_fetch_assoc($sql_result1)){
                		$rmcode = htmlentities($row1['rm_code']);
                		$rmdesc = mysql_real_escape_string($row1['rm_desc']);
                		//$rmdesc = mysql_real_escape_string($row1['rm_desc']);
                		$rmcost = $row1['rm_ucost'];
                		$rmmark = $row1['rm_mark'];
                		$rmspre = $row1['rm_spre'];
						$rmcut  = $row1['rm_cut'];
                	    $rm_bundl  = $row1['rm_bundl'];
                	    $rmuom  = $row1['rm_uom'];
                	    if ($rmcost == ""){$rmcost = 0;}
                	    if ($rmmark == ""){$rmmark = 0;}
                	    if ($rmspre == ""){$rmspre = 0;}
                	    if ($rmcut == ""){$rmcut = 0;}
                	    if ($rm_bundl == ""){$rm_bundl = 0;}
                	    
                	    $sql  = "select main_code ";
  						$sql .= " from rawmat_subcode";
    					$sql .= " where rm_code ='$rmcode'";
    					$sql_result = mysql_query($sql);
    					$row = mysql_fetch_array($sql_result);
    					$maincode  = $row['main_code'];
    						
    					$sql  = "select category ";
  						$sql .= " from rawmat_master";
    					$sql .= " where rm_code ='$maincode'";
    					$sql_result = mysql_query($sql);
    					$row = mysql_fetch_array($sql_result);
    					$catcd  = $row['category'];
		
						$sql  = "select mark, cut, spread, bundle ";
  						$sql .= " from cat_master";
    					$sql .= " where cat_code ='$catcd'";
    					$sql_result = mysql_query($sql);
    					$row = mysql_fetch_array($sql_result);
    					$tmark   = $row['mark'];
    					$tcut    = $row['cut'];
    					$tspread = $row['spread'];
    					$tbundle  = $row['bundle'];
    					
    					if ($tmark== ""){$tmark= 0;}
                	    if ($tcut== ""){$tcut= 0;}
                	    if ($tspread == ""){$tspread = 0;}
                	    if ($tbundle== ""){$tbundle= 0;}

    					
    					if($rmmark == 0){$rmmark = $tmark;}
                	    if($rmspre == 0){$rmspre = $tcut;}
                	    if($rmcut  == 0){$rmcut  = $tspread ;}
                	    if($rm_bundl == 0){$rm_bundl = $tbundle;}
                	    
                	    
                	    $sqlp  = " insert into tmpcut02 values ";
                	    $sqlp .= " ('$rmcode', '$rmcost', '$rmdesc', '$rmmark', '$rmspre', '$rmcut', '$rm_bundl', '$var_loginid', '$rmuom')";
                	    //mysql_query($sqlp) or die(mysql_error());
                	    mysql_query($sqlp) or die("Error INSERT tmpcut02 :".mysql_error(). ' Failed SQL is --> '. $sqlp);  
					}       
				}                

            	$sql  = "select distinct rmcode, rmuom, sum(mark), sum(spread), sum(cut), sum(bundle) ";
            	$sql .= " from tmpcut02";
				$sql .= " Where usernm = '$var_loginid'";
				$sql .= " group by 1, 2";
                $sql_result = mysql_query($sql) or die(mysql_error());
                
				$i = 1;	
                while ($row = mysql_fetch_assoc($sql_result)){
                	
						$rmcode  = htmlentities($row['rmcode']);

						$rmuom   = $row['rmuom'];
						$rmmark  = $row['sum(mark)'];
						$rmspre  = $row['sum(spread)'];
						$rmcut   = $row['sum(cut)'];
                	    $rmbundl = $row['sum(bundle)'];
                	    if ($rmmark == ""){$rmmark = 0;}
                	    if ($rmspre == ""){$rmspre = 0;}
                	    if ($rmcut == ""){$rmcut = 0;}
                	    if ($rmbundl == ""){$rmbundl = 0;}
                	    $rmmark  = number_format($rmmark,'3','.','');
						$rmspre  = number_format($rmspre,'3','.','');
						$rmcut   = number_format($rmcut,'3','.','');
                	    $rmbundl = number_format($rmbundl,'3','.','');
                	    
                	    $sl  = "select description ";
  						$sl .= " from rawmat_subcode";
    					$sl .= " where rm_code ='$rmcode'";
    					$sl_result = mysql_query($sl);
    					$rw = mysql_fetch_array($sl_result);
    					$rmdesc   = htmlentities($rw['description']);

						        				
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
