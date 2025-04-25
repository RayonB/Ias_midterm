<?php session_start(); ?>
<?php
include('connect/connection.php');
$message = '';

if (isset($_POST["verify"])) {
    $otp = $_SESSION['otp'];
    $email = $_SESSION['mail'];
    $otp_code = trim($_POST['otp_code']);

    if ($otp != $otp_code) {
        $message = "Invalid OTP code.";
    } else {
        mysqli_query($connect, "UPDATE login SET status = 1 WHERE email = '$email'");
        echo "<script>alert('Verify account done, you may sign in now'); window.location.replace('index.php');</script>";
        exit();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #121212;
            font-family: 'Montserrat', sans-serif;
            color: #fff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .verify-box {
            background-color: #181818;
            padding: 3rem;
            border-radius: 16px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.7);
            width: 100%;
            max-width: 450px;
        }

        .verify-box h2 {
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

        .btn-success {
            background-color: #1DB954;
            color: #fff;
            padding: 0.75rem;
            font-size: 1.1rem;
            border-radius: 30px;
            border: none;
            width: 100%;
        }

        .btn-success:hover {
            background-color: #1ed760;
        }

        .text-center a {
            color: #1DB954;
            text-decoration: none;
        }

        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="verify-box">
    <h2>OTP Verification</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-danger text-center"><?= $message ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="mb-4">
            <label for="otp" class="form-label">Enter OTP Code</label>
            <input type="text" name="otp_code" id="otp" class="form-control" required autofocus>
        </div>

        <div class="mb-4">
            <button type="submit" name="verify" class="btn btn-success btn-block">Verify</button>
        </div>
    </form>

    <div class="text-center mt-3">
        <a href="index.php">Back to Login</a>
    </div>
</div>

</body>
</html>
