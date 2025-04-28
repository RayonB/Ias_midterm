<?php
session_start();
include('connect/connection.php');

$message = '';

// Google’s **test** secret key (always returns success)
$recaptcha_secret = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    // 1) Check reCAPTCHA response exists
    if (empty($_POST['g-recaptcha-response'])) {
        $message = "Please verify you're not a robot.";
    } else {
        // 2) Verify with Google
        $captcha  = $_POST['g-recaptcha-response'];
        $verify   = file_get_contents(
            "https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$captcha}"
        );
        $response = json_decode($verify);

        if (!$response->success) {
            $message = "reCAPTCHA verification failed.";
        } else {
            // 3) Your existing registration logic
            $email    = trim($_POST["email"]);
            $password = trim($_POST["password"]);

            $check_query = mysqli_query($connect, "SELECT * FROM login WHERE email ='$email'");
            $rowCount    = mysqli_num_rows($check_query);

            if (!empty($email) && !empty($password)) {
                if ($rowCount > 0) {
                    $message = "User with this email already exists.";
                } else {
                    $password_hash = password_hash($password, PASSWORD_BCRYPT);

                    $result = mysqli_query(
                        $connect,
                        "INSERT INTO login (email, password, status) VALUES ('$email', '$password_hash', 0)"
                    );

                    if ($result) {
                        $otp = rand(100000, 999999);
                        $_SESSION['otp']  = $otp;
                        $_SESSION['mail'] = $email;

                        require "Mail/phpmailer/PHPMailerAutoload.php";
                        $mail = new PHPMailer;

                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com';
                        $mail->Port       = 587;
                        $mail->SMTPAuth   = true;
                        $mail->SMTPSecure = 'tls';
                        $mail->Username   = 'rinevarayon@gmail.com';
                        $mail->Password   = 'pxrg unlk jesm hble';

                        $mail->setFrom('rinevarayon@gmail.com', 'OTP Verification');
                        $mail->addAddress($email);
                        $mail->isHTML(true);
                        $mail->Subject = "Your verification code";
                        $mail->Body    = "<p>Dear user,</p><h3>Your OTP code is $otp</h3><p>Regards,<br><b>Verification Team</b></p>";

                        if (!$mail->send()) {
                            $message = "Registration failed. Invalid email.";
                        } else {
                            echo "<script>
                                    alert('Registered successfully. OTP sent to $email');
                                    window.location='verification.php';
                                  </script>";
                            exit();
                        }
                    }
                }
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Spotify Style Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #121212;
            font-family: 'Montserrat', sans-serif;
            color: #fff;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-box {
            background-color: #181818;
            padding: 3rem;
            border-radius: 16px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.7);
            width: 100%;
            max-width: 450px;
        }
        .register-box h2 {
            font-size: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        .form-control {
            background-color: #2a2a2a;
            border: none;
            color: #fff;
            font-size: 1.1rem;
            padding: 1rem;
            border-radius: 8px;
        }
        .form-control:focus {
            background-color: #333;
            outline: none;
            box-shadow: 0 0 0 2px #1DB954;
        }
        .btn-green {
            background-color: #1DB954;
            color: #fff;
            padding: 0.75rem;
            font-size: 1.1rem;
            border-radius: 30px;
            border: none;
            width: 100%;
        }
        .btn-green:hover {
            background-color: #1ed760;
        }
        .toggle-password {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #b3b3b3;
            cursor: pointer;
        }
        .position-relative { position: relative; }
        .text-center a {
            color: #1DB954;
            text-decoration: none;
        }
        .text-center a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Sign up for Spotify</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-warning text-center"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-4 position-relative">
            <input type="email" name="email" class="form-control" placeholder="Email address" required>
        </div>

        <div class="mb-4 position-relative">
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
            <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
        </div>

        <!-- reCAPTCHA widget -->
        <div class="mb-4 text-center">
            <div class="g-recaptcha d-inline-block"
                 data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI">
            </div>
        </div>

        <div class="mb-4">
            <button type="submit" name="register" class="btn btn-green">Register</button>
        </div>
    </form>

    <div class="text-center">
        <a href="index.php">Already have an account? Login</a>
    </div>
</div>

<!-- reCAPTCHA script -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script>
    const toggle = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    toggle.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('bi-eye');
    });
</script>

</body>
</html>
