<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	deleteClass($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Class could not be deleted';
   	header('Location: ../../pages/deleteClass');

	return;
}

function deleteClass($mysqli)
{
	if (isset($_POST['classID'])) 
	{
    	$classID = $_POST['classID'];

    	if ($stmt = $mysqli->prepare("DELETE FROM classes WHERE classID = ?"))
		{
    		$stmt->bind_param('i', $classID); 
	    	$stmt->execute();    // Execute the prepared query.

			$_SESSION['success'] = "Class Deleted";
   	   		header('Location: ../../pages/deleteClass');
		}
		else
		{
    		// The correct POST variables were not sent to this page.
    		$_SESSION['success'] = 'Class could not be deleted';
   	   		header('Location: ../../pages/deleteClass');
		}
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Class could not be deleted, data not sent';
   	   	header('Location: ../../pages/deleteClass');
	}
}

?>

