<?php
// require_once 'mail.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require './vendor/autoload.php';

date_default_timezone_set("Asia/Kuala_Lumpur");

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Retrieve email from session
if (!isset($_SESSION["Email"])) {
    die("Email is not set in the session.");
}
$email = $_SESSION["Email"];

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

// Generate strong alphanumeric OTP
$otp = generateStrongOTP(8);

$phpmailer = new PHPMailer();
$phpmailer->isSMTP();
$phpmailer->Host = 'smtp.gmail.com';
$phpmailer->SMTPAuth = true;
$phpmailer->Port = 587;
$phpmailer->Username = 'sultanssparesort@gmail.com';
$phpmailer->Password = 'mfaenrelmrtztlxu';

//Content
$phpmailer->isHTML(true);                               // Set email format to HTML
$phpmailer->CharSet = "UTF-8";                          // Support for Arabic language

//Recipients
$phpmailer->setFrom('sultanssparesort@gmail.com', 'FOJ Express');
$phpmailer->addAddress($email);                         // Add recipient
$phpmailer->Subject = 'Verification Code';
$phpmailer->Body    =
    '<div style="margin:10 auto; text-align:center">
         <p>Email Verification for Your Account in FOJ Express </p>
    
    <h4>Your Code:</h4>
    <h1>' . $otp . '</h1>
</div>';

if ($phpmailer->send()) {
    $_SESSION["OTP"] = $otp;
    $_SESSION["Email"] = $email;
    $message = '<label class="alert alert-warning"><i class="ico">&#10004;</i> Your account has not been activated. We have sent a verification code to your email!</label>';
    header("refresh:3;url=verify-otp.php");
    exit();
} else {
    $message = '<label class="alert alert-danger"><i class="ico">&#10004;</i> Your account has not been activated. An error occurred while sending the verification code to your email! Please try again.</label>';
    header("refresh:3;url=verify-otp.php");
    exit();
}
