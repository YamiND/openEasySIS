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
        // Login success, check role ID to determine page to land at
		if (roleID_check($mysqli) == 1)
		{
			// Display Admin Dashboard
        	header('Location: ../pages/adminDashboard');
		}
		else if (roleID_check($mysqli) == 2)
		{
			// Display schoolAdmin Dashboard
        	header('Location: ../pages/schoolAdminDashboard');
		}
		else if (roleID_check($mysqli) == 3)
		{
			// Display Teacher Dashboard
        	header('Location: ../pages/teacherDashboard');
		}
		else if (roleID_check($mysqli) == 4)
		{
			// Display Guardian Dashboard
        	header('Location: ../pages/guardianDashboard');
		}
		else if (roleID_check($mysqli) == 5)
		{
			// Display Student Dashboard
        	header('Location: ../pages/studentDashboard');
		}
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
