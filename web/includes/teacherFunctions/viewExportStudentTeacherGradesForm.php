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
        viewExportStudentTeacherForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
    }
}

function viewExportStudentTeacherForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						displayPanelHeading("Export Student Data");
    echo '
                        </div>
                        <div class="panel-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#addAssignment" data-toggle="tab">Export Student Data</a>
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
                        exportStudentDataForm($mysqli);
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

function exportStudentDataForm($mysqli)
{
	$classID = $_SESSION['classID'];
	$className = getClassName($classID, $mysqli);

	echo "<br>";
	echo "<label>Export data for: $className </label>";

	generateFormStart("../includes/teacherFunctions/exportStudentData", "post");
		generateFormHiddenInput("classID", "$classID");       
		generateFormButton(NULL, "Export Student Grades");
	generateFormEnd();
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

	$schoolYearID = getClassYearID($mysqli);
    if ($stmt = $mysqli->prepare("SELECT classID, className FROM classes WHERE classTeacherID = ? AND schoolYearID = ?"))
    {
        $stmt->bind_param('ii', $teacherID, $schoolYearID);
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

function getClassName($classID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT className FROM classes WHERE classID = ?"))
    {   
        $stmt->bind_param('i', $classID);
        $stmt->execute();
        $stmt->bind_result($className);
        $stmt->store_result();

        while($stmt->fetch())
        {   
            return $className;    
        }    
    }   
    else
    {   
        return "0";
    }   
}

?>
