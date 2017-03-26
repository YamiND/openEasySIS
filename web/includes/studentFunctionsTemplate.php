<?php

function getStudentGradeByID($studentID, $mysqli)
{
	 if ($stmt = $mysqli->prepare("SELECT studentGradeLevel FROM users WHERE userID = ?"))
    {
        $stmt->bind_param('i', $studentID);
        $stmt->execute();
        $stmt->bind_result($studentGradeLevel);
        $stmt->store_result();

        while($stmt->fetch())
        {   
			return "$studentGradeLevel";
        }       
    }       
    else
    {   
        return;
    } 	
}

function getStudentGraduationYear($studentID, $mysqli)
{
	$studentGradeLevel = getStudentGradeByID($studentID, $mysqli);

	$schoolYearID = getClassYearID($mysqli);
	
	if ($stmt = $mysqli->prepare("SELECT schoolYearEnd FROM schoolYear WHERE schoolYearID = ?"))
	{
		$stmt->bind_param('i', $schoolYearID);

		if ($stmt->execute())
		{
			$stmt->bind_result($schoolYearEnd);
			$stmt->store_result();
			$stmt->fetch();
		}
	}

	$numYears = 12 - $studentGradeLevel;

	if ($numYears == 0)
	{
		// The student graduates at the end of the year
		return "$schoolYearEnd";
	}
	else
	{
		// Add their remaining years to the schoolyear start date
		return ($numYears + $schoolYearEnd);
	}
}

?>
