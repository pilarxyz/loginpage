<?php
session_start();

if ($_SESSION['id']) {
  $is_login = true;
  $login['id'] = mysqli_real_escape_string($db, $_SESSION['id']);
  $data_user = mysqli_query($db, "SELECT * FROM users WHERE id = '$login[id]'");
  $data_user = mysqli_fetch_array($data_user);
  if (!$data_user['id']) { // fix
    session_start();
    session_destroy();
    header("Location: " . $domain . "login");
    die();
  }
} else {
  $is_login = false;
}
