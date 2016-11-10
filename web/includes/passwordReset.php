<?php
include_once 'dbConnect.php';
include_once 'functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['email'], $_POST['oldPassword'], $_POST['newPassword'], $_POST['repeatPassword'])) 
{
    $email = $_POST['email'];
    
    $oldPassword = $_POST['oldPassword']; // The hashed old password.
    $newPassword = $_POST['newPassword']; // The hashed new password.
    $repeatPassword = $_POST['repeatPassword']; // The hashed new repeattd password.

   echo $email . "\n";
   echo $oldPassword . "\n";
   echo $newPassword . "\n";
   echo $repeatPassword . "\n";
    if (login($email, $oldPassword, $mysqli) == true)
    {
        // Do a second check to make sure the password is right
	if($newPassword == $repeatPassword)
	{
//	    changePasword($email, $oldPassword, $newPassword, $mysqli);
//            header('Location: ../pages/settings.php?success=1');
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
