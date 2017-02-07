<?php 

include_once '../classFunctionsTemplate.php';

if (isset($_POST['gradeID']))
{
    // After the user selects an grade, set it as a $_SESSION variable
    $_SESSION['gradeID'] = $_POST['gradeID'];
}

if (isset($_POST['classID']))
{
    // After the user selects an grade, set it as a $_SESSION variable
    $_SESSION['classID'] = $_POST['classID'];
}

if (isset($_POST['changeGrade']))
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
    if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
    {
        viewStudentListClassTable($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewStudentListClassTable($mysqli)
{
    echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
       ';
                        // Call Session Message code and Panel Heading here
                        displayPanelHeading("Lookup User Profile");
    echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#studentClassList" data-toggle="tab">Student List</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="studentClassList">
                                <br>
        ';
                                if (!isset($_SESSION['gradeID']))
                                {
                                    getGradeLevelForm();
                                }
                                else if (!isset($_SESSION['classID']))
                                {
                                    getClassListForm($_SESSION['gradeID'], $mysqli);
                                }
                                else
                                {
                                    getStudentListTable($_SESSION['classID'], $mysqli);
                                }

                                if (isset($_SESSION['classID']))
                                {
                                    generateFormStart("", "post"); 
                                        generateFormButton("changeClass", "Change Class");
                                    generateFormEnd();
                                } 

                                if (isset($_SESSION['gradeID']))
                                {
                                    echo "<br>";
                                    generateFormStart("", "post"); 
                                        generateFormButton("changeGrade", "Change Grade Level");
                                    generateFormEnd();
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

function getStudentListTable($classID, $mysqli)
{
    echo '
            <table width="100%" class="table table-striped table-bordered table-hover" id="' . $classID . '">
                                            <thead>
                                                <tr>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Email</th>
                                                </tr>
                                            </thead>
                                            <tbody>
        ';

    if ($stmt = $mysqli->prepare("SELECT className FROM classes WHERE classID = ?"))
    {
        $stmt->bind_param('i', $classID);

        $stmt->execute();
        $stmt->bind_result($className);

        $stmt->store_result();

        while($stmt->fetch())
        {
            
                                                getStudentIDByClass($classID, $mysqli);
            echo ' 
                                            </tbody>
                                        </table>
                                        <!-- /.table-responsive -->
                                    


                        <!-- Page-Level Demo Scripts - Tables - Use for reference -->
                        <script>
                        $(document).ready(function() {
                            $(\'#' . $classID . '\').DataTable({
                                responsive: true
                            });
                        });
                        </script>
                ';
        }
    }
    else
    {
        echo "You are not a teacher!";
        return;
    }   
}

function getClassListForm($gradeID, $mysqli)
{
    generateFormStart("", "post"); 
        generateFormStartSelectDiv("Class List", "classID");
            getClassList($gradeID, $mysqli);
        generateFormEndSelectDiv();
        generateFormButton("classButton", "Select Class");
    generateFormEnd();
}

function getClassList($gradeID, $mysqli)
{
	$yearID = getClassYearID($mysqli);

    if ($stmt = $mysqli->prepare("SELECT classID, className FROM classes WHERE classGrade = ? AND schoolYearID = ?"))
    {   
        $stmt->bind_param('ii', $gradeID, $yearID);
        // Query database for announcement details to be modified
        $stmt->execute();

        $stmt->bind_result($classID, $className);
        
        $stmt->store_result();
        
        if ($stmt->num_rows > 0)
        {
            while ($stmt->fetch())
            {
                 generateFormOption($classID, $className);
            }
        }
        else
        {
            generateFormOption(NULL, "No Classes available", "disabled", "selected");
        }
    }
    else
    {
        generateFormOption(NULL, "No Classes available", "disabled", "selected");
    }
}

function getGradeLevelForm()
{
    generateFormStart("", "post"); 
        generateFormStartSelectDiv("Grade Level", "gradeID");
            for ($i = 1; $i <= 12; $i++)
            {
                generateFormOption($i, $i);
            }
        generateFormEndSelectDiv();
        generateFormButton("gradeButton", "Select Grade Level");
    generateFormEnd();
}

function getStudentIDByClass($classID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT studentID FROM studentClassIDs WHERE classID = ?"))
	{
        $stmt->bind_param('i', $classID);
		$stmt->execute();
		$stmt->bind_result($studentID);
		$stmt->store_result();

		while($stmt->fetch())
		{
            getStudentInfo($studentID, $mysqli);       
		}			
	}
	else
	{
        echo "No students in Class";
		return;
	}
}

function getStudentInfo($studentID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT studentFirstName, studentLastName, studentEmail FROM studentProfile WHERE studentID = ?"))
    {
        $stmt->bind_param('i', $studentID);
        $stmt->execute();
        $stmt->bind_result($studentFirstName, $studentLastName, $studentEmail);
        $stmt->store_result();

        while($stmt->fetch())
        {       
            echo '
                    <tr class="gradeA">
                        <td>' . $studentFirstName . '</td>
                        <td>' . $studentLastName . '</td>
                        <td>' . $studentEmail . '</td>
                    </tr>
                ';
        }           
    }
    else
    {
        return;
    }
}

?>
