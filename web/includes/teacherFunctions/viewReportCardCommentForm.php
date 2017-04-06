<?php
if (isset($_POST['classID']))
{
    $_SESSION['classID'] = $_POST['classID'];
}

if (isset($_POST['changeClass']))
{
    unset($_SESSION['classID']);
}

//TODO: Test this after adding multiple students to a class
function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isTeacher($mysqli)))
    {
        viewReportCardCommentForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewReportCardCommentForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	     ';
						// Call Session Message code and Panel Heading here
                        displayPanelHeading("Leave Comment for Report Card");
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#addMaterialType" data-toggle="tab">Report Card Comment</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">';

                                if ((getClassNumber($mysqli) > 1) && (!isset($_SESSION['classID'])) && (empty($_SESSION['classID'])))
                                {
                                    echo "<h4>Select Class</h4>";
                                }

                            echo '
                                
                                <div class="tab-pane fade in active" id="selectAssignment">';

                            
                               if ((getClassNumber($mysqli) > 1) && (!isset($_SESSION['classID'])) && empty($_SESSION['classID']))
                                {
                                    getClassForm($mysqli);
                                }
                                else if (getClassNumber($mysqli) < 2)
                                {
                                    $_SESSION['classID'] = getClassID($mysqli);
                                }

                                if (isset($_SESSION['classID']))
                                {
                                    viewStudents($_SESSION['classID'], $mysqli);
                                }
echo '
                                </div>
                ';

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

function viewStudents($classID, $mysqli)
{
    echo '
           
                                <table width="100%" class="table table-striped table-bordered table-hover" id="' . $materialID . '">
                                    <thead>
                                        <tr>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Report Card Comment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
        ';          
                                        getClassStudentID($classID, $mysqli);
    echo ' 
                                    </tbody>
                                </table>
                                <!-- /.table-responsive -->

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

function getClassStudentID($classID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT studentID FROM studentClassIDs WHERE classID = ?"))
    {
        $stmt->bind_param('i', $classID);
        $stmt->execute();
        $stmt->bind_result($studentID);
        $stmt->store_result();

        while($stmt->fetch())
        {
            getStudentInfo($classID, $studentID, $mysqli);       
        }           
    }
    else
    {
        echo "No students in Class";
        return;
    }
}

function getStudentInfo($classID, $studentID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT userFirstName, userLastName FROM users WHERE userID = ?"))
    {
        $stmt->bind_param('i', $studentID);
        $stmt->execute();
        $stmt->bind_result($studentFirstName, $studentLastName);
        $stmt->store_result();

        while($stmt->fetch())
        {       
            echo '
                    <tr class="gradeA">
                ';
                    generateFormStart("../includes/teacherFunctions/changeReportCardComment", "post");
                        generateFormHiddenInput("classID", $classID);
                        generateFormHiddenInput("studentID", $studentID);
            echo '
                        <td>' . $studentFirstName . '</td>
                        <td>' . $studentLastName . '</td>
                        <td> '; generateFormTextAreaDiv(NULL, "reportCardComment", "5", getReportCardComment($studentID, $classID, $mysqli)); echo '</td>
                        <td>'; echo generateFormButton("applyChangesButton", "Apply Changes"); echo '</td>
                ';
                        generateFormEnd();
            echo '
                    </tr>
                ';
        }           
    }
    else
    {
        return;
    }
}

function getMaterialName($materialID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT materialName FROM materials WHERE materialID = ?"))
    {
        $stmt->bind_param('i', $materialID);
        $stmt->execute();
        $stmt->bind_result($materialName);
        $stmt->store_result();

        $stmt->fetch();

        return $materialName;
    }
    else
    {
        return "NULL";
    }
}

function chooseAssignmentForm($classID, $mysqli)
{
    generateFormStart("", "post");
        generateFormHiddenInput("classID", $classID);       
        generateFormStartSelectDiv("Assignment", "materialID");
            getAssignmentList($classID, $mysqli);
        generateFormEndSelectDiv();
        generateFormButton("selectAssignmentButton", "Select Assignment");
    generateFormEnd();
    echo "<br>";
}

function getAssignmentList($classID, $mysqli)
{
    
    if ($stmt = $mysqli->prepare("SELECT materialID, materialName FROM materials WHERE materialClassID = ?"))
    {
        $stmt->bind_param('i', $classID);
        $stmt->execute();
        $stmt->bind_result($materialID, $materialName);
        $stmt->store_result();

        while ($stmt->fetch())
        {
            generateFormOption($materialID, $materialName);
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
            getClassList($mysqli);
        generateFormEndSelectDiv();
        generateFormButton("selectClassButton", "Select Class");
    generateFormEnd();
    echo "<br>";
}

function getClassList($mysqli)
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
            generateFormOption($classID, $className);
        }
    }
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
