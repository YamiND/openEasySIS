<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 1))
{
	parseCSV($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Account Creation Failed, invalid permissions';
   	header('Location: ../../pages/createBulkUser');
}

function parseCSV($mysqli)
{
	$output = fopen('/tmp/createdUsers.csv', 'w');

	$usersArray = array();

	fputcsv($output, array('Email', 'Password'));

	if($_FILES['csvFile']['error'] == 0)
	{
    	$name = $_FILES['csvFile']['name'];
    	$ext = strtolower(end(explode('.', $_FILES['csvFile']['name'])));
	    $type = $_FILES['csvFile']['type'];
	    $tmpName = $_FILES['csvFile']['tmp_name'];

    	// check the file is a csv
    	if($ext === 'csv')
		{
			$userCSV = array_map('str_getcsv', file($tmpName));
			foreach($userCSV as $i => $data)
			{
				$roleID = $userCSV[$i][0];
				$userEmail = $userCSV[$i][1];
				$userFirstName  = $userCSV[$i][2];
				$userLastName = $userCSV[$i][3];
				$gradeLevel  = $userCSV[$i][4];
				$birthdate = $userCSV[$i][5];
				$gender = $userCSV[$i][6];
				$graduationYear = $userCSV[$i][7];
				$gpa = $userCSV[$i][8];
				$phone = $userCSV[$i][9];
				$altEmail = $userCSV[$i][10];
				$address = $userCSV[$i][11];
				$city = $userCSV[$i][12];
				$state = $userCSV[$i][13];
				$zip = $userCSV[$i][14];
				$modProfile = $userCSV[$i][15];
				$modClassList = $userCSV[$i][16];
				$viewAllGrades = $userCSV[$i][17];
				$isParent = $userCSV[$i][18];

				if (!empty($roleID))
				{
					switch ($roleID)
					{
						case 1:
							$value = createAdminAccount($roleID, $userEmail, $userFirstName, $userLastName, $isParent, $mysqli);
							break;
						case 2:
							createSchoolAdminAccount($roleID, $userEmail, $userFirstName, $userLastName, $modProfile, $modClassList, $viewAllGrades, $isParent, $mysqli);
							break;
						case 3:
							createTeacherAccount($roleID, $userEmail, $userFirstName, $userLastName, $modProfile, $modClassList, $viewAllGrades, $isParent, $mysqli);
							break;
						case 4:
							createParentAccount($roleID, $userEmail, $userFirstName, $userLastName, $phone, $altEmail, $address, $city, $state, $zip, $isParent, $mysqli);
							break;
						case 5:
							createStudentAccount($roleID, $userEmail, $userFirstName, $userLasName, $gradeLevel, $birthdate, $gender, $graduationYear, $gpa, $mysqli);
							break;
						default:
							break;
					}
					array_push($usersArray, explode(',', $value));
				}
			}
    	}
	}
	fputcsv($output, $usersArray);
	fclose($output);

	// output headers so that the file is downloaded rather than displayed
	header("Content-type: text/csv");
	header("Content-disposition: attachment; filename = createdUsers.csv");
	readfile("/tmp/createdUsers.csv");

	$_SESSION['success'] = 'User Accounts Created';
   	//header('Location: ../../pages/createBulkUser');

}

function createAdminAccount($roleID, $userEmail, $userFirstName, $userLastName, $isParent = 0, $mysqli)
{
	if (!empty($roleID) && !empty($userEmail) && !empty($userFirstName) && !empty($userLastName)) 
	{
		$modProfile = 1;
		$modClassList = 1;
		$viewAllGrades = 1;

		$password = randomString();	


		$value = createUserAccount($userEmail, $password, $roleID, $modProfile, $modClassList, $viewAllGrades, $isParent, $mysqli);

		if ($stmt = $mysqli->prepare("SELECT userID FROM users WHERE userEmail = ? LIMIT 1"))
		{
			$stmt->bind_param('s', $userEmail);
			$stmt->execute();
			$stmt->store_result();
			
			if ($stmt->num_rows == 1)
			{
				$stmt->bind_result($userID);
				$stmt->fetch();
			}
		}
		createAdminProfile($userID, $userFirstName, $userLastName, $userEmail, $mysqli);

		return $value;
    }
	else
	{
    	$_SESSION['fail'] = 'Account Creation Failed, data not entered correctly';
	}
}

function createSchoolAdminAccount($roleID, $userEmail, $userFirstName, $userLastName, $modProifle, $modClassList, $viewAllGrades, $isParent, $mysqli)
{
	if (!empty($roleID) && !empty($userEmail) && !empty($userFirstName) && !empty($userLastName))
	{
		$password = randomString();	
		createUserAccount($userEmail, $password, $roleID, $modProfile, $modClassList, $viewAllGrades, $isParent, $mysqli);

		if ($stmt = $mysqli->prepare("SELECT userID FROM users WHERE userEmail = ? LIMIT 1"))
		{
			$stmt->bind_param('s', $userEmail);
			$stmt->execute();
			$stmt->store_result();
			
			if ($stmt->num_rows == 1)
			{
				$stmt->bind_result($schoolAdminID);
				$stmt->fetch();
			}
		}
		createSchoolAdminProfile($schoolAdminID, $userFirstName, $userLastName, $userEmail, $mysqli);
    }
	else
	{
    	$_SESSION['fail'] = 'Account Creation Failed, data not entered correctly';
	}
}

function createTeacherAccount($roleID, $userEmail, $userFirstName, $userLastName, $modProfile, $modClassList, $viewAllGrades, $isParent, $mysqli)
{
	if (!empty($roleID) && !empty($userEmail) && !empty($userFirstName) && !empty($userLastName))
	{
		$password = randomString();	
		createUserAccount($userEmail, $password, $roleID, $modProfile, $modClassList, $viewAllGrades, $isParent, $mysqli);

		if ($stmt = $mysqli->prepare("SELECT userID FROM users WHERE userEmail = ? LIMIT 1"))
		{
			$stmt->bind_param('s', $userEmail);
			$stmt->execute();
			$stmt->store_result();
			
			if ($stmt->num_rows == 1)
			{
				$stmt->bind_result($teacherID);
				$stmt->fetch();
			}
		}
		createTeacherProfile($teacherID, $userFirstName, $userLastName, $userEmail, $mysqli);
    }
	else
	{
    	$_SESSION['fail'] = 'Account Creation Failed, data not entered correctly';
	}
}

function createParentAccount($roleID, $userEmail, $userFirstName, $userLastName, $phone, $altEmail, $address, $city, $state, $zip, $isParent, $mysqli)
{
	if (!empty($roleID) && !empty($userEmail) && !empty($userFirstName) && !empty($userLastName) && !empty($address) && !empty($city) && !empty($state) && !empty($zip)) 
	{
		$modProfile = 0;
		$modClassList = 0;
		$viewAllGrades = 0;

		$password = randomString();	

		createUserAccount($userEmail, $password, $roleID, $modProfile, $modClassList, $viewAllGrades, $isParent, $mysqli);

		if ($stmt = $mysqli->prepare("SELECT userID FROM users WHERE userEmail = ? LIMIT 1"))
		{
			$stmt->bind_param('s', $userEmail);
			$stmt->execute();
			$stmt->store_result();
			
			if ($stmt->num_rows == 1)
			{
				$stmt->bind_result($parentID);
				$stmt->fetch();
			}
		}
		createParentProfile($parentID, $userEmail, $userFirstName, $userLastName, $phone, $altEmail, $address, $city, $state, $zip, $mysqli);
    }
	else
	{
    	$_SESSION['fail'] = 'Account Creation Failed';
	}
}

function createStudentAccount($roleID, $userEmail, $userFirstName, $userLasName, $gradeLevel, $birthdate, $gender, $graduationYear, $gpa, $mysqli)
{
	if (!empty($roleID) && !empty($userEmail) && !empty($userFirstName) && !empty($userLastName) && !empty($gradeLevel)) 
	{
		$modProfile = 0;
		$modClassList = 0;
		$viewAllGrades = 0;

		$password = randomString();	

		createUserAccount($userEmail, $password, $roleID, $modProfile, $modClassList, $viewAllGrades, $mysqli);

		if ($stmt = $mysqli->prepare("SELECT userID FROM users WHERE userEmail = ? LIMIT 1"))
		{
			$stmt->bind_param('s', $userEmail);
			$stmt->execute();
			$stmt->store_result();
			
			if ($stmt->num_rows == 1)
			{
				$stmt->bind_result($studentID);
				$stmt->fetch();
			}
		}

	    createStudentProfile($studentID, $userEmail, $userFirstName, $userLasName, $gradeLevel, $birthdate, $gender, $graduationYear, $gpa, $mysqli);
    }
	else
	{
    	$_SESSION['fail'] = 'Account Creation Failed';
	}
}


function createUserAccount($userEmail, $password, $roleID, $modProfile = 0, $modClassList = 0, $viewAllGrades = 0, $isParent = 0, $mysqli)
{
	$randomSalt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
	$hashedPassword = hash("sha512", $password . $randomSalt);

	if (empty($isParent))
	{
		$isParent = 0;
	}
	if (empty($viewAllGrades))
	{
		$viewAllGrades = 0;
	}
	if (empty($modClassList))
	{
		$modClassList = 0;
	}
	if (empty($modProfile))
	{
		$modProfile = 0;
	}
	
	if ($stmt = $mysqli->prepare("SELECT userEmail FROM users where userEmail = ?"))
	{
		$stmt->bind_param('s', $userEmail);

		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows > 0)
		{
    		$_SESSION['fail'] = 'Account Creation Failed, Account already exists';
		}
		else
		{
			if ($stmt = $mysqli->prepare("INSERT INTO users (userEmail, userPassword, roleID, modProfile, modClassList, viewAllGrades, userSalt, isParent) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"))
			{
    			$stmt->bind_param('ssiiiisi', $userEmail, $hashedPassword, $roleID, $modProfile, $modClassList, $viewAllGrades, $randomSalt, $isParent); 
	    		$stmt->execute();    // Execute the prepared query.

				return "$userEmail, $password";
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

function createParentProfile($parentID, $userEmail, $userFirstName, $userLastName, $phone, $altEmail, $address, $city, $state, $zip, $mysqli)
{
    if ($stmt = $mysqli->prepare("INSERT INTO parentProfile (parentID, parentFirstName, parentLastName, parentEmail, parentAddress, parentCity, parentState, parentZip, parentAltEmail, parentPhoneNumber) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))
	{
    	$stmt->bind_param('isssssss', $parentID, $userFirstName, $userLastName, $userEmail, $address, $city, $state, $zip, $altEmail, $phone); 
	    $stmt->execute();    // Execute the prepared query.
	}
}

function createStudentProfile($studentID, $userEmail, $userFirstName, $userLasName, $gradeLevel, $birthdate, $gender, $graduationYear, $gpa, $mysqli)
{
    if ($stmt = $mysqli->prepare("INSERT INTO studentProfile (studentID, studentFirstName, studentLastName, studentBirthdate, studentGender, studentGradYear, studentGPA, studentGradeLevel, studentEmail) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"))
	{
    	$stmt->bind_param('issssisis', $studentID, $userFirstName, $userLastName, $birthdate, $gender, $graduationYear, $gpa, $gradeLevel, $userEmail); 
	    $stmt->execute();    // Execute the prepared query.
	}
}

function randomString($length = 8) 
{
	// This function is used to generate a random password
	$str = "";
	$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) 
	{
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}

?>
