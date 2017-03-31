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

	header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename='.$filename);

	if(!isset($_FILES['csvFile']) || $_FILES['csvFile']['error'] == UPLOAD_ERR_NO_FILE) 
	{
   		$_SESSION['fail'] = 'Account Creation Failed, file not uploaded';
   		header('Location: ../../pages/createBulkUser');
	} 
	else 
	{
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

				if (isset($_POST['isStudent']) && !empty($_POST['isStudent']))
				{	
					foreach($userCSV as $i => $data)
					{
						$userEmail = $userCSV[$i][0];
						$userFirstName = $userCSV[$i][1];
						$userLastName = $userCSV[$i][2];
						$studentGradeLevel = $userCSV[$i][3];
						$studentBirthdate = $userCSV[$i][4];
						$studentGender = $userCSV[$i][5];
						
						if ($studentGradeLevel > 12 || $studentGradeLevel < 1)
						{
   							$_SESSION['fail'] = 'Account Creation Failed, Grade level is not valid';
					   		header('Location: ../../pages/createBulkUser');
						}
						$password = randomString();	

						createStudentAccount($userEmail, $userFirstName, $userLastName, $password, $studentGradeLevel, $studentBirthdate, $studentGender, $mysqli);
		
						// Add email and password to output csv
						fputcsv($fp, array($userEmail, $password));
					}
				}
				else if (isset($_POST['isParent']) && !empty($_POST['isParent']))
				{
					foreach($userCSV as $i => $data)
					{
						$userEmail = $userCSV[$i][0];
						$userFirstName = $userCSV[$i][1];
						$userLastName = $userCSV[$i][2];
						$parentAddress = $userCSV[$i][3];
						$parentPhone = $userCSV[$i][4];

						$password = randomString();	

						createParentAccount($userEmail, $userFirstName, $userLastName, $password, $parentAddress, $parentPhone, $mysqli);
		
						// Add email and password to output csv
						fputcsv($fp, array($userEmail, $password));
					}
				}
				else if (isset($_POST['isTeacher']) && !empty($_POST['isTeacher']))
				{
					foreach($userCSV as $i => $data)
					{
						$userEmail = $userCSV[$i][0];
						$userFirstName = $userCSV[$i][1];
						$userLastName = $userCSV[$i][2];
						$modClassList = $userCSV[$i][3];
						$viewAllGrades = $userCSV[$i][4];

						$password = randomString();	

						createTeacherAccount($userEmail, $userFirstName, $userLastName, $password, $modClassList, $viewAllGrades, $mysqli);
		
						// Add email and password to output csv
						fputcsv($fp, array($userEmail, $password));
					}
				}
				else if (isset($_POST['isSchoolAdmin']) && !empty($_POST['isSchoolAdmin']))
				{
					foreach($userCSV as $i => $data)
					{
						$userEmail = $userCSV[$i][0];
						$userFirstName = $userCSV[$i][1];
						$userLastName = $userCSV[$i][2];

						$password = randomString();	

						createSchoolAdminAccount($userEmail, $userFirstName, $userLastName, $password, $mysqli);
		
						// Add email and password to output csv
						fputcsv($fp, array($userEmail, $password));
					}
				}
				else if (isset($_POST['isAdmin']) && !empty($_POST['isAdmin']))
				{
					foreach($userCSV as $i => $data)
					{
						$userEmail = $userCSV[$i][0];
						$userFirstName = $userCSV[$i][1];
						$userLastName = $userCSV[$i][2];

						$password = randomString();	

						createAdminAccount($userEmail, $userFirstName, $userLastName, $password, $mysqli);
		
						// Add email and password to output csv
						fputcsv($fp, array($userEmail, $password));
					}
				}
				else
				{
   					$_SESSION['fail'] = 'Account Creation Failed, ';
			   		header('Location: ../../pages/createBulkUser');
				}
			}
    	}
	}
}

function createStudentAccount($userEmail, $userFirstName, $userLastName, $password, $studentGradeLevel, $studentBirthdate, $studentGender, $mysqli)
{
	$modClassList = 0; 
	$viewAllGrades = 0;
	$isAdmin = 0;
	$isSchoolAdmin = 0; 
	$isTeacher = 0;
	$isParent = 0; 
	$isStudent = 1;
	$studentGPA = NULL;
	$parentAddress = NULL;
	$parentPhone = NULL;

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
				if ($stmt = $mysqli->prepare("INSERT INTO users (userEmail, userPassword, userFirstName, userLastName, modClassList, viewAllGrades, userSalt, isAdmin, isSchoolAdmin, isTeacher, isParent, isStudent, studentGPA, studentGradeLevel, parentAddress, parentPhone, studentBirthdate, studentGender) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))
				{
    				$stmt->bind_param('ssssiisiiiiidissss', $userEmail, $hashedPassword, $userFirstName, $userLastName, $modClassList, $viewAllGrades, $randomSalt, $isAdmin, $isSchoolAdmin, $isTeacher, $isParent, $isStudent, $studentGPA, $studentGradeLevel, $parentAddress, $parentPhone, $studentBirthdate, $studentGender); 
	    			if($stmt->execute())    // Execute the prepared query.
					{
						$_SESSION['success'] = 'User Accounts Created';
					}
					else
					{
    					$_SESSION['fail'] = 'Account Creation Failed, data could not be inserted into the database';
					}
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

function createParentAccount($userEmail, $userFirstName, $userLastName, $password, $parentAddress, $parentPhone, $mysqli)
{
	$modClassList = 0; 
	$viewAllGrades = 0;
	$isAdmin = 0;
	$isSchoolAdmin = 0; 
	$isTeacher = 0;
	$isParent = 1; 
	$isStudent = 0;
	$studentGPA = NULL;

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
				if ($stmt = $mysqli->prepare("INSERT INTO users (userEmail, userPassword, userFirstName, userLastName, modClassList, viewAllGrades, userSalt, isAdmin, isSchoolAdmin, isTeacher, isParent, isStudent, studentGPA, studentGradeLevel, parentAddress, parentPhone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))
				{
    				$stmt->bind_param('ssssiisiiiiidiss', $userEmail, $hashedPassword, $userFirstName, $userLastName, $modClassList, $viewAllGrades, $randomSalt, $isAdmin, $isSchoolAdmin, $isTeacher, $isParent, $isStudent, $studentGPA, $studentGradeLevel, $parentAddress, $parentPhone); 
	    			if($stmt->execute())    // Execute the prepared query.
					{
						$_SESSION['success'] = 'User Accounts Created';
					}
					else
					{
    					$_SESSION['fail'] = 'Account Creation Failed, data could not be inserted into the database';
					}
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

function createTeacherAccount($userEmail, $userFirstName, $userLastName, $password, $modClassList, $viewAllGrades, $mysqli)
{
	$isAdmin = 0;
	$isSchoolAdmin = 0; 
	$isTeacher = 1;
	$isParent = 0; 
	$isStudent = 0;
	$studentGPA = NULL;
	$parentAddress = NULL;
	$parentPhone = NULL;

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
				if ($stmt = $mysqli->prepare("INSERT INTO users (userEmail, userPassword, userFirstName, userLastName, modClassList, viewAllGrades, userSalt, isAdmin, isSchoolAdmin, isTeacher, isParent, isStudent, studentGPA, studentGradeLevel, parentAddress, parentPhone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))
				{
    				$stmt->bind_param('ssssiisiiiiidiss', $userEmail, $hashedPassword, $userFirstName, $userLastName, $modClassList, $viewAllGrades, $randomSalt, $isAdmin, $isSchoolAdmin, $isTeacher, $isParent, $isStudent, $studentGPA, $studentGradeLevel, $parentAddress, $parentPhone); 
	    			if($stmt->execute())    // Execute the prepared query.
					{
						$_SESSION['success'] = 'User Accounts Created';
					}
					else
					{
    					$_SESSION['fail'] = 'Account Creation Failed, data could not be inserted into the database';
					}
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

function createSchoolAdminAccount($userEmail, $userFirstName, $userLastName, $password, $mysqli)
{
	$modClassList = 1; 
	$viewAllGrades = 1;
	$isAdmin = 0;
	$isSchoolAdmin = 1; 
	$isTeacher = 0;
	$isParent = 0; 
	$isStudent = 0;
	$studentGPA = NULL;
	$parentAddress = NULL;
	$parentPhone = NULL;

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
				if ($stmt = $mysqli->prepare("INSERT INTO users (userEmail, userPassword, userFirstName, userLastName, modClassList, viewAllGrades, userSalt, isAdmin, isSchoolAdmin, isTeacher, isParent, isStudent, studentGPA, studentGradeLevel, parentAddress, parentPhone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))
				{
    				$stmt->bind_param('ssssiisiiiiidiss', $userEmail, $hashedPassword, $userFirstName, $userLastName, $modClassList, $viewAllGrades, $randomSalt, $isAdmin, $isSchoolAdmin, $isTeacher, $isParent, $isStudent, $studentGPA, $studentGradeLevel, $parentAddress, $parentPhone); 
	    			if($stmt->execute())    // Execute the prepared query.
					{
						$_SESSION['success'] = 'User Accounts Created';
					}
					else
					{
    					$_SESSION['fail'] = 'Account Creation Failed, data could not be inserted into the database';
					}
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

function createAdminAccount($userEmail, $userFirstName, $userLastName, $password, $mysqli)
{
	$modClassList = 1; 
	$viewAllGrades = 1;
	$isAdmin = 1;
	$isSchoolAdmin = 0; 
	$isTeacher = 0;
	$isParent = 0; 
	$isStudent = 0;
	$studentGPA = NULL;
	$parentAddress = NULL;
	$parentPhone = NULL;

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
				if ($stmt = $mysqli->prepare("INSERT INTO users (userEmail, userPassword, userFirstName, userLastName, modClassList, viewAllGrades, userSalt, isAdmin, isSchoolAdmin, isTeacher, isParent, isStudent, studentGPA, studentGradeLevel, parentAddress, parentPhone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))
				{
    				$stmt->bind_param('ssssiisiiiiidiss', $userEmail, $hashedPassword, $userFirstName, $userLastName, $modClassList, $viewAllGrades, $randomSalt, $isAdmin, $isSchoolAdmin, $isTeacher, $isParent, $isStudent, $studentGPA, $studentGradeLevel, $parentAddress, $parentPhone); 
	    			if($stmt->execute())    // Execute the prepared query.
					{
						$_SESSION['success'] = 'User Accounts Created';
					}
					else
					{
    					$_SESSION['fail'] = 'Account Creation Failed, data could not be inserted into the database';
					}
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
