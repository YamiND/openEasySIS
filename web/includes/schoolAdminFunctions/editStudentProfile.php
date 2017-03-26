<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isSchoolAdmin($mysqli)))
{
	editUserAccount($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Account Edit Failed, invalid permissions';
   	header('Location: ../../pages/editStudentProfile');

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

		if (isset($_POST['studentGradeLevel']) && !empty($_POST['studentGradeLevel']))
		{
			$studentGradeLevel = $_POST['studentGradeLevel'];
		}
		else
		{
   			$_SESSION['fail'] = 'Account Edit Failed, student fields must be filled out';
			header('Location: ../../pages/editStudentProfile');

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
		   				header('Location: ../../pages/editStudentProfile');
					}
				}
			}
		}

		if ($stmt = $mysqli->prepare("UPDATE users SET userEmail = ?, userFirstName = ?, userLastName = ?, studentGradeLevel = ? WHERE userID = ?"))
		{
			$stmt->bind_param('sssii', $userEmail, $userFirstName, $userLastName, $studentGradeLevel, $userID); 
			if ($stmt->execute())    // Execute the prepared query.
			{
				$_SESSION['success'] = "Student Account Edit Successful";
				header('Location: ../../pages/editStudentProfile');
			}
			else
			{
				$_SESSION['fail'] = 'Student Account Edit Failed, data could not be updated in database';
				header('Location: ../../pages/editStudentProfile');
			}
		}
	}
	else
	{
   		$_SESSION['fail'] = 'Student Account Edit Failed, data not sent';
		header('Location: ../../pages/editStudentProfile');

	}
}
?>
