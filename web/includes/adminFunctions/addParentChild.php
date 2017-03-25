<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && isAdmin($mysqli))
{
	addParentChild($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Student could not be added, incorrect permissions';
   	header('Location: ../../pages/addParentChild');

	return;
}

function addParentChild($mysqli)
{
	if (isset($_POST['studentID'], $_POST['parentID']) && !empty($_POST['studentID']) && !empty($_POST['parentID'])) 
	{
	    $studentID = $_POST['studentID'];
    	$parentID = $_POST['parentID'];

		if ($stmt = $mysqli->prepare("SELECT studentID FROM studentParentIDs WHERE parentID = ? AND studentID = ?"))
		{
			$stmt->bind_param('ii', $parentID, $studentID);

			if ($stmt->execute())
			{
				$stmt->bind_result($dbStudentID);
				$stmt->store_result();

				$stmt->fetch();

				if ($stmt->num_rows > 0)
				{
    				$_SESSION['fail'] = 'Student could not be added to Parent, parent already has student assigned to them';
   				   	header('Location: ../../pages/addParentChild');
				}
				else
				{
					if ($stmt2 = $mysqli->prepare("INSERT INTO studentParentIDs (studentID, parentID) VALUES (?, ?)"))
					{
						$stmt2->bind_param('ii', $studentID, $parentID);
						
						if ($stmt2->execute())
						{
							$_SESSION['success'] = "Student added to Parent";
  				   			header('Location: ../../pages/addParentChild');
						}
					}
				}
			}
		}
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Student could not be added to Parent, data not sent';
   	   	header('Location: ../../pages/addParentChild');
	}
}

?>
