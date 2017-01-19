<?php

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
            $_SESSION['fail'] = 'Class could not be added, you need to set a school year for this current year';
            header('Location: ../../pages/addClass');
        }
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

		while ($stmt->fetch())
		{
			$score += getScoreByMaterialType($materialTypeID, $materialWeight, $mysqli);

		}
		return $score;
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

		while ($stmt->fetch())
		{
			return $gradeMaterialPointsScored;
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

function getScoreByMaterialType($materialTypeID, $materialWeight, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT materialID FROM materials WHERE materialTypeID = ?"))
	{
		$stmt->bind_param('i', $materialTypeID);
        $stmt->execute();
        $stmt->bind_result($materialID);
        $stmt->store_result();

		while ($stmt->fetch())
		{
			$materialPointsPossible = getMaterialPointsPossible($materialID, $mysqli);
			$materialPointsScored = getMaterialPointsScored($materialID, $classID, $studentID, $mysqli);	

			$tempScore = $materialPointsScored / $materialPointsPossible ;
	
			$score += $tempScore;	
		}
	
		if ($materialWeight != 0)
		{
			// Factor Weight into the score
			$score = $score * ($materialWeight * 0.10);
		}

		return $score;
	}
}
// Test studentID = 5
// Test ClassID = 1
/*    if ($stmt = $mysqli->prepare("SELECT gradeRefID, gradeMaterialID, gradeMaterialPointsScored FROM grades WHERE gradeStudentID = ? AND gradeClassID = ?"))
    {
		$stmt->bind_param('ii', $studentID, $classID);
        $stmt->execute();
        $stmt->bind_result($gradeRefID, $gradeMaterialID, $gradeMaterialPointsScored);
        $stmt->store_result();

		while ($stmt->fetch())
		{
			$materialPointsPossible = getMaterialPointsPossible($gradeMaterialID, $mysqli);
		}
	}*/
//}


?>
