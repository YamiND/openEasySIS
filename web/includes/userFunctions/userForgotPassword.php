<?php
//TODO: I believe this code works, but I need to test it with a valid email
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['userEmail'])) 
{
	$userEmail = $_POST['userEmail'];
    
	if ($stmt = $mysqli->prepare("SELECT userEmail FROM users WHERE userEmail = ? LIMIT 1"))
	{
		// Get the user's salt so that we can hash the passwords
		$stmt->bind_param('s', $userEmail);
		$stmt->execute();
		$stmt->store_result();

		$stmt->fetch();

		if ($stmt->num_rows == 1)
		{
			sendUserEmail($userEmail);
			$_SESSION['invalidEmail'] = "Email has been sent to $userEmail. Link is valid for 30 minutes";
    		header('Location: ../../pages/forgotPassword');
		}	
		else
		{	
			$_SESSION['invalidEmail'] = "Email doesn't exist";
 	   		header('Location: ../../pages/forgotPassword');
		}
	}	
	else
	{
		// Select statement failed, output error
		$_SESSION['invalidEmail'] = "Email doesn't exist";
    	header('Location: ../../pages/forgotPassword');
	}
}
else
{
    // The correct POST variables were not sent to this page.
	$_SESSION['invalidEmail'] = 'Email not submitted';
    	header('Location: ../../pages/forgotPassword');
}

function sendUserEmail($userEmail)
{
	$to = "$userEmail";
    $subject = "Password Reset Request";
         
    $message = "<p>Reset password for $userEmail.</p>";
    $message .= "<h1>This is headline.</h1>";
         
    $header = "From:noreply@sis.lakertech.com\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-type: text/html\r\n";
         
    $retval = mail ($to,$subject,$message,$header);
}

?>
