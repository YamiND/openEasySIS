<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 1))
{
	assignStudentClass($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Student could not be added';
   	header('Location: ../../pages/assignStudent');

	return;
}

function assignStudentClass($mysqli)
{
	if (isset($_POST['studentID'], $_POST['classID'])) 
	{
      $studentID = $_POST['studentID'];
    	$classID = $_POST['classID'];

    if ($stmt = $mysqli->prepare("INSERT INTO studentClassIDs (studentID, classID) VALUES (?, ?)"))
		{
    		$stmt->bind_param('ii', $studentID, $classID); 
	    	$stmt->execute();    // Execute the prepared query

			$_SESSION['success'] = "Student added to Class";
   	   		header('Location: ../../pages/assignStudent');
		}
		else
		{
    		// The correct POST variables were not sent to this page.
    		$_SESSION['fail'] = 'Student could not be added to Class';
   	   		header('Location: ../../pages/assignStudent');
		}
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['invalidAdd'] = 'Student could not be added to Class';
   	   	header('Location: ../../pages/assignStudent');
	}
}

?>

