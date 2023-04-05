<?php
require 'config.php';
require 'lib/header.php';
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
    if ($recaptcha['recaptcha_status'] == 1) {
        $captcha = $_POST['g-recaptcha-response'];
        $secretKey = $recaptcha['recaptcha_secret_key'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $captcha . "&remoteip=" . $ip);
        $responseKeys = json_decode($response, true);
        if ($responseKeys['success'] !== true) {
            $toast_type = 'error';
            $toast_message = 'Captcha tidak di Centang.';
        } else {
            $input_data = $_POST['ponsel'];
            $input_data = htmlspecialchars($input_data);
            // random password
            $newpw = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
            $password_baru = password_hash($newpw, PASSWORD_DEFAULT);
            $phone = phone_62_fix($input_data);
            $check_user2 = mysqli_query($db, "SELECT * FROM users WHERE ponsel = '$phone'");
            if (mysqli_num_rows($check_user2) == 1) {
                $check_user = mysqli_fetch_assoc($check_user2);
                mysqli_query($db, "UPDATE users SET password = '$password_baru' WHERE ponsel = '$phone'");

                $res = "Ini Kata Sandi Baru Anda  : " . $newpw;

                $data = "secret=$apikey&account=$account&recipient=$phone&type=text&message=$res";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://whapify.id/api/send/whatsapp');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                $result = curl_exec($ch);
                curl_close($ch);
                $JsonKirim = json_decode($result, true);

                if ($JsonKirim['status'] == 200) {
                    $toast_type = "success";
                    $toast_message = "Kata Sandi berhasil dikirim";
                } else {
                    $toast_type = "error";
                    $toast_message = "Kata Sandi gagal dikirim";
                }
            } else {
                $toast_type = "error";
                $toast_message = "WhatsApp tidak terdaftar.";
            }
        }
    }
}

?>
<div class="container">
    <div class="row vh-100 justify-content-center align-items-center">
        <div class="col-md-6">
            <a href="<?php echo $domain; ?>" class="text-decoration-none">
                <h2 class="text-center mb-4 animate__animated animate__fadeInDown">Reset Kata Sandi <i class="fas fa-lock"></i></h2>
            </a>
            <div class="form-container animate__animated animate__fadeIn">
                <form method="POST" action="">
                    <div class="form-group mb-3">
                        <label class="text-dark animate__animated animate__fadeInLeft">Nomor WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text animate__animated animate__fadeInLeft"><i class="fas fa-phone"></i></span>
                            <input type="number" name="ponsel" class="form-control animate__animated animate__fadeInLeft" placeholder="Masukan Nomor Whatsapp" required>
                        </div>
                    </div>
                    <?php if ($recaptcha['recaptcha_status'] == 1) { ?>
                        <div class="form-group mb-3">
                            <div class="g-recaptcha animate__animated animate__fadeInUp" data-sitekey="<?= $recaptcha['recaptcha_site_key']; ?>"></div>
                        </div>
                    <?php } ?>
                    <div class="mb-3 animate__animated animate__fadeInUp">
                        <span class="text-danger">*Kata Sandi baru akan dikirimkan ke nomor WhatsApp yang terdaftar.</span>
                    </div>




                    <button class="btn btn-primary w-100 animate__animated animate__fadeInUp" name="reset" type="submit" onclick="onSubmit()">Reset</button>
                    <!-- create back button to login -->
                    <div class="mt-3 animate__animated animate__fadeInUp">
                        <a href="<?php echo $domain . '/login'; ?>" class="text-decoration-none">Kembali ke Halaman Login</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function onSubmit() {
        event.preventDefault();
        const phone = document.querySelector('input[name="ponsel"]').value;

        if (phone === '') {
            iziToast.error({
                title: 'Error',
                message: 'Nomor WhatsApp tidak boleh kosong',
                position: 'topRight',
            });
        } else {
            document.querySelector('form').submit();
        }
    }
</script>
<?php
require 'lib/footer.php';
?>