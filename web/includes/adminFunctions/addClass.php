<?php
include_once '../dbConnect.php';
include_once '../functions.php';
include_once '../classFunctionsTemplate.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 1))
{
	addClass($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Class could not be added';
   	header('Location: ../../pages/addClass');
}

function addClass($mysqli)
{
	if (isset($_POST['className'], $_POST['classGradeLevel'], $_POST['classTeacherID']) && !empty($_POST['className']) && !empty($_POST['classGradeLevel']) && !empty($_POST['classTeacherID'])) 
	{
    	$className = $_POST['className'];
    	$classGradeLevel = $_POST['classGradeLevel'];
		$classTeacherID = $_POST['classTeacherID'];
		$classYearID = getClassYearID($mysqli);

        if ($stmt = $mysqli->prepare("SELECT className, classGrade FROM classes WHERE schoolYearID = ?"))
        {
			$stmt->bind_param("i", $classYearID);

            $stmt->bind_result($dbClassName, $dbClassGrade);
            $stmt->execute();
            
            $stmt->store_result();

            while ($stmt->fetch())
            {
                if (($className == $dbClassName) && ($classGradeLevel == $dbClassGrade))
                {
					appendLog("classes.txt", "Grade: $classGradeLevel Class: $className could not be added, already exists");
   	                $_SESSION['fail'] = 'Class can not have same name';
                   	header('Location: ../../pages/addClass');

                	return;
                }
            }
        }

    	if ($stmt = $mysqli->prepare("INSERT INTO classes (classGrade, className, classTeacherID, schoolYearID) VALUES (?, ?, ?, ?)"))
		{
    		$stmt->bind_param('isii', $classGradeLevel, $className, $classTeacherID, $classYearID); 
	    	if ($stmt->execute())
			{
				appendLog("classes.txt", "Grade: $classGradeLevel Class: $className added successfully");
				$_SESSION['success'] = "Class Added";
   	   			header('Location: ../../pages/addClass');
			}
			else
			{
				appendLog("classes.txt", "$stmt->error");
			}
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

