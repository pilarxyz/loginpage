<?php
require 'config.php';
require 'lib/header.php';
?>

<div class="container">
    <div class="row vh-100 justify-content-center align-items-center">
        <div class="col-md-6 text-center animate__animated animate__fadeIn">
            <?php if ($is_login) : ?>
                <h1 class="display-4 mb-4 animate__animated animate__fadeInDown">Selamat Berhasil Login</h1>
                <p class="lead animate__animated animate__fadeInUp">Anda berhasil masuk ke akun Anda</p>
                <a href="logout" class="btn btn-primary px-4 py-2 animate__animated animate__fadeIn">Logout</a>
            <?php else : ?>
                <h1 class="display-4 mb-4 animate__animated animate__fadeInDown">Selamat Datang</h1>
                <p class="lead animate__animated animate__fadeInUp">Silahkan masuk atau daftar untuk melanjutkan</p>
                <div class="animate__animated animate__fadeIn">
                    <a href="login" class="btn btn-primary px-4 py-2 me-2">Login</a>
                    <a href="register" class="btn btn-outline-primary px-4 py-2">Register</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require 'lib/footer.php';
?>