<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 3))
{
	addAssignment($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Assignment could not be added';
   	header('Location: ../../pages/addAssignment');

	return;
}

function addAssignment($mysqli)
{
	if ((isset($_POST['materialName'], $_POST['materialPointsPossible'], $_POST['materialDueDate'], $_POST['materialTypeID'], $_POST['classID'])) && !empty($_POST['materialName']) && !empty($_POST['materialPointsPossible']) && !empty($_POST['materialDueDate']) && !empty($_POST['materialTypeID']) && !empty($_POST['classID']))
  {
      $materialName = $_POST['materialName'];
		  $materialPointsPossible = $_POST['materialPointsPossible'];
    	$materialDueDate = $_POST['materialDueDate'];
		  $materialTypeID = $_POST['materialTypeID'];
      $materialClassID = $_POST['classID'];


		if ($materialPointsPossible <= 0)
		{
    		$_SESSION['fail'] = 'Assignment could not be added, Points Possible can not be 0 or less';
	   	   	header('Location: ../../pages/addAssignment');

		}
		else if ($materialDueDate < date('Y-m-d'))
		{
    		$_SESSION['fail'] = 'Assignment could not be added, Date can not be less than today';
	   	   	header('Location: ../../pages/addAssignment');

		}
		else
		{
    		if ($stmt = $mysqli->prepare("INSERT INTO materials (materialClassID, materialName, materialPointsPossible, materialDueDate, materialTypeID) VALUES (?, ?, ?, ?, ?)"))
		  	{
    			$stmt->bind_param('isisi', $materialClassID, $materialName, $materialPointsPossible, $materialDueDate, $materialTypeID); 

		        $stmt->execute();    // Execute the prepared query.
        
       			 $_SESSION['success'] = "Assignment Added";
		   	    header('Location: ../../pages/addAssignment');
		  }
		  else
		  {
    		// The correct POST variables were not sent to this page.
    		$_SESSION['fail'] = 'Assignment could not be added';
   	   		header('Location: ../../pages/addAssignment');
		  }
		}
  }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Assignment could not be added, data not sent or incomplete';
   	   	header('Location: ../../pages/addAssignment');
	}
}

?>

