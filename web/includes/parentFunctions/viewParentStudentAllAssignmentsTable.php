<?php

if (isset($_POST['studentID']) && !empty($_POST['studentID']))
{
	$_SESSION['studentID'] = $_POST['studentID'];
}

if (isset($_POST['changeStudent']))
{
	unset($_SESSION['studentID']);
}

//TODO: Test this after adding multiple students to a class
function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isParent($mysqli)))
    {
        viewAssignmentsForStudentTable($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewAssignmentsForStudentTable($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	     ';
						// Call Session Message code and Panel Heading here
                        displayPanelHeading("View All Assignments for Student");
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#addMaterialType" data-toggle="tab">Student Assignments</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="selectAssignment">';

                           		if ((getStudentCount($_SESSION['userID'], $mysqli) > 1) && !isset($_SESSION['studentID']))
								{
									chooseStudentForm($_SESSION['userID'], $mysqli);
								}
								else if (getStudentCount($_SESSION['userID'], $mysqli) == 1)
								{
									$_SESSION['studentID'] = getStudentID($_SESSION['userID'], $mysqli);

								}
								else
								{
									echo "<br><p>No student assigned to account</p>";	
								}
								if ((isset($_SESSION['studentID']) && (!empty($_SESSION['studentID']))))
								{
                                    viewStudentAssignments($_SESSION['studentID'], $mysqli);
								}

								if ((isset($_SESSION['studentID'])) && (getStudentCount($_SESSION['userID']) > 1))
								{
									generateFormStart("", "post");
            							generateFormButton("changeStudent", "Change Student");
						        	generateFormEnd();
								}
echo '
                                </div>
                ';
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

function chooseStudentForm($parentID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT studentID FROM studentParentIDs WHERE parentID = ?"))
    {
        $stmt->bind_param('i', $parentID);
        $stmt->execute();
        $stmt->bind_result($studentID);

        if ($stmt->num_rows > 0)
        {
			generateFormStart("", "post");
            	generateFormStartSelectDiv(NULL, "studentID");
            	while ($stmt->fetch())
            	{
            		getStudentInfo($studentID, $mysqli);    
            	}
            	generateFormEndSelectDiv();
            	generateFormButton("selectStudent", "Select Student");
        	generateFormEnd();
        }
        else
        {
            return 0;
        }
	}
}

function getStudentInfo($studentID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT userFirstName, userLastName FROM users WHERE userID = ?"))
    {
        $stmt->bind_param('i', $studentID);
        $stmt->execute();
        $stmt->bind_result($studentFirstName, $studentLastName);
        $stmt->store_result();

        while($stmt->fetch())
        {
            generateFormOption($studentID, "$studentLastName, $studentFirstName");
        }
    }
    else
    {
        return;
    }
}

function viewStudentAssignments($studentID, $mysqli)
{
	$yearID = getClassYearID($mysqli);
	$studentName = getUserName($studentID, $mysqli);

	if ($stmt = $mysqli->prepare("SELECT studentClassIDs.classID, className FROM studentClassIDs, classes WHERE studentID = ? AND schoolYearID = ?"))
	{
    	$stmt->bind_param('ii', $studentID, $yearID);
        $stmt->execute();
        $stmt->bind_result($classID, $className);
        $stmt->store_result();

		echo '<h4> Student Name: ' . $studentName . ' </h4> <br>';

		while ($stmt->fetch())
		{
			echo '
				<h4> Class Name: ' . $className . ' </h4>
                                <table width="100%" class="table table-striped table-bordered table-hover" id="' . $studentID . '">
                                    <thead>
                                        <tr>
                                            <th>Assignment Name</th>
                                            <th>Assignment Due Date</th>
                                            <th>Assigment Type</th>
                                            <th>Points Scored</th>
                                            <th>Points Possible</th>
                                            <th>Assignment Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
	        ';          
					getAssignmentsForStudentClass($studentID, $classID, $mysqli);
		    echo ' 
                                    </tbody>
                                </table>
                                <!-- /.table-responsive -->

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
	}
}

function getAssignmentsForStudentClass($studentID, $classID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT materialID, materialName, materialPointsPossible, materialDueDate, materialTypeID FROM materials WHERE materialClassID = ?"))
    {
        $stmt->bind_param('i', $classID);
        $stmt->execute();
        $stmt->bind_result($materialID, $materialName, $materialPointsPossible, $materialDueDate, $materialTypeID);
        $stmt->store_result();

		while ($stmt->fetch())
		{
			$materialPointsScored = getMaterialPointsScored($materialID, $classID, $studentID, $mysqli);

			$materialTotalPoints = ($materialPointsScored / $materialPointsPossible) * 100;

			 echo '
                    <tr class="gradeA">
                        <td>' . $materialName. '</td>
                        <td>' . $materialDueDate . '</td>
                        <td>' . getMaterialTypeName($materialTypeID, $mysqli) . '</td>
                        <td>' . $materialPointsScored . '</td>
                        <td> /' . $materialPointsPossible . '</td>
                        <td>' . $materialTotalPoints . '%</td>
                    </tr>
                ';
		
		}
    }
}

function getMaterialTypeName($materialTypeID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT materialName FROM materialType WHERE materialTypeID = ?"))
    {   
        $stmt->bind_param('i', $materialTypeID);
        $stmt->execute();
        $stmt->bind_result($materialTypeNameResult);
        $stmt->store_result();

    if ($stmt->num_rows > 0)
    {   
        while ($stmt->fetch())
        {   
            return $materialTypeNameResult;
        }   
    }   
    else
    {   
        return "No Assignment Types";
    }   

    }   
}

?>
