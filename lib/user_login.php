<?php
$recaptcha = mysqli_query($db, "SELECT * FROM recaptcha");
$recaptcha = mysqli_fetch_array($recaptcha);

if ($_POST) {
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    $captcha = $_POST['g-recaptcha-response'];
    $secretKey = $recaptcha['recaptcha_secret_key'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
    $responseKeys = json_decode($response,true);

    $toast_type = '';
    $toast_message = '';

    if ($email && $password) {
        if ($recaptcha['recaptcha_status'] == 1)  {
            if(intval($responseKeys["success"]) !== 1) {
                $toast_type = 'error';
                $toast_message = 'Captcha tidak di Centang.';
            } else {
                $login = mysqli_query($db, "SELECT id, email, password FROM users WHERE email = '$email'");
                list($id_user, $email, $pw) = mysqli_fetch_array($login);
                if (mysqli_num_rows($login) > 0) {
                    if (password_verify($password, $pw)) {
                        $_SESSION['id'] = $id_user;
                        $token = bin2hex(random_bytes(64));
                        setcookie('token', $token, time() + (86400 * 30), '/');
                        $toast_type = 'success';
                        $toast_message = 'Sukses Login.';
                        header('Refresh: 1; URL='.$domain);
                    } else {
                        $toast_type = 'error';
                        $toast_message = 'Email atau Password salah.';
                    }
                } else {
                    $toast_type = 'error';
                    $toast_message = 'Akun tidak ditemukan.';
                }
            }
        } else {
            $toast_type = 'error';
            $toast_message = 'Mohon isi semua formulir.';
        }
    }
}
