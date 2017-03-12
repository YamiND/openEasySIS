<?php 
require('../html_table2/html_table.php');
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Report Card</title>


<style>
	
		table {
			width:100%;
		}
			table, th, td {
			border: 1px solid black;
			border-collapse: collapse;
		}
			th, td {
			padding: 5px;
			text-align: left;
		}
			table#t01 tr:nth-child(even) {
			background-color: #fff;
		}
			table#t01 tr:nth-child(odd) {
	   		background-color:#fff;
		}
			table#t01 th {
			background-color: gray;
			color: white;
		}
	</style>
	

			<div>
				<p><center><font size="6"> Report Card</font> </center></p>
					<center><img src="http://www.clker.com/cliparts/Z/F/Y/E/X/y/logo-school-hi.png" align=center alt="School Logo"  style="width:100px;height:100px;">			
							</center>
							
						<p> <center> 
						 Maplewood Baptist Academy
						   3255 West M-80 Kinross, MI 49752 </center></p> 
							
					<hr>	
				
							<br>
										
							<tr>
                           	<th><p>Student Name: </p>
							  </th>
                           
                           <th> <p>Grade: </p>
							  </th>
                           
                           <th>
                           		 <p>Academic Year: </p>
							  </th>
							</tr>
                            <br>
                        
		</head>
<body>

			<br>

				<table width="90%" id="t01">
  			<tr>
	  		  <th width="26%">SUBJECT AREA</th>
	    	  <th width="8%">1st Qtr</th> 
			  <th width="8%">2nd Qtr</th>
	     	  <th width="8%">3rd Qtr</th>
        	  <th width="8%">4th Qtr</th>
  			</tr>
  			<tr>
	  			<td>Class1</td>
	  			<td>0</td>
	  			<td>50</td>
	  			<td>100</td>
	  			<td>0</td>
  			</tr>
  			<tr>
	  			<td>Class2</td>
	  			<td>0</td>
	  			<td>50</td>
	  			<td>0</td>
	  			<td>0</td>
  			</tr>
  			<tr>
	  			<td>Class3</td>
	  			<td>1</td>
	  			<td>50</td>
	  			<td>0</td>
	  			<td>0</td>
 		   </tr>
			
			</table>
			
	<br>
	
</body>
	
</html>

$pdf=new PDF_HTML_Table();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->WriteHTML("Start of the HTML table.<br>$htmlTable<br>End of the table.");
$pdf->Output();
?>



