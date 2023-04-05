<?php
require 'config.php';
require 'lib/user_login.php';
require 'lib/header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <a href="<?php echo $domain; ?>" class="text-decoration-none">
                <h2 class="text-center animate__animated animate__fadeInDown">Masuk <i class="fas fa-sign-in-alt"></i></h2>
            </a>
            <div class="form-container animate__animated animate__fadeIn">
                <form method="POST">
                    <div class="form-group mb-3">
                        <label for="email" class="form-label animate__animated animate__fadeInLeft">Email</label>
                        <div class="input-group">
                            <span class="input-group-text animate__animated animate__fadeInLeft"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control animate__animated animate__fadeInLeft" name="email" id="email" placeholder="Masukan alamat email" required>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="password" class="form-label animate__animated animate__fadeInRight">Password</label>
                        <div class="input-group">
                            <span class="input-group-text animate__animated animate__fadeInRight"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control animate__animated animate__fadeInRight" name="password" id="password" placeholder="Masukan password" required>
                        </div>
                    </div>

                    <div class="mb-3 animate__animated animate__fadeInUp">

                        <?php if ($recaptcha['recaptcha_status'] == 1) { ?>
                            <div class="g-recaptcha" data-sitekey="<?= $recaptcha['recaptcha_site_key']; ?>"></div>
                        <?php } ?>
                    </div>
                    <div class="mb-3 animate__animated animate__fadeInUp">
                        <span class="float-end"><a href="forgot">Lupa Password</a></span>
                        Belum memiliki akun? <br> <a href="register">Daftar Sekarang</a>
                    </div>
                    <button class="btn btn-primary w-100 mb-3 animate__animated animate__fadeInUp" type="submit" onclick="onSubmit()">Masuk</button>

                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function onSubmit() {
        event.preventDefault();
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        if (email === '' || password === '') {
            iziToast.error({
                title: 'Error',
                message: 'Semua form harus diisi',
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