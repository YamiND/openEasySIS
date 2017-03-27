<?php

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
    {
        viewDeleteUserForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}


function viewDeleteUserForm($mysqli)
{
    echo '
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						displayPanelHeading("Delete User");
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#administrator" data-toggle="tab">Delete User</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="administrator">
                                    <br>
            ';
								deleteUserForm($mysqli);
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

function deleteUserForm($mysqli)
{
	echo "<h4>Deleting a user should not be taken lightly. This will completely wipe them out of the system</h4>";

	generateFormStart("../includes/adminFunctions/deleteUser", "post");
        generateFormStartSelectDiv("Choose who to delete", "userID");
		if ($stmt = $mysqli->prepare("SELECT userID, userEmail, userFirstName, userLastName FROM users"))
		{
			if ($stmt->execute())
			{
				$stmt->bind_result($userID, $userEmail, $userFirstName, $userLastName);
				$stmt->store_result();
	
				while ($stmt->fetch())
				{
					generateFormOption("$userID", "$userFirstName, $userLastName - $userEmail");
				}
			}
		}
	generateFormEndSelectDiv();
        generateFormButton(NULL, "Delete User");
    generateFormEnd();
}

?>
