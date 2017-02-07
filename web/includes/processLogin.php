<?php
include_once 'dbConnect.php';
include_once 'functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['userEmail'], $_POST['password'])) 
{
	// User-supplied email
    $userEmail = $_POST['userEmail'];
	
	// User-supplied password, not hased until it hits the login function
    $password = $_POST['password']; 

    if (login($userEmail, $password, $mysqli) == true)
    {
		// Go to our dashboard for the users
    	header('Location: ../pages/dashboard');
    }
    else
    {
        // Login failed, output a message via a $_SESSION variable
		$_SESSION['invalidLogin'] = 'Username/Password Incorrect';
		header('Location: ../pages/login');
    }
}
else
{
    // The correct POST variables were not sent to this page.
    // Login failed, output a message via a $_SESSION variable
	$_SESSION['invalidLogin'] = 'Username/Password Incorrect';
	header('Location: ../pages/login');
}
