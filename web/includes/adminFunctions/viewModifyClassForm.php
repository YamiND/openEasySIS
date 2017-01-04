<?php

function viewModifyClassForm($mysqli)
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
						else if (isset($_SESSION['gradeSelected']))
						{
							echo $_SESSION['gradeSelected'];
						}
						else if (isset($_SESSION['success']))
						{
                        	echo $_SESSION['success'];
                            unset($_SESSION['success']);
						}
                        else
                        {
                        	echo 'Modify a Class';
                        }
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#modifyClass" data-toggle="tab">Modify a Class</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">';
                            if(!isset($_POST['classID']))
                            {
                                echo '<h4>Select Grade Level</h4>';
                            }
                            else
                            {
                                echo '<h4>Modify Class Information</h4>';
                            }
    echo '
                                <div class="tab-pane fade in active" id="modifyClass">';

                if (!isset($_POST['classID']))
                {

                                echo '

                                    <form action="" method="post" role="form">';

										if (isset($_POST['gradeLevelID']))
										{
											$gradeLevelID = $_POST['gradeLevelID'];
										
											echo '
													<div class="form-group">
														<select class="form-control" name="gradeLevelID">';
														    getGradeLevel($gradeLevelID);
											echo '
														</select>
													</div>
													<button type="submit" class="btn btn-default">Select Grade Level</button>
												';

										}
										else
										{
											echo ' 
                                        			<div class="form-group">
			                                        	<select class="form-control" name="gradeLevelID">';
															getGradeLevel();
				echo '									</select> 
           				                             </div>
                        			                <button type="submit" class="btn btn-default">Select Grade Level</button>';
										}

echo '
                                    </form>

                                    <br>

                                    <form action="" method="post" role="form">';

										if (!isset($_POST['gradeLevelID']))
										{
											echo '<fieldset disabled>
													<div class="form-group">
														<select class="form-control" name="classID">
															<option value="class">No Grade Selected</option>
														</select>
													</div>
													<button type="submit" class="btn btn-default">Modify Class</button>
													</fieldset>';

										}
										else
										{
										    getClassNames($mysqli);
										}

echo '
                                    </form>
                                    
                                    <br>
';

}
else
{

echo '
                                    <form action="../includes/adminFunctions/modifyClass" method="post" role="form">';
                                        
                                        if (isset($_POST['classID']))
                                        {
                                            getClassInfo($_POST['classID'], $mysqli);
                                        }

echo '
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

function getGradeLevel($selected = NULL)
{
    for ($i = 1; $i <= 12; $i++)
    {
        if ($i == $selected)
        {
            echo "<option value='" . $i . "' selected> $i </option>";
        }
        else
        {
            echo "<option value='" . $i . "'> $i </option>";
        }
    }

}

function getClassNames($mysqli)
{
    if (isset($_POST['gradeLevelID']))
    {
        $classGrade = $_POST['gradeLevelID'];

        if ($stmt = $mysqli->prepare("SELECT className, classID FROM classes WHERE classGrade = ?"))
        {
            $stmt->bind_param('i', $classGrade);

            $stmt->execute();
            $stmt->bind_result($className, $classID);

            $stmt->store_result();
        
            if ($stmt->num_rows == 0)
            {
				echo ' 
				<fieldset disabled>
                    <div class="form-group">
			          	<select class="form-control" name="classID">
                            <option value="NULL">No Classes for Grade Level</option>
				        </select> 
           			</div>
                    <button type="submit" class="btn btn-default">Modify Class</button>
                </fieldset>';
            }
            else
            {
				echo ' 
                    <div class="form-group">
			          	<select class="form-control" name="classID">';
                        while($stmt->fetch())
                        {
                            echo "<option value='" . $classID . "'>$className</option>";
                        }
				echo '
				        </select> 
           			</div>
                    <button type="submit" class="btn btn-default">Modify Class</button>';
            }
        }
    }
}
function getGradeLevelInfo($selected = NULL)
{
    echo '
        <div class="form-group">
            <label>Grade Level</label>
            <select class="form-control" name="classGradeLevel">';

        for ($i = 1; $i <= 12; $i++)
        {   
            if ($i == $selected)
            {   
                echo "<option value='" . $i . "' selected> $i </option>";
            }   
            else
            {   
                echo "<option value='" . $i . "'> $i </option>";
            }   
        }
    echo ' 
            </select>
        </div>';
}

function getTeacherList($selected = NULL, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT teacherID, teacherFirstName, teacherLastName FROM teacherProfile"))
    {
        $stmt->execute();
        $stmt->bind_result($dbTeacherID, $dbTeacherFirstName, $dbTeacherLastName);
        
        $stmt->store_result();

    echo '
        <div class="form-group">
            <label>Teacher</label>
            <select class="form-control" name="classTeacherID">';

        while ($stmt->fetch())
        {
            if ($dbTeacherID == $selected)
            {   
                echo "<option value='$dbTeacherID' selected>$dbTeacherLastName, $dbTeacherFirstName</option>";
            }   
            else
            {   
                echo "<option value='$dbTeacherID'>$dbTeacherLastName, $dbTeacherFirstName</option>";
            }   
        }

    echo ' 
            </select>
        </div>';
    }
}

function getClassInfo($classID, $mysqli)
{
    if (isset($_POST['classID']))
    {
        $classID = $_POST['classID'];

        if($stmt = $mysqli->prepare("SELECT classGrade, className, classTeacherID FROM classes WHERE classID = ?"))
        {
            $stmt->bind_param('i', $classID);

            $stmt->execute();

            $stmt->bind_result($classGrade, $className, $classTeacherID);
            $stmt->store_result();

            while ($stmt->fetch())
            {
            echo '
                <input type="hidden" name="classID" value="'.$classID.'">
                    <div class="form-group">
                        <label>Class Name</label>
                        <input class="form-control" name="className" value="' . $className . '">
                    </div>';
                        getGradeLevelInfo($classGrade);

                        getTeacherList($classTeacherID, $mysqli);
            echo '
                    <button type="submit" class="btn btn-default">Modify Class Information</button>

            ';
            }
        }
    }
}
?>
