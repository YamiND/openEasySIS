<?php
//TODO: I believe this code works, but I need to test it with a valid email
include_once '../dbConnect.php';
include_once '../functions.php';
require_once "../../vendor/autoload.php";
include_once '../customizations.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['emailTo'], $_POST['emailFrom'], $_POST]'emailBody']) && !empty($_POST['emailTo']) && !empty($_POST['emailFrom']) && !empty($_POST['emailBody']))
{
	$emailTo = $_POST['emailTo'];
	$emailFrom = $_POST['emailFrom'];
	$emailBody = $_POST['emailBody'];

	if ($stmt = $mysqli->prepare("SELECT userFirstName, userLastName FROM users WHERE userEmail = ?"))
	{
		$stmt->bind_param('s', $emailTo);

		if ($stmt->execute())
		{
			$stmt->bind_result($userFirstName, $userLastName);
			$stmt->store_result();

			$stmt->fetch();
		}
	}

	//PHPMailer Object
	$mail = new PHPMailer;

	//From email address and name
	$mail->From = emailFrom; //emailFrom is a Constant in customizations.php
	$mail->FromName = "Student Information System";

	//To address and name
	$mail->addAddress("$emailTo", "$userFirstName $userLastName");

	//Send HTML or Plain Text email
	$mail->isHTML(true);

	$mail->Subject = "Email from: $emailFrom";
	$mail->Body = "<p>" . htmlspecialchars($emailBody) . "</p>";
	$mail->AltBody = "$emailBody";

	if(!$mail->send()) 
	{
		$_SESSION['fail'] = 'Contact Email Failed, email could not be sent';
		header('Location: ../../pages/viewContactForm');
	} 
	else 
	{
		$_SESSION['success'] = "Contact Email Sent, email sent to $emailTo";
		header('Location: ../../pages/viewContactForm');
	}

}
else
{
    // The correct POST variables were not sent to this page.
	$_SESSION['fail'] = 'Email body or other data not sent';
   	header('Location: ../../pages/viewContactForm');
}

?>
