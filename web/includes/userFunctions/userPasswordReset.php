<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['userEmail'], $_POST['oldPassword'], $_POST['newPassword'], $_POST['repeatPassword'])) 
{
    $userEmail = $_POST['userEmail'];
    
    $oldPassword = $_POST['oldPassword']; // The old password.
    $newPassword = $_POST['newPassword']; // The  new password.
    $repeatPassword = $_POST['repeatPassword']; // The repeat password.

    if (login($userEmail, $oldPassword, $mysqli) == true)
    {
        // Do a second check to make sure the password is right
		if($newPassword == $repeatPassword)
		{

			if ($stmt = $mysqli->prepare("SELECT userSalt FROM users WHERE userEmail = ? LIMIT 1"))
			{
				// Get the user's salt so that we can hash the passwords
				$stmt->bind_param('s', $userEmail);
				$stmt->execute();
				$stmt->store_result();

				$stmt->bind_result($userSalt);
				$stmt->fetch();
				
				$oldPassword = hash('sha512', $oldPassword . $userSalt);	

				// I don't see an issue with using the same salt
				$newPassword = hash('sha512', $newPassword . $userSalt);
				
				changeUserPassword($userEmail, $oldPassword, $newPassword, $mysqli);
			}
			else
			{	
				$_SESSION['invalidReset'] = 'Password Reset Failed';
    			header('Location: ../../pages/settings');
			}
		}	
		else
		{
			// Passwords do not match, output error
			$_SESSION['invalidReset'] = 'Password Reset Failed';
    		header('Location: ../../pages/settings');
		}
    }
    else
    {
        // Login failed, output error
		$_SESSION['invalidReset'] = 'Password Reset Failed';
    	header('Location: ../../pages/settings');
    }
}
else
{
    // The correct POST variables were not sent to this page.
	$_SESSION['invalidReset'] = 'Password Reset Failed';
    header('Location: ../../pages/settings');
}

function changeUserPassword($userEmail, $oldPassword, $newPassword, $mysqli)
{
    // Using prepared statements means that SQL injection is not possible. 
    if ($stmt = $mysqli->prepare("UPDATE users SET userPassword = ? WHERE userEmail = ? AND userPassword = ?"))
	{
    	$stmt->bind_param('sss', $newPassword, $userEmail, $oldPassword);  // Bind "$email" to parameter.
    	$stmt->execute();    // Execute the prepared query.

		$user_browser = $_SERVER['HTTP_USER_AGENT'];

		$_SESSION['login_string'] = hash('sha512', $newPassword . $user_browser);

		$_SESSION['resetSuccess'] = 'Password Reset Succeeded';
        header('Location: ../../pages/settings');
	}
	else
	{
		$_SESSION['invalidReset'] = 'Password Reset Failed';
        header('Location: ../../pages/settings');
	}
}

