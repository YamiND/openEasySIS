<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 3))
{
	deleteAssignment($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Assignment could not be deleted';
   	header('Location: ../../pages/deleteAssignment');

	return;
}

function deleteAssignment($mysqli)
{
	if (isset($_POST['materialID'])) 
  {
      $materialID = $_POST['materialID'];

		// Need to delete grade before can delete assignment
		deleteGradedAssignments($materialID, $mysqli);
      
    	if ($stmt = $mysqli->prepare("DELETE FROM materials WHERE materialID = ?"))
		  {
    		$stmt->bind_param('i', $materialID); 

        $stmt->execute();    // Execute the prepared query.
        
        $_SESSION['success'] = "Assignment Deleted";
   	    header('Location: ../../pages/deleteAssignment');
		  }
		  else
		  {
    		// The correct POST variables were not sent to this page.
    		$_SESSION['fail'] = 'Assignment could not be deleted, delete failed';
   	   		header('Location: ../../pages/deleteAssignment');
		  }
  }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Assignment could not be deleted, data not sent';
   	   	header('Location: ../../pages/deleteAssignment');
	}
}

function deleteGradedAssignments($materialID, $mysqli)
{
	
	if ($stmt = $mysqli->prepare("DELETE FROM grades WHERE gradeMaterialID = ?"))
	{
    	$stmt->bind_param('i', $materialID); 
        $stmt->execute();    // Execute the prepared query.
        
	}
}

?>

