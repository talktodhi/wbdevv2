<?php
error_reporting(E_ALL);

	
	require 'PHPMailer/class.phpmailer.php';
	
	$to = "talktodhi@gmail.com";
	$subject = "Test Mail Subject";
	$body = "Hi<br/>Test Mail<br/>Amazon SES"; // HTML  tags
	
	$from = "noreply@waybeyond.in";
	/*
	$mail = new PHPMailer();
	$mail->IsSMTP(true); // SMTP
	$mail->SMTPAuth   = true;  // SMTP authentication
	$mail->Mailer = "smtp";
	$mail->Host= "tls://email-smtp.us-west-2.amazonaws.com"; // Amazon SES
	$mail->Port = 587;  // SMTP Port
	$mail->Username = "AKIAIKQEPFJEW7GCTCYQ";  // SMTP  Username
	$mail->Password = "Aiqkpzd3U8sKOcXK4dn2HfqXn0TZVbzgEQeQS7Yt/NDp";  // SMTP Password
	$mail->SetFrom($from, 'Name');
	$mail->AddReplyTo($from,'dhiraj.bastwade@gmail.com');
	$mail->Subject = $subject;
	$mail->MsgHTML($body);
	$address = $to;
	$mail->AddAddress($address, $to);
	$ret = $mail->Send();
	echo "<pre>";
	print_r($ret);
	if(!$ret){
		echo "ASD00";
	}else{
		echo "kjlkj";
	}
 
 */
 
 // Instantiate a new PHPMailer 
$mail = new PHPMailer;

// Tell PHPMailer to use SMTP
$mail->isSMTP();

// Replace sender@example.com with your "From" address. 
// This address must be verified with Amazon SES.
$mail->setFrom($from, 'From Name');

// Replace recipient@example.com with a "To" address. If your account 
// is still in the sandbox, this address must be verified.
// Also note that you can include several addAddress() lines to send
// email to multiple recipients.
$mail->addAddress($to, 'Recipient Name');

// Replace smtp_username with your Amazon SES SMTP user name.
$mail->Username = 'AKIAIKQEPFJEW7GCTCYQ';

// Replace smtp_password with your Amazon SES SMTP password.
$mail->Password = 'Aiqkpzd3U8sKOcXK4dn2HfqXn0TZVbzgEQeQS7Yt/NDp';
    
// Specify a configuration set. If you do not want to use a configuration
// set, comment or remove the next line.
//$mail->addCustomHeader('X-SES-CONFIGURATION-SET', 'ConfigSet');
 
// If you're using Amazon SES in a region other than US West (Oregon), 
// replace email-smtp.us-west-2.amazonaws.com with the Amazon SES SMTP  
// endpoint in the appropriate region.
$mail->Host = 'email-smtp.us-west-2.amazonaws.com';

// The subject line of the email
$mail->Subject = 'Amazon SES test (SMTP interface accessed using PHP)';

// The HTML-formatted body of the email
$mail->Body = '<h1>Email Test</h1>
    <p>This email was sent through the 
    <a href="https://aws.amazon.com/ses">Amazon SES</a> SMTP
    interface using the <a href="https://github.com/PHPMailer/PHPMailer">
    PHPMailer</a> class.</p>';

// Tells PHPMailer to use SMTP authentication
$mail->SMTPAuth = true;

// Enable TLS encryption over port 587
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

// Tells PHPMailer to send HTML-formatted email
$mail->isHTML(true);

// The alternative email body; this is only displayed when a recipient
// opens the email in a non-HTML email client. The \r\n represents a 
// line break.
$mail->AltBody = "Email Test\r\nThis email was sent through the 
    Amazon SES SMTP interface using the PHPMailer class.";

if(!$mail->send()) {
    echo "Email not sent. " , $mail->ErrorInfo , PHP_EOL;
} else {
    echo "Email sent!" , PHP_EOL;
}
?>