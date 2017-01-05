<?php

if (isset($_POST['gradeID']))
{
    // After the user selects an grade, set it as a $_SESSION variable
    $_SESSION['gradeID'] = $_POST['gradeID'];
}

if (isset($_POST['classID']))
{
    // After the user selects an class, set it as a $_SESSION variable
    $_SESSION['classID'] = $_POST['classID'];
}

if (isset($_POST['changeGradeLevel']))
{
    unset($_SESSION['gradeID']);
    unset($_SESSION['classID']);
}

if (isset($_POST['changeClass']))
{
    unset($_SESSION['classID']);
}

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 1))
    {
        viewModifyClassForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
    }
}

function viewModifyClassForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						 displayPanelHeading("Modify a Class");
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
                            <div class="tab-content">
        ';
                            if(!isset($_SESSION['gradeID']))
                            {
                                echo '<h4>Select Grade Level</h4>';
                            }
                            else
                            {
                                echo '<h4>Select Class</h4>';
                            }
    echo '
                                <div class="tab-pane fade in active" id="modifyClass">
        ';

                            if (!isset($_SESSION['gradeID']))
                            {
					           getGradeLevelForm();
                            }
                            else if (!isset($_SESSION['classID']))
                            {
                                getClassForm($_SESSION['gradeID'], $mysqli);
                            }
                            else
                            {
                                getClassInfo($_SESSION['classID'], $mysqli);
                            }

                            if (isset($_SESSION['classID']))
                                {
    echo '
                                <br>
                                <form action="" method="post" role="form">
                                <button type="submit" class="btn btn-default" name="changeClass">Change Class</button> 
                                </form>
                                
    ';
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
                <form action="" method="post" role="form">
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
                    <button type="submit" class="btn btn-default">Modify Class</button>
                </form>
                ';
        }
    }
}

function getClassInfo($classID, $mysqli)
{
    echo '
            <form action="../includes/adminFunctions/modifyClass" method="post" role="form">
        ';

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
                    </div>
                ';
                    getGradeLevel($classGrade);

                    getTeacherList($classTeacherID, $mysqli);
        echo '
                <button type="submit" class="btn btn-default">Modify Class Information</button>
            </form>
            ';
        }
    }
}

function getGradeLevel($selected = NULL)
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

?>
