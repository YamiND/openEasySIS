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
        viewAddMaterialTypeForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
    }
}

function viewAddMaterialTypeForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	     ';
						displayPanelHeading("Add Assignment Type");
    echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#selectClass" data-toggle="tab">Select a Class</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
            ';
                            if (!isset($_SESSION['classID']))
                            {
                                echo '<h4>Select Class</h4>';
                            }
                            else
                            {
                                echo '<h4>Class Name: ' . getClassName($_SESSION['classID'], $mysqli) . '</h4>';
                            }  
            echo '
                                
                                <div class="tab-pane fade in active" id="addMaterialType">
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
                                    addMaterialTypeForm($_SESSION['classID'], $mysqli);
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
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
			</div>
        ';

}

function addMaterialTypeForm($classID, $mysqli)
{
    generateFormStart("../includes/teacherFunctions/addMaterialType", "post"); 
        generateFormHiddenInput("classID", $classID);
        generateFormInputDiv("Assignment Type Name", "text", "materialName", NULL, NULL, NULL, NULL, "Assignment Type Name");
        generateFormInputDiv("Assignment Type Weight", "number", "materialWeight", 0, NULL, 0, 100, 100, 100);
        generateFormButton("addMaterialTypeButton", "Add Material Type");
    generateFormEnd();
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
