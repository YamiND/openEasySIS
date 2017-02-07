<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isTeacher($mysqli)))
{
	modifyMaterialType($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Assignment Type could not be modified';
   	header('Location: ../../pages/modifyMaterialType');

	return;
}

function modifyMaterialType($mysqli)
{
	if ((isset($_POST['classID'], $_POST['materialName'], $_POST['materialWeight'], $_POST['materialTypeID'])) && !empty($_POST['materialName']) && !empty($_POST['materialWeight']) && !empty($_POST['materialTypeID']) && !empty($_POST['classID']))
  	{
		$classID = $_POST['classID'];
		$materialTypeID = $_POST['materialTypeID'];
      	$materialName = $_POST['materialName'];
      	$materialWeight = $_POST['materialWeight'];

	  	$combinedWeight = getMaterialTypeWeight($classID, $materialTypeID, $mysqli);

		if ((($combinedWeight + $materialWeight) > 100) || ($materialWeight <= 0))
        {
            $_SESSION['fail'] = 'Assignment Type could not be added, weight can not exceed 100 or be 0 or negative';
            header('Location: ../../pages/modifyMaterialType');
        }
        else
        {
			if ($stmt = $mysqli->prepare("UPDATE materialType SET materialName = ?, materialWeight = ? WHERE materialTypeID = ?"))
			{
   		 		$stmt->bind_param('sii', $materialName, $materialWeight, $materialTypeID); 

		        $stmt->execute();    // Execute the prepared query.
        
       			 $_SESSION['success'] = "Assignment Type Modified";
		   	    header('Location: ../../pages/modifyMaterialType');
		 	}
			else
		  	{
    			// The correct POST variables were not sent to this page.
	    		$_SESSION['fail'] = 'Assignment Type could not be modified';
   		   		header('Location: ../../pages/modifyMaterialType');
			}
		}
  	}
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Assignment Type could not be modified';
   	   	header('Location: ../../pages/modifyMaterialType');
	}
}

function getMaterialTypeWeight($classID, $materialTypeID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT materialTypeID, materialWeight FROM materialType WHERE classID = ?"))
    {   
        $stmt->bind_param('i', $classID);
        $stmt->bind_result($dbMaterialTypeID, $materialWeight);

        $stmt->execute();

        $stmt->store_result();

        while ($stmt->fetch())
        {  
			if ($materialTypeID != $dbMaterialTypeID)
			{ 
            	$combinedWeight += $materialWeight;
			}
        }   

        return $combinedWeight;
    }   
}

?>

