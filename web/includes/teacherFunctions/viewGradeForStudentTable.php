<?php
if (isset($_POST['classID']))
{
    $_SESSION['classID'] = $_POST['classID'];
}

if (isset($_POST['studentID']))
{
    $_SESSION['studentID'] = $_POST['studentID'];
}

if (isset($_POST['changeStudent']))
{
    unset($_SESSION['studentID']);
}

if (isset($_POST['changeClass']))
{
    unset($_SESSION['classID']);
    unset($_SESSION['studentID']);
}

//TODO: Test this after adding multiple students to a class
function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 3))
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
                            <div class="tab-content">';

                                if ((getClassNumber($mysqli) > 1) && (!isset($_SESSION['classID'])) && (!empty($_SESSION['classID'])))
                                {
                                    echo "<h4>Select Class</h4>";
                                }
                                else if (isset($_SESSION['studentID']))
                                {
                                    echo '<h4>' .  getStudentName($_SESSION['studentID'], $mysqli) . '    -----  Grade: ' . getClassGrade($_SESSION['studentID'], $_SESSION['classID'], $mysqli) . '%</h4>';
                                }
                                else
                                {
                                    echo '<h4>Select Student</h4>';
                                }

                            echo '
                                
                                <div class="tab-pane fade in active" id="selectAssignment">';

                            
                               if ((getClassNumber($mysqli) > 1) && (!isset($_SESSION['classID'])) && !empty($_SESSION['classID']))
                                {
                                    getClassForm($mysqli);
                                }
                                else if (getClassNumber($mysqli) < 2)
                                {
                                    $_SESSION['classID'] = getClassID($mysqli);
			
                                }

                                if ((isset($_SESSION['classID'])) && (!isset($_SESSION['studentID'])))
                                {
                                    chooseStudentForm($_SESSION['classID'], $mysqli);
                                }

                                if (isset($_SESSION['studentID']))
                                {
                                    viewStudentGrades($_SESSION['studentID'], $_SESSION['classID'], $mysqli);
                                }
echo '
                                </div>
                ';

                                if (isset($_SESSION['studentID']))
                                {
                                    generateFormStart("", "post");
                                        generateFormButton("changeStudent", "Change Student");
                                    generateFormEnd();
                                    echo "<br>";
                                }

                                if ((getClassNumber($mysqli) > 1) && isset($_SESSION['classID']))
                                {
                                    generateFormStart("", "post");
                                        generateFormButton("changeClass", "Change Class");
                                    generateFormEnd();
                                }
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

function viewStudentGrades($studentID, $classID, $mysqli)
{
    echo '
           
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
                        <td>' . $materialTotalPoints . '%</td>
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

function chooseStudentForm($classID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT studentID FROM studentClassIDs WHERE classID = ?"))
    {
        $stmt->bind_param('i', $classID);
        $stmt->execute();
        $stmt->bind_result($studentID);
        $stmt->store_result();

        generateFormStart("", "post");
        	generateFormStartSelectDiv(NULL, "studentID");
        while($stmt->fetch())
        {
            getStudentInfo($classID, $studentID, $mysqli);       
        }           
		    generateFormEndSelectDiv();
            generateFormButton("selectStudent", "Select Student");
        generateFormEnd();
    }
    else
    {
        echo "No students in Class";
        return;
    }
}

function getStudentInfo($classID, $studentID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT studentFirstName, studentLastName FROM studentProfile WHERE studentID = ?"))
    {
        $stmt->bind_param('i', $studentID);
        $stmt->execute();
        $stmt->bind_result($studentFirstName, $studentLastName);
        $stmt->store_result();

        while($stmt->fetch())
        {       
        	generateFormOption($studentID, "$studentLastName, $studentFirstName");
        }           
    }
    else
    {
        return;
    }
}

function getStudentName($studentID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT studentFirstName, studentLastName FROM studentProfile WHERE studentID = ?"))
    {
        $stmt->bind_param('i', $studentID);
        $stmt->execute();
        $stmt->bind_result($studentFirstName, $studentLastName);
        $stmt->store_result();

        while($stmt->fetch())
        {       
			return "$studentLastName, $studentFirstName";
        }           
    }
    else
    {
        return;
    }
}

function getClassForm($mysqli)
{
    generateFormStart("", "post");      
        generateFormStartSelectDiv(NULL, "classID");
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
		            generateFormOption($classID, $className);
   			    }
		    }
        generateFormEndSelectDiv();
        generateFormButton("selectClassButton", "Select Class");
    generateFormEnd();
    echo "<br>";
}

function getClassNumber($mysqli)
{
    $teacherID = $_SESSION['userID'];

    if ($stmt = $mysqli->prepare("SELECT classID FROM classes WHERE classTeacherID = ?"))
    {
        $stmt->bind_param('i', $teacherID);

        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0)
        {
            return $stmt->num_rows;
        }
        else
        {
            return 0;
        }
    }
}

function getClassID($mysqli)
{
	$yearID = getClassYearID($mysqli);
    $teacherID = $_SESSION['userID'];

    if ($stmt = $mysqli->prepare("SELECT classID FROM classes WHERE classTeacherID = ? AND schoolYearID = ? LIMIT 1"))
    {
        $stmt->bind_param('ii', $teacherID, $yearID);
        $stmt->execute();
        $stmt->bind_result($classID);
        $stmt->store_result();

        $stmt->fetch();

        return $classID;
    }
}

?>
