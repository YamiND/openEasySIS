<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

//TODO Test this
if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	deleteUser($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Account Deletion Failed, invalid permissions';
   	header('Location: ../../pages/deleteUser');
}

function deleteUser($mysqli)
{
	$yourID = $_SESSION['userID'];

	if (isset($_POST['userID']) && !empty($_POST['userID']))
	{
		$userID = $_POST['userID'];

		if ($yourID == $userID)
		{
   			$_SESSION['fail'] = 'Account Deletion Failed, can\'t delete own account';
		   	header('Location: ../../pages/deleteUser');
		}
		else
		{
			if ($stmt = $mysqli->prepare("SELECT isStudent, isTeacher FROM users WHERE userID = ?"))
			{
				$stmt->bind_param('i', $userID);
				
				if ($stmt->execute())
				{
					$stmt->bind_result($isStudent, $isTeacher);
					$stmt->store_result();
					
					$stmt->fetch();

					if ($isTeacher)
					{
						if ($stmt2 = $mysqli->prepare("SELECT classID FROM classes WHERE classTeacherID = ?"))
						{
							$stmt2->bind_param('i', $userID);

							if ($stmt2->execute())
							{
								$stmt2->bind_result($classID);
								$stmt2->store_result();	
		
								if ($stmt->num_rows > 0)
								{
   									$_SESSION['fail'] = 'Account Deletion Failed, teacher has class assigned to them';
								   	header('Location: ../../pages/deleteUser');
								}
							}
						}
					}

					if ($isStudent)
					{
						if ($stmt2 = $mysqli->prepare("DELETE FROM studentClassIDs WHERE studentID = ?"))
						{
							$stmt2->bind_param('i', $userID);
							$stmt2->execute();
						}

						if ($stmt2 = $mysqli->prepare("DELETE FROM studentParentIDs WHERE studentID = ?"))
						{
							$stmt2->bind_param('i', $userID);
							$stmt2->execute();
						}

						if ($stmt2 = $mysqli->prepare("DELETE FROM grades WHERE gradeStudentID = ?"))
						{
							$stmt2->bind_param('i', $userID);
							$stmt2->execute();
						}
					}

					if ($stmt2 = $mysqli->prepare("DELETE FROM users WHERE userID = ?"))
					{
						$stmt2->bind_param('i', $userID);
						$stmt2->execute();

   						$_SESSION['success'] = 'Account Deletion Success';
					   	header('Location: ../../pages/deleteUser');
					}
				}
			}
		}
	}
}

?>
