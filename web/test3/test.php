<?php
error_reporting(E_ALL);

	
	require 'PHPMailer/class.phpmailer.php';
	
	$to = "talktodhi@gmail.com";
	$subject = "Test Mail Subject";
	$body = "Hi<br/>Test Mail<br/>Amazon SES"; // HTML  tags
	
	$from = "noreply@waybeyond.in";
	$mail = new PHPMailer();
	$mail->IsSMTP(true); // SMTP
	$mail->SMTPAuth   = true;  // SMTP authentication
	$mail->Mailer = "smtp";
	$mail->Host= "tls://email-smtp.us-west-2.amazonaws.com"; // Amazon SES
	$mail->Port = 465;  // SMTP Port
	$mail->Username = "AKIAIKQEPFJEW7GCTCYQ";  // SMTP  Username
	$mail->Password = "Aiqkpzd3U8sKOcXK4dn2HfqXn0TZVbzgEQeQS7Yt/NDp";  // SMTP Password
	$mail->SetFrom($from, 'Name');
	//$mail->AddReplyTo($from,'yourdomain.com or verified email address');
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
 
?>