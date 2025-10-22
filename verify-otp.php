<?php
session_start();
include('conf.php');

if (!isset($_SESSION['Email'])) {
    header("Location: login.php");
    exit;
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredOtp = $_POST['otpCode'];

    if ($enteredOtp == $_SESSION['OTP']) {
        $email = $_SESSION['Email'];
        $update_query = "UPDATE customer SET active = 1 WHERE email = '$email'";
        $update_run = mysqli_query($con, $update_query);

        if ($update_run) {
            $_SESSION['status'] = "Account verified successfully. You can now log in.";
            header("Location: login.php");
            exit;
        } else {
            $message = "Failed to activate account. Please try again.";
        }
    } else {
        $message = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Delivery System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        header {
            background-color:rgb(79, 100, 128);
            color: #ffffff;
            padding: 10px 0;
        }

        header a {
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
        }

        .container {
            margin-top: 5%;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 10px;
        }

        .btn-primary {
            background-color:rgb(79, 100, 128);
            border: none;
            border-radius: 10px;
            font-size: 16px;
        }

        .btn-primary:hover {
            background-color:rgb(79, 100, 128);
        }

        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .nav-link {
            color: #ffffff;
        }

        .nav-link:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>

<header>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0"><a href="index.php">Pos laju</a></h1>
            <nav>
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php">Contact</a></li>
                    <?php if (isset($_SESSION['auth'])) : ?>
                        <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                        <li class="nav-item"><a class="nav-link" href="tracking.php">Tracking</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    <?php else : ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="request.php">Request</a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>

<div class="container">
    <div class="text-center mb-4">
        <h2>Verify Your OTP</h2>
        <p class="text-muted">Please enter the OTP sent to your email.</p>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="form-container">
                <form method="POST" action="verify-otp.php">
                    <div class="mb-3">
                        <label class="form-label">Enter OTP Code</label>
                        <input type="text" name="otpCode" class="form-control" required placeholder="6-digit OTP">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Verify OTP</button>
                    <div class="mt-3 text-center">
                        <a href="mail/mail.php" class="text-decoration-none">Resend OTP</a>
                    </div>
                    <?php if ($message): ?>
                        <div class="alert alert-warning mt-3"><?= $message; ?></div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
