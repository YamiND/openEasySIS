<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && isAdmin($mysqli))
{
	removeParentChild($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Student could not be removed, incorrect permissions';
   	header('Location: ../../pages/removeParentChild');

	return;
}

function removeParentChild($mysqli)
{
	if (isset($_POST['studentID'], $_POST['parentID']) && !empty($_POST['studentID']) && !empty($_POST['parentID'])) 
	{
	    $studentID = $_POST['studentID'];
    	$parentID = $_POST['parentID'];

		if ($stmt2 = $mysqli->prepare("DELETE FROM studentParentIDs WHERE parentID = ? AND studentID = ?"))
		{
			$stmt2->bind_param('ii', $parentID, $studentID);
		
			if ($stmt2->execute())
			{
				$_SESSION['success'] = "Student removed from Parent";
				header('Location: ../../pages/removeParentChild');
			}
			else
			{
				$_SESSION['fail'] = "Student could not removed from Parent, not assigned to parent";
				header('Location: ../../pages/removeParentChild');
			}
		}
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Student could not be removed from Parent, data not sent';
   	   	header('Location: ../../pages/removeParentChild');
	}
}

?>
