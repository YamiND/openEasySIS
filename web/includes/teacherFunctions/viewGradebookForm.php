<?php
if (isset($_POST['classID']))
{
    $_SESSION['classID'] = $_POST['classID'];
}

if (isset($_POST['materialID']))
{
    $_SESSION['materialID'] = $_POST['materialID'];
}

if (isset($_POST['changeAssignment']))
{
    unset($_SESSION['materialID']);
}

if (isset($_POST['changeClass']))
{
    unset($_SESSION['changeClass']);
}
//TODO: Test this after adding multiple students to a class

function viewGradebookForm($mysqli)
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
                        	echo 'Student Gradebook';
                        }
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#addMaterialType" data-toggle="tab">Student Gradebook</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">';
                                if (isset($_SESSION['materialID']))
                                {
                                    echo '<h4>Assignment Name: ' . getMaterialName($_SESSION['materialID'], $mysqli) . '</h4>';
                                }
                                else
                                {
                                    echo '<h4>Select Assignment</h4>';
                                }

                            echo '
                                
                                <div class="tab-pane fade in active" id="selectAssignment">';

                            
                               if (getClassNumber($mysqli) > 1)
                                {
                                    getClassForm($mysqli);
                                }
                                else if (isset($_SESSION['classID']))
                                {
                                    $classID = $_SESSION['classID'];
                                }
                                else
                                {
                                    $classID = getClassID($mysqli);
                                    $_SESSION['classID'] = $classID;
                                }

                                if ((isset($_SESSION['classID'])) && (!isset($_SESSION['materialID'])))
                                {
                                    chooseAssignmentForm($_SESSION['classID'], $mysqli);
                                }

                                if (isset($_SESSION['materialID']))
                                {
                                    viewGradebook($_SESSION['classID'], $_SESSION['materialID'], $mysqli);
                                }
echo '
                                </div>


                                <br>';

                                if (isset($_SESSION['materialID']))
                                {
echo '

                                <form action="" method="post" role="form">
                                <button type="submit" class="btn btn-default" name="changeAssignment">Change Assignment</button> 
                                </form>
    ';
                                }

                                if (getClassNumber($mysqli) > 1)
                                {
echo '
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

function viewGradebook($classID, $materialID, $mysqli)
{
    $materialName = getMaterialName($materialID, $mysqli);

    echo '
           
                                <table width="100%" class="table table-striped table-bordered table-hover" id="' . $materialID . '">
                                    <thead>

                                        <tr>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Points Earned</th>
                                            <th>Points Possible</th>
                                            <th>Submit Changes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
        ';          
                                        getStudentID($classID, $materialID, $mysqli);
    echo ' 
                                    </tbody>
                                </table>
                                <!-- /.table-responsive -->

                <!-- Page-Level Demo Scripts - Tables - Use for reference -->
                <script>
                $(document).ready(function() {
                    $(\'#' . $materialID . '\').DataTable({
                        responsive: true
                    });
                });
                </script>
        ';

}

function getStudentID($classID, $materialID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT studentID FROM studentClassIDs WHERE classID = ?"))
    {
        $stmt->bind_param('i', $classID);
        $stmt->execute();
        $stmt->bind_result($studentID);
        $stmt->store_result();

        while($stmt->fetch())
        {
            getStudentInfo($classID, $studentID, $materialID, $mysqli);       
        }           
    }
    else
    {
        echo "No students in Class";
        return;
    }
}

function getStudentInfo($classID, $studentID, $materialID, $mysqli)
{
    $materialPointsPossible = getMaterialPointsPossible($materialID, $mysqli);

    if ($stmt = $mysqli->prepare("SELECT studentFirstName, studentLastName FROM studentProfile WHERE studentID = ?"))
    {
        $stmt->bind_param('i', $studentID);
        $stmt->execute();
        $stmt->bind_result($studentFirstName, $studentLastName);
        $stmt->store_result();

        while($stmt->fetch())
        {       
            echo '
                    <tr class="gradeA">
                    <form action="../includes/teacherFunctions/changeGrade" method="post" role="form">
                    <input type="hidden" name="classID" value="'. $classID .'">
                    <input type="hidden" name="studentID" value="'. $studentID .'">
                    <input type="hidden" name="materialID" value="'. $materialID .'">
                        <td>' . $studentFirstName . '</td>
                        <td>' . $studentLastName . '</td>
                        <td> <input class="form-control" type="number" name="materialPointsScored" size="' . $materialPointsPossible . '" value="' . getMaterialPointsScored($studentID, $materialID, $mysqli) . '"> </td>
                        <td>' . '/' . $materialPointsPossible . '</td>
                        <td> <button type="submit" class="btn btn-default">Apply Changes</button> </td>
                        </form>
                    </tr>
                ';
        }           
    }
    else
    {
        return;
    }
}

function getMaterialPointsScored($studentID, $materialID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT gradeMaterialPointsScored FROM grades WHERE gradeMaterialID = ? AND gradeStudentID = ?"))
    {
        $stmt->bind_param('ii', $materialID, $studentID);
        $stmt->execute();
        $stmt->bind_result($materialPointsScored);
        $stmt->store_result();

        $stmt->fetch();

        return $materialPointsScored;
    }
    else
    {
        return 0;
    }
}

function getMaterialName($materialID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT materialName FROM materials WHERE materialID = ?"))
    {
        $stmt->bind_param('i', $materialID);
        $stmt->execute();
        $stmt->bind_result($materialName);
        $stmt->store_result();

        $stmt->fetch();

        return $materialName;
    }
    else
    {
        return;
    }
}

function getMaterialPointsPossible($materialID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT materialPointsPossible FROM materials WHERE materialID = ?"))
    {
        $stmt->bind_param('i', $materialID);
        $stmt->execute();
        $stmt->bind_result($materialPointsPossible);
        $stmt->store_result();

        $stmt->fetch();

        return $materialPointsPossible;
    }
    else
    {
        return;
    }
}

function chooseAssignmentForm($classID, $mysqli)
{
    echo '
            <form action="" method="post" role="form">
                <input type="hidden" name="classID" value="'. $classID .'">
                <div class="form-group">
                    <select class="form-control" name="materialID">';
                        getAssignmentList($classID, $mysqli);
    echo '                                  
                    </select> 
                 </div>
                <button type="submit" class="btn btn-default">Select Assignment</button>
            </form>';
}

function getAssignmentList($classID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT materialID, materialName FROM materials WHERE materialClassID = ?"))
    {
        $stmt->bind_param('i', $classID);
        $stmt->execute();
        $stmt->bind_result($materialID, $materialName);
        $stmt->store_result();

        while ($stmt->fetch())
        {
            echo "<option value='" . $materialID . "'> $materialName </option>";
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