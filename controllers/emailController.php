<?php

require_once 'vendor/autoload.php';
require_once "config/config.php";

// Create the Transport
$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465,'ssl'))
  ->setUsername(EMAIL)
  ->setPassword(PASSWORD)
;

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);




function sendVerificationEmail($userEmail,$token){
    global $mailer;
    $body = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Verify Email</title>
    </head>
    <body>
        <div class="wrapper">
            <p>Thank You for signing up onn your website. please click on the below to verify your Email</p>
            <a href="http://localhost/email-verification-with-php/index.php?token='. $token . '">Verify Your Email address</a>
        </div>
    </body>
    </html>
    
    ';
    
// Create a message
$message = (new Swift_Message('Verify your Email Address'))
  ->setFrom(EMAIL)
  ->setTo($userEmail)
  ->setBody($body,'text/html');

// Send the message
$result = $mailer->send($message);
}






?>