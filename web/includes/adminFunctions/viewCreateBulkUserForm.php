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
			<div class="col-lg-12">
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

echo "<br>";
    echo "<a href=\"https://support.office.com/en-us/article/Import-or-export-text-txt-or-csv-files-5250ac4c-663c-47ce-937b-339e391393ba\">To learn how to export a file from Excel as a CSV, please click here</a>";
   echo "<br>
    <h5>The format for the Student's CSV should be this: </h5>

    <p>&nbsp;&nbsp;&nbsp;&nbsp;Student Email,Student First Name,Student Last Name,Grade Level</p>
    <p>A sample CSV is listed below: </p>
    <h5>&nbsp;&nbsp;&nbsp;&nbsp;ltorvalds@lssu.edu,Linus,Torvalds,11</h5> 
    <h5>&nbsp;&nbsp;&nbsp;&nbsp;sballmer@lssu.edu,Steve,Ballmer,10</h5> 
    <h5>&nbsp;&nbsp;&nbsp;&nbsp;sjobs@lssu.edu,Steve,Jobs,12</h5> 
";
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
echo "<br>";
    echo "<a href=\"https://support.office.com/en-us/article/Import-or-export-text-txt-or-csv-files-5250ac4c-663c-47ce-937b-339e391393ba\">To learn how to export a file from Excel as a CSV, please click here</a>";
   echo "<br>
    <h5>The format for the Parent's CSV should be this: </h5>

    <p>&nbsp;&nbsp;&nbsp;&nbsp;Parent Email,Parent First Name,Parent Last Name,Parent Home Address,Parent Phone Number</p>
    <p>A sample CSV is listed below: </p>
    <h5>&nbsp;&nbsp;&nbsp;&nbsp;ltorvalds@lssu.edu,Linus,Torvalds,850. West Easterday Avenue Sault Ste. Marie MI 49783,906-635-6677</h5> 
    <h5>&nbsp;&nbsp;&nbsp;&nbsp;sballmer@lssu.edu,Steve,Ballmer,850. North Easterday Avenue Sault Ste. Marie MI 49783,906-635-5555</h5> 
    <h5>&nbsp;&nbsp;&nbsp;&nbsp;sjobs@lssu.edu,Steve,Jobs,850. South Easterday Avenue Sault Ste. Marie MI 49783,906-635-0000</h5> 
";
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
echo "<br>";
    echo "<a href=\"https://support.office.com/en-us/article/Import-or-export-text-txt-or-csv-files-5250ac4c-663c-47ce-937b-339e391393ba\">To learn how to export a file from Excel as a CSV, please click here</a>";
   echo "<br>
    <h5>The format for the Teacher's CSV should be this: </h5>

    <p>&nbsp;&nbsp;&nbsp;&nbsp;Teacher Email,Teacher First Name,Teacher Last Name,modClassList,viewAllGrades</p>

	<h5>The last 2 fields are explained below</h5>
	<p>modClassList can be either a 1 or a 0. This allows teachers to add students to their class. If it is a 0, the teacher can not add students to their class. If it is a 1, the teacher can add students to their class</p>
	<p>viewAllGrades can be either a 1 or a 0. This allows teachers to view all grades for students. If it is a 0, the teacher can only view grades for their class. If it is a 1, the teacher can view all student grades</p>
	<br>
    <p>A sample CSV is listed below: </p>
    <h5>&nbsp;&nbsp;&nbsp;&nbsp;ltorvalds@lssu.edu,Linus,Torvalds,0,0</h5> 
    <h5>&nbsp;&nbsp;&nbsp;&nbsp;sballmers@lssu.edu,Steve,Ballmer,0,1</h5> 
    <h5>&nbsp;&nbsp;&nbsp;&nbsp;sjobs@lssu.edu,Steve,Jobs,1,0</h5> 
";
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
echo "<br>";
    echo "<a href=\"https://support.office.com/en-us/article/Import-or-export-text-txt-or-csv-files-5250ac4c-663c-47ce-937b-339e391393ba\">To learn how to export a file from Excel as a CSV, please click here</a>";
   echo "<br>
    <h5>The format for the School Admin's CSV should be this: </h5>

    <p>&nbsp;&nbsp;&nbsp;&nbsp;School Admin Email,School Admin First Name,School Admin Last Name</p>

    <p>A sample CSV is listed below: </p>
    <h5>&nbsp;&nbsp;&nbsp;&nbsp;ltorvalds@lssu.edu,Linus,Torvalds</h5> 
    <h5>&nbsp;&nbsp;&nbsp;&nbsp;sballmers@lssu.edu,Steve,Ballmer</h5> 
    <h5>&nbsp;&nbsp;&nbsp;&nbsp;sjobs@lssu.edu,Steve,Jobs</h5> 
";
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
echo "<br>";
    echo "<a href=\"https://support.office.com/en-us/article/Import-or-export-text-txt-or-csv-files-5250ac4c-663c-47ce-937b-339e391393ba\">To learn how to export a file from Excel as a CSV, please click here</a>";
   echo "<br>
    <h5>The format for the Admin's CSV should be this: </h5>

    <p>&nbsp;&nbsp;&nbsp;&nbsp;Admin Email,Admin First Name,Admin Last Name</p>

    <p>A sample CSV is listed below: </p>
    <h5>&nbsp;&nbsp;&nbsp;&nbsp;ltorvalds@lssu.edu,Linus,Torvalds</h5> 
    <h5>&nbsp;&nbsp;&nbsp;&nbsp;sballmers@lssu.edu,Steve,Ballmer</h5> 
    <h5>&nbsp;&nbsp;&nbsp;&nbsp;sjobs@lssu.edu,Steve,Jobs</h5> 
";
}
?>
