<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

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

if (login_check($mysqli) == true)
{
	if (isset($_POST['userEmail'])) 
	{
    	$userEmail = $_POST['userEmail'];
		$password = randomString();	

		$randomSalt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
		$hashedPassword = hash("sha512", $password . $randomSalt);
		if(!empty($userEmail))
		{
			adminChangePassword($userEmail, $password, $hashedPassword, $randomSalt, $mysqli);
		}
		else
		{
    		$_SESSION['invalidReset'] = 'Password Reset Failed';
        	header('Location: ../../pages/adminPasswordReset');
		}
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['invalidReset'] = 'Password Reset Failed';
        header('Location: ../../pages/adminPasswordReset');
	}
}
else
{
    $_SESSION['invalidReset'] = 'Password Reset Failed';
    header('Location: ../../pages/adminPasswordReset');

	return;
}

function adminChangePassword($userEmail, $password, $hashedPassword, $randomSalt, $mysqli)
{
    if ($stmt = $mysqli->prepare("UPDATE users SET userPassword = ?, userSalt = ? WHERE userEmail = ?"))
	{
    	$stmt->bind_param('sss', $hashedPassword, $randomSalt, $userEmail);  // Bind "$email" to parameter.
	    $stmt->execute();    // Execute the prepared query.

		$_SESSION['resetSuccess'] = "Password Reset Succeeded - Password for $userEmail is $password";
   		header('Location: ../../pages/adminPasswordReset');
	}
}

?>
