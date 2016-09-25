<?php 
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	
	if(!isset($_GET['or']) || !$method = $_GET['or']) exit;
  	if(!isset($_GET['by']) || !$method = $_GET['by']) exit;
  	if(!isset($_GET['cat']) || !$method = $_GET['cat']) exit;
  	if(!isset($_GET['prnum']) || !$method = $_GET['prnum']) exit;
  	if(!isset($_GET['cl']) || !$method = $_GET['cl']) exit;
    
  	$ord = $_GET['or'];
  	$buy = $_GET['by'];
  	$cat = $_GET['cat'];
  	$cdn = $_GET['prnum'];
  	$col = $_GET['cl'];

   	echo '<table id="itemsTable" class="general-table" align="center">
          	<thead>
          	 <tr>
              <th class="tabheader" style="width: 27px;">#</th>
              <th class="tabheader">Size</th>
              <th class="tabheader">Order Quantity</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Select</th>
             </tr>
            </thead>
            <tbody>';
     
             	$query  = "SELECT distinct y.prod_size, sum(x.sproqty), x.sprouom  FROM salesentrydet x, pro_cd_master y ";
  				$query .= " WHERE x.sordno='$ord' and x.sbuycd='$buy'";
  				$query .= " and   y.prod_code = x.sprocd";
  				$query .= " and   y.prod_catpre = '$cat'";
  				$query .= " and   y.pronumcode = '$cdn'";
  				$query .= " and   y.prod_col = '$col'";
  				$query .= " group by 1, 3";
  				$query .= " order by 1";	
				$rs_result = mysql_query($query) or die(mysql_error()); 
			   
			  $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){
					$prsize = htmlentities($rowq['prod_size']);
					$ordqty = $rowq['sum(x.sproqty)'];
					$pruom  = $rowq['sprouom'];
				
					echo '<tr class="item-row">';	
					echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 30px; border:0;"></td>'; 
					echo '<td><input name="prsize[]" value="'.$prsize.'" id="prsize'.$i.'" style="width: 100px; border-style: none;"  readonly="readonly"></td>';
          echo '<td><input name="ordqty[]" value="'.$ordqty.'" id="ordqty'.$i.'" style="width: 100px; text-align: right" onblur="mycheckdec(this.value, '.$i.')"></td>';
          echo '<td><input name="pruom[]" value="'.$pruom.'" id="pruom'.$i.'" readonly="readonly" style="border-style: none; width: 50px;"></td>';
          echo '<td><input name="chk[]" value="'.$chk.'" id="chk'.$i.'" type="checkbox" checked="yes" value = "1" title="Tick to Select Size(s) Need for Cutting Sheet"></td>';
          echo ' </tr>';     	
          $i = $i + 1;
        }
  		echo '</tbody></table>';  
?>
