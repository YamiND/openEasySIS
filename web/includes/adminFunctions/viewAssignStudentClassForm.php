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

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 1))
    {
        viewAssignStudentClassForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
    }
}

function viewAssignStudentClassForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	     ';
						// Call Session Message code and Panel Heading here
                        displayPanelHeading("Assign Student to Class");
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
                                else if (isset($_SESSION['gradeID']) && (getClassNumber($_SESSION['gradeID'], $mysqli) > 0))
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
                                else if ((isset($_SESSION['gradeID'])) && (getClassNumber($_SESSION['gradeID'], $mysqli) == 0))
                                {
                                    echo "<h3>No Classes for Grade Level, Select Another Class </h3>";
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
                                <br>
                                ';

                                if (isset($_SESSION['classID']))
                                {
echo '
                                <br>
                                <form action="" method="post" role="form">
                                <button type="submit" class="btn btn-default" name="changeClass">Change Class</button> 
                                </form>
                                <br>
    ';
                                }

                                if (isset($_SESSION['gradeID']))
                                {
echo '

                                <form action="" method="post" role="form">
                                <button type="submit" class="btn btn-default" name="changeGradeLevel">Change Grade Level</button> 
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
    //if ($stmt = $mysqli->prepare("SELECT studentID, studentFirstName, studentLastName FROM studentProfile WHERE studentGradeLevel = ?"))
    if ($stmt = $mysqli->prepare("SELECT studentID FROM studentProfile WHERE studentID IN (SELECT studentID from studentProfile WHERE studentGradeLevel = ?) AND studentID NOT IN (SELECT studentID FROM studentClassIDs WHERE classID LIKE ?);"))
    {
        $stmt->bind_param('ii', $gradeID, $classID);
        $stmt->execute();
        $stmt->bind_result($studentID);
        $stmt->store_result();

        if ($stmt->num_rows > 0)
        {
            echo '
                    <form action="../includes/adminFunctions/assignStudentClass" method="post" role="form">
                        <input type="hidden" name="classID" value="'. $classID .'">  
                        <div class="form-group">
                            <select class="form-control" name="studentID">
                ';

            while($stmt->fetch())
            {     
                getStudentName($studentID, $mysqli);  
            }  

            echo '
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default">Add Student to Class</button>
                    </form>
                ';         
        }
        else
        {
            echo "No students can be assigned";
        }
    }
}

function getStudentName($studentID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT studentFirstName, studentLastName FROM studentProfile WHERE studentID = ?"))
    {
        $stmt->bind_param('i', $studentID);
        $stmt->execute();
        $stmt->bind_result($studentLastName, $studentFirstName);
        $stmt->store_result();

        $stmt->fetch();

        echo "<option value='" . $studentID . "'> $studentLastName, $studentFirstName </option>";  
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
        ';
                    
                    getClassList($gradeID, $mysqli);
    echo '  
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

        if ($stmt->num_rows > 0)
        {
            echo '<select class="form-control" name="classID">';
            while ($stmt->fetch())
            {
                    echo "<option value='" . $classID . "'> $className </option>";
            }
            echo ' </select>';
        }
        else
        {
            echo "<h2>No Classes for Grade";
        }
    }
}

function getClassNumber($gradeID, $mysqli)
{
    // The below is required to get a num_rows result
    if ($stmt = $mysqli->prepare("SELECT classID FROM classes WHERE classGrade = ?"))
    {
        $stmt->bind_param('i', $gradeID);
        $stmt->execute();
        
        $stmt->store_result();

        return $stmt->num_rows;
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