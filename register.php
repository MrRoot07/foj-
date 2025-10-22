<?php
session_start();
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Pos Laju Delivery System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        body {
            background: url('Admin/assets/images/logo/background2.png') no-repeat center center fixed;
            background-size: 100% 100%;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            position: relative;
        }

        .header-nav {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .nav-button {
            background-color: transparent;
            border: 2px solid #ffffff;
            color: #ffffff;
            padding: 8px 20px;
            margin: 0 5px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .nav-button:hover {
            background-color: #ffffff;
            color: #000000;
            transform: translateY(-2px);
        }

        .nav-button.active {
            background-color: #ffffff;
            color: #000000;
        }

        .logo {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            color: #ffffff;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        .container {
            padding-top: 100px;
            position: relative;
            z-index: 1;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            max-width: 600px;
            margin: 0 auto;
        }

        .form-control {
            border: 2px solid #eee;
            border-radius: 15px;
            padding: 12px 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #4F6480;
            box-shadow: 0 0 0 0.2rem rgba(79, 100, 128, 0.25);
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            color: #333;
        }

        .btn-primary {
            background-color: #4F6480;
            border: none;
            border-radius: 15px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }

        .btn-primary:hover {
            background-color: #2D3864;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .page-title {
            color: #333;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            text-align: center;
        }

        .page-subtitle {
            color: #666;
            font-size: 16px;
            margin-bottom: 30px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .header-nav {
                top: 10px;
                right: 10px;
            }
            
            .nav-button {
                padding: 6px 15px;
                font-size: 14px;
            }
            
            .form-container {
                padding: 20px;
                margin: 10px;
            }
            
            .container {
                padding-top: 80px;
            }
        }
    </style>

<?php
// User registration process: Hashing the password using bcrypt

if (isset($_POST['reg_btn'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    
    // Check if the passwords match
    if ($password !== $cpassword) {
        $_SESSION['status'] = "Passwords do not match.";
        header('Location: register.php');
        exit();
    }
    
    // Hash the password using bcrypt
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    // Insert new user into the database with the hashed password
    $stmt = $con->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashed_password);
    
    if ($stmt->execute()) {
        $_SESSION['status'] = "Registration successful!";
        header('Location: login.php');
    } else {
        $_SESSION['status'] = "Registration failed. Please try again.";
        header('Location: register.php');
    }
}
?>

</head>
<body>
    <a href="index.php" class="logo">Pos Laju</a>
    
    <nav class="header-nav">
        <a href="index.php" class="nav-button">Home</a>
        <a href="index.php #section-about" class="nav-button">About Us</a>
        <a href="gallery.php" class="nav-button">Gallery</a>
        <a href="contact.php" class="nav-button">Contact</a>
        <?php if (isset($_SESSION['auth'])) : ?>
            <a href="profile.php" class="nav-button">Profile</a>
            <a href="tracking.php" class="nav-button">Tracking</a>
            <a href="logout.php" class="nav-button">Logout</a>
        <?php else : ?>
            <a href="login.php" class="nav-button">Login</a>
            <a href="register.php" class="nav-button active">Register</a>
        <?php endif; ?>
        <a href="request.php" class="nav-button">Request</a>
    </nav>

    <div class="container">
        <div class="form-container">
            <h1 class="page-title">Create Your Account</h1>
            <p class="page-subtitle">Join Pos Laju and start managing your deliveries</p>
            
            <form action="code.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="Enter your full name">
                </div>
                <div class="mb-3">
                    <label class="form-label">NIC Number</label>
                    <input type="text" name="nic" class="form-control" required placeholder="Enter your NIC number">
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" required placeholder="Enter your phone number">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required placeholder="Enter your email">
                </div>
                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-control" required>
                        <option value="">Select Gender</option>
                        <option value="1">Male</option>
                        <option value="2">Female</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" required placeholder="Enter your address">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Enter your password">
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="cpassword" class="form-control" required placeholder="Confirm your password">
                </div>

                <div class="mb-3">
                    <div class="g-recaptcha" data-sitekey="6Le407cqAAAAALOot4V59zIHTXeBJilaqE3twlQQ"></div>
                </div>

                <button type="submit" name="reg_btn" class="btn btn-primary">Register</button>
                
                <div class="mt-3 text-center">
                    <a href="login.php" class="text-decoration-none" style="color: #4F6480;">Already have an account? Login</a>
                </div>
            </form>
            
            <?php if (isset($_SESSION['status'])): ?>
                <div class="alert alert-warning mt-3"><?= $_SESSION['status']; unset($_SESSION['status']); ?></div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


