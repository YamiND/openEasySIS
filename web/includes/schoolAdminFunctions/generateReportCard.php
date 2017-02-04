<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 2))
{
	generateChoice($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Report Card could not be generated';
   	header('Location: ../../pages/generateReportCard');

	return;
}

function generateChoice($mysqli)
{
	if (isset($_POST['generateChoice']) && !empty($_POST['generateChoice']))
  	{
		$reportCardChoice = $_POST['generateChoice'];

		switch($reportCardChoice)
		{
			case "generateSingle":
				generateSingle($_POST['studentID'], $mysqli);
				break;

			case "generateForGrade":
				generateForGrade($_POST['gradeLevel'], $mysqli);
				break;

			case "generateAll":
				generateAll($mysqli);
				break;

			default:
    			$_SESSION['fail'] = 'Report Card could not be generated, data not sent or incomplete';
   	   			header('Location: ../../pages/generateReportCard');

		}
  	}
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Report Card could not be generated, data not sent or incomplete';
   	   	header('Location: ../../pages/generateReportCard');
	}
}

function generateSingle($studentID, $mysqli)
{
	generateReportCard($studentID, $mysqli);
}

function generateForGrade($gradeLevel, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT studentID FROM studentProfile WHERE studentGradeLevel = ?"))
	{
        $stmt->bind_param('i', $gradeLevel);

        if ($stmt->execute())
        {
            $stmt->bind_result($studentID);
            $stmt->store_result();

            while ($stmt->fetch())
            {
				// Follow the trend....
				generateSingle($studentID, $mysqli);
        	}   
        }  
	}
}

function generateAll($mysqli)
{
	// Ohhhh this is nasty
	for ($i = 1; $i <= 12; $i++)
    {
		generateForGrade($i, $mysqli);
		// Call Function get get student list
		// Generate Zip
		// ????
		// Profit
    }
}

function generateReportCard($studentID, $mysqli)
{
	// Get grades that occur for each quarter
	// Calculate grade for Quarter
	// Calculate Total Grade
	$yearID = getClassYearID($mysqli);

	if ($stmt = $mysqli->prepare("SELECT quarterOneStart, quarterOneEnd, quarterTwoStart, quarterTwoEnd, quarterThreeStart, quarterThreeEnd, fallSemesterStart, fallSemesterEnd, springSemesterStart, springSemesterEnd WHERE schoolYearID = ?"))
	{
		$stmt->bind_param('i', $schoolYearID);

		if ($stmt->execute())
		{
			$stmt->bind_result($quarterOneStart, $quarterOneEnd, $quarterTwoStart, $quarterTwoEnd, $quarterThreeStart, quarterThreeEnd, fallSemesterStart, fallSemesterEnd, springSemesterStart, sp    ringSemesterEnd);
		}
	}
}

?>

