<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if (login_check($mysqli) == true)
{
	determineAccountType($mysqli);
}
else
{
   	$_SESSION['invalidCreate'] = 'Account Creations Failed';
   	header('Location: ../../pages/createUserAccount');

	return;
}

function determineAccountType($mysqli)
{

	if (!empty($_POST['roleID']))
	{
		$roleID = $_POST['roleID'];

		if ($roleID == 1)
		{
			createAdminAccount($mysqli);
		}
		else if ($roleID == 2)
		{
			createSchoolAdminAccount($mysqli);
		}
		else if ($roleID == 3)
		{
			createTeacherAccount($mysqli);
		}
		else if ($roleID == 4)
		{
			createGuardianAccount($mysqli);
		}
		else if ($roleID == 5) 
		{
			createStudentAccount($mysqli);
		}
		else
		{
   			$_SESSION['invalidCreate'] = 'Account Creations Failed';
		   	header('Location: ../../pages/createUserAccount');

			return;
		}
	}
}

function createAdminAccount($mysqli)
{

	if (isset($_POST['adminEmail'], $_POST['adminFirstName'], $_POST['adminLastName'])) 
	{
    	$adminEmail = $_POST['adminEmail'];
		$adminFirstName = $_POST['adminFirstName'];
		$adminLastName = $_POST['adminLastName'];
		$roleID = $_POST['roleID'];
		$modProfile = '1';
		$modClassList = '1';
		$viewAllGrades = '1';
		$password = randomString();	

		createUserAccount($adminEmail, $password, $roleID, $modProfile, $modClassList, $viewAllGrades, $mysqli);

		if ($stmt = $mysqli->prepare("SELECT userID FROM users WHERE userEmail = ? LIMIT 1"))
		{
			$stmt->bind_param('s', $adminEmail);
			$stmt->execute();
			$stmt->store_result();
			
			if ($stmt->num_rows == 1)
			{
				$stmt->bind_result($userID);
				$stmt->fetch();
			}
		}

		createAdminProfile($userID, $adminFirstName, $adminLastName, $adminEmail, $mysqli);
    	
		$_SESSION['createSuccess'] = "Admin Account Created - email is $adminEmail and password is $password";
   	   	header('Location: ../../pages/createUserAccount');
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['invalidCreate'] = 'Account Creation Failed';
   	   	header('Location: ../../pages/createUserAccount');
	}
}

function createUserAccount($email, $password, $roleID, $modProfile, $modClassList, $viewAllGrades, $mysqli)
{
	$randomSalt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
	$hashedPassword = hash("sha512", $password . $randomSalt);

	if ($stmt = $mysqli->prepare("SELECT userEmail FROM users where userEmail = ?"))
	{
		$stmt->bind_param('s', $email);

		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows > 0)
		{
			return -1;
		}
		else
		{
    	
			if ($stmt = $mysqli->prepare("INSERT INTO users (userEmail, userPassword, roleID, modProfile, modClassList, viewAllGrades, userSalt) VALUES (?, ?, ?, ?, ?, ?, ?)"))
			{
    			$stmt->bind_param('ssiiiis', $email, $hashedPassword, $roleID, $modProfile, $modClassList, $viewAllGrades, $randomSalt); 
	    	$stmt->execute();    // Execute the prepared query.
			}
		}
	}
}

function createAdminProfile($adminID, $adminFirstName, $adminLastName, $adminEmail, $mysqli)
{
    if ($stmt = $mysqli->prepare("INSERT INTO adminProfile (adminID, adminFirstName, adminLastName, adminEmail) VALUES (?, ?, ?, ?)"))
	{
    	$stmt->bind_param('isss', $adminID, $adminFirstName, $adminLastName, $adminEmail); 
	    $stmt->execute();    // Execute the prepared query.
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
