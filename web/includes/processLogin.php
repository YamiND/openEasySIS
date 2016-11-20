<?php
include_once 'dbConnect.php';
include_once 'functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['userEmail'], $_POST['password'])) 
{
    $userEmail = $_POST['userEmail'];
    $password = $_POST['password']; // The hashed password.

    if (login($userEmail, $password, $mysqli) == true)
    {
        // Login success

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
