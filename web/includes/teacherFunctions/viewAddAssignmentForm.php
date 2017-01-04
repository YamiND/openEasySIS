<?php

function viewAddAssignmentForm($mysqli)
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
                        	echo 'Add an Assignment';
                        }
    echo '
                        </div>
                        <!-- /.panel-heading -->
                        
        ';
                        displayClassAssignmentForm($mysqli);
    echo '



                    </div>
                    <!-- /.panel -->
                </div>
			</div>
    ';

}

function displayClassAssignmentForm($mysqli)
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
            echo '
            <div class="panel-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#addAssignment" data-toggle="tab">Add an Assignment</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="addAssignment">
                        <h4>Class Name: ' . $className .'</h4>

                        <form action="../includes/teacherFunctions/addAssignment" method="post" role="form">
                            <input type="hidden" name="classID" value="'. $classID .'">
                            <div class="form-group">
                                <label>Assignment Name</label>
                                <input class="form-control" name="materialName" placeholder="Assignment Name">
                            </div>
                            <div class="form-group">
                                <label>Assignment Points Possible</label>
                                <input class="form-control" type="number" name="materialPointsPossible" size="100" value="100">
                            </div>
                            <div class="form-group">
                                <label>Assignment Due Date</label>
                                <input class="form-control" type="date" name="materialDueDate">
                            </div>
                            <div class="form-group">
                                <label>Type of Assignment</label>
                                <select class="form-control" name="materialTypeID">';
                                    getAssignmentTypes($classID, $mysqli);
                        echo '
                                </select>
                            </div>
                            <button type="submit" class="btn btn-default">Add Assignment</button>
                        </form>
                        </div>
                    </div>
                </div>
                <!-- /.panel-body -->
                ';
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

?>