<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['userEmail'], $_POST['oldPassword'], $_POST['newPassword'], $_POST['repeatPassword'])) 
{
    $userEmail = $_POST['userEmail'];
    
    $oldPassword = $_POST['oldPassword']; // The hashed old password.
    $newPassword = $_POST['newPassword']; // The hashed new password.
    $repeatPassword = $_POST['repeatPassword']; // The hashed new repeattd password.

    if (login($userEmail, $oldPassword, $mysqli) == true)
    {
        // Do a second check to make sure the password is right
		if($newPassword == $repeatPassword)
		{

			if ($stmt = $mysqli->prepare("SELECT userSalt FROM users WHERE userEmail = ? LIMIT 1"))
			{
				$stmt->bind_param('s', $userEmail);
				$stmt->execute();
				$stmt->store_result();

				$stmt->bind_result($userSalt);
				$stmt->fetch();

				
				$oldPassword = hash('sha512', $oldPassword . $userSalt);	

				// I don't see an issue with using the same salt
				$newPassword = hash('sha512', $newPassword . $userSalt);
				
				echo "Before changePassword function <br>";
				changeUserPassword($userEmail, $oldPassword, $newPassword, $mysqli);
			}
		}	
    }
    else
    {
        // Login failed
        #header('Location: ../pages/login?error=1');
    }
}
else
{
    // The correct POST variables were not sent to this page.
    echo 'Invalid Request';
}

function changeUserPassword($userEmail, $oldPassword, $newPassword, $mysqli)
{
	if (DEBUG):
		echo "Email: $userEmail <br>";
		echo "Old Password: $oldPassword <br>";
		echo "New Password: $newPassword <br>";
	endif;
	

    // Using prepared statements means that SQL injection is not possible. 
    $stmt = $mysqli->prepare("UPDATE users SET userPassword = ? WHERE userEmail = ? AND userPassword = ?");

    $stmt->bind_param('sss', $newPassword, $userEmail, $oldPassword);  // Bind "$email" to parameter.
    $stmt->execute();    // Execute the prepared query.

	$user_browser = $_SERVER['HTTP_USER_AGENT'];

	$_SESSION['login_string'] = hash('sha512', $newPassword . $user_browser);

//    header('Location: settings');
}

