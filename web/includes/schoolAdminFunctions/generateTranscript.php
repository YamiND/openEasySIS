<?php
include_once '../dbConnect.php';
include_once '../functions.php';
include_once '../classFunctionsTemplate.php';
include_once '../parentFunctionsTemplate.php';
include_once '../studentFunctionsTemplate.php';
include_once '../userFunctionsTemplate.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

date_default_timezone_set('America/New_York');

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
   	$_SESSION['fail'] = 'Transcript could not be generated';
   	header('Location: ../../pages/generateTranscript');

	return;
}

function generateChoice($mysqli)
{
	// Delete all files in the report card directory
	if (!is_dir("../../../TranscriptOutputs"))
	{
		shell_exec('mkdir ../../../TranscriptOutputs');
	}
	shell_exec('rm -rf ../../../TranscriptOutputs/*');
	array_map('unlink', glob("Transcript*")); 

	if (isset($_POST['generateChoice']) && !empty($_POST['generateChoice']))
  	{
		$transcriptChoice = $_POST['generateChoice'];

		switch($transcriptChoice)
		{
			case 'generateSingle':
				generateSingle($_POST['studentID'], $mysqli);
				break;

			case 'generateForGrade':
				generateForGrade($_POST['gradeLevel'], $mysqli);
				break;

			case 'generateAll':
				generateAll($mysqli);
				break;

			default:
    			$_SESSION['fail'] = 'Transcript could not be generated, data not sent or incomplete';
   	   			header('Location: ../../pages/generateTranscript');
		}

		// After the functions have been ran, zip the directory and output the file to the browser
		apache_setenv('no-gzip', 1);
		ini_set('zlib.output_compression', 0);

		$day = date("Y-m-d");
		$outputFile = basename("../../../Transcripts-$day.zip");

		if (file_exists("Transcripts-$day.zip"))
		{
			shell_exec("rm -f Transcripts-$day.zip");
		}
		
		Zip("../../../TranscriptOutputs/", "$outputFile");

		$_SESSION['success'] = 'Transcript should be generated, check the file';

		header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length: ".filesize($outputFile));
        header("Content-Disposition: attachment; filename=$outputFile");
		ob_clean();
	    flush();
        readfile($outputFile);
		exit;
//  	   	header('Location: ../../pages/generateReportCard');
  	}
	else
	{
    	// The correct POST variables were not sent to this page.
    	$_SESSION['fail'] = 'Transcript could not be generated, data not sent or incomplete';
   	   	header('Location: ../../pages/generateTranscript');
	}
}

function Zip($source, $destination)
{
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true)
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file)
        {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                continue;

            $file = realpath($file);

            if (is_dir($file) === true)
            {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            }
            else if (is_file($file) === true)
            {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}

function generateSingle($studentID, $mysqli)
{
	generateTranscript($studentID, $mysqli);
}

function generateForGrade($gradeLevel, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT userID FROM users WHERE studentGradeLevel = ?"))
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
    }
}

function generateTranscript($studentID, $mysqli)
{
	// Get grades that occur for each quarter
	// Calculate grade for Quarter
	// Calculate Total Grade
//	$yearID = getClassYearID($mysqli);

	$studentName = getUserName($studentID, $mysqli);
	$studentGradeLevel = getStudentGradeByID($studentID, $mysqli);

//	$tblBody = "";

// Include the main TCPDF library (search for installation path).
require_once('../../fpdf181/TCPDF-master/examples/tcpdf_include.php');
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('MBA');
$pdf->SetTitle('Maplewood Baptist Academy');
$pdf->SetSubject('3255 West M-80 Kinross, MI 49752');
$pdf->SetKeywords('MBA, Transcript, Maplewood');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE , PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', 'B', 20);
	if ($stmt = $mysqli->prepare("SELECT schoolYearID, quarterOneStart, quarterOneEnd, quarterTwoStart, quarterTwoEnd, quarterThreeStart, quarterThreeEnd, fallSemesterStart, fallSemesterEnd, springSemesterStart, springSemesterEnd, quarterFourStart, quarterFourEnd FROM schoolYear"))
	{
		$tblBody = "";
		if ($stmt->execute())
		{
			$stmt->bind_result($schoolYearID, $quarterOneStart, $quarterOneEnd, $quarterTwoStart, $quarterTwoEnd, $quarterThreeStart, $quarterThreeEnd, $fallSemesterStart, $fallSemesterEnd, $springSemesterStart, $springSemesterEnd, $quarterFourStart, $quarterFourEnd);

			$stmt->store_result();

			while ($stmt->fetch())
			{
				if ($stmt2 = $mysqli->prepare("SELECT studentClassIDs.classID, classes.className FROM studentClassIDs INNER JOIN (classes) ON (classes.classID = studentClassIDs.classID AND studentClassIDs.studentID = ? AND classes.schoolYearID = ?)"))
				{
					$stmt2->bind_param('ii', $studentID, $schoolYearID);
					$stmt2->execute();
					$stmt2->bind_result($classID, $className);
					$stmt2->store_result();

					if ($stmt2->num_rows > 0)
					{	
						while ($stmt2->fetch())
						{
							if (date('Y-m-d') >= $quarterOneEnd)
				            {
				                $quarterOneGrade = getClassGradeForRange($studentID, $classID, $quarterOneStart, $quarterOneEnd, $mysqli);
				            }
				            else
				            {
				                $quarterOneGrade = "N/A";
				            }

							if (date('Y-m-d') >= $quarterTwoEnd)
							{
								$quarterTwoGrade = getClassGradeForRange($studentID, $classID, $quarterTwoStart, $quarterTwoEnd, $mysqli);
							}
							else
							{
								$quarterTwoGrade = "N/A";
							}

							if (date('Y-m-d') >= $quarterThreeEnd)
							{
								$quarterThreeGrade = getClassGradeForRange($studentID, $classID, $quarterThreeStart, $quarterThreeEnd, $mysqli);
							}
							else
							{
								$quarterThreeGrade = "N/A";
							}

							if (date('Y-m-d') >= $quarterThreeEnd)
							{
								$quarterFourGrade = getClassGradeForRange($studentID, $classID, $quarterFourStart, $quarterFourEnd, $mysqli);
							}
							else
							{
								$quarterFourGrade = "N/A";
							}

							if (date('Y-m-d') >= $fallSemesterEnd)
							{
								$semesterOneGrade = ($quarterOneGrade + $quarterTwoGrade) / 2;
								$semesterOneGrade = number_format((float) $semesterOneGrade, 2, '.', '') . "%";
							}
							else
							{
								$semesterOneGrade = "N/A";
							}

							 if (date('Y-m-d') >= $springSemesterEnd)
							{
								$semesterTwoGrade = ($quarterTwoGrade + $quarterThreeGrade) / 2;
								$semesterTwoGrade = number_format((float) $semesterTwoGrade, 2, '.', '') . "%";
							}
							else
							{
								$semesterTwoGrade = "N/A";
							}

				/*          echo "Q1: " .$quarterOneGrade . "<br>";
							echo "Q2: " .$quarterTwoGrade . "<br>";
							echo "Q3: " .$quarterThreeGrade . "<br>";
							echo "Q4: " .$quarterFourGrade . "<br>";
							echo "S1: " .$semesterOneGrade . "<br>";
							echo "S2: " .$semesterTwoGrade . "<br>";
							exit;
				*/
							$teacherName = getTeacherNameByClassID($classID, $mysqli);
							//Changing the color and the size of the tables data NOT the header of the table 
							$tblBody .= "
								<tr style=\"background-color:white;color:black; font-size: 13px; padding: 5px;;\">
									<td width=\"140\" align=\"left\"> $className </td>
									<td width=\"130\" align=\"left\"> $teacherName </td>
									<td width=\"55\" align=\"left\"> $quarterOneGrade </td>
									<td width=\"55\" align=\"left\"> $quarterTwoGrade </td>
									<td width=\"55\" align=\"left\"> $quarterThreeGrade </td>
									<td width=\"55\" align=\"left\"> $quarterFourGrade </td>
									<td width=\"60\" align=\"left\"> $semesterOneGrade </td>
									<td width=\"60\" align=\"left\"> $semesterTwoGrade </td>
								</tr>
							";

											$academicYear = getAcademicYear($schoolYearID, $mysqli);	

						}
						writeTranscriptPDF($studentName, $studentGradeLevel, $academicYear, $tblBody, $pdf);  
						$tblBody = "";
					}
				}
			}
		}
	}
	if (!is_dir("../../../TranscriptOutputs/Grade\ $studentGradeLevel"))
	{
		shell_exec("mkdir ../../../TranscriptOutputs/Grade\ $studentGradeLevel");
	}

	$outputFile = realpath("../../../TranscriptOutputs/Grade $studentGradeLevel");
	//$pdf->Output("/var/www/html/openEasySIS/reportCardOutputs/Grade $studentGradeLevel/$studentName.pdf", 'F');
	$pdf->Output("$outputFile/$studentName.pdf", 'F');
}

function getTeacherNameByClassID($classID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT classTeacherID FROM classes WHERE classID = ?"))
	{
		$stmt->bind_param('i', $classID);
		$stmt->execute();
		$stmt->bind_result($classTeacherID);
		$stmt->store_result();

		$stmt->fetch();
	}

	if ($stmt = $mysqli->prepare("SELECT userFirstName, userLastName FROM users WHERE userID = ?"))
	{
		$stmt->bind_param('i', $classTeacherID);
		$stmt->execute();
		$stmt->bind_result($userFirstName, $userLastName);
		$stmt->store_result();

		$stmt->fetch();

		return "$userLastName,$userFirstName";
	}
}

function writeTranscriptPDF($studentName, $studentGradeLevel, $academicYear, $tblBody, $pdf)
{

// add a page
$pdf->AddPage();
//font type and size for Transcipt. 
$pdf->SetFont('times', '', 20);
$pdf->Write(0, 'Transcript', '', 0, 'L', true, 0, false, false, 0);
//font type and size for student info
$pdf->SetFont('times', '', 12);
$pdf->Cell(0,8,'Student Name: '. $studentName,0,1);
$pdf->Cell(0,8,'Grade: '.$studentGradeLevel,0,1);
$pdf->Cell(0,8,'Academic Year: '.$academicYear,0,1);
$pdf->SetFont('times', '', 12);

// -----------------------------------------------------------------------------
// Table with rowspans and THEAD
$tbl = <<<EOD
<table border="1">
<thead>
 <tr style="background-color:white;color:black; font-size: 18px;">
  <td width="140" align="center"><b>Class</b></td>
  <td width="130" align="center"><b>Teacher</b></td>
  <td width="55" align="center"><b>Q1</b></td>
  <td width="55" align="center"> <b>Q2</b></td>
  <td width="55" align="center"><b>Q3</b></td>
  <td width="55" align="center"><b>Q4</b></td>
  <td width="60" align="center"><b>S1</b></td>
  <td width="60" align="center"><b>S2</b></td>
 </tr>
</thead>
	$tblBody
</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');
//New table with new Academic Year for multiple tables like Transcript
//$pdf->Cell(0,8,'Academic Year: '.$i,0,1);
//$pdf->writeHTML($tbl, true, false, false, false, '');

//Close and output PDF document to the browser
//$pdf->Output("$studentName-test.pdf", 'I');

//Close and output PDF document to the filesystem
//$pdf->Output(realpath("../../../reportCardOutputs/Grade\ $studentGradeLevel/$studentName.pdf"), 'F');
	
//============================================================+
// END OF FILE
//============================================================+


}

function getClassGradeForRange($studentID, $classID, $startDate, $endDate, $mysqli)
{
    // General Equation for Weighted Grading
    // type1 * (type1Weight) + type2 * (type2Weight) + type3 * (type3Weight)
    // = a % then multiply by 100

/*  echo "Student ID: " . $studentID . "<br>";
    echo "Class ID: " . $classID . "<br>";
    echo "Start Date: " . $startDate . "<br>";
    echo "End Date: " . $endDate . "<br>";
*/

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
//              echo "Material Type ID: " . $materialTypeID . "<br>";
//              echo "Material Weight: " . $materialWeight . "<br>";
                // Score should be adding as a percentage
                    $score += getScoreByMaterialTypeRange($materialTypeID, $materialWeight, $studentID, $classID, $startDate, $endDate, $mysqli);
//                  echo $score . "<br>";
            }

            return number_format((float) ($score * 100), 2, '.', '') . "%";
//          return ($score * 100) . "%";
        }
        else
        {
//          return "No database results";
            return "N/A";
        }
    }
    else
    {
        return "Database query failed";
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
//          if ($stmt->num_rows == 1)
//          {
//              $materialWeight = 100;
//          }

            while ($stmt->fetch())
            {
//              echo "Material ID: " .$materialID . "<br>";
                $materialPointsPossible = getMaterialPointsPossible($materialID, $mysqli);
                $materialPointsScored = getMaterialPointsScored($materialID, $classID, $studentID, $mysqli);

                $totalMPS += $materialPointsScored;
                $totalMPP += $materialPointsPossible;
            }

            if ($totalMPP != 0)
            {
/*              echo "Total Points Scored: " . $materialPointsScored . "<br>";
                echo "Total Points Possible: " . $materialPointsPossible . "<br>";
                echo "Material Weight: " . $materialWeight . "<br>";
*/
                $totalScore = (($totalMPS / $totalMPP) * ($materialWeight * 0.01));
            }
            else
            {
                $totalScore = 0;
            }
            return $totalScore;
        }
        else
        {
            return ($materialWeight * 0.01);
        }
    }
    else
    {
        return "N/A";
    }
}

/*function getClassGradeForRange($studentID, $classID, $startDate, $endDate, $mysqli)
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
}*/

?>

