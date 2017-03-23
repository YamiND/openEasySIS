<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	unAssignStudentClass($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Student could not be added, incorrect permissions';
   	header('Location: ../../pages/assignStudent');

	return;
}

function unAssignStudentClass($mysqli)
{
	if (isset($_POST['studentID'], $_POST['classID']) && !empty($_POST['studentID']) && !empty($_POST['classID'])) 
	{
	    $studentID = $_POST['studentID'];
		$classID = $_POST['classID'];
		
		if ($stmt = $mysqli->prepare("DELETE FROM studentClassIDs WHERE studentID = ? AND classID = ?"))
		{
			$stmt->bind_param('ii', $studentID, $classID);

			if ($stmt->execute())
			{
				if ($stmt2 = $mysqli->prepare("DELETE FROM grades WHERE gradeStudentID = ? AND gradeClassID = ?"))
				{
					$stmt2->bind_param('ii', $studentID, $classID);

					if ($stmt2->execute())
					{
    					$_SESSION['success'] = 'Student removed from Class';
				   	   	header('Location: ../../pages/unAssignStudent');
					}
					else
					{
    					$_SESSION['fail'] = 'Student removed from Class; had no grades';
				   	   	header('Location: ../../pages/unAssignStudent');
					}
				}
				else
				{
    				$_SESSION['fail'] = 'Student removed from Class; grade deletion failed';
				   	header('Location: ../../pages/unAssignStudent');
				}
			}
			else
			{
    			$_SESSION['fail'] = 'Student could not be removed from Class; deletion failed';
				header('Location: ../../pages/unAssignStudent');
			}
		}
		else
		{
    		$_SESSION['fail'] = 'Student could not be removed from Class; deletion query failed';
			header('Location: ../../pages/unAssignStudent');
		}
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Student could not be removed from Class, data not sent';
   	   	header('Location: ../../pages/unAssignStudent');
	}
}

?>

