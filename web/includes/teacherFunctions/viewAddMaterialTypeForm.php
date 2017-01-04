<?php

function viewAddMaterialTypeForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	     ';
						if (isset($_SESSION['invalidAdd']))
                        {
                        	echo $_SESSION['invalidAdd'];
                            unset($_SESSION['invalidAdd']);
                        }
						else if (isset($_SESSION['successAdd']))
						{
                        	echo $_SESSION['successAdd'];
                            unset($_SESSION['successAdd']);
						}
                        else
                        {
                        	echo 'Add Assignment Type';
                        }
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#addMaterialType" data-toggle="tab">Add Assignment Type</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <h4>Add Assignment Type</h4>
                                <div class="tab-pane fade in active" id="addMaterialType">';

                            
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
                                    addMaterialTypeForm($classID);
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

function addMaterialTypeForm($classID)
{
    echo '

            <form action="../includes/teacherFunctions/addMaterialType" method="post" role="form">
                <input type="hidden" name="classID" value="'. $classID .'">
                <div class="form-group">
                    <label>Assignment Type Name</label>
                    <input class="form-control" name="materialName">
                </div>
                <div class="form-group">
                    <label>Assignment Type Weight</label>
                    <input class="form-control" type="number" name="materialWeight" size="100" value="0">
                </div>
                <button type="submit" class="btn btn-default">Add Material Type</button>
            </form>
        ';   
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