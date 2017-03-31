<?php

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
    {
        viewAddClassForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewAddClassForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						// Call Session Message code and Panel Heading here
                        displayPanelHeading("Add a Class");
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#addClass" data-toggle="tab">Add a Class</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="addClass">
                                    <br>
        ';
                                    getAddClassForm($mysqli);
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

function getAddClassForm($mysqli)
{
    generateFormStart("../includes/adminFunctions/addClass", "post"); 
        generateFormInputDiv("Class Name", "text", "className", NULL, NULL, NULL, NULL, "Class Name");
        generateFormInputDiv("Class Start Time", "time", "classStartTime", NULL, NULL, "07:00:00", NULL, NULL);
        generateFormInputDiv("Class End Time", "time", "classEndTime", NULL, NULL, NULL, "23:00:00", NULL);
        generateFormStartSelectDiv("Class Grade Level", "classGradeLevel");
            for ($i = 1; $i <= 12; $i++)
            {
                generateFormOption($i, $i);
            }
        generateFormEndSelectDiv();
        generateFormStartSelectDiv("Class Teacher", "classTeacherID");
            if ($stmt = $mysqli->prepare("SELECT userID, userFirstName, userLastName FROM users WHERE isTeacher"))
            {
                $stmt->execute();
                $stmt->bind_result($teacherID, $teacherFirstName, $teacherLastName);
                $stmt->store_result();

                if ($stmt->num_rows == 0)
                {
                    generateFormOption(NULL, "No teachers", "disabled");
                }
                else
                {
                    while ($stmt->fetch())
                    {
                        generateFormOption($teacherID, "$teacherLastName, $teacherFirstName");
                    }
                }
            }
        generateFormEndSelectDiv();
        generateFormButton("addClassButton", "Add Class");
    generateFormEnd();
}

?>
