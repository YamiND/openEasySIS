<?php

if (isset($_POST['editUserID']))
{
	$_SESSION['editUserID'] = $_POST['editUserID'];
}

if (isset($_POST['changeUser']))
{
    unset($_SESSION['editUserID']);
}

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isSchoolAdmin($mysqli) || canModClassList($mysqli)))
    {
        viewEditProfileForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewEditProfileForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						 displayPanelHeading("Lookup Student Profile");
    echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#modifyClass" data-toggle="tab">Lookup Student Profile</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="modifyClass">
                                    <br>
        ';

                            if (!isset($_SESSION['editUserID']))
                            {
					           chooseUserForm($mysqli);
                            }
							else
							{
								lookupUserForm($_SESSION['editUserID'], $mysqli);
	
								echo "<br>";
                                generateFormStart("", "post"); 
                                    generateFormButton("changeUser", "Change User");
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

function lookupUserForm($userID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT userEmail, userFirstName, userLastName, studentGradeLevel FROM users WHERE userID = ? AND isStudent"))
	{
		$stmt->bind_param('i', $userID);

		if ($stmt->execute())
		{
			$stmt->bind_result($userEmail, $userFirstName, $userLastName, $studentGradeLevel);

			$stmt->store_result();

			$stmt->fetch();

echo <<<EOF
		<form action="" method="POST">

		  <input type="hidden" name="userID" value="$userID">
		  <div class="form-group">
			<label>User's Email</label>
			<input class="form-control" type="email" placeholder="User's Email" name="userEmail" value="$userEmail" disabled>
		  </div>
		  
		  <div class="form-group">
			<label>User's First Name</label>
			<input class="form-control" type="text" placeholder="User's First Name" name="userFirstName" value="$userFirstName" disabled>
		  </div>
		  
		  <div class="form-group">
			<label>User's Last Name</label>
			<input class="form-control" type="text" placeholder="User's Last Name" name="userLastName" value="$userLastName" disabled>
		  </div>

		  <div class="form-group">
			<label>Student's Grade Level</label>
			<input class="form-control" type="text" value="$studentGradeLevel" disabled>
		  </div>

		</form>
EOF;

		echo "<h4>Class Information</h4>";

		viewStudentGradesTable($userID, $mysqli);

		}	
	}
}

function viewStudentGradesTable($studentID, $mysqli)
{
	$yearID = getClassYearID($mysqli);

    if ($stmt = $mysqli->prepare("SELECT studentClassIDs.classID, classes.className FROM studentClassIDs INNER JOIN (classes) ON (classes.classID = studentClassIDs.classID AND studentClassIDs.studentID = ? AND classes.schoolYearID = ?)"))
    {
        $stmt->bind_param('ii', $studentID, $yearID);

        $stmt->execute();
        $stmt->bind_result($classID, $className);

        $stmt->store_result();
        
		
            echo '
                    <!-- /.row -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading" id="grades"> 
                                        Class Grades 
										<br />';
			echo "Current School Year GPA: " . number_format((float) getCurrentSchoolYearGPA($studentID, $mysqli), 2, '.', '') . "";
			echo "<br />";
			echo "Cumulative GPA: " . number_format((float) getTotalGPA($studentID, $mysqli), 2, '.', '') . "";
													
			echo '

                                    </div>
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">
                                        <table width="100%" class="table table-striped table-bordered table-hover" id="' . $studentID . '">
                                            <thead>
                                                <tr>
                                                    <th>Class Name</th>
                                                    <th>Grade</th>
                                                </tr>
                                            </thead>';
        while($stmt->fetch())
        {
	    $classGrade = getClassGrade($studentID, $classID, $mysqli);	
echo '                                            <tbody>
                    			<tr class="gradeA">
		                        <td>' . $className . '</td>
		                       <td>' . $classGrade . '%</td>
		                    		</tr>';
		}
							echo '
							
                                            </tbody>
                                        </table>
                                        <!-- /.table-responsive -->
                                    </div>
                                    <!-- /.panel-body -->
                                </div>
                                <!-- /.panel -->
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>
                        <!-- /.row -->


                        <!-- Page-Level Demo Scripts - Tables - Use for reference -->
                        <script>
                        $(document).ready(function() {
                            $(\'#' . $studentID . '\').DataTable({
                                responsive: true
                            });
                        });
                        </script>
                ';
    }
    else
    {
        echo "You are not a student!";
        return;
    }   
}


function chooseUserForm($mysqli)
{   
	generateFormStart("", "post"); 
    	generateFormStartSelectDiv("Choose User", "editUserID");

		if ($stmt = $mysqli->prepare("SELECT userID, userFirstName, userLastName, userEmail, studentGradeLevel FROM users WHERE isStudent"))
		{
			if ($stmt->execute())
			{
				$stmt->bind_result($userID, $userFirstName, $userLastName, $userEmail, $studentGradeLevel);
				$stmt->store_result();
	
				while ($stmt->fetch())
				{
                    generateFormOption($userID, "$userLastName, $userFirstName - Grade: $studentGradeLevel - $userEmail");
				}
			}
		}

		generateFormEndSelectDiv();
        generateFormButton(NULL, "Select User");
	generateFormEnd();
}

?>
