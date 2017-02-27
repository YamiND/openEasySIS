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
   	$_SESSION['fail'] = 'Report Card could not be generated';
   	header('Location: ../../pages/generateReportCard');

	return;
}

function generateChoice($mysqli)
{
	// Delete all files in the report card directory
	shell_exec('rm -rf ../../../reportCardOutputs/*');

	if (isset($_POST['generateChoice']) && !empty($_POST['generateChoice']))
  	{
		$reportCardChoice = $_POST['generateChoice'];

		switch($reportCardChoice)
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
    			$_SESSION['fail'] = 'Report Card could not be generated, data not sent or incomplete';
   	   			header('Location: ../../pages/generateReportCard');
		}

		// After the functions have been ran, zip the directory and output the file to the browser
		apache_setenv('no-gzip', 1);
		ini_set('zlib.output_compression', 0);

		$day = date("Y-m-d");
		$outputFile = basename("../../../reportCards-$day.zip");

		if (file_exists("reportCards-$day.zip"))
		{
			shell_exec("rm -f reportCards-$day.zip");
		}
		
		Zip("../../../reportCardOutputs/", "$outputFile");

		$_SESSION['success'] = 'Report Card should be generated, check the file';

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
    	$_SESSION['fail'] = 'Report Card could not be generated, data not sent or incomplete';
   	   	header('Location: ../../pages/generateReportCard');
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
	generateReportCard($studentID, $mysqli);
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

function generateReportCard($studentID, $mysqli)
{
	$tblBody = "";
	// Get grades that occur for each quarter
	// Calculate grade for Quarter
	// Calculate Total Grade
	$yearID = getClassYearID($mysqli);

	$studentName = getUserName($studentID, $mysqli);
	$studentGradeLevel = getStudentGradeByID($studentID, $mysqli);
	$academicYear = getAcademicYear($yearID, $mysqli);	


	if ($stmt = $mysqli->prepare("SELECT quarterOneStart, quarterOneEnd, quarterTwoStart, quarterTwoEnd, quarterThreeStart, quarterThreeEnd, fallSemesterStart, fallSemesterEnd, springSemesterStart, springSemesterEnd, quarterFourStart, quarterFourEnd FROM schoolYear WHERE schoolYearID = ?"))
	{
		$stmt->bind_param('i', $yearID);

		if ($stmt->execute())
		{
			$stmt->bind_result($quarterOneStart, $quarterOneEnd, $quarterTwoStart, $quarterTwoEnd, $quarterThreeStart, $quarterThreeEnd, $fallSemesterStart, $fallSemesterEnd, $springSemesterStart, $springSemesterEnd, $quarterFourStart, $quarterFourEnd);

			$stmt->store_result();

			$stmt->fetch();
		}
	}

	if ($stmt = $mysqli->prepare("SELECT studentClassIDs.classID, classes.className FROM studentClassIDs INNER JOIN (classes) ON (classes.classID = studentClassIDs.classID AND studentClassIDs.studentID = ? AND classes.schoolYearID = ?)"))
	{
		$stmt->bind_param('ii', $studentID, $yearID);
		$stmt->execute();
		$stmt->bind_result($classID, $className);
		$stmt->store_result();

		while ($stmt->fetch())
		{
			$quarterOneGrade = getClassGradeForRange($studentID, $classID, $quarterOneStart, $quarterOneEnd, $mysqli);
			$quarterTwoGrade = getClassGradeForRange($studentID, $classID, $quarterOneStart, $quarterTwoEnd, $mysqli);
			$quarterThreeGrade = getClassGradeForRange($studentID, $classID, $quarterOneStart, $quarterThreeEnd, $mysqli);
			$quarterFourGrade = getClassGradeForRange($studentID, $classID, $quarterFourStart, $quarterFourEnd, $mysqli);

			$semesterOneGrade = getClassGradeForRange($studentID, $classID, $fallSemesterStart, $fallSemesterEnd, $mysqli);
			$semesterTwoGrade = getClassGradeForRange($studentID, $classID, $springSemesterStart, $springSemesterEnd, $mysqli);

			$teacherName = getTeacherNameByClassID($classID, $mysqli);

			$tblBody .= "
				<tr style=\"background-color:white;color:black; font-size: 14px; padding: 5px;;\">
			 		<td width=\"140\" align=\"left\"> $className </td>
				 	<td width=\"140\" align=\"left\"> $teacherName </td>
					<td width=\"45\" align=\"left\"> $quarterOneGrade </td>
					<td width=\"45\" align=\"left\"> $quarterTwoGrade </td>
					<td width=\"45\" align=\"left\"> $quarterThreeGrade </td>
					<td width=\"45\" align=\"left\"> $quarterFourGrade </td>
					<td width=\"60\" align=\"left\"> $semesterOneGrade </td>
					<td width=\"60\" align=\"left\"> $semesterTwoGrade </td>
				</tr>
			";
						
		}
	}
	// Generate a PDF for the student
	writeReportCardPDF($studentName, $studentGradeLevel, $academicYear, $tblBody);  
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

function writeReportCardPDF($studentName, $studentGradeLevel, $academicYear, $tblBody)
{
// Include the main TCPDF library (search for installation path).
require_once('../../fpdf181/TCPDF-master/examples/tcpdf_include.php');
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('MBA');
$pdf->SetTitle('Maplewood Baptist Academy');
$pdf->SetSubject('3255 West M-80 Kinross, MI 49752');
$pdf->SetKeywords('MBA, Report Card, Maplewood');

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

// add a page
$pdf->AddPage();

$pdf->Write(0, 'Report Card', '', 0, 'L', true, 0, false, false, 0);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0,8,'Student Name: '. $studentName,0,1);
$pdf->Cell(0,8,'Grade: '.$studentGradeLevel,0,1);
$pdf->Cell(0,8,'Academic Year: '.$academicYear,0,1);
$pdf->SetFont('helvetica', '', 12);

// -----------------------------------------------------------------------------
// Table with rowspans and THEAD
$tbl = <<<EOD
<table border="1">
<thead>
 <tr style="background-color:white;color:black; font-size: 18px;">
  <td width="140" align="center"><b>Class</b></td>
  <td width="140" align="center"><b>Teacher</b></td>
  <td width="45" align="center"><b>Q1</b></td>
  <td width="45" align="center"> <b>Q2</b></td>
  <td width="45" align="center"><b>Q3</b></td>
  <td width="45" align="center"><b>Q4</b></td>
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
	if (!is_dir("../../../reportCardOutputs/Grade\ $studentGradeLevel"))
	{
		shell_exec("mkdir ../../../reportCardOutputs/Grade\ $studentGradeLevel");
	}

	$outputFile = realpath("../../../reportCardOutputs/Grade $studentGradeLevel");
	//$pdf->Output("/var/www/html/openEasySIS/reportCardOutputs/Grade $studentGradeLevel/$studentName.pdf", 'F');
	$pdf->Output("$outputFile/$studentName.pdf", 'F');
//============================================================+
// END OF FILE
//============================================================+


}

function getClassGradeForRange($studentID, $classID, $startDate, $endDate, $mysqli)
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

?>

