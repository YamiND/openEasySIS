<?php

function viewDeleteClassForm($mysqli)
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
						else if (isset($_SESSION['gradeSelected']))
						{
							echo $_SESSION['gradeSelected'];
						}
						else if (isset($_SESSION['deleteSuccess']))
						{
                        	echo $_SESSION['deleteSuccess'];
                            unset($_SESSION['deleteSuccess']);
						}
                        else
                        {
                        	echo 'Delete a Class';
                        }
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

                                    <form action="../includes/adminFunctions/deleteClass" method="post" role="form">';

										if (!isset($_POST['gradeLevelID']))
										{
											echo '<fieldset disabled>
													<div class="form-group">
														<select class="form-control" name="classID">
															<option value="class">No Grade Selected</option>
														</select>
													</div>
													<button type="submit" class="btn btn-default">Delete Class</button>
													</fieldset>';

										}
										else
										{
										    getClassNames($mysqli);
										}

echo '
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
                    <button type="submit" class="btn btn-default">Delete Class</button>
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
                    <button type="submit" class="btn btn-default">Delete Class</button>';
            }
        }
    }
}
?>
