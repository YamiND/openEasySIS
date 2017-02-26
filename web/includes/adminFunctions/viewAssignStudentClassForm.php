<?php

if (isset($_POST['gradeID']))
{
    $_SESSION['gradeID'] = $_POST['gradeID'];
}

if (isset($_POST['classID']))
{
    $_SESSION['classID'] = $_POST['classID'];
}

if (isset($_POST['changeGradeLevel']))
{
    unset($_SESSION['gradeID']);
    unset($_SESSION['classID']);
}

if (isset($_POST['changeClass']))
{
    unset($_SESSION['classID']);
}
//TODO: Test this after adding multiple students to a class

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
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
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	     ';
						// Call Session Message code and Panel Heading here
                        displayPanelHeading("Assign Student to Class");
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#assignStudent" data-toggle="tab">Assign Student to Class</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">';
                                if (isset($_SESSION['classID']))
                                {
                                    echo '<h4>Class Name: ' . getClassName($_SESSION['classID'], $mysqli) . '</h4>';
                                }
                                else if (isset($_SESSION['gradeID']) && (getClassNumber($_SESSION['gradeID'], $mysqli) > 0))
                                {
                                    echo '<h4>Select Class</h4>';
                                }
                                else
                                {
                                    echo '<h4>Select Grade Level</h4>';
                                }
echo '
                                <div class="tab-pane fade in active" id="selectAssignment">';

                                if (!isset($_SESSION['gradeID']))
                                {       
                                    getGradeLevelForm();    
                                }
                                else if ((isset($_SESSION['gradeID'])) && (getClassNumber($_SESSION['gradeID'], $mysqli) == 0))
                                {
                                    echo "<h3>No Classes for Grade Level, Select Another Class </h3>";
                                }
                                else if (!isset($_SESSION['classID']))
                                {
                                    getClassForm($_SESSION['gradeID'], $mysqli);
                                }
                                else
                                {
                                    assignStudentForm($_SESSION['classID'], $_SESSION['gradeID'], $mysqli);
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

                                if (isset($_SESSION['gradeID']))
                                {
                                    generateFormStart("", "post"); 
                                        generateFormButton("changeGradeLevel", "Change Grade Level");
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

function assignStudentForm($classID, $gradeID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT userID FROM users WHERE userID IN (SELECT userID from users WHERE studentGradeLevel = ?) AND userID NOT IN (SELECT studentID FROM studentClassIDs WHERE classID LIKE ?);"))
    {
        $stmt->bind_param('ii', $gradeID, $classID);
        $stmt->execute();
        $stmt->bind_result($studentID);
        $stmt->store_result();

        generateFormStart("../includes/adminFunctions/assignStudentClass", "post"); 
            generateFormHiddenInput("classID", $classID);
            generateFormStartSelectDiv(NULL, "studentID");
            if ($stmt->num_rows > 0)
            {
                while ($stmt->fetch())
                {
                    getUserName($studentID, $mysqli); 
                }
            }
            else
            {
                generateFormOption(NULL, "No Students", "disabled", "selected");
            }
            generateFormEndSelectDiv();
            generateFormButton(NULL, "Add Student to Class");
        generateFormEnd();
    }
}

function getGradeLevelForm()
{
    generateFormStart("", "post"); 
        generateFormStartSelectDiv(NULL, "gradeID");
            for ($i = 1; $i <= 12; $i++)
            {
                generateFormOption($i, $i);
            }
        generateFormEndSelectDiv();
        generateFormButton("gradeButton", "Select Grade Level");
    generateFormEnd();
}

function getClassForm($gradeID, $mysqli)
{   
	$yearID = getClassYearID($mysqli);
    if ($stmt = $mysqli->prepare("SELECT classID, className FROM classes WHERE classGrade = ? AND schoolYearID = ?"))
    {
        $stmt->bind_param('ii', $gradeID, $yearID);
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

function getClassNumber($gradeID, $mysqli)
{
    // The below is required to get a num_rows result
    if ($stmt = $mysqli->prepare("SELECT classID FROM classes WHERE classGrade = ?"))
    {
        $stmt->bind_param('i', $gradeID);
        $stmt->execute();
        
        $stmt->store_result();

        return $stmt->num_rows;
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
