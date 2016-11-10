<?php
include_once 'dbConnect.php';
include_once 'functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['userEmail'], $_POST['p'])) 
{
    $userEmail = $_POST['userEmail'];
    $password = $_POST['p']; // The hashed password.

    if (login($userEmail, $password, $mysqli) == true)
    {
        // Login success
        header('Location: ../pages/index.php');
    }
    else
    {
	//TODO: Add an error message for a failed login
        // Login failed
//        header('Location: ../pages/login?error=1');
    }
}
else
{
    // The correct POST variables were not sent to this page.
    echo 'Invalid Request';
}
