<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start();

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	modifyClass($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Class could not be modified, not correct permissions';
   	header('Location: ../../pages/modifyClass');

	return;
}

function modifyClass($mysqli)
{
	if (isset($_POST['classID'], $_POST['className'], $_POST['classTeacherID'], $_POST['classGradeLevel'])) 
	{
		$classStartTime = NULL;
		$classEndTime = NULL;
		
		$classID = $_POST['classID'];
		$className = $_POST['className'];
		$classTeacherID = $_POST['classTeacherID'];
    	$classGradeLevel = $_POST['classGradeLevel'];

		if (isset($_POST['classStartTime'], $_POST['classEndTime']))
		{
			$classStartTime = $_POST['classStartTime'];
			$classEndTime = $_POST['classEndTime'];	
		}

        if ($stmt = $mysqli->prepare("SELECT classID, className, classGrade FROM classes"))
        {
            $stmt->bind_result($dbClassID, $dbClassName, $dbClassGrade);
            $stmt->execute();

            $stmt->store_result();

            while ($stmt->fetch())
            {
                if (($className == $dbClassName) && ($classGradeLevel == $dbClassGrade) && ($classID != $dbClassID))
                {
                    $_SESSION['fail'] = 'Class can not have same name';
                    header('Location: ../../pages/modifyClass');
                    
                    return;
                }
            }

    	    if ($stmt = $mysqli->prepare("UPDATE classes SET className = ?, classGrade = ?, classTeacherID = ?, classStartTime = ?, classEndTime = ? WHERE classID = ?"))
		    {
    		    $stmt->bind_param('siissi', $className, $classGradeLevel, $classTeacherID, $classStartTime, $classEndTime, $classID); 

	    	    if ($stmt->execute())    // Execute the prepared query.
				{
			    	$_SESSION['success'] = "Class Modified Successfully";
	   	   		    header('Location: ../../pages/modifyClass');
				}
				else
				{
			    	$_SESSION['fail'] = "Class Modification failed, database not updated";
	   	   		    header('Location: ../../pages/modifyClass');
				}
		    }
        }
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Class could not be updated, variables not sent';
   	   	header('Location: ../../pages/modifyClass');
	}
}

?>
