<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli) || isSchoolAdmin($mysqli)))
{
	editUserAccount($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Account Creation Failed, invalid permissions';
   	header('Location: ../../pages/createUser');

	return;
}

function editUserAccount($mysqli)
{
	if (isset($_POST['userID'], $_POST['userEmail'], $_POST['userFirstName'], $_POST['userLastName']) && !empty($_POST['userEmail']) && !empty($_POST['userFirstName']) && !empty($_POST['userLastName']) && !empty($_POST['userID']))
	{
		$userID = $_POST['userID'];
		$userEmail = $_POST['userEmail'];
		$userFirstName = $_POST['userFirstName'];
		$userLastName = $_POST['userLastName'];
		$studentGPA = NULL; // This will probably never be set at user creation
		$studentGradeLevel = NULL; // Further down this should be changed if the data is sent
		$parentAddress = NULL; // See above 
		$parentPhone = NULL; // Ditto

		if (isset($_POST['modClassList']))
		{	
			if ($_POST['modClassList'] == "1")
			{
				$modClassList = "1";
			}
			else
			{
				$modClassList = "0";
			}
		}
		else
		{
			$modClassList = "0";
		}

		if (isset($_POST['viewAllGrades']))
		{
			if ($_POST['viewAllGrades'] == "1")
			{
				$viewAllGrades = "1";
			}
			else
			{
				$viewAllGrades = "0";
			}
		}
		else
		{
			$viewAllGrades = "0";
		}

		// Do our checks to make sure one of the checkboxes is checked
		if (isset($_POST['userIsAdmin']) || isset($_POST['userIsSchoolAdmin']) || isset($_POST['userIsTeacher']) || isset($_POST['userIsParent']) || isset($_POST['userIsStudent']) || isset($_POST['userIsPrincipal']))
		{
			// Make sure that student is the only box checked if they're a student
			if (isset($_POST['userIsStudent']) && (isset($_POST['userIsAdmin']) || isset($_POST['userIsSchoolAdmin']) || isset($_POST['userIsTeacher']) || isset($_POST['userIsParent']) || isset($_POST['userIsPrincipal'])))
			{
   				$_SESSION['fail'] = 'Account Edit Failed, Can not be a student and other role';
		   		header('Location: ../../pages/editProfile');

				return;
			}
			// If they are a student, make sure nothing else is set
			else if (isset($_POST['userIsStudent']) && (!isset($_POST['userIsAdmin'], $_POST['userIsSchoolAdmin'], $_POST['userIsTeacher'], $_POST['userIsParent'], $_POST['userIsPrincipal'])))
			{
				$isStudent = "1";	
				
				if (isset($_POST['studentGradeLevel']) && !empty($_POST['studentGradeLevel']))
				{
					$studentGradeLevel = $_POST['studentGradeLevel'];
				}
				else
				{
   					$_SESSION['fail'] = 'Account Edit Failed, student fields must be filled out';
					header('Location: ../../pages/editUser');

					return;
				}
			}
			// If they're not a student, let the checkbox checking commence
			else
			{
				$isStudent = "0";

				if (isset($_POST['userIsAdmin']) && !empty($_POST['userIsAdmin']))
				{
					$isAdmin = $_POST['userIsAdmin'];
					$modClassList = "1";
					$viewAllGrades = "1";
				}
				else
				{
					$isAdmin = "0";
				}

				if (isset($_POST['userIsPrincipal']) && !empty($_POST['userIsPrincipal']))
				{
					// We need to clear out the principal in the database 

					$isPrincipal = $_POST['userIsPrincipal'];

					if ($stmt = $mysqli->prepare("SELECT userID FROM users WHERE isPrincipal"))
					{
						if ($stmt->execute())
						{
							$stmt->bind_result($dbUserID);
							$stmt->store_result();

							while ($stmt->fetch())
							{
								if ($stmt2 = $mysqli->prepare("UPDATE users SET isPrincipal = 0 WHERE userID = ?"))
								{
									$stmt2->bind_param('i', $dbUserID);

									$stmt2->execute();
								}
							}
						}
					}
				}
				else
				{
					$isPrincipal = "0";
				}

				if (isset($_POST['userIsSchoolAdmin']) && !empty($_POST['userIsSchoolAdmin']))
				{
					$isSchoolAdmin = $_POST['userIsSchoolAdmin'];
					$modClassList = "1";
					$viewAllGrades = "1";
				}
				else
				{
					$isSchoolAdmin = "0";
				}

				if (isset($_POST['userIsTeacher']) && !empty($_POST['userIsTeacher']))
				{
					$isTeacher = $_POST['userIsTeacher'];
				}
				else
				{
					$isTeacher = "0";
				}

				if (isset($_POST['userIsParent']) && !empty($_POST['userIsParent']))
				{
					$isParent = $_POST['userIsParent'];

					if (isset($_POST['parentAddress'], $_POST['parentPhone']) && !empty($_POST['parentAddress']) && !empty($_POST['parentPhone']))
					{
						$parentAddress = $_POST['parentAddress'];
						$parentPhone = $_POST['parentPhone'];
					}
					else
					{
   						$_SESSION['fail'] = 'Account Edit Failed, parent fields must be filled out';
					   	header('Location: ../../pages/editProfile');

						return;
					}
				}
				else
				{
					$isParent = "0";
				}
			}
		}
		else
		{
   			$_SESSION['fail'] = 'Account Edit Failed, At least one role must be selected';
		   	header('Location: ../../pages/editProfile');

			return;
		}


		if ($stmt = $mysqli->prepare("SELECT userEmail FROM users WHERE userID = ?"))
		{
			$stmt->bind_param('i', $userID);

			if ($stmt->execute())
			{
				$stmt->bind_result($dbUserEmail);
				$stmt->store_result();

				$stmt->fetch();
			}
		}

		if ($dbUserEmail != $userEmail)
		{
			if ($stmt = $mysqli->prepare("SELECT userID FROM users WHERE userEmail = ?"))
			{
				$stmt->bind_param('i', $userEmail);

				if ($stmt->execute())
				{
					$stmt->bind_result($dbUserID);
					$stmt->store_result();

					$stmt->fetch();

					if ($userID != $dbUserID)
					{
			   			$_SESSION['fail'] = 'Account Edit Failed, another user has this email';
		   				header('Location: ../../pages/editProfile');
					}
				}
			}
		}

		if ($stmt = $mysqli->prepare("UPDATE users SET userEmail = ?, userFirstName = ?, userLastName = ?, modClassList = ?, viewAllGrades = ?, isParent = ?, isStudent = ?, isTeacher = ?, isSchoolAdmin = ?, isAdmin = ?, studentGPA = ?, studentGradeLevel = ?, parentAddress = ?, parentPhone = ?, isPrincipal = ? WHERE userID = ?"))
		{
			$stmt->bind_param('sssiisssssdisssi', $userEmail, $userFirstName, $userLastName, $modClassList, $viewAllGrades, $isParent, $isStudent, $isTeacher, $isSchoolAdmin, $isAdmin, $studentGPA, $studentGradeLevel, $parentAddress, $parentPhone, $isPrincipal, $userID); 
			if ($stmt->execute())    // Execute the prepared query.
			{
				$_SESSION['success'] = "Account Edit Successful";
				header('Location: ../../pages/editProfile');
			}
			else
			{
				$_SESSION['fail'] = 'Account Edit Failed, data could not be updated in database';
				header('Location: ../../pages/editProfile');
			}
		}
	}
	else
	{
   		$_SESSION['fail'] = 'Account Edit Failed, data not sent';
		header('Location: ../../pages/editUser');

	}
}
?>
