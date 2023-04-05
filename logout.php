<?php
require 'config.php';
session_start();
session_destroy();
setcookie('token', '', time() - (3600 * 168), '/');
header("Location: " . $domain . "");
