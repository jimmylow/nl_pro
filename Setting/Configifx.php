<?php

      session_start();
	//Database server information.
	
//	$var_server = '219.93.61.12:9909';
	//$var_userid = 'root';
	//$var_password = 'admin9002';
	//$var_db_name='nl_db'; 
	
  
    $var_prtserver = '192.168.0.142';
	$var_server = '192.168.0.142:9909';
	$var_userid = 'root';
	$var_password = 'admin9002';
	$var_db_name='nl_db'; 
	$varrpturldb = "jdbc:mysql://".$var_server."/".$var_db_name;  
    
?>