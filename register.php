<?php
require 'config.php';
require 'lib/user_register.php';
require 'lib/header.php';
?>

<div class="container">
    <div class="row vh-100 justify-content-center align-items-center">
        <div class="col-md-6">
            <a href="<?php echo $domain; ?>" class="text-decoration-none">
                <h2 class="text-center mb-4 animate__animated animate__fadeInDown">Daftar <i class="fas fa-user-plus"></i></h2>
            </a>
            <div class="form-container animate__animated animate__fadeIn">
                <form method="POST">
                    <div class="form-group mb-3">
                        <label class="text-dark animate__animated animate__fadeInLeft">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text animate__animated animate__fadeInLeft"><i class="fas fa-user"></i></span>
                            <input type="text" name="name" class="form-control animate__animated animate__fadeInLeft" placeholder="Masukan nama lengkap anda" required>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-dark animate__animated animate__fadeInRight">Email</label>
                        <div class="input-group">
                            <span class="input-group-text animate__animated animate__fadeInRight"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control animate__animated animate__fadeInRight" placeholder="Masukan email anda" required>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-dark animate__animated animate__fadeInLeft">Nomor WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text animate__animated animate__fadeInLeft"><i class="fas fa-phone"></i></span>
                            <input type="number" name="phone" id="phone" class="form-control animate__animated animate__fadeInLeft" placeholder="Masukan Nomor WhatsApp" required>
                        </div>
                        <!-- button send otp onclick submitForm center -->
                        <button type="button" class="btn btn-primary w-100 mt-3 animate__animated animate__fadeInUp" onclick="submitForm('phone')"> Kirim OTP</button>
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-dark animate__animated animate__fadeInRight">Input OTP</label>
                        <div class="input-group">
                            <span class="input-group-text animate__animated animate__fadeInRight"><i class="fas fa-key"></i></span>
                            <input type="phone" name="otentikasi" class="form-control animate__animated animate__fadeInRight" placeholder="xxxxx" required>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-dark animate__animated animate__fadeInLeft">Password</label>
                        <div class="input-group">
                            <span class="input-group-text animate__animated animate__fadeInLeft"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control animate__animated animate__fadeInLef" placeholder="Masukan password baru" required>
                            <button type="button" class="input-group-text" id="toggle-password"><i class="fas fa-eye"></i></button>
                        </div>
                        <button type="button" class="btn btn-secondary w-100 mt-3 animate__animated animate__fadeInUp" id="generate-password">Generate Password</button>
                        <div class="strength-bar mt-2" id="strength-bar">
                            <div class="strength-fill" id="strength-fill"></div>
                        </div>
                    </div>
                    <?php if ($recaptcha['recaptcha_status'] == 1) { ?>
                        <div class="form-group mb-3">
                            <div class="g-recaptcha animate__animated animate__fadeInUp" data-sitekey="<?= $recaptcha['recaptcha_site_key']; ?>"></div>
                        </div>
                    <?php } ?>
                    <div class="mb-3 animate__animated animate__fadeInUp">
                        Sudah memiliki akun? <a href="login">Masuk Sekarang</a>
                    </div>
                    <button class="btn btn-primary w-100 animate__animated animate__fadeInUp" type="submit" onclick="onSubmit()">Daftar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function onSubmit() {
        event.preventDefault();
        const name = document.querySelector('input[name="name"]').value;
        const email = document.querySelector('input[name="email"]').value;
        const phone = document.querySelector('input[name="phone"]').value;
        const otp = document.querySelector('input[name="otentikasi"]').value;
        const password = document.querySelector('input[name="password"]').value;

        if (name === '' || email === '' || phone === '' || otp === '' || password === '') {
            iziToast.error({
                title: 'Error',
                message: 'Semua kolom harus diisi',
                position: 'topRight',
            });
        } else {
            document.querySelector('form').submit();
        }
    }
</script>
<script>
    function submitForm(id) {
        var inputVal = document.getElementById("phone").value;
        var captcha = grecaptcha.getResponse();

        $.ajax({
            url: "<?= $domain; ?>/ajax/sendOTP",
            data: 'captcha=' + captcha + '&whatsapp=' + inputVal,
            timeout: false,
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                $(".btn-pesanan").val('Detail');
                $("input").removeAttr("disabled", "disabled");
                $("button").removeAttr("disabled", "disabled");
                if (data.status) {
                    iziToast.success({
                        title: 'Success',
                        message: data.msg,
                        position: 'topRight'
                    });


                } else {
                    iziToast.error({
                        title: 'Error',
                        message: data.msg,
                        position: 'topRight'
                    });
                }
                grecaptcha.reset();
            },
            error: function(data) {
                iziToast.error({
                    title: 'Error',
                    message: 'Terjadi kesalahan, silahkan coba lagi',
                    position: 'topRight'
                });
                grecaptcha.reset();
            }
        });
    }
</script>
<script>
    const passwordInput = document.getElementById('password');
    const generatePasswordButton = document.getElementById('generate-password');
    const strengthBar = document.getElementById('strength-bar');
    const strengthFill = document.getElementById('strength-fill');

    passwordInput.addEventListener('input', function() {
        const strength = checkPasswordStrength(passwordInput.value);
        updateStrengthBar(strength);
    });

    generatePasswordButton.addEventListener('click', function() {
        const generatedPassword = generateSecurePassword();
        passwordInput.value = generatedPassword;
        const strength = checkPasswordStrength(generatedPassword);
        updateStrengthBar(strength);
    });

    function updateStrengthBar(strength) {
        let strengthClass = '';
        let strengthWidth = '0%';

        if (strength === 1) {
            strengthClass = 'strength-weak';
            strengthWidth = '33%';
        } else if (strength === 2) {
            strengthClass = 'strength-medium';
            strengthWidth = '66%';
        } else if (strength >= 3) {
            strengthClass = 'strength-strong';
            strengthWidth = '100%';
        }

        strengthFill.className = `strength-fill ${strengthClass}`;
        strengthFill.style.width = strengthWidth;
    }

    function checkPasswordStrength(password) {
        let strength = 0;
        const hasNumber = /\d/;
        const hasLowerCase = /[a-z]/;
        const hasUpperCase = /[A-Z]/;
        const hasSpecialChar = /[\W_]/;

        if (password.length >= 1) {
            strength++;

            if (hasNumber.test(password)) {
                strength++;
            }

            if (hasLowerCase.test(password) && hasUpperCase.test(password)) {
                strength++;
            }

            if (hasSpecialChar.test(password)) {
                strength++;
            }
        }

        return strength;
    }

    function generateSecurePassword() {
        const passwordLength = 12;
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()-=_+[]{}|;:,.<>?/';
        let password = '';

        for (let i = 0; i < passwordLength; i++) {
            const randomIndex = Math.floor(Math.random() * characters.length);
            password += characters[randomIndex];
        }

        return password;
    }


    const togglePasswordButton = document.getElementById('toggle-password');

    togglePasswordButton.addEventListener('click', function() {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            togglePasswordButton.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
            passwordInput.type = 'password';
            togglePasswordButton.innerHTML = '<i class="fas fa-eye"></i>';
        }
    });
</script>
<?php
require 'lib/footer.php';
?>