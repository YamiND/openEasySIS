<?php
include_once '../dbConnect.php';
include_once '../functions.php';
include_once '../classFunctionsTemplate.php';
include_once '../parentFunctionsTemplate.php';

sec_session_start(); // Our custom secure way of starting a PHP session.


// Error Testing
error_reporting(E_ALL);
ini_set('display_startup_errors',1);
ini_set('display_errors',1);

if ((login_check($mysqli) == true) && (isSchoolAdmin($mysqli) || isAdmin($mysqli)))
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
//		global $dataOutput = array();

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

		$_SESSION['success'] = 'Report Card should be generated, check the file';
   	   //	header('Location: ../../pages/generateReportCard');
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
	$fp = fopen("/tmp/$studentID.txt", 'a');//opens file in append mode  
	// Get grades that occur for each quarter
	// Calculate grade for Quarter
	// Calculate Total Grade
	$yearID = getClassYearID($mysqli);

	$studentName = getStudentName($studentID, $mysqli);
	
	fwrite($fp, "$studentName".PHP_EOL);  
	fwrite($fp, "Class Name, Q1, Q2, Q3".PHP_EOL);  

	if ($stmt = $mysqli->prepare("SELECT quarterOneStart, quarterOneEnd, quarterTwoStart, quarterTwoEnd, quarterThreeStart, quarterThreeEnd, fallSemesterStart, fallSemesterEnd, springSemesterStart, springSemesterEnd FROM schoolYear WHERE schoolYearID = ?"))
	{
		$stmt->bind_param('i', $yearID);

		if ($stmt->execute())
		{
			$stmt->bind_result($quarterOneStart, $quarterOneEnd, $quarterTwoStart, $quarterTwoEnd, $quarterThreeStart, $quarterThreeEnd, $fallSemesterStart, $fallSemesterEnd, $springSemesterStart, $springSemesterEnd);

			$stmt->store_result();

			$stmt->fetch();
		}
	}

	$dataOutput = array();
		// Setting our array tracker
//		$i = 0;
				if ($stmt = $mysqli->prepare("SELECT studentClassIDs.classID, classes.className FROM studentClassIDs INNER JOIN (classes) ON (classes.classID = studentClassIDs.classID AND studentClassIDs.studentID = ?)"))
				{
					$stmt->bind_param('i', $studentID);
					$stmt->execute();
					$stmt->bind_result($classID, $className);
					$stmt->store_result();
					while ($stmt->fetch())
					{
						$quarterOneGrade = getClassGradeForRange($fp, $studentID, $classID, $quarterOneStart, $quarterOneEnd, $mysqli);
						$quarterTwoGrade = getClassGradeForRange($fp, $studentID, $classID, $quarterOneStart, $quarterTwoEnd, $mysqli);
						$quarterThreeGrade = getClassGradeForRange($fp, $studentID, $classID, $quarterOneStart, $quarterThreeEnd, $mysqli);

		//				fwrite($fp, "$className, $quarterOneGrade, $quarterTwoGrade, $quarterThreeGrade".PHP_EOL);  
						fwrite($fp, "$className, $quarterOneGrade, $quarterTwoGrade, $quarterThreeGrade".PHP_EOL);  
						
						$dataOutput[] = array('classname' => $className, 'q1' => $quarterOneGrade, 'q2' => $quarterThreeGrade, 'q3' => $quarterThreeGrade);

					//	$combined = "$className, $quarterOneGrade%, $quarterTwoGrade%, $quarterThreeGrade%";
						// Output will be: Class Name, Q1, Q2, Q3
					}
				}
		ExportFile($studentName, $dataOutput);
			$filename = "output.xls";		 
            header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=\"$filename\"");
	fclose($fp);  
}

function getClassGradeForRange($fp, $studentID, $classID, $startDate, $endDate, $mysqli)
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
   
	if ($stmt->num_rows > 0)
	{ 
        	while ($stmt->fetch())
        	{   
            	// Score should be adding as a percentage
	            	$score += getScoreByMaterialTypeRange($materialTypeID, $materialWeight, $studentID, $classID, $startDate, $endDate, $mysqli);
        	}   

		if ($score < 0)
		{
			// Scores should only be negative if there's no assignments in the range
			return "N/A";
		}
		else
		{
        		return ($score * 100) . "%";
		}
	}
	else
	{
		return "N/A";
	}
    }  
    else
    {
	return "N/A";
    } 
}

function getScoreByMaterialTypeRange($materialTypeID, $materialWeight, $studentID, $classID, $startDate, $endDate, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT materialID FROM materials WHERE materialClassID = ? AND materialTypeID = ? AND materialDueDate BETWEEN '{$startDate}' AND '{$endDate}'"))
    {
        $stmt->bind_param('ii', $classID, $materialTypeID);
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
//            $totalScore = $materialWeight * 0.01;
		$totalScore = "-1";
        }

        return $totalScore;
    }
    else
    {
        $totalScore = NULL;
    }

    return $totalScore;
}

function ExportFile($studentName, $records) 
{
	$heading = false;
	if(!empty($records))
	{
		//echo implode("\t", $studentName . "\n";
		foreach($records as $row) 
		{
			if(!$heading) 
			{
			  
				// display field/column names as a first row
			  $arrayHeader = array('Class Name', 'Q1', 'Q2', 'Q3');
			  echo implode("\t", array_values($arrayHeader)) . "\n";
			  $heading = true;
			}
			echo implode("\t", array_values($row)) . "\n";
		}
	}
}

?>

