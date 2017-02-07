<?php
include_once '../dbConnect.php';
include_once '../functions.php';
include_once '../logTemplate.php';

//TODO: I need to comment my code (and then need to redo the error checking)
// For example:
/*
    Fall Semester Start Date must match School Year Start Date
    Spring Semester End Date must match School Year End Date
    Quarter 1 Start Date must match School Year Start Date

    Verify no duplicate years are allowed
    And we can't add previous years
    Also update your session error codes
*/

sec_session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	addSchoolYear($mysqli);
}
else
{
   	$_SESSION['fail'] = 'School Year could not be added, invalid permissions';
   	header('Location: ../../pages/addSchoolYear');
}

function addSchoolYear($mysqli)
{
	if ((isset($_POST['schoolYearStart'], $_POST['schoolYearEnd'], $_POST['fallSemesterStart'], $_POST['fallSemesterEnd'], $_POST['springSemesterStart'], $_POST['springSemesterEnd'], $_POST['quarterOneStart'], $_POST['quarterOneEnd'], $_POST['quarterTwoStart'], $_POST['quarterTwoEnd'], $_POST['quarterThreeStart'], $_POST['quarterThreeEnd'])) && !empty($_POST['schoolYearStart']) && !empty($_POST['schoolYearEnd']) && !empty($_POST['fallSemesterStart']) && !empty($_POST['fallSemesterEnd']) && !empty($_POST['springSemesterStart']) && !empty($_POST['springSemesterEnd']) && !empty($_POST['quarterOneStart']) && !empty($_POST['quarterOneEnd']) && !empty($_POST['quarterTwoStart']) && !empty($_POST['quarterTwoEnd']) && !empty($_POST['quarterThreeStart']) && !empty($_POST['quarterThreeEnd'])) 
	{
		$schoolYearStart = $_POST['schoolYearStart'];
		$schoolYearEnd =  $_POST['schoolYearEnd']; 
		$fallSemesterStart = $_POST['fallSemesterStart']; 
		$fallSemesterEnd = $_POST['fallSemesterEnd']; 
		$springSemesterStart = $_POST['springSemesterStart']; 
		$springSemesterEnd = $_POST['springSemesterEnd']; 
		$quarterOneStart = $_POST['quarterOneStart']; 
		$quarterOneEnd = $_POST['quarterOneEnd']; 
		$quarterTwoStart = $_POST['quarterTwoStart']; 
		$quarterTwoEnd = $_POST['quarterTwoEnd']; 
		$quarterThreeStart = $_POST['quarterThreeStart'];
		$quarterThreeEnd = $_POST['quarterThreeEnd'];

		if ($stmt = $mysqli->prepare("SELECT fallSemesterStart, fallSemesterEnd, springSemesterStart, springSemesterEnd, quarterOneStart, quarterOneEnd, quarterTwoStart, quarterTwoEnd, quarterThreeStart, quarterThreeEnd, schoolYearStart, schoolYearEnd FROM schoolYear"))
		{
			$stmt->execute();
			$stmt->bind_result($dbFallSemesterStart, $dbFallSemesterEnd, $dbSpringSemesterStart, $dbSpringSemesterEnd, $dbQuarterOneStart, $dbQuarterOneEnd, $dbQuarterTwoStart, $dbQuarterTwoEnd, $dbQuarterThreeStart, $dbQuarterThreeEnd, $dbSchoolYearStart, $dbSchoolYearEnd);
			$stmt->store_result();

			while($stmt->fetch())
			{
				$tempSchoolYearStart = substr("$schoolYearStart", 0, 4);	
				$tempDbSchoolYearStart = substr("$dbSchoolYearStart", 0, 4);
					
				if (($tempSchoolYearStart == $tempDbSchoolYearStart) || ($tempSchoolYearStart < $tempDbSchoolYearStart))
				{
   					$_SESSION['fail'] = 'School Year could not be added, year already exists';
				   	header('Location: ../../pages/addSchoolYear');
				}

				$tempSchoolYearEnd = substr("$schoolYearEnd", 0, 4);	
				$tempDbSchoolYearEnd = substr("$dbSchoolYearEnd", 0, 4);

				if (($tempSchoolYearEnd == $tempDbSchoolYearEnd) || ($tempSchoolYearEnd < $tempDbSchoolYearEnd) || ($tempSchoolYearStart < $tempSchoolYearEnd))
				{
					appendLog("schoolyear.txt", "School year could not be added, year already exists");
   					$_SESSION['fail'] = 'School Year could not be added, year already exists';
				   	header('Location: ../../pages/addSchoolYear');
				}
			}
   
			if ($stmt = $mysqli->prepare("INSERT INTO schoolYear (fallSemesterStart, fallSemesterEnd, springSemesterStart, springSemesterEnd, quarterOneStart, quarterOneEnd, quarterTwoStart, quarterTwoEnd, quarterThreeStart, quarterThreeEnd, schoolYearStart, schoolYearEnd) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))
			{
    			$stmt->bind_param('ssssssssssss', $fallSemesterStart, $fallSemesterEnd, $springSemesterStart, $springSemesterEnd, $quarterOneStart, $quarterOneEnd, $quarterTwoStart, $quarterTwoEnd, $quarterThreeStart, $quarterThreeEnd, $schoolYearStart, $schoolYearEnd); 
	    		if ($stmt->execute())    // Execute the prepared query.
				{
					appendLog("schoolyear.txt", "School yeare added");
					$_SESSION['success'] = "School Year Added";
   	   				header('Location: ../../pages/addSchoolYear');
				}
				else
				{
					appendLog("schoolyear.txt", "School year could not be added, year already exists");
    				$_SESSION['fail'] = 'School Year could not be added to database';
				}

			}
			else
			{
    			// The correct POST variables were not sent to this page.
    			$_SESSION['fail'] = 'School Year could not be added to database';
   	   			header('Location: ../../pages/addSchoolYear');
			}
		}
    }
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'School Year could not be added, please fill out all fields';
   	   	header('Location: ../../pages/addSchoolYear');
	}
}

?>

