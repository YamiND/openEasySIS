<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 3))
{
	modifyMaterialType($mysqli);
}
else
{
   	$_SESSION['invalidModify'] = 'Assignment Type could not be modified';
   	header('Location: ../../pages/modifyMaterialType');

	return;
}

function modifyMaterialType($mysqli)
{
	if (isset($_POST['materialName'], $_POST['materialWeight'], $_POST['materialTypeID'])) 
  {
      $materialTypeID = $_POST['materialTypeID'];
      $materialName = $_POST['materialName'];
      $materialWeight = $_POST['materialWeight'];
      
    	if ($stmt = $mysqli->prepare("UPDATE materialType SET materialName = ?, materialWeight = ? WHERE materialTypeID = ?"))
		  {
    		$stmt->bind_param('sii', $materialName, $materialWeight, $materialTypeID); 

        $stmt->execute();    // Execute the prepared query.
        
        $_SESSION['successModify'] = "Assignment Type Modified";
   	    header('Location: ../../pages/modifyMaterialType');
		  }
		  else
		  {
    		// The correct POST variables were not sent to this page.
    		$_SESSION['invalidModify'] = 'Assignment Type could not be modified';
   	   		header('Location: ../../pages/modifyMaterialType');
		  }
  }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['invalidModify'] = 'Assignment Type could not be modified';
   	   	header('Location: ../../pages/modifyMaterialType');
	}
}

?>

