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
    if ((login_check($mysqli) == true) && (isAdmin($mysqli) || isSchoolAdmin($mysqli)))
    {
        viewMassEmailForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewMassEmailForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
                        // Call Session Message code and Panel Heading here
						displayPanelHeading("Mass Email Form");
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#createAnnoucement" data-toggle="tab">Mass Email Form</a>
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
								massEmailForm($_SESSION['choice'], $mysqli);

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

function massEmailForm($choice, $mysqli)
{
	$userEmail = getUserEmail($_SESSION['userID'], $mysqli);

	switch ($choice)
	{
		case "0":
			$emailTo = "Email Everyone";
		break;

		case "1":
			$emailTo = "Email all Teachers";
		break;

		case "2":
			$emailTo = "Email all Parents";
		break;

		case "3":
			$emailTo = "Email all Students";
		break;

		case "4":
			$emailTo = "Email Elementary Parents/Students";
		break;

		case "5":
			$emailTo = "Email High School Parents/Students";
		break;

		case "6":
			$emailTo = "Email Elementary Teachers";
		break;

		case "7":
			$emailTo = "Email High School Teachers";
		break;
	}


	generateFormStart("../includes/schoolAdminFunctions/processMassEmail", "post");
		generateFormHiddenInput("choice", "$choice");
		generateFormInputDiv("Email To: ", "email", "emailTo", "$emailTo", "disabled", NULL, NULL, "Email To");
		generateFormInputDiv("Email From: ", "email", "emailFrom", "$userEmail", "disabled", NULL, NULL, "Email From");
		generateFormTextAreaDiv("Email Body", "emailBody", "5");
		generateFormButton("sendEmailButton", "Send Email");
	generateFormEnd();
}

function choiceForm()
{
	generateFormStart("", "post");
		generateFormStartSelectDiv("Choose who to Email", "choice");
			generateFormOption("0", "Email Everyone");
			generateFormOption("1", "Email all Teachers");
			generateFormOption("2", "Email all Parents");
			generateFormOption("3", "Email all Students");
			generateFormOption("4", "Email Elementary Parents/Students");
			generateFormOption("5", "Email High School Parents/Students");
			generateFormOption("6", "Email Elementary Teachers");
			generateFormOption("7", "Email High School Teachers");
		generateFormEndSelectDiv();
		generateFormButton(NULL, "Choose Who to Email");
	generateFormEnd();
}

?>
