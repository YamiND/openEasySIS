<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	assignTeacherClass($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Teacher could not be added, incorrect permissions';
   	header('Location: ../../pages/assignTeacher');
}

function assignTeacherClass($mysqli)
{
	if (isset($_POST['teacherID'], $_POST['classID']) && !empty($_POST['teacherID']) && !empty($_POST['classID'])) 
	{
      $teacherID = $_POST['teacherID'];
    	$classID = $_POST['classID'];

	    if ($stmt = $mysqli->prepare("UPDATE classes SET classTeacherID = ? WHERE classID = ?"))
			{
	    		$stmt->bind_param('ii', $teacherID, $classID); 
		    	if ($stmt->execute())    // Execute the prepared query
				{
					$_SESSION['success'] = "Teacher assigned to Class";
	   	   			header('Location: ../../pages/assignTeacher');
				}
				else
				{
	    			$_SESSION['fail'] = 'Teacher could not be assigned to Class';
	   	   			header('Location: ../../pages/assignTeacher');
				}
			}
			else
			{
	    		// The correct POST variables were not sent to this page.
	    		$_SESSION['fail'] = 'Teacher could not be assigned to Class';
	   	   		header('Location: ../../pages/assignTeacher');
			}
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Teacher could not be assigned to Class, data not sent';
   	   	header('Location: ../../pages/assignTeacher');
	}
}

?>

