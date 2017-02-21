<?php

if (isset($_POST['gradeID']))
{
    // After the user selects an grade, set it as a $_SESSION variable
    $_SESSION['gradeID'] = $_POST['gradeID'];
}

if (isset($_POST['classID']))
{
    // After the user selects an class, set it as a $_SESSION variable
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

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
    {
        viewModifyClassForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewModifyClassForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						 displayPanelHeading("Modify a Class");
    echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#modifyClass" data-toggle="tab">Modify a Class</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="modifyClass">
                                    <br>
        ';

                            if (!isset($_SESSION['gradeID']))
                            {
					           getGradeLevelForm();
                            }
                            else if (!isset($_SESSION['classID']))
                            {
                                getClassForm($_SESSION['gradeID'], $mysqli);
                            }
                            else
                            {
                                getClassInfo($_SESSION['classID'], $mysqli);
                            }

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
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
			</div>
        ';
}

function getGradeLevelForm()
{
    generateFormStart("", "post"); 
        generateFormStartSelectDiv("Grade Level", "gradeID");
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
    if ($stmt = $mysqli->prepare("SELECT classID, className FROM classes WHERE classGrade = ?"))
    {
        $stmt->bind_param('i', $gradeID);
        $stmt->execute();
        $stmt->bind_result($classID, $className);
        $stmt->store_result();

        generateFormStart("", "post"); 
            generateFormStartSelectDiv("Class Name", "classID");
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
        echo "<br>";
    }       
}

function getClassInfo($classID, $mysqli)
{
	$yearID = getClassYearID($mysqli);

    if($stmt = $mysqli->prepare("SELECT classGrade, className, classTeacherID FROM classes WHERE classID = ? AND schoolYearID = ?"))
    {
        $stmt->bind_param('ii', $classID, $yearID);

        $stmt->execute();

        $stmt->bind_result($classGrade, $className, $classTeacherID);
        $stmt->store_result();

        while ($stmt->fetch())
        {
            generateFormStart("../includes/adminFunctions/modifyClass", "post"); 
                generateFormHiddenInput("classID", $classID);
                generateFormInputDiv("Class", "text", "className", $className, NULL, NULL, NULL, "Class Name");
                generateFormStartSelectDiv("Grade Level", "classGradeLevel");
                    for ($i = 1; $i <= 12; $i++)
                    {
                        if ($i == $classGrade)
                        {
                            generateFormOption($classGrade, $classGrade, NULL, "selected");
                        }
                        else
                        {
                            generateFormOption($i, $i);
                        }
                    }
                generateFormEndSelectDiv();
                generateFormStartSelectDiv("Teacher", "classTeacherID");
                    getTeacherList($classTeacherID, $mysqli);
                generateFormEndSelectDiv();
                generateFormButton(NULL, "Modify Class Information");
            generateFormEnd();
            echo "<br>";
        }
    }
}

function getTeacherList($selected = NULL, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT userID, userFirstName, userLastName FROM users WHERE isTeacher"))
    {
        $stmt->execute();
        $stmt->bind_result($dbTeacherID, $dbTeacherFirstName, $dbTeacherLastName);
        
        $stmt->store_result();

        while ($stmt->fetch())
        {
            if ($dbTeacherID == $selected)
            {   
                generateFormOption($dbTeacherID, "$dbTeacherLastName, $dbTeacherFirstName", NULL, "selected");
            }   
            else
            {   
                generateFormOption($dbTeacherID, "$dbTeacherLastName, $dbTeacherFirstName");
            }   
        }
    }
}

?>
