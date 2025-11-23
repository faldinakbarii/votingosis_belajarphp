<?php 
session_start();
unset($_SESSION["loginUser"]);

header("Location: ../");
exit;

?>