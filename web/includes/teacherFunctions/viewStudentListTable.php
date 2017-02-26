<?php 

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isTeacher($mysqli)))
    {
        viewStudentList($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewStudentList($mysqli)
{

    $teacherID = $_SESSION['userID'];
	$yearID = getClassYearID($mysqli);

    if ($stmt = $mysqli->prepare("SELECT classID, className FROM classes WHERE classTeacherID = ? AND schoolYearID = ?"))
    {
        $stmt->bind_param('ii', $teacherID, $yearID);

        $stmt->execute();
        $stmt->bind_result($classID, $className);

        $stmt->store_result();

        while($stmt->fetch())
        {
            echo '
                    <!-- /.row -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading" id="'. $className . '"> 
                                        Class Name: ' . $className . '
                                    </div>
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">
                                        <table width="100%" class="table table-striped table-bordered table-hover" id="' . $classID . '">
                                            <thead>
                                                <tr>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Email</th>
													<th>Grade</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                ';
                                                getStudentID($classID, $mysqli);
            echo ' 
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
                            $(\'#' . $classID . '\').DataTable({
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

function getStudentID($classID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT studentID FROM studentClassIDs WHERE classID = ?"))
	{
        $stmt->bind_param('i', $classID);
		$stmt->execute();
		$stmt->bind_result($studentID);
		$stmt->store_result();

		while($stmt->fetch())
		{
            getStudentInfo($studentID, $classID, $mysqli);       
		}			
	}
	else
	{
        echo "No students in Class";
		return;
	}
}

function getStudentInfo($studentID, $classID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT userFirstName, userLastName, userEmail FROM users WHERE studentID = ?"))
    {
        $stmt->bind_param('i', $studentID);
        $stmt->execute();
        $stmt->bind_result($studentFirstName, $studentLastName, $studentEmail);
        $stmt->store_result();

		$studentGrade = getClassGrade($studentID, $classID, $mysqli);

        while($stmt->fetch())
        {       
            echo '
                    <tr class="gradeA">
                        <td>' . $studentFirstName . '</td>
                        <td>' . $studentLastName . '</td>
                        <td>' . $studentEmail . '</td>
						<td>' . $studentGrade . '%</td>
                    </tr>
                ';
        }           
    }
    else
    {
        return;
    }
}

?>
