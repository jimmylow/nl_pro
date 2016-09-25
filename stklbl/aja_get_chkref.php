<?php 

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
 
	if(!isset($_GET['ref']) || !$method = $_GET['ref']) exit;
	if(!isset($_GET['pid']) || !$method = $_GET['pid']) exit;

	$vref = $_GET['ref'];
	$vpid = $_GET['pid'];
  
  	if ($vref != ""){
		$query =  "SELECT refno FROM stck_lbl "; 
		$query .=" WHERE refno='$vref'";

		$result=mysql_query($query);
		$row = mysql_fetch_array($result);

    	if (!empty($row[0])){
    	#----------------Get Create Label Item Code -------------------------
    		$sql = "SELECT distinct sub_code ";
            $sql.= " FROM stck_lbl";
            $sql.= " Where refno = '$vref'";
            $sql.= " ORDER BY sub_code";
            $rs_result = mysql_query($sql); 
			
			echo '<select name="sitmcd[]" style="width: 200px" id="sitmcd'.$vpid.'" onchange="getnooflbl(this.value, this.id, '.$vpid.')">';
			echo '<option></option>';
			while ($rowq = mysql_fetch_assoc($rs_result)){
				echo "<option value=".$rowq['sub_code'].">".$rowq['sub_code']."</option>";
			}
			echo "</select>";
    	#--------------------------------------------------------------------
    	}else{
    		echo '0';
    	}
    }	
    
?>
