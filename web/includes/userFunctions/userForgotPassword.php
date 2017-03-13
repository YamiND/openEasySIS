<?php
//TODO: I believe this code works, but I need to test it with a valid email
include_once '../dbConnect.php';
include_once '../functions.php';
require_once "../../vendor/autoload.php";
include_once '../customizations.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['userEmail'])) 
{
	$userEmail = $_POST['userEmail'];
    
	if ($stmt = $mysqli->prepare("SELECT userEmail, userSalt FROM users WHERE userEmail = ? LIMIT 1"))
	{
		// Get the user's salt so that we can hash the passwords
		$stmt->bind_param('s', $userEmail);
		if ($stmt->execute())
		{
			$stmt->bind_result($dbUserEmail, $userSalt);
			$stmt->store_result();

			$stmt->fetch();

			if ($stmt->num_rows == 1)
			{
				resetUserPassword($userEmail, $userSalt, $mysqli);
			}	
			else
			{	
				$_SESSION['fail'] = "Email doesn't exist";
 		   		header('Location: ../../pages/forgotPassword');
			}
		}
	}	
	else
	{
		// Select statement failed, output error
		$_SESSION['fail'] = "Email doesn't exist";
    	header('Location: ../../pages/forgotPassword');
	}
}
else
{
    // The correct POST variables were not sent to this page.
	$_SESSION['fail'] = 'Email not submitted';
    	header('Location: ../../pages/forgotPassword');
}

function resetUserPassword($userEmail, $userSalt, $mysqli)
{
	// First we should get the user's name for the email field
	if ($stmt = $mysqli->prepare("SELECT userFirstName, userLastName FROM users WHERE userEmail = ?"))
	{
		$stmt->bind_param('s', $userEmail);

		if ($stmt->execute())
		{
			// Bind the results so we can use this later for our email
			$stmt->bind_result($userFirstName, $userLastName);
			$stmt->store_result();

			$stmt->fetch();
		}
	}

	// Next we set the password to a random string, and then use a hash
	$password = randomString();
	$newPassword = hash('sha512', $password . $userSalt);


	// Now we update our database with the new password for that user
    if ($stmt = $mysqli->prepare("UPDATE users SET userPassword = ? WHERE userEmail = ?"))
    {   
        $stmt->bind_param('ss', $newPassword, $userEmail);  // Bind "$email" to parameter.

        if ($stmt->execute())    // Execute the prepared query.
		{
			// If we can successfully update, now we should email the user

			//PHPMailer Object
			$mail = new PHPMailer;

			//From email address and name
			$mail->From = emailFrom; //emailFrom is a Constant in customizations.php
			$mail->FromName = "Student Information System";

			//To address and name
			$mail->addAddress("$userEmail", "$userFirstName $userLastName");

			//Send HTML or Plain Text email
			$mail->isHTML(true);

			$mail->Subject = "SIS Password Reset";
			$mail->Body = "<p>Password for user has been reset to $password . If you did not send this email, please change your password immediately</p>";
			$mail->AltBody = "Password for user has been reset to $password . If you did not send this email, please change your password immediately";

			if(!$mail->send()) 
			{
        		$_SESSION['fail'] = 'Password Reset Failed, email could not be sent';
		        header('Location: ../../pages/forgotPassword');
			} 
			else 
			{
        		$_SESSION['success'] = "Password Reset Succeeded, email sent to $userEmail";
		        header('Location: ../../pages/login');
			}
		}
    }   
    else
    {   
        $_SESSION['fail'] = 'Password Reset Failed';
        header('Location: ../../pages/forgotPassword');
    }   
}

function randomString($length = 8)
{
    // This function is used to generate a random password
    $str = "";
    $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
    $max = count($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $rand = mt_rand(0, $max);
        $str .= $characters[$rand];
    }       
    return $str;
}

?>
