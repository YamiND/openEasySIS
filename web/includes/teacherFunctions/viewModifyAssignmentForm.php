<?php

if (isset($_POST['classID']))
{
    $_SESSION['classID'] = $_POST['classID'];
}

if (isset($_POST['assignmentID']))
{
    $_SESSION['assignmentID'] = $_POST['assignmentID'];
}

if (isset($_POST['changeClass']))
{
    unset($_SESSION['classID']);
    unset($_SESSION['assignmentID']);
}

if (isset($_POST['changeMaterial']))
{
    unset($_SESSION['assignmentID']);
}

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 3))
    {
        viewModifyAssignmentForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewModifyAssignmentForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						displayPanelHeading("Modify an Assignment");
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#modifyClass" data-toggle="tab">Modify an Assignment</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <h4>Modify Assignment Information</h4>
                                <div class="tab-pane fade in active" id="modifyAssignment">';

                             if ((getClassNumber($mysqli) > 1) && (!isset($_SESSION['classID'])))
                            {
                                getClassForm($mysqli);
                            }
                            else if (!isset($_SESSION['classID']))
                            {
                                $_SESSION['classID'] = getClassID($mysqli);
                            }
                                
                            if ((isset($_SESSION['classID'])) && (!isset($_SESSION['assignmentID'])))
                            {
                                chooseAssignmentForm($_SESSION['classID'], $mysqli);
                            }

                            if ((isset($_SESSION['classID'])) && (isset($_SESSION['assignmentID'])))
                            {
                                getAssignmentForm($_SESSION['classID'], $_SESSION['assignmentID'], $mysqli);
                            }

                            if (isset($_SESSION['classID']))
                            {
                                echo "<br>";
                                generateFormStart("", "post"); 
                                    generateFormButton("changeClass", "Change Class");
                                generateFormEnd();
                                echo "<br>";
                            }

                            if (isset($_SESSION['assignmentID']))
                            {
                                generateFormStart("", "post"); 
                                    generateFormButton("changeMaterial", "Change Assignment");
                                generateFormEnd();
                                echo "<br>";
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

function getAssignmentForm($classID, $assignmentID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT materialName, materialPointsPossible, materialDueDate, materialTypeID FROM materials WHERE materialID = ?"))
    {
        $stmt->bind_param('i', $assignmentID);
        $stmt->execute();
        $stmt->bind_result($materialName, $materialPointsPossible, $materialDueDate, $materialTypeID);
        $stmt->store_result();

        while ($stmt->fetch())
        {
            generateFormStart("../includes/teacherFunctions/modifyAssignment", "post"); 
                generateFormHiddenInput("materialID", $assignmentID);
                generateFormInputDiv("Assignment Name", "text", "materialName", $materialName);
                generateFormInputDiv("Assignment Points Possible", "number", "materialPointsPossible", $materialPointsPossible, NULL, NULL, NULL, NULL, "100");
                generateFormInputDiv("Assignment Due Date", "date", "materialDueDate", $materialDueDate);
                generateFormStartSelectDiv(NULL, "materialTypeID");
                    getAssignmentTypes($classID, $materialTypeID, $mysqli);
                generateFormEndSelectDiv();
                generateFormButton("modifyAssignmentButton", "Modify Assignment");
            generateFormEnd();
        }
    }
    else
    {
        return;
    }  
}

function getAssignmentTypes($classID, $materialTypeID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT materialTypeID, materialName FROM materialType WHERE classID = ?"))
    {
        $stmt->bind_param('i', $classID);
        $stmt->execute();
        $stmt->bind_result($dbMaterialTypeID, $dbMaterialName);
        $stmt->store_result();

        if ($stmt->num_rows > 0)
        {
            while ($stmt->fetch())
            {
                if ($materialTypeID == $dbMaterialTypeID)
                {
                     generateFormOption($dbMaterialTypeID, $dbMaterialName, NULL, "selected");
                }
                else
                {
                    generateFormOption($dbMaterialTypeID, $dbMaterialName);
                }
            }
        }
        else
        {
            generateFormOption(NULL, "No Assignments", "disabled", "selected");
        }
    }
    else
    {
        generateFormOption(NULL, "No Assignments", "disabled", "selected");
    }
}

function chooseAssignmentForm($classID, $mysqli)
{
    generateFormStart("", "post"); 
        generateFormHiddenInput("classID", $classID);
        generateFormStartSelectDiv(NULL, "assignmentID");
            getAssignmentList($classID, $mysqli);
        generateFormEndSelectDiv();
        generateFormButton("selectAssignmentButton", "Select Assignment");
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

        if ($stmt->num_rows > 0)
        {
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
    else
    {
        generateFormOption(NULL, "No Assignments", "disabled", "selected");
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
}

function getClassList($mysqli)
{
    $teacherID = $_SESSION['userID'];
	$yearID = getClassYearID($mysqli);

    if ($stmt = $mysqli->prepare("SELECT classID, className FROM classes WHERE classTeacherID = ? AND schoolYearID = ?"))
    {
        $stmt->bind_param('i', $teacherID);
        $stmt->execute();
        $stmt->bind_result($classID, $className, $yearID);
        $stmt->store_result();

        if ($stmt->num_rows > 0)
        {
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
