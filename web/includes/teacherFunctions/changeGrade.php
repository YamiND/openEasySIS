<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isTeacher($mysqli)))
{
	changeGrade($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Grade could not be changed';
   	header('Location: ../../pages/teacherGradebook');

	return;
}

function changeGrade($mysqli)
{
	if (isset($_POST['studentID'], $_POST['classID'], $_POST['materialID'], $_POST['materialPointsScored'])) 
  {
      $studentID = $_POST['studentID'];
      $classID = $_POST['classID'];
      $materialID = $_POST['materialID'];
      $materialPointsScored = $_POST['materialPointsScored'];
      
      if (checkAssignmentExists($studentID, $materialID, $mysqli))
      {
        	if ($stmt = $mysqli->prepare("UPDATE grades SET gradeMaterialPointsScored = ? WHERE gradeStudentID = ? AND gradeClassID = ? AND gradeMaterialID = ?"))
    		  {
        		$stmt->bind_param('iiii', $materialPointsScored, $studentID, $classID, $materialID); 

            $stmt->execute();    // Execute the prepared query.
            
            $_SESSION['success'] = "Grade Changed";
       	    header('Location: ../../pages/teacherGradebook');
    		  }
    		  else
    		  {
        		// The correct POST variables were not sent to this page.
        		$_SESSION['fail'] = 'Grade could not be changed';
       	   		header('Location: ../../pages/teacherGradebook');
    		  }
      }
      else
      {
          if ($stmt = $mysqli->prepare("INSERT INTO grades (gradeStudentID, gradeClassID, gradeMaterialID, gradeMaterialPointsScored) VALUES (?, ?, ?, ?)"))
          {
            $stmt->bind_param('iiii', $studentID, $classID, $materialID, $materialPointsScored);
            $stmt->execute();

            $_SESSION['success'] = "Grade Changed";
            header('Location: ../../pages/teacherGradebook');
          }
          else
          {
            // The correct POST variables were not sent to this page.
            $_SESSION['fail'] = 'Grade could not be changed, insert failed';
              header('Location: ../../pages/teacherGradebook');
          }
      }
  }
	else
	{

    	// The correct POST variables were not sent to this page.
    	 $_SESSION['fail'] = 'Grade could not be changed, data not sent';
   	   	header('Location: ../../pages/teacherGradebook');
	}
}

function checkAssignmentExists($studentID, $materialID, $mysqli)
{
  if ($stmt = $mysqli->prepare("SELECT gradeStudentID FROM grades WHERE gradeStudentID = ? AND gradeMaterialID = ?"))
    {
        $stmt->bind_param('ii', $studentID, $materialID);
        $stmt->execute();
        $stmt->bind_result($dbStudentID);
        $stmt->store_result();

        $stmt->fetch();

        if ($stmt->num_rows >= 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
	else
	{
		return false;
	}
}

?>

