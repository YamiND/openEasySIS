<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 3))
{
	modifyAssignment($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Assignment could not be modified';
   	header('Location: ../../pages/modifyAssignment');

	return;
}

function modifyAssignment($mysqli)
{
	if (isset($_POST['materialName'], $_POST['materialPointsPossible'], $_POST['materialDueDate'], $_POST['materialTypeID'], $_POST['materialID'])) 
  {
      $materialID = $_POST['materialID'];
      $materialName = $_POST['materialName'];
		  $materialPointsPossible = $_POST['materialPointsPossible'];
    	$materialDueDate = $_POST['materialDueDate'];
		  $materialTypeID = $_POST['materialTypeID'];
      
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
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Assignment could not be modified';
   	   	header('Location: ../../pages/modifiedAssignment');
	}
}

?>

