<?php

//TODO: FINISH THIS

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isParent($mysqli)))
    {
        getStudentIDs($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function getStudentIDs($mysqli)
{
	// three variable, parentID and studentID are suppose to grab their ID while yearID 
	// selects for which year they want to look at. may be used? 
	$parentID = $_SESSION['userID'];

// Porter: We need to grab a list of studentIDs based on who the parent is. Below will be how
//    	$studentID = $_SESSION['userID'];
        $yearID = getClassYearID($mysqli);


	if ($stmt = $mysqli->prepare("SELECT studentID FROM studentParentIDs WHERE parentID = ?"))
	{
		$stmt->bind_param('i', $parentID);
		if ($stmt->execute())
		{
			$stmt->bind_result($studentID);

			$stmt->store_result();

			if ($stmt->num_rows > 0)
			{
				while ($stmt->fetch())
				{
					getGradesForStudent($studentID, $mysqli);
				}		
			}
			else
			{
				echo "<p>No students assigned to account</p>";
			}
		}
		else
		{
			echo "<p>No students assigned to account</p>";
		}
	}
	else
	{
		echo "<p>No students assigned to account</p>";
	}
}

function getGradesForStudent($studentID, $mysqli)
{
	$yearID = getClassYearID($mysqli);

	// selecting from the studentParentIDs table, and from their, selects which ever student
	// correspondes with the given parentID.  
    if ($stmt = $mysqli->prepare("SELECT studentClassIDs.classID, className FROM studentClassIDs, classes WHERE studentClassIDs.studentID = ? AND schoolYearID = ?"))
    {

	// searches using the parameters of both the parents and students ID's
        $stmt->bind_param('ii', $studentID, $yearID);

        $stmt->execute();

	// binds className and class grade together as a result
        $stmt->bind_result($classID, $className);

        $stmt->store_result();

// Porter, $stmt is mysqli, so instead you would do this:

		while ($stmt->fetch())
        {
            $classGrade = getClassGrade($studentID, $classID, $mysqli);
			$studentName = getUserName($studentID, $mysqli);

            echo '
                    <!-- /.row -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading" id="grades">
                                        Student Name: ' . $studentName . '
                                    </div>
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">
                                        <table width="100%" class="table table-striped table-bordered table-hover" id="' . $studentID . '">
                                            <thead>
                                                <tr>
                                                    <th>Class Name</th>
                                                    <th>Grade</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                        <tr class="gradeA">
                                        <td>' . $className . '</td>
                                       <td>' . $classGrade . '%</td>
					   </tr>
                                            </tbody>
                                        </table>
                                        <!-- /.table-responsive -->
                                    </div>
                                    <!-- /.panel-body -->
                                </div>
                                <!-- /.panel -->
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>
                        <!-- /.row -->


                        <!-- Page-Level Demo Scripts - Tables - Use for reference -->
                        <script>
                        $(document).ready(function() {
                            $(\'#' . $studentID . '\').DataTable({
                                responsive: true
                            });
                        });
                        </script>
                ';
        }
    }
    else
 {
        echo "You are not a parent!";
        return;
    }
}

?>
