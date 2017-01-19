<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 3))
{
	addMaterialType($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Assignment Type could not be added';
   	header('Location: ../../pages/addMaterialType');

	return;
}

function addMaterialType($mysqli)
{
	if ((isset($_POST['classID'], $_POST['materialName'], $_POST['materialWeight'])) && !empty($_POST['classID']) && !empty($_POST['materialName']) && !empty($_POST['materialWeight'])) 
  	{
    	$classID = $_POST['classID'];
      	$materialName = $_POST['materialName'];
		$materialWeight = $_POST['materialWeight'];

		$combinedWeight = getMaterialTypeWeight($classID, $mysqli);

		if ((($combinedWeight + $materialWeight) > 100) || ($materialWeight <= 0))
		{
    		$_SESSION['fail'] = 'Assignment Type could not be added, weight can not exceed 100 or be 0 or negative';
   	   		header('Location: ../../pages/addMaterialType');
		}
		else
		{

    		if ($stmt = $mysqli->prepare("INSERT INTO materialType (materialName, classID, materialWeight) VALUES (?, ?, ?)"))
		  	{
    			$stmt->bind_param('sii', $materialName, $classID, $materialWeight); 

		        $stmt->execute();    // Execute the prepared query.
        
       			 $_SESSION['success'] = "Assignment Type Added";
		   	    header('Location: ../../pages/addMaterialType');
			}
			else
		  	{
    			// The correct POST variables were not sent to this page.
    			$_SESSION['fail'] = 'Assignment Type could not be added';
   	   			header('Location: ../../pages/addMaterialType');
		  	}
		}
  	}
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Assignment Type could not be added, data not sent';
   	   	header('Location: ../../pages/addMaterialType');
	}
}

function getMaterialTypeWeight($classID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT materialWeight FROM materialType WHERE classID = ?"))
	{
		$stmt->bind_param('i', $classID);
		$stmt->bind_result($materialWeight);

		$stmt->execute();

		$stmt->store_result();

		while ($stmt->fetch())
		{
			$combinedWeight += $materialWeight;
		}

		return $combinedWeight;
	}
}

?>

