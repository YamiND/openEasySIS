<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isTeacher($mysqli)))
{
	modifyAssignment($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Assignment could not be modified, invalid permissions';
   	header('Location: ../../pages/modifyAssignment');
}

function modifyAssignment($mysqli)
{
	if ((isset($_POST['materialName'], $_POST['materialPointsPossible'], $_POST['materialDueDate'], $_POST['materialTypeID'])) && !empty($_POST['materialName']) && !empty($_POST['materialPointsPossible']) && !empty($_POST['materialDueDate']) && !empty($_POST['materialTypeID']))
  {
      $materialID = $_POST['materialID'];
      $materialName = $_POST['materialName'];
		  $materialPointsPossible = $_POST['materialPointsPossible'];
    	$materialDueDate = $_POST['materialDueDate'];
		  $materialTypeID = $_POST['materialTypeID'];
    
		if ($materialPointsPossible <= 0)
		{
    		$_SESSION['fail'] = 'Assignment could not be modified, Points Possible can\'t be 0 or less';
   	   		header('Location: ../../pages/modifyAssignment');

		} 
		else if ($materialDueDate < date('Y-m-d'))
		{
    		$_SESSION['fail'] = 'Assignment could not be modified, date can\'t be less than current date';
   	   		header('Location: ../../pages/modifyAssignment');
		}
		else 
		{
 
    		if ($stmt = $mysqli->prepare("UPDATE materials SET materialName = ?, materialPointsPossible = ?, materialDueDate = ?, materialTypeID = ? WHERE materialID = ?"))
		  	{
    			$stmt->bind_param('sisii', $materialName, $materialPointsPossible, $materialDueDate, $materialTypeID, $materialID); 

		        $stmt->execute();    // Execute the prepared query.
        
       			$_SESSION['success'] = "Assignment Modified";
		   	    header('Location: ../../pages/modifyAssignment');
			}
		  	else
		  	{
    			// The correct POST variables were not sent to this page.
    			$_SESSION['fail'] = 'Assignment could not be modified';
   	   			header('Location: ../../pages/modifyAssignment');
		  	}
		}
  }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Assignment could not be modified, data not sent';
   	   	header('Location: ../../pages/modifyAssignment');
	}
}

?>

