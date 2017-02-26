<?php
//============================================================+
// File name   : example_048.php
// Begin       : 2009-03-20
// Last Update : 2013-05-14
//
// Description : Example 048 for TCPDF class
//               HTML tables and table headers
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: HTML tables and table headers
 * @author Nicola Asuni
 * @since 2009-03-20
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

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
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(0,8,'Student Name: '.$i,0,1);
$pdf->Cell(0,8,'Grade: '.$i,0,1);
$pdf->Cell(0,8,'Academic Year: '.$i,0,1);
$pdf->SetFont('helvetica', '', 8);

// -----------------------------------------------------------------------------
// Table with rowspans and THEAD
$tbl = <<<EOD
<table border="1">
<thead>
 <tr style="background-color:white;color:black; font-size: 24px;">
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
<tr style="background-color:white;color:black; font-size: 16px; padding: 5px;;">
 <td width="140" align="left"> Math </td>
  <td width="140" align="left"> Joe </td>
  <td width="45" align="left"> A </td>
  <td width="45" align="left"> B </td>
  <td width="45" align="left"> A </td>
  <td width="45" align="left"> A </td>
  <td width="60" align="left"> B </td>
  <td width="60" align="left"> C </td>
 </tr>
<tr style="background-color:white;color:black; font-size: 16px; padding: 5px;;">
  <td width="140" align="left"> History </td>
  <td width="140" align="left"> Chris </td>
  <td width="45" align="left"> A </td>
  <td width="45" align="left"> B </td>
  <td width="45" align="left"> A </td>
  <td width="45" align="left"> A </td>
  <td width="60" align="left"> B </td>
  <td width="60" align="left"> C </td>
 </tr>
<tr style="background-color:white;color:black; font-size: 16px; padding: 5px;;">
 <td width="140" align="left"> English </td>
  <td width="140" align="left"> Kate </td>
  <td width="45" align="left"> A </td>
  <td width="45" align="left"> B </td>
  <td width="45" align="left"> A </td>
  <td width="45" align="left"> A </td>
  <td width="60" align="left"> B </td>
  <td width="60" align="left"> C </td>
 </tr>
 
</table>

EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');
//New table with new Academic Year for multiple tables like Transcript
//$pdf->Cell(0,8,'Academic Year: '.$i,0,1);
//$pdf->writeHTML($tbl, true, false, false, false, '');

//Close and output PDF document
$pdf->Output('example_048.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
