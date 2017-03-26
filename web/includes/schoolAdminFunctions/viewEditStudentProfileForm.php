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
						 displayPanelHeading("Edit Student Profile");
    echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#modifyClass" data-toggle="tab">Edit Student Profile</a>
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
								editUserForm($_SESSION['editUserID'], $mysqli);
	
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

function editUserForm($userID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT userEmail, userFirstName, userLastName, studentGradeLevel FROM users WHERE userID = ? AND isStudent"))
	{
		$stmt->bind_param('i', $userID);

		if ($stmt->execute())
		{
			$stmt->bind_result($userEmail, $userFirstName, $userLastName, $studentGradeLevel);

			$stmt->store_result();

			$stmt->fetch();

			$isStudentInput = '<div class="form-group" id="studentGradeField">
				<label>Student Grade Level</label> 
				<select class="form-control" name="studentGradeLevel">';

				for ($i = 1; $i <= 12; $i++)
			    { 
					if ($i == $studentGradeLevel)
					{
			        	$isStudentInput .= '<option value="' . $i . '" selected>' . $i . '</option>';
					}
					else
					{
			        	$isStudentInput .= '<option value="' . $i . '">' . $i . '</option>';
					}
			    }  
				$isStudentInput .= '</select></div>';

echo <<<EOF
		<form action="../includes/schoolAdminFunctions/editStudentProfile" method="POST">

		  <input type="hidden" name="userID" value="$userID">
		  <div class="form-group">
			<label>User's Email</label>
			<input class="form-control" type="email" placeholder="User's Email" name="userEmail" value="$userEmail">
		  </div>
		  
		  <div class="form-group">
			<label>User's First Name</label>
			<input class="form-control" type="text" placeholder="User's First Name" name="userFirstName" value="$userFirstName">
		  </div>
		  
		  <div class="form-group">
			<label>User's Last Name</label>
			<input class="form-control" type="text" placeholder="User's Last Name" name="userLastName" value="$userLastName">
		  </div>

			$isStudentInput

			 <button name="editUserButton" type="submit" class="btn btn-default">Edit Student</button>
		</form>
EOF;
		}	
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
