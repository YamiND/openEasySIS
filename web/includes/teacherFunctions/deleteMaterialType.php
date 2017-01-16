<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 3))
{
	deleteMaterialType($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Assignment Type could not be deleted';
   	header('Location: ../../pages/deleteMaterialType');

	return;
}

function deleteMaterialType($mysqli)
{
	if (isset($_POST['materialTypeID']) && !empty($_POST['materialTypeID']) )
  {
      $materialTypeID = $_POST['materialTypeID'];
      
    	if ($stmt = $mysqli->prepare("DELETE FROM materialType WHERE materialTypeID = ?"))
		  {
    		$stmt->bind_param('i', $materialTypeID); 

	        $stmt->execute();    // Execute the prepared query.
   			     
			if ($stmt->affected_rows > 0)
			{
       			$_SESSION['success'] = "Assignment Type Deleted";
			}
			else
			{
    			$_SESSION['fail'] = 'Could not delete, an Assignment has this type assigned';
			}
	   	    header('Location: ../../pages/deleteMaterialType');
		  }
		  else
		  {
    		// The correct POST variables were not sent to this page.
    		$_SESSION['fail'] = 'Could not delete, an Assignment has this type assigned';
   	   		header('Location: ../../pages/deleteMaterialType');
		  }
  }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Assignment Type could not be deleted, data not sent';
   	   	header('Location: ../../pages/deleteMaterialType');
	}
}

?>

