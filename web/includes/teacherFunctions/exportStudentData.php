<?php
include_once '../dbConnect.php';
include_once '../functions.php';
include_once '../userFunctionsTemplate.php';
include_once '../classFunctionsTemplate.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

//TODO Test this
if ((login_check($mysqli) == true) && (isTeacher($mysqli)))
{
	exportCSV($mysqli);
}
else
{
   	$_SESSION['fail'] = 'Account Creation Failed, invalid permissions';
   	header('Location: ../../pages/createBulkUser');
}

function exportCSV($mysqli)
{
	if (isset($_POST['classID']) && !empty($_POST['classID']))
	{
		$teacherID = $_SESSION['userID'];
		$classID = $_POST['classID'];
		$className = getClassName($classID, $mysqli);

		$date = date('Y-m-d');

		$filename = "studentExport-$date.csv";
		$fp = fopen('php://output', 'w');

		// Add Class Name to the CSV
		fputcsv($fp, array("Class Name: ", $className));

		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);

		if ($stmt = $mysqli->prepare("SELECT studentID FROM studentClassIDs WHERE classID = ?"))
		{
			$stmt->bind_param('i', $classID);

			if ($stmt->execute())
			{
				$stmt->bind_result($studentID);
				$stmt->store_result();

				while ($stmt->fetch())
				{
					$studentName = getUserName($studentID, $mysqli);

					// Add Student's Name to the CSV
					fputcsv($fp, array("Student's Name:", $studentName));
					fputcsv($fp, array("Student's Class Grade:", getClassGrade($studentID, $classID, $mysqli) . "%"));

					// Add Assignment Header
					fputcsv($fp, array("Assignment Name", "Assignment Due Date", "Assignment Type", "Points Scored", "Points Possible", "Assignment Grade"));

					if ($stmt2 = $mysqli->prepare("SELECT materialID, materialName, materialPointsPossible, materialDueDate, materialTypeID FROM materials WHERE materialClassID = ?"))
					{   
						$stmt2->bind_param('i', $classID);
						if ($stmt2->execute())
						{	
							$stmt2->bind_result($materialID, $materialName, $materialPointsPossible, $materialDueDate, $materialTypeID);
							$stmt2->store_result();

							while ($stmt2->fetch())
							{   
								$materialPointsScored = getMaterialPointsScored($materialID, $classID, $studentID, $mysqli);

								$materialTotalPoints = ($materialPointsScored / $materialPointsPossible) * 100;

								// Add grade Info
								fputcsv($fp, array("$materialName", "$materialDueDate", getMaterialTypeName($materialTypeID, $mysqli), $materialPointsScored, "/" . $materialPointsPossible, number_format((float) $materialTotalPoints, 2, '.', '') . '%'));
							}
						}   
					} 
				}
			}
		}
	}
}

function getMaterialTypeName($materialTypeID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT materialName FROM materialType WHERE materialTypeID = ?"))
    {   
        $stmt->bind_param('i', $materialTypeID);
        $stmt->execute();
        $stmt->bind_result($materialTypeNameResult);
        $stmt->store_result();

    if ($stmt->num_rows > 0)
    {
        while ($stmt->fetch())
        {
            return $materialTypeNameResult;
        }
    }
    else
    {
        return "No Assignment Types";
    }

    }
}

function getClassName($classID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT className FROM classes WHERE classID = ?"))
    {   
        $stmt->bind_param('i', $classID);
        $stmt->execute();
        $stmt->bind_result($className);
        $stmt->store_result();

        while($stmt->fetch())
        {   
            return $className;    
        }    
    }   
    else
    {   
        return "0";
    }   
}
?>
