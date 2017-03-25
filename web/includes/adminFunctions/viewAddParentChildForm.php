<?php

if (isset($_POST['gradeLevel']) && !empty($_POST['gradeLevel']))
{
	$_SESSION['gradeLevel'] = $_POST['gradeLevel'];
}

if (isset($_POST['changeGrade']))
{
	unset($_SESSION['gradeLevel']);
}

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
    {
        viewAddParentChildForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewAddParentChildForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						// Call Session Message code and Panel Heading here
                        displayPanelHeading("Add a Child to Parent");
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#addClass" data-toggle="tab">Add a Child to Parent</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="addClass">
                                    <br>
        ';
							if (!isset($_SESSION['gradeLevel']))
							{
								getGradeLevelForm();
							}
							else
							{
								chooseParentStudentForm($mysqli);

								echo "<br>";
						 		generateFormStart("", "post");  
									generateFormButton("changeGrade", "Change Grade");
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

function chooseParentStudentForm($mysqli)
{
	$gradeLevel = $_SESSION['gradeLevel'];

    generateFormStart("../includes/adminFunctions/addParentChild", "post"); 
        generateFormStartSelectDiv("Select Student", "studentID");
			chooseStudentOption($mysqli, $gradeLevel);
        generateFormEndSelectDiv();
        generateFormStartSelectDiv("Select Parent", "parentID");
			chooseParentOption($mysqli);
        generateFormEndSelectDiv();
        generateFormButton("addClassButton", "Add Student to Parent");
    generateFormEnd();
}

function chooseParentOption($mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT userID, userFirstName, userLastName FROM users WHERE isParent"))
	{
		if ($stmt->execute())
		{
			$stmt->bind_result($userID, $userFirstName, $userLastName);
			$stmt->store_result();

			while ($stmt->fetch())
			{
                generateFormOption($userID, "$userLastName, $userFirstName");
			}
		}
	}
}

function chooseStudentOption($mysqli, $gradeLevel)
{
	if ($stmt = $mysqli->prepare("SELECT userID, userFirstName, userLastName FROM users WHERE isStudent AND studentGradeLevel = ?"))
	{
		$stmt->bind_param('i', $gradeLevel);

		if ($stmt->execute())
		{
			$stmt->bind_result($userID, $userFirstName, $userLastName);
			$stmt->store_result();

			while ($stmt->fetch())
			{
                generateFormOption($userID, "$userLastName, $userFirstName");
			}
		}
	}
}

function getGradeLevelForm()
{
    generateFormStart("", "post"); 
        generateFormStartSelectDiv("Select Grade Level", "gradeLevel");
            for ($i = 1; $i <= 12; $i++)
            {
                generateFormOption($i, $i);
            }
        generateFormEndSelectDiv();
        generateFormButton("addClassButton", "Choose Grade Level");
    generateFormEnd();
}

?>
