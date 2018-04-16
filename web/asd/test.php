<?php
error_reporting('E_ALL');
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';
	
	$to = "talktodhi@gmail.com";
	$subject = "Test Mail Subject";
	$body = "Hi<br/>Test Mail<br/>Amazon SES"; // HTML  tags
	
	$from = "dhiraj.bastwade@gmail.com";
	

$mail = new PHPMailer();
$mail->isSMTP();
$mail->setFrom($from, 'Dhiaj');
$mail->addAddress($to, 'suraj');
$mail->Username = 'AKIAIKQEPFJEW7GCTCYQ';
$mail->Password = 'Aiqkpzd3U8sKOcXK4dn2HfqXn0TZVbzgEQeQS7Yt/NDp';
//$mail->addCustomHeader('X-SES-CONFIGURATION-SET', 'ConfigSet');
$mail->Host = 'email-smtp.us-west-2.amazonaws.com';
$mail->Subject = 'Amazon SES test (SMTP interface accessed using PHP)';
$mail->Body = '<h1>Email Test</h1>
    <p>This email was sent through the 
    <a href="https://aws.amazon.com/ses">Amazon SES</a> SMTP
    interface using the <a href="https://github.com/PHPMailer/PHPMailer">
    PHPMailer</a> class.</p>';
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';
$mail->Port = 25;
$mail->isHTML(true);

$mail->AltBody = "Email Test\r\nThis email was sent through the 
    Amazon SES SMTP interface using the PHPMailer class.";
	
//	die('Here');
//var_dump(!$mail->send());
if(!$mail->send()) {
    echo "Email not sent. " , $mail->ErrorInfo , PHP_EOL;
} else {
    echo "Email sent!" , PHP_EOL;
}
?> 