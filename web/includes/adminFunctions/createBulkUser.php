<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

//TODO Test this
if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
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
	$filename = "usersCreated.csv";
	$fp = fopen('php://output', 'w');

	$_SESSION['success'] = 'User Accounts Created';
	header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename='.$filename);

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
				$userEmail = $userCSV[$i][0];
				$userFirstName  = $userCSV[$i][1];
				$userLastName = $userCSV[$i][2];
				$modProfile = $userCSV[$i][3];
				$modClassList = $userCSV[$i][4];
				$viewAllGrades = $userCSV[$i][5];
				$isAdmin = $userCSV[$i][6];
				$isSchoolAdmin = $userCSV[$i][7];
				$isTeacher = $userCSV[$i][8];
				$isParent = $userCSV[$i][9];
				$isStudent = $userCSV[$i][10];
				$gradeLevel  = $userCSV[$i][11];
				$birthdate = $userCSV[$i][12];
				$gender = $userCSV[$i][13];
				$graduationYear = $userCSV[$i][14];
				$gpa = $userCSV[$i][15];
				$phone = $userCSV[$i][16];
				$altEmail = $userCSV[$i][17];
				$address = $userCSV[$i][18];
				$city = $userCSV[$i][19];
				$state = $userCSV[$i][20];
				$zip = $userCSV[$i][21];

				$password = randomString();	

				createUserAccount($userEmail, $password, $userFirstName, $userLastName,  $modProfile, $modClassList, $viewAllGrades, $isAdmin, $isSchoolAdmin, $isTeacher, $isParent, $isStudent, $mysqli);
		

				if ($isParent)
				{
					createParentProfile($userEmail, $userFirstName, $userLastName, $phone, $altEmail, $address, $city, $state, $zip, $mysqli);
				}
				
				if ($isStudent)
				{
	    			createStudentProfile($userEmail, $userFirstName, $userLastName, $gradeLevel, $birthdate, $gender, $graduationYear, $gpa, $mysqli);
				}

				// Add email and password to output csv
				fputcsv($fp, array($userEmail, $password));
			}
    	}
	}
}

function createUserAccount($userEmail, $password, $userFirstName, $userLastName,  $modProfile, $modClassList, $viewAllGrades, $isAdmin, $isSchoolAdmin, $isTeacher, $isParent, $isStudent, $mysqli)
{
	$randomSalt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
	$hashedPassword = hash("sha512", $password . $randomSalt);

	if ($stmt = $mysqli->prepare("SELECT userEmail FROM users where userEmail = ?"))
	{
		$stmt->bind_param('s', $userEmail);

		if($stmt->execute())
		{
			$stmt->store_result();

			if ($stmt->num_rows > 0)
			{
    			$_SESSION['fail'] = 'Account Creation Failed, Account already exists';
			}
			else
			{
				if ($stmt = $mysqli->prepare("INSERT INTO users (userEmail, userPassword, userFirstName, userLastName  modProfile, modClassList, viewAllGrades, userSalt, isAdmin, isSchoolAdmin, isTeacher, isParent, isStudent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))
				{
    				$stmt->bind_param('ssssiiisiiiii', $userEmail, $hashedPassword, $userFirstName, $userLastName, $modProfile, $modClassList, $viewAllGrades, $randomSalt, $isAdmin, $isSchoolAdmin, $isTeacher, $isParent, $isStudent); 
	    			$stmt->execute();    // Execute the prepared query.
				}
				else
				{
    				$_SESSION['fail'] = 'Account Creation Failed, data could not be inserted into the database';
				}
			}
		}
		else
		{
    		$_SESSION['fail'] = 'Account Creation Failed, database query failed';
		}
	}
	else
	{
    	$_SESSION['fail'] = 'Account Creation Failed, database query failed';
	}
}

function createParentProfile($userEmail, $userFirstName, $userLastName, $phone=NULL, $altEmail=NULL, $address, $city, $state, $zip, $mysqli)
{

	if ($stmt = $mysqli->prepare("SELECT userID FROM users WHERE userEmail = ?")
	{
		$stmt->bind_param('s', $userEmail);

		if ($stmt->execute())
		{
			$stmt->bind_result($userID);
			$stmt->store_result();

			$stmt->fetch();
		}
	}

    if ($stmt = $mysqli->prepare("INSERT INTO parentProfile (parentID, parentFirstName, parentLastName, parentEmail, parentAddress, parentCity, parentState, parentZip, parentAltEmail, parentPhoneNumber) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))
	{
    	$stmt->bind_param('isssssssss', $userID, $userFirstName, $userLastName, $userEmail, $address, $city, $state, $zip, $altEmail, $phone); 
	    $stmt->execute();    // Execute the prepared query.
	}
}

function createStudentProfile($userEmail, $userFirstName, $userLastName, $gradeLevel, $birthdate, $gender, $graduationYear, $gpa, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT userID FROM users WHERE userEmail = ?")
	{
		$stmt->bind_param('s', $userEmail);

		if ($stmt->execute())
		{
			$stmt->bind_result($userID);
			$stmt->store_result();

			$stmt->fetch();
		}
	}

    if ($stmt = $mysqli->prepare("INSERT INTO studentProfile (studentID, studentFirstName, studentLastName, studentBirthdate, studentGender, studentGradYear, studentGPA, studentGradeLevel, studentEmail) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"))
	{
    	$stmt->bind_param('issssdiis', $userID, $userFirstName, $userLastName, $birthdate, $gender, $graduationYear, $gpa, $gradeLevel, $userEmail); 
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
