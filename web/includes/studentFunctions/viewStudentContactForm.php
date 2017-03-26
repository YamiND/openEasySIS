<?php

if (isset($_POST['teacherID']))
{
	$_SESSION['teacherID'] = $_POST['teacherID'];
}

if (isset($_POST['choice']))
{
	$_SESSION['choice'] = $_POST['choice'];
}

if (isset($_POST['changeChoice']))
{
	unset($_SESSION['choice']);
	unset($_SESSION['teacherID']);
}

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isStudent($mysqli)))
    {
        viewContactForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewContactForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
                        // Call Session Message code and Panel Heading here
						displayPanelHeading("Contact Form");
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#createAnnoucement" data-toggle="tab">Contact Form</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="announcement">
                                <br>
            ';

							if (!isset($_SESSION['choice']))
							{
								// Have user choose to email principal or teacher
								choiceForm();
							}
							else
							{
								switch ($_SESSION['choice'])
								{
									case "1": 
										emailPrincipalForm($mysqli);
									break;

									case "2":

										if (!isset($_SESSION['teacherID']))
										{
											chooseTeacherForm($mysqli);
										}
										else
										{
											emailTeacherForm($_SESSION['teacherID'], $mysqli);
										}
										
									break;


									default:
										unset($_SESSION['choice']);
									break;
								}

								echo "<br>";
                                generateFormStart("", "post");
                                    generateFormButton("changeChoice", "Change Choice");
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

function emailTeacherForm($teacherID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT userEmail FROM users WHERE userID = ?"))
	{
		$stmt->bind_param('i', $teacherID);

		if ($stmt->execute())
		{
			$stmt->bind_result($teacherEmail);
			$stmt->store_result();

			$stmt->fetch();
		}
	}

	$userEmail = getUserEmail($_SESSION['userID'], $mysqli);

	generateFormStart("../includes/studentFunctions/processStudentContact", "post");
		generateFormInputDiv("Email To: ", "email", "emailTo", "$teacherEmail", "disabled", NULL, NULL, "Email To");
		generateFormInputDiv("Email From: ", "email", "emailFrom", "$userEmail", "disabled", NULL, NULL, "Email From");
		generateFormTextAreaDiv("Email Body", "emailBody", "5");
		generateFormButton("sendEmailButton", "Send Email");
	generateFormEnd();

}

function chooseTeacherForm($mysqli)
{
	// selects for which year they want to look at. may be used? 
	$studentID = $_SESSION['userID'];

	$teacherArray = [];

	if ($stmt = $mysqli->prepare("SELECT classes.classTeacherID FROM studentClassIDs INNER JOIN (classes) ON (classes.classID = studentClassIDs.classID AND studentClassIDs.studentID = ?)"))
	{
		$stmt->bind_param('i', $studentID);

		if ($stmt->execute())
		{
			$stmt->bind_result($classTeacherID);
			$stmt->store_result();

			while ($stmt->fetch())
			{
				array_push($teacherArray, "$classTeacherID");
			}	
		}
	}

	// We have the list of teacher IDs, now to get rid of the repeating numbers
	$uniqueTeacher = array_unique($teacherArray);

	generateFormStart("", "post");
		generateFormStartSelectDiv("Select Teacher", "teacherID");

		foreach ($uniqueTeacher as $teacher)
		{
			generateFormOption($teacher, getUserName($teacher, $mysqli));
		}
	
		generateFormEndSelectDiv();
		generateFormButton(NULL, "Choose Teacher");
	generateFormEnd();

}

function choiceForm()
{
	generateFormStart("", "post");
		generateFormStartSelectDiv("Choose who to Email", "choice");
			generateFormOption("1", "Email Principal");
			generateFormOption("2", "Email Teacher");
		generateFormEndSelectDiv();
		generateFormButton(NULL, "Choose Option");
	generateFormEnd();
}

function emailPrincipalForm($mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT userEmail FROM users WHERE isPrincipal LIMIT 1"))
	{
		if ($stmt->execute())
		{
			$stmt->bind_result($principalEmail);
			$stmt->store_result();

			$stmt->fetch();
		}
	}

	$userEmail = getUserEmail($_SESSION['userID'], $mysqli);

	generateFormStart("../includes/studentFunctions/processStudentContact", "post");
		generateFormInputDiv("Email To: ", "email", "emailTo", "$principalEmail", "disabled", NULL, NULL, "Email To");
		generateFormInputDiv("Email From: ", "email", "emailFrom", "$userEmail", "disabled", NULL, NULL, "Email From");
		generateFormTextAreaDiv("Email Body", "emailBody", "5");
		generateFormButton("sendEmailButton", "Send Email");
	generateFormEnd();

}

?>
