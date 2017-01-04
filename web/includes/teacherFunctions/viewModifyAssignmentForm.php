<?php

function viewModifyAssignmentForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						if (isset($_SESSION['invalidModify']))
                        {
                        	echo $_SESSION['invalidModify'];
                            unset($_SESSION['invalidModify']);
                        }
						else if (isset($_SESSION['successModify']))
						{
                        	echo $_SESSION['successModify'];
                            unset($_SESSION['successModify']);
						}
                        else
                        {
                        	echo 'Modify an Assignment';
                        }
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

                            if (!isset($_POST['assignmentID']))
                            {
                               if (getClassNumber($mysqli) > 1)
                                {
                                    getClassForm($mysqli);
                                }
                               else if ((isset($_POST['classID'])) && (!empty($_POST['classID']))) 
                                {
                                    $classID = $_POST['classID'];
                                }
                                else
                                {
                                    $classID = getClassID($mysqli);
                                }
                                    

                                if (!empty($classID))
                                {
                                    chooseAssignmentForm($classID, $mysqli);
                                }
                            }
                            else
                            {
                                $assignmentID = $_POST['assignmentID'];
                                $classID = $_POST['classID'];
                                getAssignmentForm($classID, $assignmentID, $mysqli);
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
            echo '
            <form action="../includes/teacherFunctions/modifyAssignment" method="post" role="form">
                <input type="hidden" name="materialID" value="'. $assignmentID .'">
                <div class="form-group">
                    <label>Assignment Name</label>
                    <input class="form-control" name="materialName" value="'. $materialName .'">
                </div>
                <div class="form-group">
                    <label>Assignment Points Possible</label>
                    <input class="form-control" type="number" name="materialPointsPossible" size="100" value="' . $materialPointsPossible . '">
                </div>
                <div class="form-group">
                    <label>Assignment Due Date</label>
                    <input class="form-control" type="date" name="materialDueDate" value="'. $materialDueDate .'">
                </div>
                <div class="form-group">
                    <label>Type of Assignment</label>
                    <select class="form-control" name="materialTypeID">';
                        getAssignmentTypes($classID, $materialTypeID, $mysqli);
            echo '
                    </select>
                </div>
                <button type="submit" class="btn btn-default">Modify Assignment</button>
            </form>
                ';
        }
    }
    else
    {
        return;
    }

   
}

function chooseAssignmentForm($classID, $mysqli)
{
    echo '
            <form action="" method="post" role="form">
                <input type="hidden" name="classID" value="'. $classID .'">
                <div class="form-group">
                    <select class="form-control" name="assignmentID">';
                        getAssignmentList($classID, $mysqli);
    echo '                                  
                    </select> 
                 </div>
                <button type="submit" class="btn btn-default">Select Assignment</button>
            </form>';
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
            echo "<option value='" . $materialID . "'> $materialName </option>";
        }
    }
    else
    {
        return;
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