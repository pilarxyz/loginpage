<?php
require '../config.php';

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
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
    $nomorWa = mysqli_real_escape_string($db, phone_62_fix($_POST['whatsapp']));





    $captcha        = $_POST["captcha"];
    $secretKey        = $recaptcha['recaptcha_secret_key'];
    $ip             = $_SERVER['REMOTE_ADDR'];
    $response        = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $captcha . "&remoteip=" . $ip);
    $responseKeys    = json_decode($response, true);

    // print response array to file txt
    $file = fopen("response.txt", "w");
    fwrite($file, print_r($responseKeys, true));
    fclose($file);



    $array['status'] = false;

    $isNomorWa = mysqli_query($db, "SELECT * FROM users WHERE ponsel = '$nomorWa'");
    $isNomorWa = mysqli_fetch_array($isNomorWa);


    if (intval($responseKeys["success"]) !== 1) {
        $toast_type = 'error';
        $toast_title = 'Gagal, Captcha tidak di Centang' . $responseKeys["success"];
    } else {
        if ($isNomorWa['id']) {

            $toast_type = 'error';
            $toast_title = 'Pengguna telah terdaftar, Silahkan Masuk untuk melanjutkan';
        } else {

            $pesan = "Kode otentikasi Anda adalah {{otp}}, Kode ini hanya berlaku 3 menit.";
            $data = "secret=$apikey&account=$account&type=whatsapp&phone=$nomorWa&expire=180&message=$pesan";
            $cURL = curl_init("https://whapify.id/api/send/otp");
            curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURL, CURLOPT_POSTFIELDS, $data);
            $response = curl_exec($cURL);
            curl_close($cURL);

            $result = json_decode($response, true);

            if ($result['status'] == 200) {
                $toast_type = 'success';
                $toast_title = 'Kode OTP berhasil dikirim ke Whatsapp Kamu';
                $array['status'] = true;
            } else {
                $toast_type = 'error';
                $toast_title = 'Gagal mengirim Kode OTP, Nomor WhatsApp tidak ditemukan ';
            }
        }
    }
    $array['msg'] = $toast_title;
    print_r(json_encode($array));
} else {
    exit("No direct script access allowed!");
}
