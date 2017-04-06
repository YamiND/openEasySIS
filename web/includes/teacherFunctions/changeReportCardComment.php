<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isTeacher($mysqli)))
{
	changeReportCardComment($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Report Card Comment could not be changed';
   	header('Location: ../../pages/reportCardComment');

	return;
}

function changeReportCardComment($mysqli)
{
	if (isset($_POST['studentID'], $_POST['classID']) && !empty($_POST['studentID']) && !empty($_POST['classID'])) 
  {
      $studentID = $_POST['studentID'];
      $classID = $_POST['classID'];
	  $reportCardComment = NULL;

		if (isset($_POST['reportCardComment']) && !empty($_POST['reportCardComment']))
		{
			$reportCardComment = $_POST['reportCardComment'];
		}
      
      if (checkReportCardCommentExists($studentID, $classID, $mysqli))
      {
        	if ($stmt = $mysqli->prepare("UPDATE reportCardComment SET reportCardComment = ? WHERE studentID = ? AND classID = ?"))
    		  {
        		$stmt->bind_param('sii', $reportCardComment, $studentID, $classID); 

            if ($stmt->execute())    // Execute the prepared query.
           { 
   	        	 $_SESSION['success'] = "Report Card Comment Changed";
	       	    header('Location: ../../pages/reportCardComment');
			}
			else
			{
        		$_SESSION['fail'] = 'Report Card Comment could not be changed';
       	   		header('Location: ../../pages/reportCardComment');
			}
    		  }
    		  else
    		  {
        		// The correct POST variables were not sent to this page.
        		$_SESSION['fail'] = 'Report Card Comment could not be changed';
       	   		header('Location: ../../pages/reportCardComment');
    		  }
      }
      else
      {
          if ($stmt = $mysqli->prepare("INSERT INTO reportCardComment (studentID, classID, reportCardComment) VALUES (?, ?, ?)"))
          {
            $stmt->bind_param('iis', $studentID, $classID, $reportCardComment);

            if ($stmt->execute())
			{
            	$_SESSION['success'] = "Report Card Comment Changed";
	            header('Location: ../../pages/reportCardComment');
			}
			else
			{
            	// The correct POST variables were not sent to this page.
	            $_SESSION['fail'] = 'Report Card Comment could not be changed, insert failed';
   	           header('Location: ../../pages/reportCardComment');
			}
          }
          else
          {
            // The correct POST variables were not sent to this page.
            $_SESSION['fail'] = 'Report Card Comment could not be changed, insert failed';
              header('Location: ../../pages/reportCardComment');
          }
      }
  }
	else
	{

    	// The correct POST variables were not sent to this page.
    	 $_SESSION['fail'] = 'Report Card Comment could not be changed, data not sent';
   	   	header('Location: ../../pages/reportCardComment');
	}
}

function checkReportCardCommentExists($studentID, $classID, $mysqli)
{
  if ($stmt = $mysqli->prepare("SELECT rccID FROM reportCardComment WHERE studentID = ? AND classID = ?"))
    {
        $stmt->bind_param('ii', $studentID, $classID);
        $stmt->execute();
        $stmt->bind_result($rccID);
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

