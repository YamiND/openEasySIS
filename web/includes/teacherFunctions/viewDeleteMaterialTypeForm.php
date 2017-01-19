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
        viewDeleteMaterialTypeForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewDeleteMaterialTypeForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						// Call Session Message code and Panel Heading here
                        displayPanelHeading("Delete Material Type");
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#modifyClass" data-toggle="tab">Delete Material Type</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
		';
							if (isset($_SESSION['classID']))
							{
								echo '<h4>Select Material Type</h4>';
							}
							else
							{
								echo '<h4>Select Class</h4>';
							}
echo '
                                <div class="tab-pane fade in active" id="modifyAssignment">';
                            
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
                                    chooseMaterialTypeForm($_SESSION['classID'], $mysqli);
                                }

								echo "<br>";
								
								if (isset($_SESSION['classID']))
								{

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

function chooseMaterialTypeForm($classID, $mysqli)
{
    generateFormStart("../includes/teacherFunctions/deleteMaterialType", "post"); 
        generateFormHiddenInput("classID", $classID);
        generateFormStartSelectDiv(NULL, "materialTypeID");
            getMaterialTypeList($classID, $mysqli);
        generateFormEndSelectDiv();
        generateFormButton("deleteAssignmentTypeButton", "Delete Assignment Type");
    generateFormEnd();
}

function getMaterialTypeList($classID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT materialTypeID, materialName FROM materialType WHERE classID = ?"))
    {
        $stmt->bind_param('i', $classID);
        $stmt->execute();
        $stmt->bind_result($materialTypeID, $materialName);
        $stmt->store_result();

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
