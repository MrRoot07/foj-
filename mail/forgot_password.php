<?php
// require_once 'mail.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require './vendor/autoload.php';

$link = 'http://' . $_SERVER['HTTP_HOST'] . '/sultans/forgot-password.php?step=change-password&for_user=' . $_SESSION['changePasswordUserId'];
$phpmailer = new PHPMailer();
$phpmailer->isSMTP();
$phpmailer->Host = 'smtp.gmail.com';
$phpmailer->SMTPAuth = true;
$phpmailer->Port = 587;
$phpmailer->Username = 'sultanssparesort@gmail.com';
$phpmailer->Password = 'mfaenrelmrtztlxu';
//Content
$phpmailer->isHTML(true);                               // Set email format to HTML
$phpmailer->CharSet = "UTF-8";                                   // لدعم اللغة العربية
// /Recipients
$phpmailer->setFrom('sultanssparesort@gmail.com', 'SULTANS SPA RESORT');
$phpmailer->addAddress($email);     //Add a recipient
$phpmailer->Subject = 'Forgot Password';
$phpmailer->Body    =
'<div style="margin:10 auto; text-align:center">
         <p>Email confirmation of your ownership</p>
    
    <h4>Yor Link:</h4>
    <a href=" ' . $link .'"> Click here</a>
</div>
    ';
// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if ($phpmailer->send()) {

    $message = '<label class="alert alert-success"><i class="ico">&#10004;</i> Be Submit link Change Password To your email!</label>';

    header("refresh:3;url=index.php");
}
// echo 'Message could not be sent.';

$message = '<label class="alert alert-success"><i class="ico">&#10004;</i> Be Submit link Change Password To your email!</label>';
$linkBack = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
// header("refresh:3;url=$linkBack");
