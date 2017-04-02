<?php

if (isset($_POST['gradeID']))
{
    $_SESSION['gradeID'] = $_POST['gradeID'];
}

if (isset($_POST['changeGradeLevel']))
{
    unset($_SESSION['gradeID']);
}

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
    {
        viewDeleteClassForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewDeleteClassForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	   ';
						displayPanelHeading("Delete Class");
    echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#deleteClass" data-toggle="tab">Delete a Class</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="deleteClass">
                                <br>
        ';

                            if (!isset($_SESSION['gradeID']))
                            {
                                getGradeLevelForm();
                                echo "<br>";
                            }
                            else
                            {
                                getClassForm($_SESSION['gradeID'], $mysqli);
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
        generateFormStartSelectDiv("Select Grade", "gradeID");
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

        generateFormStart("../includes/adminFunctions/deleteClass", "post"); 
            generateFormStartSelectDiv("Select Class", "classID");
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
            generateFormButton(NULL, "Delete Class");
        generateFormEnd();
    }       
}

?>
