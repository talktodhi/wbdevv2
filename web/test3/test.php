<?php

require 'class.phpmailer.php';
	
function Send_Mail($to,$subject,$body)
{

	$from = "noreply@waybeyond.in";
	$mail = new PHPMailer();
	$mail->IsSMTP(true); // SMTP
	$mail->SMTPAuth   = true;  // SMTP authentication
	$mail->Mailer = "smtp";
	$mail->Host= "tls://email-smtp.us-east.amazonaws.com"; // Amazon SES
	$mail->Port = 465;  // SMTP Port
	$mail->Username = "Your_SMTP_Username
	";  // SMTP  Username
	$mail->Password = "SMTP_Password";  // SMTP Password
	$mail->SetFrom($from, 'From Name');
	$mail->AddReplyTo($from,'yourdomain.com or verified email address');
	$mail->Subject = $subject;
	$mail->MsgHTML($body);
	$address = $to;
	$mail->AddAddress($address, $to);
	
	if(!$mail->Send())
	return false;
	else
	return true;
	
}


$to = "talktodhi@gmail.com";
$subject = "Test Mail Subject";
$body = "Hi<br/>Test Mail<br/>Amazon SES"; // HTML  tags
Send_Mail($to,$subject,$body);
?>