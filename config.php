<?php
date_default_timezone_set('Asia/Jakarta');
error_reporting(0);
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
session_start();
ini_set('memory_limit', -1);

$domain = 'xx';

$db_server = "localhost";
$db_username = "xx";
$db_password = "xx";
$db_name = "xx";

$db = mysqli_connect($db_server, $db_username, $db_password, $db_name) or die("Error in connection!");
mysqli_select_db($db, $db_name ) or die("Could not select database");

$date = date('Y-m-d H:i:s');

// whapify
$apikey = 'xx';
$account = 'xx';

// require dirname(__FILE__).'/lib/remember.php';
require dirname(__FILE__).'/lib/cek_login.php';

?>
