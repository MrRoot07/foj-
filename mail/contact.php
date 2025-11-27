<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require __DIR__ . '/vendor/autoload.php';

date_default_timezone_set("Asia/Kuala_Lumpur");

// Company email (you can change this to your actual company email)
$company_email = 'support@foj.com'; // Change this to your company email

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])) {
    // Sanitize input
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $service = isset($_POST['service']) ? htmlspecialchars(trim($_POST['service'])) : 'Not specified';
    $message = htmlspecialchars(trim($_POST['message']));
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address']);
        exit;
    }
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
        exit;
    }
    
    $success = false;
    $error_message = '';
    
    // Send email to company
    try {
        $mail_company = new PHPMailer(true);
        $mail_company->isSMTP();
        $mail_company->Host = 'smtp.gmail.com';
        $mail_company->SMTPAuth = true;
        $mail_company->Port = 587;
        $mail_company->Username = 'sultanssparesort@gmail.com';
        $mail_company->Password = 'mfaenrelmrtztlxu';
        $mail_company->isHTML(true);
        $mail_company->CharSet = "UTF-8";
        $mail_company->setFrom('sultanssparesort@gmail.com', 'FOJ Express Contact Form');
        $mail_company->addAddress($company_email);
        $mail_company->addReplyTo($email, $name);
        $mail_company->Subject = 'New Contact Form Submission - ' . $service;
        $mail_company->Body = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
                <h2 style="color: #2563eb; border-bottom: 2px solid #2563eb; padding-bottom: 10px;">New Contact Form Submission</h2>
                <div style="background: #f7f9fc; padding: 15px; border-radius: 8px; margin: 20px 0;">
                    <p><strong>Name:</strong> ' . $name . '</p>
                    <p><strong>Email:</strong> ' . $email . '</p>
                    <p><strong>Service:</strong> ' . $service . '</p>
                    <p><strong>Message:</strong></p>
                    <p style="background: white; padding: 15px; border-radius: 5px; margin-top: 10px;">' . nl2br($message) . '</p>
                </div>
                <p style="color: #6b7280; font-size: 12px; margin-top: 20px;">This email was sent from the FOJ Express contact form.</p>
            </div>
        ';
        
        $company_sent = $mail_company->send();
    } catch (Exception $e) {
        $company_sent = false;
        $error_message = $mail_company->ErrorInfo;
    }
    
    // Send confirmation email to customer
    try {
        $mail_customer = new PHPMailer(true);
        $mail_customer->isSMTP();
        $mail_customer->Host = 'smtp.gmail.com';
        $mail_customer->SMTPAuth = true;
        $mail_customer->Port = 587;
        $mail_customer->Username = 'sultanssparesort@gmail.com';
        $mail_customer->Password = 'mfaenrelmrtztlxu';
        $mail_customer->isHTML(true);
        $mail_customer->CharSet = "UTF-8";
        $mail_customer->setFrom('sultanssparesort@gmail.com', 'FOJ Express');
        $mail_customer->addAddress($email, $name);
        $mail_customer->Subject = 'Thank You for Contacting FOJ Express';
        $mail_customer->Body = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
                <div style="text-align: center; margin-bottom: 30px;">
                    <h1 style="color: #2563eb; margin: 0;">FOJ Express</h1>
                    <p style="color: #6b7280; margin: 5px 0;">Fast & Reliable Courier Services</p>
                </div>
                <div style="background: #f7f9fc; padding: 25px; border-radius: 10px; margin-bottom: 20px;">
                    <h2 style="color: #2563eb; margin-top: 0;">Thank You, ' . $name . '!</h2>
                    <p>We have received your message and will get back to you as soon as possible.</p>
                    <div style="background: white; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #2563eb;">
                        <p style="margin: 0;"><strong>Your Message:</strong></p>
                        <p style="margin: 10px 0 0; color: #6b7280;">' . nl2br($message) . '</p>
                    </div>
                    <p>Our team typically responds within 24 hours. If your inquiry is urgent, please call us at <strong>+966 550772943</strong>.</p>
                </div>
                <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                    <p style="color: #6b7280; font-size: 14px; margin: 5px 0;">FOJ Express</p>
                    <p style="color: #6b7280; font-size: 12px; margin: 5px 0;">123 Logistics Avenue, Suite 400<br>Metro City, Country</p>
                    <p style="color: #6b7280; font-size: 12px; margin: 5px 0;">Phone: +966 550772943 | Email: support@foj.com</p>
                </div>
            </div>
        ';
        
        $customer_sent = $mail_customer->send();
    } catch (Exception $e) {
        $customer_sent = false;
        if (empty($error_message)) {
            $error_message = $mail_customer->ErrorInfo;
        }
    }
    
    // Return response
    if ($company_sent && $customer_sent) {
        echo json_encode(['success' => true, 'message' => 'Thank you! We have received your message and sent a confirmation email.']);
    } else if ($company_sent) {
        echo json_encode(['success' => true, 'message' => 'Thank you! We have received your message. (Note: Confirmation email could not be sent)']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Sorry, there was an error sending your message. Please try again or contact us directly.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>

