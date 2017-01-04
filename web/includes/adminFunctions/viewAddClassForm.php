<?php

function viewAddClassForm($mysqli)
{

	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						if (isset($_SESSION['fail']))
                        {
                        	echo $_SESSION['fail'];
                            unset($_SESSION['fail']);
                        }
						else if (isset($_SESSION['success']))
						{
                        	echo $_SESSION['success'];
                            unset($_SESSION['success']);
						}
                        else
                        {
                        	echo 'Add a Class';
                        }
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#addClass" data-toggle="tab">Add a Class</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="administrator">
                                    <h4>Add a Class</h4>
                                    <form action="../includes/adminFunctions/addClass" method="post" role="form">
                                        <div class="form-group">
                                            <input class="form-control" name="className" placeholder="Class Name">
                                        </div>
										<div class="form-group">
                                            <label>Class Grade Level</label>
                                            <select class="form-control" name="classGradeLevel">';
                                                getGradeLevel();
                                    echo '
                                            </select>
                                        </div>
										<div class="form-group">
                                            <label>Class Teacher</label>
                                            <select class="form-control" name="classTeacherID">';
                                                getTeacherList($mysqli);
                                    echo '
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-default">Add Class</button>
                                    </form>
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

function getGradeLevel()
{
    for ($i = 1; $i <= 12; $i++)
    {
        echo "<option value='" . $i . "'>$i</option>";
    }
}

function getTeacherList($mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT teacherID, teacherFirstName, teacherLastName FROM teacherProfile"))
    {
        $stmt->execute();
        $stmt->bind_result($teacherID, $teacherFirstName, $teacherLastName);
        $stmt->store_result();

        while ($stmt->fetch())
        {
            echo "<option value='" . $teacherID . "'>$teacherLastName, $teacherFirstName</option>";
        }
    }
    else
    {
        return;
    }
}

?>
