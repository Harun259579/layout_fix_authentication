<?php
session_start();
session_unset();
session_destroy();

//Redirect

header("location: login.php");
exit;

?>