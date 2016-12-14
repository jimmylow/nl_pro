<?php
	$sql = "SELECT ticketno FROM sew_entry ";
    $sql .= " WHERE productcode ='".$value."'";
	$sql .= " ORDER by ticketno desc limit 1";
	$rResult = mysql_query($sql) or die("Error Checking existing Product Code Master :".mysql_error(). ' Failed SQL is -->'. $sql);
    //$row = mysql_fetch_object($rResult);
	$numSewEntry=mysql_numrows($rResult);          	
	$i=0;
	while ($i < $numSewEntry) {           	
		$ticketno=mysql_result($rResult,$i,"ticketno");
		$msg = "Cannot delete the Product Code Master [" .$value. "] because Ticket No " .$ticketno. " exist at system.";
		$i++;
           		
		echo "<script>";
 		echo "alert('".$msg."')";
		echo "</script>";
		$isexist = 1;
	}
?> 