<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 1))
{
	addClass($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Class could not be added';
   	header('Location: ../../pages/addClass');

	return;
}

function addClass($mysqli)
{
	if (isset($_POST['className'], $_POST['classGradeLevel'], $_POST['classTeacherID'])) 
	{
    	$className = $_POST['className'];
    	$classGradeLevel = $_POST['classGradeLevel'];
		$classTeacherID = $_POST['classTeacherID'];

        if ($stmt = $mysqli->prepare("SELECT className, classGrade FROM classes"))
        {
            $stmt->bind_result($dbClassName, $dbClassGrade);
            $stmt->execute();
            
            $stmt->store_result();

            while ($stmt->fetch())
            {
                if (($className == $dbClassName) && ($classGradeLevel == $dbClassGrade))
                {
   	                $_SESSION['fail'] = 'Class can not have same name';
                   	header('Location: ../../pages/addClass');

                	return;
                }
            }
        }

    	if ($stmt = $mysqli->prepare("INSERT INTO classes (classGrade, className, classTeacherID) VALUES (?, ?, ?)"))
		{
    		$stmt->bind_param('isi', $classGradeLevel, $className, $classTeacherID); 
	    	$stmt->execute();    // Execute the prepared query.
			$_SESSION['success'] = "Class Added";
   	   		header('Location: ../../pages/addClass');
		}
		else
		{
    		// The correct POST variables were not sent to this page.
    		$_SESSION['fail'] = 'Class could not be added';
   	   		header('Location: ../../pages/addClass');
		}
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Class could not be added';
   	   	header('Location: ../../pages/addClass');
	}
}

?>

