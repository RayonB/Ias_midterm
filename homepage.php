<?php
session_start();

// Redirect to login if not authenticated
// Assuming you save the user's email or ID in the session upon login
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
?>


<!doctype html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <title>Spotify Dashboard</title>
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
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            padding: 2rem;
        }

        .navbar {
            background-color: #181818;
            border-radius: 16px;
            padding: 1rem 2rem;
            margin-bottom: 2rem;
        }

        .welcome {
            font-size: 2rem;
            margin-bottom: 1.5rem;
        }

        .btn-green {
            background-color: #1DB954;
            color: #fff;
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
            border-radius: 30px;
            border: none;
        }

        .btn-green:hover {
            background-color: #1ed760;
        }

        .card {
            background-color: #181818;
            border: none;
            border-radius: 16px;
            color: #fff;
        }

        .card-title {
            font-size: 1.25rem;
        }
    </style>
</head>
<body>

<nav class="navbar d-flex justify-content-between align-items-center">
    <h4>Spotify Clone</h4>
    <a href="logout.php" class="btn btn-green">Logout</a>
</nav>

<div class="welcome">
    🎵 Welcome back, <?= $_SESSION['email'] ?>!
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card p-4">
            <h5 class="card-title">Discover Weekly</h5>
            <p class="card-text">New tracks just for you.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4">
            <h5 class="card-title">Liked Songs</h5>
            <p class="card-text">All your favorites in one place.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4">
            <h5 class="card-title">Top Artists</h5>
            <p class="card-text">Your most listened artists this week.</p>
        </div>
    </div>
</div>

</body>
</html>
