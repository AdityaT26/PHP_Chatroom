<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

function sendMail($recipients, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.sendgrid.net'; // Set the SMTP server to send through
        $mail->SMTPAuth   = true;
        $mail->Username   = 'apikey'; // Use 'apikey' as the username
        $mail->Password   = '<Your_SendGrid_API_Key'; // Your SendGrid API key
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender
        $mail->setFrom('<Your_Senders_EMail_Address>', '<Your_From_Name');

        // Recipients
        foreach ($recipients as $recipient) {
            $mail->addAddress($recipient);
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Define the recipients
$recipients = ['<email_1>', '<email_2'];  #change
?>
