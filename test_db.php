<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("config.php");  // your config file

if ($conn) {
    echo "Database connection successful!";
} else {
    echo "Database connection failed: " . mysqli_connect_error();
}
?>
