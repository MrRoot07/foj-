<?php
session_start();
include('conf.php');

// reCAPTCHA secret key
$secretKey = '6Le407cqAAAAADdzsDo1KU8mQfNmTpNxb1RtyzMV'; // Updated Secret Key

if (isset($_GET['status']) && $_GET['status'] == 'session_expired') {
    echo "<p style='color: red;'>Your session has expired due to inactivity. Please log in again.</p>";
}

// LOGIN PROCESS
if (isset($_POST['login_btn'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // Verify reCAPTCHA response
    $ip = $_SERVER['REMOTE_ADDR'];
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse&remoteip=$ip");
    $responseKeys = json_decode($response, true);

    if (intval($responseKeys["success"]) !== 1) {
        $_SESSION['status'] = "reCAPTCHA verification failed. Please try again.";
        header('Location: login.php');
        exit();
    }

    // Use a prepared statement to fetch user data
    $stmt = $con->prepare("SELECT * FROM customer WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            if ($user['active'] == 1) {
                // User is active, login successful
                $_SESSION['auth'] = [
                    'auth_id' => $user['customer_id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                ];
                $_SESSION['auth'] = true;
                $_SESSION['customer_id'] = $user['customer_id']; // Store the customer ID
                $_SESSION['last_activity'] = time();
                $_SESSION['status'] = "Logged in successfully!";
                header('Location: index.php');
                exit();
            } else {
                // Send OTP if not active
                $_SESSION['Email'] = $email;
                include_once('./mail/index.php');
                $_SESSION['status'] = "Account not activated. Please verify your email.";
                header('Location: verify-otp.php');
                exit();
            }
        } else {
            $_SESSION['status'] = "Invalid email or password.";
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['status'] = "No account found with this email.";
        header('Location: login.php');
        exit();
    }
}

// REGISTRATION PROCESS
if (isset($_POST['reg_btn'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $nic = mysqli_real_escape_string($con, $_POST['nic']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    $active = 0;

    // reCAPTCHA verification
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse&remoteip=$ip");
    $responseKeys = json_decode($response, true);

    if (intval($responseKeys["success"]) !== 1) {
        $_SESSION['status'] = "reCAPTCHA verification failed. Please try again.";
        header('Location: register.php');
        exit();
    }

    // Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['status'] = "Invalid email format.";
        header('Location: register.php');
        exit();
    }

    // Validate Phone Number
    if (!preg_match('/^\+?[0-9]{10,15}$/', $phone)) {
        $_SESSION['status'] = "Invalid phone number. It must be 10 to 15 digits and can optionally start with '+'." ;
        header('Location: register.php');
        exit();
    }

    // Password Complexcity 
    $password_errors = [];
    if (strlen($password) < 8) {
        $password_errors[] = "Password must be at least 8 characters long.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $password_errors[] = "Password must include at least one uppercase letter.";
    }
    if (!preg_match('/[a-z]/', $password)) {
        $password_errors[] = "Password must include at least one lowercase letter.";
    }
    if (!preg_match('/[0-9]/', $password)) {
        $password_errors[] = "Password must include at least one number.";
    }
    if (!preg_match('/[\W]/', $password)) {
        $password_errors[] = "Password must include at least one special character.";
    }

    if (!empty($password_errors)) {
        $_SESSION['status'] = implode(" ", $password_errors);
        header('Location: register.php');
        exit();
    }

    // Check if email already exists
    $stmt = $con->prepare("SELECT * FROM customer WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['status'] = "Email is already registered.";
        header('Location: register.php');
        exit();
    }

    if ($password !== $cpassword) {
        $_SESSION['status'] = "Passwords do not match.";
        header('Location: register.php');
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $stmt = $con->prepare("INSERT INTO customer (name, nic, phone, email, gender, address, password, active, is_deleted) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("sssssssi", $name, $nic, $phone, $email, $gender, $address, $hashed_password, $active);

    if ($stmt->execute()) {
        $_SESSION['Email'] = $email;
        include_once('./mail/index.php');
        $_SESSION['status'] = "Registration successful! Please verify your email.";
        header('Location: verify-otp.php');
        exit();
    } else {
        $_SESSION['status'] = "Registration failed. Please try again.";
        header('Location: register.php');
        exit();
    }
}

?>
