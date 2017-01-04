<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 1))
{
	modifyClass($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Class could not be updated, not correct permissions';
   	header('Location: ../../pages/modifyClass');

	return;
}

function modifyClass($mysqli)
{
	if (isset($_POST['classID'], $_POST['className'] $_POST['classTeacherID'], $_POST['classGradeLevel'])) 
	{
		$classID = $_POST['classID'];
		$className = $_POST['className'];
		$classTeacherID = $_POST['classTeacherID'];
    	$classGradeLevel = $_POST['classGradeLevel'];

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

    	    if ($stmt = $mysqli->prepare("UPDATE classes SET className = ?, classGrade = ?, classTeacherID = ? WHERE classID = ?"))
		    {
    		    $stmt->bind_param('siii', $className, $classGradeLevel, $classTeacherID, $classID); 

	    	    $stmt->execute();    // Execute the prepared query.

			    $_SESSION['success'] = "Class Modified Successfully";
   	   		    header('Location: ../../pages/modifyClass');
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
