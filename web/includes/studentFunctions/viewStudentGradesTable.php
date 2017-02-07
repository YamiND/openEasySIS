<?php 

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isStudent($mysqli)))
    {
        viewStudentGradesTable($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewStudentGradesTable($mysqli)
{
    $studentID = $_SESSION['userID'];
	$yearID = getClassYearID($mysqli);

    if ($stmt = $mysqli->prepare("SELECT studentClassIDs.classID, className FROM studentClassIDs, classes WHERE studentID = ? AND schoolYearID = ?"))
    {
        $stmt->bind_param('ii', $studentID, $yearID);

        $stmt->execute();
        $stmt->bind_result($classID, $className);

        $stmt->store_result();

        while($stmt->fetch())
        {
	    $classGrade = getClassGrade($studentID, $classID, $mysqli);	
            echo '
                    <!-- /.row -->
                        <div class="row">
                            <div class="col-lg-12">
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
        echo "You are not a student!";
        return;
    }   
}

?>
