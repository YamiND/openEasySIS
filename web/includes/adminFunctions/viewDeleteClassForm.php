<?php

if (isset($_POST['gradeID']))
{
    $_SESSION['gradeID'] = $_POST['gradeID'];
}

if (isset($_POST['changeGradeLevel']))
{
    unset($_SESSION['gradeID']);
}

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 1))
    {
        viewDeleteClassForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
    }
}

function viewDeleteClassForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	   ';
						displayPanelHeading("Delete Class");
    echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#deleteClass" data-toggle="tab">Delete a Class</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                            	<h4>Select Grade Level</h4>
                                <div class="tab-pane fade in active" id="deleteClass">
        ';
                            if (!isset($_SESSION['gradeID']))
                            {
                                getGradeLevelForm();
                            }
                            else
                            {
                                getClassForm($_SESSION['gradeID'], $mysqli);
                            }

                            if (isset($_SESSION['gradeID']))
                            {
    echo '
                                <br>
                                <form action="" method="post" role="form">
                                <button type="submit" class="btn btn-default" name="changeGradeLevel">Change Grade Level</button> 
                                </form>
    ';
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

function getGradeLevelForm()
{
    echo '
        <form action="" method="post" role="form">
            <div class="form-group">
                <label>Class Grade Level</label>
                <select class="form-control" name="gradeID">
        ';
            for ($i = 1; $i <= 12; $i++)
            {
                echo "<option value='" . $i . "'>$i</option>";
            }
    echo '
                </select>
            </div>
            <button type="submit" class="btn btn-default">Select Grade Level</button>
        </form>
        ';
}

function getClassForm($gradeID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT className, classID FROM classes WHERE classGrade = ?"))
    {
        $stmt->bind_param('i', $gradeID);

        $stmt->execute();
        $stmt->bind_result($className, $classID);

        $stmt->store_result();
    
        if ($stmt->num_rows == 0)
        {
            echo '<h3>No classes for grade, please change grade level or add class </h3>';
        }
        else
        {
            echo '
                <form action="../includes/adminFunctions/deleteClass" method="post" role="form">
                    <div class="form-group">
                        <select class="form-control" name="classID">
                ';
                        while($stmt->fetch())
                        {
                            echo "<option value='" . $classID . "'>$className</option>";
                        }
            echo '
                        </select> 
                    </div>
                    <button type="submit" class="btn btn-default">Delete Class</button>
                </form>
                ';
        }
    }
}

?>
