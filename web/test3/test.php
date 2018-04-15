<?php
error_reporting(E_ALL);

	
function Send_Mail($to,$subject,$body)
{
	require 'PHPMailer/class.phpmailer.php';
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
	
	if(!$mail->Send())
	return false;
	else
	return true;
	
}




try {
    //   $tableBresults = $dbHandler->doSomethingWithTableB();
	$to = "talktodhi@gmail.com";
	$subject = "Test Mail Subject";
	$body = "Hi<br/>Test Mail<br/>Amazon SES"; // HTML  tags
	$return = Send_Mail($to,$subject,$body);
	echo "<pre>";
	print_r($return);
 } catch (Exception $e) {
		echo "<pre>";
		print_r($e);
		return $e;
 }
 
?>