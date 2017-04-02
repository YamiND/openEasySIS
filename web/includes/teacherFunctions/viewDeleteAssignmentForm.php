<?php

if (isset($_POST['classID']))
{
	$_SESSION['classID'] = $_POST['classID'];
}

if (isset($_POST['changeClass']))
{
	unset($_SESSION['classID']);
}


function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isTeacher($mysqli)))
    {
        viewDeleteAssignmentForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewDeleteAssignmentForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						// Call Session Message code and Panel Heading here
                        displayPanelHeading("Delete an Assignment");
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#deleteAssignment" data-toggle="tab">Delete an Assignment</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
			';				
							if (isset($_SESSION['classID']))
							{
								echo '<h4>Class Name: ' . getClassName($_SESSION['classID'], $mysqli) . '</h4>';
								
							}
							else
							{
								echo "<br>";
							}
echo '                                
                                <div class="tab-pane fade in active" id="deleteAssignment">';

                               if ((getClassNumber($mysqli) > 1) && !isset($_SESSION['classID']))
                                {
                                    getClassForm($mysqli);
                                }
                               else if (getClassNumber($mysqli) == 1) 
                                {
                                    $_SESSION['classID'] = getClassID($mysqli);
                                }
                                    

                                if (isset($_SESSION['classID']))
                                {
                                    chooseAssignmentForm($_SESSION['classID'], $mysqli);
									echo "<br>";


  									generateFormStart("", "post"); 
								        generateFormButton("changeClass", "Change Class");
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

function chooseAssignmentForm($classID, $mysqli)
{
    generateFormStart("../includes/teacherFunctions/deleteAssignment", "post"); 
        generateFormStartSelectDiv("Choose Assignment", "materialID");
            getAssignmentList($classID, $mysqli);
        generateFormEndSelectDiv();
        generateFormButton(NULL, "Delete Assignment");
    generateFormEnd();
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
        generateFormOption(NULL, "No Assignments", "disabled", "selected");
    }
}

function getClassForm($mysqli)
{
    generateFormStart("", "post"); 
        generateFormStartSelectDiv("Select Class", "classID");
            getClassList($mysqli);
        generateFormEndSelectDiv();
        generateFormButton("selectClassButton", "Select Class");
    generateFormEnd();
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
    else
    {
        generateFormOption(NULL, "No Classes", "disabled", "selected");
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

function getClassID($mysqli)
{
    $teacherID = $_SESSION['userID'];

    if ($stmt = $mysqli->prepare("SELECT classID FROM classes WHERE classTeacherID = ?"))
    {
        $stmt->bind_param('i', $teacherID);
        $stmt->execute();
        $stmt->bind_result($classID);
        $stmt->store_result();

        $stmt->fetch();

        return $classID;
    }
}

?>
