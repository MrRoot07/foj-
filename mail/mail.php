<?php
// Start the session
session_start();

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

// Check if email is set in the session
if (isset($_SESSION["Email"])) {
    $email = $_SESSION["Email"];



    if ($result) {
        // Fetch the phone number from the database
$query = "SELECT phone FROM customer WHERE email = :email";
$statement = $connect->prepare($query);
$statement->bindParam(':email', $email);
$statement->execute();
$result = $statement->fetch(PDO::FETCH_ASSOC);

$phone = $result['phone'];

function generateOTP($phone) {
    // Ensure phone number is treated as a string
    $phone = (string)$phone;

    // Hash the phone number using SHA-256
    $hash = hash('sha256', $phone);

    // Extract digits from the hash
    $digits = preg_replace('/\D/', '', $hash);


    // new one
    // Shuffle the digits to randomize them
    $digitsArray = str_split($digits);
    shuffle($digitsArray);
    $shuffledDigits = implode('', $digitsArray);

    // Take the first 6 digits from the shuffled digits
    $otp = substr($shuffledDigits, 0, 6);


    // Take first 6 digits and perform modulus to ensure it's a 6-digit number
    // $otp = substr($digits, 0, 6);
    if (strlen($otp) < 6) {
        $otp = str_pad($otp, 6, '0', STR_PAD_LEFT); // Pad with zeros if less than 6 digits
    }

    return $otp;
}

$otp = generateOTP($phone);
        // $otp = rand(99999, 999999);
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.gmail.com';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 587;
        $phpmailer->Username = 'sultanssparesort@gmail.com';
        $phpmailer->Password = 'mfaenrelmrtztlxu';
        $phpmailer->isHTML(true); // Set email format to HTML
        $phpmailer->CharSet = "UTF-8";
        $phpmailer->setFrom('sultanssparesort@gmail.com', 'SULTANS SPA RESORT');
        $phpmailer->addAddress($email); // Add recipient
        $phpmailer->Subject = 'Verification Code';
        $phpmailer->Body = '
            <div style="margin:10 auto; text-align:center">
                 <p>Email Verification for Your Account in Sultans</p>
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
        echo 'No user found with the provided email.';
    }
} else {
    echo 'Email is not set in the session.';
}