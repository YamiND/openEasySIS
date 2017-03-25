<?php

if (isset($_POST['parentID']) && !empty($_POST['parentID']))
{
	$_SESSION['parentID'] = $_POST['parentID'];
}

if (isset($_POST['changeParent']))
{
	unset($_SESSION['parentID']);
}

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
    {
        viewRemoveParentChildForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewRemoveParentChildForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						// Call Session Message code and Panel Heading here
                        displayPanelHeading("Remove Child from Parent");
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#addClass" data-toggle="tab">Remove Child from Parent</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="addClass">
                                    <br>
        ';
							if (!isset($_SESSION['parentID']))
							{
								chooseParentForm($mysqli);
							}
							else
							{
								chooseStudentForm($mysqli);

								echo "<br>";
						 		generateFormStart("", "post");  
									generateFormButton("changeParent", "Change Parent");
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

function chooseParentForm($mysqli)
{
    generateFormStart("", "post"); 
        generateFormStartSelectDiv("Select Parent", "parentID");
			chooseParentOption($mysqli);
        generateFormEndSelectDiv();
        generateFormButton(NULL, "Choose Parent");
    generateFormEnd();
}

function chooseStudentForm($mysqli)
{
	$parentID = $_SESSION['parentID'];

	echo "<h5>Parent Name: " . getUserName($parentID, $mysqli) . "</h5>";
	echo "<br>";
    generateFormStart("../includes/adminFunctions/removeParentChild", "post"); 
		generateFormHiddenInput("parentID", "$parentID");
        generateFormStartSelectDiv("Select Student", "studentID");
			chooseStudentOption($mysqli, $parentID);
        generateFormEndSelectDiv();
        generateFormButton(NULL, "Remove student from Parent");
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
		else
		{
       		generateFormOption(NULL, "No parents", "disabled");
		}
	}
}

function chooseStudentOption($mysqli, $parentID)
{
	if ($stmt = $mysqli->prepare("SELECT userID, userFirstName, userLastName FROM users, studentParentIDs WHERE studentID = userID AND parentID = ?"))
	{
		$stmt->bind_param('i', $parentID);

		if ($stmt->execute())
		{
			$stmt->bind_result($userID, $userFirstName, $userLastName);
			$stmt->store_result();

			if ($stmt->num_rows > 0)
			{
				while ($stmt->fetch())
				{
					generateFormOption($userID, "$userLastName, $userFirstName");
				}
			}
			else
			{
       			generateFormOption(NULL, "No students for parent", "disabled", "selected");
			}
		}
		else
		{
       		generateFormOption(NULL, "No students for parent", "disabled", "selected");
		}
	}
	else
	{
       	generateFormOption(NULL, "No students for parent", "disabled", "selected");
	}
}

?>
