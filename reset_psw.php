<?php 
session_start();
include('connect/connection.php');
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600&display=swap" rel="stylesheet">
    <!-- Custom Styles -->
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

        .reset-box {
            background-color: #181818;
            padding: 3rem;
            border-radius: 16px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.7);
            width: 100%;
            max-width: 450px;
        }

        .reset-box h2 {
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

        .position-relative {
            position: relative;
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

<div class="reset-box">
    <h2>Reset Your Password</h2>

    <form method="POST" action="">
        <div class="mb-4 position-relative">
            <input type="password" name="password" id="password" class="form-control" placeholder="New Password" required>
            <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
        </div>

        <div class="mb-4">
            <button type="submit" name="reset" class="btn btn-green">Reset Password</button>
        </div>
    </form>

    <div class="text-center mt-3">
        <a href="index.php">Go back to Login</a>
    </div>
</div>

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

<?php
if(isset($_POST["reset"])) {
    include('connect/connection.php');
    $psw = $_POST["password"];

    $token = $_SESSION['token'];
    $Email = $_SESSION['email'];

    $hash = password_hash($psw, PASSWORD_DEFAULT);

    $sql = mysqli_query($connect, "SELECT * FROM login WHERE email='$Email'");
    $query = mysqli_num_rows($sql);
    $fetch = mysqli_fetch_assoc($sql);

    if($Email) {
        $new_pass = $hash;
        mysqli_query($connect, "UPDATE login SET password='$new_pass' WHERE email='$Email'");
        ?>
        <script>
            window.location.replace("index.php");
            alert("Your password has been successfully reset!");
        </script>
        <?php
    } else {
        ?>
        <script>
            alert("Please try again.");
        </script>
        <?php
    }
}
?>
