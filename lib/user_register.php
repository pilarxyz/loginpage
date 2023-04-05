<?php
$recaptcha = mysqli_query($db, "SELECT * FROM recaptcha");
$recaptcha = mysqli_fetch_array($recaptcha);

function phone_62_fix($nohp)
{
    $nohp = str_replace(" ", "", $nohp);
    $nohp = str_replace("(", "", $nohp);
    $nohp = str_replace(")", "", $nohp);
    $nohp = str_replace(".", "", $nohp);
    if (!preg_match('/[^+0-9]/', trim($nohp))) {
        if (substr(trim($nohp), 0, 4) == '+620') {
            $hp = '62' . substr(trim($nohp), 4);
        } else if (substr(trim($nohp), 0, 3) == '+62') {
            $hp = '62' . substr(trim($nohp), 3);
        } else if (substr(trim($nohp), 0, 1) == '0') {
            $hp = '62' . substr(trim($nohp), 1);
        } else if (substr(trim($nohp), 0, 1) == '8') {
            $hp = '628' . substr(trim($nohp), 1);
        } else if (substr(trim($nohp), 0, 4) == '6208') {
            $hp = '628' . substr(trim($nohp), 4);
        } else if (substr(trim($nohp), 0, 2) == '62') {
            $hp = '62' . substr(trim($nohp), 2);
        }
    }
    return $hp;
}




if ($_POST) {
    //fix htmlspecial bagian insert juga $data_name $data_email $data_phone
    $name = mysqli_real_escape_string($db, htmlspecialchars($_POST['name']));
    $email = mysqli_real_escape_string($db, htmlspecialchars($_POST['email']));
    $phone = mysqli_real_escape_string($db, phone_62_fix(htmlspecialchars($_POST['phone'])));
    $password = mysqli_real_escape_string($db, htmlspecialchars($_POST['password']));
    $otentikasi = mysqli_real_escape_string($db, $_POST['otentikasi']);
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $date = date('Y-m-d H:i:s');

    $captcha        = $_POST['g-recaptcha-response'];
    $secretKey        = $recaptcha['recaptcha_secret_key'];
    $ip             = $_SERVER['REMOTE_ADDR'];
    $response        = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $captcha . "&remoteip=" . $ip);
    $responseKeys    = json_decode($response, true);
    $check_email = mysqli_query($db, "SELECT * FROM users WHERE email = '$email' OR ponsel='$phone'");



    if ($recaptcha['recaptcha_status'] == 1) {
        if (intval($responseKeys["success"]) !== 1) {
            $toast_type = 'error';
            $toast_message = 'Gagal, Captcha tidak di Centang.';
        } else {

            if (mysqli_num_rows($check_email) > 0) {
                $toast_type = 'error';
                $toast_message = 'Gagal, Email / Ponsel sudah terpakai.';
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $toast_type = 'error';
                $toast_message = 'Gagal, Email tidak valid.';
            } else if ($name && $email && $phone && $password && $date) {

                $cURL = curl_init();
                curl_setopt($cURL, CURLOPT_URL, "https://whapify.id/api/get/otp?secret=$apikey&otp=$otentikasi");
                curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
                $responseJson = curl_exec($cURL);
                curl_close($cURL);

                $resultJson = json_decode($responseJson, true);

                //print_r($resultJson);
                if ($resultJson['message'] == 'OTP has been verified!') {




                    $daftar = mysqli_query($db, "INSERT INTO users (id, nama, email, password, ponsel, tanggal) VALUES(DEFAULT, '$name', '$email', '$password_hash', '$phone', '$date')");

                    if ($daftar) {

                        $_SESSION['id'] = $user_id = mysqli_insert_id($db);
                        $toast_type = 'success';
                        $toast_message = 'Sukses mendaftar.';
                        header('Refresh: 1; URL=' . $domain);
                    } else {
                        $toast_type = 'error';
                        $toast_message = 'Gagal, Sistem error, hubungi admin.';
                        // print error to txt
                        $error = mysqli_error($db);
                        $myfile = fopen("error.txt", "w") or die("Unable to open file!");
                        fwrite($myfile, $error);
                        fclose($myfile);
                    }
                } else {
                    $toast_type = 'error';
                    $toast_message = 'Gagal, Kode Otentikasi Salah.';
                }
            } else {
                $toast_type = 'error';
                $toast_message = 'Gagal, Mohon isi semua formulir.';
            }
        }
    } else {
        if (mysqli_num_rows($check_email) > 0) {
            $toast_type = 'error';
            $toast_message = 'Gagal, Email / Ponsel sudah terpakai.';
        } else if ($name && $email && $phone && $password && $date) {
            $daftar = mysqli_query($db, "INSERT INTO users (id, nama, email, password, ponsel, tanggal) VALUES(DEFAULT, '$name', '$email', '$password_hash', '$phone', '$date')");

            if ($daftar) {
                $_SESSION['id'] = $user_id = mysqli_insert_id($db);
                $toast_type = 'success';
                $toast_message = 'Sukses mendaftar.';
                header('Refresh: 1; URL=' . $domain);
            } else {
                $toast_type = 'error';
                $toast_message = 'Gagal, Sistem error, hubungi admin.';
            }
        } else {
            $toast_type = 'error';
            $toast_message = 'Gagal, Mohon isi semua formulir.';
        }
    }
}
