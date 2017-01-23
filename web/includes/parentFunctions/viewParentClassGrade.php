<?php

//TODO: FINISH THIS

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 4))
    {
        viewParentGradesTable($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewParentGradesTable($mysqli)
{
	// three variable, parentID and studentID are suppose to grab their ID while yearID 
	// selects for which year they want to look at. may be used? 
	$parentID = $_SESSION['userID'];
    	$studentID = $_SESSION['userID'];
        $yearID = getClassYearID($mysqli);

	// selecting from the studentParentIDs table, and from their, selects which ever student
	// correspondes with the given parentID.  
    if ($stmt = $mysqli->prepare("SELECT studentParentIDs.studentID, className FROM studentClassIDs, classes WHERE studentID = ? AND schoolYearID = ?"))
    {
        $stmt->bind_param('ii', $studentID, $yearID);

        $stmt->execute();
        $stmt->bind_result($classID, $className);

        $stmt->store_result();






        while($row = mysql_fetch_array($parentID))
        {
            $classGrade = getClassGrade($studentID, $classID, $mysqli);
            echo '
                    <!-- /.row -->
                        <div class="row">
			// selects from the row
                            <div class="col-lg-12">
				// selects from specific column
                                <div class="panel panel-default">
                                    <div class="panel-heading" id="grades">
                                        Class Grades
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
        echo "You are not a teacher!";
        return;
    }
}

?>



