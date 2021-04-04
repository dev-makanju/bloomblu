<?php
session_start();
unset($_SESSION['user']);
session_destroy();
//redirect to index page
header("location:index.php");
?>