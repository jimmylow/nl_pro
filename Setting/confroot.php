<?php

  if($_SERVER['SERVERNAME'] == "localhost")
  {
    define("ABS_PATH", "/nl_pro"); // Manual
  }
  else
  {
    define("ABS_PATH, dirname(__FILE__));
    // This defines the path as the directory of the containing file, normally a config.php
   }
?>
