<?php

if(isset($_POST['message']) && isset($_POST['email'])){
	$to      = 'aulet.jp@gmail.com';
	$subject = 'Hire Me';
	$message = $_POST['_subject']." from ".filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)." ".$_POST['message'];
	$headers = 'From: aulet.io' . "\r\n" .
	    'Reply-To: aulet.io' . "\r\n" .
	    'X-Mailer: PHP/' . phpversion();

	mail($to, $subject, $message, $headers);
}
?> 