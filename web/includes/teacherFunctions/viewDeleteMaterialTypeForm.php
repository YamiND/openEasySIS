<?php

function viewDeleteMaterialTypeForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						if (isset($_SESSION['invalidDelete']))
                        {
                        	echo $_SESSION['invalidDelete'];
                            unset($_SESSION['invalidDelete']);
                        }
						else if (isset($_SESSION['successDelete']))
						{
                        	echo $_SESSION['successDelete'];
                            unset($_SESSION['successDelete']);
						}
                        else
                        {
                        	echo 'Delete Material Type';
                        }
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
                                <h4>Select Material Type</h4>
                                <div class="tab-pane fade in active" id="modifyAssignment">';

                            
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
                                    chooseMaterialTypeForm($classID, $mysqli);
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
    echo '
            <form action="../includes/teacherFunctions/deleteMaterialType" method="post" role="form">
                <input type="hidden" name="classID" value="'. $classID .'">
                <div class="form-group">
                    <select class="form-control" name="materialTypeID">';
                        getMaterialTypeList($classID, $mysqli);
    echo '                                  
                    </select> 
                 </div>
                <button type="submit" class="btn btn-default">Delete Assignment Type</button>
            </form>';
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
            echo "<option value='" . $materialTypeID . "'> $materialName </option>";
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