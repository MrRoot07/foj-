<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
session_start();
}

// require_once 'mail.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require './vendor/autoload.php';

$con =  mysqli_connect("localhost", "root", "" , "royal_express_db") or die("Database Connection Fail"); 

date_default_timezone_set("Asia/Kuala_Lumpur");

// Set the previous page in the session if not already set
if (!isset($_SESSION['previous_page']) && isset($_SERVER['HTTP_REFERER'])) {
    $_SESSION['previous_page'] = $_SERVER['HTTP_REFERER'];
}

/**
 * Generate a strong alphanumeric OTP
 * 
 * This function creates a secure 8-character OTP using:
 * - Uppercase letters (A-Z, excluding confusing characters: I, O)
 * - Numbers (0-9, excluding confusing: 0, 1)
 * 
 * Why this approach is secure:
 * 1. Uses cryptographically secure random_int() when available
 * 2. Larger character set (32 characters) = 32^8 = 1,073,741,824 combinations
 * 3. Avoids visually confusing characters (0/O, 1/I) for better UX
 * 4. Mix of letters and numbers makes it harder to guess
 * 5. 8 characters provides strong security while remaining user-friendly
 */
function generateStrongOTP($length = 8) {
    // Character set: A-Z (excluding I and O), 2-9 (excluding 0 and 1)
    // This avoids confusing characters: 0/O, 1/I, l/I
    $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $charactersLength = strlen($characters);
    $otp = '';
    
    // Use cryptographically secure random if available, fallback to rand()
    if (function_exists('random_int')) {
        for ($i = 0; $i < $length; $i++) {
            $otp .= $characters[random_int(0, $charactersLength - 1)];
        }
    } else {
        for ($i = 0; $i < $length; $i++) {
            $otp .= $characters[rand(0, $charactersLength - 1)];
        }
    }

    return $otp;
}

// Check if email is set in the session
if (isset($_SESSION["Email"])) {
    $email = $_SESSION["Email"];

    // Generate strong alphanumeric OTP
    $otp = generateStrongOTP(8);
    
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.gmail.com';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 587;
        $phpmailer->Username = 'sultanssparesort@gmail.com';
        $phpmailer->Password = 'mfaenrelmrtztlxu';
        $phpmailer->isHTML(true); // Set email format to HTML
        $phpmailer->CharSet = "UTF-8";
        $phpmailer->setFrom('sultanssparesort@gmail.com', 'FOJ Express');
        $phpmailer->addAddress($email); // Add recipient
        $phpmailer->Subject = 'Verification Code';
        $phpmailer->Body = '
            <div style="margin:10 auto; text-align:center">
             <p>Email Verification for Your Account in FOJ Express</p>
                <h4>Your Code:</h4>
                <h1>' . $otp . '</h1>
            </div>
        ';

        if ($phpmailer->send()) {
            $_SESSION["OTP"] = $otp;
            $message = '<label class="alert alert-warning"><i class="ico">&#10004;</i> Your account is not active. A verification code has been sent to your email!</label>';

            // Redirect to the previous page
            if (isset($_SESSION['previous_page'])) {
                header('Refresh: 1; URL=' . $_SESSION['previous_page']);
                exit();
            } else {
                echo 'Previous page not found in session.';
            }
        } else {
            $_SESSION["OTP"] = $otp;
            $message = '<label class="alert alert-danger"><i class="ico">&#10004;</i> Your account is not active. Something went wrong when sending the verification code to your email! Please try again.</label>';

            // Redirect to the previous page
            if (isset($_SESSION['previous_page'])) {
                header('Refresh: 1; URL=' . $_SESSION['previous_page']);
                exit();
            } else {
                echo 'Previous page not found in session.';
            }
    }
} else {
    echo 'Email is not set in the session.';
}
