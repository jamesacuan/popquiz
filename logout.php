<?php
session_start();
session_unset();
session_destroy();
//echo $_SESSION['$firstrun'];
//print_r($_SESSION);
//echo "<script>alert(" . $_SESSION['$firstrun'] . ")</script>";
header("location:index.php");
?>
