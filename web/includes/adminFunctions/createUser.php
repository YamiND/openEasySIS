<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 1))
{
	determineAccountType($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Account Creations Failed';
   	header('Location: ../../pages/createUser');

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
			createParentAccount($mysqli);
		}
		else if ($roleID == 5) 
		{
			createStudentAccount($mysqli);
		}
		else
		{
   			$_SESSION['fail'] = 'Account Creations Failed';
		   	header('Location: ../../pages/createUser');

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
		if ($_POST['modProfile'] == 'modProfile')
		{
			$modProfile = '1';
		}
		else
		{
			$modProfile = '0';
		}

		if ($_POST['modClassList'] == 'modClassList')
		{
			$modClassList = '1';
		}
		else
		{
			$modClassList = '0';
		}

		if ($_POST['viewAllGrades'] == 'viewAllGrades')
		{
			$viewAllGrades = '1';
		}
		else
		{
			$viewAllGrades = '0';
		}

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
    	
		$_SESSION['success'] = "Admin Account Created - email is $adminEmail and password is $password";
   	   	header('Location: ../../pages/createUserAccount');
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Account Creation Failed';
   	   	header('Location: ../../pages/createUser');
	}
}

function createSchoolAdminAccount($mysqli)
{
	if (isset($_POST['schoolAdminEmail'], $_POST['schoolAdminFirstName'], $_POST['schoolAdminLastName'])) 
	{
    	$schoolAdminEmail = $_POST['schoolAdminEmail'];
		$schoolAdminFirstName = $_POST['schoolAdminFirstName'];
		$schoolAdminLastName = $_POST['schoolAdminLastName'];
		$roleID = $_POST['roleID'];

		if ($_POST['modProfile'] == 'modProfile')
		{
			$modProfile = '1';
		}
		else
		{
			$modProfile = '0';
		}

		if ($_POST['modClassList'] == 'modClassList')
		{
			$modClassList = '1';
		}
		else
		{
			$modClassList = '0';
		}

		if ($_POST['viewAllGrades'] == 'viewAllGrades')
		{
			$viewAllGrades = '1';
		}
		else
		{
			$viewAllGrades = '0';
		}

		$password = randomString();	

		createUserAccount($schoolAdminEmail, $password, $roleID, $modProfile, $modClassList, $viewAllGrades, $mysqli);

		if ($stmt = $mysqli->prepare("SELECT userID FROM users WHERE userEmail = ? LIMIT 1"))
		{
			$stmt->bind_param('s', $schoolAdminEmail);
			$stmt->execute();
			$stmt->store_result();
			
			if ($stmt->num_rows == 1)
			{
				$stmt->bind_result($schoolAdminID);
				$stmt->fetch();
			}
		}

		createSchoolAdminProfile($schoolAdminID, $schoolAdminFirstName, $schoolAdminLastName, $schoolAdminEmail, $mysqli);
    	
		$_SESSION['success'] = "School Admin Account Created - email is $schoolAdminEmail and password is $password";
   	   	header('Location: ../../pages/createUserAccount');
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Account Creation Failed';
   	   	header('Location: ../../pages/createUserAccount');
	}
}

function createTeacherAccount($mysqli)
{
	if (isset($_POST['teacherEmail'], $_POST['teacherFirstName'], $_POST['teacherLastName'])) 
	{
    	$teacherEmail = $_POST['teacherEmail'];
		$teacherFirstName = $_POST['teacherFirstName'];
		$teacherLastName = $_POST['teacherLastName'];
		$roleID = $_POST['roleID'];

		if ($_POST['modProfile'] == 'modProfile')
		{
			$modProfile = '1';
		}
		else
		{
			$modProfile = '0';
		}

		if ($_POST['modClassList'] == 'modClassList')
		{
			$modClassList = '1';
		}
		else
		{
			$modClassList = '0';
		}

		if ($_POST['viewAllGrades'] == 'viewAllGrades')
		{
			$viewAllGrades = '1';
		}
		else
		{
			$viewAllGrades = '0';
		}

		$password = randomString();	

		createUserAccount($teacherEmail, $password, $roleID, $modProfile, $modClassList, $viewAllGrades, $mysqli);

		if ($stmt = $mysqli->prepare("SELECT userID FROM users WHERE userEmail = ? LIMIT 1"))
		{
			$stmt->bind_param('s', $teacherEmail);
			$stmt->execute();
			$stmt->store_result();
			
			if ($stmt->num_rows == 1)
			{
				$stmt->bind_result($teacherID);
				$stmt->fetch();
			}
		}

		createTeacherProfile($teacherID, $teacherFirstName, $teacherLastName, $teacherEmail, $mysqli);
    	
		$_SESSION['success'] = "Teacher Account Created - email is $teacherEmail and password is $password";
   	   	header('Location: ../../pages/createUserAccount');
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Account Creation Failed';
   	   	header('Location: ../../pages/createUserAccount');
	}
}


function createParentAccount($mysqli)
{
	if (isset($_POST['parentEmail'], $_POST['parentFirstName'], $_POST['parentLastName'], $_POST['parentAddress'], $_POST['parentCity'], $_POST['parentState'], $_POST['parentZip'])) 
	{
    	$parentEmail = $_POST['parentEmail'];
		$parentFirstName = $_POST['parentFirstName'];
		$parentLastName = $_POST['parentLastName'];
		$parentAddress = $_POST['parentAddress'];
		$parentCity = $_POST['parentCity'];
		$parentState = $_POST['parentState'];
		$parentZip = $_POST['parentZip'];


		$roleID = $_POST['roleID'];

		$modProfile = $_POST['modProfile'];
		$modClassList = $_POST['modClassList'];
		$viewAllGrades = $_POST['viewAllGrades'];

		$password = randomString();	

		createUserAccount($parentEmail, $password, $roleID, $modProfile, $modClassList, $viewAllGrades, $mysqli);

		if ($stmt = $mysqli->prepare("SELECT userID FROM users WHERE userEmail = ? LIMIT 1"))
		{
			$stmt->bind_param('s', $parentEmail);
			$stmt->execute();
			$stmt->store_result();
			
			if ($stmt->num_rows == 1)
			{
				$stmt->bind_result($parentID);
				$stmt->fetch();
			}
		}
		
		createParentProfile($parentID, $parentFirstName, $parentLastName, $parentEmail, $parentAddress, $parentCity, $parentState, $parentZip, $mysqli);
    	
		$_SESSION['success'] = "parent Account Created - email is $parentEmail and password is $password";
   	   	header('Location: ../../pages/createUserAccount');
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Account Creation Failed';
   	   	header('Location: ../../pages/createUserAccount');
	}
}


function createStudentAccount($mysqli)
{
	if (isset($_POST['studentEmail'], $_POST['studentFirstName'], $_POST['studentLastName'], $_POST['studentGender'], $_POST['studentGradeLevel'])) 
	{
    	$studentEmail = $_POST['studentEmail'];
		$studentFirstName = $_POST['studentFirstName'];
		$studentLastName = $_POST['studentLastName'];
		$studentGender = $_POST['studentGender'];
		$studentGradeLevel = $_POST['studentGradeLevel'];

		$roleID = $_POST['roleID'];

		$modProfile = $_POST['modProfile'];
		$modClassList = $_POST['modClassList'];
		$viewAllGrades = $_POST['viewAllGrades'];

		$password = randomString();	

		createUserAccount($studentEmail, $password, $roleID, $modProfile, $modClassList, $viewAllGrades, $mysqli);

		if ($stmt = $mysqli->prepare("SELECT userID FROM users WHERE userEmail = ? LIMIT 1"))
		{
			$stmt->bind_param('s', $studentEmail);
			$stmt->execute();
			$stmt->store_result();
			
			if ($stmt->num_rows == 1)
			{
				$stmt->bind_result($studentID);
				$stmt->fetch();
			}
		}

	    createStudentProfile($studentID, $studentFirstName, $studentLastName, $studentEmail, $studentGender, $studentGradeLevel, $mysqli);

		$_SESSION['success'] = "Student Account Created - email is $studentEmail and password is $password";
   	   	header('Location: ../../pages/createUser');
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Account Creation Failed';
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
    		$_SESSION['fail'] = 'Account Creation Failed, Account already exists';
   	   		header('Location: ../../pages/createUserAccount');
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

function createSchoolAdminProfile($schoolAdminID, $schoolAdminFirstName, $schoolAdminLastName, $schoolAdminEmail, $mysqli)
{
    if ($stmt = $mysqli->prepare("INSERT INTO schoolAdminProfile (schoolAdminID, schoolAdminFirstName, schoolAdminLastName, schoolAdminEmail) VALUES (?, ?, ?, ?)"))
	{
    	$stmt->bind_param('isss', $schoolAdminID, $schoolAdminFirstName, $schoolAdminLastName, $schoolAdminEmail); 
	    $stmt->execute();    // Execute the prepared query.
	}
}


function createTeacherProfile($teacherID, $teacherFirstName, $teacherLastName, $teacherEmail, $mysqli)
{
    if ($stmt = $mysqli->prepare("INSERT INTO teacherProfile (teacherID, teacherFirstName, teacherLastName, teacherEmail) VALUES (?, ?, ?, ?)"))
	{
    	$stmt->bind_param('isss', $teacherID, $teacherFirstName, $teacherLastName, $teacherEmail); 
	    $stmt->execute();    // Execute the prepared query.
	}
}

function createParentProfile($parentID, $parentFirstName, $parentLastName, $parentEmail, $parentAddress, $parentCity, $parentState, $parentZip, $mysqli)
{

    if ($stmt = $mysqli->prepare("INSERT INTO parentProfile (parentID, parentFirstName, parentLastName, parentEmail, parentAddress, parentCity, parentState, parentZip) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"))
	{
    	$stmt->bind_param('isssssss', $parentID, $parentFirstName, $parentLastName, $parentEmail, $parentAddress, $parentCity, $parentState, $parentZip); 
	    $stmt->execute();    // Execute the prepared query.
	}
}

function createStudentProfile($studentID, $studentFirstName, $studentLastName, $studentEmail, $studentGender, $studentGradeLevel, $mysqli)
{
    if ($stmt = $mysqli->prepare("INSERT INTO studentProfile (studentID, studentFirstName, studentLastName, studentEmail, studentGender, studentGradeLevel) VALUES (?, ?, ?, ?, ?, ?)"))
	{
    	$stmt->bind_param('issssi', $studentID, $studentFirstName, $studentLastName, $studentEmail, $studentGender, $studentGradeLevel); 
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
