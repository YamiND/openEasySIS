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
    if ((login_check($mysqli) == true) && (canModClassList($mysqli)))
    {
        viewAssignStudentClassForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewAssignStudentClassForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	     ';
						// Call Session Message code and Panel Heading here
                        displayPanelHeading("Remove Student from Class");
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#assignStudent" data-toggle="tab">Remove Student from Class</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">';
		echo "<h1>Please note that the student grades will be deleted for the class</h1>";
		echo "<h1>This will affect their GPA and transcript (if they have grades in that class)!</h1>";
		echo "<br>";
                                if (isset($_SESSION['classID']))
                                {
                                    echo '<h4>Class Name: ' . getClassName($_SESSION['classID'], $mysqli) . '</h4>';
                                }
                                else
                                {
                                    echo '<h4>Select Class</h4>';
                                }
echo '
                                <div class="tab-pane fade in active" id="selectAssignment">';

                                if (!isset($_SESSION['classID']))
                                {
                                    getClassForm($_SESSION['userID'], $mysqli);
                                }
                                else
                                {
                                    unAssignStudentForm($_SESSION['classID'], $mysqli);
                                }
echo '
                                </div>
                                <br>
                                ';

                                if (isset($_SESSION['classID']))
                                {
                                    generateFormStart("", "post"); 
                                        generateFormButton("changeClass", "Change Class");
                                    generateFormEnd();

                                    echo "<br>";
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

function unAssignStudentForm($classID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT studentID FROM studentClassIDs WHERE classID = ?"))
    {
        $stmt->bind_param('i', $classID);
        $stmt->execute();
        $stmt->bind_result($studentID);
        $stmt->store_result();


        generateFormStart("../includes/teacherFunctions/unAssignStudentTeacher", "post"); 
            generateFormHiddenInput("classID", $classID);
            generateFormStartSelectDiv("Student's Name: ", "studentID");
            if ($stmt->num_rows > 0)
            {
                while ($stmt->fetch())
                {
                    generateFormOption($studentID, getUserName($studentID, $mysqli)); 
                }
            }
            else
            {
                generateFormOption(NULL, "No Students", "disabled", "selected");
            }
            generateFormEndSelectDiv();
            generateFormButton(NULL, "Remove Student from Class");
        generateFormEnd();
    }
}

function getClassForm($teacherID, $mysqli)
{   
	$yearID = getClassYearID($mysqli);
    if ($stmt = $mysqli->prepare("SELECT classID, className FROM classes WHERE schoolYearID = ? AND classTeacherID = ?"))
    {
        $stmt->bind_param('ii', $yearID, $teacherID);
        $stmt->execute();
        $stmt->bind_result($classID, $className);
        $stmt->store_result();

        generateFormStart("", "post"); 
            generateFormStartSelectDiv(NULL, "classID");
            if ($stmt->num_rows > 0)
            {
                while ($stmt->fetch())
                {
                    generateFormOption($classID, $className);
                }
            }
            else
            {
                generateFormOption(NULL, "No Classes", "disabled", "selected");
            }
            generateFormEndSelectDiv();
            generateFormButton(NULL, "Select Class");
        generateFormEnd();
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

        $stmt->fetch();

        return $className;
    }
}

?>
