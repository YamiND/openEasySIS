<?php
if (isset($_POST['gradeID']))
{
    $_SESSION['gradeID'] = $_POST['gradeID'];
}

if (isset($_POST['classID']))
{
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
//TODO: Test this after adding multiple students to a class

function viewAssignStudentClassForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	     ';
						if (isset($_SESSION['success']))
                        {
                        	echo $_SESSION['success'];
                            unset($_SESSION['success']);
                        }
						else if (isset($_SESSION['fail']))
						{
                        	echo $_SESSION['fail'];
                            unset($_SESSION['fail']);
						}
                        else
                        {
                        	echo 'Assign Student to Class';
                        }
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#assignStudent" data-toggle="tab">Assign Student to Class</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">';
                                if (isset($_SESSION['classID']))
                                {
                                    echo '<h4>Class Name: ' . getClassName($_SESSION['classID'], $mysqli) . '</h4>';
                                }
                                else if (isset($_SESSION['gradeID']))
                                {
                                    echo '<h4>Select Class</h4>';
                                }
                                else 
                                {
                                    echo '<h4>Select Grade Level</h4>';
                                }

echo '
                                <div class="tab-pane fade in active" id="selectAssignment">';

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
                                    assignStudentForm($_SESSION['classID'], $_SESSION['gradeID'], $mysqli);
                                }


echo '
                                </div>

                                <br>';

                                if (isset($_SESSION['gradeID']))
                                {
echo '

                                <form action="" method="post" role="form">
                                <button type="submit" class="btn btn-default" name="changeGradeLevel">Change Grade Level</button> 
                                </form>
    ';
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

echo '
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
			</div>
';

}

function assignStudentForm($classID, $gradeID, $mysqli)
{
     echo '
            <form action="../includes/adminFunctions/assignStudentClass" method="post" role="form">
                <input type="hidden" name="classID" value="'. $classID .'">  
                <div class="form-group">
                    <select class="form-control" name="studentID">';
                    getStudentList($classID, $gradeID, $mysqli);
    echo '
                    </select>
                </div>
                <button type="submit" class="btn btn-default">Add Student to Class</button>
            </form>
        ';
}

function getStudentList($classID, $gradeID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT studentID, studentFirstName, studentLastName FROM studentProfile WHERE studentGradeLevel = ?"))
    {
        $stmt->bind_param('i', $gradeID);
        $stmt->execute();
        $stmt->bind_result($studentID, $studentFirstName, $studentLastName);
        $stmt->store_result();

        while($stmt->fetch())
        {       
            if (!checkStudentClassIDs($classID, $studentID, $mysqli))
            {
                echo "<option value='" . $studentID . "'> $studentLastName, $studentFirstName </option>";  
            }
        }           
    }
    else
    {
        return;
    }
}

function checkStudentClassIDs($classID, $studentID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT studentID FROM studentClassIDs WHERE studentID = ? AND classID = ?"))
    {
        $stmt->bind_param('ii', $studentID, $classID);
        $stmt->execute();
        $stmt->bind_result($dbStudentID);
        $stmt->store_result();

        $stmt->fetch();

        if ($stmt->num_rows == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}

function getGradeLevelForm()
{
    echo '
            <form action="" method="post" role="form">  
                <div class="form-group">
                    <select class="form-control" name="gradeID">';
                    for ($i = 1; $i <= 12; $i++)
                    {
                            echo "<option value='" . $i . "'> $i </option>";
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

    echo '
            <form action="" method="post" role="form">  
                <div class="form-group">
                    <select class="form-control" name="classID">';
                    getClassList($gradeID, $mysqli);
    echo '
                    </select>
                </div>
                <button type="submit" class="btn btn-default">Select Class</button>
            </form>
        ';
}

function getClassList($gradeID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT classID, className FROM classes WHERE classGrade = ?"))
    {
        $stmt->bind_param('i', $gradeID);
        $stmt->execute();
        $stmt->bind_result($classID, $className);
        $stmt->store_result();

        while ($stmt->fetch())
        {
                echo "<option value='" . $classID . "'> $className </option>";
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

?>