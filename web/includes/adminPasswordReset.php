<?php
include_once 'dbConnect.php';
include_once 'functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

function randomString($length = 8) {
	$str = "";
	$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}
if (login_check($mysqli) == true):


	if (isset($_POST['userEmail'])) 
	{
    	$userEmail = $_POST['userEmail'];
		$password = randomString();	

		$randomSalt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
	
		$hashedPassword = hash("sha512", $password . $randomSalt);

		echo "$password" . "<br>";
		echo $randomSalt . "<br>";
		echo $hashedPassword . "<br>";

		adminChangePassword($userEmail, $hashedPassword, $randomSalt, $mysqli);

		echo "Password has been changed";
		echo "new password for " . $userEmail . " is " . $password;
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	echo 'Invalid Request';
	}

else:
	$url = "login";
	header("Location:$url");
	return;
endif;

?>
