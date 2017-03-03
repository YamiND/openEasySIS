<?php

function checkPermissions($mysqli)
{
    if (login_check($mysqli) == true)
    {
		viewCreateBulkUserForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewCreateBulkUserForm($mysqli)
{

	echo '
			<div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
                        // Call Session Message code and Panel Heading here
						displayPanelHeading("Create Users (CSV)");
	echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#isStudent" data-toggle="tab">Students</a>
                                </li>
                                <li><a href="#isParent" data-toggle="tab">Parents</a>
                                </li>
                                <li><a href="#isTeacher" data-toggle="tab">Teachers</a>
                                </li>
                                <li><a href="#isSchoolAdmin" data-toggle="tab">School Admins</a>
                                </li>
                                <li><a href="#isAdmin" data-toggle="tab">Admins</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="isStudent">
								';
									echo getStudentUploadForm();
						echo '
                                </div>
                                <div class="tab-pane fade" id="isParent">
							';
									echo getParentUploadForm();
						echo '
                                </div>
                                <div class="tab-pane fade" id="isTeacher">
							';
									echo getTeacherUploadForm();
						echo '
                                </div>
                                <div class="tab-pane fade" id="isSchoolAdmin">
							';
									echo getSchoolAdminUploadForm();
						echo '
                                </div>
                                <div class="tab-pane fade" id="isAdmin">
							';
									echo getAdminUploadForm();
						echo '
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
						<p>*If you want users to have multiple roles, please create them manually</p>
                </div>
';
}

function getStudentUploadForm()
{
    generateFormStart("../includes/adminFunctions/createBulkUser", "post", "multipart/form-data"); 
	echo '<h4>Upload Student CSV</h4>';
	echo '<input type="hidden" name="isStudent" value="1" />';
	echo '<input type="file" name="csvFile" id="file" />';
	echo "<br>";
        generateFormButton("uploadCSV", "Upload CSV and Create Students");
    generateFormEnd();
}

function getParentUploadForm()
{
    generateFormStart("../includes/adminFunctions/createBulkUser", "post", "multipart/form-data"); 
	echo '<h4>Upload Parent CSV</h4>';
	echo '<input type="hidden" name="isParent" value="1" />';
	echo '<input type="file" name="csvFile" id="file" />';
	echo "<br>";
        generateFormButton("uploadCSV", "Upload CSV and Create Parents");
    generateFormEnd();
}

function getTeacherUploadForm()
{
    generateFormStart("../includes/adminFunctions/createBulkUser", "post", "multipart/form-data"); 
	echo '<h4>Upload Teacher CSV</h4>';
	echo '<input type="hidden" name="isTeacher" value="1" />';
	echo '<input type="file" name="csvFile" id="file" />';
	echo "<br>";
        generateFormButton("uploadCSV", "Upload CSV and Create Teachers");
    generateFormEnd();
}

function getSchoolAdminUploadForm()
{
    generateFormStart("../includes/adminFunctions/createBulkUser", "post", "multipart/form-data"); 
	echo '<h4>Upload School Admin CSV</h4>';
	echo '<input type="hidden" name="isSchoolAdmin" value="1" />';
	echo '<input type="file" name="csvFile" id="file" />';
	echo "<br>";
        generateFormButton("uploadCSV", "Upload CSV and Create School Administrators");
    generateFormEnd();
}

function getAdminUploadForm()
{
    generateFormStart("../includes/adminFunctions/createBulkUser", "post", "multipart/form-data"); 
	echo '<h4>Upload Admin CSV</h4>';
	echo '<input type="hidden" name="isAdmin" value="1" />';
	echo '<input type="file" name="csvFile" id="file" />';
	echo "<br>";
        generateFormButton("uploadCSV", "Upload CSV and Create School Administrators");
    generateFormEnd();
}
?>
