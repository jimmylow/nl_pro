<?php 

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

	$buyr = $_GET['b'];
    $sordno = "";
	$sql = "SELECT sordno, sordnobuyer ";
	$sql.= " FROM salesentry ";
	$sql.= " where sbuycd = '$buyr'";
	$sql.= " and stat = 'ACTIVE'";
	$sql.= " ORDER BY sorddte desc ";
	$rs_result = mysql_query($sql); 
	if ($rs_result <> FALSE)
	{
		echo '<select name="sordno" id="sordno" style="width: 210px">';
        echo "<option size =30 selected></option>";
             
		if(mysql_num_rows($rs_result)) 
		{
			 while($rowq = mysql_fetch_assoc($rs_result)) 
			 { 
				echo '<option value="'.$rowq['sordno'].'">'.$rowq['sordno']." | ".$rowq['sordnobuyer'].'</option>';
			 } 
		} 			   
		echo '</select>';
		echo '<input type=submit name = "Submit" value="GetItem" class="butsub" style="width: 70px; height: 20px" >';
	}else{
		echo 'No Invoice to display';
		
	}
?>
