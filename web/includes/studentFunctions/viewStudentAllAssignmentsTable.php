<?php

//TODO: Test this after adding multiple students to a class
function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isStudent($mysqli)))
    {
        viewGradeForStudentTable($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewGradeForStudentTable($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	     ';
						// Call Session Message code and Panel Heading here
                        displayPanelHeading("View Grade for Student");
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#addMaterialType" data-toggle="tab">Student Grades</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="selectAssignment">';

                            
                                    viewStudentAssignments($_SESSION['userID'], $mysqli);
echo '
                                </div>
                ';
echo '
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
			</div>
';

}

function viewStudentAssignments($studentID, $mysqli)
{
	$yearID = getClassYearID($mysqli);

	if ($stmt = $mysqli->prepare("SELECT studentClassIDs.classID, classes.className FROM studentClassIDs INNER JOIN (classes) ON (classes.classID = studentClassIDs.classID AND studentClassIDs.studentID = ? AND classes.schoolYearID = ?)"))
	{
    	$stmt->bind_param('ii', $studentID, $yearID);
        $stmt->execute();
        $stmt->bind_result($classID, $className);
        $stmt->store_result();


		while ($stmt->fetch())
		{
			echo '
				<h4> ' . $className . ' </h4>
                                <table width="100%" class="table table-striped table-bordered table-hover" id="' . $studentID . '">
                                    <thead>
                                        <tr>
                                            <th>Assignment Name</th>
                                            <th>Assignment Due Date</th>
                                            <th>Assigment Type</th>
                                            <th>Points Scored</th>
                                            <th>Points Possible</th>
                                            <th>Assignment Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
	        ';          
					getAssignmentsForStudentClass($studentID, $classID, $mysqli);
		    echo ' 
                                    </tbody>
                                </table>
                                <!-- /.table-responsive -->

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
}

function getAssignmentsForStudentClass($studentID, $classID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT materialID, materialName, materialPointsPossible, materialDueDate, materialTypeID FROM materials WHERE materialClassID = ?"))
    {
        $stmt->bind_param('i', $classID);
        $stmt->execute();
        $stmt->bind_result($materialID, $materialName, $materialPointsPossible, $materialDueDate, $materialTypeID);
        $stmt->store_result();

		while ($stmt->fetch())
		{
			$materialPointsScored = getMaterialPointsScored($materialID, $classID, $studentID, $mysqli);

			$materialTotalPoints = ($materialPointsScored / $materialPointsPossible) * 100;

			 echo '
                    <tr class="gradeA">
                        <td>' . $materialName. '</td>
                        <td>' . $materialDueDate . '</td>
                        <td>' . getMaterialTypeName($materialTypeID, $mysqli) . '</td>
                        <td>' . $materialPointsScored . '</td>
                        <td> /' . $materialPointsPossible . '</td>
                        <td>' . number_format((float)$materialTotalPoints, 2, '.', '') . '%</td>
                    </tr>
                ';
		
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

?>
