<?php
include_once '../dbConnect.php';
include_once '../functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

//TODO Test this
if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	exportAllCSV($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Data Export Failed, invalid permissions';
   	header('Location: ../../pages/exportAllData');
}

function exportAllCSV($mysqli)
{
	$date = date('Y-m-d');

	$filename = "allDataExport-$date.csv";
	$fp = fopen('php://output', 'w');

	header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename='.$filename);

	if ($stmt = $mysqli->prepare("SHOW tables"))
	{
		if ($stmt->execute())
		{
			$stmt->bind_result($tableName);

			$stmt->store_result();

			while ($stmt->fetch())
			{
				// Put the table name in the CSV
				fputcsv($fp, array("Table Name:", "$tableName"));

				// Put the column names in the CSV
				fputcsv($fp, getColumnNames($tableName, $mysqli));

				if ($result = $mysqli->query("SELECT * FROM $tableName")) 
				{
				    /* fetch object array */
				    while ($row = $result->fetch_row()) 
					{
						// Put the entire row in the CSV
						// This makes me sick
						fputcsv($fp, $row);
				    }

				    /* free result set */
				    $result->close();
				}
			}
		}
	}
}

function getColumnNames($tableName, $mysqli)
{
	$columnArray = [];

	if ($stmt = $mysqli->prepare("SELECT column_name FROM information_schema.columns WHERE table_schema = 'openEasySIS' AND table_name = ?"))
	{
		$stmt->bind_param('s', $tableName);

		if ($stmt->execute())
		{
			$stmt->bind_result($columnName);
			$stmt->store_result();

			while ($stmt->fetch())
			{
				array_push($columnArray, $columnName);
			}
		}
	}
	return $columnArray;
}

?>
