<?php 
session_start();
session_destroy();
session_abort();
$_SESSION = [];

header("Location: ../");
exit;

?>