<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

//TODO Test this
if ((login_check($mysqli) == true) && (isSchoolAdmin($mysqli) || isAdmin($mysqli)))
{
	parseCSV($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Manual Report Card Generation failed, invalid permissions';
   	header('Location: ../../pages/generateManualReportCard');
}

function parseCSV($mysqli)
{
	if(!isset($_FILES['csvFile']) || $_FILES['csvFile']['error'] == UPLOAD_ERR_NO_FILE) 
	{
   		$_SESSION['fail'] = 'Manual Report Card Generation failed, invalid CSV';
	   	header('Location: ../../pages/generateManualReportCard');
	} 
	else 
	{
		if($_FILES['csvFile']['error'] == 0)
		{
	    	$name = $_FILES['csvFile']['name'];
   		 	$ext = strtolower(end(explode('.', $_FILES['csvFile']['name'])));
		    $type = $_FILES['csvFile']['type'];
		    $tmpName = $_FILES['csvFile']['tmp_name'];

   		 	// check the file is a csv
   		 	if($ext === 'csv')
			{
				$studentCSV = array_map('str_getcsv', file($tmpName));
			
				if (isset($_POST['studentFirstName'], $_POST['studentLastName'], $_POST['academicYear'], $_POST['studentGPA'], $_POST['studentGradeLevel']) && !empty($_POST['studentFirstName']) && !empty($_POST['studentLastName']) && !empty($_POST['academicYear']) && !empty($_POST['studentGPA']) && !empty($_POST['studentGradeLevel']))
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

					$studentFirstName = $_POST['studentFirstName'];
					$studentLastName = $_POST['studentLastName'];
					$academicYear = $_POST['academicYear'];
					$studentGPA = $_POST['studentGPA'];
					$studentGradeLevel = $_POST['studentGradeLevel'];

					$studentName = "$studentLastName, $studentFirstName";
					$tblBody = "";

					foreach($studentCSV as $i => $data)
					{
						$className = $studentCSV[$i][0];
						$teacherName = $studentCSV[$i][1];
						$Q1 = $studentCSV[$i][2];
						$Q2 = $studentCSV[$i][3];
						$Q3 = $studentCSV[$i][4];
						$Q4 = $studentCSV[$i][5];
						$S1 = (($Q1 + $Q2 )/ 2);
						$S2 = (($Q3 + $Q4 )/ 2);

						$tblBody .= "
								<tr style=\"background-color:white;color:black; font-size: 13px; padding: 5px;;\">
									<td width=\"140\" align=\"left\"> $className </td>
									<td width=\"130\" align=\"left\"> $teacherName </td>
									<td width=\"55\" align=\"left\"> $Q1 </td>
									<td width=\"55\" align=\"left\"> $Q2 </td>
									<td width=\"55\" align=\"left\"> $Q3 </td>
									<td width=\"55\" align=\"left\"> $Q4 </td>
									<td width=\"60\" align=\"left\"> $S1 </td>
									<td width=\"60\" align=\"left\"> $S2 </td>
								</tr>
							";
					}
					writeTranscriptPDF($studentName, $studentGradeLevel, $academicYear, $studentGPA , $tblBody, $pdf); 
				}
				else
				{
   					$_SESSION['fail'] = 'Manual Report Card Generation failed, data not sent';
				   	header('Location: ../../pages/generateManualReportCard');
				}
			}
			else
			{
				$_SESSION['fail'] = 'Manual Report Card Generation failed, not CSV';
			   	header('Location: ../../pages/generateManualReportCard');
			}
    	}
		else
		{
			$_SESSION['fail'] = 'Manual Report Card Generation failed, CSV upload issue';
		   	header('Location: ../../pages/generateManualReportCard');
		}
	}
}

function writeTranscriptPDF($studentName, $studentGradeLevel, $academicYear, $CumulativeGPA , $tblBody, $pdf)
{

// add a page
$pdf->AddPage();
//font type and size for Transcipt. 
$pdf->SetFont('times', '', 20);
$pdf->Write(0, 'Report Card', '', 0, 'L', true, 0, false, false, 0);
//font type and size for student info
$pdf->SetFont('times', '', 12);
$pdf->Cell(0,8,'Student Name: '. $studentName,0,1);
$pdf->Cell(0,8,'Grade: '.$studentGradeLevel,0,1);
$pdf->Cell(0,8,'Academic Year: '.$academicYear,0,1);
$pdf->Cell(0,8,'GPA: '.$CumulativeGPA ,0,1);
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

$_SESSION['success'] = "Manual Report Card Generated";
//Close and output PDF document to the browser
$pdf->Output("$studentName-manualReportCard.pdf", 'D');

//Close and output PDF document to the filesystem
//$pdf->Output(realpath("../../../reportCardOutputs/Grade\ $studentGradeLevel/$studentName.pdf"), 'F');
	
//============================================================+
// END OF FILE
//============================================================+


}

?>
