<?php

if (isset($_POST['classID']))
{
    $_SESSION['classID'] = $_POST['classID'];
}

if (isset($_POST['materialTypeID']))
{
    $_SESSION['materialTypeID'] = $_POST['materialTypeID'];
}

if (isset($_POST['changeClass']))
{
    unset($_SESSION['classID']);
    unset($_SESSION['materialTypeID']);
}

if (isset($_POST['changeMaterialType']))
{
    unset($_SESSION['materialTypeID']);
}

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 3))
    {
        viewModifyMaterialTypeForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
    }
}

function viewModifyMaterialTypeForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						displayPanelHeading("Modify Material Type");
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#modifyClass" data-toggle="tab">Modify Material Type</a>
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
                                <div class="tab-pane fade in active" id="modifyAssignment">';

                            if ((getClassNumber($mysqli) > 1) && (!isset($_SESSION['classID'])))
                            {
                                getClassForm($mysqli);
                            }
                            else if (!isset($_SESSION['classID']))
                            {
                                $_SESSION['classID'] = getClassID($mysqli);
                            }
                                
                            if ((isset($_SESSION['classID'])) && (!isset($_SESSION['materialTypeID'])))
                            {
                                chooseMaterialTypeForm($_SESSION['classID'], $mysqli);
                            }

                            if ((isset($_SESSION['classID'])) && (isset($_SESSION['materialTypeID'])))
                            {
                                getMaterialTypeForm($_SESSION['classID'], $_SESSION['materialTypeID'], $mysqli);
                            }

                            if (isset($_SESSION['classID']))
                            {
                                echo "<br>";
                                generateFormStart("", "post"); 
                                    generateFormButton("changeClass", "Change Class");
                                generateFormEnd();
                                echo "<br>";
                            }

                            if (isset($_SESSION['materialTypeID']))
                            {
                                generateFormStart("", "post"); 
                                    generateFormButton("changeMaterialType", "Change Assignment Type");
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

function getMaterialTypeForm($classID, $materialTypeID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT materialName, materialWeight from materialType WHERE materialTypeID = ?"))
    {
        $stmt->bind_param('i', $materialTypeID);
        $stmt->execute();
        $stmt->bind_result($materialName, $materialWeight);
        $stmt->store_result();

        while ($stmt->fetch())
        {
            generateFormStart("../includes/teacherFunctions/modifyMaterialType", "post"); 
                generateFormHiddenInput("materialTypeID", $materialTypeID);
                generateFormInputDiv("Assignment Type Name", "text", "materialName", $materialName);
                generateFormInputDiv("Assignment Type Weight", "number", "materialWeight", $materialWeight, NULL, NULL, NULL, "100");
                generateFormButton("modifyMaterialTypeButton", "Modify Material Type");
            generateFormEnd();
        }
    }
    else
    {
        echo "No Material Types";
        return;
    } 
}

function chooseMaterialTypeForm($classID, $mysqli)
{
    generateFormStart("", "post"); 
        generateFormHiddenInput("classID", $classID);
        generateFormStartSelectDiv(NULL, "materialTypeID");
            getMaterialTypeList($classID, $mysqli);
        generateFormEndSelectDiv();
        generateFormButton("selectAssignmentTypeButton", "Select Assignment Type");
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

        if ($stmt->num_rows > 0)
        {
            while ($stmt->fetch())
            {
                generateFormOption($materialTypeID, $materialName);
            } 
        }
        else
        {
            generateFormOption(NULL, "No Material Items", "disabled", "selected");
        }
    }
    else
    {
        generateFormOption(NULL, "No Material Items", "disabled", "selected");
    }
}

function getClassForm($mysqli)
{
    echo '
            <form action="" method="post" role="form">
                <div class="form-group">
                    <select class="form-control" name="classID">';
                        getClassList($mysqli);
    echo '                                  
                    </select> 
                 </div>
                <button type="submit" class="btn btn-default">Select Class</button>
            </form>';
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
            echo "<option value='" . $classID . "'>$className</option>";
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

function getAssignmentTypes($classID, $materialTypeID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT materialTypeID, materialName FROM materialType WHERE classID = ?"))
    {
        $stmt->bind_param('i', $classID);
        $stmt->execute();
        $stmt->bind_result($dbMaterialTypeID, $dbMaterialName);
        $stmt->store_result();

        while ($stmt->fetch())
        {
            if ($materialTypeID == $dbMaterialTypeID)
            {
                echo "<option value='" . $dbMaterialTypeID . "' selected> $dbMaterialName </option>";
            }
            else
            {
                echo "<option value='" . $dbMaterialTypeID . "'> $dbMaterialName </option>";
            }
        }
    }
    else
    {
        return;
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