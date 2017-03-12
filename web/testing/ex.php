<?php
require('../fpdf181/html_table.php');

$textHeader='<br><br><br><br><br><br>';

$htmlTable='<table>
<tr>
<td>Class</td>
<td>Teacher</td>
<td>Q1</td>
<td>Q2</td>
<td>Q3</td>
<td>Q4</td>
<td>S1</td>
<td>S2</td>
</tr>

<tr>
<td>Math</td>
<td>Joe </td>
<td>A</td>
<td>B</td>
<td>B</td>
<td>B</td>
<td>A-</td>
<td>A+</td>
</tr>

<tr>
<td>Math</td>
<td>Joe </td>
<td>A</td>
<td>B</td>
<td>B</td>
<td>B</td>
<td>A-</td>
<td>A+</td>
</tr>

<tr>
<td>Math</td>
<td>Joe </td>
<td>A</td>
<td>B</td>
<td>B</td>
<td>B</td>
<td>A-</td>
<td>A+</td>
</tr>
</table>';


$textFooter='<h>';


$pdf=new PDF_HTML_Table();
$pdf->AddPage();
$pdf->SetFont('Arial','',8);
$title = ('Report Card ');
$pdf->Cell(0,0,' Report Card '.$i,0,1,'L');
$pdf->SetTitle($title);
// Insert a logo in the top-left corner at 300 dpi
$pdf->Image('logo.png',69,12,70);
$pdf->WriteHTML("$textHeader");
$pdf->Cell(0,10,' Maplewood Baptist Academy 3255 West M-80 Kinross, MI 49752 '.$i,0,1,'C');
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,'Student Name: '.$i,0,1);
$pdf->Cell(0,8,'Grade: '.$i,0,1);
$pdf->Cell(0,8,'Academic Year: '.$i,0,1);
//$pdf->SetFont('Arial','',10);
$pdf->WriteHTML("$htmlTable<br>$textFooter<b>");
$pdf->Cell(0,8,'Academic Year: '.$i,0,1);
$pdf->WriteHTML("$htmlTable<br>$textFooter<b>");
$pdf->Cell(0,8,'Academic Year: '.$i,0,1);
$pdf->WriteHTML("$htmlTable<br>$textFooter<b>");

//$pdf->SetFont('Times','',12);
$pdf->Output();
?>
