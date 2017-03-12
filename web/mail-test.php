<?php

require_once "vendor/autoload.php";

//PHPMailer Object
$mail = new PHPMailer;

//From email address and name
$mail->From = "sis@maplewoodbaptistacademy.com";
$mail->FromName = "Student Information System";

//To address and name
$mail->addAddress("tpostma@lssu.edu", "Tyler Postma");

//Send HTML or Plain Text email
$mail->isHTML(true);

$mail->Subject = "PHP Mailer Email Test";
$mail->Body = "<i>Mail body in HTML (but WHY?)</i>";
$mail->AltBody = "This is the plain text version of the email content (lame)";

if(!$mail->send()) 
{
    echo "Mailer Error: " . $mail->ErrorInfo;
} 
else 
{
    echo "Message has been sent successfully";
}
