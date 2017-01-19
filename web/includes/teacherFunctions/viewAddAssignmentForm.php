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
    if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 3))
    {
        viewAddAssignmentForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
    }
}

function viewAddAssignmentForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						displayPanelHeading("Add an Assignment");
    echo '
                        </div>
                        <div class="panel-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#addAssignment" data-toggle="tab">Add an Assignment</a>
                            </li>
                        </ul>
                        <!-- /.panel-heading -->
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="addAssignment">
                                
        ';

                    if ((getClassNumber($mysqli) > 1) && (!isset($_SESSION['classID'])))
                    {
                        getClassForm($mysqli);
                    }
                    else if (!isset($_SESSION['classID']))
                    {
                        $_SESSION['classID'] = getClassID($mysqli);
                    }
                        
                    if (isset($_SESSION['classID']))
                    {
                        displayClassAssignmentForm($mysqli);
                    }

                    if (isset($_SESSION['classID']))
                    {
                        echo "<br>";
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
                </div>
                <!-- /.panel -->
            </div>
        </div>
    ';

}

function displayClassAssignmentForm($mysqli)
{
    $teacherID = $_SESSION['userID'];
    $classID = $_SESSION['classID'];

	$yearID = getClassYearID($mysqli);

    if ($stmt = $mysqli->prepare("SELECT className FROM classes WHERE classTeacherID = ? AND classID = ? AND schoolYearID = ?"))
    {
        $stmt->bind_param('iii', $teacherID, $classID, $yearID);

        $stmt->execute();
        $stmt->bind_result($className);

        $stmt->store_result();

        while($stmt->fetch())
        {
            echo '
                <h4>Class Name: ' . $className .'</h4>
        ';

                    generateFormStart("../includes/teacherFunctions/addAssignment", "post");
                        generateFormHiddenInput("classID", $classID);       
                        generateFormInputDiv("Assignment Name", "text", "materialName", NULL, NULL, NULL, NULL, "Assignment Name");
                        generateFormInputDiv("Assignment Points Possible", "text", "materialPointsPossible", "100", NULL, "100", "100");
                        generateFormInputDiv("Assignment Due Date", "date", "materialDueDate");

                        generateFormStartSelectDiv("Type of Assignment", "materialTypeID");
                            getAssignmentTypes($classID, $mysqli);
                        generateFormEndSelectDiv();
                        generateFormButton("addAssignmentButton", "Add Assignment");
                    generateFormEnd();
        }
    }
}

function getAssignmentTypes($classID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT materialTypeID, materialName FROM materialType WHERE classID = ?"))
    {
        $stmt->bind_param('i', $classID);
        $stmt->execute();
        $stmt->bind_result($materialTypeID, $materialName);
        $stmt->store_result();

        if ($stmt->num_rows > 0)
        {
            while ($stmt->fetch())
            {
                generateFormOption($materialTypeID, $materialName);
            }
        }
        else
        {
            generateFormOption(NULL, "No Assignment Types", "disabled", "selected");
        }
    }
    else
    {
        generateFormOption(NULL, "No Assignment Types", "disabled", "selected");
        return;
    }
}

function getClassForm($mysqli)
{
    echo "<br>";
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

    if ($stmt = $mysqli->prepare("SELECT classID, className FROM classes WHERE classTeacherID = ?"))
    {
        $stmt->bind_param('i', $teacherID);
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
