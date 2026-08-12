<?php 
session_start();
session_unset();
session_destroy();
header("Location:/LapStock/index.php");
exit();
?>