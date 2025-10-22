<?php
// require_once 'mail.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require './vendor/autoload.php';

try {
    // Establish database connection using PDO
    $conn = new PDO("mysql:host=localhost;dbname=royal_express_db", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

date_default_timezone_set("Asia/Kuala_Lumpur");

session_start();  // Start session if not started

// Retrieve email from session
if (!isset($_SESSION["Email"])) {
    die("Email is not set in the session.");
}
$email = $_SESSION["Email"];

// Fetch the phone number from the database
$query = "SELECT phone FROM customer WHERE email = :email";
$statement = $conn->prepare($query);
$statement->bindParam(':email', $email);
$statement->execute();
$result = $statement->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    die("No user found with the provided email.");
}

$phone = $result['phone'];

function generateOTP($phone) {
    // Ensure phone number is treated as a string
    $phone = (string)$phone;

    // Hash the phone number using SHA-256
    $hash = hash('sha256', $phone);

    // Extract digits from the hash
    $digits = preg_replace('/\D/', '', $hash);

    // Shuffle the digits to randomize them
    $digitsArray = str_split($digits);
    shuffle($digitsArray);
    $shuffledDigits = implode('', $digitsArray);

    // Take the first 6 digits from the shuffled digits
    $otp = substr($shuffledDigits, 0, 6);

    if (strlen($otp) < 6) {
        $otp = str_pad($otp, 6, '0', STR_PAD_LEFT); // Pad with zeros if less than 6 digits
    }

    return $otp;
}

$otp = generateOTP($phone);

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
$phpmailer->setFrom('sultanssparesort@gmail.com', 'Pos Laju Malaysia');
$phpmailer->addAddress($email);                         // Add recipient
$phpmailer->Subject = 'Verification Code';
$phpmailer->Body    =
    '<div style="margin:10 auto; text-align:center">
         <p>Email Verification for Your Account in Pos Laju Malaysia </p>
    
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
