<?php

function getReportCardComment($studentID, $classID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT reportCardComment FROM reportCardComment WHERE studentID = ? AND classID = ?"))
	{
		$stmt->bind_param('ii', $studentID, $classID);

		if ($stmt->execute())
		{
			$stmt->bind_result($reportCardComment);
			$stmt->store_result();

			$stmt->fetch();

			return $reportCardComment;
		}
	}
}

function getGradeComment($studentID, $materialID, $classID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT gradeComment FROM grades WHERE gradeMaterialID = ? AND gradeStudentID = ? AND gradeClassID = ?"))
	{
		$stmt->bind_param('iii', $materialID, $studentID, $classID);

		if ($stmt->execute())
		{
			$stmt->bind_result($gradeComment);
			$stmt->store_result();

			$stmt->fetch();

			return $gradeComment;
		}
	}
}

function getTotalGPA($studentID, $mysqli)
{
	$gpaPercentage = 0;
	$classNumber = 0;

	if ($stmt = $mysqli->prepare("SELECT schoolYearID FROM schoolYear"))
	{
		if ($stmt->execute())
		{
			$stmt->bind_result($schoolYearID);

			$stmt->store_result();

			while ($stmt->fetch())
			{
				if ($stmt2 = $mysqli->prepare("SELECT studentClassIDs.classID FROM studentClassIDs INNER JOIN (classes) ON (classes.classID = studentClassIDs.classID AND studentClassIDs.studentID = ? AND classes.schoolYearID = ?)"))
				{
					$stmt2->bind_param('ii', $studentID, $schoolYearID);

					if ($stmt2->execute())
					{
						$stmt2->bind_result($classID);
						$stmt2->store_result();
					
						if ($stmt2->num_rows > 0)
						{	
							while ($stmt2->fetch())
							{
								$classNumber++;
								$classGrade = getClassGrade($studentID, $classID, $mysqli);
								
								if ($classGrade > 59)
								{		// Appparently grades below 59% are 0 as GPAs
									$gpaPercentage += (($classGrade / 20) - 1);
								}
							}	
						}
					}
				}
			}
		}
	}
	if ($classNumber > 0)
	{
		$totalGPA = ($gpaPercentage / $classNumber);
	}
	else
	{
		$totalGPA = "0";
	}

	return "$totalGPA";	
}

function getCurrentSchoolYearGPA($studentID, $mysqli)
{
	$gpaPercentage = 0;
	$currentYear = getClassYearID($mysqli);

	if ($stmt = $mysqli->prepare("SELECT studentClassIDs.classID FROM studentClassIDs INNER JOIN (classes) ON (classes.classID = studentClassIDs.classID AND studentClassIDs.studentID = ? AND classes.schoolYearID = ?)"))
	{
		$stmt->bind_param('ii', $studentID, $currentYear);

		if ($stmt->execute())
		{
			$stmt->bind_result($classID);
			$stmt->store_result();

			$classNumber = $stmt->num_rows;

			while ($stmt->fetch())
			{
				$classGrade = getClassGrade($studentID, $classID, $mysqli);
				
				if ($classGrade > 59)
				{		// Appparently grades below 59% are 0 as GPAs
					$gpaPercentage += (($classGrade / 20) - 1);
				}
			}	
		}
	}

	$totalGPA = ($gpaPercentage / $classNumber);

	return "$totalGPA";	
}

function getClassYearID($mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT schoolYearID FROM schoolYear WHERE schoolYearStart <= CURDATE() AND schoolYearEnd >= CURDATE()"))
    {
        $stmt->execute();
        $stmt->bind_result($schoolYearID);
        $stmt->store_result();

        if ($stmt->num_rows > 0)
        {
            while ($stmt->fetch())
            {
                return $schoolYearID;
            }
        }
        else
        {
			return "-1";
        }
    }
}

function getAcademicYear($yearID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT YEAR(schoolYearStart), YEAR(schoolYearEnd) FROM schoolYear WHERE schoolYearID = ?"))
    {   
        $stmt->bind_param('i', $yearID);
        $stmt->execute();
        $stmt->bind_result($schoolYearStart, $schoolYearEnd);
        $stmt->store_result();

        $stmt->fetch();

        return "$schoolYearStart - $schoolYearEnd";
    }   
}

function getClassGrade($studentID, $classID, $mysqli)
{
	// General Equation for Weighted Grading
	// type1 * (type1Weight) + type2 * (type2Weight) + type3 * (type3Weight)
	// = a % then multiply by 100

	if ($stmt = $mysqli->prepare("SELECT materialTypeID, materialWeight FROM materialType WHERE classID = ?"))
	{
		$stmt->bind_param('i', $classID);
        $stmt->execute();
        $stmt->bind_result($materialTypeID, $materialWeight);
        $stmt->store_result();

		$score = 0;
		
		while ($stmt->fetch())
		{
			// Score should be adding as a percentage
			$score += getScoreByMaterialType($materialTypeID, $materialWeight, $studentID, $classID, $mysqli);
		}
		return number_format((float) ($score * 100), 2, '.', '');
	//	return ($score * 100);
	}
}

function getMaterialPointsScored($materialID, $classID, $studentID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT gradeMaterialPointsScored FROM grades WHERE gradeMaterialID = ? AND gradeClassID = ? AND gradeStudentID = ?"))
	{
		$stmt->bind_param('iii', $materialID, $classID, $studentID);
        $stmt->execute();
        $stmt->bind_result($gradeMaterialPointsScored);
        $stmt->store_result();

		if ($stmt->num_rows > 0)
		{
			while ($stmt->fetch())
			{
				return $gradeMaterialPointsScored;
			}
		}
		else
		{
			return "0";
		}
	}
}

function getMaterialPointsPossible($materialID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT materialPointsPossible FROM materials WHERE materialID = ?"))
	{
		$stmt->bind_param('i', $materialID);
		$stmt->execute();
		$stmt->bind_result($materialPointsPossible);
		$stmt->store_result();

		while ($stmt->fetch())
		{
			return $materialPointsPossible;	
		}
	} 
}

function getScoreByMaterialType($materialTypeID, $materialWeight, $studentID, $classID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT materialID FROM materials WHERE materialTypeID = ?"))
	{
		$stmt->bind_param('i', $materialTypeID);
        $stmt->execute();
        $stmt->bind_result($materialID);
        $stmt->store_result();

		$totalMPS = 0;
		$totalMPP = 0;
		
		if ($stmt->num_rows > 0)
		{
			while ($stmt->fetch())
			{
				$materialPointsPossible = getMaterialPointsPossible($materialID, $mysqli);
				$materialPointsScored = getMaterialPointsScored($materialID, $classID, $studentID, $mysqli);	

				$totalMPS += $materialPointsScored;
				$totalMPP += $materialPointsPossible;
			}			

			if ($totalMPP != 0)
			{
				$totalScore = (($totalMPS / $totalMPP) * ($materialWeight * 0.01));
			}
			else
			{
				$totalScore = 0;
			}
		}
		else
		{
			$totalScore = $materialWeight * 0.01;
		}

		return $totalScore;
	}
	else
	{
		$totalScore = $materialWeight * 0.01;
	}

	return $totalScore;
}

?>
