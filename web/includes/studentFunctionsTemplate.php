<?php

function getStudentGradeByID($studentID, $mysqli)
{
	 if ($stmt = $mysqli->prepare("SELECT studentGradeLevel FROM users WHERE userID = ?"))
    {
        $stmt->bind_param('i', $studentID);
        $stmt->execute();
        $stmt->bind_result($studentGradeLevel);
        $stmt->store_result();

        while($stmt->fetch())
        {   
			return "$studentGradeLevel";
        }       
    }       
    else
    {   
        return;
    } 	
}

function getStudentGraduationYear($studentID, $mysqli)
{
	$studentGradeLevel = getStudentGradeByID($studentID, $mysqli);

	$schoolYearID = getClassYearID($mysqli);
	
	if ($stmt = $mysqli->prepare("SELECT schoolYearEnd FROM schoolYear WHERE schoolYearID = ?"))
	{
		$stmt->bind_param('i', $schoolYearID);

		if ($stmt->execute())
		{
			$stmt->bind_result($schoolYearEnd);
			$stmt->store_result();
			$stmt->fetch();
		}
	}

	$numYears = 12 - $studentGradeLevel;

	if ($numYears == 0)
	{
		// The student graduates at the end of the year
		return "$schoolYearEnd";
	}
	else
	{
		// Add their remaining years to the schoolyear start date
		return ($numYears + $schoolYearEnd);
	}
}


function getStudentClassSchedule($studentID, $mysqli)
{
            echo '
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">
                                        <table width="100%" class="table table-striped table-bordered table-hover" id="' . $studentID . '">
                                            <thead>
                                                <tr>
                                                    <th>Class Name</th>
                                                    <th>Teacher</th>
                                                    <th>Start Time</th>
                                                    <th>End Time</th>
                                                </tr>
                                            </thead>
											<tbody>';



	$schoolYearID = getClassYearID($mysqli);

	if ($stmt = $mysqli->prepare("SELECT className, classTeacherID, classStartTime, classEndTime FROM classes, studentClassIDs WHERE classes.classID = studentClassIDs.classID AND schoolYearID = ? AND studentID = ? ORDER BY classStartTime ASC"))
	{
		$stmt->bind_param('ii', $schoolYearID, $studentID);

		if ($stmt->execute())
		{
			$stmt->bind_result($className, $classTeacherID, $classStartTime, $classEndTime);
			$stmt->store_result();

			while ($stmt->fetch())
			{
				$teacherName = getUserName($classTeacherID, $mysqli);


				echo '<tr class="gradeA">
						<td>' . $className . '</td>
						<td>' . $teacherName . '</td>
						<td>' . $classStartTime . '</td>
						<td>' . $classEndTime . '</td>
					</tr>';				
			}
		} 
	}


	echo "</tbody>
			</table>
		</div>";
}

?>
